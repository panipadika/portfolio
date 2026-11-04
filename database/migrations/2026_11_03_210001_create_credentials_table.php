<?php

declare(strict_types=1);

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
        Schema::create('credentials', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['certificate', 'achievement', 'tech_stack']);
            $table->string('title', 150);
            $table->string('issuer_or_level', 150)->nullable();
            $table->string('year', 20)->nullable();
            $table->string('image_or_icon', 255)->nullable();
            $table->string('credential_url', 255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credentials');
    }
};
