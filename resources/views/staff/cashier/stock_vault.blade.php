<div class="w-full space-y-6" x-data="inventoryApp()">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-left">
        <div>
            <h2 class="text-2xl md:text-3xl font-black text-slate-800 uppercase tracking-tighter">Inventory Vault</h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Audit & Stock Control</p>
        </div>

        <div class="flex flex-wrap md:flex-nowrap items-center gap-3 w-full md:w-auto">
            <button @click="exportCSV()" class="flex-1 md:flex-none px-4 py-3 bg-green-600 text-white rounded-xl font-black text-[10px] uppercase tracking-wider shadow-md hover:bg-green-700 active:scale-95 transition-all text-center">Export</button>
            <button @click="showLogModal = true" class="flex-1 md:flex-none px-4 py-3 bg-slate-800 text-white rounded-xl font-black text-[10px] uppercase tracking-wider shadow-md hover:bg-slate-900 active:scale-95 transition-all text-center">Logs</button>
            <button @click="showAddModal = true" class="w-full md:w-auto px-6 py-3 bg-[#800000] text-white rounded-xl font-black text-[10px] uppercase tracking-wider shadow-md hover:bg-red-900 active:scale-95 transition-all text-center">New Product</button>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-4 md:p-6">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mb-6">
            <div class="relative text-left sm:col-span-2">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" x-model="searchQuery" placeholder="Search product..." class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-12 pr-4 text-xs font-bold outline-none focus:border-slate-400 focus:ring-0 transition-all">
            </div>
            <select x-model="filterCat" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-xs font-bold outline-none cursor-pointer focus:border-slate-400 focus:ring-0 transition-all">
                <option value="All">All Categories</option>
                <option value="Main Course">Main Course</option>
                <option value="Appetizers">Appetizers</option>
                <option value="Dessert">Dessert</option>
                <option value="Beverages">Beverages</option>
            </select>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-xs min-w-full">
                <thead class="bg-slate-50 font-black text-slate-400 uppercase text-[9px] tracking-widest border-b border-slate-100 hidden md:table-header-group">
                    <tr>
                        <th class="px-4 py-4 whitespace-nowrap rounded-tl-xl">Product</th>
                        <th class="px-4 py-4 text-center whitespace-nowrap">Stock</th>
                        <th class="px-4 py-4 text-center whitespace-nowrap">Cost</th>
                        <th class="px-4 py-4 text-center whitespace-nowrap">Price</th>
                        <th class="px-4 py-4 text-center whitespace-nowrap">Margin</th>
                        <th class="px-4 py-4 text-right whitespace-nowrap rounded-tr-xl">Action</th>
                    </tr>
                </thead>
                <tbody class="block md:table-row-group space-y-4 md:space-y-0 p-0 md:divide-y md:divide-slate-50">
                    <template x-for="p in filteredInventory" :key="p.id">
                        <tr class="block md:table-row bg-white border border-slate-200 md:border-none rounded-xl md:rounded-none shadow-sm md:shadow-none hover:bg-slate-50/50 transition-colors mb-4 md:mb-0">
                            
                            <td class="block md:table-cell px-4 py-4 border-b border-slate-50 md:border-none">
                                <div class="flex items-center gap-4 text-left">
                                    <div class="w-12 h-12 rounded-lg border border-slate-200 overflow-hidden bg-white shrink-0 shadow-sm">
                                        <img :src="getImageUrl(p)" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-black text-slate-800 uppercase text-xs truncate" x-text="p.name"></p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider truncate" x-text="p.cat"></p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3 md:py-4 border-b border-slate-50 md:border-none">
                                <div class="flex items-center justify-between md:block">
                                    <span class="md:hidden text-[10px] font-black text-slate-400 uppercase tracking-widest">Stock</span>
                                    <div class="text-right md:text-center w-auto md:w-full">
                                        <div class="flex flex-col items-end md:items-center">
                                            <span class="text-sm font-black text-slate-800 leading-none" x-text="p.stock"></span>
                                            <div class="mt-1 flex justify-center w-full" x-show="p.stock <= 9">
                                                <span x-show="p.stock > 0" class="text-[7px] text-orange-500 font-bold uppercase bg-orange-50 border border-orange-100 px-2 py-0.5 rounded-full whitespace-nowrap">Low Stock</span>
                                                <span x-show="p.stock == 0" class="text-[7px] text-red-600 font-bold uppercase bg-red-50 border border-red-100 px-2 py-0.5 rounded-full whitespace-nowrap">Out of Stock</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="flex md:table-cell justify-between items-center px-4 py-3 md:py-4 border-b border-slate-50 md:border-none font-bold text-slate-500">
                                <span class="md:hidden text-[10px] font-black text-slate-400 uppercase tracking-widest">Cost</span>
                                <div class="text-right md:text-center">
                                    <span x-text="'₱' + parseFloat(p.cost).toFixed(2)"></span>
                                </div>
                            </td>

                            <td class="flex md:table-cell justify-between items-center px-4 py-3 md:py-4 border-b border-slate-50 md:border-none font-black text-[#800000]">
                                <span class="md:hidden text-[10px] font-black text-slate-400 uppercase tracking-widest">Price</span>
                                <div class="text-right md:text-center">
                                    <span x-text="'₱' + parseFloat(p.price).toFixed(2)"></span>
                                </div>
                            </td>

                            <td class="flex md:table-cell justify-between items-center px-4 py-3 md:py-4 border-b border-slate-50 md:border-none font-bold text-green-600">
                                <span class="md:hidden text-[10px] font-black text-slate-400 uppercase tracking-widest">Margin</span>
                                <div class="text-right md:text-center">
                                    <span class="bg-green-50 text-green-700 px-3 py-1 rounded-lg inline-block" x-text="'₱' + (p.price - p.cost).toFixed(2)"></span>
                                </div>
                            </td>

                            <td class="flex md:table-cell justify-between items-center px-4 py-4 md:py-4 bg-slate-50/50 md:bg-transparent rounded-b-xl md:rounded-none">
                                <span class="md:hidden text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</span>
                                <div class="flex justify-end gap-2">
                                    <button @click="openEditModal(p)" class="w-8 h-8 flex items-center justify-center text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-600 hover:text-white active:scale-95 transition-all shadow-sm"><i class="fa-solid fa-pen text-xs"></i></button>
                                    <button @click="if(confirm('Are you sure you want to delete this product?')) { addLog(p.name, '-' + p.stock, 'Deleted Item'); products = products.filter(i => i.id !== p.id); }" class="w-8 h-8 flex items-center justify-center text-red-500 bg-red-50 rounded-lg hover:bg-red-500 hover:text-white active:scale-95 transition-all shadow-sm"><i class="fa-solid fa-trash text-xs"></i></button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div x-show="filteredInventory.length === 0" class="py-12 text-center" style="display: none;">
            <i class="fa-solid fa-box-open text-slate-200 text-4xl mb-4"></i>
            <h3 class="text-lg font-black text-slate-700 uppercase">No products found</h3>
            <p class="text-slate-400 text-xs mt-1">Adjust your search or add a new product.</p>
        </div>
    </div>

    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" x-show="showAddModal" x-transition.opacity></div>
        
        <div class="bg-white w-full max-w-sm sm:max-w-md p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] relative z-10 text-left shadow-2xl max-h-[90vh] overflow-y-auto" x-show="showAddModal" x-transition.scale.90>
            <div class="flex justify-end items-center mb-2">
                <button @click="showAddModal = false" class="text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-full w-8 h-8 flex items-center justify-center transition-all"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            
            <h3 class="text-[11px] font-black uppercase text-slate-800 mb-6 text-center tracking-widest">New Product Entry</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Product Name</label>
                    <input type="text" x-model="newItem.name" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold outline-none bg-slate-50 focus:bg-white focus:border-[#800000] focus:ring-2 focus:ring-[#800000]/10 transition-all">
                </div>
                <div>
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Category</label>
                    <select x-model="newItem.cat" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold outline-none bg-slate-50 focus:bg-white focus:border-[#800000] focus:ring-2 focus:ring-[#800000]/10 transition-all cursor-pointer">
                        <option value="">Select...</option>
                        <option value="Main Course">Main Course</option>
                        <option value="Appetizers">Appetizers</option>
                        <option value="Dessert">Dessert</option>
                        <option value="Beverages">Beverages</option>
                    </select>
                </div>
                <div>
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Image Attachment</label>
                    <div class="flex gap-3 items-center mt-1">
                        <input type="file" id="fIn" class="hidden" @change="handleFileUpload($event)" accept="image/*">
                        <button type="button" @click="document.getElementById('fIn').click()" class="flex-1 border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-[10px] font-bold text-slate-500 truncate hover:bg-slate-100 hover:text-slate-700 transition-colors text-left flex items-center">
                            <i class="fa-solid fa-image mr-2 text-slate-400"></i>
                            <span x-text="newItem.img ? newItem.img : 'Browse files...'"></span>
                        </button>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl border border-slate-200 bg-slate-50 flex-shrink-0 overflow-hidden shadow-inner">
                            <img x-show="newItem.data" :src="newItem.data" class="w-full h-full object-cover">
                            <div x-show="!newItem.data" class="w-full h-full flex items-center justify-center text-slate-300">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Initial Stock</label>
                        <input type="number" x-model="newItem.stock" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold bg-slate-50 focus:bg-white focus:border-[#800000] focus:ring-2 focus:ring-[#800000]/10 transition-all">
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Unit Cost (₱)</label>
                        <input type="number" step="0.01" x-model="newItem.cost" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold bg-slate-50 focus:bg-white focus:border-[#800000] focus:ring-2 focus:ring-[#800000]/10 transition-all">
                    </div>
                </div>
                <div>
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Selling Price (₱) <span class="text-green-500 lowercase normal-case text-[9px]">(Must be > Cost)</span></label>
                    <input type="number" step="0.01" x-model="newItem.price" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold bg-slate-50 focus:bg-white focus:border-[#800000] focus:ring-2 focus:ring-[#800000]/10 transition-all text-[#800000]">
                </div>
            </div>

            <button @click="addItem()" class="w-full mt-8 py-3.5 sm:py-4 bg-[#800000] text-white rounded-xl font-black text-[10px] sm:text-xs uppercase tracking-widest shadow-lg shadow-red-900/20 active:scale-[0.98] transition-all hover:bg-red-900">
                Confirm & Add Product
            </button>
        </div>
    </div>

    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" x-show="showEditModal" x-transition.opacity></div>
        
        <div class="bg-white w-full max-w-sm sm:max-w-md p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] relative z-10 text-left shadow-2xl max-h-[90vh] overflow-y-auto" x-show="showEditModal" x-transition.scale.90>
            <div class="flex justify-end items-center mb-2">
                <button @click="showEditModal = false" class="text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-full w-8 h-8 flex items-center justify-center transition-all"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            
            <h3 class="text-[11px] font-black uppercase text-slate-800 mb-6 text-center tracking-widest">Edit Product Entry</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Product Name</label>
                    <input type="text" x-model="editItem.name" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold outline-none bg-slate-50 focus:bg-white focus:border-[#800000] focus:ring-2 focus:ring-[#800000]/10 transition-all">
                </div>
                <div>
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Category</label>
                    <select x-model="editItem.cat" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold outline-none bg-slate-50 focus:bg-white focus:border-[#800000] focus:ring-2 focus:ring-[#800000]/10 transition-all cursor-pointer">
                        <option value="Main Course">Main Course</option>
                        <option value="Appetizers">Appetizers</option>
                        <option value="Dessert">Dessert</option>
                        <option value="Beverages">Beverages</option>
                    </select>
                </div>
                <div>
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Image Attachment (Optional)</label>
                    <div class="flex gap-3 items-center mt-1">
                        <input type="file" id="fEditIn" class="hidden" @change="handleEditFileUpload($event)" accept="image/*">
                        <button type="button" @click="document.getElementById('fEditIn').click()" class="flex-1 border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-[10px] font-bold text-slate-500 truncate hover:bg-slate-100 hover:text-slate-700 transition-colors text-left flex items-center">
                            <i class="fa-solid fa-image mr-2 text-slate-400"></i>
                            <span x-text="editItem.img ? editItem.img : 'Update image...'"></span>
                        </button>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl border border-slate-200 bg-slate-50 flex-shrink-0 overflow-hidden shadow-inner">
                            <img x-show="editItem.data || editItem.img" :src="getImageUrl(editItem)" class="w-full h-full object-cover">
                            <div x-show="!editItem.data && (!editItem.img || editItem.img.trim() === '')" class="w-full h-full flex items-center justify-center text-slate-300">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Stock</label>
                        <input type="number" x-model="editItem.stock" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold bg-slate-50 focus:bg-white focus:border-[#800000] focus:ring-2 focus:ring-[#800000]/10 transition-all">
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Unit Cost (₱)</label>
                        <input type="number" step="0.01" x-model="editItem.cost" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold bg-slate-50 focus:bg-white focus:border-[#800000] focus:ring-2 focus:ring-[#800000]/10 transition-all">
                    </div>
                </div>
                <div>
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Selling Price (₱) <span class="text-green-500 lowercase normal-case text-[9px]">(Must be > Cost)</span></label>
                    <input type="number" step="0.01" x-model="editItem.price" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold bg-slate-50 focus:bg-white focus:border-[#800000] focus:ring-2 focus:ring-[#800000]/10 transition-all text-[#800000]">
                </div>
            </div>

            <button @click="saveEdit()" class="w-full mt-8 py-3.5 sm:py-4 bg-[#800000] text-white rounded-xl font-black text-[10px] sm:text-xs uppercase tracking-widest shadow-lg shadow-red-900/20 active:scale-[0.98] transition-all hover:bg-red-900">
                Save Changes
            </button>
        </div>
    </div>

    <div x-show="showLogModal" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" x-show="showLogModal" x-transition.opacity></div>
        
        <div class="bg-white w-full max-w-lg p-6 sm:p-8 rounded-[2rem] shadow-2xl flex flex-col max-h-[85vh] text-left relative z-10" x-show="showLogModal" x-transition.scale.90>
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-black text-slate-800 uppercase text-xs tracking-widest flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-list text-slate-400"></i> Audit Logs
                </h3>
                <button @click="showLogModal = false" class="text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-full w-8 h-8 flex items-center justify-center transition-all"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            
            <div class="overflow-y-auto overflow-x-auto pr-2 sm:pr-4 -mr-2 sm:-mr-4 custom-scrollbar">
                <table class="w-full text-[10px] min-w-[400px]">
                    <thead class="bg-white sticky top-0 z-10 after:content-[''] after:absolute after:bottom-0 after:left-0 after:right-0 after:border-b after:border-slate-100">
                        <tr>
                            <th class="p-3 pl-0 text-slate-400 font-black uppercase tracking-widest text-left">Timestamp</th>
                            <th class="p-3 text-slate-400 font-black uppercase tracking-widest text-left">Product</th>
                            <th class="p-3 text-center text-slate-400 font-black uppercase tracking-widest">Qty</th>
                            <th class="p-3 pr-0 text-right text-slate-400 font-black uppercase tracking-widest">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <template x-for="log in stockLogs">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-3 pl-0 text-slate-400 font-bold whitespace-nowrap text-[9px]" x-text="log.date"></td>
                                <td class="p-3 font-bold uppercase text-slate-800" x-text="log.name"></td>
                                <td class="p-3 font-black text-center text-xs" 
                                    :class="(log.qty.toString().includes('+') || log.action === 'Initial Entry') ? 'text-green-600' : 'text-red-600'" 
                                    x-text="log.qty">
                                </td>
                                <td class="p-3 pr-0 text-right">
                                    <span class="bg-slate-50 text-slate-500 font-bold px-2 py-1 rounded-md text-[8px] uppercase tracking-wider" x-text="log.action"></span>
                                </td>
                            </tr>
                        </template> 
                        
                        <tr x-show="stockLogs.length === 0">
                            <td colspan="4" class="p-8 text-center">
                                <div class="border-dashed border-2 border-slate-100 rounded-2xl p-6 bg-slate-50/50">
                                    <i class="fa-solid fa-clock-rotate-left text-2xl text-slate-300 mb-2"></i>
                                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[9px]">No logs recorded yet</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        // --- CENTRAL STORE ---
        // Ito ang "Brain" na nagdudugtong sa Service Hub at Vault
        Alpine.store('inventory', {
            products: [
                { id: 1, name: 'BURGER STEAK', cat: 'Main Course', stock: 10, price: 150, cost: 100, img: 'burgersteak.png', data: null },
                { id: 2, name: 'CARBONARA', cat: 'Main Course', stock: 15, price: 120, cost: 80, img: 'carbonara.png', data: null },
                { id: 3, name: 'ICE TEA', cat: 'Beverages', stock: 50, price: 45, cost: 20, img: 'icetea.png', data: null },
                { id: 4, name: 'LECHE FLAN', cat: 'Dessert', stock: 20, price: 60, cost: 30, img: 'lecheflan.png', data: null },
                { id: 5, name: 'MOZARELLA STICKS', cat: 'Appetizers', stock: 12, price: 110, cost: 60, img: 'mozarella.png', data: null }
            ],
            // Function na tatawagin ng Service Hub kapag may benta
            updateStock(productId, qty) {
                let p = this.products.find(i => i.id === productId);
                if (p) {
                    p.stock -= qty;
                    // Nagpapadala ng signal sa inventoryApp para i-log ang benta
                    window.dispatchEvent(new CustomEvent('stock-sold', { detail: { name: p.name, qty: qty } }));
                }
            }
        });

        // --- VAULT APP LOGIC ---
        Alpine.data('inventoryApp', () => ({
            showAddModal: false, 
            showEditModal: false, 
            showLogModal: false,
            searchQuery: '',
            filterCat: 'All',
            stockLogs: [],

            // KONEKTA SA GLOBAL STORE
            get products() {
                return Alpine.store('inventory').products;
            },
            set products(val) {
                Alpine.store('inventory').products = val;
            },

            // Listener para sa benta mula sa POS para ma-log dito sa Vault
            init() {
                window.addEventListener('stock-sold', (e) => {
                    this.addLog(e.detail.name, `-${e.detail.qty}`, 'POS Sale');
                });
            },

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
                
                let s = parseInt(this.newItem.stock);
                let c = parseFloat(this.newItem.cost);
                let p = parseFloat(this.newItem.price);

                if (isNaN(s) || s < 0) return alert('Error: Stock must be 0 or higher.');
                if (isNaN(c) || c <= 0) return alert('Error: Cost must be a positive number.');
                if (isNaN(p) || p <= 0) return alert('Error: Selling Price must be a positive number.');
                if (p <= c) return alert('Action Denied: Selling Price must be higher than Cost.');

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
                const fullStamp = `${now.toISOString().split('T')[0]} | ${now.toLocaleTimeString()}`;

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