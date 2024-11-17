<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notebooks', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer');
            $table->string('type');
            $table->float('display', 8, 2);
            $table->integer('memory');
            $table->integer('harddisk');
            $table->string('videocontroller');
            $table->integer('price');
            $table->unsignedBigInteger('processorid');
            $table->unsignedBigInteger('opsystemid');
            $table->integer('pieces');
            $table->timestamps();
        
            $table->foreign('processorid')->references('id')->on('processors');
            $table->foreign('opsystemid')->references('id')->on('operating_systems');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notebooks');
    }
};