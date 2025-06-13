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
        Schema::create('affectations', function (Blueprint $table) {
            $table->id();
            $table->boolean('isLast')->default(true)->comment('Indique si c\'est la dernière affectation');
            $table->unsignedBigInteger('reforme_id')->comment('ID de la réforme');
            $table->unsignedBigInteger('unite_admin_up')->comment('Unité administrative supérieure');
            $table->unsignedBigInteger('unite_admin_down')->comment('Unité administrative inférieure');
            $table->tinyInteger('sens')->comment('Sens de l\'affectation (1: montant, 0: descendant)');
            $table->text('instruction')->nullable()->comment('Instructions pour l\'affectation');
            $table->integer('delay')->nullable()->comment('Délai en jours');
            $table->timestamps();

             // Index pour améliorer les performances
            $table->index(['reforme_id', 'isLast']);
            $table->index(['unite_admin_up', 'unite_admin_down']);

            $table->foreign('reforme_id')->references('id')->on('reformes')->onDelete('cascade');
            $table->foreign('unite_admin_up')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('unite_admin_down')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }
};
