<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use ME\Nit\Models\SoftwarePage;
use ME\Nit\Support\Content;

/**
 * The POS, CRM and multi-vendor feature pages (edit only; the pages are fixed).
 */
class SoftwarePageController extends ResourceController
{
    public static $permissions = [
        'index'  => ['site_software_view', 'View software pages'],
        'edit'   => ['site_software_edit', 'Edit software pages'],
        'update' => ['site_software_edit', 'Edit software pages'],
    ];

    protected string $model = SoftwarePage::class;
    protected string $route = 'nit.admin.software';
    protected string $singular = 'Software page';
    protected string $plural = 'Software pages';
    protected array $search = ['title'];
    protected array $sortable = ['title'];
    protected string $defaultSort = 'id';

    private const ADDRESSES = ['pos' => '/pos-software', 'crm' => '/crm-software', 'multi_vendor' => '/multi-vendor-ecommerce'];

    protected function abilities(): array
    {
        return ['create' => 'site_software_none', 'edit' => 'site_software_edit', 'delete' => 'site_software_none'];
    }

    protected function columns(): array
    {
        return [
            'title' => 'Title',
            'key' => ['label' => 'Address', 'format' => fn ($p) => self::ADDRESSES[$p->key] ?? $p->key],
            'sections' => ['label' => 'Sections', 'format' => fn ($p) => count($p->sections ?? [])],
        ];
    }

    protected function fields(?Model $record = null): array
    {
        $multi = $record?->key === 'multi_vendor';

        return [
            'title'         => ['label' => 'Title', 'rules' => ['required', 'string', 'max:150']],
            'intro'         => ['type' => 'textarea', 'label' => 'Introduction', 'rules' => ['nullable', 'string', 'max:5000'], 'wide' => true,
                                'hint' => $multi ? 'First paragraph: under the title. Second paragraph: above the features. Separate them with an empty line.' : null],
            'sections_text' => ['type' => 'textarea', 'label' => 'Features', 'rules' => ['required', 'string', 'max:30000'], 'rows' => 22, 'wide' => true,
                                'hint' => $multi
                                    ? 'Write "# Block heading" for a new block, "## Section title" for a card and one feature per line below it.'
                                    : 'Write "## Section title" for each card and one feature per line below it.'],
        ];
    }

    protected function prepare(Request $request, array $data, ?Model $record = null): array
    {
        $data['sections'] = Content::parseSections($data['sections_text'] ?? '');
        unset($data['sections_text']);

        return $data;
    }

    public function create(Request $request)
    {
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
    }

    public function destroy(string $id)
    {
        abort(404);
    }
}
