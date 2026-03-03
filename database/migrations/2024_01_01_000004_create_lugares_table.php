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
    Schema::create('lugares', function (Blueprint $table) {
      // Identificación y visibilidad
      $table->id();
      $table->string('nombre', 256);
      $table->boolean('es_secreto')->default(false);
      $table->text('descripcion_breve')->nullable();

      // Relación con tipos de lugar (Bosque, Río, etc.)
      $table->foreignId('tipo_lugar_id')
        ->nullable()
        ->constrained('tipo_lugar')
        ->nullOnDelete();

      // Información geográfica y ambiental
      $table->mediumText('geografia')->nullable();
      $table->text('ecosistema')->nullable();
      $table->text('clima')->nullable();
      $table->string('fenomeno_unico')->nullable(); // Ej: Aurora permanente
      $table->string('estacionalidad')->nullable(); // Cambios según la época

      // Desafíos y mecánicas de juego/narrativa
      $table->string('nivel_peligro')->nullable(); // Mortal, Alto, Bajo...
      $table->string('tipo_peligro')->nullable(); // Mágico, Fauna, Clima, etc.
      $table->string('dificultad_acceso')->nullable(); // Escarpado, Sellado...

      // Recursos y biología
      $table->text('flora_fauna')->nullable();
      $table->text('recursos')->nullable();
      $table->string('recursos_naturales')->nullable(); // Versión resumida para filtros (ej: "Hierro, Hierba de Fuego") faciles de buscar.

      // Lore e historia
      $table->text('otros_nombres')->nullable();
      $table->mediumText('historia')->nullable();
      $table->text('rumores')->nullable(); // Leyendas o mitos locales
      $table->text('otros')->nullable(); // Campo para anécdotas o datos puntuales

      // Auditoría y borrado lógico
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('lugares');
  }
};
