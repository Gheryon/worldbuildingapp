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
    Schema::create('religiones', function (Blueprint $table) {
      $table->charset = 'utf8mb4';
      $table->collation = 'utf8mb4_general_ci';

      $table->id();
      $table->string('nombre');
      $table->string('lema')->nullable();
      $table->string('escudo')->default('default.png');
      $table->text('descripcion')->nullable();
      $table->text('historia')->nullable();
      $table->text('cosmologia')->nullable();
      $table->text('doctrina')->nullable();
      $table->text('sagrado')->nullable();
      $table->text('fiestas')->nullable();
      $table->text('politica')->nullable();
      $table->text('estructura')->nullable();
      $table->text('sectas')->nullable();
      $table->text('otros')->nullable();

      $table->integer('fundacion')->default(0);
      $table->foreign('fundacion')->references('id')->on('fechas');
      $table->integer('disolucion')->default(0);
      $table->foreign('disolucion')->references('id')->on('fechas');

      /*$table->foreignId('fundacion')->constrained(
        table: 'fechas', indexName: 'id'
      );
      $table->foreignId('disolucion')->constrained(
        table: 'fechas', indexName: 'id'
      );*/
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('religiones');
  }
};
