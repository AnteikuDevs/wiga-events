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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->uuid('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('my_storages');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type',['online','offline'])->default('offline');
            $table->text('location')->nullable();
            $table->text('link')->nullable();
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->date('registration_end')->nullable();
            $table->boolean('status_publish')->default(false);
            $table->bigInteger('registration_fee')->default(0);
            $table->integer('quota')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
