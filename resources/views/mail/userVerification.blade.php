@component('mail::layout')
@slot('header')
@component('mail::header', ['url' => config('app.url')])
Componists
@endcomponent
@endslot
Hi {{ $user->name }},

Your account on Componists has been created! Glad to see you joining the other componists!
Click the link below to verify your account:
<br>
www.componists.com/verify/{{ $user->token }}</a>
<br>
Let's make great things together!

Regards,
<br>
Head Componist

@slot('footer')
@component('mail::footer')
© 2017 Componists. All rights reserved.
@endcomponent
@endslot
@endcomponent
