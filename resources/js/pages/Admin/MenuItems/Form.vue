<template>
    <div>
        <div class="flex items-center gap-4 mb-6">
            <Link :href="route('admin.ordering.menu-items.index')" class="text-stone-500 hover:text-stone-700">&larr; Zurück</Link>
            <h1 class="text-2xl font-bold">{{ menuItem ? 'Artikel bearbeiten' : 'Neuer Artikel' }}</h1>
        </div>

        <div v-if="$page.props.flash?.success" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4 text-sm">
            {{ $page.props.flash.success }}
        </div>

        <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
            <form @submit.prevent="submit">
                <div class="space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Kategorie</label>
                            <select v-model="form.category_id" class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm">
                                <option value="">-- Kategorie wählen --</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                            <div v-if="form.errors.category_id" class="text-red-500 text-xs mt-1">{{ form.errors.category_id }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Artikelnummer</label>
                            <input type="text" v-model="form.item_number" class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm" />
                            <div v-if="form.errors.item_number" class="text-red-500 text-xs mt-1">{{ form.errors.item_number }}</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Name</label>
                        <input type="text" v-model="form.name" class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm" />
                        <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Beschreibung</label>
                        <textarea v-model="form.description" rows="3" class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm"></textarea>
                        <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Preis (&euro;)</label>
                            <input type="number" step="0.01" min="0" v-model="form.price" class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm" />
                            <div v-if="form.errors.price" class="text-red-500 text-xs mt-1">{{ form.errors.price }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Sortierung</label>
                            <input type="number" min="0" v-model="form.sort_order" class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Aktiv</label>
                            <div class="flex items-center h-10">
                                <input type="checkbox" v-model="form.is_active" class="accent-amber-600 rounded" />
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="submit" :disabled="form.processing" class="bg-amber-600 hover:bg-amber-500 disabled:opacity-50 text-white px-6 py-2 rounded-lg text-sm font-medium">
                            {{ form.processing ? 'Speichern...' : 'Speichern' }}
                        </button>
                        <Link :href="route('admin.ordering.menu-items.index')" class="border border-stone-300 hover:bg-stone-50 px-6 py-2 rounded-lg text-sm font-medium">Abbrechen</Link>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    menuItem: Object,
    categories: Array,
});

const form = useForm({
    category_id: props.menuItem?.category_id ?? '',
    item_number: props.menuItem?.item_number ?? '',
    name: props.menuItem?.name ?? '',
    description: props.menuItem?.description ?? '',
    price: props.menuItem?.price ?? '',
    is_active: props.menuItem?.is_active ?? true,
    sort_order: props.menuItem?.sort_order ?? 0,
});

function submit() {
    if (props.menuItem) {
        form.put(route('admin.ordering.menu-items.update', props.menuItem.id));
    } else {
        form.post(route('admin.ordering.menu-items.store'));
    }
}
</script>
