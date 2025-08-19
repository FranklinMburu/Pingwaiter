@component('mail::message')
<style>
    .brand-header {
        font-size: 24px;
        font-weight: bold;
        color: #2d3748;
        margin-bottom: 10px;
    }
    .brand-logo {
        width: 80px;
        margin-bottom: 10px;
    }
    .cta-btn {
        background: #38a169;
        color: #fff !important;
        padding: 12px 24px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 20px 0;
    }
    .expiry {
        color: #718096;
        font-size: 14px;
        margin-top: 10px;
    }
</style>
<div style="text-align:center;">
    <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }} Logo" class="brand-logo">
    <div class="brand-header">You're Invited to Join {{ config('app.name') }}</div>
</div>

Hello,

You have been invited to join <strong>{{ config('app.name') }}</strong> as a <strong>{{ ucfirst($invite['role']) }}</strong>.

Click the button below to accept your invitation and get started:

<a href="{{ $acceptUrl }}" class="cta-btn">Accept Invitation</a>


<div class="expiry">
    This invitation link is valid for 48 hours from the time it was sent.<br>
    If you do not accept within this period, the link will expire for your security.
</div>


If the button above does not work, copy and paste this link into your browser:
<br>
<span style="word-break:break-all;">{{ $acceptUrl }}</span>

<br>
If you have already accepted this invitation or the link has expired, please contact your administrator to request a new invitation.

Thank you,<br>
{{ config('app.name') }} Team
@endcomponent
