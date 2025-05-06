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
        Schema::create('lab_results', function (Blueprint $table) {
            $table->id();
            // $table->string('patient_name')->nullable();
            // $table->string('gender')->nullable();
            // $table->string('test_code')->nullable();
            // $table->string('test_name')->nullable();
            // $table->string('value')->nullable();
            // $table->string('unit')->nullable();
            // $table->string('reference_range')->nullable();
            // $table->string('abnormal_flag')->nullable();

            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('test_code');
            $table->string('value');
            $table->string('unit')->nullable();
            $table->string('reference_range')->nullable();
            $table->string('flag')->nullable(); // H/L/normal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_results');
    }
};
