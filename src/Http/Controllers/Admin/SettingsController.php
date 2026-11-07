<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ME\Nit\Models\Setting;
use ME\Nit\Support\Content;

/**
 * Contact details, links, social profiles and the coupon shown on the website.
 */
class SettingsController extends Controller
{
    public static $permissions = [
        'edit'   => ['site_settings_edit', 'Edit website settings'],
        'update' => ['site_settings_edit', 'Edit website settings'],
    ];

    /** Form groups: title => [setting key (dot = inside an array) => [label, rules, type, hint]]. */
    public static function groups(): array
    {
        $url = ['nullable', 'url', 'max:500'];

        return [
            'Company' => [
                'name'     => ['Company name', ['required', 'string', 'max:100']],
                'tagline'  => ['Tagline', ['nullable', 'string', 'max:200']],
                'since'    => ['Founded (year)', ['nullable', 'integer', 'min:1990', 'max:2100'], 'number'],
                'hours'    => ['Office hours', ['nullable', 'string', 'max:100']],
                'address'  => ['Address', ['nullable', 'string', 'max:300'], 'textarea'],
            ],
            'Contact' => [
                'phone'    => ['Main phone', ['required', 'string', 'max:30'], 'text', 'Shown in the top bar and calls to action.'],
                'phones'   => ['All sales phones', ['nullable', 'string', 'max:300'], 'lines', 'One number per line.'],
                'support'  => ['Support phone', ['nullable', 'string', 'max:30']],
                'whatsapp' => ['WhatsApp', ['nullable', 'string', 'max:30']],
                'email'    => ['E-mail', ['required', 'email', 'max:150'], 'email'],
                'website'  => ['Website (as shown)', ['nullable', 'string', 'max:100']],
                'map_url'  => ['Google Maps link', $url, 'url'],
                'map_embed'=> ['Google Maps embed link', $url, 'url', 'The src of the map iframe (Share › Embed a map).'],
            ],
            'Links' => [
                'links.login'         => ['Client login (top bar)', $url, 'url'],
                'links.register'      => ['Client sign up (top bar)', $url, 'url'],
                'links.blog'          => ['Blog', $url, 'url'],
                'links.client_area'   => ['Client area', $url, 'url'],
                'links.knowledgebase' => ['Knowledge base', $url, 'url'],
                'links.news'          => ['News / announcements', $url, 'url'],
                'links.terms'         => ['Terms of service', $url, 'url'],
                'links.privacy'       => ['Privacy policy', $url, 'url'],
                'links.refund'        => ['Refund policy', $url, 'url'],
                'links.demo_cpanel'   => ['Demo cPanel', $url, 'url'],
            ],
            'Social profiles' => [
                'social.facebook' => ['Facebook', $url, 'url'],
                'social.twitter'  => ['X (Twitter)', $url, 'url'],
                'social.linkedin' => ['LinkedIn', $url, 'url'],
                'social.youtube'  => ['YouTube', $url, 'url'],
            ],
            'Analytics & chat' => [
                'analytics.ga'    => ['Google Analytics ID', ['nullable', 'string', 'max:30', 'regex:/^G-[A-Z0-9]+$/'], 'text', 'e.g. G-XXXXXXXXXX. Leave empty to turn off.'],
                'analytics.pixel' => ['Meta (Facebook) Pixel ID', ['nullable', 'string', 'max:30', 'regex:/^\\d+$/'], 'text', 'Numbers only. Leave empty to turn off.'],
                'analytics.tawk'  => ['Tawk.to chat ID', ['nullable', 'string', 'max:80', 'regex:/^[a-z0-9]+\/[a-z0-9]+$/i'], 'text', 'The part after embed.tawk.to/ in the Tawk.to widget code, e.g. 64ab12…/1h5k…. Leave empty to turn off.'],
            ],
            'Coupon' => [
                'coupon.code'        => ['Code', ['nullable', 'string', 'max:40']],
                'coupon.headline'    => ['Headline', ['nullable', 'string', 'max:40'], 'text', 'e.g. 40% Off'],
                'coupon.product'     => ['For', ['nullable', 'string', 'max:100'], 'text', 'e.g. Starter Hosting'],
                'coupon.order_label' => ['Button text', ['nullable', 'string', 'max:60']],
                'coupon.order_url'   => ['Button link', $url, 'url'],
            ],
        ];
    }

    public function edit()
    {
        return view('nit::admin.settings', ['groups' => self::groups(), 'site' => Content::settings()]);
    }

    public function update(Request $request)
    {
        $fields = collect(self::groups())->collapse();
        // "links.blog" arrives as links[blog]
        $data = $request->validate(
            $fields->mapWithKeys(fn ($field, $key) => [$key => $field[1]])->all(),
            [],
            $fields->mapWithKeys(fn ($field, $key) => [$key => mb_strtolower(__($field[0]))])->all(),
        );

        $values = [];
        foreach ($fields as $key => $field) {
            $value = data_get($data, $key);
            if (($field[2] ?? null) === 'lines') {
                $value = array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $value))));
            }
            data_set($values, $key, $value ?? (str_starts_with($key, 'social.') ? '#' : null));
        }

        foreach ($values as $key => $value) {
            $setting = Setting::firstOrNew(['key' => $key]);
            // Keep keys of an array setting that the form does not show
            $setting->value = is_array($value) && is_array($setting->value) && ! array_is_list($value) ? array_replace($setting->value, $value) : $value;
            $setting->save();
        }

        return redirect()->route('nit.admin.settings')->with('success', __('Website settings saved.'));
    }
}
