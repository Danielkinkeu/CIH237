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
        Schema::create('partenaires', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('type_partenaire')->nullable(); // Ex: Laboratoire, Université, Startup, Institution
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('site_web')->nullable();
            $table->string('adresse')->nullable();
            $table->string('contact_email')->nullable();
            $table->date('date_partenariat')->nullable();
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partenaires');
    }
};
