@component('mail::message')
# Olá {{$user->name}}

Obrigado por criar sua conta. Por favor verifique seu email usando este botão:

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Verificar Conta
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
