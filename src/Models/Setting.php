<?php
namespace ME\Nit\Models;

class Setting extends SiteModel
{
    protected $table = 'site_settings';

    protected $casts = ['value' => 'array'];

    public function activityLabel(): string
    {
        return $this->key;
    }
}
