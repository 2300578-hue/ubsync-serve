<div class="flex justify-between items-end mb-8">
    <div>
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Orders Management</h1>
        <nav class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">
            <span>Active Orders</span> <span class="mx-3 text-slate-300">|</span>
            <span class="text-red-800 font-bold">Service Hub</span>
        </nav>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <template x-for="(order, index) in orders" :key="order.id">
        <div class="aws-card flex flex-col overflow-hidden transition-all shadow-sm bg-white border border-slate-100" 
             :class="order.status === 'pending' ? 'border-t-4 border-t-yellow-500' : 'border-t-4 border-t-green-500'">
            
            <div class="p-4 bg-slate-50 border-b flex justify-between items-center">
                <span class="text-xl font-black text-slate-800">Table <span x-text="order.table"></span></span>
                <span class="text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tighter" 
                      :class="order.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'" 
                      x-text="order.status">
                </span>
            </div>

            <div class="p-4 flex-1 space-y-2">
                <template x-for="item in order.items">
                    <div class="text-sm text-slate-700 flex justify-between items-center bg-slate-50 p-2 rounded">
                        <span x-text="item.name" class="font-medium"></span>
                        <span class="font-black text-slate-900 bg-white px-2 py-0.5 rounded shadow-sm border border-slate-100" x-text="item.qty + 'x'"></span>
                    </div>
                </template>
                
                <div class="mt-3 pt-3 border-t border-slate-200 text-right">
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wide">Total Amount</p>
                    <p class="text-lg font-black text-slate-800">₱<span x-text="order.total"></span></p>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100 space-y-2">
                
                <button @click="updateOrderStatus(index, 'completed')" 
                        :disabled="order.status === 'completed'"
                        :class="order.status === 'completed' ? 'bg-slate-200 text-slate-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700 text-white active:scale-95 shadow-sm'"
                        class="w-full py-3 font-black text-xs uppercase rounded-lg transition">
                    <span x-text="order.status === 'completed' ? 'Completed' : 'Mark Ready'"></span>
                </button>
                
                <button x-show="order.status === 'completed'"
                        @click="removeOrder(index)" 
                        class="w-full py-2 bg-red-100 text-red-600 hover:bg-red-200 font-bold text-[10px] uppercase rounded-lg transition active:scale-95">
                    Remove
                </button>

            </div>
        </div>
    </template>

    <div x-show="orders.length === 0" 
         class="col-span-full p-16 text-center border border-slate-200 rounded-xl bg-white shadow-sm">
        <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check-double text-2xl"></i>
        </div>
        <h3 class="text-lg font-black text-slate-600 uppercase tracking-tight">No Pending Orders</h3>
        <p class="text-slate-400 text-xs font-medium mt-1">All orders are served. Great job!</p>
    </div>
</div>