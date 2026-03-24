<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .ub-maroon { background-color: #800000; }
        .ub-text-maroon { color: #800000; }
        body {
            body {
         
             background-color: #f8f9fa; 
            margin: 0;
            padding: 0;
        }
        }
        
        .glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="glass rounded-3xl p-6 mb-8 flex justify-between items-center shadow-2xl border border-white/20">
            <div>
                <h1 class="text-4xl font-black ub-text-maroon tracking-tighter">UB SYNC</h1>
                <p class="text-gray-500 font-bold text-[10px] uppercase tracking-widest">Faculty Simulation Dashboard</p>
            </div>
            <div class="text-right flex items-center gap-4">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Instructor</p>
                    <p class="font-bold text-gray-800">{{ Auth::user()->name }}</p>
                </div>
                <a href="{{ route('logout') }}" class="ub-maroon text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-red-900 transition">Logout</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 text-center">
            <div class="glass p-6 rounded-3xl shadow-xl">
                <p class="text-[10px] font-black text-gray-400 uppercase">Total Sales</p>
                <h3 class="text-2xl font-black ub-text-maroon">₱ 0.00</h3>
            </div>
            <div class="glass p-6 rounded-3xl shadow-xl">
                <p class="text-[10px] font-black text-gray-400 uppercase">Completed Orders</p>
                <h3 class="text-2xl font-black text-gray-800">0</h3>
            </div>
            <div class="glass p-6 rounded-3xl shadow-xl">
                <p class="text-[10px] font-black text-gray-400 uppercase">Active Tables</p>
                <h3 class="text-2xl font-black text-blue-600">0/15</h3>
            </div>
            <div class="glass p-6 rounded-3xl shadow-xl">
                <p class="text-[10px] font-black text-gray-400 uppercase">Avg. Prep Time</p>
                <h3 class="text-2xl font-black text-orange-500">0m</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 glass rounded-3xl shadow-2xl overflow-hidden border border-white/30">
                <div class="ub-maroon p-4 text-white font-bold uppercase tracking-widest text-xs">Live Simulation Logs</div>
                <div class="p-4 text-sm text-gray-400 italic text-center py-20">Waiting for student activity...</div>
            </div>
            <div class="glass p-6 rounded-3xl shadow-2xl border border-white/30">
                <h2 class="font-bold text-gray-800 mb-4 border-b pb-2 uppercase text-[10px] tracking-widest">Admin Controls</h2>
                <div class="space-y-3">
                    <button class="w-full py-3 bg-gray-800 text-white rounded-xl text-xs font-bold hover:bg-gray-700 transition">Inventory Management</button>
                    <button class="w-full py-3 bg-gray-800 text-white rounded-xl text-xs font-bold hover:bg-gray-700 transition">Table QR Generator</button>
                    <button class="w-full py-3 ub-maroon text-white rounded-xl text-sm font-black shadow-lg hover:bg-red-900 transition">START SESSION</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>