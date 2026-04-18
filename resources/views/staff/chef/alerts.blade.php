            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Kitchen Alerts</h1>
                    <nav class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">
                        <span>Priority</span> <span class="mx-3 text-slate-300">|</span>
                        <span class="text-red-800 font-bold">Resolve Quickly</span>
                    </nav>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <template x-for="(alert, index) in alerts" :key="alert.id">
                    <div class="aws-card p-5 border-l-4" :class="alert.priority === 'HIGH' ? 'border-l-red-700 bg-red-50' : 'border-l-yellow-500 bg-yellow-50'">
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500" x-text="'Table ' + alert.table"></p>
                                <h2 class="font-black text-slate-900" x-text="alert.title"></h2>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-700" x-text="alert.priority"></span>
                        </div>
                        <p class="text-sm text-slate-700 mb-4" x-text="alert.message"></p>
                        <button @click="resolveAlert(index)" class="px-4 py-2 rounded-full bg-slate-900 text-white text-xs font-bold uppercase tracking-widest hover:bg-slate-800 transition">
                            Mark Resolved
                        </button>
                    </div>
                </template>

                <div x-show="alerts.length === 0" class="p-12 text-center border-2 border-dashed border-slate-300 rounded-lg bg-slate-50">
                    <i class="fas fa-bell-slash text-4xl text-slate-300 mb-3"></i>
                    <h3 class="text-lg font-bold text-slate-600 uppercase">No Active Alerts</h3>
                    <p class="text-slate-400 text-sm">Everything is running smoothly in the kitchen.</p>
                </div>
            </div>