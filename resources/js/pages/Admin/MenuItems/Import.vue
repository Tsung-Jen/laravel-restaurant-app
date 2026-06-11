<template>
    <div>
        <div class="flex items-center gap-4 mb-6">
            <Link :href="route('admin.ordering.menu-items.index')" class="text-stone-500 hover:text-stone-700">&larr; Zurück</Link>
            <h1 class="text-2xl font-bold">CSV Import</h1>
        </div>

        <div v-if="$page.props.flash?.error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
            {{ $page.props.flash.error }}
        </div>

        <div v-if="!rows" class="bg-white rounded-lg shadow p-8 max-w-xl">
            <p class="text-stone-600 text-sm mb-6">Laden Sie eine CSV-Datei mit den Artikeln hoch. Die erste Zeile muss die Spaltenüberschriften enthalten.</p>

            <Form
                action="/admin/ordering/menu-items/import/preview"
                method="post"
                #default="{ errors, processing }"
            >
                <div class="border-2 border-dashed border-stone-300 rounded-xl p-8 text-center mb-4 hover:border-amber-400 transition">
                    <svg class="h-10 w-10 mx-auto text-stone-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    <p class="text-stone-500 text-sm mb-1">CSV-Datei auswählen</p>
                    <p class="text-stone-400 text-xs">.csv oder .txt, max. 2 MB</p>
                    <input type="file" name="file" accept=".csv,.txt" class="mt-3 text-sm text-stone-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-amber-600 file:text-white hover:file:bg-amber-500 file:cursor-pointer file:transition" />
                </div>
                <div v-if="errors.file" class="text-red-500 text-xs mb-2">{{ errors.file }}</div>

                <button type="submit" :disabled="processing" class="w-full bg-amber-600 hover:bg-amber-500 disabled:opacity-50 text-white py-2.5 rounded-lg text-sm font-medium">
                    {{ processing ? 'Wird analysiert...' : 'Vorschau anzeigen' }}
                </button>
            </Form>

            <div class="mt-6 text-xs text-stone-400">
                <p class="font-medium mb-1">Erwartetes Format:</p>
                <code class="block bg-stone-50 px-3 py-2 rounded text-xs leading-relaxed">
                    item_number,category,name,description,price,is_active,sort_order<br />
                    N1,Vorspeisen,Frühlingsrollen,Knusprig gefüllt,4.50,1,1
                </code>
            </div>
        </div>

        <div v-else>
            <div class="flex items-center gap-3 mb-6">
                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm font-medium">{{ validCount }} gültig</span>
                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-medium">{{ skipCount }} übersprungen</span>
                <span v-if="errorCount > 0" class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium">{{ errorCount }} Fehler</span>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <table class="w-full text-sm">
                    <thead class="bg-stone-50 text-left">
                        <tr>
                            <th class="px-3 py-2 font-medium">#</th>
                            <th class="px-3 py-2 font-medium">Nr.</th>
                            <th class="px-3 py-2 font-medium">Name</th>
                            <th class="px-3 py-2 font-medium">Kategorie</th>
                            <th class="px-3 py-2 font-medium">Preis</th>
                            <th class="px-3 py-2 font-medium">Aktiv</th>
                            <th class="px-3 py-2 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <tr v-for="row in rows" :key="row.row_number" :class="row.errors.length ? 'bg-red-50' : row.skip_reason ? 'bg-yellow-50' : ''">
                            <td class="px-3 py-2 text-xs text-stone-400">{{ row.row_number }}</td>
                            <td class="px-3 py-2 font-mono text-xs">{{ row.item_number }}</td>
                            <td class="px-3 py-2">{{ row.name || '—' }}</td>
                            <td class="px-3 py-2 text-stone-600">{{ row.category_name || '—' }}</td>
                            <td class="px-3 py-2">{{ row.price !== null ? '&euro; ' + row.price.toFixed(2) : '—' }}</td>
                            <td class="px-3 py-2">{{ row.is_active ? 'Ja' : 'Nein' }}</td>
                            <td class="px-3 py-2">
                                <span v-if="row.errors.length" class="text-red-600 text-xs">{{ row.errors.join(', ') }}</span>
                                <span v-else-if="row.skip_reason" class="text-yellow-600 text-xs">{{ row.skip_reason }}</span>
                                <span v-else class="text-emerald-600 text-xs">OK</span>
                            </td>
                        </tr>
                        <tr v-if="rows.length === 0">
                            <td colspan="7" class="px-4 py-8 text-center text-stone-400">Keine Daten gefunden</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex gap-3">
                <button @click="executeImport" :disabled="importing || validCount === 0" class="bg-amber-600 hover:bg-amber-500 disabled:opacity-50 text-white px-6 py-2.5 rounded-lg text-sm font-medium">
                    {{ importing ? 'Importiere...' : validCount + ' Artikel importieren' }}
                </button>
                <Link :href="route('admin.ordering.menu-items.import')" class="border border-stone-300 hover:bg-stone-50 px-6 py-2.5 rounded-lg text-sm font-medium">Abbrechen</Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, Form, router } from '@inertiajs/vue3';

const props = defineProps({
    rows: Array,
    validCount: { type: Number, default: 0 },
    errorCount: { type: Number, default: 0 },
    skipCount: { type: Number, default: 0 },
});

const importing = ref(false);

function executeImport() {
    importing.value = true;
    router.post(route('admin.ordering.menu-items.import.execute'), {
        rows: JSON.stringify(props.rows),
    }, {
        preserveScroll: true,
        onFinish: () => { importing.value = false; },
    });
}
</script>
