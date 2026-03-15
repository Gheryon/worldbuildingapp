<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConflictoRequest extends FormRequest
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
      // --- Identidad y localización ---
      'nombre'                            => 'required|string|max:256',
      'tipo_conflicto_id'                 => 'required|exists:tipo_conflicto,id',
      'conflicto_padre_id'                => 'nullable|exists:conflictos,id',
      'tipo_localizacion'                 => 'nullable|string|max:128',

      // Ubicación polimórfica (Asentamiento o Lugar)
      'ubicacion_principal_type'          => 'nullable|string|in:App\Models\Asentamiento,App\Models\Lugar',
      'ubicacion_principal_id'            => 'nullable|integer', // La validación de existencia cruzada es compleja, pero requerirlo es esencial

      // --- Pestaña: desarrollo e historia ---
      'descripcion'                       => 'nullable|string',
      'preludio'                          => 'nullable|string',
      'desarrollo'                        => 'nullable|string',

      // --- Pestaña: elementos bélicos ---
      'unidades_especiales'               => 'nullable|string',
      'criaturas_combate'                 => 'nullable|string',
      'maquinaria_warlike'                => 'nullable|string',

      // --- Pestaña: factores mágicos ---
      'es_conflicto_magico'               => 'sometimes|boolean',
      'bando_vencedor'                    => 'sometimes|string|in:atacante,defensor,ninguno',
      'hechizos_decisivos'                => 'nullable|string',
      'armas_magicas_empleadas'           => 'nullable|string',
      'seres_sobrenaturales_participants' => 'nullable|string',
      'fenomenos_naturales'               => 'nullable|string',

      // --- Pestaña: resultados ---
      'vencedor_texto'                    => 'nullable|string|max:256',
      'resultado'                         => 'nullable|string',
      'consecuencias'                     => 'nullable|string',
      'otros'                             => 'nullable|string',

      // --- Pestaña: beligerantes ---
      'personajes_atacantes'   => 'nullable|array',
      'personajes_atacantes.*' => 'exists:personajes,id',
      'personajes_defensores'   => 'nullable|array',
      'personajes_defensores.*' => 'exists:personajes,id',
      'paises_atacantes'   => 'nullable|array',
      'paises_atacantes.*' => 'exists:organizaciones,id',
      'paises_defensores'   => 'nullable|array',
      'paises_defensores.*' => 'exists:organizaciones,id',

      // Calendario de 13 meses
      'dia_fecha_inicio'      => 'nullable|integer|min:1|max:30',
      'mes_fecha_inicio'      => 'nullable|integer|min:1|max:13',
      'anno_fecha_inicio'     => 'nullable|integer',
      'dia_fecha_fin'       => 'nullable|integer|min:1|max:30',
      'mes_fecha_fin'       => 'nullable|integer|min:1|max:13',
      'anno_fecha_fin'      => 'nullable|integer',
    ];
  }
}
