<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use ME\Nit\Models\PortfolioCategory;
use ME\Nit\Models\PortfolioItem;

class PortfolioCategoryController extends ResourceController
{
    public static $permissions = [
        'index'   => ['site_portfolio_category_view', 'View portfolio categories'],
        'create'  => ['site_portfolio_category_create', 'Add portfolio categories'],
        'store'   => ['site_portfolio_category_create', 'Add portfolio categories'],
        'edit'    => ['site_portfolio_category_edit', 'Edit portfolio categories'],
        'update'  => ['site_portfolio_category_edit', 'Edit portfolio categories'],
        'destroy' => ['site_portfolio_category_delete', 'Delete portfolio categories'],
    ];

    protected string $model = PortfolioCategory::class;
    protected string $route = 'nit.admin.portfolio-categories';
    protected string $singular = 'Portfolio category';
    protected string $plural = 'Portfolio categories';

    protected function extraViewData(): array
    {
        return [
            'buttons'  => [['url' => route('nit.admin.portfolio.index'), 'icon' => 'fas fa-arrow-left', 'label' => 'Portfolio']],
            'note'     => e(__('Categories become the filter buttons on the portfolio page. A category without projects is not shown.')),
            'back_url' => route('nit.admin.portfolio-categories.index'),
        ];
    }

    protected function columns(): array
    {
        $counts = [];
        foreach (PortfolioItem::pluck('categories') as $ids) {
            foreach (array_unique($ids ?? []) as $id) {
                $counts[$id] = ($counts[$id] ?? 0) + 1;
            }
        }

        return ['sort' => 'Order', 'name' => 'Name', 'projects' => ['label' => 'Projects', 'format' => fn ($c) => $counts[$c->id] ?? 0]];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            'name' => ['label' => 'Name', 'rules' => ['required', 'string', 'max:60']],
            ...$this->orderFields(false),
        ];
    }

    protected function newRecord(\Illuminate\Http\Request $request): Model
    {
        return new PortfolioCategory(['sort' => (int) PortfolioCategory::max('sort') + 1]);
    }

    /** Projects keep no ids of a deleted category. */
    public function destroy(string $id)
    {
        PortfolioItem::all()->each(function ($item) use ($id) {
            if (in_array((int) $id, $item->categories ?? [], true)) {
                $item->update(['categories' => array_values(array_diff($item->categories, [(int) $id]))]);
            }
        });

        return parent::destroy($id);
    }
}
