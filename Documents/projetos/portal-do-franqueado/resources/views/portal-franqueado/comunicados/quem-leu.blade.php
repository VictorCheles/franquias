<div class="row">
    <div class="col-xs-12">
        <div class="box box-black box-solid collapsed-box">
            <div class="box-header with-border" style="cursor: pointer" data-widget="collapse">
                <i class="fa fa-check"></i>
                <h3 class="box-title">Quem já leu ({{ $lidos->count() }})</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="progress-group">
                    <span class="progress-text">Total</span>
                    <span class="progress-number"><b>{{ $lidos->count() }}</b>/{{ $item->destinatarios->count() }}</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-green" style="width: {{ number_format($porcentagem_lido, 0) }}%"></div>
                    </div>
                </div>

                @foreach($lidos as $user)
                    <div class="post">
                        <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="{{ $user->user->thumbnail }}">
                            <span class="username">
                                <a href="#">{{ $user->user->nome }}</a>
                            </span>
                            <span class="description">
                                Visualizado {{ $user->updated_at->format('d/m/Y \- H:i') }}<br>
                                Lido depois de {{ $user->updated_at->diffInHours($item->created_at) }}h
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-black box-solid collapsed-box">
            <div class="box-header with-border" style="cursor: pointer" data-widget="collapse">
                <i class="fa fa-times"></i>
                <h3 class="box-title">Quem não leu ({{ $nao_lidos->count() }})</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div> 
            <div class="box-body">
                @foreach($nao_lidos as $user)
                    <div class="post">
                        <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="{{ $user->user->thumbnail }}">
                            <span class="username">
                                <a href="#">{{ $user->user->nome }}</a>
                            </span>
                            {{--<span class="description">--}}
                                {{--Visualizado {{ $user->updated_at->format('d/m/Y \- H:i') }}<br>--}}
                                {{--Lido depois de {{ $user->updated_at->diffInHours($item->created_at) }}h--}}
                            {{--</span>--}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>