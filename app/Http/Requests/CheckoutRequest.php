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
            'nom' => 'nom',
            'prenom' => 'prénom',
            'telephone' => 'téléphone',
            'email' => 'email',
            'adresse_livraison' => 'adresse de livraison',
            'nom_recepteur' => 'nom du destinataire',
            'telephone_recepteur' => 'téléphone du destinataire',
        ];
    }
}
