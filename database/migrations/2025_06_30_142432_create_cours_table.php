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
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description');
            $table->decimal('prix', 8, 2)->default(0);
            $table->string('image_couverture')->nullable();
            $table->foreignId('formateur_id')->constrained('users')->onDelete('cascade'); // Role formateur
            $table->enum('niveau', ['debutant', 'intermediaire', 'avance'])->default('debutant');
            $table->string('statut')->default('brouillon'); // brouillon, publie, archive
            $table->string('langue', 5)->default('fr');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
