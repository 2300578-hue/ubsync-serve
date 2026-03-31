<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uncle Brew | Secure Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfc; color: #222; }
        [x-cloak] { display: none !important; }
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
        
        /* Boxed Border Style for Cards */
        .section-box {
            border: 2px solid #e5e7eb; 
            background-color: #ffffff;
            border-radius: 1.5rem;
        }

        /* Payment Selection Box */
        .payment-box { 
            border: 2px solid #e5e7eb; 
            cursor: pointer; 
            transition: all 0.2s ease;
            position: relative;
            border-radius: 1rem;
        }
        .payment-box.active { 
            border-color: #800000; 
            background-color: #fffafa;
            box-shadow: 0 4px 0px 0px #800000; 
        }
        .payment-box.active::after {
            content: "\f058";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            top: -10px;
            right: -10px;
            color: #800000;
            background: white;
            border-radius: 50%;
            font-size: 1.4rem;
        }

        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
    </style>
</head>
<body x-data="checkoutApp()" x-init="init()" x-cloak class="py-10">

    <div class="max-w-7xl mx-auto px-6 space-y-8">
        
        <div class="section-box p-8 flex flex-col items-center justify-center relative overflow-hidden border-b-[6px] border-b-maroon shadow-sm">
            
            <button @click="window.history.back()" class="absolute top-6 left-6 group flex items-center bg-[#f8f9fb] border border-[#eef1f6] py-3 px-6 rounded-full transition-all hover:bg-gray-100 active:scale-95 shadow-sm">
                <svg class="w-4 h-4 mr-2.5 text-gray-400 group-hover:text-maroon transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="text-[11px] font-extrabold text-[#707c91] uppercase tracking-[0.1em] group-hover:text-maroon">Continue Ordering</span>
            </button>

            <h1 class="text-3xl font-black text-gray-800 uppercase tracking-tighter">Checkout</h1>
            <div class="mt-3 px-6 py-1.5 bg-maroon text-white rounded-full">
                <p class="text-xs font-bold uppercase tracking-widest text-center">Table <span x-text="table"></span></p>
            </div>
        </div>

        <section class="section-box overflow-hidden shadow-sm">
            <div class="p-6 border-b-2 border-gray-100 flex items-center gap-3 bg-gray-50/50">
                <div class="text-maroon">
                    <i class="fas fa-receipt text-lg"></i>
                </div>
                <h2 class="font-bold text-sm uppercase tracking-widest text-gray-500">Order Items</h2>
            </div>
            
            <div class="overflow-y-auto max-h-[400px] custom-scroll">
                <div class="divide-y-2 divide-gray-50">
                    <template x-for="item in cart" :key="item.id">
                        <div class="p-6 flex items-center gap-6 hover:bg-gray-50/30 transition-colors">
                            <div class="relative w-20 h-20 bg-gray-50 border-2 border-gray-100 rounded-xl flex-shrink-0 overflow-hidden shadow-inner">
                                <img :src="item.image || 'https://placehold.co/100x100?text=Uncle+Brew'" 
                                     class="w-full h-full object-cover">
                            </div>

                            <div class="flex-grow">
                                <h3 class="font-bold text-gray-800 text-lg leading-tight mb-1" x-text="item.name"></h3>
                                <p class="text-xs font-bold text-maroon border border-maroon/20 inline-block px-3 py-1 rounded-md uppercase bg-maroon/5">
                                    Qty: <span x-text="item.qty"></span>
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-gray-900 font-black text-xl tracking-tight">₱<span x-text="parseFloat(item.totalPrice).toFixed(2)"></span></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </section>

        <section class="space-y-4">
            <h2 class="font-bold text-xs uppercase tracking-[0.2em] text-gray-400 ml-2 italic">Select Payment Method</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div @click="method = 'gcash'" :class="method === 'gcash' ? 'active' : ''" 
                     class="payment-box p-6 bg-white flex items-center gap-5 shadow-sm">
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100">
                        <i class="fas fa-mobile-screen text-2xl"></i>
                    </div>
                    <span class="font-bold text-sm uppercase tracking-wider text-gray-700">GCash</span>
                </div>
                <div @click="method = 'maya'" :class="method === 'maya' ? 'active' : ''" 
                     class="payment-box p-6 bg-white flex items-center gap-5 shadow-sm">
                    <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 border border-emerald-100">
                        <i class="fas fa-wallet text-2xl"></i>
                    </div>
                    <span class="font-bold text-sm uppercase tracking-wider text-gray-700">Maya</span>
                </div>
                <div @click="method = 'card'" :class="method === 'card' ? 'active' : ''" 
                     class="payment-box p-6 bg-white flex items-center gap-5 shadow-sm">
                    <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-900 border border-gray-200">
                        <i class="fas fa-credit-card text-2xl"></i>
                    </div>
                    <span class="font-bold text-sm uppercase tracking-wider text-gray-700">Card</span>
                </div>
            </div>
        </section>

        <section class="section-box p-10 border-t-[6px] border-t-maroon shadow-sm">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6 mb-10">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">Total Amount to Pay</p>
                    <p class="text-6xl font-black text-maroon tracking-tighter leading-none mt-2">₱<span x-text="total.toFixed(2)"></span></p>
                </div>
                
            </div>

            <button 
                @click="placeOrder()" 
                :disabled="!method"
                :class="!method ? 'bg-gray-200 text-gray-400 cursor-not-allowed border-2 border-gray-300' : 'bg-maroon text-white hover:bg-black active:translate-y-1 transition-all border-b-4 border-black shadow-lg shadow-maroon/20'"
                class="w-full py-6 rounded-full font-black uppercase tracking-[0.25em] text-xl">
                Place Order Now
            </button>
        </section>

    </div>

    <script>
        function checkoutApp() {
            return {
                table: '1',
                cart: [],
                method: null,

                init() {
                    this.cart = JSON.parse(localStorage.getItem('ub_cart')) || [];
                    const params = new URLSearchParams(window.location.search);
                    this.table = params.get('table') || '1';
                    if(this.cart.length === 0) window.location.href = '/menu?table=' + this.table;
                },

                get total() {
                    return this.cart.reduce((sum, item) => sum + (parseFloat(item.totalPrice) || 0), 0);
                },

                placeOrder() {
                    if(!this.method) return;
                    localStorage.setItem('ub_final_total', this.total.toFixed(2));
                    window.location.href = `/payment/gateway/${this.method}?table=${this.table}`;
                }
            }
        }
    </script>
</body>
</html>