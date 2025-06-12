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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('projets_media_id');
            $table->string('chemin')->nullable();
            $table->string('name');
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('projets_media_id');

            // Clé étrangère si vous avez une table projets_media
            // $table->foreign('projets_media_id')->references('id')->on('projets_media')->onDelete('cascade');s
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
