@parent
<div class="row">
    <div class="col-lg-3 col-xs-6">
        
        <div class="info-box bg-aqua"  {!! Request::input('filter') ? 'rel="tooltip" title="Este item não é afetado pelo filtro"' : '' !!}>
            <span class="info-box-icon"><i class="fa fa-bookmark-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Promoções Ativas</span>
                <span class="info-box-number">{{ $promocoes->count() }}{!! Request::input('filter') ? '*' : '' !!}</span>
                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    <small>{{ $inicio_semana->format('d/m/Y') }} até {{ $fim_semana->format('d/m/Y') }}</small>
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-ticket"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Cupons Gerados</span>
                <span class="info-box-number">{{ $cupons['total'] }}</span>
                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    <small>{{ $inicio_semana->format('d/m/Y') }} até {{ $fim_semana->format('d/m/Y') }}</small>
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
       <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Cupons Usados</span>
                <span class="info-box-number">{{ $cupons[\App\Models\Cupom::STATUS_USADO] }}</span>
                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    &nbsp;
                </span>
            </div>
        </div>
    </div>


    <div class="col-lg-3 col-xs-6">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Cupons Usados %</span>
                <span class="info-box-number">{{ $cupons['total'] == 0 ? '0' : number_format($cupons[\App\Models\Cupom::STATUS_USADO] * 100 / $cupons['total'], 2, ',', '.') }}%</span>
                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    &nbsp;
                </span>
            </div>
        </div>
    </div>

</div>