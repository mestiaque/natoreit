<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use ME\Nit\Models\Setting;
use ME\Nit\Support\Content;

/**
 * Browser title, search description and top texts of the fixed pages
 * (saved in the "pages" setting; empty fields keep the default text).
 */
class PageTextController extends Controller
{
    public static $permissions = [
        'edit'   => ['site_page_text_edit', 'Edit page texts'],
        'update' => ['site_page_text_edit', 'Edit page texts'],
    ];

    public function edit(Request $request, ?string $page = null)
    {
        $key = $this->pageKey($page ?? $request->query('page'));

        return view('nit::admin.page-texts', [
            'pages'    => Content::PAGES,
            'key'      => $key,
            'defaults' => Content::PAGES[$key],
            'saved'    => (array) (Content::settings()['pages'][$key] ?? []),
        ]);
    }

    public function update(Request $request)
    {
        $key = $this->pageKey($request->input('page'));
        $data = $request->validate([
            'page'        => ['required', Rule::in(array_keys(Content::PAGES))],
            'meta_title'  => ['nullable', 'string', 'max:70'],
            'description' => ['nullable', 'string', 'max:300'],
            'eyebrow'     => ['nullable', 'string', 'max:80'],
            'title'       => ['nullable', 'string', 'max:150'],
            'highlight'   => ['nullable', 'string', 'max:60'],
            'lead'        => ['nullable', 'string', 'max:600'],
        ]);
        unset($data['page']);

        $setting = Setting::firstOrNew(['key' => 'pages']);
        $pages = (array) $setting->value;
        $pages[$key] = array_filter($data, fn ($v) => $v !== null && $v !== '');
        $setting->value = $pages;
        $setting->save();

        return redirect()->route('nit.admin.page-texts.page', $key)
            ->with('success', __(':page texts saved.', ['page' => Content::PAGES[$key]['name']]));
    }

    private function pageKey(?string $key): string
    {
        return array_key_exists((string) $key, Content::PAGES) ? $key : array_key_first(Content::PAGES);
    }
}
