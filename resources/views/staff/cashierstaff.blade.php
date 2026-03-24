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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .clay-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1.25rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
       /* BURAHIN ITO */


        .bg-maroon { background-color: #800000; }
        .maroon-gradient { background: linear-gradient(135deg, #800000 0%, #a52a2a 100%); }
        .nav-active { 
            background: #800000 !important; 
            color: white !important;
            box-shadow: 0 10px 15px -3px rgba(128, 0, 0, 0.3);
        }
        [x-cloak] { display: none !important; }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #800000; border-radius: 10px; }
        
        .status-badge {
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 800;
            text-transform: uppercase;
        }
    </style>
</head>

<body x-data="{ 
    tab: 'home',
    sidebarOpen: true,
    showTableDetail: null,
    showVacantModal: false, // DAGDAG: Para sa View Table modal
    salesSummary: { total: 24500 },
    
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
        { id: 1, isSessionActive: true, payment: 'Unpaid', isMarkedPaid: false, orders: ['Burger Steak x2', 'Ice Tea x2'], bill: 345 },
        { id: 2, isSessionActive: true, payment: 'Paid', isMarkedPaid: false, orders: ['Carbonara x1', 'Ice Tea x1'], bill: 150 },
        { id: 3, isSessionActive: true, payment: 'Unpaid', isMarkedPaid: false, orders: ['Ice tea x1', 'Burger Steak x2'], bill: 550 },
        { id: 4, isSessionActive: false, payment: 'Paid', isMarkedPaid: false, orders: [], bill: 0 },
        { id: 5, isSessionActive: true, payment: 'Paid', isMarkedPaid: false, orders: ['Leche flan x2', 'Ice tea x2'], bill: 280 },
        { id: 6, isSessionActive: true, payment: 'Unpaid', isMarkedPaid: false, orders: ['Mozarella Sticks x1'], bill: 85 },
        { id: 7, isSessionActive: false, payment: 'Paid', isMarkedPaid: false, orders: [], bill: 0 },
        { id: 8, isSessionActive: true, payment: 'Paid', isMarkedPaid: false, orders: ['Leche flan x3', 'Carbonara x3'], bill: 450 },
        { id: 9, isSessionActive: true, payment: 'Unpaid', isMarkedPaid: false, orders: ['Burger Steak x1'], bill: 120 }
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

    <div class="flex min-h-screen">
        <aside :class="sidebarOpen ? 'w-72' : 'w-0 -ml-72'" class="bg-white border-r border-gray-100 flex flex-col fixed h-full z-50 overflow-hidden transition-all duration-300 shadow-2xl">
            <div class="p-8 flex items-center gap-3">
                <div class="w-10 h-10 maroon-gradient rounded-xl flex items-center justify-center shadow-lg"><i class="fa-solid fa-sync text-white"></i></div>
                <h1 class="font-extrabold text-2xl text-[#800000] uppercase tracking-tighter">UB-SYNC</h1>
            </div>

            <nav class="flex-1 px-4 space-y-2">
                <button x-on:click="tab = 'home'" :class="tab === 'home' ? 'nav-active' : 'hover:bg-gray-50'" class="w-full flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-gray-500 transition-all text-sm text-left">
                    <i class="fa-solid fa-table-cells-large w-5 text-lg"></i> Control Center
                </button>
                <button x-on:click="tab = 'pos'" :class="tab === 'pos' ? 'nav-active' : 'hover:bg-gray-50'" class="w-full flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-gray-500 transition-all text-sm text-left">
                    <i class="fa-solid fa-cash-register w-5 text-lg"></i> Service Hub
                </button>
                <button x-on:click="tab = 'inventory'" :class="tab === 'inventory' ? 'nav-active' : 'hover:bg-gray-50'" class="w-full flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-gray-500 transition-all text-sm text-left">
                    <i class="fa-solid fa-boxes-stacked w-5 text-lg"></i> Stock Vault
                </button>
            </nav>

            <div class="p-6 border-t border-gray-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl font-bold text-[11px] uppercase tracking-widest text-red-600 bg-red-50 hover:bg-red-100 transition-colors">
                        <i class="fa-solid fa-power-off"></i> Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <main :class="sidebarOpen ? 'ml-72' : 'ml-0'" class="flex-1 transition-all duration-300">
            <header class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center sticky top-0 z-40">
                <div class="flex items-center gap-6">
                    <button x-on:click="sidebarOpen = !sidebarOpen" class="w-10 h-10 bg-gray-50 text-gray-600 rounded-xl flex items-center justify-center hover:bg-gray-100 transition-transform">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>
                    <span class="font-extrabold text-sm text-gray-800 uppercase tracking-widest" x-text="tab === 'home' ? 'Dashboard' : (tab === 'pos' ? 'POS' : 'Inventory')"></span>
                </div>

                <div class="flex items-center gap-4 py-1 px-2 pr-4 rounded-2xl border border-gray-50 bg-gray-50/30">
                    <div class="w-9 h-9 rounded-xl maroon-gradient flex items-center justify-center text-white font-black text-xs shadow-md uppercase">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-[13px] font-black text-gray-800 uppercase tracking-tight">{{ Auth::user()->name }}</p>
                    </div>
                </div>
            </header>

            <div class="p-8">
                <div x-show="tab === 'home'" x-cloak class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="clay-card p-6 bg-white border-b-4 border-green-500">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-[10px] font-black text-gray-400 uppercase">Sales Today</p>
                                <i class="fa-solid fa-chart-line text-green-500"></i>
                            </div>
                            <h3 class="text-2xl font-black text-gray-800" x-text="'₱' + salesSummary.total.toLocaleString()"></h3>
                        </div>
                        <div class="clay-card p-6 bg-white border-b-4 border-blue-500">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-[10px] font-black text-gray-400 uppercase">Live Tables</p>
                                <i class="fa-solid fa-users text-blue-500"></i>
                            </div>
                            <h3 class="text-2xl font-black text-gray-800" x-text="tables.filter(t => t.isSessionActive).length"></h3>
                        </div>
                        <div class="clay-card p-6 bg-white border-b-4 border-orange-500">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-[10px] font-black text-gray-400 uppercase">Unpaid Orders</p>
                                <i class="fa-solid fa-clock-rotate-left text-orange-500"></i>
                            </div>
                            <h3 class="text-2xl font-black text-gray-800" x-text="tables.filter(t => t.isSessionActive && t.payment === 'Unpaid').length"></h3>
                        </div>

                       <div x-on:click="showVacantModal = true" class="clay-card p-6 bg-white border-b-4 border-white cursor-pointer">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-[10px] font-black text-gray-400 uppercase">Table Manager</p>
                                <i class="fa-solid fa-eye text-[#800000]"></i>
                            </div>
                            <h3 class="text-sm font-black text-[#800000] uppercase tracking-tighter">VIEW TABLE</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="table in tables" :key="table.id">
                            <div x-show="table.isSessionActive" x-transition class="clay-card overflow-hidden">
                                <div class="p-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center font-black text-[#800000]" x-text="table.id"></div>
                                        <h4 class="font-black text-gray-700 uppercase text-xs">Table Session</h4>
                                    </div>
                                    <span class="status-badge bg-maroon text-white">Occupied</span>
                                </div>
                                
                                <div class="p-6">
                                    <div class="flex justify-between items-end mb-6">
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Total Bill</p>
                                            <template x-if="table.payment === 'Paid'">
                                                <h2 class="text-3xl font-black text-gray-800" x-text="'₱' + table.bill.toLocaleString()"></h2>
                                            </template>
                                            <template x-if="table.payment === 'Unpaid'">
                                                <div class="flex items-center gap-2 text-gray-400">
                                                    <i class="fa-solid fa-lock text-xs"></i>
                                                    <span class="text-sm font-bold italic tracking-tighter uppercase">Ordering...</span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 mb-6">
                                        <div class="p-3 rounded-xl border border-dashed flex flex-col items-center justify-center gap-1"
                                             :class="table.payment === 'Unpaid' ? 'border-blue-200 bg-blue-50 text-blue-600' : 'border-green-200 bg-green-50 text-green-600'">
                                            <i class="fa-solid" :class="table.payment === 'Unpaid' ? 'fa-utensils' : 'fa-check-double'"></i>
                                            <span class="text-[9px] font-black uppercase" x-text="table.payment === 'Unpaid' ? 'Ordering' : 'Order Ready'"></span>
                                        </div>
                                        <div class="p-3 rounded-xl border border-dashed flex flex-col items-center justify-center gap-1"
                                             :class="table.payment === 'Unpaid' ? 'border-red-200 bg-red-50 text-red-600' : 'border-green-200 bg-green-50 text-green-600'">
                                            <i class="fa-solid" :class="table.payment === 'Unpaid' ? 'fa-wallet' : 'fa-receipt'"></i>
                                            <span class="text-[9px] font-black uppercase" x-text="table.payment === 'Unpaid' ? 'Unpaid' : 'Paid'"></span>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <button x-on:click="table.payment === 'Paid' ? showTableDetail = table : alert('Please wait for order settlement.')" 
                                                class="w-full py-3 rounded-xl text-[10px] font-black uppercase transition-all flex items-center justify-center gap-2 shadow-sm"
                                                :class="table.payment === 'Paid' ? 'bg-gray-800 text-white hover:bg-gray-900' : 'bg-gray-100 text-gray-300 cursor-not-allowed'">
                                            <i class="fa-solid fa-eye"></i> View Order Details
                                        </button>

                                        <template x-if="table.payment === 'Unpaid'">
                                            <div class="w-full py-3 bg-orange-50 text-orange-600 rounded-xl text-[10px] font-black uppercase text-center border border-orange-100">
                                                <i class="fa-solid fa-spinner animate-spin mr-2"></i> Session Ongoing
                                            </div>
                                        </template>

                                        <template x-if="table.payment === 'Paid' && !table.isMarkedPaid">
                                            <button x-on:click="markAsPaid(table.id)" class="w-full py-3 bg-[#800000] text-white rounded-xl text-[10px] font-black uppercase hover:opacity-90 transition-all shadow-md">
                                                <i class="fa-solid fa-hand-holding-dollar mr-1"></i> Settle Payment
                                            </button>
                                        </template>

                                        <template x-if="table.payment === 'Paid' && table.isMarkedPaid">
                                            <button x-on:click="resetTable(table.id)" class="w-full py-3 bg-green-600 text-white rounded-xl text-[10px] font-black uppercase hover:bg-green-700 transition-all shadow-md">
                                                <i class="fa-solid fa-broom mr-1"></i> Clear & Close Table
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div x-show="tab === 'pos'" x-cloak>@include('pos.service_hub')</div>
                <div x-show="tab === 'inventory'" x-cloak>@include('pos.stock_vault')</div>
            </div>
        </main>
    </div>

    <div x-show="showVacantModal" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div class="clay-card w-full max-w-2xl bg-white overflow-hidden shadow-2xl relative">
            <div class="maroon-gradient p-6 text-white flex justify-between items-center">
                <div>
                 <h3 class="text-xl font-black uppercase tracking-normal">AVAILABLE &nbsp; &nbsp;TABLES</h3>
                    <p class="text-[10px] font-bold opacity-70 uppercase">Ready for new customers</p>
                </div>
                
            </div>
            
            <div class="p-8 grid grid-cols-2 sm:grid-cols-4 gap-4 max-h-[60vh] overflow-y-auto custom-scroll">
                <template x-for="table in tables" :key="table.id">
                    <div x-show="!table.isSessionActive" class="p-6 rounded-2xl border border-[#800000] flex flex-col items-center justify-center gap-2 bg-white">
    <span class="text-3xl font-black text-[#800000]" x-text="table.id"></span>
    <span class="text-[10px] font-black text-[#800000] uppercase">VACANT</span>
</div>
                </template>
            </div>
            <div class="p-6 bg-gray-50 text-center border-t border-gray-100">
                <button x-on:click="showVacantModal = false" class="px-8 py-3 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase">Close Viewer</button>
            </div>
        </div>
    </div>

    <div x-show="showTableDetail" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center bg-black/40 backdrop-blur-md p-4">
        <div class="clay-card w-full max-w-md bg-white overflow-hidden shadow-2xl relative" @click.away="showTableDetail = null">
            <div class="maroon-gradient p-6 text-white text-center">
                <h3 class="text-2xl font-black uppercase mb-1" x-text="showTableDetail ? 'Table ' + showTableDetail.id : ''"></h3>
                <p class="text-[10px] opacity-80 font-bold uppercase tracking-[0.2em]">Transaction Verified</p>
            </div>
            
            <div class="p-8">
                <div class="space-y-3 mb-6 max-h-[40vh] overflow-y-auto custom-scroll pr-2">
                    <template x-for="(orderStr, index) in (showTableDetail ? showTableDetail.orders : [])" :key="index">
                        <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-2xl border border-gray-100">
                            
                            <div class="w-14 h-14 rounded-xl overflow-hidden bg-white border border-gray-200 flex-shrink-0">
                          <img :src="
                           (() => {
                          let found = products.find(p => orderStr.toLowerCase().includes(p.name.toLowerCase()));
                         return found ? '/img/' + found.img : 'https://via.placeholder.com/150?text=Food';
                         })()
                    " class="w-full h-full object-cover">
                      </div>

                            <div class="flex-1">
                                <p class="text-[11px] font-black text-gray-800 uppercase leading-tight" x-text="orderStr"></p>
                                <div class="flex items-center gap-1 mt-1">
                                    <i class="fa-solid fa-circle-check text-[10px] text-green-500"></i>
                                    <span class="text-[8px] font-bold text-gray-400 uppercase">Confirmed</span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex justify-between items-center mb-8 px-2 border-t border-dashed pt-4">
                    <span class="text-xs font-black text-gray-400 uppercase">Grand Total</span>
                    <span class="text-2xl font-black text-[#800000]" x-text="showTableDetail ? '₱' + showTableDetail.bill.toLocaleString() : '₱0'"></span>
                </div>

                <button x-on:click="showTableDetail = null" class="w-full py-4 bg-gray-900 text-white rounded-2xl font-black text-xs uppercase shadow-xl hover:bg-black transition-all">
                    Confirm & Exit
                </button>
            </div>
        </div>
    </div>
</body>
</html>