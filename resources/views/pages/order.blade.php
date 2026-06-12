@extends('layouts.app')

@section('title', __('messages.order'))

@section('content')
<div
    x-data="{
        modalItem: null,
        noteItem: null,
        noteText: '',
        adding: false,
        feedback: null,
        cartCount: {{ $cartCount }},
        openModal(item) { this.modalItem = item; document.body.classList.add('overflow-hidden'); },
        closeModal() { this.modalItem = null; document.body.classList.remove('overflow-hidden'); },
        openNoteModal(item) { this.noteItem = item; this.noteText = this.itemNotes[item.id] || ''; document.body.classList.add('overflow-hidden'); },
        closeNoteModal() { this.noteItem = null; this.noteText = ''; document.body.classList.remove('overflow-hidden'); },
        saveNote() {
            if (this.noteItem) this.itemNotes[this.noteItem.id] = this.noteText;
            this.closeNoteModal();
        },
        hasNote(itemId) { return !!this.itemNotes[itemId]; },
        itemNotes: {},
        async addToCart(itemId) {
            this.adding = true;
            const formData = new FormData();
            formData.append('item_id', itemId);
            formData.append('quantity', 1);
            if (this.itemNotes[itemId]) formData.append('notes', this.itemNotes[itemId]);
            try {
                const res = await fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData,
                });
                const data = await res.json();
                this.cartCount = data.count;
                this.feedback = data.message;
                setTimeout(() => this.feedback = null, 3000);
            } catch (e) {}
            this.adding = false;
        },
    }"
    class="max-w-4xl mx-auto px-4 py-8 sm:py-12"
>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-stone-800">@lang('messages.order')</h1>
            <p class="text-stone-500 mt-1">@lang('messages.order_subtitle')</p>
        </div>
        <a href="{{ route('cart.show') }}" class="relative flex items-center gap-2 bg-amber-600 hover:bg-amber-500 text-white px-5 py-2.5 rounded-xl font-semibold transition shadow-lg shadow-amber-200/50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            <span>@lang('messages.cart')</span>
            <span x-show="cartCount > 0" x-text="cartCount" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"></span>
        </a>
    </div>

    <div x-show="feedback" x-transition class="fixed top-4 right-4 z-50 bg-emerald-600 text-white px-5 py-3 rounded-xl shadow-lg text-sm">
        <span x-text="feedback"></span>
    </div>

    @foreach ($categories as $category)
        @if ($category->activeMenuItems->isNotEmpty())
            <div class="mb-10">
                <h2 class="text-xl font-bold text-stone-800 border-b-2 border-amber-500 pb-2 mb-4">{{ $category->name }}</h2>
                <div class="space-y-2">
                    @foreach ($category->activeMenuItems as $item)
                        <div class="bg-white rounded-xl border border-stone-200 p-4 flex items-center gap-4 hover:shadow-md transition">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono text-stone-400 bg-stone-100 px-2 py-0.5 rounded">{{ $item->item_number }}</span>
                                    <button @click="openModal({id:{{ $item->id }},name:'{{ $item->name }}',number:'{{ $item->item_number }}',description:'{{ addslashes($item->description ?? '') }}',price:'{{ number_format($item->price, 2, ',', '.') }}'})" class="text-stone-800 font-medium hover:text-amber-700 transition text-left">{{ $item->name }}</button>
                                    @if ($item->description)
                                        <button @click="openModal({id:{{ $item->id }},name:'{{ $item->name }}',number:'{{ $item->item_number }}',description:'{{ addslashes($item->description ?? '') }}',price:'{{ number_format($item->price, 2, ',', '.') }}'})" class="text-stone-400 hover:text-amber-600 transition shrink-0" title="@lang('messages.show_description')">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-stone-800 font-semibold">&euro; {{ number_format($item->price, 2, ',', '.') }}</span>
                            </div>
                            <button @click="openNoteModal({id:{{ $item->id }},name:'{{ $item->name }}'})" class="relative text-stone-400 hover:text-amber-600 transition shrink-0" title="@lang('messages.add_note')">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span x-show="hasNote({{ $item->id }})" class="absolute -top-1 -right-1 h-2 w-2 bg-amber-500 rounded-full"></span>
                            </button>
                            <button @click="addToCart({{ $item->id }})" :disabled="adding" class="bg-amber-600 hover:bg-amber-500 disabled:opacity-50 text-white rounded-full h-8 w-8 flex items-center justify-center transition shrink-0">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach

    <div x-show="modalItem" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape="closeModal">
        <div class="fixed inset-0 bg-black/50" @click="closeModal"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-10 max-h-[80vh] overflow-y-auto">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <span class="text-xs font-mono text-stone-400 bg-stone-100 px-2 py-0.5 rounded" x-text="modalItem?.number"></span>
                    <h3 class="text-xl font-bold text-stone-800 mt-1" x-text="modalItem?.name"></h3>
                </div>
                <button @click="closeModal" class="text-stone-400 hover:text-stone-600 transition p-1">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <p x-show="modalItem?.description" class="text-stone-600 text-sm leading-relaxed mb-4" x-text="modalItem?.description"></p>
            <p x-show="!modalItem?.description" class="text-stone-400 text-sm italic mb-4">@lang('messages.no_description')</p>
            <div class="flex items-center justify-between pt-3 border-t border-stone-100">
                <span class="text-lg font-bold text-stone-800" x-text="'&euro; ' + modalItem?.price"></span>
                <button @click="addToCart(modalItem.id); closeModal()" :disabled="adding" class="bg-amber-600 hover:bg-amber-500 disabled:opacity-50 text-white px-5 py-2 rounded-xl font-semibold transition">@lang('messages.add_to_cart')</button>
            </div>
        </div>
    </div>

    <div x-show="noteItem" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape="closeNoteModal">
        <div class="fixed inset-0 bg-black/50" @click="closeNoteModal"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 z-10">
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-lg font-bold text-stone-800">
                    @lang('messages.add_note_for') <span class="text-amber-600" x-text="noteItem?.name"></span>
                </h3>
                <button @click="closeNoteModal" class="text-stone-400 hover:text-stone-600 transition p-1">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <textarea x-model="noteText" class="w-full border-2 border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-800 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none resize-none" rows="3" placeholder="@lang('messages.note_placeholder')" maxlength="500"></textarea>
            <div class="flex items-center justify-between mt-4 pt-3 border-t border-stone-100">
                <span class="text-xs text-stone-400" x-text="noteText.length + '/500'"></span>
                <div class="flex gap-2">
                    <button @click="closeNoteModal" class="border border-stone-300 hover:bg-stone-50 text-stone-700 px-4 py-2 rounded-xl text-sm font-medium transition">@lang('messages.cancel')</button>
                    <button @click="saveNote" class="bg-amber-600 hover:bg-amber-500 text-white px-5 py-2 rounded-xl text-sm font-semibold transition">@lang('messages.save')</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
