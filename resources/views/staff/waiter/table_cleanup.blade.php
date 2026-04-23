<div x-show="currentTab === 'cleanup'" x-cloak>
    <div class="p-6">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Table Cleaning</h1>
            <p class="text-sm text-slate-500 font-medium uppercase tracking-wide mt-1">Maintenance & Sanitation Hub</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="table in tables.filter(t => t.status === 'cleaning' || t.status === 'pending')" :key="table.id">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden transition-all hover:shadow-md border-t-4 border-t-yellow-500">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-2xl font-black text-slate-800" x-text="'Table ' + table.number"></h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Status: For Clearing</p>
                            </div>
                            <span class="bg-yellow-100 text-yellow-700 text-[10px] font-black px-2 py-1 rounded-sm uppercase tracking-tighter">Needs Cleaning</span>
                        </div>
                        
                        <div class="bg-slate-50 rounded-lg p-4 mb-6">
                            <p class="text-xs text-slate-600 leading-relaxed font-medium">
                                <i class="fas fa-info-circle mr-1 text-yellow-600"></i>
                                Customer has finished dining. Please clean, sanitize, and reset the table for the next guest.
                            </p>
                        </div>

                         <button @click="cleanTable(table.id)" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-3 rounded-lg text-xs uppercase tracking-[0.2em] transition-all active:scale-95 shadow-sm flex items-center justify-center gap-2">
    Mark as Available
</button>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="tables.filter(t => t.status === 'cleaning' || t.status === 'pending').length === 0" 
             class="text-center py-24 bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check-circle text-4xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Table Clear</h3>
            <p class="text-slate-400 font-bold uppercase text-[10px] tracking-[0.3em] mt-2">All tables are clean and ready for guests</p>
        </div>
    </div>
</div>