<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
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

        .active-category {
            background: #800000 !important;
            color: white !important;
            box-shadow: none !important;
            border: 1px solid #800000;
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

    <div class="fixed bottom-8 right-8 z-50" id="cart-anchor">
         <a :href="'{{ route('customer.cart') }}?table=' + tableNumber" class="cart-floating text-white w-16 h-16 rounded-2xl flex items-center justify-center relative shadow-lg">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            <span x-show="cartItemCount > 0" class="absolute -top-1 -right-1 bg-yellow-400 text-maroon text-[10px] font-black h-6 w-6 flex items-center justify-center rounded-full border-2 border-white shadow-sm" x-text="cartItemCount"></span>
        </a>
    </div>

    <main class="w-full max-w-7xl mx-auto px-4 pt-4 pb-32">
        
        <div class="mb-6">
            <div class="bg-white border border-gray-100 shadow-md rounded-[1.5rem] p-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center overflow-x-auto no-scrollbar flex-1 px-2 py-1 scroll-smooth w-full">
                    <div class="flex space-x-2">
                        <template x-for="cat in categories" :key="cat.name">
                            <button @click="selectedCategory = cat.name" 
                                class="px-5 py-2.5 rounded-xl font-bold text-[10px] sm:text-[11px] uppercase tracking-wider whitespace-nowrap transition-all"
                                :class="selectedCategory === cat.name ? 'active-category' : 'text-gray-400 bg-gray-50 hover:text-maroon'">
                                <span x-text="cat.name"></span>
                            </button>
                        </template>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-gray-50 rounded-2xl px-5 py-2 border border-gray-100">
                    <div class="text-center">
                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Table</p>
                        <p class="text-xl font-black text-maroon leading-none" x-text="tableNumber"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" x-model="searchQuery" placeholder="Search delicious food..." class="w-full bg-transparent outline-none text-sm text-gray-700 font-medium">
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
            <template x-for="(food, index) in filteredFoods" :key="food.id">
                <div class="bg-white rounded-[1.5rem] product-card p-3 flex flex-col border border-gray-50 shadow-sm">
                    <div class="w-full h-32 sm:h-52 bg-gray-50 rounded-[1.2rem] flex items-center justify-center p-2 mb-3 overflow-hidden">
                        <img :src="food.image" :id="'img-' + food.id" class="w-full h-full object-contain drop-shadow-lg transition-transform duration-500 hover:scale-110">
                    </div>

                    <div class="px-0.5 flex flex-col flex-1">
                        <h4 class="font-bold text-[11px] sm:text-lg text-gray-900 leading-tight mb-2 h-7 sm:h-auto line-clamp-2 uppercase" x-text="food.name"></h4>
                        
                        <div class="mt-auto">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm sm:text-xl font-black text-gray-900">₱<span x-text="food.price"></span></span>
                                <div x-show="food.stock > 0" class="flex items-center bg-gray-100 rounded-lg p-0.5">
                                    <button type="button" @click="food.tempQty > 1 ? food.tempQty-- : null" class="w-5 h-5 sm:w-7 sm:h-7 flex items-center justify-center bg-white rounded-md shadow-sm">
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="4" d="M20 12H4"/></svg>
                                    </button>
                                    <span class="w-5 sm:w-8 text-center font-bold text-[10px] sm:text-xs" x-text="food.tempQty"></span>
                                    <button type="button" @click="food.tempQty < food.stock ? food.tempQty++ : null" class="w-5 h-5 sm:w-7 sm:h-7 flex items-center justify-center bg-white rounded-md shadow-sm">
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="4" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div x-show="food.addOns && food.addOns.length > 0" class="mb-3">
                                <button type="button" @click="openCustomizeModal(food)" class="flex items-center justify-between w-full bg-gray-50 border border-gray-100 rounded-lg p-2 hover:bg-gray-100 transition-colors">
                                    <span class="text-[9px] sm:text-sm font-bold text-maroon uppercase">Customize</span>
                                    <svg class="w-3 h-3 text-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                
                                <div x-show="food.selectedAddOns.length > 0" class="mt-1.5 bg-blue-50/80 border-l-2 border-blue-400 rounded-r-lg p-2 space-y-0.5">
                                    <template x-for="addon in food.selectedAddOns" :key="addon.name">
                                        <div class="text-[8px] sm:text-[10px] text-blue-700 flex justify-between">
                                            <span class="truncate pr-1" x-text="'• ' + addon.name"></span>
                                            <span class="font-bold" x-text="'+₱' + addon.price"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <button type="button" @click="addToCart(food)" 
                                    class="w-full btn-maroon text-white py-2 sm:py-3.5 rounded-xl font-bold text-[9px] sm:text-xs uppercase tracking-widest shadow-md">
                                Add to Order
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </main>

    <div x-show="showModal" x-transition class="fixed inset-0 z-[200] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-maroon/50 backdrop-blur-sm"></div>
        
        <div class="bg-white rounded-[2rem] p-6 max-w-md w-full shadow-2xl animate__animated animate__zoomIn relative z-10">
            <div class="text-center mb-6">
                <h3 class="text-lg font-bold text-maroon" x-text="'Customize ' + (selectedFood ? selectedFood.name : '')"></h3>
                <p class="text-xs text-gray-500 mt-1">Select add-ons for this item</p>
            </div>
            
            <div x-show="selectedFood" class="space-y-3 max-h-60 overflow-y-auto mb-6 px-2">
                 <template x-for="addon in (selectedFood ? selectedFood.addOns : [])" :key="addon.name">
                    <label class="flex items-center bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors cursor-pointer border border-gray-100">
                        <input type="checkbox" 
                               :value="addon.name"
                               x-model="tempSelectedNames"
                               class="accent-maroon mr-3 w-5 h-5">
                        <span class="text-sm font-medium text-gray-800" x-text="addon.name"></span>
                        <span class="text-sm text-maroon font-bold ml-auto" x-text="'+₱' + addon.price"></span>
                    </label>
                </template>
            </div>
            
            <div class="flex gap-3">
                <button type="button" @click="confirmAddOns()" class="flex-1 bg-maroon text-white py-3 px-6 rounded-xl font-bold text-sm uppercase tracking-widest shadow-md hover:bg-red-800 transition-colors">
                    Confirm Selection
                </button>
            </div>
        </div>
    </div>

<script>
    function menuApp() {
        return {
            tableNumber: '1', 
            selectedCategory: 'All', 
            searchQuery: '', 
            cart: [], 
            showModal: false, 
            selectedFood: null,
            tempSelectedNames: [],

            categories: [
                {name:'All'}, {name:'Main Course'}, {name:'Desserts'}, {name:'Beverages'}, {name:'Appetizer'}
            ],

            foods: [
                { id: 1, name: 'Burger Steak', category: 'Main Course', price: 150, tempQty: 1, stock: 10, image: "{{ asset('img/burgersteak.png') }}", selectedAddOns: [], addOns: [{name: 'Extra Cheese', price: 20}, {name: 'Bacon', price: 30}, {name: 'Fried Egg', price: 15}] },
                { id: 2, name: 'Carbonara', category: 'Main Course', price: 120, tempQty: 1, stock: 5, image: "{{ asset('img/carbonara.png') }}", selectedAddOns: [], addOns: [{name: 'Extra Bacon', price: 25}, {name: 'Garlic Bread', price: 30}] },
                { id: 4, name: 'Ice Tea', category: 'Beverages', price: 45, tempQty: 1, stock: 20, image: "{{ asset('img/icetea.png') }}", selectedAddOns: [], addOns: [{name: 'Lemon Slice', price: 5}, {name: 'Extra Ice', price: 0}] },
                { id: 5, name: 'Mozarella Sticks', category: 'Appetizer', price: 95, tempQty: 1, stock: 8, image: "{{ asset('img/mozarella.png') }}", selectedAddOns: [], addOns: [{name: 'Marinara Sauce', price: 10}] },
                { id: 6, name: 'Leche Flan', category: 'Desserts', price: 55, tempQty: 1, stock: 8, image: "{{ asset('img/lecheflan.png') }}", selectedAddOns: [], addOns: [{name: 'Extra Caramel', price: 15}] },
            ],

            initCart() {
                const urlParams = new URLSearchParams(window.location.search);
                this.tableNumber = urlParams.get('table') || localStorage.getItem('ub_current_table') || '1';
                localStorage.setItem('ub_current_table', this.tableNumber);
                const savedCart = localStorage.getItem('ub_cart');
                this.cart = savedCart ? JSON.parse(savedCart) : [];
            },

            addToCart(food) {
                const addOnPrice = food.selectedAddOns.reduce((sum, addon) => sum + addon.price, 0);
                const finalUnitPrice = food.price + addOnPrice;
                
                const itemToCart = {
                    id: food.id,
                    name: food.name,
                    price: food.price, // base price
                    unitPrice: finalUnitPrice, // total unit price with addons
                    image: food.image,
                    qty: food.tempQty,
                    // Pinakamahalaga para sa thermal receipt display:
                    selectedAddOns: JSON.parse(JSON.stringify(food.selectedAddOns)),
                    addonName: food.selectedAddOns.length > 0 ? food.selectedAddOns.map(a => a.name).join(', ') : 'Default',
                    totalPrice: finalUnitPrice * food.tempQty
                };
                
                // Unique check base sa ID + Addons para hindi mag-merge ang plain at custom
                const existingIndex = this.cart.findIndex(i => 
                    i.id === itemToCart.id && 
                    i.addonName === itemToCart.addonName
                );

                if (existingIndex !== -1) {
                    this.cart[existingIndex].qty += itemToCart.qty;
                    this.cart[existingIndex].totalPrice += itemToCart.totalPrice;
                } else {
                    this.cart.push(itemToCart);
                }
                
                localStorage.setItem('ub_cart', JSON.stringify(this.cart));
                
                food.tempQty = 1;
                food.selectedAddOns = []; 
               
            },

            get filteredFoods() {
                let filtered = this.selectedCategory === 'All' ? this.foods : this.foods.filter(f => f.category === this.selectedCategory);
                if (this.searchQuery.trim()) {
                    const query = this.searchQuery.toLowerCase();
                    filtered = filtered.filter(f => f.name.toLowerCase().includes(query));
                }
                return filtered;
            },
            
            get cartItemCount() { 
                return this.cart.reduce((sum, item) => sum + item.qty, 0);
            },

            openCustomizeModal(food) {
                this.selectedFood = food;
                this.tempSelectedNames = food.selectedAddOns.map(a => a.name);
                this.showModal = true;
            },

            confirmAddOns() {
                if (this.selectedFood) {
                    // I-update ang selectedAddOns ng item sa main list
                    this.selectedFood.selectedAddOns = this.selectedFood.addOns.filter(addon => 
                        this.tempSelectedNames.includes(addon.name)
                    );
                }
                this.showModal = false;
            },

            closeCustomizeModal() {
                this.showModal = false;
                this.selectedFood = null;
                this.tempSelectedNames = [];
            }
        }
    }
</script>
</body>
</html>