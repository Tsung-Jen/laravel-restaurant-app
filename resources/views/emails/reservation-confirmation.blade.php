<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.email_subject', ['name' => config('app.name')]) }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #f5f5f0; margin: 0; padding: 0; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        .header { background: #92400e; color: #fff; padding: 32px 40px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; font-weight: 600; }
        .body { padding: 32px 40px; color: #44403c; }
        .body p { font-size: 15px; line-height: 1.6; margin: 0 0 16px; }
        .details { background: #f5f5f0; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .details table { width: 100%; font-size: 14px; }
        .details td { padding: 6px 0; }
        .details td:first-child { color: #a8a29e; width: 100px; }
        .details td:last-child { font-weight: 500; color: #44403c; }
        .note { border-left: 4px solid #d97706; padding: 12px 16px; margin: 20px 0; background: #fffbeb; border-radius: 4px; font-size: 14px; color: #92400e; }
        .footer { border-top: 1px solid #e7e5e4; padding: 24px 40px; text-align: center; font-size: 13px; color: #a8a29e; }
        .footer p { margin: 4px 0; }
        .large-group { border-left: 4px solid #2563eb; padding: 12px 16px; margin: 20px 0; background: #eff6ff; border-radius: 4px; font-size: 14px; color: #1e40af; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>
        <div class="body">
            <p>{{ __('messages.email_greeting', ['name' => $reservation->name]) }}</p>
            <p>{{ __('messages.email_details_heading') }}</p>

            <div class="details">
                <table>
                    <tr>
                        <td>{{ __('messages.email_table_date') }}</td>
                        <td>{{ $reservation->date instanceof \Carbon\Carbon ? $reservation->date->format('d.m.Y') : \Carbon\Carbon::parse($reservation->date)->format('d.m.Y') }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('messages.email_table_time') }}</td>
                        <td>{{ substr($reservation->time, 0, 5) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('messages.email_table_guests') }}</td>
                        <td>{{ $reservation->guests }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('messages.email_table_name') }}</td>
                        <td>{{ $reservation->name }}</td>
                    </tr>
                </table>
            </div>

            <div class="note">
                {{ __('messages.email_arrival_note') }}
            </div>

            @if ($reservation->guests >= 15)
                <div class="large-group">
                    {{ __('messages.email_large_group_note') }}
                </div>
            @endif

            <div class="note">
                {{ __('messages.email_cancel_note', ['phone' => __('messages.footer_phone')]) }}
            </div>
        </div>
        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>{{ __('messages.address') }}</p>
            <p>{{ __('messages.footer_phone') }}</p>
        </div>
    </div>
</body>
</html>
