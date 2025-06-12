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
        Schema::create('objectifs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reforme_id');
            $table->string('libobjectif', 255);
            $table->timestamps();

            // Clé étrangère (optionnel)
            $table->foreign('reforme_id')->references('id')->on('reformes')->onDelete('cascade');

            // Index pour optimiser les requêtes
            $table->index('reforme_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objectifs');
    }
};
