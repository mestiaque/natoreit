<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Content of the public website, edited from the admin panel.
 */
return new class extends Migration {
    public function up(): void
    {
        // Contact details, links, coupon … one row per key
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        // Hosting / VPS / SEO pages built from the plan template
        Schema::create('site_plan_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('heading')->nullable();
            $table->string('eyebrow')->nullable();
            $table->string('icon')->nullable();
            $table->string('lead', 500)->nullable();
            $table->string('includes_title')->nullable();
            $table->text('about')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Rows of a page's comparison table
        Schema::create('site_plan_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_page_id')->constrained('site_plan_pages')->cascadeOnDelete();
            $table->string('label');
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('site_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_page_id')->constrained('site_plan_pages')->cascadeOnDelete();
            $table->string('name');
            $table->string('price', 30);
            $table->string('regular_price', 30)->nullable();
            $table->string('period', 10)->default('/Y');
            $table->string('order_url', 500)->nullable();
            $table->boolean('is_featured')->default(false);
            // Feature id => "yes" | "no" | text
            $table->json('values')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('site_web_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('regular_price', 10, 2)->nullable();
            $table->decimal('renewal_price', 10, 2)->nullable();
            $table->string('order_url', 500)->nullable();
            $table->text('features')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('site_ssl_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('price', 30);
            $table->string('note')->nullable();
            $table->string('order_url', 500)->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('site_domain_prices', function (Blueprint $table) {
            $table->id();
            $table->string('tld', 30);
            $table->string('price', 30);
            $table->string('period', 20)->default('/yr');
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('site_portfolio_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('site_portfolio_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url', 500)->nullable();
            $table->string('image', 500)->nullable();
            // Category ids
            $table->json('categories')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('site_team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();
            $table->text('bio')->nullable();
            $table->string('image', 500)->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('site_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('author');
            $table->string('title')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('text');
            $table->string('date_label', 50)->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Small icon lists: why choose us, plan includes, software list, …
        Schema::create('site_list_items', function (Blueprint $table) {
            $table->id();
            $table->string('list', 50)->index();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->text('text')->nullable();
            $table->string('url', 500)->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // POS / CRM / multi-vendor feature pages
        Schema::create('site_software_pages', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique();
            $table->string('title');
            $table->text('intro')->nullable();
            // [{title, items: [..]}]
            $table->json('sections')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['site_software_pages', 'site_list_items', 'site_reviews', 'site_team_members', 'site_portfolio_items', 'site_portfolio_categories',
                  'site_domain_prices', 'site_ssl_plans', 'site_web_packages', 'site_plans', 'site_plan_features', 'site_plan_pages', 'site_settings'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
