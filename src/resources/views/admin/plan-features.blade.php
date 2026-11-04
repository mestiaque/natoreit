{{-- Plan page form: rows of the comparison table --}}
<hr class="my-4">
<h3 class="fs-5 fw-bold mb-1">{{ __('Comparison rows') }}</h3>
<p class="text-muted small mb-3">{{ __('Each row is a line of the "all features" table. Every plan sets its value for it (yes, no or a text) on the plan form.') }}</p>

@if($record->exists && $record->features->isNotEmpty())
    <div class="table-responsive mb-3">
        <table class="table table-sm align-middle mb-0">
            <thead><tr><th style="width: 90px">{{ __('Order') }}</th><th>{{ __('Row') }}</th><th class="text-end" style="width: 110px">{{ __('Remove') }}</th></tr></thead>
            <tbody>
                @foreach($record->features as $feature)
                    <tr>
                        <td><input type="number" min="0" name="features[{{ $feature->id }}][sort]" value="{{ old("features.{$feature->id}.sort", $feature->sort) }}" class="form-control form-control-sm" aria-label="{{ __('Order of :row', ['row' => $feature->label]) }}"></td>
                        <td><input type="text" name="features[{{ $feature->id }}][label]" value="{{ old("features.{$feature->id}.label", $feature->label) }}" class="form-control form-control-sm" aria-label="{{ __('Row name') }}"></td>
                        <td class="text-end"><label class="nx-check d-inline-flex"><input type="checkbox" name="features[{{ $feature->id }}][remove]" value="1"> <span class="visually-hidden">{{ __('Remove :row', ['row' => $feature->label]) }}</span></label></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

<x-nit::textarea name="new_features" :label="__('Add rows')" :hint="__('One row per line, e.g. Free SSL. New rows start as \'no\' on every plan.')" rows="3" />
