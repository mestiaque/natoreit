<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('site_plan_pages', function (Blueprint $table) {
            // Short line under the page name in the header's Hosting menu
            $table->string('menu_text')->nullable()->after('eyebrow');
        });
    }

    public function down(): void
    {
        Schema::table('site_plan_pages', fn (Blueprint $table) => $table->dropColumn('menu_text'));
    }
};
