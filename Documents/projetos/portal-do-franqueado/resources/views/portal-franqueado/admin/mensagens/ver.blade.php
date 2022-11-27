@extends('portal-franqueado.admin.mensagens.layout')

@section('message-action')
    <a href="{{ route('admin.mensagens') }}" class="btn btn-danger btn-block margin-bottom">Voltar para a caixa de entrada</a>
@endsection
@section('mail-content')
    <div class="col-md-9">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Ler Mensagem</h3>

                <div class="box-tools pull-right">
                    <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                    <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <div class="mailbox-read-info">
                    <h3>{{ $item->subject }}</h3>
                    <h5>
                        <small>
                            de {{ $item->from->nome }} ({{ $item->from->email }})
                            <span class="mailbox-read-time pull-right">{{  $item->created_at->formatLocalized('%d %b. %Y %H:%I') }}</span>
                        </small>
                    </h5>
                    <h5>
                        <small>
                            para {{ $item->to->nome }} ({{ $item->to->email }})
                        </small>
                    </h5>
                </div>
                <div class="mailbox-read-message">
                    {!! $item->text !!}
                </div>
            </div>
            <div class="box-footer">
                <ul class="mailbox-attachments clearfix">
                    @foreach ($item->attachments as $a)
                        <li>
                            <span class="mailbox-attachment-icon"><i class="fa fa-file-o"></i></span>
                            <div class="mailbox-attachment-info">
                                <a target="_blank" href="{{ asset('uploads/mensagens/' . $a)}}" class="mailbox-attachment-name">
                                    <i class="fa fa-paperclip"></i>
                                    {{ str_limit($a, 15) . ' ' . substr($a, -4) }}
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            @if($item->from->id != auth()->user()->id)
                <div class="box-footer">
                    <div class="pull-right">
                        <a href="{{ route('admin.mensagens.create', $item->id) }}" class="btn btn-default btn-sm">
                            <i class="fa fa-reply"></i> Responder
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
