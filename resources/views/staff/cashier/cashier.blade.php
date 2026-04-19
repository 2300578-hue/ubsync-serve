
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Command Center | UB-SYNC</title>
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

        .clay-card {
            background: white;
            border-radius: 4px; 
            border: 1px solid #eaeded;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        /* Sign Out Button Style */
        .sign-out-btn {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 800;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            color: white;
        }
        .sign-out-btn:hover { background: rgba(255,255,255,0.1); }

        .maroon-gradient { background: linear-gradient(135deg, #800000 0%, #a52a2a 100%); }
        .status-badge { font-size: 0.65rem; padding: 2px 8px; border-radius: 20px; font-weight: 800; text-transform: uppercase; }
        [x-cloak] { display: none !important; }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #800000; border-radius: 10px; }
    </style>
</head>

<body x-data="{ 
    tab: 'home',
    sidebarOpen: window.innerWidth >= 768,
    showTableDetail: null,
    showVacantModal: false,
    
  
salesSummary: { total: 24500 },


get totalSalesToday() {
   
    return this.tables
        .filter(t => t.payment === 'Paid' && t.bill > 0)
        .reduce((sum, t) => sum + t.bill, 0) + this.salesSummary.total;
},

    
    transactions: [
        { id: 1, description: 'Table 1 payment', amount: 390, time: '3:15 PM', type: 'sale' },
        { id: 2, description: 'Table 5 payment', amount: 350, time: '3:09 PM', type: 'sale' },
        { id: 3, description: 'Refund issued', amount: 45, time: '2:55 PM', type: 'refund' },
        { id: 4, description: 'Table 2 payment', amount: 225, time: '2:42 PM', type: 'sale' }
    ],
    cashierPerformance: {
        tablesAttended: 28,
        satisfactionRate: 4.8,
        performanceScore: 93,
        skills: [
            { label: 'Response Time', value: 92, color: 'bg-violet-500' },
            { label: 'Hospitality', value: 96, color: 'bg-pink-500' },
            { label: 'Product Knowledge', value: 88, color: 'bg-blue-500' }
        ]
    },
    cashierActivity: [
        { id: 1, action: 'Table 2 - Order Taken', time: '3:15 PM', status: 'Done' },
        { id: 2, action: 'Table 5 - Water Refill', time: '3:12 PM', status: 'Done' },
        { id: 3, action: 'Table 8 - Complaint Resolved', time: '3:08 PM', status: 'Done' },
        { id: 4, action: 'Table 1 - Upsell Successful', time: '3:00 PM', status: 'Done' }
    ],
    selectedTableForOrder: '',
    cart: [],
    products: [
        { id: 1, name: 'Burger Steak', cat: 'Meals', price: 150, stock: 25, img: 'burgersteak.png' },
        { id: 2, name: 'Carbonara', cat: 'Pasta', price: 180, stock: 15, img: 'carbonara.png' },
        { id: 3, name: 'Ice Tea', cat: 'Beverages', price: 45, stock: 100, img: 'icetea.png' },
        { id: 4, name: 'Leche Flan', cat: 'Desserts', price: 75, stock: 20, img: 'lecheflan.png' },
        { id: 5, name: 'Mozarella Sticks', cat: 'Appetizer', price: 130, stock: 15, img: 'mozarella.png' }
    ],
 tables: [
    { 
        id: 1, 
        isSessionActive: true, 
        payment: 'Unpaid', 
        isMarkedPaid: false, 
        orders: [
            { name: 'Burger Steak', qty: 2, img: 'burgersteak.png' },
            { name: 'Ice Tea', qty: 2, img: 'icetea.png' }
        ], 
        bill: 390 
    },
    { 
        id: 2, 
        isSessionActive: true, 
        payment: 'Paid', 
        isMarkedPaid: false, 
        orders: [
            { name: 'Carbonara', qty: 1, img: 'carbonara.png' },
            { name: 'Ice Tea', qty: 1, img: 'icetea.png' }
        ], 
        bill: 225 
    },
    { 
        id: 3, 
        isSessionActive: true, 
        payment: 'Unpaid', 
        isMarkedPaid: false, 
        orders: [
            { name: 'Burger Steak', qty: 3, img: 'burgersteak.png' },
            { name: 'Leche Flan', qty: 1, img: 'lecheflan.png' }
        ], 
        bill: 525 
    },
    { 
        id: 4, 
        isSessionActive: false, 
        payment: 'Paid', 
        isMarkedPaid: false, 
        orders: [], 
        bill: 0 
    },
    { 
        id: 5, 
        isSessionActive: true, 
        payment: 'Paid', 
        isMarkedPaid: true, 
        orders: [
            { name: 'Mozarella Sticks', qty: 2, img: 'mozarella.png' },
            { name: 'Ice Tea', qty: 2, img: 'icetea.png' }
        ], 
        bill: 350 
    },
    { 
        id: 6, 
        isSessionActive: true, 
        payment: 'Unpaid', 
        isMarkedPaid: false, 
        orders: [
            { name: 'Carbonara', qty: 2, img: 'carbonara.png' }
        ], 
        bill: 360 
    },
    { 
        id: 7, 
        isSessionActive: false, 
        payment: 'Paid', 
        isMarkedPaid: false, 
        orders: [], 
        bill: 0 
    },
    { 
        id: 8, 
        isSessionActive: true, 
        payment: 'Paid', 
        isMarkedPaid: false, 
        orders: [
            { name: 'Leche Flan', qty: 3, img: 'lecheflan.png' },
            { name: 'Ice Tea', qty: 1, img: 'icetea.png' }
        ], 
        bill: 270 
    },
    { 
        id: 9, 
        isSessionActive: true, 
        payment: 'Unpaid', 
        isMarkedPaid: false, 
        orders: [
            { name: 'Burger Steak', qty: 1, img: 'burgersteak.png' },
            { name: 'Mozarella Sticks', qty: 1, img: 'mozarella.png' }
        ], 
        bill: 280 
    }
],
    addToCart(product) {
        if(product.stock <= 0) return alert('Out of Stock!');
        let item = this.cart.find(i => i.id === product.id);
        if(item) { item.qty++; } else { this.cart.push({ ...product, qty: 1 }); }
        product.stock--;
    },
    removeFromCart(index) {
        let item = this.cart[index];
        let p = this.products.find(x => x.id === item.id);
        if(p) p.stock += item.qty;
        this.cart.splice(index, 1);
    },
    get cartTotal() { return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0); },
    get averageTicket() {
        let sales = this.transactions.filter(txn => txn.type === 'sale');
        return sales.length ? sales.reduce((sum, txn) => sum + txn.amount, 0) / sales.length : 0;
    },
    placeOrder() {
        if(!this.selectedTableForOrder) return alert('Select Table First');
        let t = this.tables.find(x => x.id == this.selectedTableForOrder);
        t.isSessionActive = true;
        t.payment = 'Unpaid';
        t.isMarkedPaid = false;
        t.bill += this.cartTotal;
        this.cart.forEach(c => t.orders.push(c.name + ' x' + c.qty));
        this.cart = [];
        this.selectedTableForOrder = '';
        this.tab = 'home';
    },
    markAsPaid(id) {
        let t = this.tables.find(x => x.id === id);
        this.salesSummary.total += t.bill;
        t.isMarkedPaid = true;
        t.payment = 'Paid';
    },
    resetTable(id) {
        let t = this.tables.find(x => x.id === id);
        t.isSessionActive = false;
        t.orders = [];
        t.payment = 'Paid';
        t.isMarkedPaid = false;
        t.bill = 0;
    }
}">

    <header class="aws-header shadow-lg">
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="hover:bg-white/20 p-2 rounded transition cursor-pointer">
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
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2.5 text-[11px] font-black text-red-600 hover:bg-red-50 rounded-lg uppercase tracking-widest flex items-center gap-3 transition-all group">
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

    <div x-show="sidebarOpen && window.innerWidth < 768" 
         @click="sidebarOpen = false" 
         x-transition.opacity 
         class="fixed inset-0 bg-black/60 z-[999] md:hidden" style="top: 69px;">
    </div>
    <aside class="aws-sidebar shadow-sm" :class="!sidebarOpen ? 'sidebar-collapsed' : ''">
        <div class="p-6 space-y-8">
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 px-2">Main Navigation</p>
                <nav class="space-y-1">
                    <button @click="tab = 'home'; if(window.innerWidth < 768) sidebarOpen = false;" :class="tab === 'home' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 transition-all text-left font-semibold">
                        <i class="fa-solid fa-table-cells-large w-5"></i> Control Center
                    </button>
                    <button @click="tab = 'pos'; if(window.innerWidth < 768) sidebarOpen = false;" :class="tab === 'pos' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 transition-all text-left font-semibold">
                        <i class="fa-solid fa-cash-register w-5"></i> Service Hub
                    </button>
                    <button @click="tab = 'inventory'; if(window.innerWidth < 768) sidebarOpen = false;" :class="tab === 'inventory' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 transition-all text-left font-semibold">
                        <i class="fa-solid fa-boxes-stacked w-5"></i> Stock Vault
                    </button>
                    <button @click="tab = 'performance'; if(window.innerWidth < 768) sidebarOpen = false;" :class="tab === 'performance' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" class="w-full flex items-center gap-4 p-3 transition-all text-left font-semibold">
                        <i class="fa-solid fa-chart-line w-5"></i> Performance
                    </button>
                </nav>
            </div>
        </div>
    </aside>

    <main class="main-content" :class="!sidebarOpen ? 'content-wide' : ''">
        
        <div x-show="tab === 'home'" x-cloak class="space-y-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-800 uppercase tracking-tighter">System Overview</h1>
                    <p class="text-[10px] sm:text-sm text-slate-500 font-bold tracking-widest uppercase">Admin Command Console</p>
                </div>
                <button x-on:click="showVacantModal = true" class="bg-slate-800 text-white px-4 sm:px-6 py-2.5 text-xs sm:text-sm font-black hover:bg-slate-700 transition rounded flex items-center justify-center gap-2 w-full sm:w-auto shadow-md">
                    <i class="fa-solid fa-eye"></i> View Vacant Tables
                </button>
            </div>

         <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 w-full">
    <div class="clay-card border-t-4 border-t-emerald-500 p-5">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sales Today</p>
        <p class="text-3xl font-black text-slate-800 mt-1" x-text="'₱' + salesSummary.total.toLocaleString()"></p>
    </div>

    <div class="clay-card p-5 border-t-4 border-t-blue-500">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Live Sessions</p>
        <p class="text-3xl font-black text-slate-800 mt-1" x-text="tables.filter(t => t.isSessionActive).length"></p>
    </div>

    <div class="clay-card p-5 border-t-4 border-t-orange-500">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pending Settlements</p>
        <p class="text-3xl font-black text-slate-800 mt-1" x-text="tables.filter(t => t.isSessionActive && t.payment === 'Unpaid').length"></p>
    </div>
</div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="table in tables" :key="table.id">
                    <div x-show="table.isSessionActive" x-transition class="clay-card flex flex-col overflow-hidden">
                        <div class="p-4 bg-slate-50 border-b flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded bg-white border font-black text-red-800 flex items-center justify-center shadow-sm" x-text="table.id"></span>
                                <span class="font-bold text-slate-700 text-xs uppercase">Occupied</span>
                            </div>
                            <span class="status-badge bg-red-800 text-white">Active</span>
                        </div>
                        
                        <div class="p-5 flex-1">
                            <div class="mb-4">
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Current Bill</p>
                                <h2 class="text-2xl font-black text-slate-800" x-text="table.payment === 'Paid' ? '₱' + table.bill.toLocaleString() : '---'"></h2>
                            </div>

                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div class="p-2 rounded border border-dashed text-center" :class="table.payment === 'Unpaid' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600'">
                                    <p class="text-[9px] font-black uppercase" x-text="table.payment === 'Unpaid' ? 'Ordering' : 'Order Ready'"></p>
                                </div>
                                <div class="p-2 rounded border border-dashed text-center" :class="table.payment === 'Unpaid' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600'">
                                    <p class="text-[9px] font-black uppercase" x-text="table.payment === 'Unpaid' ? 'Unpaid' : 'Settled'"></p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <button @click="table.payment === 'Paid' ? showTableDetail = table : alert('Wait for order settlement.')" 
                                        class="w-full py-2.5 rounded text-[10px] font-black uppercase transition-all flex items-center justify-center gap-2 border border-slate-200 hover:bg-slate-50">
                                    <i class="fa-solid fa-eye"></i> View Orders
                                </button>

                                <template x-if="table.payment === 'Paid' && !table.isMarkedPaid">
                                    <button @click="markAsPaid(table.id)" class="w-full py-2.5 bg-red-800 text-white rounded text-[10px] font-black uppercase shadow-sm">
                                        Settle Payment
                                    </button>
                                </template>

                                <template x-if="table.payment === 'Paid' && table.isMarkedPaid">
                                    <button @click="resetTable(table.id)" class="w-full py-2.5 bg-emerald-600 text-white rounded text-[10px] font-black uppercase shadow-sm">
                                        Clear & Close Table
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

       <div x-show="tab === 'pos'" x-cloak>
            @include('staff.cashier.service_hub')
        </div>

        <div x-show="tab === 'inventory'" x-cloak>
            @include('staff.cashier.stock_vault')
        </div>

        <div x-show="tab === 'performance'" x-cloak>
            @include('staff.cashier.performance')
        </div>
    </main>

    <div x-show="showVacantModal" x-cloak class="fixed inset-0 z-[1100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="clay-card w-full max-w-2xl overflow-hidden shadow-2xl">
            <div class="maroon-gradient p-5 text-white flex justify-between items-center">
                <h3 class="font-black uppercase tracking-widest">Available Tables</h3>
                
            </div>
            <div class="p-8 grid grid-cols-2 sm:grid-cols-4 gap-4 max-h-[60vh] overflow-y-auto custom-scroll">
                <template x-for="table in tables" :key="table.id">
                    <div x-show="!table.isSessionActive" class="p-6 rounded-xl border-2 border-slate-100 flex flex-col items-center justify-center bg-slate-50">
                        <span class="text-3xl font-black text-red-800" x-text="table.id"></span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase mt-1">Vacant</span>
                    </div>
                </template>
            </div>
            <div class="p-4 bg-slate-50 text-right border-t">
                <button @click="showVacantModal = false" class="px-6 py-2 bg-slate-800 text-white rounded text-[10px] font-black uppercase">Close</button>
            </div>
        </div>
    </div>

    <div x-show="showTableDetail" x-cloak class="fixed inset-0 z-[1100] flex items-center justify-center bg-black/50 backdrop-blur-md p-4">
        <div class="clay-card w-full max-w-md overflow-hidden shadow-2xl">
            <div class="maroon-gradient p-5 text-white text-center">
                <h3 class="text-xl font-black uppercase" x-text="showTableDetail ? 'Table ' + showTableDetail.id : ''"></h3>
                <p class="text-[9px] font-bold uppercase tracking-widest opacity-70">Order Breakdown</p>
            </div>
              <div class="p-6">

   <div class="space-y-2 mb-6 max-h-[40vh] overflow-y-auto custom-scroll">
    <template x-for="(order, index) in (showTableDetail ? showTableDetail.orders : [])" :key="index">
        <div class="flex items-center gap-4 p-3 bg-slate-50 border rounded-lg">   
            <div class="w-12 h-12 bg-white rounded-lg flex-shrink-0 border p-1 shadow-sm">
                <img :src="'/img/' + order.img" class="w-full h-full object-contain">
            </div>
            <div class="flex-1">
                <p class="text-[11px] font-black text-slate-700 uppercase" x-text="order.name"></p>
                <div class="flex justify-between items-center">
                    <span class="text-[8px] text-emerald-600 font-bold uppercase"><i class="fas fa-check"></i> Confirmed</span>
                    <span class="text-[10px] font-black text-red-800" x-text="'x' + order.qty"></span>
                </div>
            </div>
        </div>
    </template>
</div>
                <div class="flex justify-between items-center border-t border-dashed pt-4 mb-6">
                    <span class="text-xs font-bold text-slate-400 uppercase">Grand Total</span>
                    <span class="text-2xl font-black text-red-800" x-text="showTableDetail ? '₱' + showTableDetail.bill.toLocaleString() : '₱0'"></span>
                </div>
                <button @click="showTableDetail = null" class="w-full py-3 bg-slate-800 text-white rounded font-black text-xs uppercase hover:bg-black transition-all">Confirm & Exit</button>
            </div>
        </div>
    </div>

</body>
</html>