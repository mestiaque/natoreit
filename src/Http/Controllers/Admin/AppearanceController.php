<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ME\Nit\Models\Setting;
use ME\Nit\Support\Content;
use ME\Nit\Support\SiteTexts;
use ME\Nit\Support\Theme;
use Nit\Master\Support\Nit;

/**
 * One page for the look of the website: logo, colors, fonts, buttons, motion and fixed texts.
 */
class AppearanceController extends Controller
{
    public static $permissions = [
        'edit'   => ['site_appearance_edit', 'Edit website appearance'],
        'update' => ['site_appearance_edit', 'Edit website appearance'],
        'reset'  => ['site_appearance_edit', 'Edit website appearance'],
    ];

    public function edit()
    {
        return view('nit::admin.appearance', ['values' => Theme::values()]);
    }

    public function update(Request $request)
    {
        $themeRules = [];
        foreach (Theme::rules() as $key => $rules) {
            $themeRules['theme.' . $key] = $rules;
        }
        $data = $request->validate($themeRules + [
            'logo'         => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'favicon'      => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:512'],
            'texts'        => ['nullable', 'array'],
            'texts.*'      => ['nullable', 'string', 'max:3000'],
            'icons'        => ['nullable', 'array'],
            'icons.*'      => ['nullable', 'regex:/^bi-[a-z0-9-]+$/'],
        ], [], collect(Theme::fields())->mapWithKeys(fn ($field, $key) => ['theme.' . $key => mb_strtolower(__($field[0]))])->all());

        $this->save('theme', $data['theme']);
        $this->save('texts', $this->changedTexts($data['texts'] ?? []));
        $this->save('icons', array_filter($data['icons'] ?? [], fn ($icon, $default) => filled($icon) && $icon !== $default, ARRAY_FILTER_USE_BOTH));
        $this->saveImages($request);
        Theme::forget();

        return redirect()->route('nit.admin.appearance')->with('success', __('Appearance saved.'));
    }

    /** Back to the original design; the logo and texts are kept. */
    public function reset()
    {
        $this->save('theme', []);
        Theme::forget();

        return redirect()->route('nit.admin.appearance')->with('success', __('Design reset to the original.'));
    }

    /** Only texts that differ from the default are stored, under the md5 of the default. */
    private function changedTexts(array $texts): array
    {
        $saved = [];
        foreach (array_merge(...array_values(SiteTexts::GROUPS)) as $default) {
            $hash = md5($default);
            if (filled($texts[$hash] ?? null) && $texts[$hash] !== $default) {
                $saved[$hash] = $texts[$hash];
            }
        }

        return $saved;
    }

    private function saveImages(Request $request): void
    {
        $images = (array) (Content::settings()['logo'] ?? []);
        foreach (['logo', 'favicon'] as $name) {
            $old = $images[$name] ?? null;
            if ($request->hasFile($name) || $request->boolean($name . '_remove')) {
                if ($old) {
                    Nit::delete($old);
                }
                $images[$name] = $request->hasFile($name) ? $request->file($name)->store('site', Nit::disk()) : null;
            }
        }
        $this->save('logo', $images);
    }

    private function save(string $key, array $value): void
    {
        $setting = Setting::firstOrNew(['key' => $key]);
        $setting->value = $value;
        $setting->save();
    }
}
