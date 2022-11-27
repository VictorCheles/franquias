@if($solicitacoes->count() > 0)
    <section class="col-xs-12">
        <div class="headline-row">
            <h1 class="section-title caticon sbx">
                <img src="{{ asset('images/brand_small.png') }}"> Solicitações
            </h1>
        </div>
        <table class="table table-condensed">
            <tr>
                <th>Ticket de acompanhamento</th>
                <th>Solicitação</th>
                <th>Solicitado em</th>
                <th>Ultima atualização</th>
                <th>Status</th>
            </tr>
            @foreach($solicitacoes as $item)
                <?php $historico = $item->historico()->orderBy('created_at', 'desc')->get();?>
                <tr>
                    <td>
                        <a href="{{ route('solicitacao.show', $item->id) }}" class="btn btn-info" style="background: transparent; color: #000; border-width: 2px">
                            {!! $item->tag !!}
                        </a>
                    </td>
                    <td>{{ $item->titulo }}</td>
                    <td>{{ $item->created_at->format('d/m/Y \a\s H:i:s') }}</td>
                    <td>
                        @if($historico->count() > 0)
                            {{ $historico->first()->created_at->format('d/m/Y \a\s H:i:s') }}
                        @endif
                    </td>
                    <td>{!! $item->status_formatted !!}</td></td>
                </tr>
            @endforeach
        </table>
        <a class="btn btn-theme-padrao btn-sm btn-min-block pull-right" style="margin-right: 0%" href="{{ route('solicitacao.index') }}"><b>Ver mais</b></a>
    </section>
@endif