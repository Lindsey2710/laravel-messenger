<x-mail::message>
Hello {{ $user->name }},

@if ($user->is_admin)
You are now admin in the system. You can add and block users.
@else
Your role was changed into regular user. You can no longer add or block users.
@endif
<br>

Thank you, <br>
{{ config('app.name') }}
</x-mail::message>


