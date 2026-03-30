<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventoRequest extends FormRequest
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
      'nombre'=>'required|string|max:255',
      'dia'      => 'nullable|integer|min:1|max:30',
      'mes'      => 'nullable|integer|min:1|max:13',
      'anno'     => 'required|nullable|integer',
      'descripcion'=>'required',
      'form_tipo'=>'string|max:255|in:crisis,epidemia,general,logro,politico,religioso',
      'form_categoria'=>'string|max:255|in:local,regional,continental,global,universal',
    ];
  }
}
