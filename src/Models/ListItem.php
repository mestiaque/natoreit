<?php
namespace ME\Nit\Models;

class ListItem extends SiteModel
{
    protected $table = 'site_list_items';

    protected $casts = ['is_active' => 'boolean'];

    /** The lists shown on the website: key => [label, where it appears]. */
    public const LISTS = [
        'why_choose'      => ['Why choose us', 'Home and Web Design'],
        'plan_includes'   => ['Plans include', 'Hosting, VPS pages'],
        'site_types'      => ['Website types', 'Home and Web Design'],
        'featured_hosting'=> ['Featured hosting', 'Home page (text: one bullet per line after the first line)'],
        'offer_tabs'      => ['What more we offer', 'Home page tabs (text: one bullet per line after the first line)'],
        'about_why'       => ['About: why choose us', 'About page'],
        'software'        => ['Software solutions', 'Software Solution page'],
        'domain_links'    => ['Domain links', 'Domain page (with URL)'],
        'payment_methods' => ['Payment methods', 'Footer'],
        'footer_resources'=> ['Footer resources', 'Footer (with URL)'],
    ];
}
