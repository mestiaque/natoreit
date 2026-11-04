<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Nit\Master\Support\Nit;
use Nit\Master\Support\Table;

/**
 * List / add / edit / delete for one kind of website content.
 *
 * A subclass names its model, route and texts, describes the form in fields()
 * and declares its abilities in a static $permissions array (read by the
 * nit-master roles page and the `authorization` middleware).
 *
 * Field types: text, url, number, textarea, lines (one item per line),
 * select, checkboxes, boolean, icon (Bootstrap icon class), image (upload or URL).
 */
abstract class ResourceController extends Controller
{
    /** @var class-string<Model> */
    protected string $model;

    /** Route name prefix, e.g. "nit.admin.packages". */
    protected string $route;

    protected string $singular;

    protected string $plural;

    /** Columns searched by the list's search box. */
    protected array $search = ['name'];

    /** Columns the list can be sorted by. */
    protected array $sortable = ['sort', 'name'];

    protected string $defaultSort = 'sort';

    /** Folder for uploaded images on the panel's disk. */
    protected string $uploadFolder = 'site';

    /**
     * Form fields: name => ['type', 'label', 'rules', 'options' => [], 'hint', 'wide' => bool].
     */
    abstract protected function fields(?Model $record = null): array;

    /** List columns: attribute => label, or ['label', 'format' => fn ($row)]. */
    abstract protected function columns(): array;

    public function index(Request $request)
    {
        return view('nit::admin.resource.index', $this->viewData() + [
            'rows' => $this->table($request)->paginate(25)->withQueryString(),
        ]);
    }

    public function create(Request $request)
    {
        return view('nit::admin.resource.form', $this->viewData() + [
            'record' => $this->newRecord($request),
            'fields' => $this->fields(),
        ]);
    }

    public function store(Request $request)
    {
        $record = $this->model::create($this->prepare($request, $this->validated($request)));
        $this->saved($request, $record);

        return redirect()->to($this->backUrl($record))->with('success', __(':name was added.', ['name' => $record->activityLabel()]));
    }

    public function edit(Request $request, string $id)
    {
        $record = $this->model::findOrFail($id);

        return view('nit::admin.resource.form', $this->viewData() + [
            'record' => $record,
            'fields' => $this->fields($record),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $record = $this->model::findOrFail($id);
        $record->update($this->prepare($request, $this->validated($request, $record), $record));
        $this->saved($request, $record);

        return redirect()->to($this->backUrl($record))->with('success', __(':name was updated.', ['name' => $record->activityLabel()]));
    }

    public function destroy(string $id)
    {
        $record = $this->model::findOrFail($id);
        $this->deleteImages($record);
        $record->delete();

        return redirect()->to($this->backUrl($record))->with('success', __(':name was deleted.', ['name' => $record->activityLabel()]));
    }

    /** Shared by the list and form views. */
    protected function viewData(): array
    {
        return [
            'route'    => $this->route,
            'singular' => $this->singular,
            'plural'   => $this->plural,
            'columns'  => $this->columns(),
            'sortable' => $this->sortable,
            'can'      => $this->abilities(),
            'extra'    => $this->extraViewData(),
            'shownOn'  => \ME\Nit\Support\AdminMap::links($this->shownOnArea()),
        ];
    }

    /** Key in AdminMap::SHOWN_ON, by default the last part of the route name. */
    protected function shownOnArea(): string
    {
        return substr($this->route, strrpos($this->route, '.') + 1);
    }

    /** Extra variables for a subclass's views (filters, tabs, notes). */
    protected function extraViewData(): array
    {
        return [];
    }

    /** Ability keys of this resource, for @can in the views. */
    protected function abilities(): array
    {
        $p = static::$permissions;

        return ['create' => $p['create'][0], 'edit' => $p['edit'][0], 'delete' => $p['destroy'][0]];
    }

    protected function query(Request $request): Builder
    {
        return $this->model::query();
    }

    protected function table(Request $request): Table
    {
        return Table::make($this->query($request), $request)
            ->search($this->search)
            ->sortable($this->sortable, $this->defaultSort);
    }

    protected function newRecord(Request $request): Model
    {
        $next = (int) $this->query($request)->max('sort') + 1;

        return new $this->model(['sort' => $next, 'is_active' => true]);
    }

    /** Where to go after saving or deleting. */
    protected function backUrl(Model $record): string
    {
        return route($this->route . '.index');
    }

    /** After a create or update, e.g. to save related rows. */
    protected function saved(Request $request, Model $record): void
    {
    }

    protected function validated(Request $request, ?Model $record = null): array
    {
        $rules = [];
        foreach ($this->fields($record) as $name => $field) {
            $type = $field['type'] ?? 'text';
            $rules[$name] = $field['rules'] ?? ['nullable'];
            if ($type === 'image') {
                $rules[$name] = ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'];
                $rules[$name . '_url'] = ['nullable', 'url', 'max:500'];
            } elseif ($type === 'checkboxes') {
                $rules[$name] = ['nullable', 'array'];
                $rules[$name . '.*'] = ['integer'];
            } elseif ($type === 'boolean') {
                $rules[$name] = ['nullable', 'boolean'];
            }
        }

        return $request->validate($rules, [], $this->attributeNames($record));
    }

    protected function attributeNames(?Model $record): array
    {
        return collect($this->fields($record))->map(fn ($field) => Str::lower($field['label'] ?? ''))->all();
    }

    /** Turns the validated input into column values (booleans, images, lists). */
    protected function prepare(Request $request, array $data, ?Model $record = null): array
    {
        foreach ($this->fields($record) as $name => $field) {
            $type = $field['type'] ?? 'text';
            if ($type === 'boolean') {
                $data[$name] = $request->boolean($name);
            } elseif ($type === 'checkboxes') {
                $data[$name] = array_map('intval', $request->input($name, []));
            } elseif ($type === 'image') {
                unset($data[$name], $data[$name . '_url']);
                $data += $this->image($request, $name, $record);
            } elseif ($type === 'lines' && isset($data[$name])) {
                $data[$name] = implode("\n", array_filter(array_map('trim', preg_split('/\R/', $data[$name]))));
            }
        }

        return $data;
    }

    /** An uploaded file wins over a typed URL; "remove" clears it. Replaced uploads are deleted. */
    protected function image(Request $request, string $name, ?Model $record): array
    {
        $old = $record?->{$name};
        if ($request->hasFile($name)) {
            $this->deleteUpload($old);

            return [$name => $request->file($name)->store($this->uploadFolder, Nit::disk())];
        }
        if ($request->boolean($name . '_remove')) {
            $this->deleteUpload($old);

            return [$name => null];
        }
        if ($request->filled($name . '_url') && $request->input($name . '_url') !== $old) {
            $this->deleteUpload($old);

            return [$name => $request->input($name . '_url')];
        }

        return [];
    }

    protected function deleteImages(Model $record): void
    {
        foreach ($this->fields($record) as $name => $field) {
            if (($field['type'] ?? null) === 'image') {
                $this->deleteUpload($record->{$name});
            }
        }
    }

    /** Only files on the panel's disk are deleted, never external URLs. */
    protected function deleteUpload(?string $path): void
    {
        if ($path && ! preg_match('~^(https?:)?//~', $path)) {
            Nit::delete($path);
        }
    }

    /** Common fields at the end of most forms. */
    protected function orderFields(bool $active = true): array
    {
        $fields = ['sort' => ['type' => 'number', 'label' => 'Order', 'rules' => ['required', 'integer', 'min:0', 'max:9999'], 'hint' => 'Smaller numbers come first.']];
        if ($active) {
            $fields['is_active'] = ['type' => 'boolean', 'label' => 'Show on the website'];
        }

        return $fields;
    }
}
