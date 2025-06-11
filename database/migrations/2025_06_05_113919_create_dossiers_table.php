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
        Schema::create('dossiers', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->foreignId('parent_id')->nullable()->constrained('dossiers')->nullOnDelete();
            $table->foreignId('regle_conservation_id')->nullable()->constrained('regle_conservations')->nullOnDelete();
            $table->enum('visibilite', ['publique', 'restreinte']);
            $table->json('types_autorises')->nullable(); // tableau JSON : ["pdf", "docx"]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};
