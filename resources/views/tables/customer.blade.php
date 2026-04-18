<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table {{ $tableNumber }} QR Code | UB Sync</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .border-maroon { border-color: #800000; }
        .text-maroon { color: #800000; }
        .bg-maroon { background-color: #800000; }
        [x-cloak] { display: none !important; }
        
        @media print {
            @page { margin: 0; }
            body { background-color: white !important; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
            .no-print { display: none !important; }
            .shadow-2xl { box-shadow: none !important; border: 2px solid #f3f4f6; }
            .bg-gray-50 { background-color: white !important; border: none !important; }
        }
    </style>
</head>
<body class="bg-slate-50 flex flex-col items-center justify-center min-h-screen p-4 sm:p-6" 
      x-data="{ 
        showNameModal: false, 
        firstName: '', 
        lastName: '',
        tableNumber: '{{ $tableNumber }}',
        baseUrl: '{{ url('/menu') }}',
        
        proceedToMenu() {
            if(!this.firstName.trim() || !this.lastName.trim()) {
                alert('Please enter both First and Last Name.');
                return;
            }
            
            // I-save sa localStorage para sa backup
            const fullName = this.firstName.trim() + ' ' + this.lastName.trim();
            localStorage.setItem('ub_customer_name', fullName);
            localStorage.setItem('ub_current_table', this.tableNumber);
            
            // I-redirect sa menu dala ang table, fname, at lname sa URL
            const finalUrl = this.baseUrl + 
                             '?table=' + this.tableNumber + 
                             '&fname=' + encodeURIComponent(this.firstName.trim()) + 
                             '&lname=' + encodeURIComponent(this.lastName.trim());
                             
            window.location.href = finalUrl;
        }
      }">
    
    <div class="no-print flex gap-3 mb-6">
        <button onclick="window.print()" class="bg-maroon text-white px-4 py-2 rounded-xl font-bold text-[11px] shadow-md hover:bg-red-900 transition-all uppercase tracking-widest">
            <i class="fa-solid fa-print mr-1"></i> Print QR
        </button>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-2xl text-center max-w-sm w-full border-t-[12px] border-maroon transition-all">
        <span class="text-[10px] sm:text-xs font-black text-gray-400 uppercase tracking-[0.3em]">UB-SYNCSERVE</span>
        
        <h1 class="text-4xl sm:text-5xl font-black text-gray-900 mt-2 mb-4 sm:mb-6 tracking-tighter uppercase">
            Table {{ $tableNumber }}
        </h1>

        <div class="bg-gray-50 p-4 sm:p-6 rounded-[1.5rem] sm:rounded-3xl mb-6 inline-block shadow-inner border border-gray-100">
            {{-- QR Code API --}}
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode(url('/menu?table=' . $tableNumber)) }}" 
                 alt="QR Code" 
                 class="mx-auto w-56 h-56 sm:w-64 sm:h-64 shadow-sm rounded-xl object-contain">
        </div>

        <div class="space-y-4">
            <p class="text-gray-500 font-medium italic text-xs sm:text-sm px-2">
                Scan the QR code above to view our <br class="hidden sm:block"> digital menu and place your order.
            </p>
            
            <div class="pt-4 border-t border-gray-100 no-print">
                <button @click="showNameModal = true" 
                   class="w-full inline-flex items-center justify-center gap-2 text-maroon font-bold hover:text-red-900 transition-all text-[10px] sm:text-xs uppercase tracking-[0.2em] bg-red-50 hover:bg-red-100 px-4 py-3 rounded-xl border border-red-100">
                    <span>Order for this Table</span>
                    <i class="fa-solid fa-utensils"></i>
                </button>
            </div>
        </div>
    </div>

    <div x-show="showNameModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        
        <div class="bg-white w-full max-w-sm p-8 rounded-[2.5rem] relative z-10 shadow-2xl" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-50 text-maroon rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-user-tag text-2xl"></i>
                </div>
                <h3 class="font-black text-gray-800 uppercase text-sm tracking-widest">Customer Information</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Table {{ $tableNumber }}</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">First Name</label>
                    <input type="text" x-model="firstName" placeholder="e.g. Juan" 
                           @keydown.enter="proceedToMenu()"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold bg-gray-50 focus:bg-white focus:border-maroon focus:ring-2 focus:ring-red-900/10 outline-none transition-all">
                </div>
                <div>
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Last Name</label>
                    <input type="text" x-model="lastName" placeholder="e.g. Dela Cruz" 
                           @keydown.enter="proceedToMenu()"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold bg-gray-50 focus:bg-white focus:border-maroon focus:ring-2 focus:ring-red-900/10 outline-none transition-all">
                </div>
                
                <button @click="proceedToMenu()" 
                        class="w-full bg-maroon text-white py-4 rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-red-900/20 active:scale-95 transition-all mt-4">
                    Proceed to Menu
                </button>
                
                <button type="button" 
                        @click="showNameModal = false" 
                        class="w-full text-gray-400 hover:text-gray-600 font-bold text-[10px] uppercase tracking-widest py-2 transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>

</body>
</html>