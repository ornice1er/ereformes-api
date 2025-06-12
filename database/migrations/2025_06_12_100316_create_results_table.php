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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('objectif_id');
            $table->string('libresult');
            $table->text('indicateur');
            $table->integer('valeur_cible');
            $table->integer('valeurref');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index('objectif_id');
            $table->index('status');

            // Si vous avez une table objectifs, décommentez cette ligne
            $table->foreign('objectif_id')->references('id')->on('objectifs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
