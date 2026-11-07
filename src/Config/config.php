<?php
return [
    'name' => 'nit',

    // URL prefix for the public website ('' serves it from the site root)
    'site_prefix' => env('NIT_SITE_PREFIX', ''),

    // Live domain search on the website, backed by the WHMCS billing system
    'domain_checker' => [
        // whmcs-cart (no credentials) | whmcs-api (official API) | demo (sample data)
        'driver'         => env('NIT_DOMAIN_DRIVER', 'whmcs-cart'),
        'billing_url'    => env('NIT_BILLING_URL', 'https://billing.natoreit.com'),
        'spotlight_tlds' => ['.com', '.net', '.org', '.xyz', '.info', '.com.bd'],
        'cache_ttl'      => 300,
        'timeout'        => 10,
        'api'            => [
            'identifier'  => env('NIT_WHMCS_API_IDENTIFIER'),
            'secret'      => env('NIT_WHMCS_API_SECRET'),
            'currency_id' => env('NIT_WHMCS_CURRENCY_ID', 1),
        ],
    ],

    // Public contact details shown across the website (from natoreit.com)
    'site' => [
        'name'    => 'Natore IT',
        'tagline' => 'Domain, hosting, websites, ERP, POS and custom web applications from Bangladesh.',
        'phone'    => '+880 1628-092045',
        'phones'   => ['+880 1628-092045', '+880 1738-611938'],
        'support'  => '+880 1648-471607',
        'whatsapp' => '+880 1738-611938',
        'email'    => 'info@natoreit.com',
        'website'  => 'www.natoreit.com',
        'address'  => 'House 7, Road 14/C, Sector 4, Uttara, Dhaka-1230, Bangladesh',
        'hours'   => 'Sat–Thu, 9:00 AM – 6:00 PM',
        'since'   => 2015,
        'map_url'   => 'https://www.google.com/maps/search/?api=1&query=House+7+Road+14C+Sector+4+Uttara+Dhaka+1230',
        'map_embed' => 'https://maps.google.com/maps?q=House+7+Road+14C+Sector+4+Uttara+Dhaka+1230&z=15&output=embed',
        // Pages hosted on the WHMCS billing system
        'links' => [
            'blog'          => 'https://blog.natoreit.com',
            'login'         => 'https://billing.natoreit.com/login',
            'register'      => 'https://billing.natoreit.com/register.php',
            'knowledgebase' => 'https://billing.natoreit.com/knowledgebase.php',
            'news'          => 'https://billing.natoreit.com/announcements.php',
            'client_area'   => 'https://billing.natoreit.com/clientarea.php',
            'terms'         => 'https://billing.natoreit.com/index.php?rp=/knowledgebase',
            'privacy'       => 'https://billing.natoreit.com/index.php?rp=/knowledgebase',
            'refund'        => 'https://billing.natoreit.com/index.php?rp=/knowledgebase',
            'demo_cpanel'   => 'https://billing.natoreit.com/knowledgebase.php',
        ],
        'social'  => [
            'facebook' => '#',
            'twitter'  => '#',
            'linkedin' => '#',
            'youtube'  => '#',
        ],
    ],
];
