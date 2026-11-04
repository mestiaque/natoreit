<?php
namespace ME\Nit\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plan extends SiteModel
{
    protected $table = 'site_plans';

    protected $casts = ['is_featured' => 'boolean', 'values' => 'array'];

    public function page(): BelongsTo
    {
        return $this->belongsTo(PlanPage::class, 'plan_page_id');
    }

    /** Value of a comparison row: true / false for yes / no, otherwise the text. */
    public function valueFor(int $featureId): bool|string
    {
        $value = $this->values[$featureId] ?? 'no';

        return match ($value) {
            'yes'   => true,
            'no', '' => false,
            default => $value,
        };
    }
}
