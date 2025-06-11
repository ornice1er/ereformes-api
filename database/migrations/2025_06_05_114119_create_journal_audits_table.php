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
        Schema::create('journal_audits', function (Blueprint $table) {
        $table->id();
        $table->enum('action', ['consultation', 'telechargement']);
        $table->foreignId('utilisateur_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
        $table->dateTime('date_action');
        $table->text('details')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_audits');
    }
};
