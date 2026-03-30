<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        .ub-maroon { background-color: #800000; }
        .ub-text-maroon { color: #800000; }
        body {
            background-color: #f8f9fa; 
            margin: 0;
            padding: 0;
        }
        .fade-out { opacity: 0; transition: opacity 0.5s ease-out; }
        .alert-wrapper {
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        a { text-decoration: none !important; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="bg-white/95 backdrop-blur-md px-8 pb-10 pt-6 rounded-[2rem] shadow-2xl w-full max-w-md border border-white/20 text-center">
        
        <div class="flex justify-start mb-4">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-red-800 transition-all font-bold text-xs uppercase tracking-widest group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5 group-hover:-translate-x-1 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Home
            </a>
        </div>

        <div class="mb-2">
            <h1 class="text-5xl font-extrabold ub-text-maroon tracking-tighter">UB Sync</h1>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">Welcome to UB-SYNCSERVE</p>
        </div>

        <div class="alert-wrapper mb-4">
            {{-- Success Message from Registration --}}
            @if (session('success'))
                <div id="alert-success" class="animate__animated animate__fadeInDown w-full bg-green-50 text-green-700 p-3 rounded-xl text-sm font-bold border border-green-200 text-center flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error Message for Invalid Login --}}
            @if ($errors->any())
                <div id="alert-error-login" class="animate__animated animate__shakeX w-full bg-red-50 text-red-600 p-3 rounded-xl text-sm font-bold border border-red-200 text-center">
                    Invalid email or password
                </div>
            @endif
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4 text-left">
            @csrf
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase ml-1 mb-1 tracking-widest">Email Address</label>
                <input type="email" name="email" id="email" required placeholder="" autocomplete="off"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-800 outline-none transition shadow-sm">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase ml-1 mb-1 tracking-widest">Password</label>
                <input type="password" name="password" id="password" required placeholder="" autocomplete="current-password"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-800 outline-none transition shadow-sm">
            </div>

            <button type="submit" class="w-full ub-maroon hover:bg-red-900 text-white font-bold py-4 rounded-xl transition shadow-lg mt-4 uppercase tracking-widest text-lg focus:outline-none active:scale-95">
                Sign In
            </button>

            <div class="text-center mt-8 space-y-4">
                <a href="{{ route('password.request') }}" class="ub-text-maroon font-bold text-sm hover:opacity-80 transition-opacity inline-block">
                    Forgot Password?
                </a>
                
                <div class="border-t border-gray-100 pt-6 text-sm text-gray-500">
                    New to UB Sync? 
                    <a href="{{ route('register') }}" class="ub-text-maroon font-black hover:opacity-80 transition-opacity">
                        Create Account
                    </a>
                </div>
            </div>
        </form>
        

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Timer para sa lahat ng alerts (Success o Error)
            const alertIds = ['alert-success', 'alert-error-login'];
            
            alertIds.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    // Mas matagal nang konti ang success message (4 seconds) 
                    // kaysa sa error (2 seconds) para mabasa ng user
                 const displayTime = id === 'alert-success' ? 2000 : 2500;

                    setTimeout(() => {
                        element.classList.add('fade-out');
                        setTimeout(() => { 
                            element.style.display = 'none'; 
                        }, 500);
                    }, displayTime); 
                }
            });
        });
    </script>
</body>
</html>