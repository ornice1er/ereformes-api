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
        Schema::create('suivi_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('result_id');
            $table->decimal('taux_realisat', 5, 2); // Pourcentage avec 2 décimales (ex: 100.00)
            $table->integer('valeur_realise');
            $table->date('date');
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index('result_id');
            $table->index('date');
            $table->index(['result_id', 'date']); // Index composé pour les requêtes combinées

            // Clé étrangère vers la table resultat
            $table->foreign('result_id')->references('id')->on('results')->onDelete('cascade');

            // Contrainte unique pour éviter les doublons (un seul suivi par résultat et par date)
            $table->unique(['result_id', 'date'], 'unique_result_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suivi_results');
    }
};
