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
        Schema::create('digital_letters', function (Blueprint $table) {
            $table->id();
            $table->string('letter_type');
            $table->string('letter_number')->unique();
            $table->foreignId('resident_id')->constrained()->cascadeOnDelete();
            $table->longText('letter_content');
            $table->string('signature_path')->nullable();
            $table->date('issued_date');
            $table->date('valid_until')->nullable();
            $table->string('pdf_path')->nullable();
            $table->foreignId('issued_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_letters');
    }
};
