<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>My Cart - Uncle Brew</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfc; }
        [x-cloak] { display: none !important; }
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
        .header-glass { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(0,0,0,0.05); }
        .cart-card { transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.05); }
        .btn-maroon { background: linear-gradient(135deg, #800000 0%, #a50000 100%); transition: all 0.2s ease; }
        
        /* Custom Scroll for Addons */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }

        /* Animation for Bottom Sheet */
        .modal-bottom-sheet {
            transition: transform 0.3s ease-out;
        }
    </style>
</head>
<body x-data="cartPage()" x-init="loadCart()" x-cloak class="pb-40 sm:pb-52">

    <header class="header-glass sticky top-0 z-[100] py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex justify-between items-center">
            <div>
                <p class="text-[8px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-0.5">Review Orders</p>
                <h1 class="text-xl sm:text-3xl font-extrabold text-gray-900 tracking-tight italic">MY <span class="text-maroon">CART</span></h1>
            </div>
            
            <button @click="window.history.back()" 
               class="group flex items-center bg-gray-50 px-3 py-2 sm:px-5 sm:py-3 rounded-xl sm:rounded-2xl border border-gray-100 active:scale-95 transition-all">
                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span class="text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest">Back</span>
            </button>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 pt-6 sm:pt-10">
        <div class="grid grid-cols-1 gap-3 sm:gap-5">
            <template x-for="(item, index) in cart" :key="index">
                <div class="bg-white rounded-[1.5rem] sm:rounded-[2.5rem] p-3 sm:p-6 flex items-center gap-3 sm:gap-8 cart-card shadow-sm border border-gray-100">
                    <div class="w-20 h-20 sm:w-28 sm:h-28 bg-gray-50 rounded-[1.2rem] sm:rounded-[2rem] flex-shrink-0 flex items-center justify-center p-2 sm:p-4">
                        <img :src="item.image" class="max-w-full max-h-full object-contain drop-shadow-md">
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 sm:gap-6">
                            <div class="space-y-0.5 sm:space-y-1">
                                <h3 class="font-bold text-gray-900 text-sm sm:text-2xl tracking-tight truncate uppercase" x-text="item.name"></h3>
                                
                                  <div x-show="item.selectedAddOns && item.selectedAddOns.length > 0" class="flex flex-wrap items-center gap-x-3 gap-y-1 my-1.5">
    <template x-for="addon in item.selectedAddOns" :key="addon.name">
        <p class="text-[11px] sm:text-xs font-medium text-black">
            <span class="font-bold">+</span> <span x-text="addon.name"></span>
        </p>
    </template>
</div>
                                
                                <div class="text-maroon font-black text-base sm:text-3xl mt-1">₱<span x-text="(item.totalPrice / item.qty).toFixed(2)"></span></div>
                            </div>

                            <div class="flex items-center justify-between md:justify-end gap-2 sm:gap-10">
                                <div class="flex items-center bg-gray-50 rounded-lg sm:rounded-2xl p-1 border border-gray-100">
                                    <button @click="updateQty(index, -1)" class="w-7 h-7 sm:w-10 sm:h-10 bg-white rounded-md shadow-sm font-bold text-gray-500 active:bg-maroon active:text-white transition-colors">-</button>
                                    <span class="w-8 sm:w-14 text-center text-xs sm:text-lg font-bold" x-text="item.qty"></span>
                                    <button @click="updateQty(index, 1)" class="w-7 h-7 sm:w-10 sm:h-10 bg-white rounded-md shadow-sm font-bold text-gray-500 active:bg-maroon active:text-white transition-colors">+</button>
                                </div>

                                <div class="flex gap-1.5 sm:gap-2">
                                    <button @click="openEditModal(index)" class="p-2 sm:p-4 bg-blue-50 text-blue-400 rounded-lg sm:rounded-2xl active:scale-90 transition-all">
                                        <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>
                                    <button @click="removeItem(index)" class="p-2 sm:p-4 bg-red-50 text-red-400 rounded-lg sm:rounded-2xl active:scale-90 transition-all">
                                        <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <div x-show="cart.length === 0" class="py-20 sm:py-32 text-center bg-white rounded-[2rem] border-2 border-dashed border-gray-100">
                <div class="mb-4 flex justify-center">
                    <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-width="2"/></svg>
                </div>
                <h3 class="text-gray-400 font-bold uppercase tracking-widest text-[10px] sm:text-sm">Your cart is empty</h3>
            </div>
        </div>
    </main>

    <footer class="fixed bottom-0 left-0 right-0 z-[90] p-4 sm:p-6 bg-gradient-to-t from-[#fcfcfc] via-[#fcfcfc] to-transparent" x-show="cart.length > 0">
        <div class="max-w-7xl mx-auto bg-white rounded-[2rem] sm:rounded-[3rem] p-4 sm:p-6 shadow-2xl border border-gray-50 flex items-center justify-between gap-4">
            <div class="px-2 sm:px-6">
                <span class="block text-[8px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest">Grand Total</span>
                <span class="text-xl sm:text-5xl font-black text-gray-900 tracking-tighter leading-none">₱<span x-text="total.toFixed(2)"></span></span>
            </div>
            <button @click="placeOrder()" class="btn-maroon text-white flex-1 sm:flex-none px-6 py-4 sm:px-20 sm:py-6 rounded-xl sm:rounded-[2rem] font-bold text-xs sm:text-lg uppercase tracking-widest flex items-center justify-center gap-2 shadow-xl shadow-maroon/20">
                <span>Order Now</span>
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="3"/></svg>
            </button>
        </div>
    </footer>

    <div x-show="showEditModal" 
         class="fixed inset-0 z-[200] flex items-end sm:items-center justify-center p-0 sm:p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeEditModal()"></div>
        
        <div x-show="showEditModal" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full sm:scale-95"
             x-transition:enter-end="translate-y-0 sm:scale-100"
             class="bg-white rounded-t-[2.5rem] sm:rounded-[2.5rem] p-6 sm:p-10 w-full max-w-md relative z-10 shadow-2xl overflow-hidden">
            
            <div class="w-12 h-1 bg-gray-200 rounded-full mx-auto mb-6 sm:hidden"></div>
            
            <h3 class="text-xl sm:text-2xl font-black text-maroon text-center leading-tight uppercase" x-text="editItem?.name"></h3>
            <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center mt-2 mb-8">Customize your add-ons</p>
            
            <div class="space-y-3 max-h-[40vh] overflow-y-auto mb-8 pr-1 custom-scroll">
                <template x-for="addon in editItem?.addOns" :key="addon.name">
                    <label class="flex items-center justify-between bg-gray-50 rounded-[1.2rem] p-4 cursor-pointer border-2 border-transparent hover:border-maroon/10 active:bg-gray-100 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="relative flex items-center">
                                <input type="checkbox" 
                                       :checked="editItem.selectedAddOns && editItem.selectedAddOns.some(a => a.name === addon.name)" 
                                       @change="toggleAddon(addon)"
                                       class="w-6 h-6 rounded-lg accent-maroon cursor-pointer border-gray-300">
                            </div>
                            <span class="text-sm sm:text-base font-bold text-gray-700" x-text="addon.name"></span>
                        </div>
                        <span class="text-sm font-black text-maroon" x-text="'+₱' + addon.price"></span>
                    </label>
                </template>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <button @click="closeEditModal()" class="bg-gray-100 text-gray-500 py-4 sm:py-5 rounded-2xl font-black uppercase text-[10px] tracking-widest active:scale-95 transition-all">Cancel</button>
                <button @click="saveEdit()" class="bg-maroon text-white py-4 sm:py-5 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-maroon/20 active:scale-95 transition-all">Save Changes</button>
            </div>
        </div>
    </div>

    <script>
        function cartPage() {
            return {
                cart: [], 
                showEditModal: false, 
                editIndex: -1, 
                editItem: null,

                loadCart() { 
                    this.cart = JSON.parse(localStorage.getItem('ub_cart')) || []; 
                },

                get total() { 
                    return this.cart.reduce((sum, item) => sum + item.totalPrice, 0); 
                },

                updateQty(index, amount) {
                    let item = this.cart[index];
                    if (!item) return;
                    let newQty = item.qty + amount;
                    if (newQty > 0) {
                        item.qty = newQty;
                        this.recalcItemTotal(item);
                        this.saveCart();
                    } else { this.removeItem(index); }
                },

                removeItem(index) { 
                    if(confirm('Remove this item from cart?')) {
                        this.cart.splice(index, 1); 
                        this.saveCart(); 
                    }
                },

                openEditModal(index) {
                    this.editIndex = index;
                    // Create a deep copy so changes aren't applied until saved
                    this.editItem = JSON.parse(JSON.stringify(this.cart[index]));
                    if (!this.editItem.selectedAddOns) this.editItem.selectedAddOns = [];
                    this.showEditModal = true;
                    document.body.style.overflow = 'hidden'; // Lock background scroll
                },

                // Logic for checking/unchecking addons in the modal
                toggleAddon(addon) {
                    const exists = this.editItem.selectedAddOns.findIndex(a => a.name === addon.name);
                    if (exists > -1) {
                        this.editItem.selectedAddOns.splice(exists, 1);
                    } else {
                        this.editItem.selectedAddOns.push(addon);
                    }
                },

                saveEdit() {
                    this.recalcItemTotal(this.editItem);
                    this.cart[this.editIndex] = this.editItem;
                    this.saveCart();
                    this.closeEditModal();
                },

                recalcItemTotal(item) {
                    const base = parseFloat(item.price) || 0;
                    const addons = item.selectedAddOns ? item.selectedAddOns.reduce((s, a) => s + (parseFloat(a.price) || 0), 0) : 0;
                    item.totalPrice = (base + addons) * item.qty;
                },

                closeEditModal() { 
                    this.showEditModal = false; 
                    document.body.style.overflow = 'auto'; // Unlock scroll
                },

                saveCart() { 
                    localStorage.setItem('ub_cart', JSON.stringify(this.cart)); 
                },

                placeOrder() {
                    const table = new URLSearchParams(window.location.search).get('table') || '1';
                    window.location.href = '/customer/payment?table=' + table;
                }
            }
        }
    </script>
</body>
</html>