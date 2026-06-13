<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Langue par défaut
    |--------------------------------------------------------------------------
    |
    | Langue affichée au premier passage d'un visiteur (avant tout changement
    | manuel). Le français reste la langue source de l'application.
    |
    */
    'default' => 'fr',

    /*
    |--------------------------------------------------------------------------
    | Langues prises en charge
    |--------------------------------------------------------------------------
    |
    | Chaque entrée : code locale => libellé natif, sens d'écriture (ltr/rtl)
    | et abréviation affichée dans le sélecteur de langue.
    |
    */
    'supported' => [
        'fr' => ['native' => 'Français', 'short' => 'FR', 'dir' => 'ltr'],
        'en' => ['native' => 'English', 'short' => 'EN', 'dir' => 'ltr'],
        'ar' => ['native' => 'العربية', 'short' => 'AR', 'dir' => 'rtl'],
    ],

];
