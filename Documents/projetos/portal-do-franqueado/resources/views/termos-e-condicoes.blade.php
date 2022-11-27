@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-xs-8 col-xs-offset-2">
            <div class="box box-danger box-solid box-promocao no-border">
                <div class="box-header text-center">
                    <h2>Cupons {{ strtoupper(env('APP_NAME_FULL')) }} 2017 <br> Termos e Condições</h2>
                </div>
                <div class="box-footer box-saia"></div>
                <div class="box-body">
                    <p>
                        Todos os direitos reservados<br> Termos e Condições do Site da {{ strtoupper(env('APP_NAME_FULL')) }} na Internet
                        <br>
                        Leia atentamente os seguintes termos e condições:
                        <br>
                        Ao utilizar as páginas deste site, você concordará automaticamente com estes termos e condições. Caso você não concorde com eles, não deve utilizar o presente site.
                    </p>
                    <p>
                        <b>Restrições ao uso</b><br>
                        O material do https://www.{{ env('APP_DOMAIN') }}, ou de qualquer outro site na {{ strtoupper(env('APP_NAME_FULL')) }} atuado, licenciado, ou controlado pelo {{ strtoupper(env('APP_NAME_FULL')) }} Corporation ou quaisquer das empresas associadas, afiliadas ou subsidiárias não pode ser copiado ou distribuído, republicado, baixado, exposto, ou transmitido de qualquer modo sem o consentimento anterior expresso por escrito do {{ strtoupper(env('APP_NAME_FULL')) }} exceto nos seguintes casos: é permitido baixar uma cópia do nosso arquivo em um computador somente para seu uso pessoal, não comercial e doméstico, desde que não apague ou modifique nenhum dos avisos de direito autoral, marcas ou outros indicadores de propriedade. A modificação ou emprego dos materiais para outro desígnio viola os direitos de propriedade intelectual da {{ strtoupper(env('APP_NAME_FULL')) }}. Se baixar um software de nosso site, este software, incluindo todos os arquivos e imagens nele contidas ou por ele geradas, e quaisquer outros dados a ele agregado (juntos chamados de "Software") serão considerados como uma concessão de licença do {{ strtoupper(env('APP_NAME_FULL')) }} a você. A titularidade dos direitos relativos à propriedade intelectual não são transferidos a você, permanecendo com a {{ strtoupper(env('APP_NAME_FULL')) }}, que possui a titularidade do direito de propriedade pleno e irrestrito. É proibido revender, desmembrar, fazer engenharia reversa, desmontar, ou converter o software de qualquer modo humanamente perceptível
                    </p>
                    <p>
                        <b>Restrições de responsabilidade</b><br>
                        A {{ strtoupper(env('APP_NAME_FULL')) }} não se responsabiliza por nenhum dano ou lesão causados, não se limitando a qualquer falha de desempenho, erro, interrupção, defeito, vírus de computador, ou problema na linha. A {{ strtoupper(env('APP_NAME_FULL')) }} não se responsabiliza por nenhum dano especial ou decorrente da utilização ou incapacidade de empregar os materiais do presente site, mesmo em caso de negligência, seja da {{ strtoupper(env('APP_NAME_FULL')) }} ou de um representante autorizado da {{ strtoupper(env('APP_NAME_FULL')) }}, que tenha sido advertido da possibilidade de tais danos, ou ambos. A responsabilidade da {{ strtoupper(env('APP_NAME_FULL')) }} em virtude de eventual perda ou ano será limitada ao valor pago no acesso ao presente site.
                    </p>
                    <p>
                        <b>Jurisdição</b><br>
                        Afora quando descrito o contrário, todos os materiais do site da {{ strtoupper(env('APP_NAME_FULL')) }} estão disponíveis somente para fornecer dados acerca da {{ strtoupper(env('APP_NAME_FULL')) }}. A {{ strtoupper(env('APP_NAME_FULL')) }} controla este site a partir de sua sede em XXXXXXX, e não adquire responsabilidade pela adequação ou disponibilidade do uso desses materiais em outros locais. Se você acessar o presente site de outros lugares, será responsável pelo cumprimento da legislação local aplicável
                    </p>
                    <p>
                        <b>Renúncia</b><br>
                        Todos os materiais no presente site podem conter imprecisões técnicas ou erros tipográficos. A {{ strtoupper(env('APP_NAME_FULL')) }} pode modificar ou realizar melhorias a qualquer momento. Os materiais no presente site são fornecidos "como estão", e sem qualquer tipo de garantia expressa ou implícita até o limite máximo permitido pela legislação aplicável. A {{ strtoupper(env('APP_NAME_FULL')) }} exime-se de qualquer responsabilidade relativa a garantias ou probabilidades de comercialização e adequação para uma finalidade específica. A {{ strtoupper(env('APP_NAME_FULL')) }} não garante que as funções contidas no material nunca serão interrompidas, nem que não ocorrerão erros, que defeitos serão corrigidos, nem que esse site ou seu servidor estejam livres de vírus ou outros componentes prejudiciais. A {{ strtoupper(env('APP_NAME_FULL')) }} não garante ou faz qualquer afirmação da utilização ou resultado da utilização de qualquer material neste site quanto à sua correção, precisão, confiabilidade, ou qualquer outro item. Você (e não a {{ strtoupper(env('APP_NAME_FULL')) }}) assume inteiramente o custo de qualquer manutenção, conserto ou correção. A exclusão acima pode não aplicar-se a você, na medida em que a legislação pertinente pode não permitir a exclusão das garantias implícitas.
                    </p>
                    <p>
                        <b>Rescisão</b><br>
                        A {{ strtoupper(env('APP_NAME_FULL')) }} ou você podem rescindir o presente acordo a qualquer momento. Você pode rescindir o presente acordo destruindo: (a) todos os materiais obtidos de todos os sites da {{ strtoupper(env('APP_NAME_FULL')) }} e (b) toda a documentação relacionada e todas as cópias e instalações. A {{ strtoupper(env('APP_NAME_FULL')) }} pode rescindir imediatamente o presente acordo sem aviso prévio, caso, em seu exclusivo julgamento, você violar qualquer termo ou condição do presente acordo. Após a rescisão, você deverá destruir todos os materiais.
                    </p>
                    <p>
                        <b>Destinatários dos Dados</b><br>
                        Seus Dados não serão compartilhados com terceiros sem o seu consentimento expresso. No entanto, você está sendo informado, neste momento, que os Dados podem ser revelados por força de lei, regulamento ou decisão da autoridade legal competente ou, se for necessário, com o objetivo de preservar os direitos e interesses da {{ strtoupper(env('APP_NAME_FULL')) }}.
                        <br>
                        A {{ strtoupper(env('APP_NAME_FULL')) }} pode compartilhar Dados como parte do seu uso de aplicativos de terceiros disponibilizados na página "https://{{ env('APP_DOMAIN') }}" do Site. A {{ strtoupper(env('APP_NAME_FULL')) }} realiza o compartilhamento dos Dados apenas quando você tiver expressamente concordado com este compartilhamento ao usar cada Aplicativo. Você reconhece e concorda que a política de privacidade dos nossos parceiros se aplica ao uso feito por eles dos Dados que compartilhamos com o seu consentimento ou que eles coletaram diretamente de você.
                        <br>
                        A {{ strtoupper(env('APP_NAME_FULL')) }} também pode compartilhar os seus Dados com o Facebook e/ou Instagram, caso você tenha expressamente concordado em vincular a sua conta {{ strtoupper(env('APP_NAME_FULL')) }} com a sua conta de usuário dessas redes sociais ao se registrar no Site ou configurando a sua conta {{ strtoupper(env('APP_NAME_FULL')) }} para esses redes sociais.
                    </p>
                    <p>
                        <b>Outros</b><br>
                        Os presentes termos de condições serão governados e interpretados de acordo com a legislação brasileira, independentemente de qualquer princípio de conflito de legislação. Caso alguma parte dos presentes Termos e Condições seja considerada ilegal, nula, ou de execução impossível, esta parte será considerada separadamente e não afetará a validade nem a vigência das cláusulas restantes. Toda e qualquer evidência do uso do presente site para fim ilegal será fornecida às autoridades legais. Este é o acordo integral entre as partes envolvidas na utilização deste site. A {{ strtoupper(env('APP_NAME_FULL')) }} pode revisar os presentes Termos e Condições a qualquer momento atualizando este aviso. Os produtos e serviços da {{ strtoupper(env('APP_NAME_FULL')) }} estão disponíveis em várias partes do Brasil. Entretanto, este site da {{ strtoupper(env('APP_NAME_FULL')) }} pode descrever produtos e serviços que não estejam disponíveis no Brasil todo. Este site está ligado a outros sites que não são mantidos pela {{ strtoupper(env('APP_NAME_FULL')) }}. A {{ strtoupper(env('APP_NAME_FULL')) }} não se responsabiliza pelo conteúdo desses sites. A inclusão de qualquer link aos referidos sites não significa que a {{ strtoupper(env('APP_NAME_FULL')) }} os aprove.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
