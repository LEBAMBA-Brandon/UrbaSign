<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSignalementRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'description' => ['required', 'string', 'max:200'],
            'categorie' => ['required', 'in:Voirie,Ordures,Éclairage public,Caniveau/Inondation,Autre'],
            'gravite'   => ['required', 'in:Faible,Moyenne,Critique'],
            'quartier_id' => ['required', 'exists:quartiers,id'],

            'photo' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,jpg,png',
                'max:1024',
            ],
        ];
    }
}
