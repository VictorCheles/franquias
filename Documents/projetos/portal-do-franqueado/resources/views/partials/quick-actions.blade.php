<div class="text-right" style="padding-bottom: 10px;">
    @foreach($buttons as $button)
        @if($user->hasRoles([$button['resource']]))
            <a href="{{ $button['url'] }}" class="btn btn-theme-padrao btn-min-block">
                <i class="{{ $button['icon'] }}"></i> {{ $button['title'] }}
            </a>
        @endif
    @endforeach
</div>