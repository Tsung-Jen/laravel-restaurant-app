<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Kategorien</h1>
        </div>

        <div v-if="$page.props.flash?.success" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4 text-sm">
            {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash?.error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
            {{ $page.props.flash.error }}
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold mb-4">Neue Kategorie</h2>
                <Form
                    action="/admin/ordering/categories"
                    method="post"
                    reset-on-success
                    #default="{ errors, processing, wasSuccessful }"
                >
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Name</label>
                            <input type="text" name="name" class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm" />
                            <div v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-stone-700 mb-1">Sortierung</label>
                                <input type="number" min="0" name="sort_order" value="0" class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-700 mb-1">Aktiv</label>
                                <div class="flex items-center h-10">
                                    <input type="checkbox" name="is_active" value="1" checked class="accent-amber-600 rounded" />
                                </div>
                            </div>
                        </div>
                        <button type="submit" :disabled="processing" class="bg-amber-600 hover:bg-amber-500 disabled:opacity-50 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            {{ processing ? 'Erstellen...' : 'Erstellen' }}
                        </button>
                    </div>
                </Form>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <h2 class="text-lg font-bold p-4 border-b border-stone-200">Vorhandene Kategorien</h2>
                <div v-if="categories.length === 0" class="p-6 text-center text-stone-400 text-sm">Keine Kategorien vorhanden</div>
                <div v-for="cat in categories" :key="cat.id" class="border-b border-stone-100 last:border-0 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div v-if="editingId === cat.id && editForm?.id === cat.id" class="space-y-2">
                                <form @submit.prevent="submitEdit(cat.id)">
                                    <input type="text" v-model="editForm.name" class="w-full border border-stone-300 rounded px-2 py-1 text-sm mb-1" />
                                    <div v-if="editForm.errors.name" class="text-red-500 text-xs">{{ editForm.errors.name }}</div>
                                    <div class="flex items-center gap-3 mt-1">
                                        <input type="number" min="0" v-model="editForm.sort_order" class="w-20 border border-stone-300 rounded px-2 py-1 text-sm" />
                                        <label class="flex items-center gap-1 text-xs">
                                            <input type="checkbox" v-model="editForm.is_active" class="accent-amber-600 rounded" />
                                            Aktiv
                                        </label>
                                    </div>
                                    <div class="flex gap-2 mt-2">
                                        <button type="submit" :disabled="editForm.processing" class="bg-amber-600 hover:bg-amber-500 disabled:opacity-50 text-white px-3 py-1 rounded text-xs">Speichern</button>
                                        <button @click="cancelEdit" type="button" class="border border-stone-300 hover:bg-stone-50 px-3 py-1 rounded text-xs">Abbrechen</button>
                                    </div>
                                </form>
                            </div>
                            <div v-else class="flex items-center gap-2">
                                <span :class="cat.is_active ? 'text-stone-800' : 'text-stone-400'" class="font-medium">{{ cat.name }}</span>
                                <span class="text-xs text-stone-400">(Rang {{ cat.sort_order }})</span>
                            </div>
                        </div>
                        <div v-if="editingId !== cat.id" class="flex gap-2 shrink-0">
                            <button @click="startEdit(cat)" class="text-amber-600 hover:text-amber-800 text-xs">Bearbeiten</button>
                            <button @click="destroy(cat)" class="text-red-500 hover:text-red-700 text-xs">Löschen</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Link, Form, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    categories: Array,
});

const editingId = ref(null);
const editForm = ref(null);

function startEdit(cat) {
    editingId.value = cat.id;
    editForm.value = useForm({
        id: cat.id,
        name: cat.name,
        sort_order: cat.sort_order,
        is_active: cat.is_active,
    });
}

function submitEdit(id) {
    editForm.value.put(route('admin.ordering.categories.update', id), {
        preserveScroll: true,
        onSuccess: () => {
            editingId.value = null;
            editForm.value = null;
        },
    });
}

function cancelEdit() {
    editingId.value = null;
    editForm.value = null;
}

function destroy(category) {
    if (confirm('Kategorie löschen?')) {
        router.delete(route('admin.ordering.categories.destroy', category.id), { preserveScroll: true });
    }
}
</script>
