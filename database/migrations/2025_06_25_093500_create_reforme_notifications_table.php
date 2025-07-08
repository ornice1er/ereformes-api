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
        Schema::create('reforme_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reforme_id')->constrained()->onDelete('cascade');
            $table->enum('notification_type', ['10_days', '3_days', 'today']);
            $table->timestamp('sent_at');

            $table->unique(['reforme_id', 'notification_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reformes_notifications');
    }
};
