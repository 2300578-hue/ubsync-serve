<div class="flex justify-between items-end mb-8">
    <div>
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Customer Requests</h1>
        <nav class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">
            <span>Dining Area</span> <span class="mx-3 text-slate-300">|</span>
            <span class="text-red-800 font-bold">Call Queue</span>
        </nav>
    </div>
</div>

<div class="bg-white p-6 mb-8 border border-slate-200 rounded-xl shadow-sm border-l-4 border-l-yellow-500">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-full flex items-center justify-center">
            <i class="fas fa-exclamation-circle text-xl"></i>
        </div>
        <div>
            <p class="font-black text-slate-800 uppercase text-sm tracking-tight">Active Requests: <span x-text="requests.length" class="text-yellow-600"></span></p>
            <p class="text-xs text-slate-500 font-medium">Attend to customer requests immediately for better service quality.</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <template x-for="(request, index) in requests" :key="request.id">
        <div class="bg-white p-6 border border-slate-100 rounded-xl shadow-sm border-l-4 border-l-red-600 transition-all hover:shadow-md">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Table <span x-text="request.table"></span></p>
                    <p class="text-xl font-black text-slate-800 mt-1 uppercase tracking-tighter" x-text="request.type"></p>
                </div>
                <span class="text-[9px] font-black bg-red-100 text-red-600 px-3 py-1 rounded-full uppercase tracking-widest" x-text="request.priority"></span>
            </div>
            
            <div class="bg-slate-50 p-4 rounded-lg mb-6 border border-slate-100">
                <p class="text-sm text-slate-700 font-medium italic" x-text="'&quot;' + request.message + '&quot;'"></p>
            </div>

            <button @click="completeRequest(index)" 
                    class="w-full py-3 bg-red-600 text-white font-black text-xs uppercase rounded-lg hover:bg-red-700 transition active:scale-95 shadow-sm flex items-center justify-center gap-2">
                <i class="fas fa-check-circle"></i>
                Mark as Attended
            </button>
        </div>
    </template>

    <div x-show="requests.length === 0" 
         class="col-span-full p-20 text-center border border-slate-200 rounded-xl bg-white shadow-sm">
        <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-smile text-4xl"></i>
        </div>
        <h3 class="text-xl font-black text-slate-700 uppercase tracking-tight">No Pending Requests</h3>
        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-[0.3em] mt-2">All customers are satisfied. Keep it up!</p>
    </div>
</div>