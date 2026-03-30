<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #fcfcfc; 
        }
        [x-cloak] { display: none !important; }
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
        .header-glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .cart-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .btn-maroon {
            background: linear-gradient(135deg, #800000 0%, #a50000 100%);
            transition: all 0.2s ease;
        }
        .btn-maroon:hover { filter: brightness(1.1); transform: translateY(-1px); }
        .btn-maroon:active { transform: scale(0.98); }
        .qty-btn { transition: all 0.2s ease; }
        .qty-btn:hover { background-color: #800000; color: white; }
    </style>
</head>
<body x-data="cartPage()" x-init="loadCart()" x-cloak class="pb-52">

    <header class="header-glass sticky top-0 z-40 py-6">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">Review Orders</p>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">MY <span class="text-maroon">CART</span></h1>
            </div>
            
           <a :href="'/table/' + (new URLSearchParams(window.location.search).get('table') || '1')"  class="group flex items-center bg-gray-50 px-5 py-3 rounded-2xl border border-gray-100 transition-all hover:bg-gray-100">
                <svg class="w-4 h-4 mr-2 text-gray-400 group-hover:text-maroon transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Continue Ordering</span>
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 pt-10">
        <div class="grid grid-cols-1 gap-5">
            <template x-for="(item, index) in cart" :key="index">
                <div class="bg-white rounded-[2.5rem] p-6 flex items-center gap-8 cart-card shadow-sm border border-gray-100">
                    <div class="w-28 h-28 bg-gray-50 rounded-[2rem] flex-shrink-0 flex items-center justify-center p-4">
                        <img :src="item.image" class="max-w-full max-h-full object-contain drop-shadow-md">
                    </div>

                    <div class="flex-1">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="space-y-1">
                                <h3 class="font-bold text-gray-900 text-2xl tracking-tight" x-text="item.name"></h3>
                                <div x-show="item.selectedAddOns && item.selectedAddOns.length > 0" class="text-sm text-gray-500 leading-relaxed">
                                    <template x-for="addon in item.selectedAddOns" :key="addon.name">
                                        <div x-text="'+ ' + addon.name"></div>
                                    </template>
                                </div>
                                <div class="flex items-center mt-2">
                                    <span class="text-maroon font-black text-3xl">₱<span x-text="(item.totalPrice / item.qty).toFixed(2)"></span></span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between md:justify-end gap-4 md:gap-10">
                                <div class="flex items-center bg-gray-50 rounded-2xl p-1.5 border border-gray-100">
                                    <button type="button" @click="updateQty(index, -1)" class="qty-btn w-10 h-10 flex items-center justify-center bg-white rounded-xl shadow-sm font-bold text-gray-500 text-xl">-</button>
                                    <span class="w-14 text-center text-lg font-bold text-gray-800" x-text="item.qty"></span>
                                    <button type="button" @click="updateQty(index, 1)" class="qty-btn w-10 h-10 flex items-center justify-center bg-white rounded-xl shadow-sm font-bold text-gray-500 text-xl">+</button>
                                </div>

            <div class="flex gap-2">
                                    <button type="button" @click="openEditModal(index)" class="group p-4 bg-blue-50 hover:bg-blue-500 rounded-2xl transition-all duration-300">
                                        <svg class="w-6 h-6 text-blue-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <button type="button" @click="removeItem(index)" class="group p-4 bg-red-50 hover:bg-red-500 rounded-2xl transition-all duration-300">
                                        <svg class="w-6 h-6 text-red-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <div x-show="cart.length === 0" class="py-32 text-center bg-white rounded-[3.5rem] border-2 border-dashed border-gray-100 px-10">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h3 class="text-gray-400 font-bold uppercase tracking-[0.3em] text-sm mb-8">Your cart is empty</h3>
                <a :href="'/table/' + (new URLSearchParams(window.location.search).get('table') || '1')" 
   class="inline-block btn-maroon text-white px-12 py-5 rounded-[2rem] font-bold text-sm shadow-xl shadow-maroon/20">
    Go Back to Menu
</a>
                </a>
            </div>
        </div>
    </main>

    <footer class="fixed bottom-0 left-0 right-0 z-50 p-6 bg-gradient-to-t from-[#fcfcfc] via-[#fcfcfc] to-transparent" x-show="cart.length > 0">
        <div class="max-w-7xl mx-auto bg-white rounded-[3rem] p-6 shadow-[0_-15px_50px_rgba(0,0,0,0.1)] border border-gray-100 flex items-center justify-between">
            <div class="px-6">
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Amount</span>
                <span class="text-5xl font-black text-gray-900 tracking-tighter leading-none">₱<span x-text="total.toFixed(2)"></span></span>
            </div>
            
            <button type="button" @click="placeOrder()" 
                class="btn-maroon text-white px-20 py-6 rounded-[2rem] font-bold text-lg uppercase tracking-[0.15em] shadow-2xl shadow-maroon/30 flex items-center justify-center gap-4">
                <span>Place Order Now</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </button>
        </div>
    </footer>

    <script>
        function cartPage() {
            return {
                cart: [],
                showEditModal: false,
                editIndex: -1,
                editItem: null,
                loadCart() {
                    this.cart = JSON.parse(localStorage.getItem('ub_cart')) || [];
                    console.log('Loaded cart:', this.cart);
                },
                get total() {
                    return this.cart.reduce((sum, item) => sum + item.totalPrice, 0);
                },
                updateQty(index, amount) {
                    let newQty = this.cart[index].qty + amount;
                    if (newQty > 0) {
                        this.cart[index].qty = newQty;
                        this.cart[index].totalPrice = (this.cart[index].price + this.cart[index].selectedAddOns.reduce((sum, addon) => sum + addon.price, 0)) * newQty;
                        this.saveCart();
                    } else if (newQty === 0) {
                        this.removeItem(index);
                    }
                },
                removeItem(index) {
                    this.cart.splice(index, 1);
                    this.saveCart();
                },
                openEditModal(index) {
                    if(index >= 0 && index < this.cart.length) {
                        let item = this.cart[index];
                        this.editIndex = index;
                        this.editItem = {
                            id: item.id,
                            name: item.name,
                            price: item.price,
                            image: item.image,
                            qty: item.qty,
                            totalPrice: item.totalPrice,
                            addOns: item.addOns ? JSON.parse(JSON.stringify(item.addOns)) : [],
                            selectedAddOns: item.selectedAddOns ? JSON.parse(JSON.stringify(item.selectedAddOns)) : []
                        };
                        this.showEditModal = true;
                    }
                },
                saveEdit() {
                    if(this.editItem && this.editIndex >= 0 && this.editIndex < this.cart.length) {
                        const addOnPrice = this.editItem.selectedAddOns ? this.editItem.selectedAddOns.reduce((sum, addon) => sum + addon.price, 0) : 0;
                        const newTotalPrice = (this.editItem.price + addOnPrice) * this.editItem.qty;
                        
                        // Update cart
                        this.cart[this.editIndex] = {
                            id: this.editItem.id,
                            name: this.editItem.name,
                            price: this.editItem.price,
                            image: this.editItem.image,
                            qty: this.editItem.qty,
                            totalPrice: newTotalPrice,
                            addOns: this.editItem.addOns,
                            selectedAddOns: this.editItem.selectedAddOns
                        };
                        
                        this.saveCart();
                    }
                    
                    // Close modal and cleanup
                    this.showEditModal = false;
                    this.editItem = null;
                    this.editIndex = -1;
                },
                closeEditModal() {
                    this.showEditModal = false;
                    this.editItem = null;
                    this.editIndex = -1;
                },
                saveCart() {
                    localStorage.setItem('ub_cart', JSON.stringify(this.cart));
                },
               placeOrder() {
    if(this.cart.length === 0) {
        alert('No items in cart');
        return;
    }
    if(confirm('Confirm your order?')) {
        // Kunin ang table number mula sa URL bago i-clear ang storage
        const urlParams = new URLSearchParams(window.location.search);
        const currentTable = urlParams.get('table') || '1';

        // Clear storage
        localStorage.removeItem('ub_cart');
        localStorage.removeItem('ub_customer_name');
        
        alert('Order placed! Thank you!');

        // REDIRECT pabalik sa tamang table
        window.location.href = '/table/' + currentTable;
    }
}
            }
        }
    </script>

    <!-- Edit Add-ons Modal -->
    <div x-show="showEditModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4" @click.self="closeEditModal()">
        <div class="absolute inset-0 bg-maroon/50 backdrop-blur-sm"></div>
        <div class="bg-white rounded-[2rem] p-6 max-w-md w-full shadow-2xl animate__animated animate__zoomIn relative z-10">
            <div class="text-center mb-6">
                <h3 class="text-lg font-bold text-maroon" x-text="editItem ? 'Edit ' + editItem.name : ''"></h3>
                <p class="text-xs text-gray-500 mt-1">Modify add-ons for this item</p>
            </div>
            <div x-show="editItem && editItem.addOns && editItem.addOns.length > 0" class="space-y-3 max-h-60 overflow-y-auto mb-6">
                <template x-for="addon in editItem.addOns" :key="addon.name">
                    <label class="flex items-center bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors cursor-pointer border border-gray-100">
                        <input type="checkbox" 
                               :checked="editItem.selectedAddOns.some(a => a.name === addon.name)"
                               @change="event => {
                                   if(event.target.checked) {
                                       if(!editItem.selectedAddOns.some(a => a.name === addon.name)) {
                                           editItem.selectedAddOns.push(addon);
                                       }
                                   } else {
                                       editItem.selectedAddOns = editItem.selectedAddOns.filter(a => a.name !== addon.name);
                                   }
                               }"
                               class="accent-maroon mr-3 w-5 h-5">
                        <span class="text-sm font-medium text-gray-800" x-text="addon.name"></span>
                        <span class="text-sm text-maroon font-bold ml-auto" x-text="'+₱' + addon.price"></span>
                    </label>
                </template>
            </div>
            <div class="flex gap-3">
                <button type="button" @click="closeEditModal()" class="flex-1 bg-gray-200 text-gray-700 py-3 px-6 rounded-xl font-bold text-sm uppercase tracking-widest hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button type="button" @click="saveEdit()" class="flex-1 bg-maroon text-white py-3 px-6 rounded-xl font-bold text-sm uppercase tracking-widest shadow-md hover:bg-red-800 transition-colors">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

</body>
</html>