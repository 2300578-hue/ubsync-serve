            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Inventory Stock</h1>
                    <nav class="flex text-sm text-slate-500 mt-2 font-medium uppercase tracking-wide">
                        <span>Ingredients</span> <span class="mx-3 text-slate-300">|</span>
                        <span class="text-red-800 font-bold">Stock Control</span>
                    </nav>
                </div>
            </div>

            <div class="aws-card p-5 overflow-x-auto">
                <table class="min-w-full text-left divide-y divide-slate-200">
                    <thead>
                        <tr class="text-xs uppercase text-slate-500 tracking-[0.2em]">
                            <th class="px-4 py-3">Ingredient</th>
                            <th class="px-4 py-3">Stock</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <template x-for="(item, index) in inventory" :key="item.id">
                            <tr>
                                <td class="px-4 py-4 text-slate-700 font-semibold" x-text="item.name"></td>
                                <td class="px-4 py-4 font-black text-slate-900" x-text="item.stock"></td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-black uppercase" :class="item.stock <= 10 ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700'" x-text="item.stock <= 10 ? 'Low Stock' : 'Good'"></span>
                                </td>
                                <td class="px-4 py-4 flex gap-2">
                                    <button @click="decrementStock(index)" class="px-3 py-2 rounded-md bg-slate-900 text-white text-xs font-bold uppercase tracking-widest hover:bg-slate-800 transition">Use</button>
                                    <button @click="reorderStock(index)" class="px-3 py-2 rounded-md bg-amber-500 text-slate-900 text-xs font-bold uppercase tracking-widest hover:bg-amber-400 transition">Reorder</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>