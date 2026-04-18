<div x-data="{ 
    chefPerformance: {
        fulfilledCount: 145,
        satisfaction: 4.9,
        score: 96,
        ticketTime: 92,
        foodQuality: 98,
        recipeAccuracy: 95
    },
    recentActivity: [
        { id: 1, action: 'Prepared Order #0045', time: '10:30 AM', status: 'Done' },
        { id: 2, action: 'Completed Table 4 Tickets', time: '10:15 AM', status: 'Done' },
        { id: 3, action: 'Prep Station Ready', time: '8:00 AM', status: 'Active' }
    ]
}">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Kitchen Performance</h1>
        <p class="text-sm text-slate-500 mt-1">Prep efficiency & chef workflow metrics</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="aws-card border-t-4 border-t-purple-500 p-5 shadow-sm bg-white rounded-lg">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Completed Orders</p>
            <h3 class="text-3xl font-black text-slate-800 mt-1" x-text="chefPerformance.fulfilledCount"></h3>
            <p class="text-xs text-slate-500 mt-2">orders finished today</p>
        </div>
        <div class="aws-card p-5 border-t-4 border-t-pink-500 shadow-sm bg-white rounded-lg">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Satisfaction Rate</p>
            <h3 class="text-3xl font-black text-slate-800 mt-1" x-text="chefPerformance.satisfaction + '/5.0'"></h3>
            <p class="text-xs text-slate-500 mt-2">average kitchen rating</p>
        </div>
        <div class="aws-card p-5 border-t-4 border-t-blue-500 shadow-sm bg-white rounded-lg">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Performance Score</p>
            <h3 class="text-3xl font-black text-slate-800 mt-1" x-text="chefPerformance.score + '%'"></h3>
            <p class="text-xs text-slate-500 mt-2">efficiency score</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="aws-card p-6 shadow-sm bg-white rounded-lg">
            <h2 class="text-lg font-black text-slate-800 mb-6"><i class="fas fa-star text-yellow-500 mr-2"></i>Kitchen Skills</h2>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-slate-700">Ticket Time</span>
                        <span class="text-sm font-black text-slate-600" x-text="chefPerformance.ticketTime + '%'"></span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full transition-all" :style="'width: ' + chefPerformance.ticketTime + '%'"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-slate-700">Food Quality</span>
                        <span class="text-sm font-black text-slate-600" x-text="chefPerformance.foodQuality + '%'"></span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-pink-500 h-2 rounded-full transition-all" :style="'width: ' + chefPerformance.foodQuality + '%'"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-slate-700">Recipe Accuracy</span>
                        <span class="text-sm font-black text-slate-600" x-text="chefPerformance.recipeAccuracy + '%'"></span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full transition-all" :style="'width: ' + chefPerformance.recipeAccuracy + '%'"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="aws-card p-6 shadow-sm bg-white rounded-lg">
            <h2 class="text-lg font-black text-slate-800 mb-6"><i class="fas fa-tasks text-emerald-600 mr-2"></i>Recent Activities</h2>
            <div class="space-y-3 max-h-[300px] overflow-y-auto">
                <template x-for="activity in recentActivity" :key="activity.id">
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-bold text-slate-800" x-text="activity.action"></p>
                                <p class="text-xs text-slate-500 mt-1" x-text="activity.time"></p>
                            </div>
                            <span class="text-xs font-bold px-2 py-1 rounded bg-emerald-100 text-emerald-700" x-text="activity.status"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>