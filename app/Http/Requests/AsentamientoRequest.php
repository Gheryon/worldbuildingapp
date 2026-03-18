<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsentamientoRequest extends FormRequest
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
      'nombre'            => 'required|string|max:256',
      'poblacion'         => 'nullable|numeric|min:0',
      'gentilicio'        => 'nullable|max:256',
      'recurso_principal' => 'nullable|string|max:256',
      'nivel_riqueza'     => 'nullable|string|max:256',

      //selects
      'select_tipo'       => 'required|exists:tipo_asentamiento,id',
      'estatus'           => 'nullable|string|in:Abandonado,En ruinas,Habitado,Secreto,Olvidado',
      'select_owner'      => 'nullable|exists:organizaciones,id',
      'select_gobernante' => 'nullable|exists:personajes,id',

      // Rich Text
      'descripcion'       => 'nullable|string',
      'geografia'         => 'nullable|string',
      'clima'             => 'nullable|string',
      'ubicacion_detalles'=> 'nullable|string',
      'demografia'        => 'nullable|string',
      'cultura'           => 'nullable|string',
      'arquitectura'      => 'nullable|string',
      'gobierno'          => 'nullable|string',
      'defensas'          => 'nullable|string',
      'ejercito'          => 'nullable|string',
      'economia'          => 'nullable|string',
      'recursos'          => 'nullable|string',
      'historia'          => 'nullable|string',
      'otros'             => 'nullable|string',

      //fechas
      'dia_fundacion' => 'nullable|integer|min:1|max:30',
      'mes_fundacion' => 'nullable|integer',
      'anno_fundacion' => 'nullable|integer',
      'dia_disolucion' => 'nullable|integer|min:1|max:30',
      'mes_disolucion' => 'nullable|integer',
      'anno_disolucion' => 'nullable|integer',
    ];
  }
}
