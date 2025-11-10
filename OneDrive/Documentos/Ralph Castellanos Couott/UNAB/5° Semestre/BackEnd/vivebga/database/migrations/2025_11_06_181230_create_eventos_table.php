<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nombre');
            $table->text('descripcion');
            $table->string('categoria');
            $table->date('fecha');
            $table->time('hora');
            $table->integer('cupos')->nullable();
            $table->integer('cupos_disponibles')->nullable();
            $table->string('direccion');
            $table->decimal('precio', 10)->nullable();
            $table->string('imagen');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
