<?php
namespace ME\Nit\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use ME\Nit\Models\ListItem;
use ME\Nit\Models\Plan;
use ME\Nit\Models\PlanPage;
use ME\Nit\Models\PortfolioItem;
use ME\Nit\Models\Review;
use ME\Nit\Models\TeamMember;
use ME\Nit\Models\WebPackage;

class DashboardController extends Controller
{
    public static $permissions = [
        'index' => ['dashboard_view', 'View the dashboard'],
    ];

    public function index()
    {
        // [label, count, icon, route, ability]
        $cards = [
            ['Plan pages', PlanPage::count(), 'fas fa-server', 'nit.admin.plan-pages.index', 'site_plan_page_view'],
            ['Plans', Plan::count(), 'fas fa-layer-group', 'nit.admin.plan-pages.index', 'site_plan_page_view'],
            ['Web design packages', WebPackage::count(), 'fas fa-palette', 'nit.admin.packages.index', 'site_package_view'],
            ['Portfolio projects', PortfolioItem::count(), 'fas fa-briefcase', 'nit.admin.portfolio.index', 'site_portfolio_view'],
            ['Team members', TeamMember::count(), 'fas fa-users', 'nit.admin.team.index', 'site_team_view'],
            ['Customer reviews', Review::count(), 'fas fa-star', 'nit.admin.reviews.index', 'site_review_view'],
            ['List items', ListItem::count(), 'fas fa-list-check', 'nit.admin.lists.index', 'site_list_view'],
        ];

        // Latest changes to the website content
        $recent = \Nit\Master\Models\ActivityLog::query()->where('subject_type', 'like', 'ME\\\\Nit\\\\Models\\\\%')
            ->latest('id')->limit(8)->get();

        return view('nit::admin.dashboard', ['cards' => $cards, 'recent' => $recent]);
    }
}
