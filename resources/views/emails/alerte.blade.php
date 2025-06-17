@extends('emails.base')
@section('body-content')
<p>Cher(e) {{ $data['user']['name'] }},</p>

<p>Vous avez reçu une nouvelle instance de réformes à traiter.</p>

<p>
    Accédez à la plateforme, <a href="{{ env('APP_FRONT_URL')}}/login" target="_blank">en cliquant ici</a>
</p>

{{-- <p class="text-center"> {{ SettingData('name') }}, vous remercie.</p> --}}
@endsection

