<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    private const AGED_BRIE = 'Aged Brie';
    private const BACKSTAGE_PASSES = 'Backstage passes to a TAFKAL80ETC concert';
    private const SULFURAS = 'Sulfuras, Hand of Ragnaros';

    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            if ($item->name === self::SULFURAS) {
                continue;
            }

            if ($item->name === self::AGED_BRIE) {
                $this->updateBrie($item);
                continue;
            }

            if ($item->name === self::BACKSTAGE_PASSES) {
                $this->updateBackstagePasses($item);
                continue;
            }

            $item->sellIn--;

            if ($item->quality <= 0) {
                continue;
            }

            if ($item->sellIn < 0 && $item->quality >= 2) {
                $item->quality -= 2;
            } else {
                $item->quality--;
            }
        }
    }


    private function updateBackstagePasses(Item $item): void
    {
        $item->sellIn--;

        if ($item->sellIn <= 0) {
            $item->quality = 0;
            return;
        }
        
        if ($item->sellIn <= 5) {
            $item->quality += 3;
            return;
        }
        
        if ($item->sellIn <= 10) {
            $item->quality += 2;
            return;
        }

        $item->quality++;
    }

    private function updateBrie(Item $item): void
    {
        if ($item->quality < 50) {
            $item->quality++;
        }
        $item->sellIn--;
    }
}
