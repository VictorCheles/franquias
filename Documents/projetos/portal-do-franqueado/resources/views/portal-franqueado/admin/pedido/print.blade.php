<html>
    <head>
        <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>Loja</th>
            <th>Solicitado em</th>
            <th>Data prevista de entrega</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ $item->id }}
                </td>
                <td>
                    {{ $item->loja()->first()->nome }}
                </td>
                <td>
                    {{ $item->created_at->format('d/m/Y') }}
                </td>
                <td>
                    {{ $item->data_entrega ? $item->data_entrega->format('d/m/Y') : 'data ainda não foi definida' }}
                </td>
                <td>
                    {!! $item->status_formatted !!}
                </td>
            </tr>
        </tbody>
    </table>
        <table class="table table-bordered table-responsive">
            <tr>
                <th>Produtos</th>
                <td>
                    {!! $item->produtos_formatted !!}
                </td>
            </tr>
            <tr>
                <th>Observações</th>
                <td>{{ $item->observacoes }}</td>
            </tr>
            <tr>
                <th>Solicitado em</th>
                <td>
                    {{ $item->created_at->format('d/m/Y \a\s H:i:s') }}
                </td>
            </tr>
            <tr>
                <th>Data prevista de entrega</th>
                <td>
                    {{ $item->data_entrega ? $item->data_entrega->format('d/m/Y') : 'data ainda não definida' }}
                </td>
            </tr>
            <tr>
                <th>Multa por atraso</th>
                <td>{{ maskMoney($item->multa) }}</td>
            </tr>
            <tr>
                <th>Peso total</th>
                <td>{{ $item->pesoTotal() }}kg</td>
            </tr>
            <tr>
                <th>Valor total</th>
                <td>{{ maskMoney($item->valorTotal() + $item->multa) }}</td>
            </tr>
        </table>
    </body>
    <script>
        window.print();
    </script>
</html>
