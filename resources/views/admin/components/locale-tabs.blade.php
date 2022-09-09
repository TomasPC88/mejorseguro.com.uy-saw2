<div class="wo2-form-tabs">
    <div class="tabs is-toggle">
        <ul>
            @foreach(explode(',',config('app.locales')) as $locale)
                <li data-tab="{{"tab-$name-".$locale}}" @if($loop->first) class="is-active" @endif>
                    <a href="#">
                        {{$locale}}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    @foreach(explode(',',config('app.locales')) as $locale)
        <div data-lang="{{"tab-$name-".$locale}}" class="tab @if($loop->first) is-active @endif">
            {{$$locale}}

            {{$slot}}
        </div>
    @endforeach
</div>