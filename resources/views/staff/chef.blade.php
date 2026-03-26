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
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .mono { font-family: 'JetBrains Mono', monospace; }
        
        /* Glass Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .maroon-gradient { background: linear-gradient(135deg, #800000 0%, #4a0000 100%); }

        /* Priority Borders */
        .priority-low { border-top: 10px solid #10b981; }      /* Green: 0-5 mins */
        .priority-medium { border-top: 10px solid #f59e0b; }   /* Amber: 5-10 mins */
        .priority-high { 
            border-top: 10px solid #ef4444; 
            animation: pulse-red 2s infinite; 
        }

        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        /* Checklist Strike-through */
        .done-item { 
            text-decoration: line-through; 
            opacity: 0.3; 
            filter: grayscale(1);
        }

        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen pb-12" x-data="kitchenHandler()">

    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200 px-8 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 maroon-gradient rounded-2xl flex items-center justify-center shadow-lg shadow-red-900/20 rotate-3">
                <i class="fa-solid fa-fire-burner text-2xl text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black tracking-tighter text-slate-800 uppercase leading-none text-red-900">UB SYNC SERVE</h1>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.4em] mt-1">Kitchen Command Center</p>
            </div>
        </div>

        <div class="flex items-center gap-8">
            <div class="hidden lg:flex items-center gap-3 bg-slate-100 px-4 py-2 rounded-2xl border border-slate-200">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-ping"></span>
                <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">POS SYNC ACTIVE</span>
            </div>

            <div class="text-right">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">System Time</p>
                <p class="text-xl font-black text-slate-700 mono" x-text="currentTime"></p>
            </div>

            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="button" @click="confirmLogout()" class="w-12 h-12 rounded-2xl bg-white border border-slate-200 text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-power-off text-xl"></i>
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-[1750px] mx-auto p-8 space-y-8">
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="glass-card p-6 rounded-[2.5rem] border-l-8 border-blue-500 shadow-xl">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Tickets</p>
                <h3 class="text-4xl font-black text-slate-800 mono" x-text="orders.length"></h3>
            </div>

            <div class="glass-card p-6 rounded-[2.5rem] border-l-8 border-amber-500 shadow-xl">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Inventory (Patties)</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-black text-slate-800 mono" x-text="inventory.burgers"></h3>
                    <span class="text-xs font-bold text-slate-400">/ 50</span>
                </div>
            </div>

            <div class="glass-card p-6 rounded-[2.5rem] border-l-8 border-emerald-500 shadow-xl">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Orders Served</p>
                <h3 class="text-4xl font-black text-slate-800 mono" x-text="fulfilledCount"></h3>
            </div>

            <div @click="simulateNewOrder()" class="maroon-gradient p-6 rounded-[2.5rem] shadow-2xl shadow-red-900/40 cursor-pointer hover:scale-[1.02] active:scale-95 transition-all group">
                <div class="flex justify-between items-start text-white">
                    <div>
                        <p class="text-[10px] font-black opacity-60 uppercase tracking-widest">Simulator</p>
                        <h3 class="text-xl font-black uppercase leading-tight">New Order<br>Incoming</h3>
                    </div>
                    <i class="fa-solid fa-plus-circle text-2xl group-hover:rotate-90 transition-transform"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <template x-for="(order, index) in orders" :key="order.id">
                <div :class="{
                        'priority-low': order.minutes < 5,
                        'priority-medium': order.minutes >= 5 && order.minutes < 10,
                        'priority-high': order.minutes >= 10
                    }" 
                    class="bg-white rounded-[3rem] overflow-hidden shadow-2xl flex flex-col transition-all duration-500">
                    
                    <div class="px-8 py-6 bg-slate-50/80 flex justify-between items-start border-b border-slate-100">
                        <div>
                            <span class="text-[10px] font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded mb-2 inline-block tracking-widest" x-text="'#' + order.id"></span>
                            <h2 class="text-5xl font-black text-slate-800 mono tracking-tighter" x-text="order.table"></h2>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-slate-400 uppercase mb-1 tracking-tighter">Cook Time</p>
                            <div class="px-3 py-1 bg-white rounded-xl border border-slate-200 shadow-sm">
                                <p class="text-xl font-black mono text-red-800" x-text="order.minutes + ':' + order.seconds"></p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 flex-1 space-y-4 overflow-y-auto max-h-[350px] custom-scroll">
                        <template x-for="(item, iIdx) in order.items">
                            <div @click="item.done = !item.done" 
                                class="group flex items-center gap-4 p-4 rounded-2xl hover:bg-slate-50 cursor-pointer transition-all border border-transparent hover:border-slate-100">
                                <div :class="item.done ? 'bg-emerald-500 shadow-emerald-200' : 'bg-slate-200'" 
                                    class="w-10 h-10 rounded-xl flex items-center justify-center text-white transition-all shadow-lg">
                                    <i class="fa-solid fa-check text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 :class="item.done ? 'done-item' : ''" 
                                        class="font-black text-slate-800 uppercase text-md leading-tight" x-text="item.name"></h4>
                                    <div x-show="item.details" class="mt-2 bg-red-50 border border-red-100 px-3 py-1 rounded-lg inline-block">
                                        <p class="text-[10px] font-black text-red-600 uppercase italic" x-text="item.details"></p>
                                    </div>
                                </div>
                                <div class="text-lg font-black text-slate-300" x-text="'x'+item.qty"></div>
                            </div>
                        </template>
                    </div>

                    <div class="p-8 bg-slate-50 border-t border-slate-100">
                        <button @click="serveOrder(index)" class="w-full py-5 maroon-gradient text-white rounded-[2rem] text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-red-900/30 hover:shadow-red-900/50 hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-3">
                            <span>Order Ready</span>
                            <i class="fa-solid fa-bell-concierge"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </nav>

    <script>
        function kitchenHandler() {
            return {
                currentTime: '',
                fulfilledCount: 124,
                inventory: { burgers: 42 },
                orders: [
                    {
                        id: '8821',
                        table: 'T-04',
                        minutes: 3,
                        seconds: '15',
                        items: [
                            { qty: 2, name: 'UB Burger Deluxe', details: 'NO ONIONS - ALLERGY', done: false },
                            { qty: 1, name: 'Truffle Fries', details: 'EXTRA CHEESE', done: false }
                        ]
                    },
                    {
                        id: '8825',
                        table: 'T-12',
                        minutes: 11, // High Priority (Red)
                        seconds: '42',
                        items: [
                            { qty: 1, name: 'Spicy Carbonara', details: 'MILD ONLY', done: false }
                        ]
                    }
                ],
                init() {
                    this.updateTime();
                    setInterval(() => {
                        this.updateTime();
                        this.incrementTimers();
                    }, 1000);
                },
                updateTime() {
                    const now = new Date();
                    this.currentTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
                },
                incrementTimers() {
                    this.orders.forEach(order => {
                        let sec = parseInt(order.seconds) + 1;
                        if(sec >= 60) {
                            order.minutes++;
                            sec = 0;
                        }
                        order.seconds = sec < 10 ? '0' + sec : sec.toString();
                    });
                },
                simulateNewOrder() {
                    new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3').play();
                    this.orders.push({
                        id: Math.floor(Math.random() * 9000) + 1000,
                        table: 'T-' + (Math.floor(Math.random() * 20) + 1),
                        minutes: 0,
                        seconds: '00',
                        items: [
                            { qty: 1, name: 'Simulated Paid Order', details: 'PRIORITY', done: false }
                        ]
                    });
                },
                serveOrder(index) {
                    if(confirm("Notify Customer & POS that this order is ready?")) {
                        this.inventory.burgers -= 1;
                        this.orders.splice(index, 1);
                        this.fulfilledCount++;
                        new Audio('https://assets.mixkit.co/active_storage/sfx/1435/1435-preview.mp3').play();
                    }
                },
                confirmLogout() {
                    if(confirm("Shut down Kitchen Console?")) {
                        document.getElementById('logout-form').submit();
                    }
                }
            }
        }
    </script>
</body>
</html>