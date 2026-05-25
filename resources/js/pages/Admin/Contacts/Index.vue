<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">Kontaktnachrichten</h1>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-stone-50 text-left">
                    <tr>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">E-Mail</th>
                        <th class="px-4 py-3 font-medium">Betreff</th>
                        <th class="px-4 py-3 font-medium">Nachricht</th>
                        <th class="px-4 py-3 font-medium">Datum</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    <tr v-for="c in contacts.data" :key="c.id" :class="{'bg-stone-50': !c.read_at}">
                        <td class="px-4 py-3 font-medium" :class="{'text-stone-400': c.read_at}">{{ c.name }}</td>
                        <td class="px-4 py-3 text-stone-500">{{ c.email }}</td>
                        <td class="px-4 py-3">{{ c.subject }}</td>
                        <td class="px-4 py-3 max-w-xs truncate">{{ c.message }}</td>
                        <td class="px-4 py-3 text-stone-500">{{ c.created_at }}</td>
                        <td class="px-4 py-3">
                            <button v-if="!c.read_at" @click="markAsRead(c)" class="text-blue-600 hover:text-blue-800 text-xs">Als gelesen markieren</button>
                            <span v-else class="text-stone-400 text-xs">Gelesen</span>
                        </td>
                    </tr>
                    <tr v-if="contacts.data.length === 0">
                        <td colspan="6" class="px-4 py-8 text-center text-stone-400">Keine Nachrichten</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

defineProps({
    contacts: Object,
});

function markAsRead(contact) {
    router.patch(route('admin.contacts.read', contact.id), {}, { preserveScroll: true });
}
</script>
