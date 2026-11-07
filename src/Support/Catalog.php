<?php
namespace ME\Nit\Support;

/**
 * Plans and pages mirrored from natoreit.com. Prices in USD; "order" links go to the WHMCS cart.
 * Comparison rows: [label, value per plan...] where true = included.
 */
class Catalog
{
    private const CART = 'https://billing.natoreit.com/cart.php?a=add&pid=';

    /** Shared-hosting style rows reused by Shared / WordPress hosting. */
    private static function sharedRows(): array
    {
        return [
            ['Number of Websites', '1 Website', '2 Website', '5 Website'],
            ['Domain Registration', '$11.50', '$11.50', '$11.50'],
            ['Space', '1 GB SSD', '5 GB SSD', 'Unlimited SSD'],
            ['Bandwidth', '10GB', '50GB', 'Unlimited'],
            ['Sub-domain', '10', '50', 'Unlimited'],
            ['Email Accounts', '10', '50', 'Unlimited'],
            ['MySQL Databases', '10', '50', 'Unlimited'],
            ['cPanel Control Panel', true, true, true],
            ['LiteSpeed Server', true, true, true],
            ['99.9% Uptime Guarantee', true, true, true],
            ['Free Setup', true, true, true],
            ['Free SSL', true, true, true],
            ['Node.js', true, true, true],
            ['Python', true, true, true],
            ['Ruby App', true, true, true],
            ['Softaculous Auto Installer', true, true, true],
            ['Free Migration', true, true, true],
            ['Auto Backup', 'last 3 days', 'last 3 days', 'last 3 days'],
            ['Advanced Security Features', 'Free', 'Free', 'Free'],
            ['Operating System', 'CloudLinux', 'CloudLinux', 'CloudLinux'],
            ['24/7/365 Support', true, true, true],
        ];
    }

    /** Rows shared by Reseller / Master / Alpha Master reseller hosting (after the plan-specific ones). */
    private static function resellerRows(): array
    {
        $all = fn ($v) => [$v, $v, $v];
        $rows = [
            ['Bandwidth', ...$all('Unlimited')],
            ['Free SSL', ...$all(true)],
            ['MySQL Databases', ...$all('Unlimited')],
            ['Processor', ...$all('INTEL® CORE™ i9')],
            ['CPU', ...$all('32 CORE')],
            ['99.9% Uptime Guarantee', ...$all(true)],
            ['Addon / Parked / Sub Domains', ...$all('Unlimited')],
        ];
        foreach (['Spam Assassin', 'Mailing Lists', 'Auto Responder', 'E-mail Alias', 'Webmail', 'POP3 Accounts', 'Attracta SEO Tools Ready',
                  'Cloudflare Ready', 'Mod_Security Protection', 'SSL Certificate', 'Ruby On Rails', 'FFMPEG', 'Softaculous', 'DNS Zone Editor',
                  'Free Setup', 'SMTP and IMAP Support', 'Private Nameserver'] as $label) {
            $rows[] = [$label, ...$all(true)];
        }
        $rows[] = ['Domain Reseller Account', ...$all('INCLUDED')];
        $rows[] = ['Firewall', ...$all(true)];
        $rows[] = ['Imunify360', ...$all(true)];
        $rows[] = ['Network Uptime', ...$all('99.9%')];
        $rows[] = ['24/7/365 Support', ...$all(true)];

        return $rows;
    }

    public static function includes(): array
    {
        return [
            ['bi-shield-check', 'Firewall & DDoS Protection'], ['bi-database', 'Unlimited Database'], ['bi-box-seam', '1-click install apps'],
            ['bi-speedometer2', '99.9% Uptime Guarantee'], ['bi-sliders', 'Easy-to-use control panel'], ['bi-graph-up-arrow', 'Attracta SEO Tools'],
            ['bi-envelope', 'Email Accounts'], ['bi-arrow-left-right', 'Transfer Website & Domain'], ['bi-infinity', 'Lifetime Support'], ['bi-headset', '24/7/365 Support'],
        ];
    }

    private static function plans(array $names, array $prices, array $regular, array $pids, string $period): array
    {
        return array_map(fn ($i) => [
            'name' => $names[$i], 'price' => $prices[$i], 'regular' => $regular[$i], 'period' => $period,
            'order' => $pids[$i] ? self::CART . $pids[$i] : 'https://billing.natoreit.com/cart.php',
            'featured' => $i === 1,
        ], array_keys($names));
    }

    /** Pages built from the plan template, keyed by URL slug. */
    public static function planPages(): array
    {
        $three = ['Starter', 'Business', 'Premium'];

        return [
            'shared-hosting' => [
                'title' => 'Shared Hosting', 'heading' => 'Web Hosting Plan Perfect For You!', 'icon' => 'bi-hdd-stack',
                'lead' => 'Shared web hosting with cPanel, LiteSpeed servers, free SSL and daily backups.',
                'plans' => self::plans($three, ['$12.00', '$25.00', '$60.00'], ['$24.00', '$50.00', '$120.00'], [1, 2, 3], '/Y'),
                'rows' => self::sharedRows(), 'includes_title' => 'All Our plans include',
            ],
            'wordpress-hosting' => [
                'title' => 'WordPress Hosting', 'heading' => 'Web Hosting Plan Perfect For You!', 'icon' => 'bi-wordpress',
                'lead' => 'Security & speed managed for WordPress — one-click installs, auto updates and LiteSpeed caching.',
                'plans' => self::plans($three, ['$12.00', '$25.00', '$60.00'], ['$24.00', '$50.00', '$120.00'], [1, null, 4], '/Y'),
                'rows' => self::sharedRows(), 'includes_title' => 'All Our plans include',
            ],
            'e-commerce-hosting' => [
                'title' => 'E-Commerce Hosting', 'heading' => 'Web Hosting Plan Perfect For You!', 'icon' => 'bi-cart3',
                'lead' => 'The best shopping cart performance, with unlimited email and search engine optimisation built in.',
                'plans' => self::plans($three, ['$36.00', '$59.00', '$118.00'], ['$49.00', '$75.00', '$150.00'], [11, 3, 4], '/Y'),
                'rows' => [
                    ['Number of Websites', '1 Website', '2 Website', '5 Website'],
                    ['Domain Registration', '$11.50', '$11.50', '$11.50'],
                    ['Web Space', '1GB SSD', '5 GB SSD', 'Unlimited SSD'],
                    ['Email Accounts', 'Unlimited', 'Unlimited', 'Unlimited'],
                    ['MySQL Databases', true, true, true],
                    ['cPanel Control Panel', true, true, true],
                    ['LiteSpeed Server', true, true, true],
                    ['Search Engine Optimization', true, true, true],
                    ...array_slice(self::sharedRows(), 9),
                ],
                'includes_title' => 'All Our plans include',
            ],
            'reseller-hosting' => [
                'title' => 'Reseller Hosting', 'heading' => 'Reseller Hosting Plan Perfect For You!', 'icon' => 'bi-diagram-3',
                'lead' => 'Business class hosting experience — start your own hosting business under your brand.',
                'plans' => self::plans($three, ['$7.00', '$10.00', '$20.00'], ['$12.00', '$14.00', '$29.00'], [7, 9, 10], '/M'),
                'rows' => [['cPanel Accounts Supported', '30', '100', 'Unlimited'], ['Disk Space', '25 GB SSD', '60 GB SSD', 'Unlimited SSD'], ...self::resellerRows()],
                'includes_title' => 'Our Reseller hosting plans All include',
                'about' => ['Natore-IT Reseller plans are planned for you to obtain money. Reseller hosting is a form of web hosting so that the account owner has the ability to use his or her allotted hard drive space and bandwidth to host websites on behalf of third parties.'],
            ],
            'master-reseller-hosting' => [
                'title' => 'Master Reseller Hosting', 'heading' => 'Master Reseller Hosting Plan Perfect For You!', 'icon' => 'bi-diagram-3-fill',
                'lead' => 'Cheap master reseller hosting — create reseller accounts of your own and grow your hosting company.',
                'plans' => self::plans($three, ['$15.00', '$22.00', '$26.00'], ['$25.00', '$35.00', '$50.00'], [30, 29, 28], '/M'),
                'rows' => [['WHM/Reseller Accounts', '5', '15', 'Unlimited'], ['cPanel Accounts Supported', '100', '150', 'Unlimited'], ['Disk Space', '50 GB SSD', '100 GB SSD', 'Unlimited SSD'], ...self::resellerRows()],
                'includes_title' => 'Our Master Reseller Hosting Plans All Include',
                'about' => [
                    'The two main items you look for when you buy hosting are web space and bandwidth. Web space is the amount of space on the hosting server for your website; bandwidth is the amount of data that can be transferred to and from it.',
                    'Data carried across the web travels over network lines with limited capacity, so the quantity of data that can be moved at any point in time is limited — which is why bandwidth matters as much as space.',
                ],
            ],
            'alpha-master-reseller-hosting' => [
                'title' => 'Alpha Master Reseller Hosting', 'heading' => 'Alpha Master Reseller Hosting Plan Perfect For You!', 'icon' => 'bi-stars',
                'lead' => 'Cheap alpha reseller hosting — one step up from master reseller, with master reseller accounts included.',
                'plans' => self::plans($three, ['$25.00', '$32.00', '$37.50'], ['$50.00', '$60.00', '$80.00'], [33, 32, 31], '/M'),
                'rows' => [['Master Reseller Accounts Supported', '5', '10', 'Unlimited'], ['WHM/Reseller Accounts', '10', '20', 'Unlimited'], ['cPanel Accounts Supported', '200', '250', 'Unlimited'], ['Disk Space', '120 GB SSD', '150 GB SSD', 'Unlimited SSD'], ...self::resellerRows()],
                'includes_title' => 'Our Alpha Master reseller hosting plans all include',
                'about' => ['Alpha Master reseller hosting is one step up from Master Reseller Hosting. Many hosting providers advertise unlimited bandwidth and space; we give you real resources with master reseller accounts so you can scale your own hosting business.'],
            ],
            'vps' => ['eyebrow' => 'VPS',
                'title' => 'VPS', 'heading' => 'VPS Hosting Plan Perfect For You!', 'icon' => 'bi-server',
                'lead' => 'Dedicated resources with cPanel/WHM, LiteSpeed, Imunify360 and 1 TB backup storage.',
                'plans' => self::plans(['Starter', 'Business', 'Premium', 'Enterprise'], ['$60.00', '$76.00', '$95.00', '$117.00'], ['$100.00', '$125.00', '$150.00', '$200.00'], [null, null, null, null], '/M'),
                'rows' => [
                    ['CPU', '4 Cores', '6 Cores', '8 Cores', '10 Cores'],
                    ['RAM', '8 GB', '16 GB', '30 GB', '60 GB'],
                    ['SSD', '200 GB', '400 GB', '800 GB', '1.6 TB'],
                    ['Port', '200 Mbit/s', '400 Mbit/s', '600 Mbit/s', '1 Gbit/s'],
                    ['Snapshot', '1', '2', '3', '4'],
                    ['Traffic (100 Mbit/s)', '32 TB', '32 TB', '32 TB', '32 TB'],
                    ['Incoming Traffic', 'Unlimited', 'Unlimited', 'Unlimited', 'Unlimited'],
                    ['cPanel/WHM', true, true, true, true],
                    ['LiteSpeed', true, true, true, true],
                    ['Imunify360', true, true, true, true],
                    ['Jetpack Backup', true, true, true, true],
                    ['Backup Storage', '1 TB', '1 TB', '1 TB', '1 TB'],
                ],
                'includes_title' => 'All Our plans include',
            ],
            'seo' => ['eyebrow' => 'SEO',
                'title' => 'SEO', 'heading' => 'SEO Plan Perfect For You!', 'icon' => 'bi-graph-up-arrow',
                'lead' => 'Keyword research, on-page optimisation, link building and monthly reporting.',
                'plans' => self::plans($three, ['$100.00', '$300.00', '$500.00'], ['$125.00', '$400.00', '$700.00'], [14, 15, 16], '/M'),
                'rows' => array_merge(
                    [['Keywords Optimization', '5', '10', '20']],
                    array_map(fn ($l) => [$l, true, true, true], ['Initial Review & Analysis', 'In-Depth Site Analysis', 'Website Analysis', 'Mobile Usability Check',
                        'Content Duplicacy Check', 'Initial Backlinks analysis', 'Google Penalty Check', 'Competition Analysis', 'Keyword Research', 'Keyword Plan',
                        'On Page Optimization', 'SEO Friendly URL Setup', 'Check 404 Pages', 'Internal Link Building', 'H1, H2, H3 Tag Optimization',
                        'Image Optimization & Alt Tags', 'Website Speed Optimization', 'Facebook Page Setup', 'Twitter Setup', 'LinkedIn Setup',
                        'YouTube Channel Setup', 'Google XML sitemap creation', 'Google Analytics Setup', 'Robots.txt Optimization', 'Webmaster Optimization']),
                    [['Web 2.0 Submission', '10', '30', '50'], ['Directory Submission', '10', '30', '50'], ['Social Bookmarking', '50', '100', '200'],
                     ['Article Submission', '50', '100', '200'], ['Blog Comments', '10', '30', '50'], ['Citation Building', true, true, true],
                     ['Google My Business Listing', true, true, true], ['Forum Posting', '30', '50', '100'], ['Search Engine Rank Report', true, true, true],
                     ['SEO Report', true, true, true], ['Activity Report', true, true, true]]
                ),
                'includes_title' => null,
            ],
        ];
    }

    public static function sslPlans(): array
    {
        return [
            ['Comodo PositiveSSL', '$8.50', 17, 'Domain validation for one site'],
            ['Comodo PositiveSSL Wildcard', '$81.00', 34, 'Secures all sub-domains'],
            ['Comodo Instant SSL', '$36.00', 35, 'Organisation validated'],
            ['Comodo Essential SSL', '$13.00', 36, 'Fast issue, strong encryption'],
            ['Rapid SSL Standard', '$15.99', 37, 'Trusted DV certificate'],
            ['Rapid SSL Wildcard SSL', '$102.00', 38, 'Unlimited sub-domains'],
        ];
    }

    public static function sslOrderUrl(int $pid): string
    {
        return self::CART . $pid;
    }

    public static function domainLinks(): array
    {
        return [
            ['Domain Registration Sign-Up', 'http://nitbd.supersite2.myorderbox.com/login.php', 'bi-person-plus'],
            ['Customer Control Panel Login', 'http://nitbd.myorderbox.com/customer', 'bi-box-arrow-in-right'],
            ['Domain Reseller Sign-up', 'https://nitbd.partnersite.myorderbox.com/reseller.php?action=show_signupform', 'bi-people'],
            ['Domain Reseller Login', 'https://nitbd.myorderbox.com/reseller', 'bi-key'],
        ];
    }

    public static function softwareSolutions(): array
    {
        return [
            ['bi-diagram-3', 'ERP Software'], ['bi-code-square', 'Custom Web Applications'], ['bi-shop', 'POS Software'], ['bi-hospital', 'Hospital Management Software'],
            ['bi-box-seam', 'Inventory Management Software'], ['bi-mortarboard', 'School Management Software'], ['bi-boxes', 'Stock Management Software'],
            ['bi-graph-up', 'Sales Management Software'], ['bi-bag', 'Retail Management Software'], ['bi-capsule', 'Pharmacy Management Software'],
            ['bi-calculator', 'Accounting Software'], ['bi-people', 'NGO Management Software'], ['bi-buildings', 'Apartment Management Software'],
            ['bi-cup-hot', 'Restaurant Management Software'],
        ];
    }

    public static function aboutWhy(): array
    {
        return [
            ['bi-shield-lock', 'Reliability, Speed and Security'], ['bi-phone', 'Responsive Design'], ['bi-journal-bookmark', 'Easy Knowledge Base'],
            ['bi-tools', 'Free Web Tools & Applications'], ['bi-headset', '24/7 Support'], ['bi-arrow-repeat', 'Free Updates Lifetime'],
            ['bi-code-slash', 'In-house Development Team'], ['bi-boxes', 'Hosting and Software Under One Roof'],
        ];
    }

    public static function content(string $name): array
    {
        return require __DIR__ . '/content/' . $name . '.php';
    }
}
