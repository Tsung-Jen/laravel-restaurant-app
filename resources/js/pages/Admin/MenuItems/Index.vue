<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Artikel</h1>
            <div class="flex gap-2">
                <Link :href="route('admin.ordering.menu-items.import')" class="border border-amber-600 text-amber-700 hover:bg-amber-50 px-4 py-2 rounded-lg text-sm font-medium">CSV Import</Link>
                <Link :href="route('admin.ordering.menu-items.create')" class="bg-amber-600 hover:bg-amber-500 text-white px-4 py-2 rounded-lg text-sm font-medium">Neu erstellen</Link>
            </div>
        </div>

        <div v-if="$page.props.flash?.success" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4 text-sm">
            {{ $page.props.flash.success }}
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b border-stone-200">
                <input type="text" v-model="filters.search" @input="applyFilters" placeholder="Suchen..." class="border border-stone-300 rounded px-3 py-1.5 text-sm w-full max-w-xs" />
            </div>

            <table class="w-full text-sm">
                <thead class="bg-stone-50 text-left">
                    <tr>
                        <th class="px-4 py-3 font-medium">Nr.</th>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Kategorie</th>
                        <th class="px-4 py-3 font-medium">Preis</th>
                        <th class="px-4 py-3 font-medium">Aktiv</th>
                        <th class="px-4 py-3 font-medium">Sortierung</th>
                        <th class="px-4 py-3 font-medium">Aktionen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    <tr v-for="item in menuItems.data" :key="item.id">
                        <td class="px-4 py-3 font-mono text-xs text-stone-500">{{ item.item_number }}</td>
                        <td class="px-4 py-3 font-medium">{{ item.name }}</td>
                        <td class="px-4 py-3 text-stone-500">{{ item.category?.name }}</td>
                        <td class="px-4 py-3">&euro; {{ Number(item.price).toFixed(2) }}</td>
                        <td class="px-4 py-3">{{ item.is_active ? 'Ja' : 'Nein' }}</td>
                        <td class="px-4 py-3 text-stone-500">{{ item.sort_order }}</td>
                        <td class="px-4 py-3 flex gap-2">
                            <Link :href="route('admin.ordering.menu-items.edit', item.id)" class="text-amber-600 hover:text-amber-800 text-xs">Bearbeiten</Link>
                            <button @click="destroy(item)" class="text-red-500 hover:text-red-700 text-xs">Löschen</button>
                        </td>
                    </tr>
                    <tr v-if="menuItems.data.length === 0">
                        <td colspan="7" class="px-4 py-8 text-center text-stone-400">Keine Artikel gefunden</td>
                    </tr>
                </tbody>
            </table>

            <div v-if="menuItems.last_page > 1" class="p-4 border-t border-stone-200 flex justify-center gap-2 text-sm">
                <Link v-for="page in menuItems.last_page" :key="page" :href="menuItems.path + '?page=' + page" class="px-3 py-1 rounded hover:bg-stone-100" :class="{'bg-amber-600 text-white': page === menuItems.current_page}">{{ page }}</Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    menuItems: Object,
    filters: Object,
});

const filters = ref({
    search: props.filters?.search || '',
});

let debounceTimeout;
function applyFilters() {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        router.get(route('admin.ordering.menu-items.index'), filters.value, { preserveState: true });
    }, 300);
}

function destroy(item) {
    if (confirm('Artikel löschen?')) {
        router.delete(route('admin.ordering.menu-items.destroy', item.id), { preserveScroll: true });
    }
}
</script>
