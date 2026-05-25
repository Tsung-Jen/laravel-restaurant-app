<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">Feiertage</h1>

        <div v-if="$page.props.flash?.success" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4">{{ $page.props.flash.success }}</div>

        <div class="bg-white rounded-lg shadow overflow-hidden p-6 mb-6">
            <h2 class="font-semibold mb-4">Neuen Feiertag hinzufügen</h2>
            <form @submit.prevent="add" class="flex items-end gap-4">
                <div>
                    <label class="block text-xs font-medium text-stone-600 mb-1">Datum</label>
                    <input type="date" v-model="form.date" required class="border border-stone-300 rounded px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-stone-600 mb-1">Name</label>
                    <input type="text" v-model="form.name" required placeholder="z. B. Tag der Arbeit" class="border border-stone-300 rounded px-3 py-2 text-sm w-64" />
                </div>
                <button type="submit" class="bg-amber-600 hover:bg-amber-500 text-white font-semibold px-4 py-2 rounded-lg transition text-sm">
                    Hinzufügen
                </button>
            </form>
            <p v-if="form.errors.date" class="text-red-500 text-xs mt-2">{{ form.errors.date }}</p>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm" v-if="holidays.length">
                <thead class="bg-stone-50 text-left">
                    <tr>
                        <th class="px-4 py-3 font-medium">Datum</th>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Aktion</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    <tr v-for="h in holidays" :key="h.id">
                        <td class="px-4 py-3">{{ h.date }}</td>
                        <td class="px-4 py-3">{{ h.name }}</td>
                        <td class="px-4 py-3">
                            <button @click="remove(h.id)" class="text-red-600 hover:text-red-500 text-sm">Löschen</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p v-else class="p-6 text-stone-500 text-sm">Keine Feiertage hinterlegt.</p>
        </div>
    </div>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3';

defineProps({ holidays: Array });

const form = useForm({ date: '', name: '' });

function add() {
    form.post(route('admin.holidays.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

function remove(id) {
    router.delete(route('admin.holidays.destroy', id), { preserveScroll: true });
}
</script>
