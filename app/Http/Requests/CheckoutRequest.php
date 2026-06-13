<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'adresse_livraison' => ['required', 'string', 'max:500'],
            'nom_recepteur' => ['required', 'string', 'max:255'],
            'telephone_recepteur' => ['required', 'string', 'max:30'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nom' => __('nom'),
            'prenom' => __('prénom'),
            'telephone' => __('téléphone'),
            'email' => __('email'),
            'adresse_livraison' => __('adresse de livraison'),
            'nom_recepteur' => __('nom du destinataire'),
            'telephone_recepteur' => __('téléphone du destinataire'),
        ];
    }
}
