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
            ['bi-code-slash', 'Our own development team', 'Websites, ERP, POS and custom applications are built by our in-house team.'],
            ['bi-life-preserver', 'Support after delivery', 'We stay with you after launch to help with your website or software.'],
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
            ['bi-hdd-stack', 'Domain & Hosting', 'Register your domain and host it on fast cPanel servers. We also offer domain, reseller and master reseller accounts for those selling to their own clients.', 'shared-hosting', ['Domain registration and reselling', 'Shared, WordPress and reseller hosting', 'Free SSL and business email']],
            ['bi-window', 'Websites', 'Responsive websites and online stores designed for your brand, delivered with domain, hosting and on-page SEO.', 'web-design', ['Company and landing page websites', 'E-commerce and multi-vendor stores', 'Ready-to-launch packages']],
            ['bi-diagram-3', 'ERP', 'One system for accounts, inventory, sales, purchases and staff, so every department works from the same data.', 'erp-software', ['Modules chosen for your business', 'Role-based user access', 'Reports for management']],
            ['bi-upc-scan', 'POS', 'Fast point of sale for shops and retail chains, with stock, purchases, customers and supplier records in one place.', 'pos-software', ['Sales, returns and exchanges', 'Inventory and purchase tracking', 'Sales and profit reports']],
            ['bi-code-square', 'Custom Web Apps', 'When ready-made software does not fit, we build a web application around the way you work.', 'custom-web-application', ['Portals and business systems', 'Payment and SMS integration', 'Support after delivery']],
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
    public static function paymentMethods(): array
    {
        return ['bKash', 'Nagad', 'Rocket', 'VISA', 'Mastercard', 'Bank'];
    }
    public static function usd(int|float $amount): string
    {
        return '$' . (floor($amount) == $amount ? number_format($amount) : number_format($amount, 2));
    }
    public static function softwareHome(): array
    {
        return [
            ['bi-diagram-3', 'ERP Software', 'Accounts, inventory, sales, purchases and staff in one system.', 'erp-software'],
            ['bi-upc-scan', 'POS Software', 'Sales, stock, customers and suppliers for shops and retail.', 'pos-software'],
            ['bi-people', 'CRM Software', 'Leads, clients, sales pipeline and team follow-ups.', 'crm-software'],
            ['bi-code-square', 'Custom Web Application', 'Software built around the way your business works.', 'custom-web-application'],
        ];
    }
    public static function process(): array
    {
        return [
            ['bi-chat-dots', 'Discover', 'We learn how your business works and what you need.'],
            ['bi-vector-pen', 'Plan & design', 'We agree the features, timeline, price and design.'],
            ['bi-code-slash', 'Build & test', 'Our team develops the system and tests it with you.'],
            ['bi-rocket-takeoff', 'Launch & support', 'We host it, train your team and stay with you.'],
        ];
    }
    public static function tech(): array
    {
        return ['Laravel', 'PHP', 'React', 'WordPress', 'MySQL', 'cPanel', 'WHM', 'LiteSpeed', 'Cloudflare', 'bKash', 'Nagad'];
    }
    public static function quoteServices(): array
    {
        return ['ERP Software', 'POS Software', 'CRM Software', 'Custom Web Application', 'Website', 'Domain & Hosting'];
    }
    public static function quoteBudgets(): array
    {
        return ['Not sure yet', 'Small', 'Medium', 'Large'];
    }
    public static function stats(): array
    {
        return [
            ['bi-calendar-check', '10+', 'Years in business'],
            ['bi-people', '1,000+', 'Happy clients'],
            ['bi-window-stack', '60+', 'Websites built'],
            ['bi-activity', '99.9%', 'Server uptime'],
        ];
    }
    public static function faqs(): array
    {
        return [
            ['How do I pay for a service?', 'You can pay with bKash, Nagad, Rocket, VISA, Mastercard or bank transfer from the client area after placing your order.'],
            ['How soon is my hosting account ready?', 'Most hosting accounts are set up automatically within a few minutes of payment. You get the login details by e-mail.'],
            ['Can you move my existing website for free?', 'Yes. Our team migrates websites, databases and e-mails from your old host at no extra cost.'],
            ['Is SSL included with hosting?', 'Every hosting plan comes with a free SSL certificate. Paid certificates with warranty are available on the SSL page.'],
            ['How long does it take to build software?', 'It depends on the features you need. After discussing your requirements we give you a timeline and a price.'],
            ['Can you build software to match my business?', 'Yes. We can adapt our ERP, POS and CRM software or build a custom web application around your process.'],
            ['Do you support the software after delivery?', 'Yes. Contact us about support and changes after delivery, and we will explain the options for your project.'],
            ['How can I get support?', 'Call or WhatsApp our support team, open a ticket from the client area, or send us a message from the contact page.'],
        ];
    }
}
