<div class="clay-card p-8" x-data="{ 
    editingId: null, 
    showAddModal: false, 
    showLogModal: false,
    searchQuery: '',
    filterCat: 'All',
    tempStock: 0, 
    tempPrice: 0, 
    tempCost: 0,
    stockLogs: [],
    products: [
        { id: 1, name: 'BURGER STEAK', cat: 'Main Course', stock: 10, price: 150, cost: 100, img: 'burgersteak.png', data: null },
        { id: 2, name: 'CARBONARA', cat: 'Main Course', stock: 15, price: 120, cost: 80, img: 'carbonara.png', data: null },
        { id: 3, name: 'ICE TEA', cat: 'Beverages', stock: 50, price: 45, cost: 20, img: 'icetea.png', data: null },
        { id: 4, name: 'LECHE FLAN', cat: 'Dessert', stock: 20, price: 60, cost: 30, img: 'lecheflan.png', data: null },
        { id: 5, name: 'MOZARELLA STICKS', cat: 'Appetizers', stock: 12, price: 110, cost: 60, img: 'mozarella.png', data: null }
    ],
    newItem: { name: '', cat: '', stock: '', price: '', cost: '', img: '', data: null },

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

    getImageUrl(p) {
        if (p.data) return p.data;
        if (!p.img || p.img.trim() === '') return 'https://via.placeholder.com/150?text=No+Image';
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
        alert('Success: Data validated and saved.');
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

    saveEdit(p) {
        if (parseFloat(this.tempPrice) <= parseFloat(this.tempCost)) {
            return alert('Error: Price must be higher than Cost.');
        }
        let diff = parseInt(this.tempStock) - p.stock;
        if(diff !== 0) this.addLog(p.name, diff > 0 ? '+' + diff : diff, 'Manual Update');
        p.stock = parseInt(this.tempStock) || 0;
        p.price = parseFloat(this.tempPrice) || 0;
        p.cost = parseFloat(this.tempCost) || 0;
        this.editingId = null;
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
}">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 text-left">
        <div>
            <h2 class="text-xl font-black text-gray-800 uppercase tracking-tight">Inventory Vault</h2>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Audit & Stock Control</p>
        </div>
        <div class="flex gap-2">
            <button @click="exportCSV()" class="px-4 py-3 bg-green-600 text-white rounded-xl font-black text-[9px] uppercase shadow-md active:scale-95">Export</button>
            <button @click="showLogModal = true" class="px-4 py-3 bg-gray-800 text-white rounded-xl font-black text-[9px] uppercase shadow-md active:scale-95">Logs</button>
            <button @click="showAddModal = true" class="px-6 py-3 bg-red-900 text-white rounded-xl font-black text-[9px] uppercase shadow-md active:scale-95">New Product</button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="relative text-left md:col-span-2">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" x-model="searchQuery" placeholder="Search product..." class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-3 pl-12 pr-4 text-xs font-bold outline-none">
        </div>
        <select x-model="filterCat" class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-3 px-4 text-xs font-bold outline-none cursor-pointer">
            <option value="All">All Categories</option>
            <option value="Main Course">Main Course</option>
            <option value="Appetizers">Appetizers</option>
            <option value="Dessert">Dessert</option>
            <option value="Beverages">Beverages</option>
        </select>
    </div>

    <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-50 font-black text-gray-400 uppercase text-[9px] tracking-widest">
                <tr>
                    <th class="px-6 py-5">Product</th>
                    <th class="px-6 py-5 text-center">Stock</th>
                    <th class="px-6 py-5 text-center">Cost</th>
                    <th class="px-6 py-5 text-center">Price</th>
                    <th class="px-6 py-5 text-center">Margin</th>
                    <th class="px-6 py-5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <template x-for="p in filteredInventory" :key="p.id">
                    <tr class="hover:bg-gray-50 transition-none">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4 text-left">
                                <div class="w-10 h-10 rounded-lg border overflow-hidden bg-gray-50">
                                    <img :src="getImageUrl(p)" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-black text-gray-800 uppercase text-[10px]" x-text="p.name"></p>
                                    <p class="text-[8px] text-gray-400 font-bold" x-text="p.cat"></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col items-center justify-center">
                                <template x-if="editingId === p.id">
                                    <input type="number" x-model="tempStock" class="w-16 border rounded text-center font-bold outline-none bg-yellow-50">
                                </template>
                                <template x-if="editingId !== p.id">
                                    <div class="text-center">
                                        <span class="text-sm font-black text-gray-800" x-text="p.stock"></span>
                                        <div class="mt-0.5 min-h-[10px]">
                                            <span x-show="p.stock > 0 && p.stock <= 9" class="text-[7px] text-orange-500 font-black uppercase">Low Stock</span>
                                            <span x-show="p.stock == 0" class="text-[7px] text-red-600 font-black uppercase">Out of Stock</span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-gray-500">
                            <template x-if="editingId === p.id">
                                <input type="number" x-model="tempCost" class="w-16 border rounded text-center outline-none">
                            </template>
                            <span x-show="editingId !== p.id" x-text="'₱' + parseFloat(p.cost).toFixed(2)"></span>
                        </td>
                        <td class="px-6 py-4 text-center font-black text-[#800000]">
                            <template x-if="editingId === p.id">
                                <input type="number" x-model="tempPrice" class="w-16 border rounded text-center outline-none">
                            </template>
                            <span x-show="editingId !== p.id" x-text="'₱' + parseFloat(p.price).toFixed(2)"></span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-green-600">
                            <span x-text="'₱' + (p.price - p.cost).toFixed(2)"></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button x-show="editingId !== p.id" @click="editingId = p.id; tempStock = p.stock; tempPrice = p.price; tempCost = p.cost" class="p-2 text-blue-500 bg-blue-50 rounded-lg active:scale-95"><i class="fa-solid fa-pen"></i></button>
                                <button x-show="editingId === p.id" @click="saveEdit(p)" class="p-2 bg-green-500 text-white rounded-lg active:scale-95"><i class="fa-solid fa-check"></i></button>
                                <button @click="if(confirm('Delete?')) products = products.filter(i => i.id !== p.id)" class="p-2 text-red-500 bg-red-50 rounded-lg active:scale-95"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/60"></div>
        
        <div class="bg-white w-full max-w-xs p-6 rounded-[2.5rem] relative z-10 text-left shadow-2xl">
            <div class="flex justify-between items-center mb-4">
              
                <button @click="showAddModal = false" class="text-red-500"><i class="fa-solid fa-circle-xmark text-lg"></i></button>
            </div>
            
            <h3 class="text-[11px] font-black uppercase text-gray-800 mb-6 text-center tracking-widest">New Product Entry</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-1">Product Name</label>
                    <input type="text" x-model="newItem.name" class="w-full border border-gray-100 rounded-xl px-4 py-2 text-xs font-bold outline-none bg-gray-50/50">
                </div>
                <div>
                    <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-1">Category</label>
                    <select x-model="newItem.cat" class="w-full border border-gray-100 rounded-xl px-4 py-2 text-xs font-bold outline-none bg-gray-50/50">
                        <option value="">Select...</option>
                        <option value="Main Course">Main Course</option>
                        <option value="Appetizers">Appetizers</option>
                        <option value="Dessert">Dessert</option>
                        <option value="Beverages">Beverages</option>
                    </select>
                </div>
                <div>
                    <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-1">Image Attachment</label>
                    <div class="flex gap-2 items-center mt-1">
                        <input type="file" id="fIn" class="hidden" @change="handleFileUpload($event)" accept="image/*">
                        <button type="button" @click="document.getElementById('fIn').click()" class="flex-1 border border-gray-200 bg-gray-50/50 rounded-xl px-4 py-2 text-[10px] font-bold text-gray-500 truncate">
                            <i class="fa-solid fa-folder-open mr-2"></i>
                            <span x-text="newItem.img ? newItem.img : 'Attach File'"></span>
                        </button>
                        <div class="w-10 h-10 rounded-lg border bg-gray-100 flex-shrink-0">
                            <img :src="newItem.data || 'https://via.placeholder.com/50'" class="w-full h-full object-cover rounded-lg">
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-1">Stock</label>
                        <input type="number" x-model="newItem.stock" class="w-full border border-gray-100 rounded-xl px-4 py-2 text-xs font-bold bg-gray-50/50">
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-1">Cost (₱)</label>
                        <input type="number" x-model="newItem.cost" class="w-full border border-gray-100 rounded-xl px-4 py-2 text-xs font-bold bg-gray-50/50">
                    </div>
                </div>
                <div>
                    <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-1">Price (₱) - <span class="text-green-600">Must be > Cost</span></label>
                    <input type="number" x-model="newItem.price" class="w-full border border-gray-100 rounded-xl px-4 py-2 text-xs font-bold bg-gray-50/50">
                </div>
            </div>

            <button @click="addItem()" class="w-full mt-8 py-3.5 bg-[#800000] text-white rounded-2xl font-black text-[10px] uppercase shadow-lg active:scale-95">
                Confirm Add
            </button>
        </div>
    </div>

    <div x-show="showLogModal" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center bg-black/40 p-4">
        <div class="bg-white w-full max-w-lg p-8 rounded-[2rem] shadow-2xl flex flex-col max-h-[80vh] text-left">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Audit Logs</h3>
                <button @click="showLogModal = false" class="text-red-500"><i class="fa-solid fa-circle-xmark text-xl"></i></button>
            </div>
            <div class="overflow-y-auto">
                <table class="w-full text-[10px]">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="p-3">Timestamp</th>
                            <th class="p-3">Product</th>
                            <th class="p-3 text-center">Qty</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="log in stockLogs">
    <tr class="border-b border-gray-50">
        <td class="p-3 text-gray-500 font-bold whitespace-nowrap" x-text="log.date"></td>
        <td class="p-3 font-bold uppercase" x-text="log.name"></td>
        <td class="p-3 font-black text-center" :class="log.qty.toString().includes('+') ? 'text-green-600' : 'text-red-600'" x-text="log.qty"></td>
        <td class="p-3 italic text-gray-400 text-right" x-text="log.action"></td>
    </tr>
</template> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>