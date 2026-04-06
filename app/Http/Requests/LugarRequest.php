<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LugarRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      // Campos básicos
      'nombre'            => 'required|max:255',
      'otros_nombres'     => 'nullable|string|max:255',
      'select_tipo'       => 'required|exists:tipo_lugar,id',
      'nivel_peligro'     => 'nullable|string|in:Ninguno,Bajo,Moderado,Alto,Mortal,Desconocido',
      'tipo_peligro'      => 'required|string|in:Mágico,Fauna,Clima,Geológico,Político,Sobrenatural,Ninguno',
      'dificultad_acceso' => 'nullable|string|in:Muy fácil,Fácil,Moderada,Difícil,Extrema',
      'estacionalidad'    => 'nullable|string|max:255',
      'es_secreto'        => 'nullable|boolean',

      // Campos de texto largo (summernote)
      'descripcion_breve' => 'nullable|string',
      'geografia'         => 'nullable|string',
      'ecosistema'        => 'nullable|string',
      'clima'             => 'nullable|string',
      'fenomeno_unico'    => 'nullable|string',
      'flora_fauna'       => 'nullable|string',
      'recursos'          => 'nullable|string',
      'historia'          => 'nullable|string', // Campo Summernote
      'rumores'           => 'nullable|string',
      'otros'             => 'nullable|string',
    ];
  }

  /**
   * Preparación de datos antes de validar (Sanitización).
   */
  protected function prepareForValidation()
  {
    $this->merge([
      // Laravel no envía el checkbox si no está marcado, forzamos el booleano
      'es_secreto' => $this->has('es_secreto'),
    ]);
  }
}
