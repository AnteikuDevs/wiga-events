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
        Schema::create('my_storages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('hash_name');
            $table->string('path');
            $table->string('url');
            $table->string('type')->comment('mimes file');
            $table->string('ext')->comment('ekstensi file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_storages');
    }
};
