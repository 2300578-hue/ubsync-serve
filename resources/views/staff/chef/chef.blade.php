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
        
        /* Consistent UB Branding Header */
        .aws-header { background-color: #800000; height: 65px; display: flex; align-items: center; justify-content: space-between; padding: 0 25px; color: white; position: fixed; top: 0; width: 100%; z-index: 1000; }
        .gold-accent { background-color: #D4AF37; height: 4px; position: fixed; top: 65px; width: 100%; z-index: 999; }
        
        /* Sidebar Navigation */
        .aws-sidebar { width: 260px; background: white; border-right: 1px solid #eaeded; height: calc(100vh - 69px); position: fixed; top: 69px; left: 0; transition: all 0.3s ease; z-index: 1000; }
        .sidebar-collapsed { left: -260px; }
        
        /* Main Content Area */
        .main-content { margin-left: 260px; margin-top: 69px; padding: 25px; transition: all 0.3s ease; min-height: calc(100vh - 69px); }
        .content-wide { margin-left: 0; width: 100%; }

        /* --- MOBILE RESPONSIVENESS --- */
        @media (max-width: 768px) {
            .main-content { margin-left: 0 !important; padding: 15px; width: 100%; }
            .aws-sidebar { box-shadow: 10px 0 15px rgba(0,0,0,0.1); z-index: 1001; }
        }

        .aws-card { background: white; border: 1px solid #eaeded; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }

        .maroon-gradient { background: linear-gradient(135deg, #800000 0%, #a52a2a 100%); }
        .status-badge { font-size: 0.65rem; padding: 2px 8px; border-radius: 20px; font-weight: 800; text-transform: uppercase; }
        [x-cloak] { display: none !important; }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #800000; border-radius: 10px; }
  
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
<body x-data="kitchenHandler()" x-init="init()">

    <header class="aws-header shadow-lg">
        <div class="flex items-center gap-4">
            <button @click="isSidebarOpen = !isSidebarOpen" class="hover:bg-white/20 p-2 rounded transition cursor-pointer">
                <i class="fas fa-bars"></i>
            </button>
            <div class="flex items-center gap-2">
               
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

             <div x-show="open" x-cloak class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl py-2 z-[1100] border border-slate-200 overflow-hidden">
                    <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 mb-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5">Signed in as</p>
                        <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name ?? 'Guest User' }}</p>
                    </div>
                    <div class="px-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2.5 text-[11px] font-black text-red-600 hover:bg-red-50 rounded-lg uppercase tracking-widest flex items-center gap-3 transition-all group">
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
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 px-2">Kitchen</p>
                <nav class="space-y-1">
                    <button @click="currentTab = 'orders'" :class="currentTab === 'orders' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-utensils w-5"></i> Live Orders
                    </button>
                    <button @click="currentTab = 'incoming'" :class="currentTab === 'incoming' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-box-open w-5"></i> Incoming Orders <span x-show="incomingOrders.length > 0" class="ml-auto bg-red-600 text-white text-xs px-2 py-0.5 rounded-full font-bold" x-text="incomingOrders.length"></span>
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
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 w-full">
               <div class="aws-card border-t-4 border-t-emerald-500 p-5">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Active Tickets</p>
                    <p class="text-3xl font-black text-slate-800 mt-1" x-text="orders.length"></p>
                </div>
               <div class="bg-white rounded shadow p-6 border-t-4 border-blue-500">
    <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">
        Live Sessions
    </div>
    <div class="text-3xl font-bold text-gray-800" x-text="orders.length">
        0
    </div>
</div>

                 <div class="aws-card p-5 border-t-4 border-t-yellow-500">
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
                            <template x-for="item in order.items" :key="item.name">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" class="kitchen-checkbox" x-model="item.done">
                                    <span class="text-sm font-bold uppercase group-hover:text-red-800 transition-colors" :class="item.done ? 'line-through text-slate-400' : 'text-slate-700'" x-text="item.qty + 'x ' + item.name"></span>
                                </label>
                            </template>
                        </div>
                        <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-3">
                            <button @click="serveOrder(index)" class="flex-1 py-3 bg-red-800 text-white font-black text-xs uppercase tracking-widest rounded hover:bg-red-900 transition active:scale-[0.98]">
                                Order Ready <i class="fas fa-check ml-1"></i>
                            </button>
                            <button @click="orders.splice(index, 1)" class="flex-1 py-3 bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-widest rounded hover:bg-slate-300 transition active:scale-[0.98]">
                                Cancel
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

        <div x-show="currentTab === 'incoming'" x-cloak>
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Incoming Orders</h1>
                    <nav class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">
                        <span>Kitchen Queue</span> <span class="mx-3 text-slate-300">|</span>
                        <span class="text-red-800 font-bold">Accept to Prep</span>
                    </nav>
                </div>
                <button @click="simulateIncomingOrder()" class="bg-white border border-slate-300 px-6 py-2.5 text-sm font-bold hover:bg-slate-50 transition shadow-sm rounded flex items-center gap-2">
                    <i class="fas fa-sync-alt text-slate-400"></i> New Incoming
                </button>
            </div>

           <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
               <template x-for="(order, index) in incomingOrders" :key="'incoming-'+index">
                    <div class="aws-card p-5 border border-slate-200 bg-white rounded-lg shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Order #</p>
                                    <h2 class="font-black text-xl text-slate-900" x-text="order.id"></h2>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-[0.2em] text-amber-700 bg-amber-100 px-3 py-1 rounded-full" x-text="order.status"></span>
                            </div>
                            
                            <p class="font-bold text-slate-800 mb-3 border-b pb-2" x-text="order.table"></p>

                            <div class="space-y-3 mb-6">
                                <template x-for="(item, itemIndex) in order.items" :key="itemIndex">
                                    <div class="flex justify-between items-center text-sm text-slate-700">
                                        <span x-text="item.qty + 'x ' + item.name" class="font-medium"></span>
                                    </div>
                                </template>
                                <p class="text-xs text-slate-400 italic mt-2" x-show="order.note" x-text="order.note"></p>
                            </div>
                        </div>

                        <button @click="acceptIncomingOrder(index)" class="mt-4 w-full py-3 bg-red-800 text-white font-black text-xs uppercase tracking-widest rounded hover:bg-red-900 transition">
                            Accept to Prep
                        </button>
                    </div>
                </template>

                <div x-show="incomingOrders.length === 0" class="col-span-full p-12 text-center border-2 border-dashed border-slate-300 rounded-lg bg-slate-50">
                    <i class="fas fa-inbox text-4xl text-slate-300 mb-3"></i>
                    <h3 class="text-lg font-bold text-slate-600 uppercase">No Incoming Orders</h3>
                    <p class="text-slate-400 text-sm">New orders will appear here as they arrive.</p>
                </div>
            </div>
        </div>

        <div x-show="currentTab === 'history'" x-cloak>
            @include('staff.chef.history')
        </div>

    </main>

    <script>
    function kitchenHandler() {
        return {
            isSidebarOpen: window.innerWidth >= 768,
            currentTab: 'incoming', // Default tab
            incomingOrders: [],     // Stack ng bagong orders
            orders: [],             // Live/Active Orders (Prep)
            fulfilledLogs: [],      // History ng natapos
            fulfilledCount: 0,

            init() {
                // 1. Timer para sa Live Orders (Per segundo)
                setInterval(() => {
                    this.orders.forEach(o => {
                        let s = parseInt(o.seconds || 0) + 1;
                        let m = parseInt(o.minutes || 0);
                        if(s >= 60) { m++; s = 0; }
                        o.seconds = s < 10 ? '0' + s : s.toString();
                        o.minutes = m;
                    });
                }, 1000);

                // 2. Makinig sa Cashier
                window.addEventListener('storage', (event) => {
                    if (event.key === 'ub_chef_new_order' && event.newValue) {
                        const newOrder = JSON.parse(event.newValue);
                        this.incomingOrders.push(newOrder);
                        localStorage.removeItem('ub_chef_new_order');
                    }
                });

                // Check kung may pending order
                const pending = localStorage.getItem('ub_chef_new_order');
                if (pending) {
                    this.incomingOrders.push(JSON.parse(pending));
                    localStorage.removeItem('ub_chef_new_order');
                }
            },

            // Pindutan ng "ACCEPT TO PREP"
            acceptIncomingOrder(index) {
                const order = this.incomingOrders.splice(index, 1)[0];
                
                // Idagdag sa Live Orders na may timer fields
                this.orders.push({
                    ...order,
                    minutes: 0,
                    seconds: '00'
                });
                
                this.currentTab = 'orders'; // Auto-switch sa Live Orders tab
            },

            // Pindutan ng "MARK READY"
            serveOrder(index) {
                const order = this.orders.splice(index, 1)[0];
                
                this.fulfilledLogs.unshift({
                    id: order.id,
                    table: order.table,
                    prepTime: `${order.minutes}m ${order.seconds}s`,
                    completedAt: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                    items: order.items
                });
                
                this.fulfilledCount++;
            },

            // Tool para sa pag-test
            simulateIncomingOrder() {
                const uniqueID = 'SIM-' + Math.random().toString(36).substring(2, 6).toUpperCase();
                this.incomingOrders.push({
                    id: uniqueID,
                    table: 'TABLE 0' + (Math.floor(Math.random() * 9) + 1),
                    items: [{ name: 'Burger Steak', qty: 1 }, { name: 'Iced Tea', qty: 1 }],
                    timestamp: new Date().getTime()
                });
            }
        }
    }
</script>
    

</body>
</html>