<template>
    <div class="min-h-screen flex items-center justify-center bg-stone-100">
        <div class="bg-white shadow rounded-lg p-8 w-full max-w-md">
            <h1 class="text-2xl font-bold mb-6 text-center">Anmelden</h1>

            <form @submit.prevent="login">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">E-Mail</label>
                    <input type="email" v-model="form.email" required class="w-full border border-stone-300 rounded-lg px-4 py-2 focus:ring-amber-500 focus:border-amber-500" />
                    <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Passwort</label>
                    <input type="password" v-model="form.password" required class="w-full border border-stone-300 rounded-lg px-4 py-2 focus:ring-amber-500 focus:border-amber-500" />
                </div>

                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" v-model="form.remember" id="remember" class="rounded border-stone-300" />
                    <label for="remember" class="text-sm">Angemeldet bleiben</label>
                </div>

                <button type="submit" :disabled="form.processing" class="w-full bg-amber-600 hover:bg-amber-500 text-white font-semibold py-2.5 rounded-lg transition disabled:opacity-50">
                    Anmelden
                </button>

                <p class="text-center text-sm text-stone-500 mt-4">
                    Noch kein Konto? <Link :href="route('register')" class="text-amber-600 hover:underline">Registrieren</Link>
                </p>
            </form>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function login() {
    form.post(route('login'), {
        preserveScroll: true,
    });
}
</script>
