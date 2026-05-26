<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-stone-500">Heutige Reservierungen</p>
                <p class="text-3xl font-bold text-amber-600">{{ stats.today_reservations }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-stone-500">Offene Reservierungen</p>
                <p class="text-3xl font-bold text-yellow-600">{{ stats.pending_reservations }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-stone-500">Ungelesene Nachrichten</p>
                <p class="text-3xl font-bold text-blue-600">{{ stats.unread_contacts }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-stone-500 mb-1">Mittagstisch (11:30–14:30)</p>
                <p class="text-2xl font-bold mb-3" :class="barColor(sessionStats.lunch.booked, sessionStats.lunch.max)">
                    {{ sessionStats.lunch.booked }} / {{ sessionStats.lunch.max }} Plätze belegt
                </p>
                <div class="w-full bg-stone-200 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full" :class="barFill(sessionStats.lunch.booked, sessionStats.lunch.max)"
                         :style="{ width: barWidth(sessionStats.lunch.booked, sessionStats.lunch.max) }"></div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-stone-500 mb-1">Abendtisch (17:30–23:00)</p>
                <p class="text-2xl font-bold mb-3" :class="barColor(sessionStats.dinner.booked, sessionStats.dinner.max)">
                    {{ sessionStats.dinner.booked }} / {{ sessionStats.dinner.max }} Plätze belegt
                </p>
                <div class="w-full bg-stone-200 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full" :class="barFill(sessionStats.dinner.booked, sessionStats.dinner.max)"
                         :style="{ width: barWidth(sessionStats.dinner.booked, sessionStats.dinner.max) }"></div>
                </div>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-4">Bevorstehende Reservierungen</h2>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-stone-50 text-left">
                    <tr>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Datum</th>
                        <th class="px-4 py-3 font-medium">Uhrzeit</th>
                        <th class="px-4 py-3 font-medium">Personen</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    <tr v-for="r in upcoming_reservations" :key="r.id">
                        <td class="px-4 py-3">{{ r.name }}</td>
                        <td class="px-4 py-3">{{ r.date }}</td>
                        <td class="px-4 py-3">{{ r.time }}</td>
                        <td class="px-4 py-3">{{ r.guests }}</td>
                        <td class="px-4 py-3">
                            <span :class="statusClass(r.status)" class="px-2 py-1 rounded-full text-xs font-medium">
                                {{ r.status }}
                            </span>
                        </td>
                    </tr>
                    <tr v-if="upcoming_reservations.length === 0">
                        <td colspan="5" class="px-4 py-8 text-center text-stone-400">Keine bevorstehenden Reservierungen</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
defineProps({
    stats: Object,
    sessionStats: Object,
    upcoming_reservations: Array,
});

function statusClass(status) {
    return {
        open: 'bg-yellow-100 text-yellow-800',
        confirmed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
    }[status] || 'bg-stone-100 text-stone-800';
}

function barWidth(booked, max) {
    return Math.min(100, (booked / max) * 100) + '%';
}

function barColor(booked, max) {
    const ratio = booked / max;
    if (ratio >= 1) return 'text-red-600';
    if (ratio >= 0.8) return 'text-orange-600';
    return 'text-stone-800';
}

function barFill(booked, max) {
    const ratio = booked / max;
    if (ratio >= 1) return 'bg-red-500';
    if (ratio >= 0.8) return 'bg-orange-400';
    return 'bg-amber-500';
}
</script>
