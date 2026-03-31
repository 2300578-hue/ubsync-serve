<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start pt-4" x-data="{ 
    searchQuery: '', 
    selectedCategory: 'All',
    paymentMethod: 'Cash',
    customerName: '',
    selectedTable: '',
    cart: [],
    
    products: [
        { id: 1, name: 'Burger Steak', price: 120, stock: 15, img: 'burgersteak.png', cat: 'Main Course' },
        { id: 2, name: 'Carbonara', price: 160, stock: 8, img: 'carbonara.png', cat: 'Main Course' },
        { id: 3, name: 'Mozzarella Sticks', price: 95, stock: 5, img: 'mozarella.png', cat: 'Appetizer' },
        { id: 4, name: 'Leche Flan', price: 50, stock: 12, img: 'lecheflan.png', cat: 'Desserts' },
        { id: 5, name: 'Iced Tea', price: 40, stock: 0, img: 'icetea.png', cat: 'Beverages' }
    ],

    get filteredProducts() {
        return this.products.filter(p => {
            const matchesSearch = p.name.toLowerCase().includes(this.searchQuery.toLowerCase());
            const matchesCat = this.selectedCategory === 'All' || p.cat === this.selectedCategory;
            return matchesSearch && matchesCat;
        });
    },

    get cartTotal() {
        return this.cart.reduce((sum, item) => sum + (item.price * (item.qty || 1)), 0);
    },

    addToCart(product, addonValue) {
        let addonPrice = 0;
        let addonName = 'Default';

        if (addonValue && addonValue.includes('|')) {
            const parts = addonValue.split('|');
            addonName = parts[0];
            addonPrice = parseInt(parts[1]) || 0;
        }

        let cartId = product.id + '-' + addonName;
        let foundIndex = this.cart.findIndex(i => i.cartId === cartId);

        if (foundIndex > -1) { 
            this.cart = this.cart.map((item, index) => {
                if(index === foundIndex) {
                    return { ...item, qty: item.qty + 1 };
                }
                return item;
            });
        } else { 
            this.cart = [...this.cart, {
                ...product, 
                cartId: cartId,
                qty: 1, 
                addonName: addonName,
                price: product.price + addonPrice
            }]; 
        }
    },

    removeFromCart(indexToRemove) {
        this.cart = this.cart.filter((_, index) => index !== indexToRemove);
    }
}">
    
    <div class="lg:col-span-7 xl:col-span-8 flex flex-col h-full">
        <div class="mb-6 flex flex-col items-center w-full">
            <div class="relative w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="text" x-model="searchQuery" placeholder="Search menu items..." 
                       class="w-full pl-16 pr-6 py-5 bg-white border border-gray-200 rounded-2xl text-base font-bold outline-none shadow-sm focus:ring-4 focus:ring-red-900/10 focus:border-red-800 transition-all placeholder:font-medium placeholder:text-gray-400">
            </div>

            <div class="flex justify-start lg:justify-center gap-3 w-full mt-5 overflow-x-auto pb-4 scrollbar-hide px-1">
                <template x-for="cat in ['All', 'Main Course', 'Appetizer', 'Desserts', 'Beverages']">
                    <button x-on:click="selectedCategory = cat" 
                            :class="selectedCategory === cat ? 'bg-[#800000] text-white shadow-md border-[#800000]' : 'bg-white text-gray-500 border-gray-200 hover:border-gray-300 hover:bg-gray-50'"
                            class="px-6 py-3.5 rounded-2xl text-sm font-black uppercase border whitespace-nowrap transition-all tracking-wide" 
                            x-text="cat"></button>
                </template>
            </div>
        </div>

        <div class="grid grid-cols-2 xl:grid-cols-3 gap-6">
            <template x-for="p in filteredProducts" :key="p.id">
                <div x-data="{ selectedAddon: 'Default|0' }" 
                     class="bg-white p-5 flex flex-col relative border border-gray-200 rounded-[2rem] shadow-sm hover:shadow-lg transition-all duration-300"
                     :class="p.stock <= 0 ? 'opacity-60 grayscale' : ''">
                    
                    <div class="absolute top-6 right-6 z-10 flex flex-col gap-2 items-end">
                        <template x-if="p.stock > 0 && p.stock <= 9">
                            <span class="bg-orange-500 text-white text-[10px] font-black px-3 py-1.5 rounded-lg uppercase shadow-sm tracking-wider">Low Stock</span>
                        </template>
                        <template x-if="p.stock <= 0">
                            <span class="bg-gray-800 text-white text-[10px] font-black px-3 py-1.5 rounded-lg uppercase shadow-sm tracking-wider">Sold Out</span>
                        </template>
                    </div>

                    <div class="w-full h-40 mb-4 overflow-hidden rounded-[1.5rem] bg-gray-50 border border-gray-100 relative group">
                        <img :src="'/img/' + p.img" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" x-on:error="$el.src='https://placehold.co/400x300?text=No+Image'">
                    </div>
                    
                    <div class="mb-4 flex-grow">
                        <h4 class="text-base font-black text-gray-800 uppercase tracking-tight leading-tight mb-1" x-text="p.name"></h4>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-black text-[#800000]" x-text="'₱' + p.price.toFixed(2)"></span>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider" x-text="'Stock: ' + p.stock"></span>
                        </div>
                    </div>

                    <div class="mt-auto space-y-3 pt-4 border-t border-gray-100">
                        <div>
                            <select x-model="selectedAddon" :disabled="p.stock <= 0" class="w-full text-sm font-bold bg-gray-50 border border-gray-200 rounded-xl p-3.5 outline-none cursor-pointer text-gray-700 focus:border-[#800000] transition-colors disabled:cursor-not-allowed appearance-none">
                                <option value="Default|0">Default Serving</option>
                                <option disabled>--- Add-ons ---</option>
                                <option value="Extra Rice|20">Extra Rice (+₱20)</option>
                                <option value="Extra Sauce|10">Extra Sauce (+₱10)</option>
                                <option value="Add Egg|15">Add Egg (+₱15)</option>
                                <option value="Add Cheese|15">Add Cheese (+₱15)</option>
                                <option disabled>--- Options ---</option>
                                <option value="No Onions|0">No Onions</option>
                                <option value="Less Spicy|0">Less Spicy</option>
                            </select>
                        </div>
                        
                        <button x-on:click.prevent="if(p.stock > 0) { $data.addToCart(p, selectedAddon); selectedAddon = 'Default|0'; }" 
                                :disabled="p.stock <= 0"
                                class="w-full py-4 bg-[#800000] text-white rounded-xl text-sm font-black uppercase tracking-[0.1em] active:bg-red-900 active:scale-[0.98] transition-all shadow-md disabled:bg-gray-300 disabled:shadow-none disabled:active:scale-100 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div class="lg:col-span-5 xl:col-span-4 sticky top-4">
        <div class="bg-white p-5 border-t-[8px] border-[#800000] shadow-xl flex flex-col h-[calc(100vh-90px)] rounded-[2rem] overflow-hidden">
            
            <div class="grid grid-cols-2 gap-4 mb-4 shrink-0 bg-gray-50 p-3.5 rounded-2xl border border-gray-100">
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest block pl-1">Table No.</label>
                    <input type="text" x-model="selectedTable" placeholder="00" class="w-full p-2.5 bg-white rounded-xl border border-gray-200 font-black text-sm text-center outline-none focus:border-[#800000] transition-colors">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest block pl-1">Customer</label>
                    <input type="text" x-model="customerName" placeholder="Name" class="w-full p-2.5 bg-white rounded-xl border border-gray-200 font-black text-sm outline-none focus:border-[#800000] transition-colors">
                </div>
            </div>

            <div class="flex justify-between items-end mb-3 border-b border-gray-100 pb-2 shrink-0">
                <h3 class="font-black text-sm uppercase text-gray-800 tracking-[0.15em]">Current Order</h3>
                <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg" x-text="cart.length + ' items'"></span>
            </div>

            <div class="space-y-3 mb-4 overflow-y-auto flex-1 min-h-[150px] scrollbar-hide pr-1 relative">
                
                <div x-show="cart.length === 0" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
                    <i class="fas fa-shopping-basket text-4xl text-gray-300 mb-3"></i>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">Cart is empty</p>
                </div>

                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex justify-between items-center bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm relative z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-red-50 text-[#800000] border border-red-100 flex items-center justify-center text-sm font-black shrink-0" x-text="item.qty + 'x'"></div>
                            <div>
                                <p class="text-sm font-black uppercase text-gray-800 tracking-tight leading-tight" x-text="item.name"></p>
                                <p x-show="item.addonName !== 'Default'" class="text-[10px] text-orange-600 font-bold uppercase mt-0.5" x-text="'+ ' + item.addonName"></p>
                                <p class="text-xs text-gray-500 font-bold mt-1" x-text="'₱' + (item.price * item.qty).toFixed(2)"></p>
                            </div>
                        </div>
                        <button x-on:click="removeFromCart(index)" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-red-500 bg-gray-50 hover:bg-red-50 rounded-lg transition-all active:scale-90">
                            <i class="fa-solid fa-trash-can text-base"></i>
                        </button>
                    </div>
                </template>
            </div>
            
            <div class="space-y-4 pt-3 border-t border-gray-100 shrink-0 bg-white">
                <div class="grid grid-cols-3 gap-2">
                    <button x-on:click="paymentMethod = 'Cash'" :class="paymentMethod === 'Cash' ? 'border-[#800000] bg-red-50 text-[#800000]' : 'border-gray-200 text-gray-400'" class="border-2 p-3 rounded-xl flex flex-col items-center transition-all bg-white active:scale-95">
                        <i class="fa-solid fa-money-bill-1 text-base mb-1"></i>
                        <span class="text-[10px] font-black uppercase tracking-wider">Cash</span>
                    </button>
                    <button x-on:click="paymentMethod = 'GCash'" :class="paymentMethod === 'GCash' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-400'" class="border-2 p-3 rounded-xl flex flex-col items-center transition-all bg-white active:scale-95">
                        <i class="fa-solid fa-mobile-screen text-base mb-1"></i>
                        <span class="text-[10px] font-black uppercase tracking-wider">GCash</span>
                    </button>
                    <button x-on:click="paymentMethod = 'Card'" :class="paymentMethod === 'Card' ? 'border-gray-800 bg-gray-100 text-gray-900' : 'border-gray-200 text-gray-400'" class="border-2 p-3 rounded-xl flex flex-col items-center transition-all bg-white active:scale-95">
                        <i class="fa-solid fa-credit-card text-base mb-1"></i>
                        <span class="text-[10px] font-black uppercase tracking-wider">Card</span>
                    </button>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <div class="flex justify-between items-center text-xs font-bold text-gray-500 uppercase mb-2">
                        <span>Subtotal</span>
                        <span x-text="'₱' + cartTotal.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between items-end pt-3 border-t border-dashed border-gray-300">
                        <span class="text-xs font-black text-gray-800 uppercase tracking-widest leading-none mb-1">Total Due</span>
                        <span class="text-3xl font-black text-[#800000] leading-none tracking-tight" x-text="'₱' + cartTotal.toFixed(2)"></span>
                    </div>
                </div>
                
                <button type="button" 
                        :disabled="cart.length === 0"
                        class="w-full py-4 bg-[#800000] text-white rounded-xl font-black text-sm uppercase shadow-lg tracking-[0.15em] active:bg-red-900 active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100 disabled:shadow-none flex justify-center items-center gap-2">
                    Complete Order <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>