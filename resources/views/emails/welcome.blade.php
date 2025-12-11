@component('mail::message')
# Welcome, {{ $user->name }} 👋

Thank you for joining **{{ config('app.name') }}**.  
Your account has been successfully created.

@component('mail::button', ['url' => config('app.url')])
Visit Website
@endcomponent

If you have any questions, feel free to reach out.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
