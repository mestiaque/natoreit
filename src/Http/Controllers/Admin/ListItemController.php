<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use ME\Nit\Models\ListItem;

/**
 * The small icon lists of the website (why choose us, plan includes, …), one tab per list.
 */
class ListItemController extends ResourceController
{
    public static $permissions = [
        'index'   => ['site_list_view', 'View website lists'],
        'create'  => ['site_list_create', 'Add website list items'],
        'store'   => ['site_list_create', 'Add website list items'],
        'edit'    => ['site_list_edit', 'Edit website list items'],
        'update'  => ['site_list_edit', 'Edit website list items'],
        'destroy' => ['site_list_delete', 'Delete website list items'],
    ];

    protected string $model = ListItem::class;
    protected string $route = 'nit.admin.lists';
    protected string $singular = 'List item';
    protected string $plural = 'Website lists';
    protected array $search = ['title', 'text'];
    protected array $sortable = ['sort', 'title'];

    private function currentList(?Model $record = null): string
    {
        $list = $record?->list ?: (request()->route('list') ?: request('list'));

        return array_key_exists((string) $list, ListItem::LISTS) ? $list : array_key_first(ListItem::LISTS);
    }

    protected function shownOnArea(): string
    {
        return 'list:' . $this->currentList();
    }

    protected function query(Request $request): Builder
    {
        return ListItem::where('list', $this->currentList());
    }

    protected function extraViewData(): array
    {
        $list = $this->currentList();
        return [
            'table_title'  => ListItem::LISTS[$list][0],
            'create_query' => ['list' => $list],
            'back_url'     => route($this->route . '.list', $list),
        ];
    }

    protected function backUrl(Model $record): string
    {
        return route($this->route . '.list', $record->list);
    }

    protected function newRecord(Request $request): Model
    {
        return parent::newRecord($request)->fill(['list' => $this->currentList()]);
    }

    protected function columns(): array
    {
        return [
            'sort' => 'Order',
            'icon' => ['label' => 'Icon', 'format' => fn ($i) => $i->icon ? new \Illuminate\Support\HtmlString('<i class="bi ' . e($i->icon) . ' fs-4" aria-hidden="true"></i> <small class="text-muted">' . e($i->icon) . '</small>') : '—'],
            'title' => 'Title',
            'is_active' => 'Shown',
        ];
    }

    protected function fields(?Model $record = null): array
    {
        $list = $this->currentList($record);
        $fields = [
            'list'  => ['type' => 'select', 'label' => 'List', 'rules' => ['required', 'in:' . implode(',', array_keys(ListItem::LISTS))], 'options' => collect(ListItem::LISTS)->map(fn ($m) => __($m[0]))->all()],
            'title' => ['label' => 'Title', 'rules' => ['required', 'string', 'max:150']],
        ];
        if ($list !== 'payment_methods') {
            $fields['icon'] = ['type' => 'icon', 'label' => 'Icon', 'rules' => ['nullable', 'string', 'max:60']];
        }
        if (in_array($list, ['why_choose', 'featured_hosting', 'offer_tabs'], true)) {
            $fields['text'] = ['type' => 'textarea', 'label' => 'Text', 'rules' => ['nullable', 'string', 'max:2000'], 'wide' => true,
                'hint' => $list !== 'why_choose' ? 'First line: the description. Each next line: one bullet point.' : null];
        }
        if (in_array($list, ['domain_links', 'footer_resources'], true)) {
            $fields['url'] = ['type' => 'url', 'label' => 'Link', 'rules' => ['required', 'url', 'max:500']];
        } elseif (in_array($list, ['featured_hosting', 'offer_tabs'], true)) {
            $fields['url'] = ['label' => 'Read more link', 'rules' => ['nullable', 'string', 'max:500'], 'hint' => 'A page of this website, e.g. /shared-hosting, or a full URL.'];
        }
        if ($list === 'footer_resources') {
            unset($fields['icon']);
        }

        return $fields + $this->orderFields();
    }
}
