<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">Urlaub / Betriebsurlaub</h1>

        <div v-if="$page.props.flash?.success" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4">{{ $page.props.flash.success }}</div>

        <div class="bg-white rounded-lg shadow overflow-hidden p-6 mb-6">
            <h2 class="font-semibold mb-4">Urlaubszeitraum hinzufügen</h2>
            <form @submit.prevent="add" class="flex items-end gap-4 flex-wrap">
                <div>
                    <label class="block text-xs font-medium text-stone-600 mb-1">Von</label>
                    <input type="date" v-model="form.start_date" required class="border border-stone-300 rounded px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-stone-600 mb-1">Bis</label>
                    <input type="date" v-model="form.end_date" required class="border border-stone-300 rounded px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-stone-600 mb-1">Grund (optional)</label>
                    <input type="text" v-model="form.reason" placeholder="z. B. Sommerurlaub" class="border border-stone-300 rounded px-3 py-2 text-sm w-56" />
                </div>
                <button type="submit" class="bg-amber-600 hover:bg-amber-500 text-white font-semibold px-4 py-2 rounded-lg transition text-sm">
                    Hinzufügen
                </button>
            </form>
            <p v-if="form.errors.start_date || form.errors.end_date" class="text-red-500 text-xs mt-2">{{ form.errors.start_date || form.errors.end_date }}</p>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm" v-if="vacations.length">
                <thead class="bg-stone-50 text-left">
                    <tr>
                        <th class="px-4 py-3 font-medium">Von</th>
                        <th class="px-4 py-3 font-medium">Bis</th>
                        <th class="px-4 py-3 font-medium">Grund</th>
                        <th class="px-4 py-3 font-medium">Aktion</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    <tr v-for="v in vacations" :key="v.id">
                        <td class="px-4 py-3">{{ v.start_date }}</td>
                        <td class="px-4 py-3">{{ v.end_date }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ v.reason || '–' }}</td>
                        <td class="px-4 py-3">
                            <button @click="remove(v.id)" class="text-red-600 hover:text-red-500 text-sm">Löschen</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p v-else class="p-6 text-stone-500 text-sm">Keine Urlaubszeiträume hinterlegt.</p>
        </div>
    </div>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3';

defineProps({ vacations: Array });

const form = useForm({ start_date: '', end_date: '', reason: '' });

function add() {
    form.post(route('admin.vacations.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

function remove(id) {
    router.delete(route('admin.vacations.destroy', id), { preserveScroll: true });
}
</script>
