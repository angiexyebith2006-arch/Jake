<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
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
            'nombre' => 'required|string|max:100'
            'correo' => 'required|email|max:100'
            'telefono' => 'nullable|string|max:20'
            'rol' => 'required|string|max:50'
            'nivel_ministerial' => 'required|string|max:50'
        ];
    }

    public function messages()
{
    return [
            
            'nombre.required' =>'El nombre es obligatorio.',
            'correo.required' =>'El correo es obligatoria.',
            'telefono.required' =>'El telefono es obligatorio.',
            'rol.required' =>'Seleccione su rol.',
            'nivel_ministerial.required' =>'Seleccione su nivel ministerial.',
            
        ];
    }   
}
