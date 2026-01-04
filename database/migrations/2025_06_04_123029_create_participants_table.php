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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events');
            $table->string('name');
            $table->string('agency')->comment('Instansi');
            $table->string('email')->nullable();
            $table->string('phone_number');
            $table->string('reg_code')->nullable()->comment('Kode Registrasi');
            $table->boolean('status_publish')->default(false);
            $table->string('type')->default('participant')->comment('participant, committee');
            $table->uuid('proof_of_payment_id')->nullable();
            $table->foreign('proof_of_payment_id')->references('id')->on('my_storages');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
