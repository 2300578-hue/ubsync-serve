<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uncle Brew | Processing {{ ucfirst($method) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #fcfcfc; font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
        
        /* QR Scan Animation */
        .scan-line {
            width: 100%; height: 4px; background: #800000;
            position: absolute; left: 0;
            animation: scanMove 3s infinite ease-in-out;
            box-shadow: 0 0 20px #800000;
        }
        @keyframes scanMove { 0%, 100% { top: 0; } 50% { top: 100%; } }

        .gateway-card {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            border: 1px solid #f3f4f6;
        }
    </style>
</head>
<body x-data="gatewayApp()" x-init="init()" class="min-h-screen flex items-center justify-center p-4 sm:p-6">

    <div class="bg-white rounded-[2.5rem] sm:rounded-[4rem] p-8 sm:p-16 max-w-lg w-full text-center gateway-card relative overflow-hidden">
        
        <div x-show="!isDone && method !== 'card'" x-cloak>
            <div class="mb-6 sm:mb-10">
                <h3 class="text-2xl sm:text-3xl font-black uppercase tracking-tighter text-gray-900">{{ $method }} Pay</h3>
                <p class="text-[9px] sm:text-[10px] font-bold text-maroon uppercase tracking-widest mt-2">Scan QR to Complete Payment</p>
            </div>

            <div class="relative w-48 h-48 sm:w-64 sm:h-64 mx-auto bg-gray-50 rounded-[2rem] sm:rounded-[3rem] p-6 sm:p-8 border-4 sm:border-8 border-gray-100 mb-8 sm:mb-10 overflow-hidden">
                <div class="scan-line"></div>
                <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=UNCLE_BREW_' + method" class="w-full h-full grayscale opacity-70">
            </div>

            <div class="space-y-4">
                <h2 class="text-4xl sm:text-5xl font-black tracking-tighter text-gray-900">₱<span x-text="total"></span></h2>
                <div class="flex items-center justify-center gap-3 text-gray-400">
                    <i class="fas fa-circle-notch animate-spin text-xs"></i>
                    <p class="text-[10px] sm:text-xs font-bold uppercase tracking-widest">Waiting for scan...</p>
                </div>
            </div>
        </div>

        <div x-show="!isDone && method === 'card'" x-cloak>
            <div class="mb-6 sm:mb-8 text-left">
                <h3 class="text-2xl sm:text-3xl font-black uppercase tracking-tighter text-gray-900">Card Details</h3>
                <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-2">Enter your card information</p>
            </div>

            <div class="space-y-3 sm:space-y-4 mb-8 sm:mb-10 text-left">
                <div>
                    <label class="text-[9px] sm:text-[10px] font-black uppercase text-gray-400 ml-4 mb-1 block">Card Number</label>
                    <input type="text" placeholder="0000 0000 0000 0000" class="w-full p-4 sm:p-5 bg-white border-2 border-gray-100 rounded-2xl sm:rounded-3xl outline-none focus:border-maroon transition-all font-bold text-sm sm:text-base">
                </div>
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="text-[9px] sm:text-[10px] font-black uppercase text-gray-400 ml-4 mb-1 block">Expiry</label>
                        <input type="text" placeholder="MM/YY" class="w-full p-4 sm:p-5 bg-white border-2 border-gray-100 rounded-2xl sm:rounded-3xl outline-none focus:border-maroon transition-all font-bold text-center text-sm sm:text-base">
                    </div>
                    <div>
                        <label class="text-[9px] sm:text-[10px] font-black uppercase text-gray-400 ml-4 mb-1 block">CVV</label>
                        <input type="password" placeholder="***" class="w-full p-4 sm:p-5 bg-white border-2 border-gray-100 rounded-2xl sm:rounded-3xl outline-none focus:border-maroon transition-all font-bold text-center text-sm sm:text-base">
                    </div>
                </div>
            </div>

            <div class="bg-maroon/5 p-4 sm:p-6 rounded-2xl sm:rounded-3xl mb-6 sm:mb-8 flex justify-between items-center">
                <span class="font-bold text-gray-500 uppercase text-[10px] sm:text-xs">Total:</span>
                <span class="font-black text-maroon text-lg sm:text-xl">₱<span x-text="total"></span></span>
            </div>

            <button @click="processPayment()" class="w-full py-4 sm:py-6 bg-maroon text-white rounded-2xl sm:rounded-[2rem] font-black uppercase tracking-widest shadow-xl shadow-maroon/30 transition-all active:scale-95 text-sm sm:text-base">
                Authorize Payment
            </button>
        </div>

        <div x-show="isDone" x-transition.duration.500ms x-cloak>
            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 sm:mb-8 text-3xl sm:text-4xl shadow-inner">
                <i class="fas fa-check"></i>
            </div>
            <h2 class="text-4xl sm:text-5xl font-black text-gray-900 uppercase tracking-tighter mb-2 sm:mb-4">Paid!</h2>
            <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-8 sm:mb-12">Your order is now being prepared</p>
            
            <button @click="backToMenu()" class="w-full py-4 sm:py-6 bg-maroon text-white rounded-2xl sm:rounded-[2rem] font-black uppercase tracking-widest shadow-xl shadow-maroon/30 transition-all hover:brightness-110 active:scale-95 text-sm sm:text-base">
                Back to Menu
            </button>
        </div>
    </div>

<script>
function gatewayApp() {
    return {
        // --- Properties ---
        method: "{{ $method }}", 
        // 1. Kunin ang total mula sa storage
        total: localStorage.getItem('ub_final_total') || '0.00',
        isDone: false,
        
        // 2. LAYERED DEFENSE PARA SA PANGALAN AT TABLE
        // Sinisiguro nito na kung wala sa URL, kukuha sa LocalStorage na sinave ng Menu Page
        table: new URLSearchParams(window.location.search).get('table') || localStorage.getItem('ub_current_table') || '1',
        fname: new URLSearchParams(window.location.search).get('fname') || localStorage.getItem('ub_customer_fname') || 'Guest',
        lname: new URLSearchParams(window.location.search).get('lname') || localStorage.getItem('ub_customer_lname') || '',

        // --- Initialization ---
        init() {
            console.log("Gateway initialized for: " + this.fname); // Para makita mo sa Console kung sino ang binabasa

            // Kung GCash o Maya, may 4 seconds delay bago mag-auto-complete (simulation)
            if (this.method !== 'card') {
                setTimeout(() => {
                    this.isDone = true;
                    this.sendOrderToCashier(); 
                }, 4000);
            }
        },

        // --- Actions ---
        processPayment() {
            // Para sa Card payment button manual trigger
            this.isDone = true;
            this.sendOrderToCashier(); 
        },

        sendOrderToCashier() {
            // 1. Kunin ang cart items mula sa storage
            const cartData = JSON.parse(localStorage.getItem('ub_cart')) || [];
            
            if(cartData.length > 0) {
                // 2. Pag-aayos ng images: Siguraduhin na .png lahat bago isend sa cashier
                // LINE 46: Simula ng pag-aayos
const sanitizedCart = cartData.map(item => {
    // 1. Kunin ang raw image name (check img or image property)
    let rawImage = item.img || item.image || 'default.png';
    
    // 2. Linisin: Tanggalin ang spaces, gawing lowercase
    let cleanedName = rawImage.trim().toLowerCase();
    
    // 3. Extension Guard: Siguraduhin na may .png (iwas sa .png.png)
    if (!cleanedName.includes('.')) {
        cleanedName = cleanedName + '.png';
    }

    return {
        ...item,
        img: cleanedName // Ito ang ipapasa sa orderPayload.cart
    };
});


                // 3. Pag-prepare ng data na ibabato sa Cashier Dashboard
                const orderPayload = {
                    table: this.table,
                    fname: this.fname, // SIGURADO NANG MAY LAMAN ITO
                    lname: this.lname,
                    bill: parseFloat(this.total),
                    method: this.method,
                    cart: sanitizedCart,
                    timestamp: Date.now() 
                };
                
                // 4. TRIGGER: Ito ang babasahin ng cashier.blade.php
                localStorage.setItem('ub_new_customer_order', JSON.stringify(orderPayload));
                
                console.log("Order successfully transmitted for: " + this.fname);
            } else {
                console.error("Cart is empty. Nothing to send.");
            }
        },

        backToMenu() {
            // Linisin ang cart pagkatapos ng successful order
            localStorage.removeItem('ub_cart');
            localStorage.removeItem('ub_final_total');

            // Bumalik sa menu dala pa rin ang table at name (para sa repeat orders)
            // Encode natin para safe ang URL
            let encodedFname = encodeURIComponent(this.fname);
            let encodedLname = encodeURIComponent(this.lname);
            
            window.location.href = `/customer/menu?table=${this.table}&fname=${encodedFname}&lname=${encodedLname}`;
        }
    }
}
</script>


</body>
</html>