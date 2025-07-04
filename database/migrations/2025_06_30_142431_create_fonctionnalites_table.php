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
        Schema::create('fonctionnalites', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->foreignId('entite_fonctionnelle_id')->constrained('entite_fonctionnelles')->onDelete('cascade');
            $table->foreignId('batiment_id')->nullable()->constrained('batiments')->onDelete('set null');
            $table->foreignId('etage_id')->nullable()->constrained('etages')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fonctionnalites');
    }
};
