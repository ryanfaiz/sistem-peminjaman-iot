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
        // Tabel pivot ini menyimpan detail item yang dipinjam dalam satu transaksi Loan.
        Schema::create('loan_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('restrict');

            $table->integer('quantity');

            $table->unique(['loan_id', 'item_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_items');
    }
};