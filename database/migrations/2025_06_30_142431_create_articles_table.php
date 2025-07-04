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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->longText('contenu');
            $table->string('image_principale')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Auteur
            $table->timestamp('date_publication')->nullable();
            $table->enum('statut', ['brouillon', 'publie'])->default('brouillon');
            $table->string('langue', 5)->default('fr');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
