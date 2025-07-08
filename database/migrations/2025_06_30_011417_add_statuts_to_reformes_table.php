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
        Schema::table('reformes', function (Blueprint $table) {
             $table->enum('statuts', ['Planification', 'Exécution', 'Evaluation'])
                   ->default('Planification')
                   ->comment('Statut de la réforme');

            $table->index('statuts');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reformes', function (Blueprint $table) {
            //
        });
    }
};
