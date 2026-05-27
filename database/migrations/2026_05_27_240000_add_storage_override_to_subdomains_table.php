<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds an optional per-subdomain storage quota override.
     * When set, this value takes priority over the active plan's max_storage_mb.
     */
    public function up(): void
    {
        Schema::table('subdomains', function (Blueprint $table) {
            $table->unsignedInteger('storage_override_mb')->nullable()->after('status')
                ->comment('Per-subdomain custom storage quota (MB). Overrides the plan quota if set.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subdomains', function (Blueprint $table) {
            $table->dropColumn('storage_override_mb');
        });
    }
};
