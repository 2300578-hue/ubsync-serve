<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Culinary Command | UB-SYNC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc; 
            background-image: radial-gradient(#e2e8f0 1.5px, transparent 1.5px);
            background-size: 30px 30px;
            color: #1e293b;
        }
        .mono { font-family: 'JetBrains Mono', monospace; }
        
        /* Premium Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.01);
        }

        .maroon-gradient { 
            background: linear-gradient(135deg, #800000 0%, #4a0000 100%); 
        }

        /* Ticket Design */
        .ticket-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.02), 0 4px 6px -2px rgba(0, 0, 0, 0.01);
            border: 1px solid #f1f5f9;
        }

        /* Custom Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

        [x-cloak] { display: none !important; }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide { animation: slideIn 0.3s ease-out forwards; }
    </style>
</head>
<body class="min-h-screen pb-12" x-data="kitchenHandler()">

    <nav class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl border-b border-slate-200/60 px-8 py-4 flex justify-between items-center">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 maroon-gradient rounded-2xl flex items-center justify-center shadow-xl shadow-red-900/20 rotate-3 hover:rotate-0 transition-transform duration-500">
                <i class="fa-solid fa-utensils text-2xl text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black tracking-tighter text-slate-800 uppercase">UB <span class="text-[#800000]">Sync</span> Serve</h1>
                <div class="flex items-center gap-2">
                    <span class="flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em]">Kitchen Command Center</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-8">
            <div class="text-right hidden md:block">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">System Clock</p>
                <p class="text-lg font-black text-slate-700 mono leading-none" x-text="currentTime"></p>
            </div>

            <div class="h-10 w-[1px] bg-slate-200"></div>

            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="button" @click="confirmLogout()" 
                    class="group relative flex items-center justify-center w-12 h-12 bg-white rounded-2xl border border-slate-200 text-slate-400 hover:border-red-200 hover:text-red-600 hover:bg-red-50 transition-all duration-300 shadow-sm active:scale-90">
                    <i class="fa-solid fa-power-off text-xl"></i>
                    <span class="absolute -bottom-10 opacity-0 group-hover:opacity-100 transition-opacity bg-red-600 text-white text-[10px] font-bold px-3 py-1 rounded-lg whitespace-nowrap shadow-lg">Exit System</span>
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-[1700px] mx-auto p-8 space-y-10">
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="glass-card p-6 rounded-[2.5rem] flex items-center gap-5">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 text-xl shadow-inner">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Tickets</p>
                    <h3 class="text-3xl font-black text-slate-800 mono" x-text="orders.length"></h3>
                </div>
            </div>

            <div class="glass-card p-6 rounded-[2.5rem] flex items-center gap-5">
                <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 text-xl shadow-inner">
                    <i class="fa-solid fa-fire animate-pulse"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Average Prep</p>
                    <h3 class="text-3xl font-black text-slate-800 mono">12<span class="text-sm ml-1 font-bold">min</span></h3>
                </div>
            </div>

            <div class="glass-card p-6 rounded-[2.5rem] flex items-center gap-5">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 text-xl shadow-inner">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Served Today</p>
                    <h3 class="text-3xl font-black text-slate-800 mono" x-text="fulfilledCount"></h3>
                </div>
            </div>

            <div @click="openRecipeModal = true" class="maroon-gradient p-6 rounded-[2.5rem] flex items-center gap-5 cursor-pointer hover:scale-[1.02] transition-transform shadow-2xl shadow-red-900/20 group">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-white text-xl border border-white/20">
                    <i class="fa-solid fa-book-open group-hover:rotate-12 transition-transform"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-white/60 uppercase tracking-widest">Chef's Handbook</p>
                    <h3 class="text-xl font-black text-white leading-tight">View Recipes</h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <template x-for="(order, index) in orders" :key="order.id">
                <div class="bg-white rounded-[3rem] overflow-hidden ticket-shadow flex flex-col animate-slide group border-t-8 border-[#800000]">
                    <div class="px-8 py-6 flex justify-between items-start bg-slate-50/50">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[9px] font-black px-2 py-0.5 rounded bg-red-100 text-red-600 uppercase tracking-widest" x-show="order.isRush">Rush</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase mono" x-text="'#' + order.id"></span>
                            </div>
                            <h2 class="text-5xl font-black text-slate-800 tracking-tighter mono" x-text="order.table"></h2>
                        </div>
                        <div class="bg-white px-4 py-2 rounded-2xl border border-slate-100 text-center shadow-sm">
                            <p class="text-[9px] font-black text-slate-400 uppercase">Time</p>
                            <p class="text-lg font-black text-amber-600 mono" x-text="order.elapsed"></p>
                        </div>
                    </div>

                    <div class="p-8 flex-1 space-y-6">
                        <template x-for="item in order.items">
                            <div class="flex items-start gap-4 p-4 rounded-3xl bg-slate-50/50 border border-transparent hover:border-slate-100 transition-colors">
                                <div class="w-10 h-10 maroon-gradient rounded-xl flex items-center justify-center text-white font-black text-lg shadow-lg" x-text="item.qty"></div>
                                <div class="flex-1">
                                    <h4 class="font-black text-slate-800 text-lg leading-none uppercase tracking-tight" x-text="item.name"></h4>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1 italic" x-text="item.details"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="p-8 pt-0 mt-auto">
                        <div class="flex items-center justify-between mb-4 px-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Staff: <span class="text-slate-800 ml-1" x-text="order.staff"></span></p>
                            <i class="fa-solid fa-ellipsis text-slate-300"></i>
                        </div>
                        <button @click="serveOrder(index)" 
                            class="w-full py-5 maroon-gradient text-white rounded-[2rem] text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-red-900/20 hover:shadow-red-900/40 hover:-translate-y-1 active:scale-95 transition-all duration-300 flex items-center justify-center gap-3">
                            <span>Ready to Serve</span>
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </main>

    <div x-show="openRecipeModal" x-cloak 
        class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/40 backdrop-blur-md"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
        <div class="bg-white/90 backdrop-blur-2xl w-full max-w-xl rounded-[3rem] p-10 shadow-2xl border border-white" @click.away="openRecipeModal = false">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-black text-slate-800 tracking-tighter uppercase">Kitchen <span class="text-[#800000]">Guide</span></h2>
                <button @click="openRecipeModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-2xl"></i></button>
            </div>
            <div class="space-y-4 max-h-[400px] overflow-y-auto custom-scroll pr-4">
                <div class="p-5 bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <p class="font-black text-slate-800 uppercase tracking-tight">Classic UB Burger</p>
                    <p class="text-xs text-slate-500 mt-1">Brioche Bun, 150g Angus Beef, Double Cheese, Caramelized Onions.</p>
                </div>
                <div class="p-5 bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <p class="font-black text-slate-800 uppercase tracking-tight">Garlic Truffle Fries</p>
                    <p class="text-xs text-slate-500 mt-1">Russet Potatoes, Truffle Oil, Parmesan, Fresh Parsley.</p>
                </div>
            </div>
            <button @click="openRecipeModal = false" class="mt-8 w-full py-4 bg-slate-900 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg active:scale-95 transition-all">Close Handbook</button>
        </div>
    </div>

    <script>
        function kitchenHandler() {
            return {
                currentTime: '',
                openRecipeModal: false,
                fulfilledCount: 124,
                orders: [
                    {
                        id: '7721',
                        table: 'T-04',
                        isRush: true,
                        elapsed: '08:45',
                        staff: 'Chef Marco',
                        items: [
                            { qty: 2, name: 'Classic UB Burger', details: 'No Pickles, Medium Rare' },
                            { qty: 1, name: 'Garlic Truffle Fries', details: 'Extra Parmesan' }
                        ]
                    },
                    {
                        id: '7725',
                        table: 'T-12',
                        isRush: false,
                        elapsed: '03:12',
                        staff: 'Chef Liza',
                        items: [
                            { qty: 1, name: 'Carbonara Pasta', details: 'No Onions' }
                        ]
                    }
                ],
                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                },
                updateTime() {
                    const now = new Date();
                    this.currentTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
                },
                serveOrder(index) {
                    if(confirm("Confirm: Order is ready for dispatch?")) {
                        this.orders.splice(index, 1);
                        this.fulfilledCount++;
                        // Play a subtle chime
                        new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3').play();
                    }
                },
                confirmLogout() {
                    if(confirm("System Alert: You are about to shut down the kitchen console. Continue?")) {
                        // Mag-su-submit ng Laravel logout form
                        document.getElementById('logout-form').submit();
                    }
                }
            }
        }
    </script>
</body>
</html>