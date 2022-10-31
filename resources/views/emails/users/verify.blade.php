@component('mail::message')
# Final step...

Hi {{ $user->first_name }},

Confirm your email address to complete your Twitter account @<b><i>{{ $user->username }}</i></b>.

It's easy — just click the button below.

@component('mail::button', ['url' => $actionUrl])
{{ $actionText }}
@endcomponent

Thanks,

{{ config('app.name') }}
@endcomponent
