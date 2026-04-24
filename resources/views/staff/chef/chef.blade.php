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
                    <button @click="currentTab = 'alerts'" :class="currentTab === 'alerts' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-bell w-5"></i> Kitchen Alerts <span x-show="alerts.length > 0" class="ml-auto bg-red-600 text-white text-xs px-2 py-0.5 rounded-full font-bold" x-text="alerts.length"></span>
                    </button>
                    <button @click="currentTab = 'inventory'" :class="currentTab === 'inventory' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-boxes w-5"></i> Inventory Stock
                   
                    <button @click="currentTab = 'history'" :class="currentTab === 'history' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-clipboard-list w-5"></i> Fulfilled Logs
                    </button>
                      
                      </button>
                    <button @click="currentTab = 'performance'" :class="currentTab === 'performance' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-chart-line w-5"></i> Performance
                    </button>
                     
                </nav>
            </div>
        </div>
    </aside>

    <main class="main-content" :class="!isSidebarOpen ? 'content-wide' : ''">
        
        <!-- ORDERS VIEW -->
        <div x-show="currentTab === 'orders'" x-cloak>
            @include('staff.chef.orders')
        </div>

        <!-- INCOMING ORDERS VIEW -->
        <div x-show="currentTab === 'incoming'" x-cloak>
            @include('staff.chef.incoming_orders')
        </div>

        <!-- ALERTS VIEW -->
        <div x-show="currentTab === 'alerts'" x-cloak>
            @include('staff.chef.alerts')
        </div>

        <!-- INVENTORY VIEW -->
        <div x-show="currentTab === 'inventory'" x-cloak>
            @include('staff.chef.inventory')
        </div>

        <!-- PERFORMANCE VIEW -->
        <div x-show="currentTab === 'performance'" x-cloak>
            @include('staff.chef.performance')
        </div>

        <!-- HISTORY VIEW -->
        <div x-show="currentTab === 'history'" x-cloak>
            @include('staff.chef.history')
        </div>

    </main>

    <script>
        function kitchenHandler() {
            return {
                currentTab: 'orders',
                isSidebarOpen: true,
                fulfilledCount: 86,
                avgPrepSpeed: 10.5,
                chefPerformance: {
                    satisfaction: 4.7,
                    score: 92,
                    responseTime: 87,
                    hospitality: 93,
                    knowledge: 88
                },
                orders: [
                    { id: '1001', table: 'TABLE 04', minutes: 4, seconds: '20', items: [{ qty: 2, name: 'UB Burger', done: false }, { qty: 1, name: 'Fries', done: false }] },
                    { id: '1002', table: 'TABLE 09', minutes: 11, seconds: '05', items: [{ qty: 1, name: 'Spicy Pasta', done: false }] }
                ],
                incomingOrders: [
                    { id: 'I101', status: 'NEW', items: [{ qty: 1, name: 'Chicken Wings' }, { qty: 2, name: 'Iced Tea' }], note: 'No onions' },
                    { id: 'I102', status: 'NEW', items: [{ qty: 1, name: 'Cheese Pizza' }], note: 'Extra cheese' }
                ],
                alerts: [
                    { id: 'A01', table: '05', title: 'Special Request', message: 'Customer wants extra spicy sauce', priority: 'HIGH' },
                    { id: 'A02', table: '02', title: 'Allergy Alert', message: 'No nuts for this order', priority: 'NORMAL' }
                ],
                inventory: [
                    { id: 1, name: 'Beef Patty', stock: 18 },
                    { id: 2, name: 'Potato Fries', stock: 8 },
                    { id: 3, name: 'Tomato Sauce', stock: 24 },
                    { id: 4, name: 'Cheddar Cheese', stock: 12 },
                    { id: 5, name: 'Lettuce', stock: 10 }
                ],
                fulfilledLogs: [
                    { id: 'F100', table: 'TABLE 01', completedAt: '3:05 PM', status: 'Done', items: [{ qty: 2, name: 'Burger Steak' }, { qty: 1, name: 'Ice Tea' }] },
                    { id: 'F101', table: 'TABLE 07', completedAt: '3:18 PM', status: 'Done', items: [{ qty: 3, name: 'Carbonara' }] }
                ],
                recentActivity: [
                    { id: 1, action: 'Marked Table 04 order ready', time: '3:22 PM', status: 'Done' },
                    { id: 2, action: 'Accepted new incoming order I101', time: '3:18 PM', status: 'Done' },
                    { id: 3, action: 'Resolved allergy alert for Table 02', time: '3:10 PM', status: 'Done' }
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
                    const order = this.orders.splice(index, 1)[0];
                    this.fulfilledLogs.unshift({ id: 'F' + Date.now(), table: order.table, completedAt: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }), status: 'Done', items: order.items.map(i => ({ qty: i.qty, name: i.name })) });
                    this.fulfilledCount++;
                    this.recentActivity.unshift({ id: Date.now(), action: 'Marked ' + order.table + ' ready', time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }), status: 'Done' });
                },
                acceptIncomingOrder(index) {
                    const order = this.incomingOrders.splice(index, 1)[0];
                    this.orders.push({ id: order.id, table: 'TABLE ' + (Math.floor(Math.random() * 20) + 1).toString().padStart(2, '0'), minutes: 0, seconds: '00', items: order.items.map(i => ({ ...i, done: false })) });
                    this.recentActivity.unshift({ id: Date.now(), action: 'Accepted incoming order ' + order.id, time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }), status: 'Done' });
                },
                resolveAlert(index) {
                    this.alerts.splice(index, 1);
                },
                decrementStock(index) {
                    if (this.inventory[index].stock > 0) {
                        this.inventory[index].stock -= 1;
                    }
                },
                reorderStock(index) {
                    this.inventory[index].stock += 20;
                },
                simulateIncomingOrder() {
                    const sampleItems = [
                        [{ qty: 1, name: 'Spicy Pasta' }],
                        [{ qty: 2, name: 'Burger Steak' }, { qty: 1, name: 'Iced Tea' }],
                        [{ qty: 1, name: 'Mozzarella Sticks' }, { qty: 1, name: 'Lemonade' }]
                    ];
                    const randomItems = sampleItems[Math.floor(Math.random() * sampleItems.length)];
                    this.incomingOrders.push({
                        id: 'I' + Date.now(),
                        status: 'NEW',
                        items: randomItems,
                        note: 'Chef note: prepare quickly'
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