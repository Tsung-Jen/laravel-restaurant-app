@extends('layouts.app')

@section('title', __('messages.cart'))

@section('content')
<div
    x-data="{
        items: {{ json_encode(array_map(fn($i) => ['menu_item_id' => $i['menu_item_id'], 'item_name' => $i['item_name'], 'item_number' => $i['item_number'], 'price' => (float)$i['price'], 'quantity' => (int)$i['quantity']], $cartItems)) }},
        total: {{ $cartTotal }},
        count: {{ $cartCount }},
        empty: {{ $cartEmpty ? 'true' : 'false' }},
        pending: new Set(),
        recalc() {
            this.total = this.items.reduce((sum, i) => sum + i.price * i.quantity, 0);
            this.count = this.items.reduce((sum, i) => sum + i.quantity, 0);
            this.empty = this.items.length === 0;
        },
        async updateQty(itemId, qty) {
            if (qty < 1) { this.removeItem(itemId); return; }
            const item = this.items.find(i => i.menu_item_id === itemId);
            if (!item) return;
            item.quantity = qty;
            this.recalc();
            this.pending.add(itemId);
            try {
                const formData = new FormData();
                formData.append('item_id', itemId);
                formData.append('quantity', qty);
                const res = await fetch('{{ route('cart.update') }}', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData,
                });
                const data = await res.json();
                this.items = data.items;
                this.total = data.total;
                this.count = data.count;
                this.empty = data.items.length === 0;
            } catch (e) {}
            this.pending.delete(itemId);
        },
        async removeItem(itemId) {
            this.pending.add(itemId);
            try {
                const formData = new FormData();
                formData.append('item_id', itemId);
                const res = await fetch('{{ route('cart.remove') }}', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData,
                });
                const data = await res.json();
                this.items = data.items;
                this.total = data.total;
                this.count = data.count;
                this.empty = data.items.length === 0;
            } catch (e) {}
            this.pending.delete(itemId);
        },
        async clearCart() {
            if (this.empty) return;
            const snapshot = { items: [...this.items], total: this.total, count: this.count, empty: this.empty };
            this.items = [];
            this.recalc();
            try {
                const res = await fetch('{{ route('cart.clear') }}', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                });
                if (!res.ok) throw new Error();
            } catch (e) {
                Object.assign(this, snapshot);
            }
        },
    }"
    class="max-w-3xl mx-auto px-4 py-8 sm:py-12"
>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-stone-800">@lang('messages.cart')</h1>
            <p class="text-stone-500 mt-1">@lang('messages.cart_subtitle')</p>
        </div>
        <a href="{{ route('order.index') }}" class="text-amber-700 hover:text-amber-600 font-semibold text-sm transition flex items-center gap-1">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            @lang('messages.back_to_order')
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl mb-8 flex items-center gap-3 shadow-sm">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div x-show="empty" x-cloak class="bg-white rounded-2xl shadow-lg shadow-stone-200/60 border border-stone-100 p-12 text-center">
        <svg class="h-16 w-16 mx-auto text-stone-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
        <p class="text-stone-500 mb-6">@lang('messages.cart_empty')</p>
        <a href="{{ route('order.index') }}" class="bg-amber-600 hover:bg-amber-500 text-white px-6 py-2.5 rounded-xl font-semibold transition inline-block">@lang('messages.browse_items')</a>
    </div>

    <div x-show="!empty" x-cloak class="space-y-6">
        <div class="bg-white rounded-2xl shadow-lg shadow-stone-200/60 border border-stone-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-stone-50 text-stone-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-5 py-3">@lang('messages.item')</th>
                        <th class="text-center px-3 py-3">@lang('messages.quantity')</th>
                        <th class="text-right px-5 py-3">@lang('messages.price')</th>
                        <th class="text-right px-5 py-3">@lang('messages.total')</th>
                        <th class="w-10 px-2 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    <template x-for="(item, idx) in items" :key="item.menu_item_id">
                        <tr class="hover:bg-stone-50/50 transition">
                            <td class="px-5 py-4">
                                <span class="text-xs font-mono text-stone-400 bg-stone-100 px-2 py-0.5 rounded" x-text="item.item_number"></span>
                                <span class="ml-2 text-stone-800 font-medium" x-text="item.item_name"></span>
                            </td>
                            <td class="px-3 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    <button @click="updateQty(item.menu_item_id, item.quantity - 1)" :disabled="pending.has(item.menu_item_id)" class="bg-stone-100 hover:bg-stone-200 disabled:opacity-30 rounded-full h-7 w-7 flex items-center justify-center transition text-stone-600">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                                    </button>
                                    <span class="w-8 text-center font-semibold text-stone-800" x-text="item.quantity"></span>
                                    <button @click="updateQty(item.menu_item_id, item.quantity + 1)" :disabled="pending.has(item.menu_item_id)" class="bg-amber-100 hover:bg-amber-200 disabled:opacity-30 rounded-full h-7 w-7 flex items-center justify-center transition text-amber-700">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right text-stone-600" x-text="'&euro; ' + item.price.toFixed(2)"></td>
                            <td class="px-5 py-4 text-right font-semibold text-stone-800" x-text="'&euro; ' + (item.price * item.quantity).toFixed(2)"></td>
                            <td class="px-2 py-4">
                                <button @click="removeItem(item.menu_item_id)" :disabled="pending.has(item.menu_item_id)" class="text-red-400 hover:text-red-600 disabled:opacity-30 transition p-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between">
            <button @click="clearCart" :disabled="pending.size > 0" class="text-red-500 hover:text-red-700 disabled:opacity-30 text-sm font-medium transition flex items-center gap-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                @lang('messages.empty_cart')
            </button>
            <div class="text-right">
                <span class="text-stone-500 text-sm">@lang('messages.subtotal')</span>
                <span class="text-2xl font-bold text-stone-800 ml-2" x-text="'&euro; ' + total.toFixed(2)"></span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg shadow-stone-200/60 border border-stone-100 p-6">
            <h3 class="text-lg font-bold text-stone-800 mb-4">@lang('messages.pickup_details')</h3>
            <form method="POST" action="{{ route('order.store') }}" class="space-y-4">
                @csrf
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-stone-700 mb-1.5">@lang('messages.pickup_date') <span class="text-red-400">*</span></label>
                        <input type="date" name="pickup_date" value="{{ old('pickup_date') }}" required min="{{ $minDate }}" max="{{ $maxDate }}"
                            class="w-full border-2 border-stone-200 rounded-xl px-4 py-3 text-stone-800 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none @error('pickup_date') border-red-300 @enderror">
                        @error('pickup_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-stone-700 mb-1.5">@lang('messages.pickup_time') <span class="text-red-400">*</span></label>
                        <input type="time" name="pickup_time" value="{{ old('pickup_time') }}" required
                            class="w-full border-2 border-stone-200 rounded-xl px-4 py-3 text-stone-800 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none @error('pickup_time') border-red-300 @enderror">
                        @error('pickup_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-1.5">@lang('messages.phone') <span class="text-red-400">*</span></label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="+49 123 456789"
                        class="w-full border-2 border-stone-200 rounded-xl px-4 py-3 text-stone-800 placeholder-stone-400 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none @error('phone') border-red-300 @enderror">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-2">@lang('messages.payment_method') <span class="text-red-400">*</span></label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="payment_method" value="cash" {{ old('payment_method') === 'cash' ? 'checked' : '' }} required class="accent-amber-600">
                            <span class="text-stone-700">@lang('messages.cash')</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="payment_method" value="card" {{ old('payment_method') === 'card' ? 'checked' : '' }} required class="accent-amber-600">
                            <span class="text-stone-700">@lang('messages.card')</span>
                        </label>
                    </div>
                    @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                @error('cart')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror

                <button type="submit" class="w-full bg-amber-600 hover:bg-amber-500 active:bg-amber-700 text-white font-semibold py-3.5 rounded-xl transition shadow-lg shadow-amber-200/50">
                    @lang('messages.place_order')
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
