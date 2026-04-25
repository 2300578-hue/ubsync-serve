<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiter Service | UB SYNC</title>
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
  
        
        /* Table Status Badge Styles */
        .table-badge {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        
        .table-available { background-color: #ecfdf5; color: #047857; border-color: #10b981; }
        .table-occupied { background-color: #fef2f2; color: #991b1b; border-color: #dc2626; }
        .table-pending { background-color: #fffbeb; color: #92400e; border-color: #fbbf24; }
        
        .table-badge:hover { transform: scale(1.05); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    </style>


   
</head>
<body x-data="waiterHandler()" x-init="initializeOrderListener()">

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
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 px-2">Management</p>
                <nav class="space-y-1">
                    <button @click="currentTab = 'tables'" :class="currentTab === 'tables' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-chair w-5"></i> Table Management
                    </button>
                    <button @click="currentTab = 'orders'" :class="currentTab === 'orders' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-list w-5"></i> Orders
                        <span x-show="orders.length > 0" class="ml-auto bg-red-600 text-white text-xs px-2 py-0.5 rounded-full font-bold shadow-sm" x-text="orders.length"></span>
                    </button>
                    <button @click="currentTab = 'cleanup'" :class="currentTab === 'cleanup' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-broom w-5"></i> Table Cleaning
                    </button>
                    <button @click="currentTab = 'incoming'" :class="currentTab === 'incoming' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-box-open w-5"></i> Incoming Orders <span x-show="incomingOrders.length > 0" class="ml-auto bg-red-600 text-white text-xs px-2 py-0.5 rounded-full font-bold" x-text="incomingOrders.length"></span>
                    </button>
                    <button @click="currentTab = 'requests'" :class="currentTab === 'requests' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-bell w-5"></i> Customer Requests <span x-show="requests.length > 0" class="ml-auto bg-red-600 text-white text-xs px-2 py-0.5 rounded-full font-bold" x-text="requests.length"></span>
                    </button>
                    <button @click="currentTab = 'billing'" :class="currentTab === 'billing' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-receipt w-5"></i> Billing
                    </button>
                    <button @click="currentTab = 'performance'" :class="currentTab === 'performance' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-chart-line w-5"></i> Performance
                    </button>
                </nav>
            </div>
        </div>
    </aside>

    <main class="main-content" :class="!isSidebarOpen ? 'content-wide' : ''">
        
        <div x-show="currentTab === 'tables'" x-cloak>
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Table Management</h1>
                    <nav class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">
                        <span>Dining Area</span> <span class="mx-3 text-slate-300">|</span>
                        <span class="text-red-800 font-bold">Service Station</span>
                    </nav>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 w-full">
                <div class="aws-card border-t-4 border-t-emerald-500 p-5">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Occupied Tables</p>
                    <p class="text-3xl font-black text-slate-800 mt-1" x-text="tables.filter(t => t.status === 'occupied').length"></p>
                </div>
                <div class="aws-card p-5 border-t-4 border-t-blue-500">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Available Tables</p>
                    <p class="text-3xl font-black text-slate-800 mt-1" x-text="tables.filter(t => t.status === 'available').length"></p>
                </div>
                <div class="aws-card p-5 border-t-4 border-t-yellow-500">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pending Orders</p>
                    <p class="text-3xl font-black text-slate-800 mt-1" x-text="tables.filter(t => t.status === 'pending').length"></p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                <template x-for="(table, index) in tables" :key="table.id">
                    <div class="table-badge" :class="'table-' + table.status" @click="selectTable(index)">
                        <div class="text-lg font-black" x-text="table.number"></div>
                        <div class="text-[10px]" x-text="table.status.toUpperCase()"></div>
                    </div>
                </template>
            </div>

            <div x-show="selectedTable !== null" x-cloak class="mt-8 aws-card p-6">
                <h2 class="text-2xl font-black text-slate-800 mb-4">Table <span x-text="tables[selectedTable]?.number"></span> Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase ml-1 mb-2 tracking-widest">Number of Guests</label>
                        <input type="number" x-model="tables[selectedTable].guests" min="1" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-red-800">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase ml-1 mb-2 tracking-widest">Status</label>
                        <select x-model="tables[selectedTable].status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white outline-none focus:ring-2 focus:ring-red-800">
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
                <button @click="updateTable()" class="w-full mt-4 bg-red-800 hover:bg-red-900 text-white font-bold py-3 rounded-xl uppercase tracking-widest">Update Table</button>
            </div>
        </div>
            
        <div x-show="currentTab === 'cleanup'" x-cloak>
            @include('staff.waiter.table_cleanup')
        </div>

        <div x-show="currentTab === 'orders'" x-cloak>
            @include('staff.waiter.orders')
        </div>

        <div x-show="currentTab === 'incoming'" x-cloak>
            @include('staff.waiter.incoming_orders')
        </div>

        <div x-show="currentTab === 'requests'" x-cloak>
            @include('staff.waiter.customer_request')
        </div>

        <div x-show="currentTab === 'billing'" x-cloak>
            @include('staff.waiter.billing')
        </div>

        <div x-show="currentTab === 'performance'" x-cloak>
            @include('staff.waiter.performance')
        </div>

    </main>

     
    <script>
        function waiterHandler() {
            return {
                currentTab: 'tables',
                isSidebarOpen: true,
                selectedTable: null,
                billedCount: 0,
                totalRevenue: 0,
                    
                  

                get averageBill() {
                    if (this.billedCount === 0) return 0;
                    return (this.totalRevenue / this.billedCount).toFixed(2);
                },

                tables: [
                    { id: 1, number: '01', status: 'available', guests: 0 },
                    { id: 2, number: '02', status: 'occupied', guests: 4 },
                    { id: 3, number: '03', status: 'available', guests: 0 },
                    { id: 4, number: '04', status: 'occupied', guests: 2 },
                    { id: 5, number: '05', status: 'pending', guests: 3 },
                    { id: 6, number: '06', status: 'available', guests: 0 },
                ],
                orders: [
                   
                ],
                requests: [
                    { id: 1, table: '02', type: 'Water Refill', message: 'Customer needs water refill', priority: 'HIGH' },
                ],
                recentBills: [],
                
                // Incoming Orders Data
                incomingOrders: [
                    { 
                        id: 'ORD-101', 
                        table: '03', 
                        customer: 'Juan Dela Cruz', 
                        total: 450.00, 
                        cooking: false, // PENDING STATUS
                        ready: false, 
                        items: [{ qty: 2, name: 'UB Burger' }, { qty: 1, name: 'Iced Tea' }] 
                    },
                    { 
                        id: 'ORD-102', 
                        table: '05', 
                        customer: 'Maria Clara', 
                        total: 320.00, 
                        cooking: true,  // COOKING STATUS
                        ready: false, 
                        items: [{ qty: 1, name: 'Spicy Pasta' }, { qty: 1, name: 'Fries' }] 
                    },
                    { 
                        id: 'ORD-103', 
                        table: '08', 
                        customer: 'Pedro Penduko', 
                        total: 890.00, 
                        cooking: true, 
                        ready: true,    // READY STATUS
                        items: [{ qty: 2, name: 'Chicken Wings' }, { qty: 2, name: 'Caesar Salad' }] 
                    }
                ],

                // Mark Served Function - Inaalis na sa array/screen kapag tapos na
               // Mark Served Function - Ililipat ang order sa Orders Tab
                markTableServed(orderId) {
                    const index = this.incomingOrders.findIndex(o => o.id === orderId);
                    if(index !== -1) {
                        // 1. Kunin ang order data bago ito tanggalin
                        const orderToTransfer = this.incomingOrders[index];

                        // 2. Ipasok ang order sa "orders" array para lumabas sa Orders tab
                        // Gagamit tayo ng unshift para mapunta sa pinakauna ang bagong order
                              this.orders.unshift({
    id: orderToTransfer.id,
    table: orderToTransfer.table,
    status: 'pending',
    items: orderToTransfer.items,
    total: orderToTransfer.total // <-- Siguraduhing naidagdag itong linya para mapasa ang presyo
});

                        // 3. Idagdag sa performance metrics
                        this.waiterPerformance.tablesAttended++;

                        this.logActivity(`Table ${orderToTransfer.table} - Order Served`, 'Done');
                        
                        // 4. Tanggalin ang order sa Incoming Orders screen
                        this.incomingOrders.splice(index, 1);
                        
                        // 5. I-update ang local storage
                        localStorage.setItem('ub_orders', JSON.stringify(this.incomingOrders));
                        
                        // (Optional) Pwede mag-alert para alam ng user na lumipat ang order
                        // alert('Order successfully transferred to Orders Management!');
                    }
                },

                logActivity(actionText, statusText) {
                const timeNow = new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit', hour12: true });
                this.recentActivities.unshift({
                    id: Date.now(),
                    action: actionText,
                    time: timeNow,
                    status: statusText
                });
                
                // Panatilihin lang ang top 10 na pinakabagong activity para hindi humaba nang sobra
                if (this.recentActivities.length > 10) {
                    this.recentActivities.pop();
                }
            }, // <-- WAG KAKALIMUTAN ITONG COMMA (,) SA DULO

                // Automatic na gumagalaw ang kitchen status
                simulateKitchenProgress() {
                    setInterval(() => {
                        this.incomingOrders.forEach(order => {
                            if (!order.cooking && !order.ready) {
                                // PENDING -> COOKING
                                order.cooking = true; 
                            } else if (order.cooking && !order.ready) {
                                // COOKING -> READY (Randomized for realism)
                                if(Math.random() > 0.5) order.ready = true; 
                            }
                        });
                    }, 8000); // Mag-uupdate ang kusina kada 8 segundo
                },

                selectTable(index) {
                    this.selectedTable = index;
                },
                updateTable() {
                    alert('Table updated successfully!');
                    this.selectedTable = null;
                },

                  cleanTable(tableId) {
                    // 1. Hanapin ang table gamit ang ID na ipinasa mula sa HTML
                    const index = this.tables.findIndex(t => t.id === tableId);
                    
                    if(index !== -1) {
                        const tableNumber = this.tables[index].number;
                        
                        // 2. Palitan ang status para mawala sa Cleaning Dashboard
                        this.tables[index].status = 'available';
                        this.tables[index].guests = 0; // i-reset ang bilang ng tao

                        // 3. I-log sa Recent Activities!
                        this.logActivity(`Table ${tableNumber} - Table Cleaned`, 'Done');
                    }
                },




                assignNewTable() {
                    const nextTable = this.tables.length + 1;
                    this.tables.push({
                        id: nextTable,
                        number: nextTable.toString().padStart(2, '0'),
                        status: 'available',
                        guests: 0
                    });
                },
                simulateNewOrder() {
                    const sampleItems = ['UB Burger', 'Spicy Pasta', 'Fries', 'Iced Tea', 'Chicken Wings', 'Caesar Salad'];
                    const randomItem = sampleItems[Math.floor(Math.random() * sampleItems.length)];
                    const tableNum = Math.floor(Math.random() * 6) + 1;
                    
                    this.orders.push({
                        id: Date.now(),
                        table: tableNum.toString().padStart(2, '0'),
                        status: 'pending',
                        items: [{ qty: Math.floor(Math.random() * 3) + 1, name: randomItem }]
                    });
                },
                simulateCustomerRequest() {
                    const requestTypes = ['Water Refill', 'Extra Napkins', 'Check Please', 'Complaint', 'Special Request'];
                    const priorities = ['HIGH', 'NORMAL'];
                    const tableNum = Math.floor(Math.random() * 6) + 1;
                    
                    this.requests.push({
                        id: Date.now(),
                        table: tableNum.toString().padStart(2, '0'),
                        type: requestTypes[Math.floor(Math.random() * requestTypes.length)],
                        message: 'Customer requires immediate attention',
                        priority: priorities[Math.floor(Math.random() * priorities.length)]
                    });
                },
                 updateOrderStatus(index, status) {
                    this.orders[index].status = status;
                    if(status === 'completed') {
                        // Kukunin na ang totoong total amount mula sa order imbes na random number
                        const actualTotal = parseFloat(this.orders[index].total) || 0;
                        
                        this.recentBills.unshift({
                            id: Date.now(),
                            table: this.orders[index].table,
                            amount: actualTotal, // Gagamitin ang totoong presyo dito
                            method: ['Cash', 'Card', 'GCash'][Math.floor(Math.random() * 3)],
                            time: new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit', hour12: true })
                        });
                        
                        // Idadagdag ang totoong amount sa overall Total Revenue
                        this.totalRevenue += actualTotal; 
                        this.billedCount++;
                        this.logActivity(`Table ${this.orders[index].table} - Bill Processed`, 'Completed');
                    }
                },
                removeOrder(index) {
                    this.orders.splice(index, 1);
                },
                completeRequest(index) {
                   const requestDone = this.requests[index];
                    
                    // Idagdag sa Recent Activities
                    this.logActivity(`Table ${requestDone.table} - ${requestDone.type} Resolved`, 'Done');
                    
                    this.requests.splice(index, 1);
                },
                loadIncomingOrders() {
                    const orders = JSON.parse(localStorage.getItem('ub_orders') || '[]');
                    this.incomingOrders = orders.filter(o => o.forWaiter && o.status !== 'served');
                },
                initializeOrderListener() {
                    window.addEventListener('orderPlaced', (event) => {
                        this.loadIncomingOrders();
                    });
                    
                    // Tinawag ang bagong function dito:
                    this.simulateKitchenProgress(); 
                },
                confirmLogout() {
                    document.getElementById('logout-form').submit();
                },
                waiterPerformance: {
                    tablesAttended: 28,
                    satisfaction: 4.8,
                    score: 93,
                    responseTime: 92,
                    hospitality: 96,
                    knowledge: 88
                },
                recentActivities: [
                    { id: 1, action: 'Table 2 - Order Taken', time: '3:15 PM', status: 'Done' },
                    { id: 2, action: 'Table 5 - Water Refill', time: '3:12 PM', status: 'Done' },
                    { id: 3, action: 'Table 8 - Complaint Resolved', time: '3:08 PM', status: 'Done' },
                    { id: 4, action: 'Table 1 - Upsell Successful', time: '3:00 PM', status: 'Done' },
                    { id: 5, action: 'Table 3 - Customer Feedback', time: '2:55 PM', status: 'Positive' }
                ]

                
            }
            
        }

        
    </script>

</body>
</html>