@if($vitrines->count() > 0)
    <section class="col-xs-12 visible-lg-block">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                @foreach($vitrines as $k => $v)
                    <li data-target="#carousel-example-generic" data-slide-to="{{ $k }}" class="{{ $k == 0 ? 'active' : '' }}"></li>
                @endforeach
            </ol>
            <div class="carousel-inner">
                @foreach($vitrines as $k => $v)
                    <div class="item {{ $k == 0 ? 'active' : '' }}">
                        <a href="{{ !empty($v->link) ? $v->link : '#' }}" {{ !empty($v->link) ? 'target="_blank"' : '' }}>
                            {{--<img src="http://lorempixel.com/1400/350/business/" class="img-responsive">--}}
                            <img src="{{ $v->img }}" class="img-responsive">
                        </a>
                    </div>
                @endforeach
            </div>
            <a class="left carousel-control visible-lg-block" href="#carousel-example-generic" data-slide="prev">
                <span class="fa fa-angle-left"></span>
            </a>
            <a class="right carousel-control visible-lg-block" href="#carousel-example-generic" data-slide="next">
                <span class="fa fa-angle-right"></span>
            </a>
        </div>
    </section>
@endif