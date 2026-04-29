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
            background: white !important; /* Siguradong white ang loob */
            border: 1px solid #eaeded; 
            border-radius: 8px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); 
            transition: all 0.3s ease;
        }
        

        .maroon-gradient { background: linear-gradient(135deg, #800000 0%, #a52a2a 100%); }
        .status-badge { font-size: 0.65rem; padding: 2px 8px; border-radius: 20px; font-weight: 800; text-transform: uppercase; }
        [x-cloak] { display: none !important; }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #800000; border-radius: 10px; }

      @media print {
            /* 1. Itago nang tuluyan ang mga main layouts para iwas blank pages */
            .aws-header, .gold-accent, .aws-sidebar, .main-content, div[x-show="showTableDetail"] {
                display: none !important;
            }

            /* 2. Tanggalin ang itim na background at ayusin ang position ng receipt popup */
            div[x-show="receiptData"] {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                background: white !important; /* Puti dapat ang background pag print */
                backdrop-filter: none !important;
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
            }
            
            /* Tanggalin ang shadow at padding nung puting box na bumabalot sa resibo */
            div[x-show="receiptData"] > div {
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* 3. I-format ang mismong papel (Thermal 80mm) */
            #print-receipt-area {
                width: 80mm !important;
                margin: 0 !important;
                padding: 10px !important;
                font-family: 'Courier New', Courier, monospace !important;
                color: black !important;
                border: none !important;
            }

            /* 4. Itago ang mga buttons tulad ng Print at Close */
            .no-print { display: none !important; }
        }

        /* 5. Alisin ang default margins ng browser (header/footer na may link at date) */
        @page {
            margin: 0; 
        }
        

    </style>
</head>

<body x-data="cashierSystem()">

    <header class="aws-header shadow-lg">
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="hover:bg-white/20 p-2 rounded transition cursor-pointer">
                <i class="fas fa-bars"></i> 
            </button>
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

    <aside class="aws-sidebar shadow-sm" :class="!sidebarOpen ? 'sidebar-collapsed' : ''">
    <div class="p-6 space-y-8">
        <div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 px-2">Main Navigation</p>
            
            <nav class="space-y-1">
                <button @click="switchTab('home')" 
                    :class="tab === 'home' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" 
                    class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                    <i class="fa-solid fa-table-cells-large w-5"></i> Control Center
                </button>

                <button @click="switchTab('pos')" 
                    :class="tab === 'pos' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" 
                    class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                    <i class="fa-solid fa-cash-register w-5"></i> Service Hub
                </button>

                <button @click="switchTab('inventory')" 
                    :class="tab === 'inventory' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" 
                    class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                    <i class="fa-solid fa-boxes-stacked w-5"></i> Stock Vault
                </button>

                <button @click="switchTab('reservations')" 
                    :class="tab === 'reservations' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" 
                    class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                    <i class="fas fa-calendar-check w-5"></i> Reservations
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
                <p class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">Admin Command Console</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 w-full">
            <div class="clay-card border-t-4 border-t-emerald-500 p-5">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sales Today</p>
                <p class="text-3xl font-black text-slate-800 mt-1" x-text="formatCurrency(salesSummary.total)"></p>
            </div>

            <div class="clay-card border-t-4 border-t-blue-500 p-5">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Live Sessions</p>
                <p class="text-3xl font-black text-slate-800 mt-1" x-text="tables.filter(t => t.isSessionActive).length"></p>
            </div>

            <div class="clay-card border-t-4 border-t-yellow-500 p-5">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Active Reservations</p>
                <p class="text-3xl font-black text-slate-800 mt-1" x-text="reservations.length"></p>
            </div>
        </div>

       <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <template x-for="table in tables" :key="table.id">
        <div x-show="table.isSessionActive && !table.isMarkedPaid" 
             x-transition 
             class="clay-card flex flex-col overflow-hidden border border-slate-200 shadow-md">
            
            <div class="p-4 bg-slate-50 border-b flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded bg-white border font-black text-red-800 flex items-center justify-center shadow-sm" x-text="table.id"></span>
                    <span class="font-bold text-slate-700 text-xs uppercase tracking-tight">Occupied</span>
                </div>
                
                <span class="px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest text-white shadow-sm" 
                      :class="table.payment.startsWith('Paid') ? 'bg-emerald-600' : 'bg-red-800'" 
                      x-text="table.payment"></span>
            </div>
            
            <div class="p-5 flex-1">
            
                <div class="mb-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Bill</p>
                    <h2 class="text-2xl font-black text-slate-800" x-text="formatCurrency(table.bill)"></h2>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div class="p-2 rounded border border-dashed text-center bg-green-50 text-green-600">
                        <p class="text-[9px] font-black uppercase">Order Ready</p>
                    </div>
                    <div class="p-2 rounded border border-dashed text-center"
                         :class="table.payment.startsWith('Paid') ? 'bg-green-50 text-green-600' : 'bg-slate-50 text-slate-400'">
                        <p class="text-[9px] font-black uppercase">Settled</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <button @click="showTableDetail = table" 
                            class="w-full py-2.5 rounded text-[10px] font-black uppercase transition-all flex items-center justify-center gap-2 border border-slate-200 hover:bg-slate-50">
                        <i class="fa-solid fa-eye"></i> View Orders
                    </button>

                    <button @click="markAsPaid(table.id)" 
                            class="w-full py-2.5 text-white rounded text-[10px] font-black uppercase shadow-sm transition-all"
                            :class="table.payment.startsWith('Paid') ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-800 hover:bg-red-900'"
                            x-text="table.payment.startsWith('Paid') ? 'Clear Table (Paid)' : 'Settle Payment'">
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

        <template x-if="tables.filter(t => t.isSessionActive && !t.isMarkedPaid).length === 0">
    <div class="w-full py-20 flex flex-col items-center justify-center bg-white border border-slate-200 rounded-2xl shadow-sm">
        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
            <i class="fa-solid fa-calendar-check text-slate-300 text-2xl"></i>
        </div>
        <h3 class="text-lg font-black text-slate-700 uppercase tracking-tight">No Pending Settlements</h3>
        <p class="text-sm text-slate-400">Updates will appear here when tables are ready for payment.</p>
    </div>
</template>
</div>

    <div x-show="tab === 'reservations'" x-cloak>
        @include('staff.cashier.reservecustomer')
    </div>

    <div x-show="tab === 'inventory'" x-cloak>
        @include('staff.cashier.stock_vault')
    </div>

    <div x-show="tab === 'pos'" x-cloak>
        @include('staff.cashier.service_hub')
    </div>
    
</main>

<div x-show="showTableDetail" x-cloak class="fixed inset-0 z-[1100] flex items-center justify-center bg-black/50 backdrop-blur-md p-4">
    <div class="clay-card w-full max-w-md overflow-hidden shadow-2xl bg-white rounded-xl">
        <div class="maroon-gradient bg-red-900 p-5 text-white text-center">
            <h3 class="text-xl font-black uppercase" x-text="showTableDetail ? 'Table ' + showTableDetail.id : ''"></h3>
            <p class="text-[9px] font-bold uppercase tracking-widest opacity-70">Order Breakdown</p>
        </div>
        <div class="p-6">
            <div class="space-y-2 mb-6 max-h-[40vh] overflow-y-auto custom-scroll">
                <template x-for="(order, index) in (showTableDetail ? showTableDetail.orders : [])" :key="index">
                    <div class="flex items-center gap-4 p-3 bg-slate-50 border rounded-lg">   
                        <div class="w-12 h-12 bg-white rounded-lg flex-shrink-0 border p-1 shadow-sm">
                            <img :src="getImageUrl(order.img)" 
                                 x-on:error="$event.target.src = 'https://placehold.co/150x150/eeeeee/800000?text=No+Image'" 
                                 class="w-full h-full object-contain rounded">
                        </div>
                        <div class="flex-1">
                            <p class="text-[11px] font-black text-slate-700 uppercase" x-text="order.name"></p>
                            <template x-if="order.addonName && order.addonName !== 'Default'">
                                <p class="text-[9px] text-red-600 font-bold italic" x-text="'+ ' + order.addonName"></p>
                            </template>
                            
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-[8px] text-emerald-600 font-bold uppercase"><i class="fas fa-check"></i> Confirmed</span>
                                <span class="text-[10px] font-black text-red-800" x-text="'x' + order.qty"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="flex justify-between items-center border-t border-dashed pt-4 mb-6">
                <span class="text-xs font-bold text-slate-400 uppercase">Grand Total</span>
                <span class="text-2xl font-black text-red-800" x-text="showTableDetail ? formatCurrency(showTableDetail.bill) : '₱0'"></span>
            </div>
            <button @click="showTableDetail = null" class="w-full py-3 bg-slate-800 text-white rounded font-black text-xs uppercase hover:bg-black transition-all">Confirm & Exit</button>
        </div>
    </div>
</div>

</body>
</html>


   <script>
function cashierSystem() {
    return {
        tab: 'home',
        sidebarOpen: window.innerWidth >= 768,
        selectedTableForOrder: '',
        showTableDetail: null,
        receiptData: null,
        cart: [],
        orderHistory: [],
        salesSummary: { total: 0 },
        reservations: [],

        // Listahan ng Tables
        tables: [
            { id: 1, isSessionActive: false, payment: 'Unpaid', orders: [], bill: 0, customerName: '' },
            { id: 2, isSessionActive: false, payment: 'Unpaid', orders: [], bill: 0, customerName: '' },
            { id: 3, isSessionActive: false, payment: 'Unpaid', orders: [], bill: 0, customerName: '' },
            { id: 4, isSessionActive: false, payment: 'Unpaid', orders: [], bill: 0, customerName: '' },
            { id: 5, isSessionActive: false, payment: 'Unpaid', orders: [], bill: 0, customerName: '' },
            { id: 6, isSessionActive: false, payment: 'Unpaid', orders: [], bill: 0, customerName: '' }
        ],

        init() {
            this.loadReservations();

            // Makinig sa orders at reservations galing sa Customer/Booking Tab
            window.addEventListener('storage', (event) => {
                if (event.key === 'ub_new_customer_order' && event.newValue) {
                    this.receiveIncomingOrder(JSON.parse(event.newValue));
                }
                
                if (event.key === 'ub_reservations') {
                    this.loadReservations();
                }
            });

            const missed = localStorage.getItem('ub_new_customer_order');
            if (missed) this.receiveIncomingOrder(JSON.parse(missed));
        },

        receiveIncomingOrder(data) {
            let index = this.tables.findIndex(x => x.id === parseInt(data.table));
            if (index !== -1) {
                this.tables[index].isSessionActive = true;
                this.tables[index].bill = parseFloat(data.bill);
                this.tables[index].payment = data.method || 'Unpaid';
                this.tables[index].orders = data.cart || [];
                this.tables = [...this.tables];
                localStorage.removeItem('ub_new_customer_order');
            }
        },

        // --- UPDATED ADD TO CART WITH ADDONS ---
        addToCart(product, addonValue = 'Default|0') {
            if (product.stock <= 0) return alert('Out of Stock!');

            let addonPrice = 0;
            let addonName = 'Default';

            if (addonValue && addonValue.includes('|')) {
                const parts = addonValue.split('|');
                addonName = parts[0];
                addonPrice = parseInt(parts[1]) || 0;
            }

            // Gumawa ng unique ID base sa Product + Addon para hindi sila mag-merge kung magkaiba ng addon
            let cartId = product.id + '-' + addonName;
            let foundItem = this.cart.find(i => i.cartId === cartId);

            if (foundItem) {
                foundItem.qty++;
            } else {
                this.cart.push({
                    ...product,
                    cartId: cartId,
                    qty: 1,
                    addonName: addonName,
                    price: product.price + addonPrice // Addon price is added to the base price
                });
            }
            product.stock--;
        },

        removeFromCart(index) {
            let item = this.cart[index];
            let p = Alpine.store('inventory').products.find(x => x.id === item.id);
            if (p) p.stock += item.qty;
            this.cart.splice(index, 1);
        },

        get cartTotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        },

        placeOrder() {
            if (!this.selectedTableForOrder) return alert('Select Table First');
            let t = this.tables.find(x => x.id == this.selectedTableForOrder);
            
            t.isSessionActive = true;
            t.payment = 'Unpaid';
            t.isMarkedPaid = false;
            t.bill += this.cartTotal;
            
            // I-save ang orders kasama ang addon details
            this.cart.forEach(c => {
                t.orders.push({ 
                    name: c.name, 
                    qty: c.qty, 
                    price: c.price,
                    addonName: c.addonName,
                    img: c.img 
                });
            });
            
            this.tables = [...this.tables]; 
            this.cart = [];
            this.selectedTableForOrder = '';
            this.tab = 'home';
        },

    markAsPaid(id) {
    let index = this.tables.findIndex(x => x.id === id);
    if (index !== -1) {
        let currentTable = this.tables[index];

        // 1. Ipadala sa Chef Tab (Mananatili ito)
        const dataForChef = {
            id: 'ORD-' + Date.now().toString().slice(-6),
            table: 'TABLE ' + currentTable.id.toString().padStart(2, '0'),
            status: 'NEW',
            items: currentTable.orders.map(item => ({
                name: item.name + (item.addonName && item.addonName !== 'Default' ? ' (' + item.addonName + ')' : ''),
                qty: item.qty,
                done: false
            })),
            note: 'Paid at Counter - Start Prep',
            timestamp: new Date().getTime()
        };
        localStorage.setItem('ub_chef_new_order', JSON.stringify(dataForChef));

        // 2. GUMAWA NG TEMPORARY DATA (Huwag i-assign sa this.receiptData)
        const tempReceiptData = {
            orderId: dataForChef.id,
            tableId: currentTable.id,
            customerName: currentTable.customerName || 'Walk-in',
            items: [...currentTable.orders],
            totalAmount: currentTable.bill,
            timestamp: new Date().toLocaleDateString() + ', ' + new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true })
        };

        // Save to history & sales
        this.orderHistory.unshift({ ...tempReceiptData, paymentMethod: currentTable.payment });
        this.salesSummary.total += currentTable.bill;

        // 3. I-reset agad ang Mesa sa UI (Dito mawawala na agad yung mesa sa screen)
        this.tables[index].isMarkedPaid = true;
        this.tables[index].isSessionActive = false;
        this.tables[index].bill = 0;
        this.tables[index].orders = [];
        this.tables[index].customerName = '';
        this.tables = [...this.tables]; 

        // 4. REKTA PRINT (I-pass natin yung tempReceiptData direkta sa function)
        this.triggerIsolatedPrint(tempReceiptData);
    }
},


        formatCurrency(num) {
            return '₱' + parseFloat(num).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        },

        // --- UPDATED PRINT WITH ADDONS ---
      // --- UPDATED PRINT WITH DYNAMIC TABLE NUMBER ---
   triggerIsolatedPrint(data) {
    if (!data) return;

    // Gagawa ng items list para sa resibo
    let itemsHTML = '';
    data.items.forEach(item => {
        itemsHTML += `
        <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
            <div style="width: 20px;">${item.qty}</div>
            <div style="flex: 1; padding-left: 5px;">
                <div style="text-transform: uppercase;">${item.name}</div>
                ${item.addonName && item.addonName !== 'Default' ? `<div style="font-size: 10px; font-style: italic;">+ ${item.addonName}</div>` : ''}
            </div>
        </div>`;
    });

    // Invisible Iframe para sa "Silent" Printing
    let iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    document.body.appendChild(iframe);
    let doc = iframe.contentWindow.document;
doc.write(`
        <html>
        <head>
            <style>
                body { font-family: 'Courier New', monospace; width: 80mm; padding: 4mm; color: black; font-size: 12px; margin: 0; }
                .text-center { text-align: center; }
                .divider { border-bottom: 1px dashed black; margin: 5px 0; }
                .bold { font-weight: bold; }
                .total { font-size: 16px; font-weight: bold; display: flex; justify-content: space-between; margin-top: 10px; }
                .table-box { font-size: 24px; font-weight: bold; border: 2px solid black; margin: 10px 0; padding: 10px; text-transform: uppercase; }
            </style>
        </head>
        <body>
            <div class="text-center">
                <div class="bold" style="font-size: 14px;">UNIVERSITY OF BATANGAS</div>
                <div style="font-size: 10px;">Hilltop Rd, Batangas City, 4200 Batangas</div> 
                <div class="divider"></div>
                
                <div class="table-box">TABLE ${String(data.tableId).padStart(2, '0')}</div>
            </div>
            
            <div style="font-size: 10px; margin-top: 5px;">
                <div style="display: flex; justify-content: space-between;">
                    <span>Order ID:</span>
                    <span class="bold">${data.orderId}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Date:</span>
                    <span>${data.timestamp}</span>
                </div>
            </div>

            <div class="divider"></div>
            
            <div style="display: flex; font-weight: bold; margin-bottom: 5px; font-size: 10px;">
                <div style="width: 20px;">QTY</div>
                <div style="flex: 1; padding-left: 5px;">ITEM/S</div>
            </div>

            ${itemsHTML}

            <div class="divider"></div>
            
            <div class="total">
                <span>TOTAL:</span>
                <span>₱${parseFloat(data.totalAmount).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
            </div>

            <div class="text-center" style="margin-top: 25px; font-size: 10px;">
                <div class="bold">THANK YOU!</div>
                <div>Please come again.</div>
            </div>
        </body>
        </html>
    `);
    doc.close();

    // Command sa printer
    setTimeout(() => {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
        
        // Pagkatapos lumabas ng print dialog, tanggalin ang iframe at linisin ang variable
        setTimeout(() => {
            document.body.removeChild(iframe);
            this.receiptData = null; 
        }, 1000);
    }, 500);


},


        getImageUrl(img) {
            if (!img || img === 'Default.png') return 'https://placehold.co/150x150/eeeeee/800000?text=No+Image';
            return img.startsWith('http') ? img : '/img/' + img;
        },

        switchTab(target) {
            this.tab = target;
            if (window.innerWidth < 768) this.sidebarOpen = false;
        },

        loadReservations() {
            const stored = localStorage.getItem('ub_reservations');
            this.reservations = stored ? JSON.parse(stored) : [];
        },

        confirmReservation(resId) {
            const index = this.reservations.findIndex(r => r.id === resId);
            if (index !== -1) {
                this.reservations[index].status = 'confirmed'; 
                this.updateStorage();
            }
        },

        deleteReservation(resId) {
            this.reservations = this.reservations.filter(r => r.id !== resId);
            this.updateStorage();
        },

        formatTime(time) {
            if (!time) return '';
            const [hours, minutes] = time.split(':');
            let h = parseInt(hours);
            const ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12 || 12;
            return `${h}:${minutes} ${ampm}`;
        },

        updateStorage() {
            localStorage.setItem('ub_reservations', JSON.stringify(this.reservations));
        }
    }
}
</script>





</body>
</html>