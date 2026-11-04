<?php
namespace ME\Nit\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use ME\Nit\Models\DomainPrice;
use ME\Nit\Models\ListItem;
use ME\Nit\Models\Plan;
use ME\Nit\Models\PlanFeature;
use ME\Nit\Models\PlanPage;
use ME\Nit\Models\PortfolioCategory;
use ME\Nit\Models\PortfolioItem;
use ME\Nit\Models\Review;
use ME\Nit\Models\Setting;
use ME\Nit\Models\SoftwarePage;
use ME\Nit\Models\SslPlan;
use ME\Nit\Models\TeamMember;
use ME\Nit\Models\WebPackage;
use ME\Nit\Support\Catalog;
use ME\Nit\Support\Content;
use ME\Nit\Support\SiteData;

/**
 * Fills the website tables with the content mirrored from natoreit.com
 * (Support/Catalog, Support/SiteData, Support/content). Tables that already
 * have rows are left alone unless --fresh is given.
 */
class ImportSiteContent extends Command
{
    protected $signature = 'nit:site-import {--fresh : Empty the website tables first}';

    protected $description = 'Import the default natoreit.com content into the website tables';

    public function handle(): int
    {
        DB::transaction(function () {
            $this->import(Setting::class, fn () => $this->settings());
            $this->import(PlanPage::class, fn () => $this->planPages());
            $this->import(WebPackage::class, fn () => $this->webPackages());
            $this->import(SslPlan::class, fn () => $this->sslPlans());
            $this->import(DomainPrice::class, fn () => $this->domains());
            $this->import(PortfolioItem::class, fn () => $this->portfolio());
            $this->import(TeamMember::class, fn () => $this->team());
            $this->import(Review::class, fn () => $this->reviews());
            $this->importLists();
            $this->import(SoftwarePage::class, fn () => $this->software());
        });
        Content::flush();

        return self::SUCCESS;
    }

    private function import(string $model, callable $fill): void
    {
        $label = class_basename($model);
        if ($this->option('fresh')) {
            $this->wipe($model);
        } elseif ($model::query()->exists()) {
            $this->components->twoColumnDetail($label, '<fg=yellow>kept (has rows)</>');

            return;
        }
        $fill();
        $this->components->twoColumnDetail($label, '<fg=green>imported</>');
    }

    private function wipe(string $model): void
    {
        $related = [PlanPage::class => [Plan::class, PlanFeature::class], PortfolioItem::class => [PortfolioCategory::class]];
        foreach ($related[$model] ?? [] as $child) {
            $child::query()->delete();
        }
        $model::query()->delete();
    }

    private function settings(): void
    {
        foreach (config('nit.site') as $key => $value) {
            Setting::create(['key' => $key, 'value' => $value]);
        }
        Setting::create(['key' => 'coupon', 'value' => [
            'headline' => '40% Off', 'product' => 'Starter Hosting', 'code' => 'ZWR345FG',
            'order_url' => 'https://billing.natoreit.com/cart.php?a=add&pid=1', 'order_label' => 'Order Starter Hosting',
        ]]);
    }

    private function planPages(): void
    {
        $menu = ['shared-hosting' => 'Shared Web Hosting With cPanel', 'wordpress-hosting' => 'Security & Speed Managed by WP',
            'e-commerce-hosting' => 'The Best Shopping Cart Performance', 'reseller-hosting' => 'Business Class Hosting Experience',
            'master-reseller-hosting' => 'Cheap Master Reseller Hosting', 'alpha-master-reseller-hosting' => 'Cheap Alpha Reseller Hosting'];
        $sort = 0;
        foreach (Catalog::planPages() as $slug => $data) {
            $page = PlanPage::create([
                'slug' => $slug, 'title' => $data['title'], 'heading' => $data['heading'], 'icon' => $data['icon'],
                'eyebrow' => $data['eyebrow'] ?? 'Hosting', 'menu_text' => $menu[$slug] ?? null, 'lead' => $data['lead'], 'includes_title' => $data['includes_title'],
                'about' => implode("\n\n", $data['about'] ?? []), 'sort' => ++$sort,
            ]);
            $features = [];
            foreach ($data['rows'] as $i => $row) {
                $features[] = PlanFeature::create(['plan_page_id' => $page->id, 'label' => $row[0], 'sort' => $i + 1]);
            }
            foreach ($data['plans'] as $p => $plan) {
                $values = [];
                foreach ($data['rows'] as $i => $row) {
                    $value = $row[$p + 1] ?? false;
                    $values[$features[$i]->id] = $value === true ? 'yes' : ($value === false ? 'no' : (string) $value);
                }
                Plan::create([
                    'plan_page_id' => $page->id, 'name' => $plan['name'], 'price' => $plan['price'], 'regular_price' => $plan['regular'],
                    'period' => $plan['period'], 'order_url' => $plan['order'], 'is_featured' => $plan['featured'], 'values' => $values, 'sort' => $p + 1,
                ]);
            }
        }
    }

    private function webPackages(): void
    {
        foreach (SiteData::webPackages() as $i => $p) {
            WebPackage::create([
                'name' => $p['name'], 'icon' => $p['icon'], 'price' => $p['price'], 'regular_price' => $p['regular'], 'renewal_price' => $p['renewal'],
                'order_url' => $p['order'], 'features' => implode("\n", $p['features']), 'is_featured' => $p['featured'], 'sort' => $i + 1,
            ]);
        }
    }

    private function sslPlans(): void
    {
        foreach (Catalog::sslPlans() as $i => [$name, $price, $pid, $note]) {
            SslPlan::create(['name' => $name, 'price' => $price, 'note' => $note, 'order_url' => Catalog::sslOrderUrl($pid), 'sort' => $i + 1]);
        }
    }

    private function domains(): void
    {
        foreach (SiteData::featuredDomains() as $i => $d) {
            DomainPrice::create($d + ['sort' => $i + 1]);
        }
    }

    private function portfolio(): void
    {
        $data = Catalog::content('portfolio');
        $ids = [];
        $sort = 0;
        foreach ($data['categories'] as $key => $name) {
            $ids[$key] = PortfolioCategory::create(['name' => $name, 'sort' => ++$sort])->id;
        }
        foreach ($data['items'] as $i => $item) {
            PortfolioItem::create([
                'title' => $item['title'], 'url' => $item['url'], 'image' => $item['image'], 'sort' => $i + 1,
                'categories' => array_values(array_unique(array_filter(array_map(fn ($k) => $ids[$k] ?? null, $item['categories'])))),
            ]);
        }
    }

    private function team(): void
    {
        foreach (Catalog::content('team') as $i => $m) {
            TeamMember::create($m + ['sort' => $i + 1]);
        }
    }

    private function reviews(): void
    {
        foreach (Catalog::content('reviews') as $i => $r) {
            Review::create(['author' => $r['author'], 'title' => $r['title'], 'rating' => $r['rating'], 'text' => $r['text'], 'date_label' => $r['date'], 'sort' => $i + 1]);
        }
    }

    /** Lists are filled one by one, so lists added in a later version are imported too. */
    private function importLists(): void
    {
        if ($this->option('fresh')) {
            ListItem::query()->delete();
        }
        $done = $this->lists(ListItem::distinct()->pluck('list')->all());
        $this->components->twoColumnDetail('ListItem', $done ? '<fg=green>imported: ' . implode(', ', $done) . '</>' : '<fg=yellow>kept (has rows)</>');
    }

    private function lists(array $existing): array
    {
        $links = config('nit.site.links');
        $rows = [
            'why_choose'       => array_map(fn ($r) => ['icon' => $r[0], 'title' => $r[1], 'text' => $r[2]], SiteData::whyChooseUs()),
            'featured_hosting' => array_map(fn ($r, $slug) => ['icon' => $r[0], 'title' => $r[1], 'text' => $r[2] . "\n" . implode("\n", $r[3]), 'url' => '/' . $slug],
                SiteData::featuredHosting(), ['shared-hosting', 'reseller-hosting', 'master-reseller-hosting']),
            'offer_tabs'       => array_values(array_map(fn ($t) => ['icon' => $t[0], 'title' => $t[1], 'text' => $t[2] . "\n" . implode("\n", $t[5]), 'url' => '/' . $t[3]], SiteData::offerTabs())),
            'footer_resources' => [
                ['title' => 'Outlook configuration step by step', 'url' => $links['knowledgebase']],
                ['title' => '3 Reasons Websites Are Vital for Small Businesses', 'url' => $links['news']],
            ],
            'site_types'       => array_map(fn ($r) => ['icon' => $r[0], 'title' => $r[1]], SiteData::siteTypes()),
            'plan_includes'    => array_map(fn ($r) => ['icon' => $r[0], 'title' => $r[1]], Catalog::includes()),
            'about_why'        => array_map(fn ($r) => ['icon' => $r[0], 'title' => $r[1]], Catalog::aboutWhy()),
            'software'         => array_map(fn ($r) => ['icon' => $r[0], 'title' => $r[1]], Catalog::softwareSolutions()),
            'domain_links'     => array_map(fn ($r) => ['title' => $r[0], 'url' => $r[1], 'icon' => $r[2]], Catalog::domainLinks()),
            'payment_methods'  => array_map(fn ($r) => ['title' => $r], SiteData::paymentMethods()),
        ];
        $done = [];
        foreach ($rows as $list => $items) {
            if (in_array($list, $existing, true)) {
                continue;
            }
            foreach ($items as $i => $item) {
                ListItem::create($item + ['list' => $list, 'sort' => $i + 1]);
            }
            $done[] = $list;
        }

        return $done;
    }

    private function software(): void
    {
        $data = Catalog::content('software');
        SoftwarePage::create(['key' => 'pos', 'title' => 'Online POS Software', 'intro' => $data['pos']['intro'], 'sections' => $data['pos']['sections']]);
        SoftwarePage::create(['key' => 'crm', 'title' => 'Online CRM Software', 'intro' => $data['crm']['intro'], 'sections' => $data['crm']['sections']]);
        SoftwarePage::create(['key' => 'multi_vendor', 'title' => 'Multi Vendor Ecommerce', 'intro' => implode("\n\n", $data['multi_vendor']['intro']), 'sections' => $data['multi_vendor']['groups']]);
    }
}
