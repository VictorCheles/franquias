<p>
    Olá, {{ $user->nome }}. Você foi recrutado para uma missão muito importante e deve, a partir de agora, se comportar com muita discrição e ler com bastante atenção as informações a seguir.
</p>
<p>
    <b>Seja bem-vindo ao programa: Cliente Oculto {{ env('APP_NAME') }}.</b>
</p>
<p>
    A partir de agora, você é nosso avaliador oculto, onde tem a missão de visitar uma de nossas unidades e avaliar a sua experiência, sem que eles percebam quem você realmente é e para quem trabalha.
</p>
<p>
    As suas respostas durante a avaliação, influenciam diretamente na tomada de decisões sobre nossos produtos e serviços. Por isso, todas as questões devem ser respondidas com muita seriedade e responsabilidade.
</p>
<p>
    No final desse e-mail, consta o seu Voucher de R$40,00 de desconto para consumação da sua visita, precisamos que durante a resposta do seu questionário você faça o upload de 2 fotos obrigatórias: sendo uma do seu lanche e uma da frente da unidade.
</p>
<p>
    Ao apresentar o Voucher, não deixe que o atendente saiba/desconfie que você é um cliente oculto, caso ocorra algum questionamento por parte da loja, informe que o direito de consumo do voucher se deu através das redes sociais.
</p>
<p>
    É muito importante o seu compromisso com as informações passadas, pois o questionário deve ser respondido durante a visita.
</p>
<p>
    Antes de iniciar a sua avaliação, confira este vídeo com maiores orientações que preparamos para você, recruta.
</p>
<p>
    Dados de sua missão:
</p>
@foreach($visitas as $visita)
    <ul>
        <li>Unidade a ser visitada: {{ \App\Models\Loja::findOrFail($visita['pivot']->loja_id)->nome }}</li>
        @if(!is_null($visita['pivot']->data_visita))
            <li>Dia da visita:  {{ \Carbon\Carbon::parse($visita['pivot']->data_visita)->format('d/m/Y') }}</li>
            <li>Horário da Visita:  A partir dàs {{ \Carbon\Carbon::parse($visita['pivot']->data_visita)->format('H:i') }}</li>
            <li>Voucher: <a href="{{ urlCupons() }}/exibir-voucher/{{ $visita['pivot']->foto_comprovante }}">Clique aqui para baixar</a></li>
        @endif
    </ul>
@endforeach
<p>
    Esse login e senha te dão acesso ao nosso sistema.<br>
    Login: {{ $user->email }}<br>
    Senha: {{ $raw_password }}
</p>
<p>
    Acesse o nosso site de avaliação e entre com seus dados.
</p>
<p>
    <a target="_blank" href="{{ urlClienteOculto() }}/login/{{ $user->email }}">https://{{ env('APP_SUBDOMAIN_AVALIADOR_OCULTO') }}.{{ env('APP_DOMAIN') }}/login/{{ $user->email }}</a>
</p>
<p>
    Boa missão e "hasta la vista, baby".
</p>
<p>
    <a href="https://youtu.be/6Wc61jaMalI">Vídeo de apresentação do cliente oculto</a>
</p>
<img src="{{ asset('images/top-secret.png') }}">