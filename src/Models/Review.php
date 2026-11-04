<?php
namespace ME\Nit\Models;

class Review extends SiteModel
{
    protected $table = 'site_reviews';

    protected $casts = ['is_active' => 'boolean', 'rating' => 'integer'];

    public function activityLabel(): string
    {
        return $this->author;
    }
}
