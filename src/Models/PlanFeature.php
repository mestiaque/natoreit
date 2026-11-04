<?php
namespace ME\Nit\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanFeature extends SiteModel
{
    protected $table = 'site_plan_features';

    public function page(): BelongsTo
    {
        return $this->belongsTo(PlanPage::class, 'plan_page_id');
    }

    public function activityLabel(): string
    {
        return $this->label;
    }
}
