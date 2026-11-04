<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use ME\Nit\Models\PlanFeature;
use ME\Nit\Models\PlanPage;

/**
 * Hosting / VPS / SEO pages: page texts and the rows of the comparison table.
 * The plans themselves are edited in PlanController.
 */
class PlanPageController extends ResourceController
{
    public static $permissions = [
        'index'   => ['site_plan_page_view', 'View plan pages'],
        'create'  => ['site_plan_page_create', 'Add plan pages'],
        'store'   => ['site_plan_page_create', 'Add plan pages'],
        'edit'    => ['site_plan_page_edit', 'Edit plan pages'],
        'update'  => ['site_plan_page_edit', 'Edit plan pages'],
        'destroy' => ['site_plan_page_delete', 'Delete plan pages'],
    ];

    protected string $model = PlanPage::class;
    protected string $route = 'nit.admin.plan-pages';
    protected string $singular = 'Plan page';
    protected string $plural = 'Plan pages';
    protected array $search = ['title', 'slug'];
    protected array $sortable = ['sort', 'title', 'slug'];

    protected function extraViewData(): array
    {
        return [
            'note' => e(__('Hosting, VPS and SEO pages. An address that contains "hosting" also appears in the header\'s Hosting menu.')),
            'row_links' => [['icon' => 'fas fa-layer-group', 'label' => 'Plans', 'url' => fn ($page) => route('nit.admin.plans.index', ['page' => $page->id])]],
            'form_partial' => 'nit::admin.plan-features',
        ];
    }

    protected function columns(): array
    {
        return [
            'sort' => 'Order', 'title' => 'Title',
            'slug' => ['label' => 'Address', 'format' => fn ($p) => '/' . $p->slug],
            'plans' => ['label' => 'Plans', 'format' => fn ($p) => $p->plans()->count()],
            'is_active' => 'Shown',
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            'title'          => ['label' => 'Page name', 'rules' => ['required', 'string', 'max:100']],
            'slug'           => ['label' => 'Address', 'rules' => ['required', 'string', 'max:80', 'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/', Rule::unique('site_plan_pages', 'slug')->ignore($record?->id),
                                 Rule::notIn(['web-design', 'multi-vendor-ecommerce', 'pos-software', 'crm-software', 'software-solution', 'ssl', 'domain', 'about-us', 'portfolio', 'customer-opinion', 'contact-us', 'coupon', 'admin', 'login', 'settings', 'profile'])],
                                 'hint' => 'Lower-case letters, numbers and dashes, e.g. shared-hosting'],
            'heading'        => ['label' => 'Heading above the plans', 'rules' => ['nullable', 'string', 'max:150']],
            'eyebrow'        => ['label' => 'Small label above the title', 'rules' => ['nullable', 'string', 'max:40']],
            'icon'           => ['type' => 'icon', 'label' => 'Icon', 'rules' => ['nullable', 'string', 'max:60']],
            'menu_text'      => ['label' => 'Line in the Hosting menu', 'rules' => ['nullable', 'string', 'max:100']],
            'lead'           => ['type' => 'textarea', 'label' => 'Introduction', 'rules' => ['nullable', 'string', 'max:500'], 'rows' => 2],
            'includes_title' => ['label' => '"All plans include" heading', 'rules' => ['nullable', 'string', 'max:150'], 'hint' => 'Leave empty to hide that section.'],
            'about'          => ['type' => 'textarea', 'label' => 'Text under that section', 'rules' => ['nullable', 'string', 'max:5000'], 'hint' => 'Separate paragraphs with an empty line.'],
            ...$this->orderFields(),
        ];
    }

    /** Validates the comparison rows typed on the form as well. */
    protected function validated(Request $request, ?Model $record = null): array
    {
        $request->validate([
            'features'           => ['nullable', 'array'],
            'features.*.label'   => ['nullable', 'string', 'max:150'],
            'features.*.sort'    => ['nullable', 'integer', 'min:0', 'max:9999'],
            'new_features'       => ['nullable', 'string', 'max:5000'],
        ]);

        return parent::validated($request, $record);
    }

    protected function prepare(Request $request, array $data, ?Model $record = null): array
    {
        unset($data['features'], $data['new_features']);

        return parent::prepare($request, $data, $record);
    }

    /** Saves the comparison rows: edited labels, removed rows and new ones (one per line). */
    protected function saved(Request $request, Model $record): void
    {
        foreach ((array) $request->input('features', []) as $id => $row) {
            $feature = $record->features()->whereKey($id)->first();
            if (! $feature) {
                continue;
            }
            if (! empty($row['remove']) || trim((string) ($row['label'] ?? '')) === '') {
                $feature->delete();
            } else {
                $feature->update(['label' => trim($row['label']), 'sort' => (int) ($row['sort'] ?? $feature->sort)]);
            }
        }

        $next = (int) $record->features()->max('sort');
        foreach (preg_split('/\R/', (string) $request->input('new_features')) as $label) {
            if (($label = trim($label)) !== '') {
                PlanFeature::create(['plan_page_id' => $record->id, 'label' => $label, 'sort' => ++$next]);
            }
        }
    }
}
