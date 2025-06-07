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
        Schema::create('participant_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events');
            $table->foreignId('participant_id')->constrained('participants');
            $table->foreignId('certificate_template_id')->constrained('certificate_templates');
            $table->string('participant_type');
            $table->string('certificate_number');
            $table->uuid('certificate_file_id')->nullable();
            $table->foreign('certificate_file_id')->references('id')->on('my_storages');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_certificates');
    }
};
