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
        Schema::create('user_databases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subdomain_id')->constrained()->cascadeOnDelete();
            $table->string('db_name');
            $table->string('db_user');
            $table->string('db_password');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_databases');
    }
};
