@extends('portal-franqueado.admin.mensagens.layout')

@section('extra_styles')
    @parent
    <style>
        .lidas
        {
            background: rgba(243,243,243,.85) !important;
        }
        .nao-lidas
        {
            background: #fff !important;
        }
        .btn-box-tool
        {
            color: #333 !important;
        }
    </style>
@endsection

@section('message-action')
    @if(Auth::user()->isAdmin())
        <a href="{{ route('admin.mensagens.create') }}" class="btn btn-danger btn-block margin-bottom">Escrever mensagem</a>
    @endif
@endsection
@section('mail-content')
    <div class="col-md-9">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $titulo }}</h3>
                <div class="box-tools pull-right">
                    <a href="#" class="btn-box-tool" data-toggle="modal" data-target="#modal-filter">
                        <i class="fa fa-filter"></i> Filtro
                    </a>
                    <a href="{{ url()->current() }}" class="btn-box-tool">
                        <i class="fa fa-close"></i> Limpar filtro
                    </a>
                </div>
            </div>
            <div class="box-body no-padding">
                <div class="mailbox-controls">
                    <button type="button" onClick="window.location.reload()" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                        {{ paginationCounters($lista) }}
                    </div>
                </div>
                <div class="table-responsive mailbox-messages">
                    <table class="table">
                        <tbody>
                            @foreach($lista as $m)
                                @php
                                    $class = 'class="lidas"';
                                    if(is_null($m->read_in))
                                    {
                                        $class = 'class="nao-lidas"';
                                    }
                                @endphp
                                <tr {!! $class !!}>
                                    <td>
                                        <img src="{{ $m->$quem->thumbnail }}" width="45" height="45">
                                    </td>
                                    <td class="mailbox-name"><a style="color: #333;" href="{{ route('admin.mensagens.show', $m->id) }}">{{ $m->$quem->nome }}</a></td>
                                    <td class="mailbox-subject"><b>{{ $m->subject }}</b> - {{ strip_tags(str_limit($m->text, 25)) }}</td>
                                    <td class="mailbox-attachment">
                                        @if(count($m->attachments) > 0)
                                            <i class="fa fa-paperclip"></i>
                                        @endif
                                    </td>
                                    <td class="mailbox-date">{{ $m->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box-footer no-padding">
                <div class="text-center pagination-black">
                    {{ $lista->appends(Request::all())->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('portal-franqueado.admin.mensagens.modal-filter')
@endsection
