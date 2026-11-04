<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use ME\Nit\Models\Review;

class ReviewController extends ResourceController
{
    public static $permissions = [
        'index'   => ['site_review_view', 'View customer reviews'],
        'create'  => ['site_review_create', 'Add customer reviews'],
        'store'   => ['site_review_create', 'Add customer reviews'],
        'edit'    => ['site_review_edit', 'Edit customer reviews'],
        'update'  => ['site_review_edit', 'Edit customer reviews'],
        'destroy' => ['site_review_delete', 'Delete customer reviews'],
    ];

    protected string $model = Review::class;
    protected string $route = 'nit.admin.reviews';
    protected string $singular = 'Review';
    protected string $plural = 'Customer reviews';
    protected array $search = ['author', 'title', 'text'];
    protected array $sortable = ['sort', 'author', 'rating'];

    protected function columns(): array
    {
        return [
            'sort' => 'Order', 'author' => 'Customer', 'title' => 'Title',
            'rating' => ['label' => 'Rating', 'format' => fn ($r) => str_repeat('★', $r->rating) . str_repeat('☆', 5 - $r->rating)],
            'is_active' => 'Shown',
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            'author'     => ['label' => 'Customer name', 'rules' => ['required', 'string', 'max:100']],
            'title'      => ['label' => 'Title', 'rules' => ['nullable', 'string', 'max:150'], 'hint' => 'e.g. Web Hosting'],
            'rating'     => ['type' => 'select', 'label' => 'Rating', 'rules' => ['required', 'integer', 'between:1,5'], 'options' => [5 => '★★★★★ 5', 4 => '★★★★ 4', 3 => '★★★ 3', 2 => '★★ 2', 1 => '★ 1']],
            'date_label' => ['label' => 'Date', 'rules' => ['nullable', 'string', 'max:50'], 'hint' => 'As shown, e.g. December 27, 2020'],
            'text'       => ['type' => 'textarea', 'label' => 'Review', 'rules' => ['required', 'string', 'max:2000'], 'wide' => true],
            ...$this->orderFields(),
        ];
    }

    protected function newRecord(\Illuminate\Http\Request $request): Model
    {
        return parent::newRecord($request)->fill(['rating' => 5, 'date_label' => now()->format('F j, Y')]);
    }
}
