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
        Schema::create('projet_innovateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom_projet');
            $table->text('description');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Innovateur
            $table->string('lien_video')->nullable();
            $table->string('lien_site_web')->nullable();
            $table->string('image_projet')->nullable();
            $table->enum('statut', ['en_cours', 'termine', 'incube'])->default('en_cours');
            $table->timestamp('date_soumission')->useCurrent();
            $table->string('langue', 5)->default('fr');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projet_innovateurs');
    }
};
