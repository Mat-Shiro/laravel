@component('mail::message')
# Olá {{$user->name}}

Você alterou o seu email, então precisamos verificar este novo endereço. Por favor use o botão abaixo:

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Verificar Conta
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
