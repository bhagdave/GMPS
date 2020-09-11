@component('mail::message')

You have been invited to join GMPS.  

Follow the link below to accept the invite.  


@component('mail::button', ['url' => $url])
Accept invite
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
