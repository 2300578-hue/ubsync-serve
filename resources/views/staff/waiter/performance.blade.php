<div class="mb-8">
    <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">My Performance</h1>
    <p class="text-sm text-slate-500 mt-1">Customer satisfaction & service excellence metrics</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="aws-card border-t-4 border-t-purple-500 p-5">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tables Attended</p>
        <h3 class="text-3xl font-black text-slate-800 mt-1" x-text="waiterPerformance.tablesAttended"></h3>
        <p class="text-xs text-slate-500 mt-2">customers served today</p>
    </div>
    <div class="aws-card p-5 border-t-4 border-t-pink-500">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Satisfaction Rate</p>
        <h3 class="text-3xl font-black text-slate-800 mt-1" x-text="waiterPerformance.satisfaction + '/5.0'"></h3>
        <p class="text-xs text-slate-500 mt-2">average feedback</p>
    </div>
    <div class="aws-card p-5 border-t-4 border-t-blue-500">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Performance Score</p>
        <h3 class="text-3xl font-black text-slate-800 mt-1" x-text="waiterPerformance.score + '%'"></h3>
        <p class="text-xs text-slate-500 mt-2">overall rating</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="aws-card p-6">
        <h2 class="text-lg font-black text-slate-800 mb-6"><i class="fas fa-star text-yellow-500 mr-2"></i>Service Skills</h2>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-bold text-slate-700">Response Time</span>
                    <span class="text-sm font-black text-slate-600" x-text="waiterPerformance.responseTime + '%'"></span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2"><div class="bg-purple-500 h-2 rounded-full transition-all" :style="'width: ' + waiterPerformance.responseTime + '%'"></div></div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-bold text-slate-700">Hospitality</span>
                    <span class="text-sm font-black text-slate-600" x-text="waiterPerformance.hospitality + '%'"></span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2"><div class="bg-pink-500 h-2 rounded-full transition-all" :style="'width: ' + waiterPerformance.hospitality + '%'"></div></div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-bold text-slate-700">Product Knowledge</span>
                    <span class="text-sm font-black text-slate-600" x-text="waiterPerformance.knowledge + '%'"></span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2"><div class="bg-blue-500 h-2 rounded-full transition-all" :style="'width: ' + waiterPerformance.knowledge + '%'"></div></div>
            </div>
        </div>
    </div>

    <div class="aws-card p-6">
        <h2 class="text-lg font-black text-slate-800 mb-6"><i class="fas fa-tasks text-emerald-600 mr-2"></i>Recent Activities</h2>
        <div class="space-y-3 max-h-[300px] overflow-y-auto">
            <template x-for="activity in recentActivities" :key="activity.id">
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