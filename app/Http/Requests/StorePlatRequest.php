<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'categorie_id' => ['required', 'exists:categories,id'],
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'ingredients' => ['required', 'string', 'max:2000'],
            'temps_preparation' => ['required', 'integer', 'min:1', 'max:600'],
            'prix' => ['required', 'numeric', 'min:0', 'max:99999'],
            'stock' => ['required', 'integer', 'min:0'],
            'disponible' => ['nullable', 'boolean'],
            'image' => ['nullable', 'url', 'starts_with:http://,https://', 'max:2048'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:4096'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'categorie_id' => 'catégorie',
            'temps_preparation' => 'temps de préparation',
            'image' => "URL de l'image",
            'image_file' => 'image',
        ];
    }
}
