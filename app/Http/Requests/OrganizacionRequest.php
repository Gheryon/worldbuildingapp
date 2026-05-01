<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizacionRequest extends FormRequest
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
      'nombre'                => 'required|string|max:255',
      'lema'                  => 'nullable|string|max:512',
      'gentilicio'            => 'nullable|string|max:128',
      'capital'               => 'nullable|string|max:128',

      // Escudo
      'escudo'                => 'nullable|file|image|mimes:jpg,png,gif|max:10240',

      // Selects
      'select_tipo'           => 'required|exists:tipo_organizacion,id',
      'select_lider'          => 'nullable|exists:personajes,id',
      'religiones'            => 'nullable|array',
      'religiones.*'          => 'exists:religiones,id',
      'select_organizacion_padre' => 'nullable',
      'select_organizacion_padre.*' => 'exists:organizaciones,id',

      // Fechas
      'dia_fundacion'         => 'nullable|integer|min:1|max:30',
      'mes_fundacion'         => 'nullable|integer|min:1|max:13',
      'anno_fundacion'        => 'nullable|integer',
      'dia_disolucion'        => 'nullable|integer|min:1|max:30',
      'mes_disolucion'        => 'nullable|integer|min:1|max:13',
      'anno_disolucion'       => 'nullable|integer',

      // Campos de texto largo (pestañas)
      'descripcion_breve'     => 'nullable|string',
      'estructura'            => 'nullable|string',
      'geopolitica'           => 'nullable|string',
      'militar'               => 'nullable|string',
      'demografia'            => 'nullable|string',
      'cultura'               => 'nullable|string',
      'religion'              => 'nullable|string',
      'educacion'             => 'nullable|string',
      'tecnologia'            => 'nullable|string',
      'economia'              => 'nullable|string',
      'territorio'            => 'nullable|string',
      'recursos_naturales'    => 'nullable|string',
      'historia'              => 'nullable|string',
      'otros'                 => 'nullable|string',
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
      'nombre.required' => 'El nombre de la organización es obligatorio.',
      'select_tipo.required' => 'Debe seleccionarse un tipo de organización.',
      'select_tipo.exists' => 'El tipo de organización seleccionado no existe.',
      'select_lider.exists' => 'El líder seleccionado no existe en el sistema.',
      'religiones.*.exists' => 'Una o más religiones seleccionadas no existen.',
      'select_organizacion_padre.*.exists' => 'La organización padre seleccionada no existe.',
      'escudo.file' => 'El escudo debe ser un archivo válido.',
      'escudo.image' => 'El escudo debe ser una imagen.',
      'escudo.mimes' => 'El escudo debe estar en formato: jpg, png o gif.',
      'escudo.max' => 'El escudo no puede ser mayor a 10MB.',
    ];
  }
}
