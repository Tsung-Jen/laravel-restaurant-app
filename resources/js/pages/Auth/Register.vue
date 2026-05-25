<template>
    <div class="min-h-screen flex items-center justify-center bg-stone-100">
        <div class="bg-white shadow rounded-lg p-8 w-full max-w-md">
            <h1 class="text-2xl font-bold mb-6 text-center">Registrieren</h1>

            <form @submit.prevent="register">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" v-model="form.name" required class="w-full border border-stone-300 rounded-lg px-4 py-2 focus:ring-amber-500 focus:border-amber-500" />
                    <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">E-Mail</label>
                    <input type="email" v-model="form.email" required class="w-full border border-stone-300 rounded-lg px-4 py-2 focus:ring-amber-500 focus:border-amber-500" />
                    <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Passwort</label>
                    <input type="password" v-model="form.password" required class="w-full border border-stone-300 rounded-lg px-4 py-2 focus:ring-amber-500 focus:border-amber-500" />
                    <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Passwort bestätigen</label>
                    <input type="password" v-model="form.password_confirmation" required class="w-full border border-stone-300 rounded-lg px-4 py-2 focus:ring-amber-500 focus:border-amber-500" />
                </div>

                <button type="submit" :disabled="form.processing" class="w-full bg-amber-600 hover:bg-amber-500 text-white font-semibold py-2.5 rounded-lg transition disabled:opacity-50">
                    Registrieren
                </button>

                <p class="text-center text-sm text-stone-500 mt-4">
                    Bereits registriert? <Link :href="route('login')" class="text-amber-600 hover:underline">Anmelden</Link>
                </p>
            </form>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function register() {
    form.post(route('register'), {
        preserveScroll: true,
    });
}
</script>
