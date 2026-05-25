<template>
    <div class="min-h-screen bg-stone-100">
        <nav class="bg-white border-b border-stone-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-8">
                        <span class="text-lg font-bold text-amber-800">Admin</span>
                        <div class="flex gap-4 text-sm">
                            <Link :href="route('admin.dashboard')" class="hover:text-amber-700">Dashboard</Link>
                            <Link :href="route('admin.reservations.index')" class="hover:text-amber-700">Reservierungen</Link>
                            <Link :href="route('admin.contacts.index')" class="hover:text-amber-700">Kontakte</Link>
                            <Link :href="route('admin.menu.edit')" class="hover:text-amber-700">Speisekarte</Link>
                            <Link :href="route('admin.opening-hours.index')" class="hover:text-amber-700">Öffnungszeiten</Link>
                            <Link :href="route('admin.holidays.index')" class="hover:text-amber-700">Feiertage</Link>
                            <Link :href="route('admin.vacations.index')" class="hover:text-amber-700">Urlaub</Link>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-sm">
                        <Link :href="route('home')" class="text-stone-500 hover:text-stone-700">Zur Website</Link>
                        <form @submit.prevent="logout">
                            <button type="submit" class="text-red-600 hover:text-red-500">Abmelden</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <slot />
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

function logout() {
    router.post(route('logout'));
}
</script>
