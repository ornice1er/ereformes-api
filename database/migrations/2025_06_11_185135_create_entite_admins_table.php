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
        Schema::create('entite_admins', function (Blueprint $table) {
            $table->id();
            $table->string('libelle', 255)->unique();
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('libelle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entite_admins');
    }
};
