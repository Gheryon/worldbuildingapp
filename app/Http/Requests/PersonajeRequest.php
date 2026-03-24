<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonajeRequest extends FormRequest
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
      'nombre'            => 'required|string|max:255',
      'nombre_familia'    => 'nullable|string|max:255',
      'apellidos'         => 'nullable|string|max:255',
      'apodo'             => 'nullable|string|max:255',
      'sexo'              => 'required|in:Hombre,Mujer',
      'select_especie'    => 'required|exists:especies,id',
      'lugar_nacimiento'  => 'nullable|string|max:255',
      'profesion'         => 'nullable|string|max:255',

      // Imagen de retrato
      'retrato' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

      // Fechas y fallecimiento
      'dia_nacimiento'      => 'nullable|integer|min:1|max:30',
      'mes_nacimiento'      => 'nullable|integer|min:1|max:13',
      'anno_nacimiento'     => 'nullable|integer',
      'dia_fallecimiento'   => 'nullable|integer|min:1|max:30',
      'mes_fallecimiento'   => 'nullable|integer|min:1|max:13',
      'anno_fallecimiento'  => 'nullable|integer',
      'causa_fallecimiento' => 'nullable|string|max:255',

      // Descripción breve

      // Campos de texto largo (pestañas)
      'descripcion_corta' => 'nullable|string|max:1000',
      'descripcion'       => 'nullable|string',
      'salud'             => 'nullable|string',
      'personalidad'      => 'nullable|string',
      'deseos'            => 'nullable|string',
      'miedos'            => 'nullable|string',
      'magia'             => 'nullable|string',
      'educacion'         => 'nullable|string',
      'religion'          => 'nullable|string',
      'familia'           => 'nullable|string',
      'politica'          => 'nullable|string',
      'biografia'         => 'nullable|string',
      'otros'             => 'nullable|string',
    ];
  }

  /**
   * Get custom messages for validator errors.
   *
   * @return array
   */
  public function messages()
  {
    return [
      'nombre.required' => 'El nombre del personaje es obligatorio.',
      'sexo.required' => 'El sexo es obligatorio.',
      'sexo.in' => 'El sexo seleccionado no es válido.',
      'select_especie.required' => 'Debe seleccionarse una especie.',
      'select_especie.exists' => 'La especie seleccionada no existe en el sistema.',
      'retrato.image' => 'El archivo debe ser una imagen.',
      'retrato.mimes' => 'La imagen debe estar en formato: jpeg, png, jpg o gif.',
      'retrato.max' => 'La imagen no puede ser mayor a 2MB.',
    ];
  }
}
