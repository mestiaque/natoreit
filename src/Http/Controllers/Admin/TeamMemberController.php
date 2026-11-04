<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use ME\Nit\Models\TeamMember;

class TeamMemberController extends ResourceController
{
    public static $permissions = [
        'index'   => ['site_team_view', 'View team members'],
        'create'  => ['site_team_create', 'Add team members'],
        'store'   => ['site_team_create', 'Add team members'],
        'edit'    => ['site_team_edit', 'Edit team members'],
        'update'  => ['site_team_edit', 'Edit team members'],
        'destroy' => ['site_team_delete', 'Delete team members'],
    ];

    protected string $model = TeamMember::class;
    protected string $route = 'nit.admin.team';
    protected string $singular = 'Team member';
    protected string $plural = 'Team';
    protected array $search = ['name', 'role'];
    protected string $uploadFolder = 'site/team';

    protected function columns(): array
    {
        return [
            'sort' => 'Order',
            'image' => ['label' => 'Photo', 'format' => fn ($m) => $m->image
                ? new \Illuminate\Support\HtmlString('<img src="' . e(\ME\Nit\Support\Content::mediaUrl($m->image)) . '" alt="" class="nx-thumb" style="width:44px;height:44px;object-fit:cover;border-radius:50%" loading="lazy">')
                : '—'],
            'name' => 'Name', 'role' => 'Role', 'is_active' => 'Shown',
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            'name'  => ['label' => 'Name', 'rules' => ['required', 'string', 'max:100']],
            'role'  => ['label' => 'Role', 'rules' => ['nullable', 'string', 'max:150']],
            'image' => ['type' => 'image', 'label' => 'Photo', 'hint' => 'Square photos look best.'],
            'bio'   => ['type' => 'textarea', 'label' => 'Short bio', 'rules' => ['nullable', 'string', 'max:1000']],
            ...$this->orderFields(),
        ];
    }
}
