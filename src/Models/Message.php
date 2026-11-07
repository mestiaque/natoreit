<?php
namespace ME\Nit\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A contact form message or newsletter sign-up from the website. Not a
 * SiteModel: visitors' submissions neither touch the content cache nor
 * fill the activity log.
 */
class Message extends Model
{
    protected $table = 'site_messages';

    protected $guarded = ['id'];

    protected $casts = ['read_at' => 'datetime'];

    public const TYPES = ['contact' => 'Contact messages', 'quote' => 'Quote & demo requests', 'newsletter' => 'Newsletter sign-ups'];

    public const SUBJECTS = ['hosting' => 'Hosting', 'domain' => 'Domain', 'web-design' => 'Web design', 'software' => 'Software', 'seo' => 'SEO', 'support' => 'Support', 'sales' => 'Sales', 'other' => 'Something else'];

    public function activityLabel(): string
    {
        return $this->name ?: $this->email;
    }
}
