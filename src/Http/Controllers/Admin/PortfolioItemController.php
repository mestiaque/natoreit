<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use ME\Nit\Models\PortfolioCategory;
use ME\Nit\Models\PortfolioItem;

class PortfolioItemController extends ResourceController
{
    public static $permissions = [
        'index'   => ['site_portfolio_view', 'View portfolio projects'],
        'create'  => ['site_portfolio_create', 'Add portfolio projects'],
        'store'   => ['site_portfolio_create', 'Add portfolio projects'],
        'edit'    => ['site_portfolio_edit', 'Edit portfolio projects'],
        'update'  => ['site_portfolio_edit', 'Edit portfolio projects'],
        'destroy' => ['site_portfolio_delete', 'Delete portfolio projects'],
    ];

    protected string $model = PortfolioItem::class;
    protected string $route = 'nit.admin.portfolio';
    protected string $singular = 'Project';
    protected string $plural = 'Portfolio';
    protected array $search = ['title', 'url'];
    protected array $sortable = ['sort', 'title'];
    protected string $uploadFolder = 'site/portfolio';

    protected function extraViewData(): array
    {
        return ['buttons' => [['url' => route('nit.admin.portfolio-categories.index'), 'icon' => 'fas fa-tags', 'label' => 'Categories']]];
    }

    protected function columns(): array
    {
        $names = PortfolioCategory::pluck('name', 'id');

        return [
            'sort' => 'Order',
            'image' => ['label' => 'Image', 'format' => fn ($p) => $p->image
                ? new \Illuminate\Support\HtmlString('<img src="' . e(\ME\Nit\Support\Content::mediaUrl($p->image)) . '" alt="" class="nx-thumb" style="width:64px;height:48px;object-fit:cover;object-position:top" loading="lazy">')
                : '—'],
            'title' => 'Title',
            'categories' => ['label' => 'Categories', 'format' => fn ($p) => collect($p->categories ?? [])->map(fn ($id) => $names[$id] ?? null)->filter()->join(', ') ?: '—'],
            'is_active' => 'Shown',
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            'title'      => ['label' => 'Title', 'rules' => ['required', 'string', 'max:150']],
            'url'        => ['type' => 'url', 'label' => 'Website link', 'rules' => ['nullable', 'url', 'max:500']],
            'image'      => ['type' => 'image', 'label' => 'Screenshot'],
            'categories' => ['type' => 'checkboxes', 'label' => 'Categories', 'options' => PortfolioCategory::ordered()->pluck('name', 'id')->all()],
            ...$this->orderFields(),
        ];
    }
}
