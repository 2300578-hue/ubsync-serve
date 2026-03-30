<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table {{ $tableNumber }} QR Code</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .border-maroon { border-color: #800000; }
        .text-maroon { color: #800000; }
        @media print {
            .pt-4.border-t {
                display: none; /* Tinatago ang Test Link sa print */
            }
            body {
                background-color: white !important;
            }
            .shadow-2xl {
                box-shadow: none !important;
                border: 1px solid #f3f4f6;
            }
            .bg-gray-100 {
                background-color: white !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-[2.5rem] shadow-2xl text-center max-w-sm w-full border-t-[12px] border-maroon">
        
        <span class="text-xs font-black text-gray-400 uppercase tracking-[0.3em]"></span>
        <h1 class="text-5xl font-black text-gray-900 mt-2 mb-6 tracking-tighter uppercase">
            Table {{ $tableNumber }}
        </h1>

        <div class="bg-gray-50 p-6 rounded-3xl mb-6 inline-block shadow-inner border border-gray-100">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode(url('/menu?table=' . $tableNumber)) }}" 
                 alt="QR Code" 
                 class="mx-auto w-64 h-64 shadow-sm rounded-xl">
        </div>

        <div class="space-y-4">
            <p class="text-gray-500 font-medium italic text-sm">
                Scan the QR code above to view our <br> digital menu and place your order.
            </p>
            
            <div class="pt-4 border-t border-gray-100">
                <a href="{{ url('/menu?table=' . $tableNumber) }}" 
                   class="inline-flex items-center gap-2 text-maroon font-bold hover:opacity-70 transition-all text-sm uppercase tracking-widest">
                    <span>Test Order Link</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</body>
</html>