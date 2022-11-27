@extends('layouts.app')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Dashboard - Score de Promoções
                </h1>
            </div>
        </section>
    </div>
    @include('backend.bi.partials.promocoes.grafico')
@endsection
@section('extra_scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
@endsection