<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UB-SYNCSERVE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .ub-maroon { background-color: #800000; }
        .ub-text-maroon { color: #800000; }
        .nav-link:hover { color: #800000 !important; }
        
        body {
            background: linear-gradient(-45deg, #ffffff, #fcf8f8, #fffafa, #ffffff);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(128, 0, 0, 0.08);
        }

        .hero-title {
            color: #800000;
            padding-bottom: 0.2em; 
            line-height: 1.2;
        }

        /* Slideshow Styling */
        .slideshow-container {
            position: relative;
            background: transparent;
        }
        .slideshow-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            opacity: 0;
            transition: opacity 1.2s ease-in-out;
        }
        .slideshow-container img.active {
            opacity: 1;
            position: relative;
        }

        /* Scroll Reveal Animation Classes */
        .reveal-hidden {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .reveal-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .delay-1 { transition-delay: 0.2s; }
        .delay-2 { transition-delay: 0.4s; }
        .delay-3 { transition-delay: 0.6s; }
    </style>
</head>
<body class="antialiased overflow-x-hidden text-slate-900">

    <nav class="fixed top-0 left-0 w-full z-50 glass-nav px-4 md:px-6 py-4 flex justify-between items-center shadow-md">
        <div class="flex items-center gap-2 group cursor-pointer">
            <div class="w-8 h-8 md:w-10 md:h-10 ub-maroon rounded-xl flex items-center justify-center">
                <span class="text-white font-black text-lg md:text-xl">UB</span>
            </div>
            <span class="text-xl md:text-2xl font-black tracking-tighter ub-text-maroon">SYNCSERVE</span>
        </div>
        
        <div class="hidden md:flex gap-6 lg:gap-10 text-[11px] lg:text-[12px] font-black uppercase tracking-[0.2em] text-gray-500">
            <a href="#" class="nav-link transition-colors">Home</a>
            <a href="#features" class="nav-link transition-colors">Modules</a>
            <a href="#workflow" class="nav-link transition-colors">Workflow</a>
            <a href="#objectives" class="nav-link transition-colors">Objectives</a>
        </div>

        <div class="flex items-center gap-2 md:gap-4">
            <a href="{{ route('login') }}" class="text-gray-600 hover:ub-text-maroon font-bold text-xs px-2 transition-all">
                Login
            </a>
            <a href="{{ route('register') }}" class="ub-maroon text-white px-5 py-2 md:px-8 md:py-2.5 rounded-full font-bold text-xs shadow-xl hover:shadow-red-900/20 transition-all inline-block active:scale-95">
                Sign Up
            </a>
        </div>
    </nav>


    <section class="relative min-h-screen flex items-center justify-center pt-24 md:pt-28 px-4 md:px-6 overflow-hidden text-center">
        <div class="max-w-5xl relative z-10">
            <h1 class="hero-title text-4xl sm:text-5xl md:text-7xl font-black tracking-tighter mb-6 md:mb-8 leading-[1.1] reveal-hidden delay-1">
                Elevating Business and <br class="hidden sm:block"> Hospitality Excellence
            </h1>
            
            <p class="text-slate-600 text-base md:text-2xl max-w-3xl mx-auto leading-relaxed mb-10 md:mb-12 reveal-hidden delay-2 px-2">
                Provides hands-on training to improve learning and build practical skills in real-world kitchen and dining operations
            </p>

            <div class="flex justify-center reveal-hidden delay-3">
    <a href="{{ route('register') }}" class="ub-maroon text-white px-8 py-4 md:px-12 md:py-5 rounded-2xl font-bold text-base md:text-lg shadow-[0_20px_50px_rgba(128,0,0,0.3)] hover:bg-red-900 hover:-translate-y-1 transition-all flex items-center gap-3">
        Get Started
    </a>
</div>
        </div>
    </section>

   
    <section id="features" class="py-16 md:py-32 bg-white px-4 md:px-6">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12 md:mb-20 reveal-hidden">
            <h2 class="text-3xl md:text-5xl font-black ub-text-maroon tracking-tight">Core Training Modules</h2>
            <p class="text-slate-400 mt-3 md:mt-4 font-medium uppercase tracking-widest text-[10px] md:text-xs">Essential Tools for Hospitality Management</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            
            <div class="p-8 md:p-10 rounded-[2rem] bg-slate-50 border border-slate-100 reveal-hidden delay-1 hover:bg-white hover:shadow-2xl transition-all duration-500 flex flex-col h-full">
                <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center text-2xl mb-4">💳</div>
                <h3 class="text-xl md:text-2xl font-black ub-text-maroon mb-3">POS Operations</h3>
                <p class="text-slate-500 leading-relaxed text-sm md:text-base flex-grow">Learn to handle ordering, billing, and payment processing systems effectively and accurately.</p>
            </div>

            <div class="p-8 md:p-10 rounded-[2rem] bg-slate-50 border border-slate-100 reveal-hidden delay-2 hover:bg-white hover:shadow-2xl transition-all duration-500 flex flex-col h-full">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-2xl mb-4">📦</div>
                <h3 class="text-xl md:text-2xl font-black text-blue-900 mb-3">Inventory Control</h3>
                <p class="text-slate-500 leading-relaxed text-sm md:text-base flex-grow">Monitor ingredient stocks, manage wastage, and optimize kitchen supplies with automated tracking.</p>
            </div>

            <div class="p-8 md:p-10 rounded-[2rem] bg-slate-50 border border-slate-100 reveal-hidden delay-3 hover:bg-white hover:shadow-2xl transition-all duration-500 flex flex-col h-full">
                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-2xl mb-4">📊</div>
                <h3 class="text-xl md:text-2xl font-black text-green-900 mb-3">Performance Analytics</h3>
                <p class="text-slate-500 leading-relaxed text-sm md:text-base flex-grow">Check daily sales, identify top menus, and manage profits for your hospitality training.</p>
            </div>

            <div class="p-8 md:p-10 rounded-[2rem] bg-slate-50 border border-slate-100 reveal-hidden delay-1 hover:bg-white hover:shadow-2xl transition-all duration-500 flex flex-col h-full">
                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center text-2xl mb-4">📅</div>
                <h3 class="text-xl md:text-2xl font-black text-purple-900 mb-3">Reservation & Booking</h3>
                <p class="text-slate-500 leading-relaxed text-sm md:text-base flex-grow">Manage advanced table bookings and event reservations to ensure organized dining sessions.</p>
            </div>
              <div class="p-8 md:p-10 rounded-[2rem] bg-slate-50 border border-slate-100 reveal-hidden delay-3 hover:bg-white hover:shadow-2xl transition-all duration-500 flex flex-col h-full">
                <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-2xl mb-4">📜</div>
                <h3 class="text-xl md:text-2xl font-black text-orange-900 mb-3">Digital Menu</h3>
                <p class="text-slate-500 leading-relaxed text-sm md:text-base flex-grow">Organize categories, update prices in real-time, and manage menu availability for seamless ordering.</p>
            </div>

            <div class="p-8 md:p-10 rounded-[2rem] bg-slate-50 border border-slate-100 reveal-hidden delay-2 hover:bg-white hover:shadow-2xl transition-all duration-500 flex flex-col h-full">
                <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center text-2xl mb-4">🏢</div>
                <h3 class="text-xl md:text-2xl font-black text-yellow-900 mb-3">One-Stop-Shop Hub</h3>
                <p class="text-slate-500 leading-relaxed text-sm md:text-base flex-grow">A centralized platform for all hospitality services, from ordering to inventory management.</p>
            </div>

            

        </div>
    </div>
</section>


    <section id="workflow" class="py-16 md:py-32 bg-slate-50 px-4 md:px-6">
        <div class="max-w-[1600px] mx-auto text-center">
            <div class="mb-12 md:mb-20 reveal-hidden">
                <h2 class="text-3xl md:text-6xl font-black text-slate-900 tracking-tight">Project Phases</h2>
                <p class="text-red-800 text-sm md:text-lg font-bold uppercase tracking-[0.2em] md:tracking-[0.3em] mt-3 md:mt-4">Operational Simulation Lifecycle</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 md:gap-8">
                <div class="bg-white p-8 md:p-12 rounded-[2rem] md:rounded-[3rem] border border-slate-200 shadow-xl reveal-hidden delay-1 min-h-[300px] md:min-h-[450px] flex flex-col justify-center relative overflow-hidden group">
                    <div class="absolute -right-2 -top-4 text-[6rem] md:text-[10rem] font-black text-slate-50 group-hover:text-red-50 transition-colors">1</div>
                    <h4 class="font-black text-xl md:text-2xl mb-4 md:mb-6 ub-text-maroon relative z-10">PRE-OPERATION</h4>
                    <p class="text-base md:text-xl text-slate-600 leading-relaxed relative z-10">Menu & inventory setup, table QR configuration, and network synchronization.</p>
                </div>
                <div class="bg-white p-8 md:p-12 rounded-[2rem] md:rounded-[3rem] border border-slate-200 shadow-xl reveal-hidden delay-2 min-h-[300px] md:min-h-[450px] flex flex-col justify-center relative overflow-hidden group">
                    <div class="absolute -right-2 -top-4 text-[6rem] md:text-[10rem] font-black text-slate-50 group-hover:text-red-50 transition-colors">2</div>
                    <h4 class="font-black text-xl md:text-2xl mb-4 md:mb-6 ub-text-maroon relative z-10">GUEST JOURNEY</h4>
                    <p class="text-base md:text-xl text-slate-600 leading-relaxed relative z-10">QR scan identification, digital ordering, and real-time bill calculation.</p>
                </div>
                <div class="bg-white p-8 md:p-12 rounded-[2rem] md:rounded-[3rem] border border-slate-200 shadow-xl reveal-hidden delay-3 min-h-[300px] md:min-h-[450px] flex flex-col justify-center relative overflow-hidden group sm:col-span-2 lg:col-span-1">
                    <div class="absolute -right-2 -top-4 text-[6rem] md:text-[10rem] font-black text-slate-50 group-hover:text-red-50 transition-colors">3</div>
                    <h4 class="font-black text-xl md:text-2xl mb-4 md:mb-6 ub-text-maroon relative z-10">PAYMENT GATE</h4>
                    <p class="text-base md:text-xl text-slate-600 leading-relaxed relative z-10">Verified payment triggers order authorization and production.</p>
                </div>
                <div class="bg-white p-8 md:p-12 rounded-[2rem] md:rounded-[3rem] border border-slate-200 shadow-xl reveal-hidden delay-1 min-h-[300px] md:min-h-[450px] flex flex-col justify-center relative overflow-hidden group">
                    <div class="absolute -right-2 -top-4 text-[6rem] md:text-[10rem] font-black text-slate-50 group-hover:text-red-50 transition-colors">4</div>
                    <h4 class="font-black text-xl md:text-2xl mb-4 md:mb-6 ub-text-maroon relative z-10">DUAL-PRINTING</h4>
                    <p class="text-base md:text-xl text-slate-600 leading-relaxed relative z-10">Synchronized Kitchen Order Slip (KOS) and Official Receipt issuance.</p>
                </div>
                <div class="bg-white p-8 md:p-12 rounded-[2rem] md:rounded-[3rem] border border-slate-200 shadow-xl reveal-hidden delay-2 min-h-[300px] md:min-h-[450px] flex flex-col justify-center relative overflow-hidden group">
                    <div class="absolute -right-2 -top-4 text-[6rem] md:text-[10rem] font-black text-slate-50 group-hover:text-red-50 transition-colors">5</div>
                    <h4 class="font-black text-xl md:text-2xl mb-4 md:mb-6 ub-text-maroon relative z-10">ANALYTICS</h4>
                    <p class="text-base md:text-xl text-slate-600 leading-relaxed relative z-10">Inventory auto-deduction and faculty-led performance monitoring.</p>
                </div>

                
            </div>
        </div>
    </section>

    <section id="objectives" class="py-16 md:py-32 bg-white px-4 md:px-6">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-10 md:gap-20 items-center">
            <div class="relative h-[300px] md:h-[500px] w-full slideshow-container reveal-hidden order-2 md:order-1">
                <img src="{{ asset('img/IndustryExposure.png') }}" class="active" alt="Industry Exposure">
                <img src="{{ asset('img/OperationalSimulation.png') }}" alt="Operational Simulation">
                <img src="{{ asset('img/EfficiencyTraining.png') }}" alt="Efficiency Training">
                <img src="{{ asset('img/AnalyticalSkills.png') }}" alt="Analytical Skills">
            </div>
            
            <div class="px-2 md:px-4 reveal-hidden delay-1 order-1 md:order-2">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight mb-8 md:mb-12">Program Objectives</h2>
                <div class="space-y-8 md:space-y-12">
                    <div class="flex gap-4 md:gap-6">
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl md:rounded-2xl bg-red-50 flex-shrink-0 flex items-center justify-center text-red-800 text-xl md:text-2xl"><i class="fas fa-check-circle"></i></div>
                        <p class="text-slate-700 text-base md:text-xl leading-relaxed">
                            <b class="text-slate-900 block text-xl md:text-3xl mb-1 md:mb-2">Industry Exposure</b> Hands-on experience with standard POS workflows and hospitality technology.
                        </p>
                    </div>
                    <div class="flex gap-4 md:gap-6">
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl md:rounded-2xl bg-red-50 flex-shrink-0 flex items-center justify-center text-red-800 text-xl md:text-2xl"><i class="fas fa-check-circle"></i></div>
                        <p class="text-slate-700 text-base md:text-xl leading-relaxed">
                            <b class="text-slate-900 block text-xl md:text-3xl mb-1 md:mb-2">Operational Simulation</b> Bridging the gap between theory and practical laboratory simulations.
                        </p>
                    </div>
                    <div class="flex gap-4 md:gap-6">
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl md:rounded-2xl bg-red-50 flex-shrink-0 flex items-center justify-center text-red-800 text-xl md:text-2xl"><i class="fas fa-check-circle"></i></div>
                        <p class="text-slate-700 text-base md:text-xl leading-relaxed">
                            <b class="text-slate-900 block text-xl md:text-3xl mb-1 md:mb-2">Efficiency Training</b> Manage order processing to reduce errors and service delays efficiently.
                        </p>
                    </div>
                    <div class="flex gap-4 md:gap-6">
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl md:rounded-2xl bg-red-50 flex-shrink-0 flex items-center justify-center text-red-800 text-xl md:text-2xl"><i class="fas fa-check-circle"></i></div>
                        <p class="text-slate-700 text-base md:text-xl leading-relaxed">
                            <b class="text-slate-900 block text-xl md:text-3xl mb-1 md:mb-2">Analytical Skills</b> Utilizing data to evaluate performance and improve operational outcomes.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <footer class="pt-12 md:pt-20 pb-4 md:pb-3 bg-slate-50 border-t border-slate-100 px-4 md:px-6">
        <div class="max-w-8xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6 md:gap-8">
            <div class="flex items-center gap-2">
                <div class="w-8 h-6 md:w-10 md:h-8 ub-maroon rounded-lg flex items-center justify-center shadow-md">
                    <span class="text-white font-black text-[10px] md:text-sm">UB</span>
                </div>
                <span class="text-lg md:text-xl font-black tracking-tighter ub-text-maroon">SYNCSERVE</span>
            </div>
            
            <p class="text-slate-500 text-xs md:text-sm font-bold text-center">
                © 2026 UB-SYNCSERVE. All Rights Reserved
            </p>

            <div class="text-[10px] md:text-[11px] font-black uppercase tracking-[0.25em] text-slate-400">
                Built for Excellence
            </div>
        </div>
    </footer>

    <script>
        // Intersection Observer for Scroll Reveal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal-visible');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.reveal-hidden').forEach(el => observer.observe(el));

        // Smooth Cross-fade Slideshow
        let slideIndex = 0;
        const slides = document.querySelectorAll(".slideshow-container img");
        function showSlides() {
            if(slides.length === 0) return;
            slides.forEach(s => s.classList.remove("active"));
            slideIndex++;
            if (slideIndex > slides.length) slideIndex = 1;
            slides[slideIndex - 1].classList.add("active");
            setTimeout(showSlides, 4000); 
        }
        if(slides.length > 0) showSlides();
    </script>
</body>
</html>