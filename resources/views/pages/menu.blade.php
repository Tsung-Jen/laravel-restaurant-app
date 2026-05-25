@extends('layouts.app')

@section('title', __('messages.menu'))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-16 text-center">
    <h1 class="text-3xl font-bold mb-8">@lang('messages.menu')</h1>

    @if ($menuExists)
        <div x-data="pdfViewer('{{ $menuUrl }}')" class="space-y-6">
            <div x-show="loading" class="flex items-center justify-center h-96 bg-stone-100 rounded-lg">
                <p class="text-stone-500 text-lg animate-pulse">@lang('messages.loading_menu')</p>
            </div>

            <div x-show="error" class="flex items-center justify-center h-96 bg-red-50 rounded-lg">
                <p class="text-red-500 text-lg" x-text="error"></p>
            </div>

            <div x-show="!loading && !error" class="relative">
                <div class="relative flex items-center justify-center bg-stone-100 rounded-lg p-2 sm:p-4">
                    <button x-show="page > 1" @click="prevPage"
                        class="absolute left-1 sm:left-3 z-10 bg-white/90 hover:bg-white text-stone-700 rounded-full p-2.5 sm:p-3 shadow-lg transition hover:scale-110 border border-stone-200"
                        aria-label="@lang('messages.previous')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <canvas x-ref="canvas" class="shadow-xl rounded max-w-full h-auto"></canvas>

                    <button x-show="page < numPages" @click="nextPage"
                        class="absolute right-1 sm:right-3 z-10 bg-white/90 hover:bg-white text-stone-700 rounded-full p-2.5 sm:p-3 shadow-lg transition hover:scale-110 border border-stone-200"
                        aria-label="@lang('messages.next')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <div class="flex items-center justify-center gap-4 mt-4">
                    <button @click="prevPage" :disabled="page <= 1"
                        class="text-sm text-amber-700 hover:text-amber-600 disabled:text-stone-300 disabled:cursor-not-allowed transition font-medium">
                        &larr; @lang('messages.previous')
                    </button>
                    <span class="text-sm text-stone-500 font-medium" x-show="numPages > 0">
                        @lang('messages.page') <span x-text="page"></span> / <span x-text="numPages"></span>
                    </span>
                    <button @click="nextPage" :disabled="page >= numPages"
                        class="text-sm text-amber-700 hover:text-amber-600 disabled:text-stone-300 disabled:cursor-not-allowed transition font-medium">
                        @lang('messages.next') &rarr;
                    </button>
                </div>
            </div>

            <a href="{{ $menuUrl }}" download
               class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-500 text-white px-6 py-3 rounded-lg transition font-semibold shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                @lang('messages.download_pdf')
            </a>
        </div>
    @else
        <div class="bg-stone-100 rounded-lg p-12">
            <p class="text-stone-500 text-lg">@lang('messages.no_menu_yet')</p>
        </div>
    @endif
</div>
@endsection
