<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">Speisekarte (PDF)</h1>

        <div v-if="$page.props.flash?.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ $page.props.flash.success }}
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div v-if="menuExists" class="mb-6 p-4 bg-stone-50 rounded-lg border border-stone-200">
                <p class="text-sm text-stone-500 mb-2">Aktuelle Speisekarte:</p>
                <div class="flex items-center gap-3">
                    <a :href="menuUrl" target="_blank" class="text-amber-600 hover:text-amber-800 underline text-sm font-medium">
                        speisekarte.pdf ansehen
                    </a>
                    <button @click="confirmDelete = true" class="text-xs text-red-500 hover:text-red-700 underline ml-auto">
                        Löschen
                    </button>
                </div>

                <div v-if="confirmDelete" class="mt-3 pt-3 border-t border-stone-200 flex items-center gap-2">
                    <span class="text-sm text-stone-600">Sind Sie sicher?</span>
                    <button @click="remove" :disabled="deleteForm.processing" class="bg-red-600 hover:bg-red-500 text-white px-4 py-1.5 rounded text-xs font-semibold transition disabled:opacity-50">
                        Löschen
                    </button>
                    <button @click="confirmDelete = false" class="text-xs text-stone-500 hover:text-stone-700 underline">
                        Abbrechen
                    </button>
                </div>
            </div>

            <form @submit.prevent="submit">
                <label class="block mb-2 text-sm font-medium">Neue PDF hochladen</label>
                <input
                    type="file"
                    @input="onFileSelect"
                    accept=".pdf"
                    ref="fileInput"
                    class="block w-full text-sm text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100"
                />
                <p v-if="selectedFileName" class="text-xs text-stone-500 mt-1">
                    Ausgewählt: {{ selectedFileName }}
                </p>
                <p v-if="form.errors.menu" class="text-red-500 text-xs mt-1">{{ form.errors.menu }}</p>

                <button type="submit" :disabled="form.processing || !form.menu" class="mt-3 bg-amber-600 hover:bg-amber-500 text-white px-6 py-2 rounded-lg text-sm font-semibold transition disabled:opacity-50">
                    <span v-if="form.processing">Wird hochgeladen...</span>
                    <span v-else>Hochladen</span>
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    menuExists: Boolean,
    menuUrl: String,
});

const fileInput = ref(null);
const selectedFileName = ref(null);
const confirmDelete = ref(false);

const form = useForm({
    menu: null,
});

const deleteForm = useForm({});

function onFileSelect(event) {
    const file = event.target.files[0];
    form.menu = file;
    selectedFileName.value = file ? file.name : null;
}

function submit() {
    form.post(route('admin.menu.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            selectedFileName.value = null;
            if (fileInput.value) fileInput.value.value = '';
        },
    });
}

function remove() {
    deleteForm.delete(route('admin.menu.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            confirmDelete.value = false;
        },
    });
}
</script>
