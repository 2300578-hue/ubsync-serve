<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table {{ $tableNumber }} QR Code | UB Sync</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .border-maroon { border-color: #800000; }
        .text-maroon { color: #800000; }
        .bg-maroon { background-color: #800000; }
        
        @media print {
            @page { margin: 0; }
            body { background-color: white !important; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
            .no-print { display: none !important; }
            .shadow-2xl { box-shadow: none !important; border: 2px solid #f3f4f6; }
            .bg-gray-50 { background-color: white !important; border: none !important; }
        }
    </style>
</head>
<body class="bg-slate-50 flex flex-col items-center justify-center min-h-screen p-4 sm:p-6">
    
    <div class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-2xl text-center max-w-sm w-full border-t-[12px] border-maroon transition-all">
        <span class="text-[10px] sm:text-xs font-black text-gray-400 uppercase tracking-[0.3em]">UB-SYNCSERVE</span>
        
        <h1 class="text-4xl sm:text-5xl font-black text-gray-900 mt-2 mb-4 sm:mb-6 tracking-tighter uppercase">
            Table {{ $tableNumber }}
        </h1>

        <div class="bg-gray-50 p-4 sm:p-6 rounded-[1.5rem] sm:rounded-3xl mb-6 inline-block shadow-inner border border-gray-100">
            {{-- QR Code API - Binago papunta sa customer/info --}}
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode(url('/customer/info?table=' . $tableNumber)) }}" 
                 alt="QR Code" 
                 class="mx-auto w-56 h-56 sm:w-64 sm:h-64 shadow-sm rounded-xl object-contain">
        </div>

        <div class="space-y-4">
            <p class="text-gray-500 font-medium italic text-xs sm:text-sm px-2">
                Scan the QR code above to enter your details <br class="hidden sm:block"> and view our digital menu.
            </p>
            
            <div class="pt-4 border-t border-gray-100 no-print">
                <a href="{{ url('/customer/info?table=' . $tableNumber) }}" 
                   class="w-full inline-flex items-center justify-center gap-2 text-maroon font-bold hover:text-red-900 transition-all text-[10px] sm:text-xs uppercase tracking-[0.2em] bg-red-50 hover:bg-red-100 px-4 py-3 rounded-xl border border-red-100">
                    <span>Order for this Table</span>
                    <i class="fa-solid fa-utensils"></i>
                </a>
            </div>
        </div>
    </div>

</body>
</html>