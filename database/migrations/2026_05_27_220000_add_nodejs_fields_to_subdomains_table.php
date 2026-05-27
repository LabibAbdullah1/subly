<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subdomains', function (Blueprint $table) {
            $table->string('nodejs_version')->default('20')->after('status');
            $table->string('nodejs_startup_file')->default('server.js')->after('nodejs_version');
            $table->string('nodejs_mode')->default('production')->after('nodejs_startup_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subdomains', function (Blueprint $table) {
            $table->dropColumn(['nodejs_version', 'nodejs_startup_file', 'nodejs_mode']);
        });
    }
};
