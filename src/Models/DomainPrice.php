<?php
namespace ME\Nit\Models;

class DomainPrice extends SiteModel
{
    protected $table = 'site_domain_prices';

    protected $casts = ['is_active' => 'boolean'];

    public function activityLabel(): string
    {
        return $this->tld;
    }
}
