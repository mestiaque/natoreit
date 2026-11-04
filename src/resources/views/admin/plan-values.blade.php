{{-- Plan form: the plan's value for each comparison row of its page --}}
@php $features = $extra['features']; $values = $record->values ?? []; @endphp
<hr class="my-4">
<h3 class="fs-5 fw-bold mb-1">{{ __('Features of this plan') }}</h3>
<p class="text-muted small mb-3">
    {{ __('The first six rows also show on the plan card.') }}
    <a href="{{ route('nit.admin.plan-pages.edit', $record->plan_page_id) }}">{{ __('Add or rename rows') }}</a>
</p>

@if($features->isEmpty())
    <p class="text-muted">{{ __('This page has no comparison rows yet.') }}</p>
@else
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead><tr><th>{{ __('Row') }}</th><th style="width: 330px">{{ __('Value') }}</th></tr></thead>
            <tbody>
                @foreach($features as $feature)
                    @php
                        $saved = $values[$feature->id] ?? 'no';
                        $choice = old("values.{$feature->id}", in_array($saved, ['yes', 'no'], true) ? $saved : 'text');
                        $text = old("texts.{$feature->id}", in_array($saved, ['yes', 'no'], true) ? '' : $saved);
                    @endphp
                    <tr data-plan-value>
                        <th scope="row" class="fw-semibold">{{ $feature->label }}</th>
                        <td>
                            <div class="d-flex gap-2">
                                <select name="values[{{ $feature->id }}]" class="form-select form-select-sm" style="max-width: 120px" aria-label="{{ __(':row: value', ['row' => $feature->label]) }}"
                                        onchange="this.nextElementSibling.hidden = this.value !== 'text'">
                                    <option value="yes" @selected($choice === 'yes')>✓ {{ __('Yes') }}</option>
                                    <option value="no" @selected($choice === 'no')>– {{ __('No') }}</option>
                                    <option value="text" @selected($choice === 'text')>{{ __('Text') }}</option>
                                </select>
                                <input type="text" name="texts[{{ $feature->id }}]" value="{{ $text }}" class="form-control form-control-sm" placeholder="{{ __('e.g. 10 GB') }}" aria-label="{{ __(':row: text', ['row' => $feature->label]) }}" @if($choice !== 'text') hidden @endif>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
