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
    Schema::create('organizaciones', function (Blueprint $table) {
      $table->id(); // BigInt autoincremental
      $table->string('nombre', 255);
      $table->string('gentilicio', 128)->nullable();
      $table->string('lema', 512)->nullable();
      $table->string('escudo', 255)->default('default.png');
      $table->string('estatus', 512)->nullable();//activo, en declive, disuelta, en el exilio...

      // --- Dualidad de Capital ---
      // Opción A: Nombre libre (String)
      $table->string('capital_nombre', 255)->nullable();
      // Opción B: Relación formal con asentamientos
      $table->foreignId('asentamiento_id')
        ->nullable();
        //->constrained('asentamientos')
        //->nullOnDelete(); // Comentado para evitar errores de migración circular

      // Relación con tipos (Reino, Gremio, etc.)
      $table->foreignId('tipo_organizacion_id')
        ->nullable()
        ->constrained('tipo_organizacion')
        ->nullOnDelete();

      // Relaciones de Cronología (apuntando a la tabla fechas)
      $table->unsignedBigInteger('fundacion_id')->nullable();
      $table->unsignedBigInteger('disolucion_id')->nullable();

      // Jerarquía y Poder
      // Relación recursiva (una organización posee a otra)
      $table->foreignId('organizacion_padre_id')
        ->nullable()
        ->constrained('organizaciones')
        ->nullOnDelete();

      // El líder de la organización
      $table->foreignId('lider_id')
        ->nullable()
        ->constrained('personajes')
        ->nullOnDelete();

      // Contenido narrativo
      $table->text('descripcion_breve')->nullable();
      $table->mediumText('historia')->nullable();
      $table->text('demografia')->nullable();
      $table->text('estructura')->nullable();
      $table->text('geopolitica')->nullable();
      $table->text('militar')->nullable();
      $table->text('religion')->nullable();
      $table->text('cultura')->nullable();
      $table->text('educacion')->nullable();
      $table->text('tecnologia')->nullable();
      $table->text('territorio')->nullable();
      $table->text('economia')->nullable();
      $table->text('recursos_naturales')->nullable();
      $table->text('otros')->nullable();

      // Auditoría y Borrado Lógico
      $table->timestamps();
      $table->softDeletes();

      // Claves foráneas manuales para fechas (ya que no siguen el nombre estándar)
      $table->foreign('fundacion_id')->references('id')->on('fechas')->onDelete('set null');
      $table->foreign('disolucion_id')->references('id')->on('fechas')->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('organizaciones');
  }
};
