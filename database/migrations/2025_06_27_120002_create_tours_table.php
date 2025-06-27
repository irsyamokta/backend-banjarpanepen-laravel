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
        Schema::create('tours', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('about');
            $table->string('location')->nullable();
            $table->string('operational');
            $table->string('start')->nullable();
            $table->string('end')->nullable();
            $table->text('facility');
            $table->string('maps')->nullable();
            $table->integer('price');
            $table->string('public_id')->nullable();
            $table->string('thumbnail');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
