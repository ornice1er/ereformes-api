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
        Schema::create('reformes', function (Blueprint $table) {
            $table->id();
             // Clés étrangères
            $table->unsignedBigInteger('structure_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('nature_id');
            $table->unsignedBigInteger('couverture_id');

             // Informations principales
            $table->string('libref', 500)->comment('Libellé de la réforme');
            $table->string('typeref', 100)->comment('Type de réforme');
            $table->text('objectif_glob')->comment('Objectif global');
            $table->string('popul_cible', 500)->comment('Population cible');
            $table->string('struct_impl', 500)->comment('Structure d\'implémentation');

             // Période et dates
            $table->string('periodexe', 100)->comment('Période d\'exécution');
            $table->date('date_debut')->comment('Date de début');
            $table->date('date_fin')->comment('Date de fin');
            $table->date('date_enreg')->comment('Date d\'enregistrement');

             // Cadre institutionnel et état
            $table->string('cadreinst_mor', 100)->comment('Cadre institutionnel');
            $table->enum('etat_mor', ['EN COURS', 'TERMINÉ', 'SUSPENDU', 'ANNULÉ'])
                   ->default('EN COURS')
                   ->comment('État de la réforme');

             // Montants
            $table->decimal('montant_prevu', 15, 2)->comment('Montant prévu');
            $table->decimal('montant_trealise', 15, 2)->default(0)->comment('Montant réalisé');

             // Difficultés, solutions et perspectives
            $table->text('difficult')->nullable()->comment('Difficultés rencontrées');
            $table->text('solution')->nullable()->comment('Solutions apportées');
            $table->text('perspective')->nullable()->comment('Perspectives');

             // Statut de publication
            $table->boolean('isPublished')->default(true)->comment('Statut de publication');

             // Timestamps
            $table->timestamps();

             // Index et contraintes
            $table->index('structure_id');
            $table->index('user_id');
            $table->index('nature_id');
            $table->index('couverture_id');
            $table->index('etat_mor');
            $table->index('isPublished')->default(true);
            $table->index(['date_debut', 'date_fin']);

             // Contraintes de clés étrangères
            $table->foreign('structure_id')->references('id')->on('structures')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('nature_id')->references('id')->on('natures')->onDelete('restrict');
            $table->foreign('couverture_id')->references('id')->on('couvertures')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reformes');
    }
};
