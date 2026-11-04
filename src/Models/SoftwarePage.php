<?php
namespace ME\Nit\Models;

class SoftwarePage extends SiteModel
{
    protected $table = 'site_software_pages';

    protected $casts = ['sections' => 'array'];

    /** The sections as typed on the admin form (see Content::parseSections). */
    public function getSectionsTextAttribute(): string
    {
        return \ME\Nit\Support\Content::sectionsToText($this->sections ?? []);
    }

    public function activityLabel(): string
    {
        return $this->title;
    }
}
