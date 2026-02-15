<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->foreignId('vendor_id')->nullable()->constrained();
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->string('category');
            $table->date('transaction_date');
            $table->string('status'); // Paid, Pending, Processing
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
