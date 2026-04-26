<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Info | UB Sync</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .border-maroon { border-color: #800000; }
        .text-maroon { color: #800000; }
        .bg-maroon { background-color: #800000; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4"
      x-data="{ 
          firstName: '', 
          lastName: '',
          tableNumber: '{{ $tableNumber }}',
          baseUrl: '{{ url('/customer/menu') }}', 
          
          proceedToMenu() {
              if(!this.firstName.trim() || !this.lastName.trim()) {
                  alert('Please enter both First and Last Name.');
                  return;
              }
              
              const fullName = this.firstName.trim() + ' ' + this.lastName.trim();
              localStorage.setItem('ub_customer_name', fullName);
              localStorage.setItem('ub_current_table', this.tableNumber);
              
              const finalUrl = this.baseUrl + 
                               '?table=' + this.tableNumber + 
                               '&fname=' + encodeURIComponent(this.firstName.trim()) + 
                               '&lname=' + encodeURIComponent(this.lastName.trim());
                               
              window.location.href = finalUrl;
          }
      }">

    <div class="bg-white w-full max-w-sm p-8 rounded-[2.5rem] shadow-2xl">
        
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-50 text-maroon rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-user-tag text-2xl"></i>
            </div>
            <h3 class="font-black text-gray-800 uppercase text-sm tracking-widest">Customer Information</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Table {{ $tableNumber }}</p>
        </div>

        <div class="space-y-4">
            <div>
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">First Name</label>
                <input type="text" x-model="firstName" placeholder="e.g. Juan" 
                       @keydown.enter="proceedToMenu()"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold bg-gray-50 focus:bg-white focus:border-maroon focus:ring-2 focus:ring-red-900/10 outline-none transition-all">
            </div>
            <div>
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Last Name</label>
                <input type="text" x-model="lastName" placeholder="e.g. Dela Cruz" 
                       @keydown.enter="proceedToMenu()"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold bg-gray-50 focus:bg-white focus:border-maroon focus:ring-2 focus:ring-red-900/10 outline-none transition-all">
            </div>
            
            <button @click="proceedToMenu()" 
                    class="w-full bg-maroon text-white py-4 rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-red-900/20 active:scale-95 transition-all mt-4">
                Proceed to Menu
            </button>
        </div>
        
    </div>

</body>
</html>