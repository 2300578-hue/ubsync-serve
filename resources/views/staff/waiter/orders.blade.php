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
        <div class="aws-card flex flex-col overflow-hidden transition-all" :class="order.status === 'pending' ? 'border-t-4 border-t-yellow-500' : 'border-t-4 border-t-green-500'">
            <div class="p-4 bg-slate-50 border-b flex justify-between items-center">
                <span class="text-xl font-black text-slate-800">Table <span x-text="order.table"></span></span>
                <span class="text-xs font-bold px-3 py-1 rounded-full" 
                      :class="order.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'" 
                      x-text="order.status.toUpperCase()">
                </span>
            </div>
            <div class="p-4 flex-1 space-y-2">
                <template x-for="item in order.items">
                    <div class="text-sm text-slate-700">
                        <span class="font-bold" x-text="item.qty"></span>x <span x-text="item.name"></span>
                    </div>
                </template>
            </div>
            <div class="p-4 bg-slate-50 border-t border-slate-100 space-y-2">
                <button @click="updateOrderStatus(index, 'completed')" class="w-full py-2 bg-green-600 text-white font-bold text-xs uppercase rounded hover:bg-green-700 transition">
                    Mark Ready
                </button>
                <button @click="removeOrder(index)" class="w-full py-2 bg-gray-300 text-gray-700 font-bold text-xs uppercase rounded hover:bg-gray-400 transition">
                    Remove
                </button>
            </div>
        </div>
    </template>

    <div x-show="orders.length === 0" class="col-span-full p-12 text-center border-2 border-dashed border-slate-300 rounded-lg bg-slate-50">
        <i class="fas fa-check-double text-4xl text-slate-300 mb-3"></i>
        <h3 class="text-lg font-bold text-slate-600 uppercase">No Pending Orders</h3>
        <p class="text-slate-400 text-sm">All orders are served. Great job!</p>
    </div>
</div>
