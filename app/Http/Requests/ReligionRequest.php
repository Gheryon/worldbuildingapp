<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReligionRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      // Campos básicos
      'nombre'        => 'required|string|max:255',
      'lema'          => 'nullable|string|max:256',
      'tipo_teismo'   => 'nullable|string',
      'deidades'      => 'nullable|string|max:255',
      'estatus_legal' => 'required|string',

      // Imagen de escudo
      'escudo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

      // Fechas
      'dia_fundacion'   => 'nullable|integer|min:1|max:30',
      'mes_fundacion'   => 'nullable|integer|min:1|max:13',
      'anno_fundacion'  => 'nullable|integer',
      'dia_disolucion'  => 'nullable|integer|min:1|max:30',
      'mes_disolucion'  => 'nullable|integer|min:1|max:13',
      'anno_disolucion' => 'nullable|integer',

      // Descripción breve
      'descripcion' => 'nullable|string',

      // Campos de texto largo (pestañas)
      'cosmologia'       => 'nullable|string',
      'doctrina'         => 'nullable|string',
      'sagrado'          => 'nullable|string',
      'fiestas'          => 'nullable|string',
      'sobrenatural'     => 'nullable|string',
      'politica'         => 'nullable|string',
      'estructura'       => 'nullable|string',
      'sectas'           => 'nullable|string',
      'clase_sacerdotal' => 'nullable|string',
      'historia'         => 'nullable|string',
      'otros'            => 'nullable|string',
    ];
  }

  public function messages(): array
  {
    return [
      'nombre.required' => 'El nombre de la religión es obligatorio.',
      'nombre.max' => 'El nombre no puede superar los 255 caracteres.',
      'estatus_legal.required' => 'El estatus legal es obligatorio.',
      'escudo.image' => 'El archivo debe ser una imagen.',
      'escudo.mimes' => 'El escudo debe estar en formato: jpeg, png, jpg o gif.',
      'escudo.max' => 'El escudo no puede superar los 2MB.',
    ];
  }
}
