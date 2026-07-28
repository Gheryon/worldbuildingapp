<?php

namespace App\Http\Requests;

use App\Enums\ActitudForasteros;
use App\Enums\ActitudMagia;
use App\Enums\EstatusCultura;
use App\Enums\TipoTerritorio;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CulturaRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      //Campos básicos
      'nombre' => 'required|string|max:256',
      'gentilicio' => 'nullable|string|max:128',
      'estatus' => ['nullable', new Enum(EstatusCultura::class)],
      'tipo_territorio' => ['nullable', new Enum(TipoTerritorio::class)],
      'categoria' => 'nullable|string|max:64',
      'unidad_familiar' => 'nullable|string|max:64',
      'actitud_magia' => ['nullable', new Enum(ActitudMagia::class)],
      'actitud_forasteros' => ['nullable', new Enum(ActitudForasteros::class)],
      'madre_id' => 'nullable|exists:culturas,id',

      //fechas
      'dia_fundacion' => 'nullable|integer|min:1|max:30',
      'mes_fundacion' => 'nullable|integer|min:1|max:13',
      'anno_fundacion' => 'nullable|integer',
      'dia_disolucion' => 'nullable|integer|min:1|max:30',
      'mes_disolucion' => 'nullable|integer|min:1|max:13',
      'anno_disolucion' => 'nullable|integer',

      //Campos de texto largo (pestañas)
      'descripcion_breve' => 'nullable|string',
      'distribucion_geografica' => 'nullable|string',
      'historia' => 'nullable|string',
      'idioma' => 'nullable|string',
      'estructura_social' => 'nullable|string',
      'roles_genero' => 'nullable|string',
      'cosmovision' => 'nullable|string',
      'fiestas' => 'nullable|string',
      'tabues' => 'nullable|string',
      'simbolos' => 'nullable|string',
      'etica' => 'nullable|string',
      'vestimenta' => 'nullable|string',
      'gastronomia' => 'nullable|string',
      'arquitectura' => 'nullable|string',
      'arte_musica' => 'nullable|string',
      'tecnologia' => 'nullable|string',
      'educacion' => 'nullable|string',
      'otros' => 'nullable|string',

      //Imágenes de referencia
      'imagenes_referencia' => 'nullable|array',
      'imagenes_referencia.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
    ];
  }

  public function messages()
  {
    return [
      'nombre.required' => 'El nombre de la cultura es obligatorio.',
      'madre_id.exists' => 'La cultura madre seleccionada no existe.',
    ];
  }
}
