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
        Schema::create('deployments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subdomain_id')->constrained()->cascadeOnDelete();
            $table->string('zip_path');
            $table->integer('version')->default(1);
            $table->enum('status', ['queued', 'processing', 'success', 'error'])->default('queued');
            $table->text('admin_note')->nullable();
            $table->timestamp('deployed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};
