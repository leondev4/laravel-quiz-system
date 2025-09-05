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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('code', 10)->unique(); // Código de la materia (ej: MAT101)
            $table->boolean('active')->default(true);
            $table->foreignId('user_id')->nullable()->constrained(); // Quien creó la materia
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para optimización
            $table->index(['active', 'created_at']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
