<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | UB Sync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        .ub-maroon { background-color: #800000; }
        .ub-text-maroon { color: #800000; }
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .hidden-step { display: none !important; }
        
        /* Responsive OTP Squares */
        .otp-square { 
            width: 2rem; 
            height: 2.5rem; 
            text-align: center; 
            font-size: 1rem; 
            font-weight: 800; 
            border: 2px solid #e2e8f0; 
            border-radius: 0.5rem; 
            background-color: #f8fafc; 
            transition: all 0.2s; 
        }
        @media (min-width: 640px) {
            .otp-square {
                width: 2.5rem; 
                height: 3rem; 
                font-size: 1.25rem;
                border-radius: 0.75rem; 
            }
        }
        .otp-square:focus { border-color: #800000; outline: none; box-shadow: 0 0 0 4px rgba(128,0,0,0.1); }
        input::placeholder { font-size: 0.8rem; opacity: 0.5; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 sm:p-6 lg:p-8">

    <div id="success-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-black/60 backdrop-blur-md animate__animated animate__fadeIn px-4">
        <div class="bg-white p-6 sm:p-8 rounded-[2.5rem] shadow-2xl text-center max-w-xs w-full border border-white/20 animate__animated animate__zoomIn">
            <div class="mb-4 flex justify-center">
                <div class="bg-green-100 p-4 rounded-full animate__animated animate__bounceIn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 sm:h-10 sm:w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-gray-800 tracking-tight">Success!</h2>
            <p class="text-gray-500 text-[10px] font-bold mt-1 uppercase tracking-widest">Account Created</p>
        </div>
    </div>

    <div class="bg-white px-6 sm:px-8 pb-8 pt-6 rounded-[2.2rem] shadow-2xl w-full max-w-md border border-white/20">
        
        <div id="back-home-wrapper" class="flex justify-start mb-4">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-red-800 transition-all font-bold text-[11px] uppercase tracking-widest no-underline group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5 group-hover:-translate-x-1 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Home
            </a>
        </div>

        <div class="text-center -mb-6">
            <h1 class="text-3xl sm:text-4xl font-extrabold ub-text-maroon tracking-tighter leading-none">UB Sync</h1>
            <p id="step-title" class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">Step 1: Account Details</p>
        </div>

        <div id="alert-container" class="min-h-[45px] mb-1">
            <div id="error-alert" class="hidden w-full bg-red-50 text-red-600 p-2.5 rounded-xl text-[11px] sm:text-xs font-bold border border-red-100 text-center animate__animated">
                <span id="error-message"></span>
            </div>
        </div>

        <form id="regForm" action="{{ route('register.post') }}" method="POST" onsubmit="return false;">
            @csrf
            
            <div id="step1" class="space-y-4 text-left">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase ml-1 mb-1 tracking-widest">First Name</label>
                        <input type="text" id="first_name" name="first_name" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-red-800 transition-all text-sm shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase ml-1 mb-1 tracking-widest">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-red-800 transition-all text-sm shadow-sm">
                    </div>
                </div>
                
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase ml-1 mb-1 tracking-widest">Email Address</label>
                    <input type="email" id="email" name="email" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-red-800 transition-all text-sm shadow-sm">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase ml-1 mb-1 tracking-widest">User Role</label>
                    <select id="role" name="role" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm outline-none focus:ring-2 focus:ring-red-800 transition-all shadow-sm">
                        <option value="" disabled selected>Select Role</option>
                        <option value="admin">Faculty / Instructor</option>
                        <option value="cashier">Student Cashier</option>
                        <option value="chef">Student Chef</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase ml-1 mb-1 tracking-widest">Password</label>
                        <input type="password" id="password" name="password" required minlength="8" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-red-800 transition-all text-sm shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase ml-1 mb-1 tracking-widest">Confirm</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-red-800 transition-all text-sm shadow-sm">
                    </div>
                </div>

                <button type="button" onclick="validateStep1()" id="btn-next" class="w-full ub-maroon hover:bg-red-900 text-white font-bold py-3.5 rounded-xl shadow-lg mt-2 uppercase tracking-widest active:scale-95 transition-all text-sm">
                    Create Account
                </button>
            </div>
            
            <div id="step2" class="hidden-step text-center space-y-6 animate__animated animate__fadeIn">
                <div class="bg-red-50 p-4 rounded-2xl border border-red-100">
                    <p class="text-[10px] text-gray-500 mb-1 font-bold uppercase tracking-widest text-center">Verification Sent To</p>
                    <b id="display-email" class="ub-text-maroon text-xs sm:text-sm break-all"></b>
                </div>
                
                <div class="flex items-center justify-center gap-2">
                    <span id="countdown-timer" class="text-xl font-black ub-text-maroon tracking-tighter">01:00</span>
                    <button type="button" id="btn-resend" onclick="handleResend()" class="hidden text-xs font-black ub-text-maroon uppercase tracking-widest hover:text-red-700 transition-colors">
                        Resend Code
                    </button>
                </div>

                <div class="flex flex-col items-center">
                    <div class="flex justify-center gap-1 sm:gap-1.5" id="otp-inputs-register">
                        <input type="text" maxlength="1" class="otp-square" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-square" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-square" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-square" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-square" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-square" inputmode="numeric">
                    </div>
                    <input type="hidden" name="otp_code" id="final_otp_register">
                </div>

                <div class="flex flex-col gap-3">
                    <button type="button" onclick="handleFinalSubmit()" id="btn-complete" class="w-full ub-maroon hover:bg-red-900 text-white font-bold py-3.5 rounded-xl shadow-xl uppercase tracking-widest active:scale-95 transition-all text-sm">
                        Complete Registration
                    </button>
                    <button type="button" onclick="backToStep1()" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">← Edit Account Details</button>
                </div>
            </div>
        </form>

        <div id="login-link" class="text-center mt-7 pt-5 border-t border-gray-50">
            <p class="text-xs sm:text-sm text-gray-400 font-medium">Already have an account? <a href="{{ route('login') }}" class="ub-text-maroon font-black hover:opacity-70 transition-opacity">Sign In</a></p>
        </div>
    </div>

    <script>
        let timerInterval;
        const regInputs = document.querySelectorAll('#otp-inputs-register input');
        const finalRegOtp = document.getElementById('final_otp_register');

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (!document.getElementById('step1').classList.contains('hidden-step')) {
                    validateStep1();
                } else {
                    handleFinalSubmit();
                }
            }
        });

        function showAlert(msg) {
            const alertBox = document.getElementById('error-alert');
            document.getElementById('error-message').innerText = msg;
            alertBox.classList.remove('hidden', 'animate__fadeOutUp');
            alertBox.classList.add('animate__shakeX');
            setTimeout(() => {
                alertBox.classList.replace('animate__shakeX', 'animate__fadeOutUp');
                setTimeout(() => alertBox.classList.add('hidden'), 500);
            }, 3000);
        }

        async function validateStep1() {
            const email = document.getElementById('email');
            const pass = document.getElementById('password');
            const conf = document.getElementById('password_confirmation');

            if (!document.getElementById('first_name').checkValidity()) { document.getElementById('first_name').reportValidity(); return; }
            if (!document.getElementById('last_name').checkValidity()) { document.getElementById('last_name').reportValidity(); return; }
            if (!email.checkValidity()) { email.reportValidity(); return; }
            if (!document.getElementById('role').checkValidity()) { document.getElementById('role').reportValidity(); return; }
            if (!pass.checkValidity()) { pass.reportValidity(); return; }

            if (pass.value !== conf.value) { showAlert("Passwords do not match!"); conf.focus(); return; }

            const btn = document.getElementById('btn-next');
            btn.innerText = "VERIFYING..."; btn.disabled = true;

            try {
                const res = await fetch("{{ route('send.otp') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}", "Accept": "application/json" },
                    body: JSON.stringify({ email: email.value })
                });
                const data = await res.json();
                if (res.ok) {
                    document.getElementById('step1').classList.add('hidden-step');
                    document.getElementById('back-home-wrapper').classList.add('hidden');
                    document.getElementById('login-link').classList.add('hidden');
                    document.getElementById('step2').classList.remove('hidden-step');
                    document.getElementById('step-title').innerText = "Step 2: Verification Code";
                    document.getElementById('display-email').innerText = email.value;
                    startTimer(60);
                    setTimeout(() => regInputs[0].focus(), 500);
                } else { showAlert(data.error || "Error occurred."); }
            } catch (e) { showAlert("Connection error."); }
            finally { btn.innerText = "CREATE ACCOUNT"; btn.disabled = false; }
        }

        async function handleFinalSubmit() {
            if (finalRegOtp.value.length < 6) { showAlert("Enter 6-digit code."); return; }
            const btn = document.getElementById('btn-complete');
            btn.innerText = "VERIFYING..."; btn.disabled = true;
            try {
                const res = await fetch(document.getElementById('regForm').action, {
                    method: "POST", body: new FormData(document.getElementById('regForm')), headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const result = await res.json();
                if (res.ok && result.success) {
                    document.getElementById('success-modal').classList.remove('hidden');
                    setTimeout(() => { window.location.href = result.redirect; }, 2500);
                } else { showAlert(result.message || "Invalid Code."); btn.disabled = false; btn.innerText = "COMPLETE REGISTRATION"; }
            } catch (e) { showAlert("Server error."); btn.disabled = false; }
        }

        async function handleResend() {
            const btn = document.getElementById('btn-resend');
            btn.innerText = "SENDING..."; btn.disabled = true;
            try {
                const res = await fetch("{{ route('send.otp') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}", "Accept": "application/json" },
                    body: JSON.stringify({ email: document.getElementById('email').value })
                });
                if (res.ok) { btn.innerText = "RESEND CODE"; btn.disabled = false; startTimer(60); }
            } catch (e) { btn.innerText = "RESEND CODE"; btn.disabled = false; }
        }

        function backToStep1() {
            document.getElementById('step1').classList.remove('hidden-step');
            document.getElementById('back-home-wrapper').classList.remove('hidden');
            document.getElementById('login-link').classList.remove('hidden');
            document.getElementById('step2').classList.add('hidden-step');
            document.getElementById('step-title').innerText = "Step 1: Account Details";
        }

        regInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value && index < regInputs.length - 1) regInputs[index + 1].focus();
                let code = ""; regInputs.forEach(i => code += i.value); finalRegOtp.value = code;
            });
            input.addEventListener('keydown', (e) => { if (e.key === 'Backspace' && !e.target.value && index > 0) regInputs[index - 1].focus(); });
        });

        function startTimer(duration) {
            clearInterval(timerInterval);
            let timer = duration;
            document.getElementById('countdown-timer').classList.remove('hidden');
            document.getElementById('btn-resend').classList.add('hidden');
            timerInterval = setInterval(() => {
                let m = Math.floor(timer/60), s = timer%60;
                document.getElementById('countdown-timer').textContent = `${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
                if (--timer < 0) { clearInterval(timerInterval); document.getElementById('countdown-timer').classList.add('hidden'); document.getElementById('btn-resend').classList.remove('hidden'); }
            }, 1000);
        }
    </script>
</body>
</html>