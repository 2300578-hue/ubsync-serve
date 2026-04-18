<div class="flex justify-between items-end mb-8">
    <div>
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Customer Requests</h1>
        <nav class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">
            <span>Dining Area</span> <span class="mx-3 text-slate-300">|</span>
            <span class="text-red-800 font-bold">Call Queue</span>
        </nav>
    </div>
</div>

<div class="aws-card p-6 mb-6 bg-yellow-50 border-l-4 border-l-yellow-500">
    <div class="flex items-center gap-3">
        <i class="fas fa-exclamation-circle text-yellow-600 text-2xl"></i>
        <div>
            <p class="font-bold text-yellow-900">Active Requests: <span x-text="requests.length"></span></p>
            <p class="text-sm text-yellow-700">Attend to customer requests immediately for better service</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <template x-for="(request, index) in requests" :key="request.id">
        <div class="aws-card p-6 border-l-4 border-l-red-600 bg-red-50 animate-pulse">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase">Table <span x-text="request.table"></span></p>
                    <p class="text-xl font-black text-slate-800 mt-1" x-text="request.type"></p>
                </div>
                <span class="text-xs font-bold bg-red-600 text-white px-3 py-1 rounded-full" x-text="request.priority"></span>
            </div>
            <p class="text-sm text-slate-700 mb-4" x-text="request.message"></p>
            <button @click="completeRequest(index)" class="w-full py-2 bg-red-600 text-white font-bold text-xs uppercase rounded hover:bg-red-700 transition">
                <i class="fas fa-check mr-2"></i>Attended
            </button>
        </div>
    </template>

    <div x-show="requests.length === 0" class="col-span-full p-12 text-center border-2 border-dashed border-slate-300 rounded-lg bg-slate-50">
        <i class="fas fa-smile text-4xl text-slate-300 mb-3"></i>
        <h3 class="text-lg font-bold text-slate-600 uppercase">No Pending Requests</h3>
        <p class="text-slate-400 text-sm">All customers are satisfied. Keep up the good service!</p>
    </div>
</div>