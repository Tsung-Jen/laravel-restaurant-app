<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">Reservierungen</h1>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b border-stone-200 flex gap-4">
                <input type="date" v-model="filters.date" @change="applyFilters" class="border border-stone-300 rounded px-3 py-1.5 text-sm" />
                <select v-model="filters.status" @change="applyFilters" class="border border-stone-300 rounded px-3 py-1.5 text-sm">
                    <option value="">Alle Status</option>
                    <option value="open">Offen</option>
                    <option value="confirmed">Bestätigt</option>
                    <option value="cancelled">Storniert</option>
                </select>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-stone-50 text-left">
                    <tr>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">E-Mail</th>
                        <th class="px-4 py-3 font-medium">Telefon</th>
                        <th class="px-4 py-3 font-medium">Datum</th>
                        <th class="px-4 py-3 font-medium">Zeit</th>
                        <th class="px-4 py-3 font-medium">Personen</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Aktionen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    <tr v-for="r in reservations.data" :key="r.id">
                        <td class="px-4 py-3">{{ r.name }}</td>
                        <td class="px-4 py-3 text-stone-500">{{ r.email }}</td>
                        <td class="px-4 py-3 text-stone-500">{{ r.phone }}</td>
                        <td class="px-4 py-3">{{ r.date }}</td>
                        <td class="px-4 py-3">{{ r.time }}</td>
                        <td class="px-4 py-3">{{ r.guests }}</td>
                        <td class="px-4 py-3">
                            <select v-model="r.status" @change="updateStatus(r)" class="border border-stone-300 rounded px-2 py-1 text-xs">
                                <option value="open">Offen</option>
                                <option value="confirmed">Bestätigt</option>
                                <option value="cancelled">Storniert</option>
                            </select>
                        </td>
                        <td class="px-4 py-3">
                            <button @click="destroy(r)" class="text-red-500 hover:text-red-700 text-xs">Löschen</button>
                        </td>
                    </tr>
                    <tr v-if="reservations.data.length === 0">
                        <td colspan="8" class="px-4 py-8 text-center text-stone-400">Keine Reservierungen gefunden</td>
                    </tr>
                </tbody>
            </table>

            <div v-if="reservations.last_page > 1" class="p-4 border-t border-stone-200 flex justify-center gap-2 text-sm">
                <Link v-for="page in reservations.last_page" :key="page" :href="reservations.path + '?page=' + page" class="px-3 py-1 rounded hover:bg-stone-100" :class="{'bg-amber-600 text-white': page === reservations.current_page}">{{ page }}</Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    reservations: Object,
    filters: Object,
});

const filters = ref({
    date: props.filters?.date || '',
    status: props.filters?.status || '',
});

function applyFilters() {
    router.get(route('admin.reservations.index'), filters.value, { preserveState: true });
}

function updateStatus(reservation) {
    router.patch(route('admin.reservations.status', reservation.id), {
        status: reservation.status,
    }, { preserveScroll: true });
}

function destroy(reservation) {
    if (confirm('Reservierung löschen?')) {
        router.delete(route('admin.reservations.destroy', reservation.id), { preserveScroll: true });
    }
}
</script>
