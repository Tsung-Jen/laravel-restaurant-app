@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
<div x-data="carousel()" class="relative bg-stone-900 text-white overflow-hidden" x-init="start()">
    <template x-for="(slide, index) in slides" :key="index">
        <div x-show="current === index" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100" class="absolute inset-0 bg-cover bg-center h-[70vh]" :style="`background-image: url('${slide}')`"></div>
    </template>
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 flex flex-col items-center justify-center h-[70vh] text-center px-4">
        <h1 class="text-4xl md:text-6xl font-bold mb-4 drop-shadow-lg">@lang('messages.hero_title')</h1>
        <p class="text-lg md:text-xl mb-8 max-w-xl drop-shadow">@lang('messages.hero_subtitle')</p>
        <a href="{{ route('reservation.create') }}" class="bg-amber-600 hover:bg-amber-500 px-8 py-3 rounded-lg font-semibold transition">
            @lang('messages.reserve_now')
        </a>
    </div>
    <button @click="prev" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/40 rounded-full p-2 transition">&larr;</button>
    <button @click="next" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/40 rounded-full p-2 transition">&rarr;</button>
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex gap-2">
        <template x-for="(slide, index) in slides" :key="'dot'+index">
            <button @click="current = index" :class="{'bg-amber-500': current === index, 'bg-white/50': current !== index}" class="w-3 h-3 rounded-full transition"></button>
        </template>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 py-16 text-center">
    <h2 class="text-3xl font-bold mb-4">@lang('messages.welcome_title')</h2>
    <p class="text-stone-600 max-w-2xl mx-auto text-lg">@lang('messages.welcome_text')</p>
</div>

<script>
function carousel() {
    return {
        current: 0,
        slides: [
            @foreach(glob(public_path('images/carousel/*.{jpg,jpeg,png,webp,svg}'), GLOB_BRACE) as $img)
                "{{ asset('images/carousel/' . basename($img)) }}",
            @endforeach
        ],
        timer: null,
        start() {
            if (this.slides.length < 2) return;
            this.timer = setInterval(() => this.next(), 5000);
        },
        next() {
            this.current = (this.current + 1) % this.slides.length;
        },
        prev() {
            this.current = (this.current - 1 + this.slides.length) % this.slides.length;
        }
    }
}
</script>
@endsection
