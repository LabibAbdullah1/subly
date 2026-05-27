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
        Schema::create('subdomain_envs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subdomain_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->text('value'); // Encrypted env value
            $table->boolean('is_secret')->default(true); // Default true for privacy masking
            $table->timestamps();

            // Mencegah duplikasi key pada satu subdomain
            $table->unique(['subdomain_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subdomain_envs');
    }
};
