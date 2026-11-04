<?php
namespace ME\Nit\Models;

class WebPackage extends SiteModel
{
    protected $table = 'site_web_packages';

    protected $casts = ['is_featured' => 'boolean', 'is_active' => 'boolean', 'price' => 'float', 'regular_price' => 'float', 'renewal_price' => 'float'];

    /** One feature per line. */
    public function featureList(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->features))));
    }
}
