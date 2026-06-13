<?php

namespace App\Support;

use App\Models\Plat;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

/**
 * Panier client stocké en session : tableau [plat_id => quantite].
 */
class Panier
{
    public const KEY = 'panier';

    /** @return array<int,int> */
    public static function items(): array
    {
        return Session::get(self::KEY, []);
    }

    public static function add(int $platId, int $qty = 1): void
    {
        $items = self::items();
        $items[$platId] = ($items[$platId] ?? 0) + $qty;
        self::persist($items);
    }

    public static function set(int $platId, int $qty): void
    {
        $items = self::items();

        if ($qty <= 0) {
            unset($items[$platId]);
        } else {
            $items[$platId] = $qty;
        }

        self::persist($items);
    }

    public static function remove(int $platId): void
    {
        $items = self::items();
        unset($items[$platId]);
        self::persist($items);
    }

    public static function clear(): void
    {
        Session::forget(self::KEY);
    }

    /** Nombre total d'articles (somme des quantités). */
    public static function count(): int
    {
        return array_sum(self::items());
    }

    public static function isEmpty(): bool
    {
        return self::count() === 0;
    }

    /**
     * Lignes détaillées du panier : chaque entrée contient le plat, la
     * quantité et le sous-total. Les plats supprimés sont ignorés.
     *
     * @return Collection<int,array{plat:Plat,quantite:int,sous_total:float}>
     */
    public static function lignes(): Collection
    {
        $items = self::items();

        if (empty($items)) {
            return collect();
        }

        $plats = Plat::whereIn('id', array_keys($items))->get()->keyBy('id');

        return collect($items)
            ->filter(fn ($qty, $id) => $plats->has($id))
            ->map(fn ($qty, $id) => [
                'plat' => $plats[$id],
                'quantite' => (int) $qty,
                'sous_total' => round($plats[$id]->prix * $qty, 2),
            ])
            ->values();
    }

    public static function total(): float
    {
        return round(self::lignes()->sum('sous_total'), 2);
    }

    /** @param array<int,int> $items */
    private static function persist(array $items): void
    {
        Session::put(self::KEY, $items);
    }
}
