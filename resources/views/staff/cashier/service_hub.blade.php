<style>
    /* Thermal Print Specific Styles */
    @media print {
        body * { visibility: hidden; }
        #print-receipt-area, #print-receipt-area * { visibility: visible; }
        #print-receipt-area { 
            position: absolute; 
            left: 0; top: 0; width: 80mm; 
            padding: 0; margin: 0;
            font-family: 'Courier New', Courier, monospace !important;
            color: black;
        }
        .no-print { display: none !important; }
    }
</style>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start pt-4" x-data="posSystem()">
    
    <div class="lg:col-span-7 xl:col-span-8 flex flex-col h-full no-print">
        <div class="mb-6 flex flex-col items-center w-full">
            <div class="relative w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="text" x-model="searchQuery" placeholder="Search menu items..." 
                       class="w-full pl-16 pr-6 py-5 bg-white border border-gray-200 rounded-2xl text-base font-bold outline-none shadow-sm focus:ring-4 focus:ring-red-900/10 focus:border-red-800 transition-all">
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

                  <div class="w-full h-44 mb-4 overflow-hidden rounded-[2rem] bg-[#fdfdfd] border border-gray-100 relative group flex items-center justify-center shadow-inner">
    <img :src="'/img/' + p.img" 
         class="w-full h-full object-contain p-4 transition-all duration-500 group-hover:scale-110" 
         x-on:error="$el.src='https://placehold.co/400x400/f8fafc/800000?text=No+Image'">
    
    <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
</div>
                    
                    <div class="mb-4 flex-grow">
                        <h4 class="text-base font-black text-gray-800 uppercase tracking-tight leading-tight mb-1" x-text="p.name"></h4>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-black text-[#800000]" x-text="'₱' + p.price.toFixed(2)"></span>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider" x-text="'Stock: ' + p.stock"></span>
                        </div>
                    </div>

                    <div class="mt-auto space-y-3 pt-4 border-t border-gray-100">
                        <select x-model="selectedAddon" :disabled="p.stock <= 0" class="w-full text-sm font-bold bg-gray-50 border border-gray-200 rounded-xl p-3.5 outline-none cursor-pointer">
                            <option value="Default|0">Default Serving</option>
                            <option value="Extra Rice|20">Extra Rice (+₱20)</option>
                            <option value="Extra Sauce|10">Extra Sauce (+₱10)</option>
                            <option value="Add Egg|15">Add Egg (+₱15)</option>
                        </select>
                        
                        <button x-on:click.prevent="if(p.stock > 0) { addToCart(p, selectedAddon); selectedAddon = 'Default|0'; }" 
                                :disabled="p.stock <= 0"
                                class="w-full py-4 bg-[#800000] text-white rounded-xl text-sm font-black uppercase tracking-[0.1em] transition-all shadow-md active:scale-[0.98] disabled:bg-gray-300">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div class="lg:col-span-5 xl:col-span-4 sticky top-4 no-print">
        <div class="bg-white p-5 border-t-[8px] border-[#800000] shadow-xl flex flex-col h-[calc(100vh-90px)] rounded-[2rem] overflow-hidden">
            
            

            <div class="space-y-3 mb-4 overflow-y-auto flex-1 min-h-[150px] scrollbar-hide pr-1 relative">
                <div x-show="cart.length === 0" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 bg-white">
                    <p class="text-sm font-bold text-gray-400 uppercase">Cart is empty</p>
                </div>

                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex justify-between items-center bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-red-50 text-[#800000] flex items-center justify-center text-sm font-black" x-text="item.qty + 'x'"></div>
                            <div>
                                <p class="text-sm font-black uppercase text-gray-800" x-text="item.name"></p>
                                <p x-show="item.addonName !== 'Default'" class="text-[10px] text-orange-600 font-bold uppercase" x-text="'+ ' + item.addonName"></p>
                                <p class="text-xs text-gray-500 font-bold" x-text="formatCurrency(item.price * item.qty)"></p>
                            </div>
                        </div>
                        <button x-on:click="removeFromCart(index)" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-red-500 bg-gray-50 rounded-lg">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </template>
            </div>
            
            <div class="space-y-4 pt-3 border-t border-gray-100 shrink-0 bg-white">
                <div class="grid grid-cols-3 gap-2">
                    <button @click="paymentMethod = 'Cash'" :class="paymentMethod === 'Cash' ? 'border-[#800000] bg-red-50 text-[#800000]' : 'border-gray-200 text-gray-400'" class="border-2 p-3 rounded-xl flex flex-col items-center transition-all">
                        <i class="fa-solid fa-money-bill-1 mb-1"></i> <span class="text-[10px] font-black">CASH</span>
                    </button>
                    <button @click="paymentMethod = 'GCash'" :class="paymentMethod === 'GCash' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-400'" class="border-2 p-3 rounded-xl flex flex-col items-center transition-all">
                        <i class="fa-solid fa-mobile-screen mb-1"></i> <span class="text-[10px] font-black">GCASH</span>
                    </button>
                    <button @click="paymentMethod = 'Card'" :class="paymentMethod === 'Card' ? 'border-gray-800 bg-gray-100 text-gray-900' : 'border-gray-200 text-gray-400'" class="border-2 p-3 rounded-xl flex flex-col items-center transition-all">
                        <i class="fa-solid fa-credit-card mb-1"></i> <span class="text-[10px] font-black">CARD</span>
                    </button>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <div class="flex justify-between items-end pt-3">
                        <span class="text-xs font-black text-gray-800 uppercase">Total Due</span>
                        <span class="text-3xl font-black text-[#800000]" x-text="formatCurrency(cartTotal)"></span>
                    </div>
                </div>
                
                <button type="button" 
                        @click="completeOrder"
                        :disabled="cart.length === 0"
                        class="w-full py-4 bg-[#800000] text-white rounded-xl font-black text-sm uppercase shadow-lg disabled:opacity-50 flex justify-center items-center gap-2">
                    Complete Order <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    <div x-show="receiptData" x-cloak class="fixed inset-0 z-[2000] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 no-print">
        <div class="bg-white p-6 rounded-xl shadow-2xl w-[350px] flex flex-col items-center">
            
            <div id="print-receipt-area" class="w-full text-black bg-white p-4 text-sm font-mono border border-slate-300">
                <div class="text-center mb-4">
                    <h2 class="font-bold text-lg uppercase">University of Batangas</h2>
                    <p class="text-[10px]">Brgy Hilltop Rd, Batangas City, 4200 Batangas</p>
                    <p class="text-[10px] font-bold mt-1">OFFICIAL RECEIPT</p>
                    <div class="border-b border-dashed border-black my-2"></div>
                </div>

                <div class="text-[11px] mb-3 space-y-1">
                    <p>Order ID: <span class="font-bold" x-text="receiptData?.orderId"></span></p>
                   
                    <p>Date: <span class="font-bold" x-text="receiptData?.timestamp"></span></p>
                </div>

                <div class="border-b border-dashed border-black my-2"></div>

                <table class="w-full text-[11px] mb-3">
                    <thead>
                        <tr class="border-b border-dashed border-black">
                            <th class="text-left pb-1">QTY</th>
                            <th class="text-left pb-1">ITEM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="item in receiptData?.items" :key="item.cartId">
                            <tr>
                                <td class="py-1 text-left align-top" x-text="'x' + item.qty"></td>
                                <td class="py-1 text-left">
                                    <span x-text="item.name"></span>
                                    <template x-if="item.addonName !== 'Default'">
                                        <div class="text-[9px] italic" x-text="'+ ' + item.addonName"></div>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <div class="border-b border-dashed border-black my-2"></div>

                <div class="flex justify-between font-black text-sm mt-2">
                    <span>TOTAL:</span>
                    <span x-text="receiptData ? formatCurrency(receiptData.totalAmount) : '₱0.00'"></span>
                </div>

                <div class="text-center text-[10px] mt-6">
                    <p>Thank you for dining with us!</p>
                    <p>Please come again.</p>
                </div>
            </div>

            <div class="flex gap-3 mt-6 w-full no-print">
                <button @click="window.print()" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-lg font-black uppercase tracking-widest text-xs flex justify-center items-center gap-2 transition-all shadow-md">
                    <i class="fa-solid fa-print text-lg"></i> Print
                </button>
                <button @click="closeReceipt()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-800 py-3 rounded-lg font-black uppercase tracking-widest text-xs transition-all">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function posSystem() {
        return {
            searchQuery: '',
            selectedCategory: 'All',
            paymentMethod: 'Cash',
            customerName: '',
            selectedTable: '',
            cart: [],
            receiptData: null, // Unified receipt object
            
            get products() {
                return Alpine.store('inventory').products;
            },

            get filteredProducts() {
                return this.products.filter(p => {
                    const matchesSearch = p.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                    const matchesCat = this.selectedCategory === 'All' || p.cat === this.selectedCategory;
                    return matchesSearch && matchesCat;
                });
            },

            get cartTotal() {
                return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            },

            formatCurrency(val) {
                return '₱' + parseFloat(val).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
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
                    this.cart[foundIndex].qty++;
                } else { 
                    this.cart.push({
                        ...product, 
                        cartId: cartId,
                        qty: 1, 
                        addonName: addonName,
                        price: product.price + addonPrice
                    });
                }
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
            },

   completeOrder() {
    if (this.cart.length === 0) return;

    const orderID = 'ORD-' + Math.random().toString(36).substr(2, 6).toUpperCase();
    
    // Naka-set na sa default values dahil inalis na ang input fields
this.receiptData = {
    orderId: orderID,
    tableId: 'DINE IN', // Ginawang ALL CAPS
    customerName: 'WALK-IN CUSTOMER', // Optional: Ginawa ko na ring ALL CAPS para sa kitchen style
    items: [...this.cart],
    totalAmount: this.cartTotal,
    // Format: "4/29/2026, 12:30 AM"
    timestamp: new Date().toLocaleDateString() + ', ' + new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true })
};

const orderForChef = {
    id: orderID,
    table: 'DINE IN', // Siguradong DINE IN ang lilitaw sa Production Dashboard
    status: 'Pending',
    items: this.cart.map(item => ({
        name: item.name + (item.addonName !== 'Default' ? ' (' + item.addonName + ')' : ''),
        qty: item.qty,
        done: false
    })),
    note: "Direct Order",
    timestamp: new Date().getTime()
};

// I-save sa localStorage para mag-trigger ang doorbell sound sa chef side
localStorage.setItem('ub_chef_new_order', JSON.stringify(orderForChef));


    this.cart.forEach(item => {
        Alpine.store('inventory').updateStock(item.id, item.qty);
    });

    this.cart = [];
},

closeReceipt() {
    this.receiptData = null;
    // Inalis na natin ang pag-reset dito dahil wala na yung fields
},


            closeReceipt() {
                this.receiptData = null;
                this.customerName = '';
                this.selectedTable = '';
            }
        }
    }
</script>