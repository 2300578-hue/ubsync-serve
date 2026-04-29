<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Command | UB SYNC</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f2f3f3; color: #334155; overflow-x: hidden; }
        [x-cloak] { display: none !important; }

        .aws-header { background-color: #800000; height: 65px; display: flex; align-items: center; justify-content: space-between; padding: 0 25px; color: white; position: fixed; top: 0; width: 100%; z-index: 1000; }
        .gold-accent { background-color: #D4AF37; height: 4px; position: fixed; top: 65px; width: 100%; z-index: 999; }
        .aws-sidebar { width: 260px; background: white; border-right: 1px solid #eaeded; height: calc(100vh - 69px); position: fixed; top: 69px; left: 0; transition: all 0.3s ease; z-index: 1000; }
        .sidebar-collapsed { left: -260px; }
        .main-content { margin-left: 260px; margin-top: 69px; padding: 25px; transition: all 0.3s ease; min-height: calc(100vh - 69px); }
        .content-wide { margin-left: 0; width: 100%; }
        
        .clay-card, .aws-card { background: white; border: 1px solid #eaeded; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }

        .kitchen-checkbox {
            appearance: none; width: 1.5rem; height: 1.5rem; border: 2px solid #cbd5e1; border-radius: 4px; cursor: pointer; position: relative;
        }
        .kitchen-checkbox:checked { background-color: #991b1b; border-color: #991b1b; }
        .kitchen-checkbox:checked::after { content: '✓'; color: white; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: bold; }

        @media print {
            /* 1. Itago nang buo ang dashboard UI */
            .aws-header, .gold-accent, .aws-sidebar, .main-content {
                display: none !important;
            }

            /* 2. Ilabas at ipwesto ang resibo */
            #kitchen-receipt { 
                position: absolute !important; 
                left: 0 !important; 
                top: 0 !important; 
                width: 80mm !important; 
                display: block !important;
                background-color: white !important; 
                margin: 0 !important; 
                padding: 10px !important;
            }
            
            /* Puti dapat ang background para malinaw ang print */
            body { background: white !important; }
        }

        /* 3. Alisin ang default margins ng browser (URLs and Dates) */
        @page {
            margin: 0;
        }

    </style>
</head>
<body x-data="kitchenHandler()" x-init="init()">

    <audio id="kitchen-bell" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>

    <header class="aws-header shadow-lg">
        <div class="flex items-center gap-4">
            <button @click="isSidebarOpen = !isSidebarOpen" class="hover:bg-white/20 p-2 rounded transition cursor-pointer">
                <i class="fas fa-bars"></i>
            </button>
            <span class="font-bold tracking-tight uppercase"></span>
        </div>

        <div class="flex items-center gap-6">
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" class="flex items-center gap-3 border-l border-white/20 pl-6 h-full text-right hover:bg-white/5 p-2 rounded transition-all cursor-pointer focus:outline-none">
                    <div class="hidden md:block text-right">
                        <span class="text-[10px] text-white/60 block leading-none uppercase tracking-widest font-bold">Account</span>
                        <p class="font-bold text-white uppercase text-sm tracking-tight">{{ Auth::user()->name ?? 'Guest User' }}</p>
                    </div>
                    <div class="relative">
                        <i class="fas fa-user-circle text-2xl text-white/80 transition-transform" :class="open ? 'scale-110' : ''"></i>
                        <div class="absolute -bottom-0.5 -right-0.5 bg-emerald-500 w-2.5 h-2.5 rounded-full border-2 border-[#800000]"></div>
                    </div>
                    <i class="fa-solid fa-chevron-down text-[9px] text-white/40 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" x-cloak class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl py-2 z-[1100] border border-slate-200 overflow-hidden">
                    <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 mb-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5">Signed in as</p>
                        <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name ?? 'Guest User' }}</p>
                    </div>
                    <div class="px-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2.5 text-[11px] font-black text-red-600 hover:bg-red-50 rounded-lg uppercase tracking-widest flex items-center gap-3 transition-all">
                                <i class="fa-solid fa-power-off text-sm"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="gold-accent"></div>

    <aside class="aws-sidebar shadow-sm" :class="!isSidebarOpen ? 'sidebar-collapsed' : ''">
        <div class="p-6 space-y-8">
            <nav class="space-y-1">
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 px-2">Kitchen</p>
                <button @click="currentTab = 'orders'" 
    :class="currentTab === 'orders' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" 
    class="w-full flex items-center gap-4 p-3 rounded-sm text-left font-semibold">
    <i class="fas fa-utensils w-5"></i> Live Orders
</button>

               <button @click="currentTab = 'history'" 
    :class="currentTab === 'history' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" 
    class="w-full flex items-center gap-4 p-3 rounded-sm text-left font-semibold">
    <i class="fas fa-clipboard-list w-5"></i> Fulfilled Logs
    
</button>

            </nav>
        </div>
    </aside>

    <main class="main-content" :class="!isSidebarOpen ? 'content-wide' : ''">
        
        <div x-show="currentTab === 'orders'" x-cloak>
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Production Dashboard</h1>
                    <nav class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">
                        <span>Prep Station</span> <span class="mx-3 text-slate-300">|</span> <span class="text-red-800 font-bold">Kitchen Command</span>
                    </nav>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 w-full">
                <div class="clay-card border-t-4 border-t-emerald-500 p-5">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Active Tickets</p>
                    <p class="text-3xl font-black text-slate-800 mt-1" x-text="orders.length"></p>
                </div>
                <div class="clay-card border-t-4 border-t-blue-500 p-5">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Live Sessions</p>
                    <p class="text-3xl font-black text-slate-800 mt-1" x-text="orders.length"></p>
                </div>
                <div class="clay-card border-t-4 border-t-yellow-500 p-5">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Served</p>
                    <p class="text-3xl font-black text-slate-800 mt-1" x-text="fulfilledCount"></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <template x-for="(order, index) in orders" :key="order.id">
                    <div class="clay-card flex flex-col overflow-hidden" :class="order.minutes >= 10 ? 'border-t-4 border-t-red-600 ring-1 ring-red-100' : ''">
                        <div class="p-4 bg-slate-50 border-b flex justify-between items-center">
                            <div>
                                <span class="text-xl font-black text-slate-800" x-text="order.table"></span>
                                <p class="text-[10px] text-slate-400 font-bold uppercase" x-text="order.id"></p>
                            </div>
                            <span class="font-mono font-bold px-2 py-1 rounded" :class="order.minutes >= 10 ? 'text-red-800 bg-red-100 animate-pulse' : 'text-slate-600 bg-slate-200'" x-text="order.minutes + ':' + order.seconds"></span>
                        </div>
                        <div class="p-4 flex-1 space-y-3">
                            <template x-for="item in order.items">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" class="kitchen-checkbox" x-model="item.done">
                                    <span class="text-sm font-bold uppercase group-hover:text-red-800 transition-colors" :class="item.done ? 'line-through text-slate-400' : 'text-slate-700'" x-text="item.qty + 'x ' + item.name"></span>
                                </label>
                            </template>
                        </div>
                        <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-2">
                            <button @click="serveOrder(index)" class="flex-1 py-3 bg-red-800 text-white font-black text-xs uppercase tracking-widest rounded hover:bg-red-900 transition active:scale-[0.98]">
                                Mark Ready <i class="fas fa-check ml-1"></i>
                            </button>
                            <button @click="reprint(order)" class="px-4 py-3 bg-slate-200 text-slate-700 rounded hover:bg-slate-300">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

     <div x-show="currentTab === 'history'" x-cloak>
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Fulfilled Logs</h1>
            <nav class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">
                <span>Completed Orders</span> <span class="mx-3 text-slate-300">|</span>
                <span class="text-red-800 font-bold">Kitchen Records</span>
            </nav>
        </div>

        <div class="pr-4"> <button @click="clearHistory()" 
                    x-show="fulfilledLogs.length > 0"
                    class="flex items-center gap-2 bg-[#fff1f1] hover:bg-[#ffe4e4] text-[#d93025] px-5 py-2.5 rounded-xl border border-[#ffdfdf] transition-all active:scale-95 shadow-sm mb-0.5">
                <i class="fas fa-trash-alt text-xs"></i>
                <span class="text-[11px] font-black uppercase tracking-wider">Clear All</span>
            </button>
        </div>
    </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="log in fulfilledLogs" :key="log.id">
                    <div class="aws-card p-5 border border-slate-200">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h2 class="font-black text-slate-900 text-lg" x-text="log.table"></h2>
                                <p class="text-[10px] uppercase tracking-[0.2em] text-slate-400 font-bold" x-text="log.completedAt"></p>
                            </div>
                            <span class="text-[10px] font-black uppercase text-emerald-700 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100" x-text="log.status"></span>
                        </div>
                        <div class="space-y-2 border-t pt-3">
                            <template x-for="item in log.items" :key="item.name">
                                <p class="text-sm font-bold text-slate-700 uppercase" x-text="item.qty + 'x ' + item.name"></p>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </main>

    <div id="kitchen-receipt" class="hidden" style="width: 80mm; padding: 10px;">
    <div style="background-color: white; border: 1px solid #000; padding: 15px; font-family: 'Courier New', Courier, monospace;">
        
        <center>
            <h1 style="margin: 0; font-size: 28px; font-weight: bold; text-transform: uppercase;" x-text="printData.table || 'DINE IN'"></h1>
           <p style="margin: 5px 0; font-size: 12px;" x-text="new Date().toLocaleTimeString([], {hour: 'numeric', minute:'2-digit', hour12: true})"></p>
            <p style="margin: 0;">==========================</p>
        </center>

        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr style="font-size: 14px; border-bottom: 1px solid #000;">
                    <th style="text-align: left; width: 20%; padding-bottom: 5px;">QTY</th>
                    <th style="text-align: left; padding-bottom: 5px;">ORDER ITEM</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="item in printData.items">
                    <tr style="border-bottom: 1px dashed #ccc;">
                        <td style="padding: 15px 0; font-size: 22px; font-weight: bold; vertical-align: top;" x-text="'x' + item.qty"></td>
                        <td style="padding: 15px 0; font-size: 22px; font-weight: bold; text-transform: uppercase; line-height: 1.2;" x-text="item.name"></td>
                    </tr>
                </template>
            </tbody>
        </table>

        <p style="margin: 10px 0 0 0; text-align: center;">==========================</p>
    </div>
</div>


   
  <script>
function kitchenHandler() {
    return {
        isSidebarOpen: window.innerWidth >= 768,
        currentTab: 'orders',
        orders: [],
        fulfilledLogs: [],
        fulfilledCount: 0,
        printData: { items: [] },
        processedIds: new Set(), // Tagabantay para sa double tickets

        init() {
            // Load history from localstorage para hindi mawala pag-refresh
            const savedHistory = localStorage.getItem('ub_kitchen_history');
            if (savedHistory) {
                this.fulfilledLogs = JSON.parse(savedHistory);
                this.fulfilledCount = this.fulfilledLogs.length;
            }

            // Sound Activator
            const unlock = () => {
                const bell = document.getElementById('kitchen-bell');
                bell.muted = true;
                bell.play().then(() => { bell.pause(); bell.currentTime = 0; bell.muted = false; });
                window.removeEventListener('click', unlock);
            };
            window.addEventListener('click', unlock);

            // Timer
            setInterval(() => {
                this.orders.forEach(o => {
                    let s = parseInt(o.seconds || 0) + 1;
                    let m = parseInt(o.minutes || 0);
                    if(s >= 60) { m++; s = 0; }
                    o.seconds = s < 10 ? '0' + s : s.toString();
                    o.minutes = m;
                });
            }, 1000);

            // LISTEN para sa bagong order
            window.addEventListener('storage', (e) => {
                if (e.key === 'ub_chef_new_order' && e.newValue !== null) {
                    try {
                        const orderData = JSON.parse(e.newValue);
                        this.processIncomingOrder(orderData);
                    } catch (err) {
                        console.error("Error parsing order:", err);
                    }
                }
            });

            // Check kung may naiwan na order pagka-load ng page
            const pending = localStorage.getItem('ub_chef_new_order');
            if (pending) {
                this.processIncomingOrder(JSON.parse(pending));
            }
        },

        async processIncomingOrder(order) {
            // CRITICAL FIX: Huwag tanggapin kung ang ID ay naproseso na
            if (this.processedIds.has(order.id) || this.orders.some(o => o.id === order.id)) {
                localStorage.removeItem('ub_chef_new_order'); // Burahin para hindi mag-loop
                return; 
            }

            // Mark as processed agad
            this.processedIds.add(order.id);
            
            // Burahin ang order sa storage para sa ibang tabs
            localStorage.removeItem('ub_chef_new_order');

            // Play Sound
            const bell = document.getElementById('kitchen-bell');
            if(bell) {
                bell.currentTime = 0;
                try { await bell.play(); } catch(e) {}
            }

            // I-push sa live dashboard
            this.orders.push({ ...order, minutes: 0, seconds: '00' });

            // FIX: Repreint with delay para hindi mag-clash ang Alpine updates at browser print
            setTimeout(() => { 
                this.reprint(order); 
            }, 500);
        },

        reprint(order) {
            this.printData = { 
                table: order.table, 
                items: order.items 
            };
            
            // Mas maikling delay para sa actual print dialog
            this.$nextTick(() => { 
                window.print(); 
            });
        },

        serveOrder(index) {
            const order = this.orders[index];
            
            // I-save sa local history array
            this.fulfilledLogs.unshift({
                id: order.id,
                table: order.table,
                items: order.items,
                status: 'COMPLETED',
           completedAt: new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit', hour12: true })
            });

            // FIX: I-save permanentemente sa LocalStorage para sa History Tab
            localStorage.setItem('ub_kitchen_history', JSON.stringify(this.fulfilledLogs));

            this.orders.splice(index, 1);
            this.fulfilledCount = this.fulfilledLogs.length;
        },
    

    clearHistory() {
    // Tinanggal na ang alert/confirm message
    this.fulfilledLogs = [];
    this.fulfilledCount = 0;
    localStorage.removeItem('ub_kitchen_history');
    this.processedIds.clear();
}
}
}

</script>

</body>
</html>