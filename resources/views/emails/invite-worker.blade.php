@component('mail::message')
# Invitation to Join {{ $restaurantName }}

Hello,

You have been invited to join **{{ $restaurantName }}** on PingWaiter as a **{{ ucfirst($designation) }}**.

Click the button below to accept the invitation using your Google account:

@component('mail::button', ['url' => $link])
Accept Invitation
@endcomponent

This invitation link will expire in 48 hours.

Thanks,
{{ $restaurantName }} Team
@endcomponent
