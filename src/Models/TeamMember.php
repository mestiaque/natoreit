<?php
namespace ME\Nit\Models;

class TeamMember extends SiteModel
{
    protected $table = 'site_team_members';

    protected $casts = ['is_active' => 'boolean'];
}
