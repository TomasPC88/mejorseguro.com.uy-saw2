<section class="sad-slide portadas portadas-theme hidden">
    <div class="wo-slide-overlay">
        <div class="init-slide-cell">
            <svg class="slide-wo-loading" viewBox="0 0 56 56">
                <g>
                    <circle cx="28" cy="28" id="spinner" r="16"/>
                </g>
            </svg>
        </div>
    </div>
    @if(isset($portadas) && $portadas->count() > 0)
{{--        @if ($portadas->count() > 0)--}}
            @foreach ($portadas as $p)
                <div class="slide-cell">
                    <div class="slide-cell-content">
                        @if($p->medias->isNotEmpty())
                            @if ($p->url != null)
                                <a href="{{ $p->url }}" {{ $p->target == true ? 'target="_blank"' : ''}}>
                                    <img src="{{ $p->first_image->get('large') }}"/>
                                </a>
                            @else
                                <img src="{{ $p->first_image->get('large') }}"/>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
{{--        @endif--}}
    @else
        <div class="slide-cell">
            <div class="slide-cell-content">
                <img src="{{ pw2img('portada/slide1.jpg') }}"/>
            </div>
        </div>
    @endif
</section>