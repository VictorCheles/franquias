<section class="col-xs-12">
    <div class="headline-row">
        <h1 class="section-title caticon sbx">
            <img src="{{ asset('images/brand_small.png') }}"> Calendário
        </h1>
    </div>
    <div class="row">
        <div class="col-lg-4 col-sm-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-body no-padding">
                    <div id="calendar">
                        <table class="table table-bordered table-striped text-center no-margin simple-calendar">
                            <thead>
                                <tr>
                                    <td class="reload-simple-calendar" data-month="{{ $preview_date->format('m') }}" data-year="{{ $preview_date->format('Y') }}" style="cursor: pointer"><<</td>
                                    <td colspan="5"><b>{{ ucfirst($cur_date->formatLocalized('%B - %Y')) }}</b></td>
                                    <td class="reload-simple-calendar" data-month="{{ $next_date->format('m') }}" data-year="{{ $next_date->format('Y') }}" style="cursor: pointer">>></td>
                                </tr>
                                <tr>
                                    <th>D</th>
                                    <th>S</th>
                                    <th>T</th>
                                    <th>Q</th>
                                    <th>Q</th>
                                    <th>S</th>
                                    <th>S</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(calendario($cur_date->format('m'), $cur_date->format('Y')) as $week)
                                    <tr>
                                        @foreach($week as $day)
                                            <?php
                                            $color = '';
                                            $bg = '';
                                            $today = '';
                                            ?>
                                            @if($day == date('d') and $cur_date->format('m') == date('m') and date('Y') == $cur_date->format('Y'))
                                                <?php $today = 'class="today"'?>
                                            @endif
                                            @foreach($events as $event)
                                                @if($event->inicio->format('d') == $day)
                                                    <?php
                                                        switch ($event->relacao) {
                                                            case \App\Models\Comunicado::class:
                                                                $color = '#00a65a';
                                                                break;
                                                            case \App\Models\Praca::class:
                                                                $color = '#f39c12';
                                                                break;
                                                        }
                                                    ?>
                                                    @break;
                                                @endif
                                            @endforeach
                                            <td {!! $today !!} style="background-color: {!! $color !!};">
                                                {{ $day }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="overlay" style="display: none;">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div>
        </div>
        @if($calendarioChunks)
            <div class="col-lg-8 col-sm-6 col-xs-12 show-hide-events event-calendar-index">
                <div class="box box-primary">
                    @foreach($calendarioChunks as $chunk)
                        <div class="col-sm-6 col-xs-12 no-padding">
                            <table class="table table-bordered table-striped">
                                @foreach($chunk as $event)
                                    <?php
                                    switch ($event->relacao) {
                                        case \App\Models\Comunicado::class:
                                            $color = '#00a65a';
                                            $url = url('comunicados/ler', $event->relacao_id);
                                            $title = $event->titulo;
                                            break;
                                        case \App\Models\Praca::class:
                                            $url = route('pedido.create');
                                            $color = '#f39c12';
                                            $title = $event->titulo;
                                            break;
                                        case \App\Models\AcaoCorretiva::class:
                                            $color = 'red';
                                            $url = '#';
                                            $title = $event->visita_tecnica->loja->nome . ' | ' . $event->descricao;
                                            break;
                                    }
                                    ?>
                                    <tr class="tr-link" data-url="{{ $url }}">
                                        <td width="10%" style="background: {!! $color !!};">
                                            <b>{{ $event->inicio->format('d') }}</b>
                                        </td>
                                        <td>
                                            {{ $title }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>