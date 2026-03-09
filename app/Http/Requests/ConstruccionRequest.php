<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConstruccionRequest extends FormRequest
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
      'nombre'                => 'required|string|max:256',
      'tipo_construccion_id'  => 'required|exists:tipo_construccion,id',
      'estatus'               => 'required|nullable|string',
      'asentamiento_id'       => 'nullable|exists:asentamientos,id',
      'altura'                => 'nullable|numeric|min:0',
      'altitud'               => 'nullable|numeric',
      'nivel_deterioro'       => 'nullable|string',
      'dificultad_acceso'     => 'nullable|string',

      // Rich Text
      'descripcion_breve'     => 'nullable|string',
      'aspecto'               => 'nullable|string',
      'historia'              => 'nullable|string',
      'arquitectura'          => 'nullable|string',
      'proposito'             => 'nullable|string',
      'importancia_social'    => 'nullable|string',
      'materiales_principales' => 'nullable|string',
      'materiales_exoticos'   => 'nullable|string',
      'tecnica_construccion'  => 'nullable|string',
      'rutas_acceso'          => 'nullable|string',
      'simbolismo'            => 'nullable|string',
      'propiedades_magicas'   => 'nullable|string',
      'fuente_poder_magico'   => 'nullable|string',
      'otros'                 => 'nullable|string',

      'tipo_magia'            => 'nullable|string|max:256',
      'tiene_magia_inherente' => 'nullable|boolean',

      // Calendario de 13 meses
      'dia_construccion'      => 'nullable|integer|min:1|max:30',
      'mes_construccion'      => 'nullable|integer|min:1|max:13',
      'anno_construccion'     => 'nullable|integer',
      'dia_destruccion'       => 'nullable|integer|min:1|max:30',
      'mes_destruccion'       => 'nullable|integer|min:1|max:13',
      'anno_destruccion'      => 'nullable|integer',

      'acceso_publico'        => 'nullable|boolean',
      'acceso_temporal'       => 'nullable|boolean',
      'tecnologia_perdida'    => 'nullable|boolean',
    ];
  }
}
