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
        Schema::create('reforme_publication_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reforme_id')->constrained()->onDelete('cascade');
            $table->timestamp('sent_at');
            $table->json('recipients'); // Stocke les IDs des utilisateurs qui ont reçu la notification
            $table->timestamps();

            $table->unique('reforme_id'); // Une seule notification par réforme publiée
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reforme_publication_notifications');
    }
};
