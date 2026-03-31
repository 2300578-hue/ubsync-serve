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
        
        .section-box {
            border: 2px solid #e5e7eb; 
            background-color: #ffffff;
            border-radius: 1rem; /* Pinaliit nang konti para sa mobile */
        }

        @media (min-width: 640px) {
            .section-box { border-radius: 1.5rem; }
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
            top: -8px;
            right: -8px;
            color: #800000;
            background: white;
            border-radius: 50%;
            font-size: 1.2rem;
        }

        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
    </style>
</head>
<body x-data="checkoutApp()" x-init="init()" x-cloak class="py-4 sm:py-10">

    <div class="max-w-3xl mx-auto px-4 sm:px-6 space-y-4 sm:space-y-8">
        
        <div class="section-box p-6 sm:p-8 flex flex-col items-center justify-center relative overflow-hidden border-b-[4px] sm:border-b-[6px] border-b-maroon shadow-sm">
            <button @click="window.history.back()" class="absolute top-4 left-4 sm:top-6 sm:left-6 group flex items-center bg-[#f8f9fb] border border-[#eef1f6] py-2 px-3 sm:py-3 sm:px-6 rounded-full transition-all active:scale-95 shadow-sm">
                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="text-[9px] sm:text-[11px] font-extrabold text-[#707c91] uppercase tracking-wider">Back</span>
            </button>

            <h1 class="text-xl sm:text-3xl font-black text-gray-800 uppercase tracking-tighter">Checkout</h1>
            <div class="mt-2 sm:mt-3 px-4 py-1 bg-maroon text-white rounded-full">
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-center">Table <span x-text="table"></span></p>
            </div>
        </div>

        <section class="section-box overflow-hidden shadow-sm">
            <div class="p-4 sm:p-6 border-b-2 border-gray-100 flex items-center gap-3 bg-gray-50/50">
                <div class="text-maroon">
                    <i class="fas fa-receipt text-base sm:text-lg"></i>
                </div>
                <h2 class="font-bold text-[10px] sm:text-sm uppercase tracking-widest text-gray-500">Order Summary</h2>
            </div>
            
            <div class="overflow-y-auto max-h-[300px] sm:max-h-[400px] custom-scroll">
                <div class="divide-y divide-gray-50">
                    <template x-for="item in cart" :key="item.id">
                        <div class="p-4 sm:p-6 flex items-center gap-4 sm:gap-6 hover:bg-gray-50/30 transition-colors">
                            <div class="relative w-14 h-14 sm:w-20 sm:h-20 bg-gray-50 border border-gray-100 rounded-xl flex-shrink-0 overflow-hidden shadow-inner">
                                <img :src="item.image || 'https://placehold.co/100x100?text=Brew'" class="w-full h-full object-cover">
                            </div>

                            <div class="flex-grow min-w-0">
                                <h3 class="font-bold text-gray-800 text-sm sm:text-lg leading-tight mb-1 truncate" x-text="item.name"></h3>
                                <p class="text-[9px] sm:text-xs font-bold text-maroon border border-maroon/20 inline-block px-2 py-0.5 rounded uppercase bg-maroon/5">
                                    Qty: <span x-text="item.qty"></span>
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-gray-900 font-black text-sm sm:text-xl tracking-tight">₱<span x-text="parseFloat(item.totalPrice).toFixed(2)"></span></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </section>

        <section class="space-y-3">
            <h2 class="font-bold text-[10px] sm:text-xs uppercase tracking-[0.2em] text-gray-400 ml-2 italic">Payment Method</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                <div @click="method = 'gcash'" :class="method === 'gcash' ? 'active' : ''" 
                     class="payment-box p-4 sm:p-6 bg-white flex items-center gap-4 sm:flex-col sm:justify-center sm:text-center shadow-sm">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100">
                        <i class="fas fa-mobile-screen text-xl sm:text-2xl"></i>
                    </div>
                    <span class="font-bold text-xs sm:text-sm uppercase tracking-wider text-gray-700">GCash</span>
                </div>
                <div @click="method = 'maya'" :class="method === 'maya' ? 'active' : ''" 
                     class="payment-box p-4 sm:p-6 bg-white flex items-center gap-4 sm:flex-col sm:justify-center sm:text-center shadow-sm">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 border border-emerald-100">
                        <i class="fas fa-wallet text-xl sm:text-2xl"></i>
                    </div>
                    <span class="font-bold text-xs sm:text-sm uppercase tracking-wider text-gray-700">Maya</span>
                </div>
                <div @click="method = 'card'" :class="method === 'card' ? 'active' : ''" 
                     class="payment-box p-4 sm:p-6 bg-white flex items-center gap-4 sm:flex-col sm:justify-center sm:text-center shadow-sm">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-900 border border-gray-200">
                        <i class="fas fa-credit-card text-xl sm:text-2xl"></i>
                    </div>
                    <span class="font-bold text-xs sm:text-sm uppercase tracking-wider text-gray-700">Card</span>
                </div>
            </div>
        </section>

        <section class="section-box p-6 sm:p-10 border-t-[4px] sm:border-t-[6px] border-t-maroon shadow-sm">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6 sm:mb-10 text-center sm:text-left">
                <div>
                    <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">Total to Pay</p>
                    <p class="text-4xl sm:text-6xl font-black text-maroon tracking-tighter leading-none mt-1">₱<span x-text="total.toFixed(2)"></span></p>
                </div>
            </div>

            <button 
                @click="placeOrder()" 
                :disabled="!method"
                :class="!method ? 'bg-gray-200 text-gray-400 cursor-not-allowed border-2 border-gray-300' : 'bg-maroon text-white hover:bg-black active:translate-y-1 transition-all border-b-4 border-black shadow-lg shadow-maroon/20'"
                class="w-full py-4 sm:py-6 rounded-full font-black uppercase tracking-[0.15em] sm:tracking-[0.25em] text-lg sm:text-xl">
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
                    // I-redirect sa gateway depende sa pinili
                    window.location.href = `/payment/gateway/${this.method}?table=${this.table}`;
                }
            }
        }
    </script>
</body>
</html>