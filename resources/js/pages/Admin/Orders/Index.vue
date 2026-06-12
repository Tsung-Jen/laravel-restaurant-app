<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">Bestellungen</h1>

        <div v-if="$page.props.flash?.success" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4 text-sm">
            {{ $page.props.flash.success }}
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b border-stone-200 flex gap-4">
                <input type="date" v-model="filters.date" @change="applyFilters" class="border border-stone-300 rounded px-3 py-1.5 text-sm" />
                <select v-model="filters.status" @change="applyFilters" class="border border-stone-300 rounded px-3 py-1.5 text-sm">
                    <option value="">Alle Status</option>
                    <option value="pending">Ausstehend</option>
                    <option value="confirmed">Bestätigt</option>
                    <option value="cancelled">Storniert</option>
                </select>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-stone-50 text-left">
                    <tr>
                        <th class="px-4 py-3 font-medium">#</th>
                        <th class="px-4 py-3 font-medium">Telefon</th>
                        <th class="px-4 py-3 font-medium">Abholdatum</th>
                        <th class="px-4 py-3 font-medium">Abholzeit</th>
                        <th class="px-4 py-3 font-medium">Artikel</th>
                        <th class="px-4 py-3 font-medium">Summe</th>
                        <th class="px-4 py-3 font-medium">Zahlung</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Aktionen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    <tr v-for="o in orders.data" :key="o.id">
                        <td class="px-4 py-3 font-mono text-xs">{{ o.id }}</td>
                        <td class="px-4 py-3">{{ o.phone }}</td>
                        <td class="px-4 py-3">{{ o.pickup_date }}</td>
                        <td class="px-4 py-3">{{ o.pickup_time }}</td>
                        <td class="px-4 py-3">{{ o.items_count }}</td>
                        <td class="px-4 py-3 font-semibold">&euro; {{ Number(o.subtotal).toFixed(2) }}</td>
                        <td class="px-4 py-3 capitalize">{{ o.payment_method }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" :class="{
                                'bg-yellow-100 text-yellow-800': o.status === 'pending',
                                'bg-emerald-100 text-emerald-800': o.status === 'confirmed',
                                'bg-red-100 text-red-800': o.status === 'cancelled',
                            }">{{ o.status }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <Link :href="route('admin.ordering.orders.show', o.id)" class="text-amber-600 hover:text-amber-800 text-xs">Details</Link>
                            <button v-if="o.status === 'pending'" @click="updateStatus(o.id, 'confirmed')" class="text-emerald-600 hover:text-emerald-800 text-xs ml-2">Bestätigen</button>
                            <button v-if="o.status !== 'cancelled'" @click="updateStatus(o.id, 'cancelled')" class="text-red-600 hover:text-red-800 text-xs ml-2">Stornieren</button>
                        </td>
                    </tr>
                    <tr v-if="orders.data.length === 0">
                        <td colspan="9" class="px-4 py-8 text-center text-stone-400">Keine Bestellungen gefunden</td>
                    </tr>
                </tbody>
            </table>

            <div v-if="orders.last_page > 1" class="p-4 border-t border-stone-200 flex justify-center gap-2 text-sm">
                <Link v-for="page in orders.last_page" :key="page" :href="orders.path + '?page=' + page" class="px-3 py-1 rounded hover:bg-stone-100" :class="{'bg-amber-600 text-white': page === orders.current_page}">{{ page }}</Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    orders: Object,
    filters: Object,
});

const filters = ref({
    date: props.filters?.date || '',
    status: props.filters?.status || '',
});

function applyFilters() {
    router.get(route('admin.ordering.orders.index'), filters.value, { preserveState: true });
}

function updateStatus(id, status) {
    router.patch(route('admin.ordering.orders.status', id), { status }, { preserveScroll: true });
}
</script>
