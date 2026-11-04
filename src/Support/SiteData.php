<?php
namespace ME\Nit\Support;

/**
 * Home page content mirrored from natoreit.com.
 */
class SiteData
{
    /** Featured extensions under the hero domain search; USD, matching billing.natoreit.com. */
    public static function featuredDomains(): array
    {
        return [
            ['tld' => '.com',    'price' => '$11.66', 'period' => '/yr'],
            ['tld' => '.net',    'price' => '$18.00', 'period' => '/yr'],
            ['tld' => '.info',   'price' => '$17.49', 'period' => '/yr'],
            ['tld' => '.edu.bd', 'price' => '$28.99', 'period' => '/2 yrs'],
        ];
    }
    public static function webPackages(): array
    {
        return [
            [
                'name' => 'Basic', 'order' => 'https://billing.natoreit.com/cart.php?a=add&pid=5&carttpl=boxes', 'price' => 62, 'regular' => 100, 'renewal' => 24, 'featured' => false, 'icon' => 'bi-window',
                'features' => ['1 .com domain', '1 GB hosting', 'Unlimited bandwidth', '1–5 pages', 'Dynamic website', 'Responsive design', 'On-page SEO', '10 email accounts', 'Lifetime support'],
            ],
            [
                'name' => 'Business', 'order' => 'https://billing.natoreit.com/cart.php', 'price' => 93, 'regular' => 150, 'renewal' => 30, 'featured' => true, 'icon' => 'bi-briefcase',
                'features' => ['1 .com domain', '2 GB hosting', 'Unlimited bandwidth', '5–10 pages', 'Dynamic website', 'Responsive design', 'On-page SEO', '20 email accounts', 'Lifetime support'],
            ],
            [
                'name' => 'E-commerce', 'order' => 'https://billing.natoreit.com/cart.php', 'price' => 150, 'regular' => 300, 'renewal' => 37, 'featured' => false, 'icon' => 'bi-cart3',
                'features' => ['1 .com domain', '5 GB hosting', 'Unlimited bandwidth', 'Unlimited pages', 'Dynamic website', 'Responsive design', 'On-page SEO', 'Unlimited email accounts', 'Lifetime support'],
            ],
            [
                'name' => 'Classified / Newspaper', 'order' => 'https://billing.natoreit.com/cart.php', 'price' => 190, 'regular' => 450, 'renewal' => 75, 'featured' => false, 'icon' => 'bi-newspaper',
                'features' => ['1 .com domain', 'Unlimited hosting', 'Unlimited bandwidth', 'Unlimited pages', 'Dynamic website', 'Responsive design', 'On-page SEO', 'Unlimited email accounts', 'Lifetime support'],
            ],
        ];
    }
    public static function whyChooseUs(): array
    {
        return [
            ['bi-cloud-arrow-down', 'Backups available', 'Email, database and file backups, with full backup and one-click restore.'],
            ['bi-envelope-paper', 'Unlimited email addresses', 'Create as many mailboxes and email forwarders as your business needs.'],
            ['bi-cpu', 'Reliable hardware', 'Enterprise servers with server-level fixes for major vulnerabilities.'],
            ['bi-arrow-repeat', 'Autoupdates', 'WordPress core and plugin updates applied automatically.'],
            ['bi-cloud-check', 'CloudFlare integration', 'Global caching and filtering of malicious traffic built in.'],
            ['bi-headset', 'Free 24×7/365 support', 'Real people on call every day of the year, including holidays.'],
        ];
    }
    public static function siteTypes(): array
    {
        return [
            ['bi-layout-text-window', 'Landing Page'], ['bi-window-stack', 'Dynamic Website'], ['bi-cart3', 'E-commerce'],
            ['bi-newspaper', 'Newspaper'], ['bi-megaphone', 'Classified'], ['bi-graph-up-arrow', 'SEO'], ['bi-facebook', 'Facebook Marketing'],
        ];
    }
    public static function offerTabs(): array
    {
        return [
            'hosting'   => ['bi-hdd-stack', 'Hosting', 'Fast cPanel hosting on LiteSpeed servers with free SSL, daily backups and unlimited email. Shared, WordPress, reseller and master reseller plans to match every stage of growth.', 'shared-hosting', 'dashboard', ['Free SSL on every domain', 'Daily automatic backups', 'One-click WordPress install']],
            'websites'  => ['bi-window', 'Websites', 'Responsive, dynamic websites designed for your brand — from one-page landing sites to full company portals — delivered with hosting, domain and on-page SEO.', 'web-design', 'website', ['Mobile-first responsive design', 'Easy content management', 'On-page SEO included']],
            'domains'   => ['bi-globe2', 'Domains', 'Register .com, .net, .info, .com.bd and .edu.bd domains with free DNS management, domain forwarding and help moving existing domains to us.', 'domain', 'table', ['Free DNS management', 'Easy domain transfer', 'Renewal reminders']],
            'ecommerce' => ['bi-bag-check', 'eCommerce', 'Online stores and multi-vendor marketplaces with bKash, Nagad and card payments, courier integration, inventory and order management.', 'multi-vendor-ecommerce', 'pos', ['bKash, Nagad & card payments', 'Inventory and order tracking', 'Multi-vendor ready']],
            'seo'       => ['bi-graph-up-arrow', 'SEO', 'Search engine optimisation and Facebook marketing that bring the right visitors to your site, with clear monthly reports on rankings and traffic.', 'seo', 'analytics', ['Technical SEO audit', 'Keyword & content strategy', 'Monthly ranking report']],
        ];
    }
    public static function featuredHosting(): array
    {
        return [
            ['bi-server', 'Shared Hosting (cPanel)', 'Everything a business website needs: cPanel, free SSL, email and daily backups on fast SSD servers.', ['cPanel control panel', 'Free SSL certificate', 'Daily backups']],
            ['bi-diagram-3', 'Reseller Hosting', 'Start your own hosting business. Create and manage client accounts under your own brand with WHM.', ['WHM + cPanel accounts', 'White-label branding', 'Free migration']],
            ['bi-diagram-3-fill', 'Master Reseller Hosting', 'Sell reseller accounts of your own. Maximum resources and control for growing hosting companies.', ['Create reseller accounts', 'Private nameservers', 'Priority support']],
        ];
    }
    public static function hostingPackageLinks(): array
    {
        return [
            ['shared-hosting', 'Shared Hosting'], ['reseller-hosting', 'Reseller Hosting'], ['e-commerce-hosting', 'E-commerce Hosting'],
            ['wordpress-hosting', 'WordPress Hosting'], ['master-reseller-hosting', 'Master Reseller Hosting'], ['alpha-master-reseller-hosting', 'Alpha Master Reseller Hosting'],
        ];
    }
    public static function paymentMethods(): array
    {
        return ['bKash', 'Nagad', 'Rocket', 'VISA', 'Mastercard', 'Bank'];
    }
    public static function usd(int|float $amount): string
    {
        return '$' . (floor($amount) == $amount ? number_format($amount) : number_format($amount, 2));
    }
    public static function taka(?int $amount): string
    {
        return $amount === null ? 'Custom' : '৳' . number_format($amount);
    }
}
