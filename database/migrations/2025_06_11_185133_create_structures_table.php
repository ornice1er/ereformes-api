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
        Schema::create('structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')->constrained('sectors')->onDelete('cascade');
            $table->string('sigl', 20)->unique();
            $table->string('designation', 255);
            $table->text('adresse_struct')->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->timestamps();

            // Index pour optimiser les recherches
            $table->index('sector_id');
            $table->index('sigl');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structures');
    }
};
