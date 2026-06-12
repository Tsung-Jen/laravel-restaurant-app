<template>
    <div>
        <div class="flex items-center gap-4 mb-6">
            <Link :href="route('admin.ordering.orders.index')" class="text-stone-500 hover:text-stone-700">&larr; Zurück</Link>
            <h1 class="text-2xl font-bold">Bestellung #{{ order.id }}</h1>
        </div>

        <div v-if="$page.props.flash?.success" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4 text-sm">
            {{ $page.props.flash.success }}
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-bold mb-4">Bestellte Artikel</h2>
                    <table class="w-full text-sm">
                        <thead class="bg-stone-50 text-left">
                            <tr>
                                <th class="px-4 py-2 font-medium">Nr.</th>
                                <th class="px-4 py-2 font-medium">Name</th>
                                <th class="px-4 py-2 font-medium">Menge</th>
                                <th class="px-4 py-2 font-medium text-right">Preis</th>
                                <th class="px-4 py-2 font-medium text-right">Gesamt</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            <tr v-for="item in order.items" :key="item.id">
                                <td class="px-4 py-2 font-mono text-xs text-stone-500">{{ item.item_number }}</td>
                                <td class="px-4 py-2">
                                    <div>{{ item.item_name }}</div>
                                    <div v-if="item.notes" class="text-xs text-stone-500 italic mt-0.5 flex items-center gap-1">
                                        <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>{{ item.notes }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-2">{{ item.quantity }}</td>
                                <td class="px-4 py-2 text-right">&euro; {{ Number(item.price).toFixed(2) }}</td>
                                <td class="px-4 py-2 text-right font-semibold">&euro; {{ (Number(item.price) * item.quantity).toFixed(2) }}</td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-stone-50 font-semibold">
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-right">Gesamtsumme</td>
                                <td class="px-4 py-2 text-right">&euro; {{ Number(order.subtotal).toFixed(2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-bold mb-4">Details</h2>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-stone-500">Telefon</dt>
                            <dd class="font-medium">{{ order.phone }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-stone-500">Abholdatum</dt>
                            <dd class="font-medium">{{ order.pickup_date }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-stone-500">Abholzeit</dt>
                            <dd class="font-medium">{{ order.pickup_time }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-stone-500">Zahlung</dt>
                            <dd class="font-medium capitalize">{{ order.payment_method }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-stone-500">Status</dt>
                            <dd>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" :class="{
                                    'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                    'bg-emerald-100 text-emerald-800': order.status === 'confirmed',
                                    'bg-red-100 text-red-800': order.status === 'cancelled',
                                }">{{ order.status }}</span>
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-stone-500">Erstellt</dt>
                            <dd class="font-medium text-xs">{{ order.created_at }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-bold mb-4">Status ändern</h2>
                    <div class="space-y-2">
                        <button v-if="order.status === 'pending'" @click="updateStatus('confirmed')" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg text-sm font-medium">Bestätigen</button>
                        <button v-if="order.status !== 'cancelled'" @click="updateStatus('cancelled')" class="w-full bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium">Stornieren</button>
                        <button v-if="order.status === 'cancelled' || order.status === 'confirmed'" @click="updateStatus('pending')" class="w-full border border-stone-300 hover:bg-stone-50 px-4 py-2 rounded-lg text-sm font-medium">Auf ausstehend setzen</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    order: Object,
});

function updateStatus(status) {
    router.patch(route('admin.ordering.orders.status', props.order.id), { status }, { preserveScroll: true });
}
</script>
