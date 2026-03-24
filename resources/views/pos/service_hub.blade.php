<div class="grid grid-cols-12 gap-8 items-start pt-4" x-data="{ 
    searchQuery: '', 
    selectedCategory: 'All',
    discountType: 'None',
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

    get discountedTotal() {
        let total = this.cartTotal || 0;
        if(this.discountType === 'Senior/PWD') total *= 0.8;
        return total;
    },

    // In-update ang addToCart para tumanggap ng addonValue
    addToCart(product, addonValue) {
        let addonPrice = 0;
        let addonName = 'Default';

        // Hatiin ang value (halimbawa: 'Extra Rice|20')
        if (addonValue && addonValue.includes('|')) {
            const parts = addonValue.split('|');
            addonName = parts[0];
            addonPrice = parseInt(parts[1]) || 0;
        }

        // Gagawa ng unique ID (Product ID + Addon) para hindi mag-merge ang magkaibang customization
        let cartId = product.id + '-' + addonName;
        let found = this.cart.find(i => i.cartId === cartId);

        if (found) { 
            found.qty++; 
        } else { 
            this.cart.push({
                ...product, 
                cartId: cartId,
                qty: 1, 
                addonName: addonName,
                price: product.price + addonPrice // Dito ina-add ang presyo sa total
            }); 
        }
    },

    removeFromCart(index) {
        this.cart.splice(index, 1);
    }
}">
    <div class="col-span-8">
        <div class="mb-8 flex flex-col items-center">
            <div class="relative w-full max-w-2xl">
                <i class="fa-solid fa-magnifying-glass absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input type="text" x-model="searchQuery" placeholder="Search menu items..." 
                       class="w-full pl-14 pr-6 py-5 bg-white border border-gray-100 rounded-2xl text-xs font-bold outline-none shadow-sm text-center focus:ring-4 focus:ring-red-50/30 transition-all">
            </div>

            <div class="flex justify-center gap-2 w-full mt-6 overflow-x-auto pb-2">
                <template x-for="cat in ['All', 'Main Course', 'Appetizer', 'Desserts', 'Beverages']">
                    <button x-on:click="selectedCategory = cat" 
                            :class="selectedCategory === cat ? 'bg-[#800000] text-white shadow-md' : 'bg-white text-gray-400 border-gray-100'"
                            class="px-6 py-3 rounded-xl text-[10px] font-black uppercase border whitespace-nowrap transition-all" 
                            x-text="cat"></button>
                </template>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <template x-for="p in filteredProducts" :key="p.id">
                <div class="bg-white p-5 flex flex-col relative border border-gray-100 rounded-[2rem] shadow-sm hover:shadow-md transition-all"
                     :class="p.stock <= 0 ? 'opacity-60 grayscale' : ''">
                    
                    <div class="absolute top-4 right-4 z-10">
                        <template x-if="p.stock > 0 && p.stock <= 9">
                            <span class="bg-orange-500 text-white text-[7px] font-black px-2 py-1 rounded-lg uppercase">Low Stock</span>
                        </template>
                        <template x-if="p.stock <= 0">
                            <span class="bg-gray-800 text-white text-[7px] font-black px-2 py-1 rounded-lg uppercase">Sold Out</span>
                        </template>
                    </div>

                    <div class="w-full h-44 mb-4 overflow-hidden rounded-[1.5rem] bg-gray-50">
                        <img :src="'/img/' + p.img" class="w-full h-full object-cover" x-on:error="$el.src='https://via.placeholder.com/200?text=Food'">
                    </div>
                    
                    <div class="mb-4 px-1">
                        <h4 class="text-[14px] font-black text-gray-800 uppercase tracking-tight mb-0.5" x-text="p.name"></h4>
                        <span class="text-[16px] font-black text-[#800000]" x-text="'₱' + p.price"></span>
                        <p class="text-[9px] font-bold text-gray-400 uppercase mt-1" x-text="'Stock: ' + p.stock"></p>
                    </div>

                    <div class="mt-auto space-y-2">
                        <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-1">Customize Order</label>
                        <select :id="'select-' + p.id" class="w-full text-[10px] font-bold bg-gray-50 border-none rounded-xl p-3.5 outline-none cursor-pointer text-gray-700">
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
                        <button x-on:click="if(p.stock > 0) { 
                                    const selectEl = document.getElementById('select-' + p.id);
                                    addToCart(p, selectEl.value); 
                                    selectEl.value = 'Default|0'; 
                                }" 
                                class="w-full py-4 bg-[#800000] text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] active:scale-95 transition-all shadow-sm">
                            Add to Order
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div class="col-span-4 sticky top-4">
        <div class="bg-white p-6 border-t-[10px] border-[#800000] shadow-2xl flex flex-col h-[calc(100vh-40px)] rounded-[2.5rem]">
            <div class="grid grid-cols-2 gap-3 mb-6 shrink-0">
                <div class="space-y-1 text-center">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Table</label>
                    <input type="text" x-model="selectedTable" placeholder="00" class="w-full p-3 bg-gray-50 rounded-xl border-none font-black text-sm text-center outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block ml-1">Customer</label>
                    <input type="text" x-model="customerName" placeholder="Name" class="w-full p-3 bg-gray-50 rounded-xl border-none font-black text-xs outline-none">
                </div>
            </div>

            <h3 class="font-black text-[11px] uppercase text-gray-800 mb-4 tracking-[0.2em] border-b pb-2 shrink-0">Order Summary</h3>

            <div class="space-y-3 mb-4 overflow-y-auto custom-scroll pr-2 flex-grow">
                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-[#800000] flex items-center justify-center text-[10px] font-black text-white" x-text="item.qty"></div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-gray-800 tracking-tight" x-text="item.name"></p>
                                <p x-show="item.addonName !== 'Default'" class="text-[8px] text-orange-600 font-bold uppercase" x-text="'+ ' + item.addonName"></p>
                                <p class="text-[9px] text-gray-400 font-bold" x-text="'₱' + (item.price * item.qty)"></p>
                            </div>
                        </div>
                        <button x-on:click="removeFromCart(index)" class="text-red-200 hover:text-red-500 transition-colors">
                            <i class="fa-solid fa-circle-xmark text-lg"></i>
                        </button>
                    </div>
                </template>
            </div>
            
            <div class="space-y-4 border-t border-dashed border-gray-200 pt-6 shrink-0">
                <div class="flex justify-between items-center bg-gray-50 p-2 rounded-xl">
                    <span class="text-[9px] font-black text-gray-400 uppercase ml-2 tracking-widest">Discount</span>
                    <div class="flex gap-1">
                        <button x-on:click="discountType = 'None'" :class="discountType === 'None' ? 'bg-gray-800 text-white' : 'bg-white text-gray-400'" class="px-3 py-2 rounded-lg text-[8px] font-black uppercase">None</button>
                        <button x-on:click="discountType = 'Senior/PWD'" :class="discountType === 'Senior/PWD' ? 'bg-blue-600 text-white' : 'bg-white text-gray-400'" class="px-3 py-2 rounded-lg text-[8px] font-black uppercase">PWD</button>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <button x-on:click="paymentMethod = 'Cash'" :class="paymentMethod === 'Cash' ? 'border-[#800000] bg-red-50 text-[#800000]' : 'border-gray-100 opacity-60'" class="border-2 p-2 rounded-2xl flex flex-col items-center transition-all">
                        <i class="fa-solid fa-money-bill-1 text-xs mb-1"></i>
                        <span class="text-[7px] font-black uppercase">Cash</span>
                    </button>
                    <button x-on:click="paymentMethod = 'GCash'" :class="paymentMethod === 'GCash' ? 'border-blue-500 bg-blue-50 text-blue-600' : 'border-gray-100 opacity-60'" class="border-2 p-2 rounded-2xl flex flex-col items-center transition-all">
                        <i class="fa-solid fa-mobile-screen text-xs mb-1"></i>
                        <span class="text-[7px] font-black uppercase">GCash</span>
                    </button>
                    <button x-on:click="paymentMethod = 'Card'" :class="paymentMethod === 'Card' ? 'border-gray-800 bg-gray-50 text-gray-800' : 'border-gray-100 opacity-60'" class="border-2 p-2 rounded-2xl flex flex-col items-center transition-all">
                        <i class="fa-solid fa-credit-card text-xs mb-1"></i>
                        <span class="text-[7px] font-black uppercase">Card</span>
                    </button>
                </div>

                <div class="space-y-1">
                    <template x-if="discountType === 'Senior/PWD'">
                        <div class="flex justify-between items-center text-[9px] font-bold text-blue-600 uppercase px-1">
                            <span>PWD Discount (20%)</span>
                            <span x-text="'- ₱' + (cartTotal * 0.2).toFixed(2)"></span>
                        </div>
                    </template>
                    <div class="flex justify-between items-end px-1 pt-2">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Total Due</span>
                        <span class="text-3xl font-black text-[#800000] leading-none" x-text="'₱' + discountedTotal.toLocaleString()"></span>
                    </div>
                </div>
                
                <button type="button" 
                        class="w-full py-5 bg-[#800000] text-white rounded-2xl font-black text-xs uppercase shadow-xl tracking-[0.2em] active:scale-95 transition-all disabled:opacity-30">
                    Complete Order
                </button>
            </div>
        </div>
    </div>
</div>