            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Production Dashboard</h1>
                    <nav class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">
                        <span>Prep Station</span> <span class="mx-3 text-slate-300">|</span>
                        <span class="text-red-800 font-bold">Kitchen Command</span>
                    </nav>
                </div>
                <button @click="simulateIncomingOrder()" class="bg-white border border-slate-300 px-6 py-2.5 text-sm font-bold hover:bg-slate-50 transition shadow-sm rounded flex items-center gap-2">
                    <i class="fas fa-sync-alt text-slate-400"></i> New Incoming
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 w-full">
               <div class="aws-card border-t-4 border-t-emerald-500 p-5">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Active Tickets</p>
                    <p class="text-3xl font-black text-slate-800 mt-1" x-text="orders.length"></p>
                </div>
                <div class="aws-card p-5 border-t-4 border-t-blue-500">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Avg Prep Speed</p>
                    <p class="text-3xl font-black text-slate-800 mt-1" x-text="avgPrepSpeed.toFixed(1) + ' mins'"></p>
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
                            <button @click="markReady(index)" class="flex-1 py-3 bg-red-800 text-white font-black text-xs uppercase tracking-widest rounded hover:bg-red-900 transition active:scale-[0.98]">
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