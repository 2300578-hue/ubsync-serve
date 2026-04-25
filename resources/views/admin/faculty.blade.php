<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UB SYNC | Student Performance Console</title>
     <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f2f3f3; color: #334155; overflow-x: hidden; }
        
        /* Consistent UB Branding Header */
        .aws-header { background-color: #800000; height: 65px; display: flex; align-items: center; justify-content: space-between; padding: 0 25px; color: white; position: fixed; top: 0; width: 100%; z-index: 1000; }
        .gold-accent { background-color: #D4AF37; height: 4px; position: fixed; top: 65px; width: 100%; z-index: 999; }
        
        /* Sidebar Navigation */
        .aws-sidebar { width: 260px; background: white; border-right: 1px solid #eaeded; height: calc(100vh - 69px); position: fixed; top: 69px; left: 0; transition: all 0.3s ease; z-index: 1000; }
        .sidebar-collapsed { left: -260px; }
        
        /* Main Content Area */
        .main-content { margin-left: 260px; margin-top: 69px; padding: 25px; transition: all 0.3s ease; min-height: calc(100vh - 69px); }
        .content-wide { margin-left: 0; width: 100%; }

        /* --- MOBILE RESPONSIVENESS --- */
        @media (max-width: 768px) {
            .main-content { margin-left: 0 !important; padding: 15px; width: 100%; }
            .aws-sidebar { box-shadow: 10px 0 15px rgba(0,0,0,0.1); z-index: 1001; }
        }

         .aws-card { background: white; border: 1px solid #eaeded; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }


        .maroon-gradient { background: linear-gradient(135deg, #800000 0%, #a52a2a 100%); }
        .status-badge { font-size: 0.65rem; padding: 2px 8px; border-radius: 20px; font-weight: 800; text-transform: uppercase; }
        [x-cloak] { display: none !important; }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #800000; border-radius: 10px; }

       
    </style>
</head>
<body class="antialiased" x-data="consoleApp()">

    <header class="aws-header shadow-lg">
        <div class="flex items-center gap-4">
            <button @click="isSidebarOpen = !isSidebarOpen" class="hover:bg-white/20 p-2 rounded transition cursor-pointer">
                <i class="fas fa-bars"></i>
            </button>
            <div class="flex items-center gap-2">
               
              
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" class="flex items-center gap-3 border-l border-white/20 pl-6 h-full text-right hover:bg-white/5 p-2 rounded transition-all cursor-pointer focus:outline-none">
                    <div class="hidden md:block text-right">
                        <span class="text-[10px] text-white/60 block leading-none uppercase tracking-widest font-bold">Account</span>
                        <p class="font-bold text-white uppercase text-sm tracking-tight">
                            {{ Auth::user()->name ?? 'Guest User' }}
                        </p>
                    </div>
                    
                    <div class="relative">
                        <i class="fas fa-user-circle text-2xl text-white/80 transition-transform" :class="open ? 'scale-110' : ''"></i>
                        <div class="absolute -bottom-0.5 -right-0.5 bg-emerald-500 w-2.5 h-2.5 rounded-full border-2 border-[#800000]"></div>
                    </div>

                    <i class="fa-solid fa-chevron-down text-[9px] text-white/40 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>

             <div x-show="open" x-cloak class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl py-2 z-[1100] border border-slate-200 overflow-hidden">
                    <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 mb-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5">Signed in as</p>
                        <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name ?? 'Guest User' }}</p>
                    </div>
                    <div class="px-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2.5 text-[11px] font-black text-red-600 hover:bg-red-50 rounded-lg uppercase tracking-widest flex items-center gap-3 transition-all group">
                                <i class="fa-solid fa-power-off text-sm"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="gold-accent"></div>


   <div x-show="isSidebarOpen && window.innerWidth < 768" 
     @click="isSidebarOpen = false" 
     x-transition.opacity 
     class="fixed inset-0 bg-black/60 z-[999] md:hidden" style="top: 55px;">
</div>

<aside class="aws-sidebar shadow-sm" :class="!isSidebarOpen ? 'sidebar-collapsed' : ''">
    <div class="p-6 space-y-8">
        <div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 px-2">Monitoring</p>
            <nav class="space-y-1">
                <button @click="currentTab = 'dashboard'; if(window.innerWidth < 768) isSidebarOpen = false;" 
                    :class="currentTab === 'dashboard' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" 
                    class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                    <i class="fas fa-th-large w-5"></i> Dashboard
                </button>
                
                <button @click="currentTab = 'sessions'; if(window.innerWidth < 768) isSidebarOpen = false;" 
                    :class="currentTab === 'sessions' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" 
                    class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                    <i class="fas fa-signal w-5 text-green-600"></i> Live Sessions
                </button>
            </nav>
        </div>

        <div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 px-2">Settings</p>
            <nav class="space-y-1">
                <button @click="currentTab = 'management'; if(window.innerWidth < 768) isSidebarOpen = false;" 
                    :class="currentTab === 'management' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" 
                    class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                    <i class="fas fa-users-cog w-5"></i> Class Management
                </button>
                
                <button @click="currentTab = 'audit'; if(window.innerWidth < 768) isSidebarOpen = false;" 
                    :class="currentTab === 'audit' ? 'bg-red-50 border-l-4 border-red-800 text-red-900 font-bold' : 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent'" 
                    class="w-full flex items-center gap-4 p-3 rounded-sm transition-all text-left font-semibold">
                    <i class="fas fa-history w-5"></i> Audit Logs
                </button>
            </nav>
        </div>
    </div>
</aside>


    <main class="main-content" :class="!isSidebarOpen ? 'content-wide' : ''">
        <div x-show="currentTab === 'dashboard'" x-cloak>
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-800 uppercase tracking-tighter">Performance Console</h1>
                    <nav class="flex text-[10px] sm:text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide flex-wrap">
                        <span>University of Batangas</span> <span class="mx-2 sm:mx-3 text-slate-300">|</span>
                        <span class="text-red-800 font-bold">Student Monitoring</span>
                    </nav>
                </div>
                <button @click="refreshData()" class="bg-white border border-slate-300 px-4 sm:px-6 py-2.5 text-xs sm:text-sm font-bold hover:bg-slate-50 transition shadow-sm rounded flex items-center justify-center gap-2 w-full sm:w-auto">
                    <i class="fas fa-sync-alt text-slate-400" :class="isRefreshing ? 'animate-spin' : ''"></i> Refresh Data
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 w-full">
                <div class="aws-card border-t-4 border-t-red-800 p-5">
                    <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest">System Status</p>
                    <p class="text-lg sm:text-xl font-bold text-green-600 mt-2 uppercase flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> Operational
                    </p>
                </div>
                <div class="aws-card p-5">
                    <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest">Enrolled Students</p>
                    <p class="text-2xl sm:text-3xl font-black text-slate-800 mt-1" x-text="students.length"></p>
                </div>
                <div class="aws-card p-5">
                    <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest">Average Grade</p>
                    <p class="text-2xl sm:text-3xl font-black text-slate-800 mt-1">88.5%</p>
                </div>
                <div class="aws-card border-l-4 border-l-blue-500 p-5">
                    <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest">Active Tables</p>
                    <p class="text-2xl sm:text-3xl font-black text-emerald-600 mt-1">8</p>
                </div>
            </div>

            <div class="aws-card no-padding overflow-x-auto custom-scroll border-slate-200 w-full">
                <div class="p-5 border-b flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50 min-w-[600px]">
                    <h3 class="font-black text-sm uppercase tracking-wider text-slate-600">Student Performance</h3>
                    <div class="relative w-full sm:w-auto">
                        <i class="fas fa-search absolute left-3 top-3.5 text-slate-400 text-sm"></i>
                        <input type="text" x-model="searchQuery" placeholder="Search student..." class="text-sm border border-slate-300 pl-10 pr-4 py-2.5 w-full sm:w-80 md:w-96 rounded outline-none focus:border-red-800 transition">
                    </div>
                </div>
                <table class="w-full text-left min-w-[600px]">
                    <thead>
                        <tr class="text-slate-500 font-bold uppercase text-xs border-b bg-white">
                            <th class="p-4 sm:p-5">Student ID</th> 
                            <th class="p-4 sm:p-5">Name</th>
                            <th class="p-4 sm:p-5">Score</th>
                            <th class="p-4 sm:p-5 text-center">History</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <template x-for="student in filteredStudents" :key="student.id">
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 sm:p-5 font-bold text-blue-600 font-mono text-xs sm:text-base" x-text="student.id"></td>
                                <td class="p-4 sm:p-5 font-black text-slate-800 text-xs sm:text-base uppercase" x-text="student.name"></td>
                                <td class="p-4 sm:p-5">
                                    <div class="flex items-center gap-2 sm:gap-4">
                                        <div class="w-24 sm:w-full sm:max-w-xs bg-slate-100 h-2.5 rounded-full overflow-hidden border">
                                            <div class="bg-red-800 h-full transition-all duration-700" :style="'width: ' + student.totalScore + '%'"></div>
                                        </div>
                                        <span class="font-black text-slate-700 text-xs sm:text-base" x-text="student.totalScore + '%'"></span>
                                    </div>
                                </td>
                                <td class="p-4 sm:p-5 text-center">
                                    <button @click="viewDetails(student)" class="bg-slate-800 text-white px-3 sm:px-5 py-1.5 sm:py-2 text-[10px] sm:text-xs font-black rounded-sm uppercase hover:bg-slate-700 transition tracking-widest">View</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="currentTab === 'sessions'" x-cloak>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-800 uppercase tracking-tighter mb-8">Live Sessions Monitor</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <template x-for="session in liveSessions" :key="session.id">
                   <div class="aws-card border-l-8 p-5 sm:p-6" :class="session.active ? 'border-l-green-500' : 'border-l-slate-300'">
                       <div class="flex justify-between items-start mb-6 gap-2">
                           <div>
                               <h3 class="text-xl sm:text-2xl font-black text-slate-800 uppercase mt-1 break-words" x-text="session.student"></h3>
                           </div>
                           <span :class="session.active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500'" class="text-[10px] sm:text-xs font-black px-2 sm:px-4 py-1 sm:py-1.5 uppercase rounded border flex-shrink-0" x-text="session.active ? 'Online' : 'Idle'"></span>
                       </div>
                       <div class="mt-8">
                           <div class="flex justify-between text-[10px] sm:text-sm font-black uppercase mb-2">
                               <span>Progress</span>
                               <span class="text-blue-600" x-text="session.progress + '%'"></span>
                           </div>
                           <div class="w-full bg-slate-100 h-2 sm:h-3 rounded-full overflow-hidden border shadow-inner">
                               <div class="bg-blue-600 h-full transition-all duration-1000" :style="'width: ' + session.progress + '%'"></div>
                           </div>
                       </div>
                   </div>
               </template>
            </div>
        </div>

        <div x-show="currentTab === 'management'" x-cloak>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <h2 class="text-2xl sm:text-3xl font-black text-slate-800 uppercase tracking-tighter">Class Management</h2>
                <button class="bg-red-800 text-white px-6 sm:px-8 py-2.5 sm:py-3 text-xs sm:text-sm font-black uppercase rounded shadow-md hover:bg-red-900 transition flex items-center justify-center gap-2 w-full sm:w-auto">
                    <i class="fas fa-plus"></i> Register Student
                </button>
            </div>
            <div class="aws-card no-padding overflow-x-auto custom-scroll">
                <table class="w-full text-left min-w-[600px]">
                    <thead>
                        <tr class="text-slate-500 font-bold uppercase text-xs border-b bg-slate-50">
                            <th class="p-4 sm:p-5">Student ID</th>
                            <th class="p-4 sm:p-5">Full Name</th>
                            <th class="p-4 sm:p-5">Section</th>
                            <th class="p-4 sm:p-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <template x-for="student in students" :key="student.id">
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 sm:p-5 font-bold font-mono text-slate-600 text-sm" x-text="student.id"></td>
                                <td class="p-4 sm:p-5 font-black text-slate-800 uppercase text-sm" x-text="student.name"></td>
                                <td class="p-4 sm:p-5 text-xs sm:text-sm font-bold text-slate-500">BSCS-4A</td>
                                <td class="p-4 sm:p-5 text-right">
                                    <button class="text-blue-600 hover:bg-blue-50 px-2 sm:px-3 py-1.5 rounded transition"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:bg-red-50 px-2 sm:px-3 py-1.5 rounded transition ml-1 sm:ml-2"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="currentTab === 'audit'" x-cloak>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-800 uppercase tracking-tighter mb-8">Audit Logs</h2>
            <div class="aws-card no-padding overflow-x-auto custom-scroll">
                <table class="w-full text-left text-sm min-w-[600px]">
                    <thead class="bg-slate-50 border-b">
                        <tr class="text-slate-500 font-bold uppercase text-xs">
                            <th class="p-4 sm:p-5">Action</th>
                            <th class="p-4 sm:p-5">User</th>
                            <th class="p-4 sm:p-5">Category</th>
                            <th class="p-4 sm:p-5">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <template x-for="log in auditLogs" :key="log.action + log.timestamp">
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 sm:p-5 font-bold text-red-900 font-mono text-[10px] sm:text-xs break-all" x-text="log.action"></td>
                                <td class="p-4 sm:p-5 font-black uppercase text-slate-700 text-xs" x-text="log.user"></td>
                                <td class="p-4 sm:p-5">
                                    <span :class="log.type === 'auth' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700'" class="px-2 py-1 rounded-full text-[9px] sm:text-[10px] font-black uppercase" x-text="log.type"></span>
                                </td>
                                <td class="p-4 sm:p-5 text-slate-400 font-mono text-[10px] sm:text-xs" x-text="log.timestamp"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div x-show="showModal" class="fixed inset-0 z-[2000] flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-md" x-cloak x-transition>
        <div class="bg-white w-full max-w-4xl rounded shadow-2xl overflow-hidden border-t-8 border-t-red-800 max-h-[90vh] flex flex-col">
            <div class="p-6 sm:p-8 overflow-y-auto">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b pb-4 sm:pb-6 mb-4 sm:mb-6 gap-4">
                    <div>
                        <span class="text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-[0.2em]" x-text="'Record: ' + selectedStudent?.id"></span>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-800 uppercase mt-1 break-words" x-text="selectedStudent?.name"></h2>
                    </div>
                    <div class="text-left sm:text-right w-full sm:w-auto">
                        <p class="text-4xl sm:text-5xl font-black text-red-800" x-text="selectedStudent?.totalScore + '%'"></p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="task in selectedStudent?.workflows" :key="task.name">
                        <div class="flex items-center justify-between p-4 sm:p-5 bg-slate-50 border border-slate-200 rounded gap-2">
                            <span class="text-xs sm:text-sm font-black text-slate-600 uppercase" x-text="task.name"></span>
                            <span class="text-sm sm:text-base font-black text-slate-900 bg-white px-3 sm:px-5 py-1.5 sm:py-2 border rounded shadow-sm whitespace-nowrap" x-text="task.score + ' / 100'"></span>
                        </div>
                    </template>
                </div>
            </div>
            <div class="bg-slate-50 p-4 sm:p-6 flex justify-end mt-auto">
                <button @click="showModal = false" class="w-full sm:w-auto px-6 sm:px-10 py-2.5 sm:py-3 text-[10px] sm:text-xs font-black bg-slate-800 text-white uppercase tracking-[0.2em] rounded hover:bg-red-800 transition">Close View</button>
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
                isSidebarOpen: window.innerWidth >= 768,
                selectedStudent: null,
                liveSessions: [
                    { id: 'NODE-01', student: 'JUAN DELA CRUZ', progress: 85, active: true },
                    { id: 'NODE-02', student: 'MARIA SANTOS', progress: 45, active: true },
                    { id: 'NODE-03', student: 'STANDBY', progress: 0, active: false },
                    { id: 'NODE-04', student: 'ARNEL BAUTISTA', progress: 12, active: true }
                ],
                auditLogs: [
                    { action: 'SEC_USER_LOGIN', user: 'SYSTEM_ADMIN', type: 'auth', timestamp: '2026-03-27 10:15:00' },
                    { action: 'DATA_ENTRY_UPDATE', user: 'MARIA SANTOS', type: 'db', timestamp: '2026-03-27 10:20:00' },
                    { action: 'ADMIN_SESS_REVOKE', user: 'SYSTEM_ADMIN', type: 'auth', timestamp: '2026-03-27 10:25:00' },
                    { action: 'TRX_LOG_WRITE', user: 'ARNEL BAUTISTA', type: 'db', timestamp: '2026-03-27 10:30:00' }
                ],
                students: [
                    { id: '2024-1001-UB', name: 'JUAN DELA CRUZ', totalScore: 92, workflows: [{ name: 'Authentication', score: 100 }, { name: 'Transaction', score: 85 }, { name: 'Inventory', score: 92 }] },
                    { id: '2024-1002-UB', name: 'MARIA SANTOS', totalScore: 78, workflows: [{ name: 'Authentication', score: 90 }, { name: 'Transaction', score: 65 }, { name: 'Inventory', score: 75 }] },
                    { id: '2024-1003-UB', name: 'ARNEL BAUTISTA', totalScore: 98, workflows: [{ name: 'Authentication', score: 100 }, { name: 'Transaction', score: 98 }, { name: 'Inventory', score: 100 }] },
                    { id: '2024-1004-UB', name: 'ELENA MERCADO', totalScore: 65, workflows: [{ name: 'Authentication', score: 80 }, { name: 'Transaction', score: 50 }, { name: 'Inventory', score: 60 }] },
                    { id: '2024-1005-UB', name: 'RICARDO REYES', totalScore: 88, workflows: [{ name: 'Authentication', score: 95 }, { name: 'Transaction', score: 82 }, { name: 'Inventory', score: 85 }] }
                ],
                get filteredStudents() {
                    return this.students.filter(s => 
                        s.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                        s.id.includes(this.searchQuery)
                    );
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