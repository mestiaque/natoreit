@extends('nit::master')

@section('title', __('Dashboard'))

@include('nit::elements.page-ui')

@push('buttons')
    <a href="{{ route('nit.site.home') }}" target="_blank" rel="noopener" class="btn btn-sm btn-light fw-bold"><i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i> {{ __('Open the website') }}</a>
@endpush

@section('content')
<div class="nx-page">
    <div class="row g-4 mb-5">
        @foreach($cards as [$label, $count, $icon, $route, $ability])
            @can($ability)
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route($route) }}" class="nx-panel d-flex align-items-center gap-3 p-4 h-100 text-reset text-decoration-none">
                        <span class="d-inline-grid rounded-3 text-white" style="place-items: center; width: 48px; height: 48px; background: var(--nx-primary)"><i class="{{ $icon }} fs-4" aria-hidden="true"></i></span>
                        <span>
                            <span class="d-block fs-2 fw-bold lh-1">{{ $count }}</span>
                            <span class="text-muted">{{ __($label) }}</span>
                        </span>
                    </a>
                </div>
            @endcan
        @endforeach
    </div>

    <x-nit::card :title="__('Latest website changes')" :padding="false">
        @if($recent->isEmpty())
            <x-nit::empty-state icon="fas fa-clock-rotate-left" :title="__('No changes yet')" />
        @else
            <ul class="list-unstyled mb-0">
                @foreach($recent as $log)
                    <li class="d-flex justify-content-between gap-3 px-4 py-3 border-bottom">
                        <span>{{ $log->description }}</span>
                        <span class="text-muted text-nowrap small">{{ $log->user?->name ?? __('System') }} · {{ $log->created_at?->diffForHumans() }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-nit::card>
</div>
@endsection
