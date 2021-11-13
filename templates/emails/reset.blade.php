@component('mail::message')
# Forgot your password ?

That's OK, it happens! Click on the button bellow to reset your password.

@component('mail::button', ['url' => ''])
RESET YOUR PASSWORD
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
