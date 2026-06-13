<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Plats;
use Illuminate\Database\Seeder;

class CarteSeeder extends Seeder
{
    /**
     * Catégories + plats traditionnels marocains.
     */
    public function run(): void
    {
        $carte = [
            'Entrées' => [
                'couleur' => 'B45309',
                'description' => 'Soupes, salades et bouchées pour ouvrir le repas.',
                'plats' => [
                    ['Harira traditionnelle', 'Soupe emblématique du Maroc, riche et parfumée, servie au coucher du soleil.', 'Tomate, Lentilles, Pois chiches, Coriandre, Céleri', 40, 25, 30],
                    ['Briouates au fromage', 'Petits triangles croustillants de feuille de brick fourrés au fromage fondant.', 'Feuille de brick, Fromage, Persil, Œuf', 25, 30, 20],
                    ['Salade marocaine', 'Salade fraîche finement coupée, relevée d’herbes et de citron.', 'Tomate, Concombre, Oignon, Coriandre, Citron', 15, 20, 25],
                    ['Zaalouk d’aubergines', 'Caviar d’aubergines fondant mijoté à la tomate et aux épices.', 'Aubergine, Tomate, Ail, Cumin, Paprika', 30, 22, 18],
                ],
            ],
            'Plats principaux' => [
                'couleur' => 'C2410C',
                'description' => 'Tagines mijotés et grandes spécialités de la cuisine marocaine.',
                'plats' => [
                    ['Tagine d’agneau aux pruneaux', 'Agneau fondant mijoté longuement avec pruneaux, amandes et miel.', 'Agneau, Pruneaux, Amandes, Cannelle, Miel', 90, 85, 15],
                    ['Tagine de poulet au citron confit', 'Poulet doré aux olives et citron confit, sauce safranée.', 'Poulet, Citron confit, Olives, Gingembre, Safran', 75, 70, 18],
                    ['Pastilla au poulet', 'Feuilleté sucré-salé aux amandes, cannelle et sucre glace.', 'Feuille de brick, Poulet, Amandes, Cannelle, Sucre glace', 120, 95, 10],
                    ['Rfissa au poulet', 'Msemen effiloché, poulet et lentilles parfumés au fenugrec.', 'Msemen, Poulet, Lentilles, Fenugrec, Ras el hanout', 80, 78, 8],
                    ['Kefta mkaouara', 'Boulettes de bœuf épicées mijotées à la tomate avec œufs.', 'Bœuf haché, Tomate, Œufs, Cumin, Persil', 50, 60, 20],
                    ['Mechoui d’agneau', 'Épaule d’agneau rôtie lentement, cumin et beurre.', 'Épaule d’agneau, Cumin, Sel, Beurre', 150, 130, 0, false],
                ],
            ],
            'Couscous' => [
                'couleur' => '9A3412',
                'description' => 'Le plat du vendredi, semoule roulée à la main.',
                'plats' => [
                    ['Couscous aux sept légumes', 'Semoule vapeur et bouillon généreux de sept légumes.', 'Semoule, Courgette, Carotte, Navet, Pois chiches', 90, 75, 14],
                    ['Couscous Tfaya', 'Couscous sucré-salé aux oignons caramélisés et raisins secs.', 'Semoule, Oignons, Raisins secs, Cannelle, Poulet', 95, 80, 12],
                    ['Couscous au bœuf', 'Semoule et bœuf mijoté aux légumes de saison.', 'Semoule, Bœuf, Légumes, Pois chiches', 100, 82, 12],
                ],
            ],
            'Desserts' => [
                'couleur' => 'A16207',
                'description' => 'Pâtisseries au miel, aux amandes et à la fleur d’oranger.',
                'plats' => [
                    ['Chebakia', 'Fleurs de pâte frites enrobées de miel et de sésame.', 'Farine, Sésame, Miel, Anis, Fleur d’oranger', 60, 35, 25],
                    ['Cornes de gazelle', 'Croissants délicats fourrés à la pâte d’amande.', 'Amandes, Fleur d’oranger, Cannelle, Farine', 45, 40, 22],
                    ['Sellou', 'Confiserie énergétique de farine grillée, amandes et miel.', 'Amandes, Sésame, Farine grillée, Miel', 30, 38, 16],
                    ['Salade d’oranges à la cannelle', 'Oranges fraîches parfumées à la cannelle et fleur d’oranger.', 'Oranges, Cannelle, Fleur d’oranger', 15, 25, 20],
                ],
            ],
            'Thés & Boissons' => [
                'couleur' => '166534',
                'description' => 'Le thé à la menthe et autres boissons traditionnelles.',
                'plats' => [
                    ['Thé à la menthe', 'L’incontournable thé vert à la menthe fraîche, versé de haut.', 'Thé vert, Menthe fraîche, Sucre', 10, 15, 60],
                    ['Jus d’avocat', 'Smoothie onctueux d’avocat aux amandes et au lait.', 'Avocat, Lait, Amandes, Sucre', 10, 22, 30],
                    ['Qahwa épicée', 'Café noir parfumé à la cardamome et à la cannelle.', 'Café, Cardamome, Cannelle', 8, 14, 40],
                ],
            ],
        ];

        foreach ($carte as $nomCategorie => $infos) {
            $categorie = Categorie::create([
                'nom' => $nomCategorie,
                'description' => $infos['description'],
            ]);

            foreach ($infos['plats'] as $p) {
                [$nom, $description, $ingredients, $temps, $prix, $stock] = $p;
                $disponible = $p[6] ?? true;

                $categorie->plats()->create([
                    'nom' => $nom,
                    'description' => $description,
                    'ingredients' => $ingredients,
                    'temps_preparation' => $temps,
                    'prix' => $prix,
                    'stock' => $stock,
                    'disponible' => $disponible && $stock > 0,
                    'image' => 'https://placehold.co/800x600/'.$infos['couleur'].'/FFF7ED/png?text='.rawurlencode($nom),
                ]);
            }
        }
    }
}
