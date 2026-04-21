<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['car', 'motorcycle']);
            $table->string('brand')->default('Honda'); // todo checar se é necessário colocar default
            $table->string('model');
            $table->integer('year'); // todo checar se será string ou integer
            $table->decimal('price', 12, 2);
            $table->string('color')->nullable();
            $table->integer('mileage')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
