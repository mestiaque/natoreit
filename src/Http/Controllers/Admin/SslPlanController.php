<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use ME\Nit\Models\SslPlan;

class SslPlanController extends ResourceController
{
    public static $permissions = [
        'index'   => ['site_ssl_view', 'View SSL plans'],
        'create'  => ['site_ssl_create', 'Add SSL plans'],
        'store'   => ['site_ssl_create', 'Add SSL plans'],
        'edit'    => ['site_ssl_edit', 'Edit SSL plans'],
        'update'  => ['site_ssl_edit', 'Edit SSL plans'],
        'destroy' => ['site_ssl_delete', 'Delete SSL plans'],
    ];

    protected string $model = SslPlan::class;
    protected string $route = 'nit.admin.ssl';
    protected string $singular = 'SSL plan';
    protected string $plural = 'SSL plans';

    protected function columns(): array
    {
        return ['sort' => 'Order', 'name' => 'Name', 'price' => 'Price / year', 'note' => 'Note', 'is_active' => 'Shown'];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            'name'      => ['label' => 'Name', 'rules' => ['required', 'string', 'max:100']],
            'price'     => ['label' => 'Price per year', 'rules' => ['required', 'string', 'max:30'], 'hint' => 'As shown, e.g. $8.50'],
            'note'      => ['label' => 'Short note', 'rules' => ['nullable', 'string', 'max:150']],
            'order_url' => ['type' => 'url', 'label' => 'Order link', 'rules' => ['nullable', 'url', 'max:500']],
            ...$this->orderFields(),
        ];
    }
}
