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
                <template x-for="(order, index) in incomingOrders" :key="order.id || index">
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
            