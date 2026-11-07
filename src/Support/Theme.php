<?php
namespace ME\Nit\Support;

use Illuminate\Validation\Rule;

/**
 * Look of the public website (admin › Appearance).
 *
 * Saved values are checked against FIELDS again before they reach the page,
 * and only values that differ from the default produce CSS.
 */
class Theme
{
    public const FONTS = [
        'Plus Jakarta Sans', 'Inter', 'Poppins', 'Roboto', 'Open Sans', 'Nunito', 'Montserrat', 'Lato',
        'Hind Siliguri', 'Noto Sans Bengali', 'Baloo Da 2',
    ];

    public const RADII = ['0' => 'Square', '0.5rem' => 'Slightly rounded', '1rem' => 'Rounded', '50rem' => 'Pill'];

    /** Group => key => [label, type, default, options|[min, max, step], hint]. */
    public const FIELDS = [
        'Colors' => [
            'primary' => ['Main color', 'color', '#1474b8', null, 'Buttons, links and icons. Darker and lighter shades are made from it.'],
            'sky'     => ['Highlight color', 'color', '#2a9be0', null, 'Glow and gradients.'],
            'accent'  => ['Accent color', 'color', '#0f9f8f', null, 'Discount badges and check marks.'],
            'navy'    => ['Dark color', 'color', '#0f2147', null, 'Headings, top bar and footer.'],
            'ink'     => ['Text color', 'color', '#17213a', null],
            'muted'   => ['Soft text color', 'color', '#56627e', null],
            'border'  => ['Border color', 'color', '#e2e7f1', null],
            'bg_soft' => ['Soft background', 'color', '#f5f7fc', null, 'Grey sections.'],
        ],
        'Fonts & text' => [
            'font_body'      => ['Text font', 'font', 'Plus Jakarta Sans', null, 'Hind Siliguri, Noto Sans Bengali and Baloo Da 2 support Bangla.'],
            'font_heading'   => ['Heading font', 'font', 'Plus Jakarta Sans', null],
            'size_desktop'   => ['Text size on desktop (px)', 'number', 12.5, [11, 18, 0.5], 'All text and spacing grow with this size.'],
            'size_mobile'    => ['Text size on mobile (px)', 'number', 15, [12, 19, 0.5]],
            'heading_weight' => ['Heading weight', 'select', '700', ['500' => 'Medium', '600' => 'Semi bold', '700' => 'Bold', '800' => 'Extra bold']],
        ],
        'Buttons' => [
            'btn_radius'    => ['Button shape', 'select', '50rem', self::RADII],
            'btn_hover'     => ['Hover effect', 'select', 'liquid', ['liquid' => 'Liquid fill', 'simple' => 'Simple color change']],
            'btn_uppercase' => ['Button text', 'select', 'normal', ['normal' => 'Normal', 'uppercase' => 'UPPERCASE']],
            'btn_weight'    => ['Button text weight', 'select', '600', ['500' => 'Medium', '600' => 'Semi bold', '700' => 'Bold']],
        ],
        'Cards' => [
            'card_radius' => ['Card corners', 'select', '1rem', ['0' => 'Square', '0.5rem' => 'Slightly rounded', '1rem' => 'Rounded', '1.5rem' => 'Very rounded']],
            'shadows'     => ['Shadows', 'select', 'on', ['on' => 'On', 'off' => 'Off']],
        ],
        'Animation & transition' => [
            'animations'  => ['Scroll and page-load animation', 'select', 'on', ['on' => 'On', 'off' => 'Off']],
            'transitions' => ['Hover transitions', 'select', 'on', ['on' => 'On', 'off' => 'Off']],
            'speed'       => ['Speed', 'select', '1', ['0.6' => 'Fast', '1' => 'Normal', '1.5' => 'Slow']],
            'distance'    => ['Scroll animation distance (px)', 'number', 16, [0, 60, 1]],
            'hover_lift'  => ['Cards rise on hover', 'select', 'on', ['on' => 'On', 'off' => 'Off']],
        ],
        'Page elements' => [
            'whatsapp_button' => ['WhatsApp button', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'back_to_top'     => ['Back to top button', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'mobile_bar'      => ['Mobile action bar', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'quote_button'    => ['Get a quote button in the menu', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'dark_mode'       => ['Dark mode switch', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'scroll_progress' => ['Reading progress bar', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'count_up'        => ['Numbers count up', 'select', 'on', ['on' => 'On', 'off' => 'Off']],
        ],
        'Currency' => [
            'bdt_rate'         => ['Taka for 1 USD', 'number', 0, [0, 1000, 0.01], 'Today\'s rate. Leave 0 to show only USD and hide the switch.'],
            'currency_default' => ['Show prices first in', 'select', 'usd', ['usd' => 'USD ($)', 'bdt' => 'Taka (৳)']],
        ],
        'Home page sections' => [
            'hero_3d'       => ['3D globe in the hero', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'home_stats'    => ['Numbers bar', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'home_tech'     => ['Technologies strip', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'home_process'  => ['How we work', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'home_software' => ['Software we build', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'home_packages' => ['Web design packages', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'home_why'      => ['Why choose us', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'home_builder'  => ['Website types', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'home_offers'   => ['What more we offer', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'home_hosting'  => ['Hosting cards', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'home_reviews'  => ['Customer reviews', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
            'home_faq'      => ['FAQ', 'select', 'on', ['on' => 'Show', 'off' => 'Hide']],
        ],
    ];

    private static ?array $values = null;

    public static function fields(): array
    {
        return array_merge(...array_values(self::FIELDS));
    }

    public static function forget(): void
    {
        self::$values = null;
    }

    public static function rules(): array
    {
        $rules = [];
        foreach (self::fields() as $key => $field) {
            $rules[$key] = match ($field[1]) {
                'color'  => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
                'font'   => ['required', Rule::in(self::FONTS)],
                'number' => ['required', 'numeric', 'min:' . $field[3][0], 'max:' . $field[3][1]],
                default  => ['required', Rule::in(array_map('strval', array_keys($field[3])))],
            };
        }

        return $rules;
    }

    /** Saved values, with the default for anything missing or invalid. */
    public static function values(): array
    {
        if (self::$values !== null) {
            return self::$values;
        }
        $saved = (array) (Content::settings()['theme'] ?? []);
        $values = [];
        foreach (self::fields() as $key => $field) {
            $value = $saved[$key] ?? null;
            $values[$key] = self::valid($field, $value) ? $value : $field[2];
        }

        return self::$values = $values;
    }

    private static function valid(array $field, mixed $value): bool
    {
        return match ($field[1]) {
            'color'  => is_string($value) && preg_match('/^#[0-9a-fA-F]{6}$/', $value),
            'font'   => in_array($value, self::FONTS, true),
            'number' => is_numeric($value) && $value >= $field[3][0] && $value <= $field[3][1],
            default  => is_scalar($value) && array_key_exists((string) $value, $field[3]),
        };
    }

    public static function is(string $key, string $value): bool
    {
        return (string) self::values()[$key] === $value;
    }

    /** Fonts the page must load, as a Google Fonts URL; the default stylesheet stays when nothing changed. */
    public static function fontsUrl(): string
    {
        $families = array_unique([self::values()['font_body'], self::values()['font_heading']]);
        $query = implode('&', array_map(fn ($font) => 'family=' . str_replace(' ', '+', $font) . ':wght@400;500;600;700;800', $families));

        return 'https://fonts.googleapis.com/css2?' . $query . '&display=swap';
    }

    public static function css(): string
    {
        $v = self::values();
        $changed = fn (string $key) => (string) $v[$key] !== (string) self::fields()[$key][2];
        $vars = [];
        $css = '';

        if ($changed('primary')) {
            $vars['--nit-primary'] = $v['primary'];
            $vars['--nit-primary-rgb'] = implode(', ', self::rgb($v['primary']));
            $vars['--nit-primary-dark'] = self::mix($v['primary'], '#000000', 0.15);
            $vars['--nit-primary-mid'] = self::mix($v['primary'], '#ffffff', 0.12);
            $vars['--nit-primary-soft'] = self::mix($v['primary'], '#ffffff', 0.9);
        }
        if ($changed('sky')) {
            $vars['--nit-sky'] = $v['sky'];
            $vars['--nit-sky-rgb'] = implode(', ', self::rgb($v['sky']));
        }
        foreach (['accent' => '--nit-accent', 'navy' => '--nit-chrome', 'ink' => '--nit-ink', 'muted' => '--nit-muted', 'border' => '--nit-border', 'bg_soft' => '--nit-bg-soft'] as $key => $var) {
            if ($changed($key)) {
                $vars[$var] = $v[$key];
            }
        }
        if ($changed('navy')) {
            $css .= 'html:not([data-bs-theme=dark]){--nit-navy:' . $v['navy'] . '}';
        }
        if ($changed('font_body')) {
            $vars['--bs-body-font-family'] = '"' . $v['font_body'] . '", system-ui, sans-serif';
        }
        if ($changed('card_radius')) {
            $vars['--radius'] = $v['card_radius'];
            $vars['--radius-lg'] = $v['card_radius'];
        }
        if ($changed('speed')) {
            foreach (['--dur-fast' => 160, '--dur-base' => 240, '--dur-smooth' => 320, '--dur-enter' => 520] as $var => $ms) {
                $vars[$var] = round($ms * $v['speed']) . 'ms';
            }
        }
        if ($changed('distance')) {
            $vars['--reveal-distance'] = (float) $v['distance'] . 'px';
        }
        if ($v['hover_lift'] === 'off') {
            $vars += ['--hover-lift-sm' => '0', '--hover-lift-md' => '0', '--media-zoom' => '1'];
        }
        if ($v['shadows'] === 'off') {
            foreach (['--shadow-sm', '--shadow-card', '--shadow-card-hover', '--shadow-dropdown', '--shadow-header'] as $var) {
                $vars[$var] = 'none';
            }
        }
        if ($vars) {
            $css .= ':root{' . implode('', array_map(fn ($name, $value) => "$name:$value;", array_keys($vars), $vars)) . '}';
        }

        if ($changed('size_mobile')) {
            $css .= 'html{font-size:' . (float) $v['size_mobile'] . 'px}';
        }
        if ($changed('size_desktop')) {
            $css .= '@media (min-width:768px){html{font-size:' . (float) $v['size_desktop'] . 'px}}';
        } elseif ($changed('size_mobile')) {
            $css .= '@media (min-width:768px){html{font-size:12.5px}}';
        }
        if ($changed('font_heading')) {
            $css .= 'h1,h2,h3,h4,h5,h6,.h1,.h2,.h3,.h4,.h5,.h6{font-family:"' . $v['font_heading'] . '",system-ui,sans-serif}';
        }
        if ($changed('heading_weight')) {
            $css .= 'h1,h2,h3,h4,h5,h6,.h1,.h2,.h3,.h4,.h5,.h6,.hero h1,.page-hero h1{font-weight:' . $v['heading_weight'] . '}';
        }

        $button = [];
        if ($changed('btn_radius')) {
            $button[] = '--bs-btn-border-radius:' . $v['btn_radius'];
        }
        if ($changed('btn_weight')) {
            $button[] = '--bs-btn-font-weight:' . $v['btn_weight'];
        }
        if ($v['btn_uppercase'] === 'uppercase') {
            $button[] = 'text-transform:uppercase';
        }
        if ($changed('speed')) {
            $button[] = '--liquid-speed:' . round(650 * $v['speed']) . 'ms';
        }
        if ($button) {
            $css .= '.btn{' . implode(';', $button) . '}';
        }
        if ($v['btn_hover'] === 'simple') {
            $css .= '.btn::before,.btn::after{display:none!important}'
                . '.btn-primary:hover,.btn-outline-primary:hover{background:var(--nit-primary)!important;color:#fff!important}';
        }
        if ($v['animations'] === 'off') {
            $css .= '.enter,.reveal{animation:none!important;opacity:1!important;transform:none!important}';
        }
        if ($v['transitions'] === 'off') {
            $css .= '*,*::before,*::after{transition:none!important}';
        }

        return $css;
    }

    private static function rgb(string $hex): array
    {
        return array_map('hexdec', str_split(ltrim($hex, '#'), 2));
    }

    private static function mix(string $from, string $to, float $amount): string
    {
        $to = self::rgb($to);

        return '#' . implode('', array_map(fn ($c, $i) => str_pad(dechex((int) round($c + ($to[$i] - $c) * $amount)), 2, '0', STR_PAD_LEFT), self::rgb($from), [0, 1, 2]));
    }
}
