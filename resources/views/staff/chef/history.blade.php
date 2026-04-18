            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Fulfilled Logs</h1>
                    <nav class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">
                        <span>Completed Orders</span> <span class="mx-3 text-slate-300">|</span>
                        <span class="text-red-800 font-bold">Kitchen Records</span>
                    </nav>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <template x-for="log in fulfilledLogs" :key="log.id">
                    <div class="aws-card p-5 border border-slate-200">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h2 class="font-black text-slate-900" x-text="log.table"></h2>
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400" x-text="log.completedAt"></p>
                            </div>
                            <span class="text-xs font-black uppercase text-slate-700 px-3 py-1 rounded-full bg-slate-100" x-text="log.status"></span>
                        </div>
                        <div class="space-y-2">
                            <template x-for="item in log.items" :key="item.name">
                                <p class="text-sm text-slate-700" x-text="item.qty + 'x ' + item.name"></p>
                            </template>
                        </div>
                    </div>
                </template>

                <div x-show="fulfilledLogs.length === 0" class="p-12 text-center border-2 border-dashed border-slate-300 rounded-lg bg-slate-50">
                    <i class="fas fa-clipboard-list text-4xl text-slate-300 mb-3"></i>
                    <h3 class="text-lg font-bold text-slate-600 uppercase">No Fulfilled Logs</h3>
                    <p class="text-slate-400 text-sm">Order history will appear once kitchen items are completed.</p>
                </div>
            </div>