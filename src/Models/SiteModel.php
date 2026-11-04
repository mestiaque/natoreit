<?php
namespace ME\Nit\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Nit\Master\Models\Concerns\LogsActivity;
use ME\Nit\Support\Content;

/**
 * Base of the website content models: changes are logged and clear the
 * public site's content cache.
 */
abstract class SiteModel extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::saved(fn () => Content::flush());
        static::deleted(fn () => Content::flush());
    }

    /** Website order: the "sort" column, then oldest first. */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
