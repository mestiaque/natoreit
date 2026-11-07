<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use ME\Nit\Models\Plan;
use ME\Nit\Models\PlanPage;

/**
 * Plans of one plan page, with their value for each comparison row.
 */
class PlanController extends ResourceController
{
    public static $permissions = [
        'index'   => ['site_plan_view', 'View plans'],
        'create'  => ['site_plan_create', 'Add plans'],
        'store'   => ['site_plan_create', 'Add plans'],
        'edit'    => ['site_plan_edit', 'Edit plans'],
        'update'  => ['site_plan_edit', 'Edit plans'],
        'destroy' => ['site_plan_delete', 'Delete plans'],
    ];

    protected string $model = Plan::class;
    protected string $route = 'nit.admin.plans';
    protected string $singular = 'Plan';
    protected string $plural = 'Plans';
    protected array $sortable = ['sort', 'name'];

    private ?PlanPage $page = null;

    // Plans always belong to a page: without ?page= go to the list of pages
    public function index(Request $request)
    {
        return $request->filled('page') ? parent::index($request) : redirect()->route('nit.admin.plan-pages.index');
    }

    public function create(Request $request)
    {
        return $request->filled('page') ? parent::create($request) : redirect()->route('nit.admin.plan-pages.index');
    }

    private function page(?Model $record = null): PlanPage
    {
        // The plan being edited, else ?page= (list, new plan) or the submitted page
        $record ??= request()->route('id') ? Plan::find(request()->route('id')) : null;

        return $this->page ??= PlanPage::findOrFail($record?->plan_page_id ?: request('page') ?: request('plan_page_id'));
    }

    protected function query(Request $request): Builder
    {
        return Plan::where('plan_page_id', $this->page()->id);
    }

    protected function extraViewData(): array
    {
        $page = $this->page();

        return [
            'table_title'  => __('Plans of :page', ['page' => $page->title]),
            'create_query' => ['page' => $page->id],
            'buttons'      => [
                ['url' => route('nit.admin.plan-pages.index'), 'icon' => 'fas fa-arrow-left', 'label' => 'Plan pages'],
                ['url' => route('nit.admin.plan-pages.edit', $page->id), 'icon' => 'fas fa-table-list', 'label' => 'Comparison rows'],
            ],
            'back_url'     => route($this->route . '.index', ['page' => $page->id]),
            'form_partial' => 'nit::admin.plan-values',
            'features'     => $page->features,
            'form_note'    => $page->title,
        ];
    }

    protected function backUrl(Model $record): string
    {
        return route($this->route . '.index', ['page' => $record->plan_page_id]);
    }

    protected function newRecord(Request $request): Model
    {
        return parent::newRecord($request)->fill(['plan_page_id' => $this->page()->id, 'period' => '/Y']);
    }

    protected function columns(): array
    {
        return ['sort' => 'Order', 'name' => 'Name', 'price' => 'Price', 'regular_price' => 'Regular', 'period' => 'Per', 'is_featured' => 'Highlighted'];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            'plan_page_id'  => ['type' => 'select', 'label' => 'Page', 'rules' => ['required', 'exists:site_plan_pages,id'], 'options' => PlanPage::ordered()->pluck('title', 'id')->all()],
            'name'          => ['label' => 'Name', 'rules' => ['required', 'string', 'max:100']],
            'price'         => ['label' => 'Price', 'rules' => ['required', 'string', 'max:30'], 'hint' => 'As shown, e.g. $12.00'],
            'regular_price' => ['label' => 'Regular price (crossed out)', 'rules' => ['nullable', 'string', 'max:30']],
            'period'        => ['type' => 'select', 'label' => 'Billed', 'rules' => ['required', 'in:/Y,/M'], 'options' => ['/Y' => __('Per year'), '/M' => __('Per month')]],
            'order_url'     => ['type' => 'url', 'label' => 'Order link', 'rules' => ['nullable', 'url', 'max:500'], 'hint' => 'billing.natoreit.com cart link, e.g. …/cart.php?a=add&pid=1'],
            'is_featured'   => ['type' => 'boolean', 'label' => 'Highlight', 'hint' => 'Show as "Best value"'],
            ...$this->orderFields(false),
        ];
    }

    protected function validated(Request $request, ?Model $record = null): array
    {
        $request->validate(['values' => ['nullable', 'array'], 'values.*' => ['nullable', 'in:yes,no,text'], 'texts' => ['nullable', 'array'], 'texts.*' => ['nullable', 'string', 'max:100']]);

        return parent::validated($request, $record);
    }

    /** Comparison values: "yes", "no" or the typed text, keyed by row id. */
    protected function prepare(Request $request, array $data, ?Model $record = null): array
    {
        $data = parent::prepare($request, $data, $record);
        $page = PlanPage::findOrFail($data['plan_page_id']);
        $values = [];
        foreach ($page->features as $feature) {
            $choice = $request->input("values.{$feature->id}", 'no');
            $values[$feature->id] = $choice === 'text' ? trim((string) $request->input("texts.{$feature->id}")) : $choice;
            if ($values[$feature->id] === '') {
                $values[$feature->id] = 'no';
            }
        }
        $data['values'] = $values;

        return $data;
    }
}
