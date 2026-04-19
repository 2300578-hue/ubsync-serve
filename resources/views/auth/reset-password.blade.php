<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password | UB Sync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        .ub-maroon { background-color: #800000; }
        .ub-text-maroon { color: #800000; }
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .fade-out { opacity: 0; transition: opacity 0.5s ease-out; }
        .alert-container { min-height: 65px; display: flex; align-items: center; justify-content: center; margin-bottom: 0.5rem; }
        
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
                width: 3.2rem; 
                height: 3.8rem; 
                font-size: 1.5rem;
                border-radius: 0.75rem; 
            }
        }
        
        .otp-square:focus { border-color: #800000; outline: none; box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.1); }
       
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 sm:p-6 lg:p-8">

    <div id="success-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-black/60 backdrop-blur-md animate__animated animate__fadeIn px-4">
        <div class="bg-white p-6 sm:p-8 rounded-[2.5rem] shadow-2xl text-center max-w-xs w-full animate__animated animate__zoomIn">
            <div class="mb-4 flex justify-center">
                <div class="bg-green-100 p-4 rounded-full animate__animated animate__bounceIn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-gray-800 tracking-tight">Success!</h2>
            <p class="text-gray-500 text-[10px] font-bold mt-1 uppercase tracking-widest">Password Reset Successfully</p>
        </div>
    </div>

    <div class="bg-white px-6 sm:px-8 pb-10 pt-6 rounded-[2.5rem] shadow-2xl w-full max-w-md sm:max-w-lg text-center border border-white/20">
        
        <div class="flex justify-start mb-4">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-red-800 transition-all font-bold text-xs uppercase tracking-widest group no-underline">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5 group-hover:-translate-x-1 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back to Login
            </a>
        </div>
          
          <div class="-mb-9 relative z-10">
            <h1 class="text-3xl sm:text-4xl font-extrabold ub-text-maroon tracking-tighter">Reset Password</h1>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">Verify identity to update password</p>
        </div>

        <div class="alert-container pt-10 relative z-0">
            <div id="dynamic-alert" class="hidden w-full p-3 rounded-xl text-xs sm:text-sm font-bold border text-center transition-all duration-300">
                <span id="alert-message"></span>
            </div>
        </div>

        <form id="resetForm" action="{{ route('password.verify-update') }}" method="POST" class="space-y-4 text-left">
            @csrf
            
            <div>
                <label class="block text-[11px] sm:text-xs font-bold text-gray-500 uppercase ml-1 mb-1 tracking-tight">Registered Email</label>
                <div class="flex gap-2">
                    <input type="email" name="email" id="email" required oninput="checkInputs()"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-red-800 shadow-sm text-sm">
                    <button type="button" id="btn-send-otp" onclick="handleOTPSending()" disabled
                        class="ub-maroon text-white text-[10px] font-bold px-3 sm:px-4 rounded-xl hover:bg-red-900 transition shadow-md uppercase tracking-widest disabled:opacity-60 disabled:cursor-not-allowed min-w-[90px] sm:min-w-[100px]">
                        Send OTP
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] sm:text-xs font-bold text-gray-500 uppercase ml-1 mb-1 tracking-tight">New Password</label>
                    <input type="password" name="password" id="password" required oninput="checkInputs()"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-red-800 shadow-sm text-sm">
                </div>
                <div>
                    <label class="block text-[11px] sm:text-xs font-bold text-gray-500 uppercase ml-1 mb-1 tracking-tight">Confirm</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required oninput="checkInputs()"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-red-800 shadow-sm text-sm">
                </div>
            </div>

            <div class="pt-4 flex flex-col items-center">
                <div class="w-fit">
                    <label class="block text-[11px] sm:text-xs font-bold text-gray-500 uppercase mb-2 sm:mb-3 tracking-tight text-center sm:text-left ml-1">Enter 6-Digit Code</label>
                    <div class="flex justify-center gap-1 sm:gap-2" id="otp-inputs">
                        <input type="text" maxlength="1" class="otp-square" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-square" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-square" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-square" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-square" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-square" inputmode="numeric">
                    </div>
                    <input type="hidden" name="otp_code" id="final_otp">
                </div>
            </div>

            <button type="submit" id="btn-update" class="w-full ub-maroon hover:bg-red-900 text-white font-bold py-3.5 sm:py-4 rounded-2xl transition shadow-lg mt-6 uppercase tracking-widest sm:tracking-[0.2em] active:scale-95 text-sm sm:text-base">
                Update Password
            </button>
        </form>
    </div>

    <script>
        const inputs = document.querySelectorAll('#otp-inputs input');
        const hiddenInput = document.getElementById('final_otp');
        let otpCountdown;

        function showDynamicAlert(msg, type = 'error') {
            const alertBox = document.getElementById('dynamic-alert');
            const msgSpan = document.getElementById('alert-message');
            alertBox.className = type === 'success' ? "w-full bg-green-50 text-green-700 p-3 rounded-xl text-xs sm:text-sm font-bold border border-green-200 text-center animate__animated animate__fadeInDown" : "w-full bg-red-50 text-red-600 p-3 rounded-xl text-xs sm:text-sm font-bold border border-red-200 text-center animate__animated animate__shakeX";
            msgSpan.innerText = msg;
            alertBox.classList.remove('hidden', 'fade-out');
            setTimeout(() => { alertBox.classList.add('fade-out'); setTimeout(() => alertBox.classList.add('hidden'), 500); }, 4000);
        }

        function checkInputs() {
            const email = document.getElementById('email').value;
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const btn = document.getElementById('btn-send-otp');
            const isTimerRunning = /s$/.test(btn.innerText) && btn.innerText !== "Send OTP";

            if(email && pass && confirm && pass.length >= 8 && !isTimerRunning) {
                btn.disabled = false;
            } else {
                btn.disabled = true;
            }
        }

        async function handleOTPSending() {
            const email = document.getElementById('email').value;
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const btn = document.getElementById('btn-send-otp');

            if(pass !== confirm) { showDynamicAlert("Passwords do not match!", "error"); return; }
            btn.disabled = true; btn.innerText = "Sending...";
            
            try {
                const res = await fetch("{{ route('password.send-otp') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content, "Accept": "application/json" },
                    body: JSON.stringify({ email })
                });
                const data = await res.json();
                if(res.ok) { startTimer(60); inputs[0].focus(); }
                else { showDynamicAlert(data.message, "error"); btn.disabled = false; btn.innerText = "Send OTP"; checkInputs(); }
            } catch(e) { showDynamicAlert("Server Connection Error"); btn.disabled = false; btn.innerText = "Send OTP"; }
        }

        function startTimer(s) {
            const btn = document.getElementById('btn-send-otp');
            let t = s; clearInterval(otpCountdown);
            btn.disabled = true;
            otpCountdown = setInterval(() => {
                btn.innerText = `${t}s`;
                if(t-- <= 0) {
                    clearInterval(otpCountdown);
                    btn.innerText = "Send OTP";
                    checkInputs();
                }
            }, 1000);
        }

     function triggerOTPSemanticError() {
    // Inalis ang 'otp-error' sa classList.add
    inputs.forEach(i => { 
        i.classList.add('animate__animated', 'animate__shakeX'); 
        i.value = ""; 
    });
    hiddenInput.value = "";
    setTimeout(() => inputs.forEach(i => i.classList.remove('animate__shakeX')), 600);
    inputs[0].focus();
}

        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {             
                if (e.target.value.length > 1) e.target.value = e.target.value.slice(0, 1);
                if (e.target.value && index < inputs.length - 1) inputs[index + 1].focus();
                let code = ""; inputs.forEach(i => code += i.value); hiddenInput.value = code;
            });
            input.addEventListener('keydown', (e) => { if (e.key === 'Backspace' && !e.target.value && index > 0) inputs[index - 1].focus(); });
        });

        document.getElementById('resetForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            if(hiddenInput.value.length < 6) { showDynamicAlert("Please enter the 6-digit OTP code."); triggerOTPSemanticError(); return; }
            const btn = document.getElementById('btn-update');
            btn.innerText = "UPDATING..."; btn.disabled = true;

            try {
                const res = await fetch(this.action, {
                    method: "POST",
                    body: new FormData(this),
                    headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content, "Accept": "application/json" }
                });
                const data = await res.json();
                if(res.ok && data.success) {
                    document.getElementById('success-modal').classList.remove('hidden');
                    setTimeout(() => { window.location.href = data.redirect; }, 3000);
                } else {
                    showDynamicAlert(data.message, "error");
                    btn.innerText = "Update Password"; btn.disabled = false;
                    triggerOTPSemanticError();
                }
            } catch(e) { showDynamicAlert("Connection Error"); btn.innerText = "Update Password"; btn.disabled = false; }
        });
    </script>
</body>
</html>