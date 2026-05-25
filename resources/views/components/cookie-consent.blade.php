<link rel="stylesheet" href="{{ asset('css/silktide-consent-manager.css') }}">

<script src="{{ asset('js/silktide-consent-manager.js') }}" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    window.silktideConsentManager.init({
        namespace: 'restaurant-app',
        consentTypes: [
            {
                id: 'essential',
                label: '{{ __('messages.consent_essential_label') }}',
                description: '{{ __('messages.consent_essential_description') }}',
                required: true,
            },
            {
                id: 'analytics',
                label: '{{ __('messages.consent_analytics_label') }}',
                description: '{{ __('messages.consent_analytics_description') }}',
                required: false,
                defaultValue: false,
            },
            {
                id: 'marketing',
                label: '{{ __('messages.consent_marketing_label') }}',
                description: '{{ __('messages.consent_marketing_description') }}',
                required: false,
                defaultValue: false,
            },
        ],
        text: {
            prompt: {
                description: '{!! __('messages.consent_banner_description') !!}',
                acceptAllButtonText: '{{ __('messages.consent_accept_all') }}',
                rejectNonEssentialButtonText: '{{ __('messages.consent_reject_non_essential') }}',
                preferencesButtonText: '{{ __('messages.consent_preferences') }}',
            },
            preferences: {
                title: '{{ __('messages.consent_modal_title') }}',
                description: '{!! __('messages.consent_modal_description') !!}',
                saveButtonText: '{{ __('messages.consent_save') }}',
                creditLinkText: '{{ __('messages.consent_credit') }}',
            },
        },
        prompt: {
            position: 'bottomCenter',
        },
        icon: {
            position: 'bottomRight',
        },
        backdrop: {
            show: true,
        },
    });
});
</script>
