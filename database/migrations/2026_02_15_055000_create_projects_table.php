<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('client');
            $table->string('location');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status'); // Planning, Execution, On Track, Delayed, Completed
            $table->decimal('budget', 15, 2);
            $table->decimal('margin_projection', 5, 2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
