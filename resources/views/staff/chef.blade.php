<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Command | UB SYNC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;600;700;900&display=swap');
        body { font-family: 'Public Sans', sans-serif; background-color: #f2f3f3; color: #334155; overflow-x: hidden; }
        
        /* UB Branding Header */
        .aws-header { background-color: #800000; height: 65px; display: flex; align-items: center; justify-content: space-between; padding: 0 25px; color: white; position: fixed; top: 0; width: 100%; z-index: 1000; }
        .gold-accent { background-color: #D4AF37; height: 4px; position: fixed; top: 65px; width: 100%; z-index: 999; }
        
        /* Sidebar Navigation */
        .aws-sidebar { width: 260px; background: white; border-right: 1px solid #eaeded; height: calc(100vh - 69px); position: fixed; top: 69px; left: 0; transition: all 0.3s ease; z-index: 998; }
        .sidebar-collapsed { left: -260px; }
        
        /* Main Content Area */
        .main-content { margin-left: 260px; margin-top: 69px; padding: 25px; transition: all 0.3s ease; min-height: calc(100vh - 69px); }
        .content-wide { margin-left: 0; width: 100%; }

        .aws-card { background: white; border: 1px solid #eaeded; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        
        [x-cloak] { display: none !important; }
        
        /* Custom Checkbox Style for Kitchen */
        .kitchen-checkbox {
            appearance: none;
            width: 1.5rem;
            height: 1.5rem;
            border: 2px solid #cbd5e1;
            border-radius: 4px;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }
        .kitchen-checkbox:checked {
            background-color: #991b1b;
            border-color: #991b1b;
        }
        .kitchen-checkbox:checked::after {
            content: '✓';
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1rem;
            font-weight: bold;
        }
    </style>
</head>
<body x-data="kitchenHandler()">

    <header class="aws-header shadow-lg">
        <div class="flex items-center gap-4">
            <button @click="isSidebarOpen = !isSidebarOpen" class="hover:bg-white/20 p-2 rounded transition cursor-pointer">
                <i class="fas fa-bars"></i>
            </button>
            <div class="flex items-center gap-2">
                <i class="fas fa-fire-burner text-yellow-500 text-xl"></i>
                <span class="font-bold tracking-tighter text-lg uppercase">UB SYNC KITCHEN</span>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" class="flex items-center gap-3 border-l border-white/20 pl-6 h-full text-right hover:bg-white/5 p-2 rounded transition-all cursor-pointer focus:outline-none">
                    <div class="hidden md:block text-right">
                        <span class="text-[10px] text-white/60 block leading-none uppercase tracking-widest font-bold">Account</span>
                        <p class="font-bold text-white uppercase text-sm tracking-tight">
                            {{ Auth::user()->name ?? 'Guest User' }}
                        </p>
                    </div>
                    
                    <div class="relative">
                        <i class="fas fa-user-circle text-2xl text-white/80 transition-transform" :class="open ? 'scale-110' : ''"></i>
                        <div class="absolute -bottom-0.5 -right-0.5 bg-emerald-500 w-2.5 h-2.5 rounded-full border-2 border-[#800000]"></div>
                    </div>

                    <i class="fa-solid fa-chevron-down text-[9px] text-white/40 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                     x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.2)] py-2 z-[1100] border border-slate-200 overflow-hidden">
                    
                    <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 mb-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5">Signed in as</p>
                        <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name ?? 'Guest User' }}</p>
                    </div>

                    <div class="px-2">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="button" @click="confirmLogout()" class="w-full text-left px-3 py-2.5 text-[11px] font-black text-red-600 hover:bg-red-50 rounded-lg uppercase tracking-widest flex items-center gap-3 transition-all group">
                                <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors">
                                    <i class="fa-solid fa-power-off text-sm"></i> 
                                </div>
                                Sign Out
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
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 px-2">Management</p>
                <nav class="space-y-1">
                    <button @click="currentTab = 'orders'" :class="currentTab === 'orders' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-utensils w-5"></i> Live Orders
                    </button>
                    <button @click="currentTab = 'inventory'" :class="currentTab === 'inventory' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-boxes w-5"></i> Inventory Stock
                    </button>
                    <button @click="currentTab = 'history'" :class="currentTab === 'history' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-clipboard-list w-5"></i> Fulfilled Logs
                    </button>
                </nav>
            </div>
        </div>
    </aside>

    <main class="main-content" :class="!isSidebarOpen ? 'content-wide' : ''">
        
        <div x-show="currentTab === 'orders'" x-cloak>
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Production Dashboard</h1>
                    <nav class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">
                        <span>Prep Station</span> <span class="mx-3 text-slate-300">|</span>
                        <span class="text-red-800 font-bold">Kitchen Command</span>
                    </nav>
                </div>
                <button @click="simulateNewOrder()" class="bg-white border border-slate-300 px-6 py-2.5 text-sm font-bold hover:bg-slate-50 transition shadow-sm rounded flex items-center gap-2">
                    <i class="fas fa-plus text-slate-400"></i> New Simulation
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 w-full">
                <div class="aws-card border-t-4 border-t-red-800 p-5">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Active Tickets</p>
                    <p class="text-3xl font-black text-slate-800 mt-1" x-text="orders.length"></p>
                </div>
                <div class="aws-card p-5 border-t-4 border-t-blue-500">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Avg Prep Speed</p>
                    <p class="text-3xl font-black text-slate-800 mt-1">10.5 <span class="text-sm text-slate-500">mins</span></p>
                </div>
                <div class="aws-card p-5 border-t-4 border-t-emerald-500">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Served</p>
                    <p class="text-3xl font-black text-slate-800 mt-1" x-text="fulfilledCount"></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <template x-for="(order, index) in orders" :key="order.id">
                    <div class="aws-card flex flex-col overflow-hidden transition-all duration-300" :class="order.minutes >= 10 ? 'border-t-4 border-t-red-600 shadow-md ring-1 ring-red-100' : ''">
                        <div class="p-4 bg-slate-50 border-b flex justify-between items-center">
                            <span class="text-xl font-black text-slate-800" x-text="order.table"></span>
                            <span class="font-mono font-bold px-2 py-1 rounded" 
                                  :class="order.minutes >= 10 ? 'text-red-800 bg-red-100 animate-pulse' : 'text-slate-600 bg-slate-200'" 
                                  x-text="(order.minutes < 10 ? '0' + order.minutes : order.minutes) + ':' + order.seconds">
                            </span>
                        </div>
                        <div class="p-4 flex-1 space-y-3">
                            <template x-for="item in order.items">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" class="kitchen-checkbox">
                                    <span class="text-sm font-bold text-slate-700 uppercase group-hover:text-red-800 transition-colors" x-text="item.qty + 'x ' + item.name"></span>
                                </label>
                            </template>
                        </div>
                        <div class="p-4 bg-slate-50 border-t border-slate-100">
                            <button @click="serveOrder(index)" class="w-full py-3 bg-red-800 text-white font-black text-xs uppercase tracking-widest rounded hover:bg-red-900 transition active:scale-[0.98]">
                                Order Ready <i class="fas fa-check ml-1"></i>
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="orders.length === 0" class="col-span-full p-12 text-center border-2 border-dashed border-slate-300 rounded-lg bg-slate-50">
                    <i class="fas fa-clipboard-check text-4xl text-slate-300 mb-3"></i>
                    <h3 class="text-lg font-bold text-slate-600 uppercase">No Active Orders</h3>
                    <p class="text-slate-400 text-sm">Kitchen is clear. Waiting for new tickets...</p>
                </div>
            </div>
        </div>

        <div x-show="currentTab === 'inventory'" x-cloak>
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter mb-8">Inventory Stock</h1>
            <div class="aws-card p-6">
                <p class="text-slate-500">Inventory management interface will be loaded here.</p>
            </div>
        </div>

        <div x-show="currentTab === 'history'" x-cloak>
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter mb-8">Fulfilled Logs</h1>
            <div class="aws-card p-6">
                <p class="text-slate-500">History and fulfilled orders list will be displayed here.</p>
            </div>
        </div>

    </main>

    <script>
        function kitchenHandler() {
            return {
                currentTab: 'orders',
                isSidebarOpen: true,
                fulfilledCount: 86,
                orders: [
                    { id: '1001', table: 'TABLE 04', minutes: 4, seconds: '20', items: [{ qty: 2, name: 'UB Burger' }, { qty: 1, name: 'Fries' }] },
                    { id: '1002', table: 'TABLE 09', minutes: 11, seconds: '05', items: [{ qty: 1, name: 'Spicy Pasta' }] }
                ],
                init() {
                    // Start the timer when the component initializes
                    setInterval(() => {
                        this.orders.forEach(o => {
                            let s = parseInt(o.seconds) + 1;
                            if(s >= 60) { 
                                o.minutes++; 
                                s = 0; 
                            }
                            o.seconds = s < 10 ? '0' + s : s.toString();
                        });
                    }, 1000);
                },
                serveOrder(index) {
                    this.orders.splice(index, 1);
                    this.fulfilledCount++;
                },
                simulateNewOrder() {
                    const sampleItems = ['UB Burger', 'Spicy Pasta', 'Fries', 'Iced Tea', 'Chicken Wings'];
                    const randomItem = sampleItems[Math.floor(Math.random() * sampleItems.length)];
                    
                    this.orders.push({ 
                        id: Date.now(), 
                        table: 'TABLE ' + (Math.floor(Math.random() * 20) + 1).toString().padStart(2, '0'), 
                        minutes: 0, 
                        seconds: '00', 
                        items: [{ qty: Math.floor(Math.random() * 3) + 1, name: randomItem }] 
                    });
                },
                confirmLogout() {
                    
                        document.getElementById('logout-form').submit();
                    }
                }
            }
        
    </script>
</body>
</html>