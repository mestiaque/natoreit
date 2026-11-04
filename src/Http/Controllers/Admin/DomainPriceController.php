<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use ME\Nit\Models\DomainPrice;

class DomainPriceController extends ResourceController
{
    public static $permissions = [
        'index'   => ['site_domain_view', 'View domain prices'],
        'create'  => ['site_domain_create', 'Add domain prices'],
        'store'   => ['site_domain_create', 'Add domain prices'],
        'edit'    => ['site_domain_edit', 'Edit domain prices'],
        'update'  => ['site_domain_edit', 'Edit domain prices'],
        'destroy' => ['site_domain_delete', 'Delete domain prices'],
    ];

    protected string $model = DomainPrice::class;
    protected string $route = 'nit.admin.domains';
    protected string $singular = 'Domain price';
    protected string $plural = 'Domain prices';
    protected array $search = ['tld'];
    protected array $sortable = ['sort', 'tld'];

    protected function extraViewData(): array
    {
        return ['note' => e(__('Shown under the domain search on the home and domain pages. Live prices in the search results come from the billing system.'))];
    }

    protected function columns(): array
    {
        return ['sort' => 'Order', 'tld' => 'Extension', 'price' => 'Price', 'period' => 'Period', 'is_active' => 'Shown'];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            'tld'    => ['label' => 'Extension', 'rules' => ['required', 'string', 'max:30', 'regex:/^\.[a-z0-9.-]+$/i'], 'hint' => 'With the dot, e.g. .com'],
            'price'  => ['label' => 'Price', 'rules' => ['required', 'string', 'max:30'], 'hint' => 'As shown, e.g. $11.66'],
            'period' => ['label' => 'Period', 'rules' => ['required', 'string', 'max:20'], 'hint' => 'e.g. /yr or /2 yrs'],
            ...$this->orderFields(),
        ];
    }
}
