<?php
namespace ME\Nit\Models;

class PortfolioItem extends SiteModel
{
    protected $table = 'site_portfolio_items';

    protected $casts = ['categories' => 'array', 'is_active' => 'boolean'];
}
