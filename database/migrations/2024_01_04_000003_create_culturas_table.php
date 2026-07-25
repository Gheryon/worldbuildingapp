<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('culturas', function (Blueprint $table) {
      $table->id();
      $table->string('categoria')->nullable()->default('regional');

      $table->string('nombre', 256);
      $table->string('gentilicio', 128)->nullable();
      $table->string('estatus', 64)->nullable();
      $table->string('tipo_territorio', 64)->nullable();

      $table->unsignedBigInteger('fundacion_id')->nullable();
      $table->unsignedBigInteger('disolucion_id')->nullable();
      $table->unsignedBigInteger('madre_id')->nullable();

      $table->text('descripcion_breve')->nullable();
      $table->mediumText('distribucion_geografica')->nullable();
      $table->mediumText('historia')->nullable();
      $table->mediumText('idioma')->nullable();
      $table->mediumText('estructura_social')->nullable();
      $table->text('roles_genero')->nullable();
      $table->text('unidad_familiar')->nullable();
      $table->mediumText('cosmovision')->nullable();
      $table->text('fiestas')->nullable();
      $table->text('tabues')->nullable();
      $table->text('simbolos')->nullable();
      $table->text('etica')->nullable();
      $table->text('vestimenta')->nullable();
      $table->text('gastronomia')->nullable();
      $table->text('arquitectura')->nullable();
      $table->text('arte_musica')->nullable();
      $table->text('tecnologia')->nullable();
      $table->text('educacion')->nullable();
      $table->text('actitud_magia')->nullable();
      $table->text('actitud_forasteros')->nullable();
      $table->text('otros')->nullable();

      $table->timestamps();
      $table->softDeletes();

      $table->foreign('fundacion_id')->references('id')->on('fechas')->onDelete('set null');
      $table->foreign('disolucion_id')->references('id')->on('fechas')->onDelete('set null');
      $table->foreign('madre_id')->references('id')->on('culturas')->onDelete('set null');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('culturas');
  }
};
