@component('mail::message')
# Verify Your Email

Click the button below to verify your email address:

@component('mail::button', ['url' => $actionUrl])
Verify Email
@endcomponent

If the button doesn't work, copy and paste this link in your browser:

{{ $actionUrl }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
