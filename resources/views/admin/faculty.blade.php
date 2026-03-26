<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UB SYNC | Student Performance Console</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;600;700&display=swap');
        body { font-family: 'Public Sans', sans-serif; background-color: #f2f3f3; }
        .aws-header { background-color: #800000; height: 50px; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; color: white; position: fixed; top: 0; width: 100%; z-index: 1000; }
        .gold-accent { background-color: #D4AF37; height: 4px; position: fixed; top: 50px; width: 100%; z-index: 999; }
        .aws-sidebar { width: 240px; background: white; border-right: 1px solid #eaeded; height: calc(100vh - 54px); position: fixed; top: 54px; }
        .main-content { margin-left: 240px; margin-top: 54px; padding: 20px 40px; }
        .aws-card { background: white; border: 1px solid #eaeded; padding: 16px; border-radius: 2px; }
        .status-pill { font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 2px; text-transform: uppercase; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased text-slate-700" x-data="consoleApp()">

    <header class="aws-header">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-layer-group text-yellow-500"></i>
                <span class="font-bold tracking-tight text-md uppercase">UB SYNC</span>
            </div>
            <div class="h-5 w-[1px] bg-white/20"></div>
            <span class="text-xs font-semibold tracking-wide text-white/90 uppercase" x-text="currentTabName"></span>
        </div>

        <div class="flex items-center gap-5 text-[12px]">
            <div class="flex items-center gap-2 border-l border-white/20 pl-4 h-full">
                <div class="text-right">
                    <span class="text-[9px] text-white/60 block leading-none uppercase tracking-widest">Instructor Account</span>
                    <p class="font-bold text-white uppercase tracking-tight">{{ Auth::user()->name }}</p>
                </div>
                <i class="fas fa-user-circle text-lg ml-1 text-white/80"></i>
            </div>

            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="bg-black/20 hover:bg-black/40 px-3 py-1.5 rounded-sm font-bold text-[10px] transition uppercase border border-white/10">
               Sign Out
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </header>
    <div class="gold-accent"></div>

    <aside class="aws-sidebar hidden lg:block">
        <div class="p-4 space-y-6 mt-4">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-2">Monitoring</p>
                <nav class="space-y-1">
                    <button @click="currentTab = 'dashboard'" :class="currentTab === 'dashboard' ? 'bg-slate-100 border-l-4 border-l-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-l-transparent'" class="w-full flex items-center gap-3 text-sm p-2 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-th-large w-4"></i> Dashboard
                    </button>
                    <button @click="currentTab = 'sessions'" :class="currentTab === 'sessions' ? 'bg-slate-100 border-l-4 border-l-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-l-transparent'" class="w-full flex items-center gap-3 text-sm p-2 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-signal w-4"></i> Live Sessions
                    </button>
                </nav>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-2">Settings</p>
                <nav class="space-y-1">
                    <button @click="currentTab = 'management'" :class="currentTab === 'management' ? 'bg-slate-100 border-l-4 border-l-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-l-transparent'" class="w-full flex items-center gap-3 text-sm p-2 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-users-cog w-4"></i> Class Management
                    </button>
                    <button @click="currentTab = 'audit'" :class="currentTab === 'audit' ? 'bg-slate-100 border-l-4 border-l-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-l-transparent'" class="w-full flex items-center gap-3 text-sm p-2 rounded-sm transition-all text-left font-semibold">
                        <i class="fas fa-history w-4"></i> Audit Logs
                    </button>
                </nav>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <div x-show="currentTab === 'dashboard'">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 uppercase tracking-tight">Performance Console</h1>
                    <nav class="flex text-[11px] text-slate-500 mt-1 uppercase font-semibold">
                        <span>University of Batangas</span> <span class="mx-2">/</span>
                        <span class="text-slate-800 font-bold">Student Monitoring</span>
                    </nav>
                </div>
                <button @click="refreshData()" class="bg-white border border-slate-300 px-4 py-2 text-xs font-bold hover:bg-slate-50 transition shadow-sm rounded-sm">
                    <i class="fas fa-sync-alt mr-2 text-slate-400" :class="isRefreshing ? 'animate-spin' : ''"></i> Refresh Data
                </button>
            </div>

            <div class="grid grid-cols-4 gap-5 mb-8 text-center lg:text-left">
                <div class="aws-card shadow-sm border-t-4 border-t-red-800">
                    <p class="text-[10px] font-bold text-slate-500 uppercase">System Status</p>
                    <p class="text-lg font-bold text-green-600 mt-1 uppercase"><i class="fas fa-check-circle mr-1"></i> Operational</p>
                </div>
                <div class="aws-card shadow-sm">
                    <p class="text-[10px] font-bold text-slate-500 uppercase">Enrolled Students</p>
                    <p class="text-2xl font-bold mt-1">24</p>
                </div>
                <div class="aws-card shadow-sm">
                    <p class="text-[10px] font-bold text-slate-500 uppercase">Average Grade</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">88.5%</p>
                </div>
                <div class="aws-card shadow-sm border-l-4 border-l-blue-400">
                    <p class="text-[10px] font-bold text-slate-500 uppercase">Current Term</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1 uppercase">S1-2024</p>
                </div>
            </div>

            <div class="aws-card shadow-sm no-padding overflow-hidden">
                <div class="flex justify-between items-center mb-4 pb-4 border-b">
                    <h3 class="font-bold text-sm uppercase">Student Performance</h3>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-2.5 text-slate-400 text-[10px]"></i>
                        <input type="text" x-model="searchQuery" placeholder="Search Student..." class="text-xs border border-slate-300 px-8 py-2 w-64 rounded-sm outline-none focus:border-red-800 transition">
                    </div>
                </div>

                <table class="w-full text-left text-[12px]">
                    <thead>
                        <tr class="text-slate-500 font-bold uppercase text-[10px] border-b bg-slate-50">
                            <th class="p-4">Student ID</th>
                            <th class="p-4">Name</th>
                            <th class="p-4">Score</th>
                            <th class="p-4 text-center">History</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        <template x-for="student in filteredStudents" :key="student.id">
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 font-mono text-blue-600 font-bold" x-text="student.id"></td>
                                <td class="p-4 font-bold text-slate-800 uppercase" x-text="student.name"></td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-32 bg-slate-100 h-2 rounded-sm overflow-hidden border">
                                            <div class="bg-red-800 h-full transition-all" :style="'width: ' + student.totalScore + '%'"></div>
                                        </div>
                                        <span class="font-black text-slate-700" x-text="student.totalScore + '%'"></span>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <button @click="viewDetails(student)" class="bg-slate-800 text-white px-4 py-1.5 text-[10px] font-bold rounded-sm uppercase hover:bg-slate-700 transition">View</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="currentTab !== 'dashboard'" x-cloak class="p-20 border-2 border-dashed border-slate-300 text-center rounded-sm">
            <h2 class="text-slate-400 font-bold uppercase tracking-widest" x-text="currentTabName"></h2>
        </div>
    </main>

    <div x-show="showModal" class="fixed inset-0 z-[2000] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white w-full max-w-2xl rounded-sm shadow-2xl overflow-hidden">
            <div class="bg-[#800000] text-white p-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <i class="fas fa-chart-line text-yellow-500"></i>
                    <h4 class="font-bold text-xs uppercase tracking-widest">Student Workflow Analysis: <span class="text-yellow-400" x-text="selectedStudent?.id"></span></h4>
                </div>
            </div>
            
            <div class="p-6">
                <div class="flex items-center justify-between mb-6 border-b pb-4">
                    <h2 class="text-xl font-black text-slate-800 uppercase" x-text="selectedStudent?.name"></h2>
                    <p class="text-3xl font-black text-red-800" x-text="selectedStudent?.totalScore + '%'"></p>
                </div>
                <div class="space-y-3">
                    <template x-for="task in selectedStudent?.workflows">
                        <div class="flex items-center justify-between p-3 bg-slate-50 border rounded-sm">
                            <span class="text-xs font-bold text-slate-700 uppercase" x-text="task.name"></span>
                            <span class="text-xs font-black text-slate-900" x-text="task.score + ' / 100'"></span>
                        </div>
                    </template>
                </div>
            </div>

            <div class="bg-slate-100 p-4 flex justify-end">
                <button @click="showModal = false" class="px-6 py-2 text-[10px] font-black bg-slate-800 text-white uppercase tracking-widest hover:bg-slate-700 transition">Close</button>
            </div>
        </div>
    </div>

    <script>
        function consoleApp() {
            return {
                currentTab: 'dashboard',
                searchQuery: '',
                isRefreshing: false,
                showModal: false,
                selectedStudent: null,
                
                get currentTabName() {
                    const names = {
                        'dashboard': 'Dashboard Monitor',
                        'sessions': 'Live Sessions',
                        'management': 'Class Management',
                        'audit': 'Audit History'
                    };
                    return names[this.currentTab];
                },

                students: [
                    { id: 'ID-2024-001', name: 'JUAN DELA CRUZ', totalScore: 92, workflows: [{ name: 'Authentication', score: 100 }, { name: 'Transaction', score: 85 }, { name: 'Inventory', score: 92 }] },
                    { id: 'ID-2024-002', name: 'MARIA SANTOS', totalScore: 78, workflows: [{ name: 'Authentication', score: 90 }, { name: 'Transaction', score: 65 }, { name: 'Inventory', score: 75 }] },
                    { id: 'ID-2024-003', name: 'ARNEL BAUTISTA', totalScore: 98, workflows: [{ name: 'Authentication', score: 100 }, { name: 'Transaction', score: 98 }, { name: 'Inventory', score: 100 }] },
                    { id: 'ID-2024-004', name: 'ELENA MERCADO', totalScore: 65, workflows: [{ name: 'Authentication', score: 80 }, { name: 'Transaction', score: 50 }, { name: 'Inventory', score: 60 }] },
                    { id: 'ID-2024-005', name: 'RICARDO REYES', totalScore: 88, workflows: [{ name: 'Authentication', score: 95 }, { name: 'Transaction', score: 82 }, { name: 'Inventory', score: 85 }] }
                ],

                get filteredStudents() {
                    return this.students.filter(s => s.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || s.id.includes(this.searchQuery));
                },

                viewDetails(student) {
                    this.selectedStudent = student;
                    this.showModal = true;
                },

                refreshData() {
                    this.isRefreshing = true;
                    setTimeout(() => { this.isRefreshing = false; }, 800);
                }
            }
        }
    </script>
</body>
</html>