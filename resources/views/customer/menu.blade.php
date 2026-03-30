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
        <div class="bg-white rounded-[2rem] p-6 sm:p-8 max-w-lg w-full shadow-2xl animate__animated animate__zoomIn">
            <div class="text-center mb-6 sm:mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Table <span class="text-gray" x-text="tableNumber"></span></h2>
                <p class="text-gray-500 mt-2">Enter your name to start ordering</p>
            </div>  
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" x-model="tempFirstName" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-maroon" placeholder="First Name">
                    <input type="text" x-model="tempLastName" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-maroon" placeholder="Last Name">
                </div>
                <button type="button" @click="saveIdentity()" class="w-full btn-maroon text-white py-3 sm:py-4 rounded-xl font-bold uppercase tracking-widest text-sm">
                    Enter Menu
                </button>
            </div>
        </div>
    </div>

    <div class="fixed bottom-8 right-8 z-50" id="cart-anchor">
             <a :href="'{{ route('customer.cart') }}?table=' + tableNumber" class="cart-floating text-white w-16 h-16 rounded-2xl flex items-center justify-center relative shadow-lg">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            <span x-show="cartItemCount > 0" class="absolute -top-1 -right-1 bg-yellow-400 text-maroon text-[10px] font-black h-6 w-6 flex items-center justify-center rounded-full border-2 border-white shadow-sm" x-text="cartItemCount"></span>
        </a>
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 pt-6 sm:pt-8 pb-32">
        <div class="relative z-30 mb-8 sm:mb-10">
            <div class="bg-white border border-gray-100 shadow-md rounded-[1.5rem] p-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-0">
                <div class="flex space-x-1 overflow-x-auto no-scrollbar flex-1 px-2">
                    <template x-for="cat in categories" :key="cat.name">
                        <button type="button" @click="selectedCategory = cat.name" 
                            class="px-4 sm:px-6 py-2 sm:py-2.5 rounded-xl font-bold text-[10px] sm:text-[11px] uppercase tracking-wider whitespace-nowrap"
                            :class="selectedCategory === cat.name ? 'active-category' : 'text-gray-400 hover:text-maroon'">
                            <span x-text="cat.name"></span>
                        </button>
                    </template>
                </div>
                <div class="flex items-center gap-3 sm:gap-5 bg-gray-50 rounded-2xl px-4 sm:px-5 py-2 border border-gray-100 sm:ml-4">
                    <div class="flex flex-col items-center justify-center min-w-[40px] sm:min-w-[50px]">
                        <p class="text-[8px] sm:text-[9px] font-bold text-gray-400 uppercase tracking-tighter leading-none mb-0.5">Table</p>
                        <p class="text-lg sm:text-xl font-black text-maroon leading-none" x-text="tableNumber"></p>
                    </div>
                    <div class="h-6 sm:h-8 w-[1px] sm:w-[1.5px] bg-gray-200"></div>
                    <div class="flex flex-col items-start justify-center">
                        <p class="text-[8px] sm:text-[9px] font-bold text-gray-400 uppercase tracking-tighter leading-none mb-0.5">Ordering as</p>
                        <p class="text-[10px] sm:text-[11px] font-black text-gray-900 uppercase truncate max-w-[100px] sm:max-w-[120px]" x-text="customerName || '---'"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative z-30 mb-6 sm:mb-8">
            <div class="bg-white border border-gray-100 shadow-md rounded-[1.5rem] p-4 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" x-model="searchQuery" placeholder="Search menu items..." class="w-full bg-transparent outline-none text-sm text-gray-700 placeholder-gray-400 font-medium">
                <button type="button" x-show="searchQuery" @click="searchQuery = ''" class="text-gray-400 hover:text-maroon transition-colors flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <p x-show="searchQuery && filteredFoods.length === 0" class="text-center text-gray-400 text-sm mt-3">No items match your search</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            <template x-for="food in filteredFoods" :key="food.id">
                <div class="bg-white rounded-[2rem] product-card p-4 flex flex-col border border-gray-50">
                    <div class="w-full h-48 sm:h-52 bg-gray-50 rounded-[1.5rem] flex items-center justify-center p-4 mb-4">
                        <img :src="food.image" :id="'img-' + food.id" class="max-h-full object-contain drop-shadow-xl">
                    </div>
                    <div class="px-1 flex flex-col flex-1">
                        <h4 class="font-bold text-base sm:text-lg text-gray-900 leading-tight mb-4" x-text="food.name"></h4>
                        <div class="mt-auto">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-lg sm:text-xl font-black text-gray-900">₱<span x-text="food.price"></span></span>
                                <div x-show="food.stock > 0" class="flex items-center bg-gray-100 rounded-xl p-1">
                                    <button type="button" @click="food.tempQty > 1 ? food.tempQty-- : null" class="w-8 h-8 sm:w-7 sm:h-7 flex items-center justify-center bg-white rounded-lg shadow-sm hover:bg-maroon hover:text-white transition-colors">
                                        <svg class="w-4 h-4 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M20 12H4"/></svg>
                                    </button>
                                    <span class="w-10 sm:w-8 text-center font-bold text-sm sm:text-xs" x-text="food.tempQty"></span>
                                    <button type="button" @click="food.tempQty < food.stock ? food.tempQty++ : null" class="w-8 h-8 sm:w-7 sm:h-7 flex items-center justify-center bg-white rounded-lg shadow-sm hover:bg-maroon hover:text-white transition-colors">
                                        <svg class="w-4 h-4 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div x-show="food.addOns && food.addOns.length > 0" class="mb-4">
                                <button type="button" @click="openCustomizeModal(food)" class="flex items-center justify-between w-full bg-gray-50 border border-gray-200 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                                    <span class="text-sm font-bold text-maroon">Customize Order</span>
                                    <svg class="w-4 h-4 text-maroon transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="food.selectedAddOns && food.selectedAddOns.length > 0" class="mt-2 bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-400 rounded-lg p-3 space-y-1">
                                    <p class="text-xs font-bold text-blue-800">Selected Add-ons:</p>
                                    <template x-for="addon in food.selectedAddOns" :key="addon.name">
                                        <div class="text-xs text-blue-700 flex items-center">
                                            <span class="text-blue-400 mr-2">✓</span>
                                            <span x-text="addon.name"></span>
                                            <span class="text-blue-600 font-bold ml-auto">+₱<span x-text="addon.price"></span></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <button type="button" @click="addToCart(food)" 
                                    class="w-full btn-maroon text-white py-3 sm:py-4 rounded-xl font-bold text-sm sm:text-xs uppercase tracking-widest shadow-md">
                                Add to Order
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </main>

    <!-- Add-ons Modal -->
    <div x-show="showModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4" @click.self="closeCustomizeModal()">
        <div class="absolute inset-0 bg-maroon/50 backdrop-blur-sm"></div>
        <div class="bg-white rounded-[2rem] p-6 max-w-md w-full shadow-2xl animate__animated animate__zoomIn relative z-10">
            <div class="text-center mb-6">
                <h3 class="text-lg font-bold text-maroon" x-text="'Customize ' + (selectedFood ? selectedFood.name : '')"></h3>
                <p class="text-xs text-gray-500 mt-1">Select add-ons for this item</p>
            </div>
            <div x-show="selectedFood && selectedFood.addOns && selectedFood.addOns.length > 0" class="space-y-3 max-h-60 overflow-y-auto mb-6">
                <template x-for="addon in selectedFood.addOns" :key="addon.name">
                    <label class="flex items-center bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors cursor-pointer border border-gray-100">
                        <input type="checkbox" :checked="selectedFood.selectedAddOns.some(a => a.name === addon.name)" 
                               @change="event => {
                                   if(event.target.checked) {
                                       if(!selectedFood.selectedAddOns.some(a => a.name === addon.name)) {
                                           selectedFood.selectedAddOns.push(addon);
                                       }
                                   } else {
                                       selectedFood.selectedAddOns = selectedFood.selectedAddOns.filter(a => a.name !== addon.name);
                                   }
                               }" 
                               class="accent-maroon mr-3 w-5 h-5">
                        <span class="text-sm font-medium text-gray-800" x-text="addon.name"></span>
                        <span class="text-sm text-maroon font-bold ml-auto" x-text="'+₱' + addon.price"></span>
                    </label>
                </template>
            </div>
            <div class="flex gap-3">
                <button type="button" @click="confirmAddOns()" class="flex-1 bg-maroon text-white py-3 px-6 rounded-xl font-bold text-sm uppercase tracking-widest shadow-md hover:bg-red-800 transition-colors">
                    Confirm
                </button>
            </div>
        </div>
    </div>

    <script>
    function menuApp() {
        return {
            customerName: '', tempFirstName: '', tempLastName: '', tableNumber: '1', selectedCategory: 'All', searchQuery: '', cart: [], showModal: false, selectedFood: null, pendingFood: null,
            categories: [{name:'All'}, {name:'Main Course'}, {name:'Desserts'}, {name:'Beverages'}, {name:'Appetizer'}],
            foods: [
                { id: 1, name: 'Burger Steak', category: 'Main Course', price: 150, tempQty: 1, stock: 10, image: "{{ asset('img/burgersteak.png') }}", selectedAddOns: [], addOns: [{name: 'Extra Cheese', price: 20}, {name: 'Bacon', price: 30}, {name: 'Fried Egg', price: 15}] },
                { id: 2, name: 'Carbonara', category: 'Main Course', price: 120, tempQty: 1, stock: 5, image: "{{ asset('img/carbonara.png') }}", selectedAddOns: [], addOns: [{name: 'Extra Bacon', price: 25}, {name: 'Garlic Bread', price: 30}] },
                { id: 4, name: 'Ice Tea', category: 'Beverages', price: 45, tempQty: 1, stock: 20, image: "{{ asset('img/icetea.png') }}", selectedAddOns: [], addOns: [{name: 'Lemon Slice', price: 5}, {name: 'Extra Ice', price: 0}] },
                { id: 5, name: 'Mozarella Sticks', category: 'Appetizer', price: 95, tempQty: 1, stock: 8, image: "{{ asset('img/mozarella.png') }}", selectedAddOns: [], addOns: [{name: 'Marinara Sauce', price: 10}] },
                { id: 6, name: 'Leche Flan', category: 'Desserts', price: 55, tempQty: 1, stock: 8, image: "{{ asset('img/lecheflan.png') }}", selectedAddOns: [], addOns: [{name: 'Extra Caramel', price: 15}] },
            ],
           initCart() {
    const urlParams = new URLSearchParams(window.location.search);
    this.tableNumber = urlParams.get('table') || '1';
    
    // IDAGDAG MO ITO: Para matandaan ng browser ang table kahit lumipat ng page
    localStorage.setItem('ub_current_table', this.tableNumber);
    
    const savedName = localStorage.getItem('ub_customer_name');
    if (savedName) {
        this.customerName = savedName;
    }
    // ... yung iba pang code mo sa baba nito

                
                // Kunin ang cart sa storage kung meron na
                const savedCart = localStorage.getItem('ub_cart');
                if (savedCart) {
                    this.cart = JSON.parse(savedCart);
                }
            },
            saveIdentity() {
                if(this.tempFirstName.trim() && this.tempLastName.trim()) {
                    this.customerName = this.tempFirstName.trim() + ' ' + this.tempLastName.trim();
                    // Save name sa localStorage
                    localStorage.setItem('ub_customer_name', this.customerName);
                } else { alert('Name required.'); }
            },
            get filteredFoods() {
                let filtered = this.selectedCategory === 'All' ? this.foods : this.foods.filter(f => f.category === this.selectedCategory);
                if (this.searchQuery.trim()) {
                    const query = this.searchQuery.toLowerCase();
                    filtered = filtered.filter(f => f.name.toLowerCase().includes(query));
                }
                return filtered;
            },
            
            // FIX: Ito yung nagbabago ng count sa cart icon badge
            get cartItemCount() { 
                return this.cart.reduce((sum, item) => sum + item.qty, 0); // Sum of all quantities
            },

            openCustomizeModal(food) {
                this.selectedFood = JSON.parse(JSON.stringify(food));
                this.selectedFood.selectedAddOns = [];
                this.showModal = true;
            },

            confirmAddOns() {
                if (this.selectedFood) {
                    // Find the original food in foods array
                    const originalFood = this.foods.find(f => f.id === this.selectedFood.id);
                    if (originalFood) {
                        originalFood.selectedAddOns = [...this.selectedFood.selectedAddOns];
                    }
                    this.showModal = false;
                    this.selectedFood = null;
                }
            },

            closeCustomizeModal() {
                this.showModal = false;
                this.selectedFood = null;
            },

            addToCart(food) {
                const addOnPrice = food.selectedAddOns ? food.selectedAddOns.reduce((sum, addon) => sum + addon.price, 0) : 0;
                const totalPrice = (food.price + addOnPrice) * food.tempQty;
                
                const item = {
                    id: food.id,
                    name: food.name,
                    price: food.price,
                    image: food.image,
                    qty: food.tempQty,
                    selectedAddOns: [...food.selectedAddOns],
                    totalPrice: totalPrice,
                    addOns: food.addOns
                };
                
                // Check if item with same id and same add-ons exists
                const existing = this.cart.find(i => i.id === food.id && JSON.stringify(i.selectedAddOns) === JSON.stringify(item.selectedAddOns));
                if (existing) {
                    existing.qty += food.tempQty;
                    existing.totalPrice += totalPrice;
                } else {
                    this.cart.push(item);
                }
                
                // Save to local storage
                localStorage.setItem('ub_cart', JSON.stringify(this.cart));
                
                // Optional: Reset tempQty to 1 after adding
                food.tempQty = 1;
                // Reset selectedAddOns
                food.selectedAddOns = [];

                // Feedback (Optional)
                console.log('Added to cart. Total items:', this.cartItemCount);
            }
        }
    }
    </script>
</body>
</html>