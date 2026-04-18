<div class="flex justify-between items-end mb-8">
    <div>
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Billing Management</h1>
        <nav class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">
            <span>Transaction</span> <span class="mx-3 text-slate-300">|</span>
            <span class="text-red-800 font-bold">Payment Hub</span>
        </nav>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="aws-card border-t-4 border-t-green-500 p-5">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Served</p>
        <p class="text-3xl font-black text-slate-800 mt-1" x-text="billedCount"></p>
    </div>
    <div class="aws-card p-5 border-t-4 border-t-blue-500">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Revenue</p>
        <p class="text-3xl font-black text-slate-800 mt-1">₱<span x-text="(totalRevenue).toFixed(2)"></span></p>
    </div>
    <div class="aws-card p-5 border-t-4 border-t-purple-500">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Avg Bill</p>
        <p class="text-3xl font-black text-slate-800 mt-1">₱<span x-text="billedCount > 0 ? (totalRevenue / billedCount).toFixed(2) : '0.00'"></span></p>
    </div>
</div>

<div class="aws-card p-6">
    <h2 class="text-xl font-black text-slate-800 mb-4">Recent Transactions</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 border-b-2 border-slate-300">
                <tr>
                    <th class="px-4 py-3 text-left font-bold text-slate-600">Table</th>
                    <th class="px-4 py-3 text-left font-bold text-slate-600">Amount</th>
                    <th class="px-4 py-3 text-left font-bold text-slate-600">Payment</th>
                    <th class="px-4 py-3 text-left font-bold text-slate-600">Time</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="bill in recentBills" :key="bill.id">
                    <tr class="border-b hover:bg-slate-50">
                        <td class="px-4 py-3 font-bold text-slate-800">Table <span x-text="bill.table"></span></td>
                        <td class="px-4 py-3 font-bold text-green-600">₱<span x-text="bill.amount.toFixed(2)"></span></td>
                        <td class="px-4 py-3 text-slate-600" x-text="bill.method"></td>
                        <td class="px-4 py-3 text-slate-500 text-xs" x-text="bill.time"></td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>