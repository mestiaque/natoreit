<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use ME\Nit\Models\WebPackage;
use ME\Nit\Support\SiteData;

class WebPackageController extends ResourceController
{
    public static $permissions = [
        'index'   => ['site_package_view', 'View web design packages'],
        'create'  => ['site_package_create', 'Add web design packages'],
        'store'   => ['site_package_create', 'Add web design packages'],
        'edit'    => ['site_package_edit', 'Edit web design packages'],
        'update'  => ['site_package_edit', 'Edit web design packages'],
        'destroy' => ['site_package_delete', 'Delete web design packages'],
    ];

    protected string $model = WebPackage::class;
    protected string $route = 'nit.admin.packages';
    protected string $singular = 'Web design package';
    protected string $plural = 'Web design packages';
    protected array $sortable = ['sort', 'name', 'price'];

    protected function columns(): array
    {
        return [
            'sort' => 'Order', 'name' => 'Name',
            'price' => ['label' => 'Price', 'format' => fn ($p) => SiteData::usd($p->price)],
            'regular_price' => ['label' => 'Regular', 'format' => fn ($p) => $p->regular_price ? SiteData::usd($p->regular_price) : '—'],
            'is_featured' => 'Featured', 'is_active' => 'Shown',
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            'name'          => ['label' => 'Name', 'rules' => ['required', 'string', 'max:100']],
            'icon'          => ['type' => 'icon', 'label' => 'Icon', 'rules' => ['nullable', 'string', 'max:60']],
            'price'         => ['type' => 'number', 'label' => 'Price (USD, one-time)', 'rules' => ['required', 'numeric', 'min:0']],
            'regular_price' => ['type' => 'number', 'label' => 'Regular price (crossed out)', 'rules' => ['nullable', 'numeric', 'min:0'], 'hint' => 'The discount badge is worked out from these two prices.'],
            'renewal_price' => ['type' => 'number', 'label' => 'Renewal per year', 'rules' => ['nullable', 'numeric', 'min:0']],
            'order_url'     => ['type' => 'url', 'label' => 'Order link', 'rules' => ['nullable', 'url', 'max:500'], 'hint' => 'Usually the billing.natoreit.com cart link of the product.'],
            'features'      => ['type' => 'lines', 'label' => 'Features', 'rules' => ['required', 'string', 'max:3000'], 'hint' => 'One feature per line.'],
            'is_featured'   => ['type' => 'boolean', 'label' => 'Highlight', 'hint' => 'Show as "Best seller"'],
            ...$this->orderFields(),
        ];
    }
}
