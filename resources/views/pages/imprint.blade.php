@extends('layouts.app')

@section('title', __('messages.imprint'))

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12 sm:py-16">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-stone-800">@lang('messages.imprint')</h1>
        <p class="text-stone-500 mt-2">@lang('messages.imprint_subtitle')</p>
    </div>

    <div class="bg-white rounded-2xl shadow-lg shadow-stone-200/60 border border-stone-100 p-6 sm:p-8 space-y-8 text-stone-700">

        <div>
            <h2 class="text-xl font-semibold text-stone-800 mb-3">@lang('messages.imprint_contact')</h2>
            <div class="space-y-1.5 text-sm leading-relaxed">
                <p class="font-medium text-stone-900">{{ config('app.name') }}</p>
                <p>@lang('messages.imprint_address')</p>
                <p>@lang('messages.imprint_phone')</p>
                <p>@lang('messages.imprint_email')</p>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-semibold text-stone-800 mb-3">@lang('messages.imprint_representative')</h2>
            <p class="text-sm">@lang('messages.imprint_representative_text')</p>
        </div>

        <div>
            <h2 class="text-xl font-semibold text-stone-800 mb-3">@lang('messages.imprint_register')</h2>
            <div class="space-y-1.5 text-sm">
                <p>@lang('messages.imprint_register_court')</p>
                <p>@lang('messages.imprint_register_number')</p>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-semibold text-stone-800 mb-3">@lang('messages.imprint_vat')</h2>
            <p class="text-sm">@lang('messages.imprint_vat_id')</p>
        </div>

        <div>
            <h2 class="text-xl font-semibold text-stone-800 mb-3">@lang('messages.imprint_responsible')</h2>
            <p class="text-sm">@lang('messages.imprint_responsible_text')</p>
        </div>

        <div>
            <h2 class="text-xl font-semibold text-stone-800 mb-3">@lang('messages.imprint_disclaimer')</h2>
            <div class="space-y-4 text-sm leading-relaxed">
                <h3 class="font-medium text-stone-900">@lang('messages.imprint_links_heading')</h3>
                <p>@lang('messages.imprint_links_text')</p>

                <h3 class="font-medium text-stone-900">@lang('messages.imprint_content_heading')</h3>
                <p>@lang('messages.imprint_content_text')</p>

                <h3 class="font-medium text-stone-900">@lang('messages.imprint_copyright_heading')</h3>
                <p>@lang('messages.imprint_copyright_text')</p>
            </div>
        </div>

    </div>
</div>
@endsection
