<div x-data="posSystem()" class="relative w-full h-full">

    <div class="pos-interface grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start pt-4">
        
        <div class="lg:col-span-7 xl:col-span-8 flex flex-col h-full">
            <div class="mb-6 flex flex-col items-center w-full">
                <div class="relative w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="text" x-model="searchQuery" placeholder="Search menu items..." 
                           class="w-full pl-16 pr-6 py-5 bg-white border border-gray-200 rounded-2xl text-base font-bold outline-none shadow-sm focus:ring-4 focus:ring-red-900/10 focus:border-red-800 transition-all">
                </div>

                <div class="flex justify-start lg:justify-center gap-3 w-full mt-5 overflow-x-auto pb-4 scrollbar-hide px-1">
                    <template x-for="cat in ['All', 'Main Course', 'Appetizer', 'Dessert', 'Beverage']">
                        <button x-on:click="selectedCategory = cat" 
                                :class="selectedCategory === cat ? 'bg-[#800000] text-white shadow-md border-[#800000]' : 'bg-white text-gray-500 border-gray-200 hover:border-gray-300 hover:bg-gray-50'"
                                class="px-6 py-3.5 rounded-2xl text-sm font-black uppercase border whitespace-nowrap transition-all tracking-wide" 
                                x-text="cat"></button>
                    </template>
                </div>
            </div>

            <div class="grid grid-cols-2 xl:grid-cols-3 gap-6">
                <template x-for="p in filteredProducts" :key="p.id">
                    <div class="bg-white p-5 flex flex-col relative border border-gray-200 rounded-[2rem] shadow-sm hover:shadow-lg transition-all duration-300"
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
                            
                             <div class="mb-2">
    <button type="button" @click="openCustomizeModal(p)" :disabled="p.stock <= 0" class="flex items-center justify-between w-full bg-gray-50 border border-gray-200 rounded-xl p-3 hover:bg-gray-100 transition-colors">
        <span class="text-[11px] font-bold text-[#800000] uppercase tracking-wider">Customize</span>
        <i class="fas fa-chevron-right text-[#800000] text-xs"></i>
    </button>
    
    <div x-show="p.selectedAddOns && p.selectedAddOns.length > 0" style="display: none;" class="mt-2 bg-blue-50/80 border-l-2 border-blue-400 rounded-r-lg p-2 space-y-0.5">
        <template x-for="addon in p.selectedAddOns" :key="addon.name">
            <div class="text-[10px] text-blue-700 flex justify-between font-bold uppercase tracking-wider">
                <span class="truncate pr-1" x-text="'• ' + addon.name"></span>
                <span x-text="'+₱' + addon.price"></span>
            </div>
        </template>
    </div>
</div>

                            
                            <button x-on:click.prevent="if(p.stock > 0) { addToCart(p); }" 
                                    :disabled="p.stock <= 0"
                                    class="w-full py-4 bg-[#800000] text-white rounded-xl text-sm font-black uppercase tracking-[0.1em] transition-all shadow-md active:scale-[0.98] disabled:bg-gray-300 flex items-center justify-center gap-2">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="lg:col-span-5 xl:col-span-4 sticky top-4">
            <div class="bg-white p-5 border-t-[8px] border-[#800000] shadow-xl flex flex-col h-[calc(100vh-90px)] rounded-[2rem] overflow-hidden">
                
                <div class="space-y-3 mb-4 overflow-y-auto flex-1 min-h-[150px] scrollbar-hide pr-1 relative">
                    <div x-show="cart.length === 0" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 bg-white">
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Cart is empty</p>
                    </div>

                    <template x-for="(item, index) in cart" :key="index">
                        <div class="flex justify-between items-center bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex items-center gap-3 w-full">
                                <div class="w-8 h-8 rounded-lg bg-red-50 text-[#800000] flex items-center justify-center text-sm font-black shrink-0" x-text="item.qty + 'x'"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-black uppercase text-gray-800 truncate" x-text="item.name"></p>
                                    <p x-show="item.addonName !== 'Default'" class="text-[10px] text-orange-600 font-bold uppercase truncate" x-text="'+ ' + item.addonName"></p>
                                    <p class="text-xs text-gray-500 font-bold" x-text="formatCurrency(item.price * item.qty)"></p>
                                </div>
                            </div>
                            <button x-on:click="removeFromCart(index)" class="w-10 h-10 shrink-0 flex items-center justify-center text-gray-400 hover:text-red-500 bg-gray-50 rounded-lg transition-colors ml-2">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </template>
                </div>
                
                <div class="space-y-4 pt-3 border-t border-gray-100 shrink-0 bg-white">
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <button @click="orderType = 'DINE IN'" :class="orderType === 'DINE IN' ? 'border-[#800000] bg-red-50 text-[#800000]' : 'border-gray-200 text-gray-400'" class="border-2 p-3 rounded-xl flex items-center justify-center gap-2 font-black text-xs transition-all">
                            <i class="fa-solid fa-utensils"></i> DINE IN
                        </button>
                        <button @click="orderType = 'TAKE OUT'" :class="orderType === 'TAKE OUT' ? 'border-[#800000] bg-red-50 text-[#800000]' : 'border-gray-200 text-gray-400'" class="border-2 p-3 rounded-xl flex items-center justify-center gap-2 font-black text-xs transition-all">
                            <i class="fa-solid fa-bag-shopping"></i> TAKE OUT
                        </button>
                    </div>

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
                            <span class="text-xs font-black text-gray-800 uppercase tracking-widest">Total Due</span>
                            <span class="text-3xl font-black text-[#800000]" x-text="formatCurrency(cartTotal)"></span>
                        </div>
                    </div>
                    
                    <button type="button" 
                            @click="completeOrder"
                            :disabled="cart.length === 0"
                            class="w-full py-4 bg-[#800000] text-white rounded-xl font-black text-sm uppercase shadow-lg disabled:opacity-50 flex justify-center items-center gap-2 transition-all">
                        Complete Order <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>

   <!-- Inalis ang @click sa backdrop para hindi na mag-close sa labas -->
<div x-show="showModal" x-transition class="fixed inset-0 z-[200] flex items-center justify-center p-4">
    
    <!-- Backdrop: Static na lang ito, hindi na clickable -->
    <div class="absolute inset-0 bg-maroon/50 backdrop-blur-sm"></div>
    
    <div class="bg-white rounded-[2rem] p-6 max-w-md w-full shadow-2xl animate__animated animate__zoomIn relative z-10">
        
        <!-- EXIT BUTTON: Ginawa nating explicit hex code ang hover para siguradong mag-maroon -->
        <button type="button" @click="closeCustomizeModal()" 
            class="absolute top-5 right-5 text-gray-400 hover:text-[#800000] transition-colors duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="text-center mb-6 mt-2">
           <h3 class="text-lg font-bold text-[#800000] capitalize" x-text="'Customize ' + (selectedFood ? selectedFood.name.toLowerCase() : '')"></h3>
            <p class="text-xs text-gray-500 mt-1">Select add-ons for this item</p>
        </div>
        
        <div x-show="selectedFood" class="space-y-3 max-h-60 overflow-y-auto mb-6 px-2">
             <template x-for="addon in (selectedFood ? selectedFood.addOns : [])" :key="addon.name">
                <label class="flex items-center bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors cursor-pointer border border-gray-100">
                    <input type="checkbox" 
                           :value="addon.name"
                           x-model="tempSelectedNames"
                           class="accent-[#800000] mr-3 w-5 h-5">
                    <span class="text-sm font-medium text-gray-800" x-text="addon.name"></span>
                    <span class="text-sm text-[#800000] font-bold ml-auto" x-text="'+₱' + addon.price"></span>
                </label>
            </template>
        </div>
        
        <div class="flex gap-3">
            <!-- Button logic: Disabled kung walang check, para hindi rin mag-close accidentally -->
            <button type="button" 
                @click="confirmAddOns()" 
                :disabled="tempSelectedNames.length === 0"
                :class="tempSelectedNames.length === 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-[#800000] hover:bg-[#a50000] shadow-md'"
                class="flex-1 text-white py-3 px-6 rounded-xl font-bold text-sm uppercase tracking-widest transition-all">
                Confirm Selection
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
            orderType: 'DINE IN',
            
            cart: [],
            receiptData: null, 
            
            // New State for Modal
            showModal: false,
            selectedFood: null,
            tempSelectedNames: [],
            
            get products() {
                return Alpine.store('inventory').products;
            },

            get filteredProducts() {
                return this.products.filter(p => {
                    const matchesSearch = p.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                    const btnCat = this.selectedCategory.toLowerCase().trim().replace(/s$/, '');
                    const dbCat = p.cat ? p.cat.toLowerCase().trim().replace(/s$/, '') : '';
                    const matchesCat = this.selectedCategory === 'All' || dbCat === btnCat;
                    return matchesSearch && matchesCat;
                });
            },
            
            get cartTotal() {
                return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            },

            formatCurrency(val) {
                return '₱' + parseFloat(val).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            },

            // Modal Functions
          // Modal Functions
          // Modal Functions
            openCustomizeModal(product) {
                this.selectedFood = product;
                
                // Kapag walang add-ons galing sa database, i-mamatch natin base sa pangalan ng product 
                // para parehong-pareho sa menu.blade.php mo
                if (!this.selectedFood.addOns || this.selectedFood.addOns.length === 0) {
                    const itemName = this.selectedFood.name.toLowerCase();
                    
                    if (itemName.includes('burger steak')) {
                        this.selectedFood.addOns = [
                            {name: 'Extra Cheese', price: 20}, 
                            {name: 'Bacon', price: 30}, 
                            {name: 'Fried Egg', price: 15}
                        ];
                    } else if (itemName.includes('carbonara')) {
                        this.selectedFood.addOns = [
                            {name: 'Extra Bacon', price: 25}, 
                            {name: 'Garlic Bread', price: 30}
                        ];
                    } else if (itemName.includes('ice tea') || itemName.includes('icetea')) {
                        this.selectedFood.addOns = [
                            {name: 'Lemon Slice', price: 5}, 
                            {name: 'Extra Ice', price: 0}
                        ];
                    } else if (itemName.includes('mozarella')) {
                        this.selectedFood.addOns = [
                            {name: 'Marinara Sauce', price: 10}
                        ];
                    } else if (itemName.includes('leche flan')) {
                        this.selectedFood.addOns = [
                            {name: 'Extra Caramel', price: 15}
                        ];
                    } else {
                        // Kung may ibang item na wala sa listahan, empty lang siya
                        this.selectedFood.addOns = []; 
                    }
                }

                if (!this.selectedFood.selectedAddOns) {
                    this.selectedFood.selectedAddOns = [];
                }
                
                this.tempSelectedNames = this.selectedFood.selectedAddOns.map(a => a.name);
                this.showModal = true;
            },



            confirmAddOns() {
                if (this.selectedFood && this.selectedFood.addOns) {
                    this.selectedFood.selectedAddOns = this.selectedFood.addOns.filter(addon => 
                        this.tempSelectedNames.includes(addon.name)
                    );
                }
                this.showModal = false;
            },

            closeCustomizeModal() {
                this.showModal = false;
                this.selectedFood = null;
                this.tempSelectedNames = [];
            },

            // Updated Add to Cart logic to support new structure
            addToCart(product) {
                // Default to empty array if no customizations were selected
                const selectedAddOns = product.selectedAddOns || [];
                
                const addOnPrice = selectedAddOns.reduce((sum, addon) => sum + addon.price, 0);
                const finalUnitPrice = product.price + addOnPrice;
                
                const addonNameStr = selectedAddOns.length > 0 ? selectedAddOns.map(a => a.name).join(', ') : 'Default';
                const cartId = product.id + '-' + addonNameStr;

                let foundIndex = this.cart.findIndex(i => i.cartId === cartId);

                if (foundIndex > -1) { 
                    this.cart[foundIndex].qty++;
                } else { 
                    this.cart.push({
                        ...product, 
                        cartId: cartId,
                        qty: 1, 
                        addonName: addonNameStr,
                        // Override product price in cart to reflect base + addons
                        price: finalUnitPrice 
                    });
                }
                
                // Clear selections after adding
                product.selectedAddOns = [];
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
            },

            completeOrder() {
                if (this.cart.length === 0) return;

                const orderID = 'ORD-' + Math.random().toString(36).substr(2, 6).toUpperCase();
                
                const tempReceiptData = {
                    orderId: orderID,
                    orderType: this.orderType,
                    customerName: 'WALK-IN CUSTOMER',
                    items: [...this.cart],
                    totalAmount: this.cartTotal,
                    timestamp: new Date().toLocaleDateString() + ', ' + new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit', hour12: true })
                };

                const orderForChef = {
                    id: orderID,
                    table: this.orderType,
                    status: 'Pending',
                    items: this.cart.map(item => ({
                        name: item.name + (item.addonName !== 'Default' ? ' (' + item.addonName + ')' : ''),
                        qty: item.qty,
                        done: false
                    })),
                    note: "Direct Order",
                    timestamp: new Date().getTime()
                };

                localStorage.setItem('ub_chef_new_order', JSON.stringify(orderForChef));

                this.cart.forEach(item => {
                    Alpine.store('inventory').updateStock(item.id, item.qty);
                });

                this.triggerIsolatedPrint(tempReceiptData);

                this.cart = [];
                this.orderType = 'DINE IN'; 
            },

            closeReceipt() {
                this.receiptData = null;
                this.orderType = 'DINE IN';
            },

            triggerIsolatedPrint(data) {
                if (!data) return;

                let itemsHTML = '';
                data.items.forEach(item => {
                    itemsHTML += `
                    <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                        <div style="width: 20px;">${item.qty}</div>
                        <div style="flex: 1; padding-left: 5px;">
                            <div style="text-transform: uppercase;">${item.name}</div>
                            ${item.addonName !== 'Default' ? `<div style="font-size: 10px; font-style: italic;">+ ${item.addonName}</div>` : ''}
                        </div>
                    </div>`;
                });

                let iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                document.body.appendChild(iframe);
                let doc = iframe.contentWindow.document;

                doc.write(`
                    <html>
                    <head>
                        <style>
                            body { font-family: 'Courier New', monospace; width: 80mm; padding: 4mm; color: black; font-size: 12px; margin: 0; }
                            .text-center { text-align: center; }
                            .divider { border-bottom: 1px dashed black; margin: 5px 0; }
                            .bold { font-weight: bold; }
                            .total { font-size: 16px; font-weight: bold; display: flex; justify-content: space-between; margin-top: 10px; }
                            .highlight-box { font-size: 24px; font-weight: bold; border: 2px solid black; margin: 10px 0; padding: 10px; text-transform: uppercase; text-align: center; }
                        </style>
                    </head>
                    <body>
                        <div class="text-center">
                            <div class="bold" style="font-size: 14px;">UNIVERSITY OF BATANGAS</div>
                            <div style="font-size: 10px;">Hilltop Rd, Batangas City, 4200 Batangas</div>
                            <div class="divider"></div>
                            
                            <div class="highlight-box">${data.orderType}</div> 
                        </div>
                        
                        <div style="font-size: 10px; margin-top: 5px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>Order ID:</span>
                                <span class="bold">${data.orderId}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Date:</span>
                                <span>${data.timestamp}</span>
                            </div>
                        </div>

                        <div class="divider"></div>
                        
                        <div style="display: flex; font-weight: bold; margin-bottom: 5px; font-size: 10px;">
                            <div style="width: 20px;">QTY</div>
                            <div style="flex: 1; padding-left: 5px;">ITEM/S</div>
                        </div>

                        ${itemsHTML}

                        <div class="divider"></div>
                        
                        <div class="total">
                            <span>TOTAL:</span>
                            <span>${this.formatCurrency(data.totalAmount)}</span>
                        </div>

                        <div class="text-center" style="margin-top: 25px; font-size: 10px;">
                            <div class="bold">THANK YOU!</div>
                            <div>Please come again.</div>
                        </div>
                    </body>
                    </html>
                `);
                doc.close();

                setTimeout(() => {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                    setTimeout(() => { document.body.removeChild(iframe); }, 1000);
                }, 500);
            }
        }
    }
</script>