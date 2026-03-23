<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EspecieRequest extends FormRequest
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
      'nombre'              => 'required|string|max:255',
      'reino'               => 'required|in:Animalia,Fungi,Monera,Plantae,Protista',
      'clase_taxonomica'    => 'required|in:Anfibio,Arácnidos,Ave,Insectos,Mamífero,Reptil,Peces',
      'locomocion'          => 'required|in:Acuático,Caminante,Escalador,Mixto,Terrestre,Volador',
      'organizacion_social' => 'required|in:Clan familiar,Colonia,Manada,Rebaño,Solitaria',

      // Características físicas
      'edad'        => 'nullable|string|max:100',
      'mortalidad'  => 'nullable|string|max:50',
      'peso'        => 'nullable|string|max:50',
      'altura'      => 'nullable|string|max:50',
      'longitud'    => 'nullable|string|max:50',

      // Clasificación adicional
      'dieta'   => 'required|in:Carnívoro,Herbívoro,Insectívoro,Omnívoro',
      'rareza'  => 'required|in:Común,Legendario,Mítológico,Raro',
      'estatus' => 'required|in:Viva,En peligro,Extinta',

      // Campos de texto largo (pestañas)
      'anatomia'          => 'nullable|string',
      'alimentacion'      => 'nullable|string',
      'reproduccion'      => 'nullable|string',
      'dimorfismo_sexual' => 'nullable|string',
      'distribucion'      => 'nullable|string',
      'habilidades'       => 'nullable|string',
      'domesticacion'     => 'nullable|string',
      'explotacion'       => 'nullable|string',
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
      'nombre.required' => 'El nombre de la especie es obligatorio.',
      'reino.required' => 'El reino es obligatorio.',
      'reino.in' => 'El reino seleccionado no es válido.',
      'clase_taxonomica.required' => 'La clase taxonómica es obligatoria.',
      'clase_taxonomica.in' => 'La clase taxonómica seleccionada no es válida.',
      'locomocion.required' => 'El tipo de locomoción es obligatorio.',
      'locomocion.in' => 'El tipo de locomoción seleccionado no es válido.',
      'organizacion_social.required' => 'La organización social es obligatoria.',
      'organizacion_social.in' => 'La organización social seleccionada no es válida.',
      'dieta.required' => 'La dieta es obligatoria.',
      'dieta.in' => 'La dieta seleccionada no es válida.',
      'rareza.required' => 'La rareza es obligatoria.',
      'rareza.in' => 'La rareza seleccionada no es válida.',
      'estatus.required' => 'El estatus es obligatorio.',
      'estatus.in' => 'El estatus seleccionado no es válido.',
    ];
  }
}
