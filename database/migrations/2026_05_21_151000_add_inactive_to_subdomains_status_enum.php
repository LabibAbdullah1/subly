<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE subdomains MODIFY COLUMN status ENUM('active', 'inactive', 'suspended', 'expired') NOT NULL DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Avoid rollback failures by updating 'inactive' records to 'suspended' first
        DB::table('subdomains')->where('status', 'inactive')->update(['status' => 'suspended']);
        
        DB::statement("ALTER TABLE subdomains MODIFY COLUMN status ENUM('active', 'suspended', 'expired') NOT NULL DEFAULT 'active'");
    }
};
