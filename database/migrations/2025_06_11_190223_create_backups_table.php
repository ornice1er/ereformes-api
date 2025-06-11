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
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Nom du fichier de sauvegarde');
            $table->text('filename')->comment('Chemin complet du fichier de sauvegarde');
            $table->enum('type', ['doc', 'database', 'full', 'config'])->default('doc')->comment('Type de sauvegarde');
            $table->bigInteger('size')->nullable()->comment('Taille du fichier en octets');
            $table->text('description')->nullable()->comment('Description de la sauvegarde');
            $table->boolean('is_active')->default(true)->comment('Indique si la sauvegarde est active');
            $table->timestamps();

             // Index pour améliorer les performances
            $table->index(['type', 'created_at']);
            $table->index(['is_active', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};
