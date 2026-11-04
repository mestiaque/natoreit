<?php
namespace ME\Nit\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanPage extends SiteModel
{
    protected $table = 'site_plan_pages';

    protected $casts = ['is_active' => 'boolean'];

    public function features(): HasMany
    {
        return $this->hasMany(PlanFeature::class)->orderBy('sort')->orderBy('id');
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class)->orderBy('sort')->orderBy('id');
    }
}
