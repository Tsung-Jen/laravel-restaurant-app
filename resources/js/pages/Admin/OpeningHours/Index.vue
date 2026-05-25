<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">Öffnungszeiten</h1>

        <div v-if="$page.props.flash?.success" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4">{{ $page.props.flash.success }}</div>

        <div class="bg-white rounded-lg shadow overflow-hidden p-6">
            <form @submit.prevent="save">
                <table class="w-full text-sm">
                    <thead class="text-left border-b border-stone-200">
                        <tr>
                            <th class="pb-3 font-medium">Tag</th>
                            <th class="pb-3 font-medium">Mittag</th>
                            <th class="pb-3 font-medium">Abend</th>
                            <th class="pb-3 font-medium">Geschlossen</th>
                            <th class="pb-3 font-medium">Ausnahme-KW</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <tr v-for="hour in form.hours" :key="hour.day_of_week">
                            <td class="py-3 font-medium">{{ dayLabel(hour.day_of_week) }}</td>
                            <td class="py-3">
                                <div class="flex items-center gap-2">
                                    <input type="time" v-model="hour.lunch_start" class="border border-stone-300 rounded px-2 py-1 text-sm w-28" />
                                    <span class="text-stone-400">–</span>
                                    <input type="time" v-model="hour.lunch_end" class="border border-stone-300 rounded px-2 py-1 text-sm w-28" />
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="flex items-center gap-2">
                                    <input type="time" v-model="hour.evening_start" class="border border-stone-300 rounded px-2 py-1 text-sm w-28" />
                                    <span class="text-stone-400">–</span>
                                    <input type="time" v-model="hour.evening_end" class="border border-stone-300 rounded px-2 py-1 text-sm w-28" />
                                </div>
                            </td>
                            <td class="py-3">
                                <input type="checkbox" v-model="hour.is_closed" class="rounded border-stone-300 text-amber-600 focus:ring-amber-500" />
                            </td>
                            <td class="py-3">
                                <input type="week" v-model="hour.closed_except_week"
                                    class="border border-stone-300 rounded px-2 py-1 text-sm w-44"
                                    :disabled="!hour.is_closed" />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-6 text-xs text-stone-500 space-y-1">
                    <p><strong>Ausnahme-KW</strong>: Ist ein Tag als "Geschlossen" markiert, kann hier eine Kalenderwoche mit Jahr angegeben werden, in der das Restaurant ausnahmsweise geöffnet ist (z. B. Donnerstag in KW 20 von 2026).</p>
                    <p>Feiertage am Donnerstag werden ebenfalls als geöffnet gewertet.</p>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-amber-600 hover:bg-amber-500 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                        Speichern
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({ hours: Array });

const labels = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];

function dayLabel(dow) { return labels[dow]; }

const form = useForm({
    hours: props.hours.map(h => ({
        ...h,
        is_closed: Boolean(h.is_closed),
        closed_except_week: h.closed_except_week ?? null,
    })),
});

function save() {
    form.transform((data) => ({
        ...data,
        hours: data.hours.map(h => ({
            ...h,
            closed_except_week: h.closed_except_week || null,
        })),
    })).put(route('admin.opening-hours.update'));
}
</script>
