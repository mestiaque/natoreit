<?php
namespace ME\Nit\Models;

class SslPlan extends SiteModel
{
    protected $table = 'site_ssl_plans';

    protected $casts = ['is_active' => 'boolean'];
}
