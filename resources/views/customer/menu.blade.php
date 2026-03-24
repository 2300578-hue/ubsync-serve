<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Menu | UB-Sync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfc; }
        [x-cloak] { display: none !important; }
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
        
        .product-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0,0,0,0.03);
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(128, 0, 0, 0.08);
        }

        .category-node { transition: all 0.3s ease; }
        .active-category {
            background: #800000 !important;
            color: white !important;
            box-shadow: 0 8px 15px rgba(128, 0, 0, 0.2);
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        .cart-floating {
            background: linear-gradient(135deg, #800000 0%, #a50000 100%);
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.3);
        }

        .btn-maroon {
            background: linear-gradient(135deg, #800000 0%, #a50000 100%);
            transition: all 0.2s ease;
        }
        .btn-maroon:hover { filter: brightness(1.1); transform: translateY(-1px); }
        .btn-maroon:active { transform: translateY(0); scale: 0.98; }
    </style>
</head>
<body class="antialiased" x-data="menuApp()" x-init="initCart()" x-cloak>

    <div x-show="!customerName" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-maroon/40 backdrop-blur-md"></div>
        <div class="bg-white rounded-[2rem] p-8 max-w-lg w-full shadow-2xl animate__animated animate__zoomIn">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900">Table <span class="text-gray" x-text="tableNumber"></span></h2>
                <p class="text-gray-500 mt-2">Enter your name to start ordering</p>
            </div>  
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" x-model="tempFirstName" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-maroon" placeholder="First Name">
                    <input type="text" x-model="tempLastName" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-maroon" placeholder="Last Name">
                </div>
                <button @click="saveIdentity()" class="w-full btn-maroon text-white py-4 rounded-xl font-bold uppercase tracking-widest text-sm">
                    Enter Menu
                </button>
            </div>
        </div>
    </div>

    <div class="fixed bottom-8 right-8 z-50" id="cart-anchor">
        <a href="{{ route('customer.cart') }}" class="cart-floating text-white w-16 h-16 rounded-2xl flex items-center justify-center relative shadow-lg">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            <span x-show="cartItemCount > 0" class="absolute -top-1 -right-1 bg-yellow-400 text-maroon text-[10px] font-black h-6 w-6 flex items-center justify-center rounded-full border-2 border-white shadow-sm" x-text="cartItemCount"></span>
        </a>
    </div>

    <main class="max-w-7xl mx-auto px-6 pt-8 pb-32">
        <div class="relative z-30 mb-10">
            <div class="bg-white border border-gray-100 shadow-md rounded-[1.5rem] p-2 flex items-center justify-between">
                <div class="flex space-x-1 overflow-x-auto no-scrollbar flex-1 px-2">
                    <template x-for="cat in categories" :key="cat.name">
                        <button @click="selectedCategory = cat.name" 
                            class="px-6 py-2.5 rounded-xl font-bold text-[11px] uppercase tracking-wider whitespace-nowrap"
                            :class="selectedCategory === cat.name ? 'active-category' : 'text-gray-400 hover:text-maroon'">
                            <span x-text="cat.name"></span>
                        </button>
                    </template>
                </div>
                <div class="flex items-center gap-5 bg-gray-50 rounded-2xl px-5 py-2 border border-gray-100 ml-4">
                    <div class="flex flex-col items-center justify-center min-w-[50px]">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter leading-none mb-0.5">Table</p>
                        <p class="text-xl font-black text-maroon leading-none" x-text="tableNumber"></p>
                    </div>
                    <div class="h-8 w-[1.5px] bg-gray-200"></div>
                    <div class="flex flex-col items-start justify-center">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter leading-none mb-0.5">Ordering as</p>
                        <p class="text-[11px] font-black text-gray-900 uppercase truncate max-w-[120px]" x-text="customerName || '---'"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <template x-for="food in filteredFoods" :key="food.id">
                <div class="bg-white rounded-[2rem] product-card p-4 flex flex-col border border-gray-50">
                    <div class="w-full h-52 bg-gray-50 rounded-[1.5rem] flex items-center justify-center p-4 mb-4">
                        <img :src="food.image" :id="'img-' + food.id" class="max-h-full object-contain drop-shadow-xl">
                    </div>
                    <div class="px-1 flex flex-col flex-1">
                        <h4 class="font-bold text-lg text-gray-900 leading-tight mb-4" x-text="food.name"></h4>
                        <div class="mt-auto">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xl font-black text-gray-900">₱<span x-text="food.price"></span></span>
                                <div x-show="food.stock > 0" class="flex items-center bg-gray-100 rounded-xl p-1">
                                    <button @click="food.tempQty > 1 ? food.tempQty-- : null" class="w-7 h-7 flex items-center justify-center bg-white rounded-lg shadow-sm hover:bg-maroon hover:text-white transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M20 12H4"/></svg>
                                    </button>
                                    <span class="w-8 text-center font-bold text-xs" x-text="food.tempQty"></span>
                                    <button @click="food.tempQty < food.stock ? food.tempQty++ : null" class="w-7 h-7 flex items-center justify-center bg-white rounded-lg shadow-sm hover:bg-maroon hover:text-white transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>
                            <button @click="addToCart(food)" 
                                    class="w-full btn-maroon text-white py-4 rounded-xl font-bold text-xs uppercase tracking-widest shadow-md">
                                Add to Order
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </main>

    <script>
    function menuApp() {
        return {
            customerName: '', tempFirstName: '', tempLastName: '', tableNumber: '1', selectedCategory: 'All', cart: [],
            categories: [{name:'All'}, {name:'Main Course'}, {name:'Desserts'}, {name:'Beverages'}, {name:'Appetizer'}],
            foods: [
                { id: 1, name: 'Burger Steak', category: 'Main Course', price: 150, tempQty: 1, stock: 10, image: "{{ asset('img/burgersteak.png') }}" },
                { id: 2, name: 'Carbonara', category: 'Main Course', price: 120, tempQty: 1, stock: 5, image: "{{ asset('img/carbonara.png') }}" },
                { id: 4, name: 'Ice Tea', category: 'Beverages', price: 45, tempQty: 1, stock: 20, image: "{{ asset('img/icetea.png') }}" },
                { id: 5, name: 'Mozarella Sticks', category: 'Appetizer', price: 95, tempQty: 1, stock: 8, image: "{{ asset('img/mozarella.png') }}" },
                { id: 6, name: 'Leche Flan', category: 'Desserts', price: 55, tempQty: 1, stock: 8, image: "{{ asset('img/lecheflan.png') }}" },
            ],
            initCart() {
                const urlParams = new URLSearchParams(window.location.search);
                this.tableNumber = urlParams.get('table') || '1';
                
                // Kunin ang cart sa storage kung meron na
                const savedCart = localStorage.getItem('ub_cart');
                if (savedCart) {
                    this.cart = JSON.parse(savedCart);
                }
            },
            saveIdentity() {
                if(this.tempFirstName.trim() && this.tempLastName.trim()) {
                    this.customerName = this.tempFirstName.trim() + ' ' + this.tempLastName.trim();
                } else { alert('Name required.'); }
            },
            get filteredFoods() {
                return this.selectedCategory === 'All' ? this.foods : this.foods.filter(f => f.category === this.selectedCategory);
            },
            
            // FIX: Ito yung nagbabago ng count sa cart icon badge
            get cartItemCount() { 
                return this.cart.length; // Bibilangin lang kung ilang items ang nasa array
            },

            addToCart(food) {
                const existing = this.cart.find(i => i.id === food.id);
                if (existing) { 
                    existing.qty += food.tempQty; 
                } else { 
                    this.cart.push({...food, qty: food.tempQty}); 
                }
                
                // Save to local storage
                localStorage.setItem('ub_cart', JSON.stringify(this.cart));
                
                // Optional: Reset tempQty to 1 after adding
                food.tempQty = 1;

                // Feedback (Optional)
                console.log('Added to cart. Unique items:', this.cart.length);
            }
        }
    }
    </script>
</body>
</html>