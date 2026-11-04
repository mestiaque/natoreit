@extends('nit::master')

@section('title', __($extra['table_title'] ?? $plural))

@include('nit::elements.page-ui')

@push('buttons')
    @foreach($extra['buttons'] ?? [] as $button)
        <a href="{{ $button['url'] }}" class="btn btn-sm btn-light fw-bold"><i class="{{ $button['icon'] }}" aria-hidden="true"></i> {{ __($button['label']) }}</a>
    @endforeach
    @if(Route::has($route . '.create'))@can($can['create'])
        <a href="{{ route($route . '.create', $extra['create_query'] ?? []) }}" class="btn btn-sm btn-nit fw-bold"><i class="fas fa-plus" aria-hidden="true"></i> {{ __('Add :item', ['item' => __($singular)]) }}</a>
    @endcan @endif
@endpush

@php
    $headers = collect($columns)->map(fn ($column) => is_array($column) ? array_diff_key($column, ['format' => 1]) : __($column))->all();
    $cell = function ($row, $key, $column) {
        $value = is_array($column) && isset($column['format']) ? $column['format']($row) : data_get($row, $key);
        if (is_bool($value)) {
            return new \Illuminate\Support\HtmlString($value
                ? '<span class="nx-pill nx-pill-you">' . e(__('Yes')) . '</span>'
                : '<span class="nx-pill">' . e(__('No')) . '</span>');
        }
        return $value;
    };
@endphp

@section('content')
<div class="nx-page">
    @include('nit::admin.shown-on')
    @isset($extra['note'])
        <div class="alert alert-light border d-flex gap-2 align-items-start"><i class="fas fa-circle-info mt-1 text-primary" aria-hidden="true"></i><div>{!! $extra['note'] !!}</div></div>
    @endisset

    @isset($extra['tabs'])
        <ul class="nav nav-pills mb-4 flex-wrap gap-1">
            @foreach($extra['tabs'] as $tab)
                <li class="nav-item"><a @class(['nav-link', 'active' => $tab['active']]) href="{{ $tab['url'] }}">{{ __($tab['label']) }} @isset($tab['count'])<span class="badge bg-light text-dark ms-1">{{ $tab['count'] }}</span>@endisset</a></li>
            @endforeach
        </ul>
    @endisset

    <x-nit::table :rows="$rows" :title="__($extra['table_title'] ?? 'All :items', ['items' => mb_strtolower(__($plural))])" :search="__('Search')" row-actions
        :columns="$headers" :sortable="$sortable" :empty="__('Nothing here yet')">
        @foreach ($rows as $row)
            <tr>
                @foreach($columns as $key => $column)
                    <td @if(is_array($column) && isset($column['class'])) class="{{ $column['class'] }}" @endif>{{ $cell($row, $key, $column) }}</td>
                @endforeach
                <td class="text-end">
                    <div class="nx-actions">
                        @foreach($extra['row_links'] ?? [] as $link)
                            <a href="{{ $link['url']($row) }}" class="nx-icon-btn" title="{{ __($link['label']) }}" aria-label="{{ __($link['label']) }}"><i class="{{ $link['icon'] }}" aria-hidden="true"></i></a>
                        @endforeach
                        @can($can['edit'])
                            <a href="{{ route($route . '.edit', $row->id) }}" class="nx-icon-btn" title="{{ __('Edit') }}" aria-label="{{ __('Edit :name', ['name' => $row->activityLabel()]) }}"><i class="fas fa-pen" aria-hidden="true"></i></a>
                        @endcan
                        @if(Route::has($route . '.destroy'))@can($can['delete'])
                            <x-nit::delete-button :action="route($route . '.destroy', $row->id)" :name="$row->activityLabel()" />
                        @endcan @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </x-nit::table>
</div>
@endsection
