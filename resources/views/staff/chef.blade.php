<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UB Sync | Kitchen Display</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .ub-maroon { background-color: #800000; } 
        .ub-text-maroon { color: #800000; }
        .order-card { transition: all 0.3s ease; }
        .order-card:hover { transform: translateY(-4px); }
    </style>
</head>
<body class="bg-zinc-950 text-white min-h-screen font-sans">
    
    <div class="border-b border-white/10 p-4 flex justify-between items-center bg-zinc-900/50 backdrop-blur-md sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 ub-maroon rounded flex items-center justify-center font-black">UB</div>
            <h1 class="text-xl font-black ub-text-maroon tracking-tighter">KITCHEN <span class="text-white">DISPLAY</span></h1>
        </div>

        <div class="flex gap-6 items-center">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-[10px] font-bold text-green-400 uppercase tracking-widest">Production Live</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs font-bold text-gray-500 hover:text-red-500 transition-colors uppercase tracking-widest">
                    Exit System
                </button>
            </form>
        </div>
    </div>

    <main class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        
        <div class="bg-white text-black rounded-2xl overflow-hidden shadow-2xl flex flex-col order-card">
            <div class="bg-red-800 p-4 text-white flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-bold opacity-70 uppercase mb-0.5">Location</p>
                    <span class="font-black text-xl tracking-tight">TABLE #05</span>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold opacity-70 uppercase mb-0.5">Time</p>
                    <span class="font-mono text-lg font-bold">05:22</span>
                </div>
            </div>

            <div class="p-5 flex-1 bg-gray-50">
                <ul class="font-extrabold space-y-3 text-xl text-zinc-800">
                    <li class="flex items-center gap-2">
                        <span class="text-red-700">2x</span> 
                        <span>UB Special Burger</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-red-700">1x</span> 
                        <span>Iced Americano</span>
                    </li>
                </ul>
                
                <div class="mt-8 pt-4 border-t border-dashed border-gray-300">
                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Guest Name</p>
                    <p class="font-bold text-gray-900">JUAN DELA CRUZ</p>
                </div>
            </div>

            <button class="w-full bg-zinc-900 text-white font-black py-5 uppercase tracking-[0.2em] text-sm hover:bg-green-600 transition-all active:scale-95">
                Mark as Served
            </button>
        </div>

    </main>
</body>
</html>