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
        Schema::create('collaborators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('matricule')->unique();
            $table->date('date_naissance')->nullable();
            $table->string('email')->unique();
            $table->string('adresse')->nullable();
            $table->string('genre')->nullable();
            $table->date('date_embauche')->nullable();
            $table->decimal('salaire_base', 10, 2)->default(0);
            $table->string('poste')->nullable();
            $table->string('statut')->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaborators');
    }
};
