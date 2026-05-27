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
            $table->string('git_url')->nullable()->after('status');
            $table->string('git_branch')->nullable()->after('git_url');
            $table->text('git_token')->nullable()->after('git_branch');
            $table->string('git_last_commit')->nullable()->after('git_token');
            $table->timestamp('git_connected_at')->nullable()->after('git_last_commit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subdomains', function (Blueprint $table) {
            $table->dropColumn([
                'git_url',
                'git_branch',
                'git_token',
                'git_last_commit',
                'git_connected_at',
            ]);
        });
    }
};
