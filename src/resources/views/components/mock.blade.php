{{-- CSS-drawn product screenshot placeholder. Fixed 16:10 ratio, so it never causes layout shift. --}}
@props(['variant' => 'dashboard', 'accent' => '#1474b8', 'label' => 'Product screenshot', 'title' => null])
<div {{ $attributes->class(['mock', 'mock--' . $variant]) }} style="--mock-accent: {{ $accent }}" role="img" aria-label="{{ $label }}">
    <div class="mock-bar" aria-hidden="true"><span></span><span></span><span></span>@if($title)<em>{{ $title }}</em>@endif</div>
    <div class="mock-body" aria-hidden="true">
        @switch($variant)
            @case('mobile')
                <div class="mock-phone"><div class="mock-screen"><b></b><i style="width: 70%"></i><i style="width: 45%"></i><span></span><span></span><span></span></div></div>
                <div class="mock-phone"><div class="mock-screen"><i style="width: 55%"></i><span></span><b></b><span></span><i style="width: 80%"></i></div></div>
                @break
            @case('website')
                <div class="mock-main">
                    <div class="mock-site-nav"><b></b><i></i><i></i><i></i><i></i></div>
                    <div class="mock-site-hero"><i></i><i></i><i></i></div>
                    <div class="mock-site-cards">
                        @for($i = 0; $i < 3; $i++)<div class="mock-card"><b></b><i style="width: 70%"></i><i style="width: 45%"></i></div>@endfor
                    </div>
                </div>
                @break
            @default
                <div class="mock-side"><i></i><i></i><i></i><i></i><i></i><i></i></div>
                <div class="mock-main">
                    @if($variant === 'pos')
                        <div class="mock-pos">
                            <div class="mock-products">@for($i = 0; $i < 12; $i++)<i></i>@endfor</div>
                            <div class="mock-receipt"><i style="width: 60%"></i><i></i><i></i><i style="width: 80%"></i><i></i><i style="width: 50%"></i><b></b></div>
                        </div>
                    @elseif($variant === 'table')
                        <div class="mock-toolbar"><i></i><i></i><i></i></div>
                        <div class="mock-table">
                            @for($r = 0; $r < 7; $r++)<div class="mock-tr"><i></i><i></i><i></i><i></i><i></i></div>@endfor
                        </div>
                    @else
                        <div class="mock-tiles">
                            @for($i = 0; $i < 4; $i++)<div class="mock-tile"><b></b><i style="width: 80%"></i></div>@endfor
                        </div>
                        <div class="mock-split">
                            <div class="mock-panel">
                                <i class="d-block" style="height: .6em; width: 30%; border-radius: .3em; background: #dde2ec"></i>
                                @if($variant === 'analytics')
                                    <div class="mock-line-chart">
                                        <svg viewBox="0 0 200 80" preserveAspectRatio="none">
                                            <path d="M0 62 L25 50 L50 56 L75 34 L100 40 L125 22 L150 28 L175 12 L200 18 L200 80 L0 80 Z" fill="var(--mock-accent)" opacity=".14"/>
                                            <path d="M0 62 L25 50 L50 56 L75 34 L100 40 L125 22 L150 28 L175 12 L200 18" fill="none" stroke="var(--mock-accent)" stroke-width="2.2" vector-effect="non-scaling-stroke"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="mock-bars">
                                        @foreach([45, 62, 38, 74, 56, 88, 66, 92] as $h)<i style="height: {{ $h }}%"></i>@endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="mock-panel">
                                @if($variant === 'analytics')
                                    <div class="mock-donut"></div>
                                @else
                                    @for($i = 0; $i < 5; $i++)<div class="mock-row"><i></i><i></i></div>@endfor
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
        @endswitch
    </div>
</div>
