<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Contact form messages and newsletter sign-ups sent from the website.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_messages', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20)->default('contact')->index(); // contact | newsletter
            $table->string('name', 100)->nullable();
            $table->string('email', 150);
            $table->string('phone', 30)->nullable();
            $table->string('subject', 50)->nullable();
            $table->text('message')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_messages');
    }
};
