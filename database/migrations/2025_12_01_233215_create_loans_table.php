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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');            
            $table->date('loan_date');
            $table->date('due_date');
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'dipinjam', 'dikembalikan'])->default('diajukan');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('returned_at')->nullable();            
            $table->text('purpose');
            $table->text('admin_notes')->nullable();
            $table->timestamps(); 
            $table->index('status');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};