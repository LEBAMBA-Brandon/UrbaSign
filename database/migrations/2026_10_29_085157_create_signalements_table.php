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
        Schema::create('signalements', function (Blueprint $table) {
            $table->id();
            $table->decimal('longitude', 10, 7)->index();
            $table->decimal('latitude', 10, 7)->index();
            $table->string('description', 200);

            $table->enum('gravite', ['Faible', 'Moyenne', 'Critique'])->index();
            $table->enum('categorie', ['Voirie', 'Ordures','Éclairage public', 'Caniveau/Inondation', 'Autre'])->index();
            $table->enum('statut', ['Signalé', 'En cours', 'Résolu'])->default('Signalé')->index();

            $table->foreignId('auteur_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('quartier_id')->index()->constrained('quartiers')->restrictOnDelete();

            $table->string('photo');

            $table->timestampTz('date_creation')->useCurrent()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signalements');
    }
};
