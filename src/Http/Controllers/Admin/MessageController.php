<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use ME\Nit\Models\Message;
use Nit\Master\Support\Table;

/**
 * Inbox of the website's contact form and newsletter sign-ups (read and delete only).
 */
class MessageController extends ResourceController
{
    public static $permissions = [
        'index'   => ['site_message_view', 'Read website messages'],
        'edit'    => ['site_message_view', 'Read website messages'],
        'export'  => ['site_message_view', 'Read website messages'],
        'destroy' => ['site_message_delete', 'Delete website messages'],
    ];

    protected string $model = Message::class;
    protected string $route = 'nit.admin.messages';
    protected string $singular = 'Message';
    protected string $plural = 'Messages';
    protected array $search = ['name', 'email', 'phone', 'message'];
    protected array $sortable = ['created_at', 'name', 'email'];
    protected string $defaultSort = '-created_at';

    private function type(): string
    {
        return array_key_exists((string) request('type'), Message::TYPES) ? request('type') : 'contact';
    }

    protected function abilities(): array
    {
        return ['create' => 'site_message_none', 'edit' => 'site_message_view', 'delete' => 'site_message_delete'];
    }

    protected function shownOnArea(): string
    {
        return 'messages';
    }

    protected function query(Request $request): Builder
    {
        return Message::where('type', $this->type());
    }

    protected function extraViewData(): array
    {
        $unread = Message::whereNull('read_at')->selectRaw('type, count(*) as n')->groupBy('type')->pluck('n', 'type');

        return [
            'tabs' => collect(Message::TYPES)->map(fn ($label, $key) => [
                'label' => $label, 'url' => route($this->route . '.index', ['type' => $key]), 'active' => $key === $this->type(), 'count' => $unread[$key] ?? null,
            ])->values()->all(),
            'table_title' => Message::TYPES[$this->type()],
            'buttons' => [['url' => route($this->route . '.export', ['type' => $this->type()]), 'icon' => 'fas fa-file-csv', 'label' => 'Download CSV']],
        ];
    }

    protected function columns(): array
    {
        $bold = fn ($m, $text) => $m->read_at ? $text : new HtmlString('<strong>' . e($text) . '</strong>');
        $columns = [
            'created_at' => ['label' => 'Received', 'format' => fn ($m) => $bold($m, $m->created_at?->format('d M Y, h:i A'))],
            'name' => ['label' => 'Name', 'format' => fn ($m) => $bold($m, $m->name ?? '—')],
            'email' => 'Email',
        ];
        if ($this->type() !== 'newsletter') {
            $columns += [
                'phone' => 'Mobile',
                'subject' => ['label' => 'Subject', 'format' => fn ($m) => Message::SUBJECTS[$m->subject] ?? $m->subject],
                'message' => ['label' => 'Message', 'format' => fn ($m) => Str::limit($m->message, 60)],
            ];
        } else {
            unset($columns['name']);
        }

        return $columns;
    }

    protected function fields(?Model $record = null): array
    {
        return [];
    }

    /** Opens a message and marks it as read. */
    public function edit(Request $request, string $id)
    {
        $message = Message::findOrFail($id);
        if (! $message->read_at) {
            $message->forceFill(['read_at' => now()])->save();
        }

        return view('nit::admin.message', ['message' => $message, 'back' => route($this->route . '.index', ['type' => $message->type])]);
    }

    public function export(Request $request)
    {
        return Table::make($this->query($request), $request)->search($this->search)->sortable($this->sortable, $this->defaultSort)->csv([
            'Received' => fn ($m) => $m->created_at?->format('Y-m-d H:i'),
            'Name' => 'name', 'Email' => 'email', 'Mobile' => 'phone',
            'Subject' => fn ($m) => Message::SUBJECTS[$m->subject] ?? $m->subject,
            'Message' => 'message',
        ], $this->type() . '-' . now()->format('Y-m-d') . '.csv');
    }

    protected function backUrl(Model $record): string
    {
        return route($this->route . '.index', ['type' => $record->type]);
    }

    public function create(Request $request)
    {
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
    }

    public function update(Request $request, string $id)
    {
        abort(404);
    }
}
