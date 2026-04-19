<div class="flex justify-between items-end mb-8">
    <div>
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Incoming Customer Orders</h1>
        <nav class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">
            <span>Awaiting Service</span> <span class="mx-3 text-slate-300">|</span>
            <span class="text-red-800 font-bold">Kitchen Dispatch</span>
        </nav>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="aws-card border-t-4 border-t-blue-500 p-5">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pending Delivery</p>
        <p class="text-3xl font-black text-slate-800 mt-1" x-text="incomingOrders.filter(o => !o.cooking && !o.ready).length"></p>
    </div>
    <div class="aws-card p-5 border-t-4 border-t-yellow-500">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Being Prepared</p>
        <p class="text-3xl font-black text-slate-800 mt-1" x-text="incomingOrders.filter(o => o.cooking && !o.ready).length"></p>
    </div>
    <div class="aws-card p-5 border-t-4 border-t-green-500">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Ready to Serve</p>
        <p class="text-3xl font-black text-slate-800 mt-1" x-text="incomingOrders.filter(o => o.ready).length"></p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <template x-for="(order, index) in incomingOrders" :key="order.id">
        <div class="aws-card overflow-hidden transition-all shadow-sm" 
             :class="order.ready ? 'border-t-4 border-t-green-500 bg-green-50' : order.cooking ? 'border-t-4 border-t-yellow-500' : 'border-t-4 border-t-blue-500'">
            
            <div class="p-4 bg-slate-50 border-b flex justify-between items-center">
                <span class="text-xl font-black text-slate-800">Table <span x-text="order.table"></span></span>
                <span class="text-xs font-bold px-3 py-1 rounded-full" 
                      :class="order.ready ? 'bg-green-100 text-green-800' : order.cooking ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800'"
                      x-text="order.ready ? 'READY' : order.cooking ? 'COOKING' : 'PENDING'">
                </span>
            </div>

            <div class="p-4 flex-1 space-y-2">
                <p class="font-bold text-slate-700" x-text="'Customer: ' + (order.customer || 'Guest')"></p>
                <div class="border-t pt-3">
                    <template x-for="item in order.items">
                        <div class="text-sm text-slate-700">
                            <span class="font-bold" x-text="item.qty"></span>x <span x-text="item.name"></span>
                        </div>
                    </template>
                </div>
                <p class="text-sm font-bold text-slate-500 mt-3">Total: ₱<span x-text="order.total"></span></p>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100">
                <button @click="markTableServed(order.id)" 
                        x-show="order.ready"
                        class="w-full py-2 bg-green-600 text-white font-bold text-xs uppercase rounded hover:bg-green-700 transition shadow-sm">
                    <i class="fas fa-check mr-2"></i>Mark Served
                </button>

                <button x-show="!order.ready" disabled 
                        class="w-full py-2 bg-slate-200 text-slate-500 font-bold text-xs uppercase rounded cursor-not-allowed">
                    <i class="fas fa-clock mr-2"></i>Waiting for Kitchen
                </button>
            </div>
        </div>
    </template>

    <div x-show="incomingOrders.length === 0" 
         class="col-span-full p-12 text-center border border-slate-200 rounded-lg bg-white shadow-sm">
        <i class="fas fa-inbox text-4xl text-slate-200 mb-4"></i>
        <h3 class="text-lg font-bold text-slate-600 uppercase tracking-tight">No Incoming Orders</h3>
        <p class="text-slate-400 text-sm mt-1">All customer orders have been served. Great service!</p>
    </div>
</div>