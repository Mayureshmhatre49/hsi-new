<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boq_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boq_id')->constrained()->onDelete('cascade');
            $table->string('code');
            $table->text('description');
            $table->string('unit');
            $table->decimal('quantity', 15, 2);
            $table->decimal('unit_rate', 15, 2);
            $table->decimal('material_cost', 15, 2);
            $table->decimal('labor_cost', 15, 2);
            $table->decimal('total', 15, 2);
            $table->string('group_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boq_items');
    }
};
