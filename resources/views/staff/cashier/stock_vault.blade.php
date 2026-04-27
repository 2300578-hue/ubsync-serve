<div class="space-y-8" x-data="inventoryApp()">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-800 uppercase tracking-tighter">
                Inventory Vault
            </h1>
            <p class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">
                Audit & Stock Control
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <button @click="exportCSV()" class="px-5 py-2.5 bg-green-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-sm hover:bg-green-700 transition-all">
                Export
            </button>
            <button @click="showLogModal = true" class="px-5 py-2.5 bg-slate-800 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-sm hover:bg-slate-900 transition-all">
                Logs
            </button>
            <button @click="showAddModal = true" class="px-6 py-2.5 bg-[#800000] text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg hover:bg-red-800 transition-all">
                + New Product
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <div class="relative md:col-span-9">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" x-model="searchQuery" placeholder="Search product by name..." 
                class="w-full bg-white border border-slate-200 rounded-2xl py-3.5 pl-12 pr-4 text-xs font-bold outline-none focus:ring-4 focus:ring-red-900/5 transition-all shadow-sm">
        </div>
        <div class="md:col-span-3">
            <select x-model="filterCat" 
                class="w-full bg-white border border-slate-200 rounded-2xl py-3.5 px-4 text-xs font-bold outline-none cursor-pointer shadow-sm">
                <option value="All">All Categories</option>
                <option value="Main Course">Main Course</option>
                <option value="Appetizers">Appetizers</option>
                <option value="Dessert">Dessert</option>
                <option value="Beverages">Beverages</option>
            </select>
        </div>
    </div>

    <div class="w-full bg-white rounded-[2rem] border border-slate-100 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-6 font-black text-slate-400 uppercase text-[10px] tracking-[0.15em]">Product</th>
                        <th class="px-6 py-6 font-black text-slate-400 uppercase text-[10px] tracking-[0.15em] text-center">Stock</th>
                        <th class="px-6 py-6 font-black text-slate-400 uppercase text-[10px] tracking-[0.15em] text-center">Cost</th>
                        <th class="px-6 py-6 font-black text-slate-400 uppercase text-[10px] tracking-[0.15em] text-center">SRP</th>
                        <th class="px-6 py-6 font-black text-slate-400 uppercase text-[10px] tracking-[0.15em] text-center">Margin</th>
                        <th class="px-8 py-6 font-black text-slate-400 uppercase text-[10px] tracking-[0.15em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <template x-for="p in filteredInventory" :key="p.id">
                        <tr class="hover:bg-slate-50/40 transition-all group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl border border-slate-100 overflow-hidden bg-white shadow-sm group-hover:scale-105 transition-transform">
                                        <img :src="getImageUrl(p)" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-800 uppercase text-[12px]" x-text="p.name"></p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5" x-text="p.cat"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center font-black text-slate-800 text-sm" x-text="p.stock"></td>
                            <td class="px-6 py-5 text-center font-bold text-slate-500" x-text="'₱' + parseFloat(p.cost).toFixed(2)"></td>
                            <td class="px-6 py-5 text-center font-black text-red-800" x-text="'₱' + parseFloat(p.price).toFixed(2)"></td>
                            <td class="px-6 py-5 text-center">
                                <span class="bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-lg font-black text-[10px]" x-text="'₱' + (p.price - p.cost).toFixed(2)"></span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="openEditModal(p)" class="w-9 h-9 flex items-center justify-center text-blue-600 bg-blue-50 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm"><i class="fa-solid fa-pen text-xs"></i></button>
                                    <button @click="if(confirm('Delete?')) { products = products.filter(i => i.id !== p.id); }" class="w-9 h-9 flex items-center justify-center text-red-500 bg-red-50 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm"><i class="fa-solid fa-trash text-xs"></i></button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    
                    <tr x-show="filteredInventory.length === 0">
                        <td colspan="6" class="px-6 py-20 text-center text-slate-400">
                            <i class="fa-solid fa-box-open text-4xl mb-4 opacity-20"></i>
                            <p class="text-xs font-black uppercase tracking-[0.2em]">No Inventory Items Found</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" x-show="showAddModal" x-transition.opacity></div>
        <div class="bg-white w-full max-w-md p-8 rounded-[2.5rem] relative z-10 text-left shadow-2xl max-h-[90vh] overflow-y-auto" x-show="showAddModal" x-transition.scale.90>
            <div class="flex justify-end mb-2">
                <button @click="showAddModal = false" class="text-gray-400 hover:text-red-500 bg-gray-50 rounded-full w-8 h-8 flex items-center justify-center transition-all"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <h3 class="text-[11px] font-black uppercase text-gray-800 mb-6 text-center tracking-widest">New Product Entry</h3>
            <div class="space-y-4">
                <div>
                    <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Product Name</label>
                    <input type="text" x-model="newItem.name" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold outline-none bg-gray-50 focus:bg-white focus:border-red-900 transition-all">
                </div>
                <div>
                    <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Category</label>
                    <select x-model="newItem.cat" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold outline-none bg-gray-50 focus:bg-white focus:border-red-900 transition-all cursor-pointer">
                        <option value="">Select...</option>
                        <option value="Main Course">Main Course</option>
                        <option value="Appetizers">Appetizers</option>
                        <option value="Dessert">Dessert</option>
                        <option value="Beverages">Beverages</option>
                    </select>
                </div>
                <div>
                    <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Initial Stock</label>
                    <input type="number" x-model="newItem.stock" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold bg-gray-50 focus:bg-white focus:border-red-900 transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Cost (₱)</label>
                        <input type="number" step="0.01" x-model="newItem.cost" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold bg-gray-50 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Price (₱)</label>
                        <input type="number" step="0.01" x-model="newItem.price" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold bg-gray-50 focus:bg-white transition-all text-[#800000]">
                    </div>
                </div>
            </div>
            <button @click="addItem()" class="w-full mt-8 py-4 bg-[#800000] text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-red-900 transition-all">
                Confirm & Add Product
            </button>
        </div>
    </div>

    <div x-show="showLogModal" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showLogModal = false"></div>
        <div class="bg-white w-full max-w-lg p-8 rounded-[2rem] shadow-2xl relative z-10 max-h-[85vh] flex flex-col" x-show="showLogModal" x-transition.scale.90>
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-list text-gray-400"></i> Audit Logs
                </h3>
                <button @click="showLogModal = false" class="text-gray-400 hover:text-red-500 bg-gray-50 rounded-full w-8 h-8 flex items-center justify-center transition-all"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="overflow-y-auto custom-scrollbar">
                <table class="w-full text-[10px]">
                    <thead class="bg-white sticky top-0 border-b border-gray-100">
                        <tr>
                            <th class="p-3 pl-0 text-gray-400 font-black uppercase text-left">Timestamp</th>
                            <th class="p-3 text-gray-400 font-black uppercase text-left">Product</th>
                            <th class="p-3 text-center text-gray-400 font-black uppercase">Qty</th>
                            <th class="p-3 pr-0 text-right text-gray-400 font-black uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <template x-for="log in stockLogs">
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="p-3 pl-0 text-gray-400 font-bold whitespace-nowrap" x-text="log.date"></td>
                                <td class="p-3 font-bold uppercase text-gray-800" x-text="log.name"></td>
                                <td class="p-3 font-black text-center text-xs" :class="log.qty.includes('+') ? 'text-green-600' : 'text-red-600'" x-text="log.qty"></td>
                                <td class="p-3 pr-0 text-right">
                                    <span class="bg-gray-50 text-gray-500 font-bold px-2 py-1 rounded-md text-[8px] uppercase" x-text="log.action"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('inventoryApp', () => ({
            showAddModal: false, 
            showEditModal: false, 
            showLogModal: false,
            searchQuery: '',
            filterCat: 'All',
            stockLogs: [],
            products: [
                { id: 1, name: 'BURGER STEAK', cat: 'Main Course', stock: 10, price: 150, cost: 100, img: 'burgersteak.png', data: null },
                { id: 2, name: 'CARBONARA', cat: 'Main Course', stock: 15, price: 120, cost: 80, img: 'carbonara.png', data: null },
                { id: 3, name: 'ICE TEA', cat: 'Beverages', stock: 50, price: 45, cost: 20, img: 'icetea.png', data: null },
                { id: 4, name: 'LECHE FLAN', cat: 'Dessert', stock: 20, price: 60, cost: 30, img: 'lecheflan.png', data: null },
                { id: 5, name: 'MOZARELLA STICKS', cat: 'Appetizers', stock: 12, price: 110, cost: 60, img: 'mozarella.png', data: null }
            ],
            newItem: { name: '', cat: '', stock: '', price: '', cost: '', img: '', data: null },
            
            editItem: { id: null, name: '', cat: '', stock: '', price: '', cost: '', img: '', data: null },

            handleFileUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.newItem.data = e.target.result;
                        this.newItem.img = file.name;
                    };
                    reader.readAsDataURL(file);
                }
            },

            handleEditFileUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.editItem.data = e.target.result;
                        this.editItem.img = file.name;
                    };
                    reader.readAsDataURL(file);
                }
            },

            getImageUrl(p) {
                if (p.data) return p.data;
                if (!p.img || p.img.trim() === '') return 'https://placehold.co/150x150?text=No+Image';
                return '/img/' + p.img;
            },

            addItem() {
                if (!this.newItem.name) return alert('Error: Product Name is required.');
                if (!this.newItem.cat) return alert('Error: Please select a Category.');
                if (!this.newItem.data) return alert('Error: Please upload a Product Image.');
                
                let s = parseInt(this.newItem.stock);
                let c = parseFloat(this.newItem.cost);
                let p = parseFloat(this.newItem.price);

                if (isNaN(s) || s < 0) return alert('Error: Stock must be 0 or higher.');
                if (isNaN(c) || c <= 0) return alert('Error: Cost must be a positive number.');
                if (isNaN(p) || p <= 0) return alert('Error: Selling Price must be a positive number.');
                if (p <= c) return alert('Action Denied: Selling Price must be higher than Cost to have a margin.');

                const product = {
                    id: Date.now(),
                    name: this.newItem.name.toUpperCase(),
                    cat: this.newItem.cat,
                    stock: s,
                    price: p,
                    cost: c,
                    img: this.newItem.img,
                    data: this.newItem.data
                };

                this.products.push(product);
                this.addLog(product.name, product.stock, 'Initial Entry');
                this.newItem = { name: '', cat: '', stock: '', price: '', cost: '', img: '', data: null };
                this.showAddModal = false;
            },

            exportCSV() {
                let csv = 'Product,Category,Stock,Cost,Price,Margin\n';
                this.products.forEach(p => {
                    csv += `${p.name},${p.cat},${p.stock},${p.cost},${p.price},${p.price - p.cost}\n`;
                });
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'Inventory_Report.csv';
                a.click();
            },

            openEditModal(product) {
                this.editItem = { ...product };
                this.showEditModal = true;
            },

            saveEdit() {
                let s = parseInt(this.editItem.stock);
                let c = parseFloat(this.editItem.cost);
                let p = parseFloat(this.editItem.price);

                if (isNaN(s) || s < 0) return alert('Error: Stock must be 0 or higher.');
                if (p <= c) return alert('Error: Price must be higher than Cost.');

                const index = this.products.findIndex(prod => prod.id === this.editItem.id);
                
                if (index !== -1) {
                    let originalProduct = this.products[index];
                    
                    let diff = s - originalProduct.stock;
                    if(diff !== 0) this.addLog(originalProduct.name, diff > 0 ? '+' + diff : diff, 'Manual Update');
                    
                    this.products[index] = { 
                        ...this.editItem, 
                        stock: s, 
                        price: p, 
                        cost: c,
                        name: this.editItem.name.toUpperCase()
                    };
                }

                this.showEditModal = false;
            },

            addLog(name, qty, action) {
                const now = new Date();
                const datePart = now.toISOString().split('T')[0];
                const timePart = now.toLocaleTimeString('en-US', { 
                    hour: '2-digit', 
                    minute: '2-digit', 
                    hour12: true 
                });
                const fullStamp = `${datePart} | ${timePart}`;

                this.stockLogs.unshift({
                    date: fullStamp, 
                    name: name,
                    qty: qty,
                    action: action
                });
            },

            get filteredInventory() {
                return this.products.filter(p => {
                    const matchesSearch = p.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                    const matchesCat = this.filterCat === 'All' || p.cat === this.filterCat;
                    return matchesSearch && matchesCat;
                });
            }
        }));
    });
</script>