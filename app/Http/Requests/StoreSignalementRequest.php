<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Signalement;
use Illuminate\Validation\Rule;

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
            'categorie' => ['required', Rule::in(Signalement::CATEGORIES)],
            'gravite'   => ['required', Rule::in(Signalement::GRAVITES)],
            'quartier_id' => ['required', 'integer', 'exists:quartiers,id'],

            'photo' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,jpg,png',
                'max:1024',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'photo.required' => 'La photo est obligatoire.',
            'photo.image' => 'Le fichier envoyé doit être une image.',
            'photo.max' => 'La photo ne doit pas dépasser 1 Mo.',
            'categorie.in' => 'Catégorie invalide.',
            'gravite.in' => 'Gravité invalide.',
            'quartier_id.exists' => 'Ce quartier n’existe pas.',
        ];
    }
}
