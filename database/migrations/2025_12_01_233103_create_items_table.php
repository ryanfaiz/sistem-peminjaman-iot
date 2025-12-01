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
        Schema::create('items', function (Blueprint $table) {
            $table->id();            
            $table->string('name');             
            $table->string('code')->unique();             
            $table->integer('total_quantity');             
            $table->integer('available_quantity');             
            $table->text('description')->nullable();            
            $table->enum('condition', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
            $table->string('photo_path')->nullable(); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};