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
    if (Schema::hasTable('construccions')) {
      // The "construccions" table exists...  
    }else{
      Schema::create('construccions', function (Blueprint $table) {
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_general_ci';

        $table->id();
        $table->string('nombre')->nullable();
        $table->text('descripcion')->nullable();
        $table->text('historia')->nullable();
        $table->text('proposito')->nullable();
        $table->text('aspecto')->nullable();
        $table->text('otros')->nullable();

        //$table->index('tipo')->nullable();
        $table->foreignId('tipo')
        ->nullable()
        ->constrained(table: 'tipo_construccion', indexName: 'tipo_id');
        //$table->foreignId('ubicacion')
        //->nullable()
        //->constrained(table: 'asentamientos', indexName: 'ubicacion');
        $table->integer('ubicacion')->nullable();
        $table->foreign('ubicacion')->references('id')->on('asentamientos');
        $table->integer('construccion')->default(0);
        $table->foreign('construccion')->references('id')->on('fechas');
        $table->integer('destruccion')->default(0);
        $table->foreign('destruccion')->references('id')->on('fechas');
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('construccions');
  }
};
