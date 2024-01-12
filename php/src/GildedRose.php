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

            $item->sellIn--;

            if ($item->name === self::AGED_BRIE) {
                $this->increaseItemQuality($item, 1);
                continue;
            }

            if ($item->name === self::BACKSTAGE_PASSES) {
                $this->updateBackstagePasses($item);
                continue;
            }

            if ($item->quality <= 0) {
                continue;
            }

            if (str_contains($item->name, 'Conjured')) {
                $this->decreaseItemQuality($item, 2);
                continue;
            }

            $this->decreaseItemQuality($item, 1);
        }
    }

    private function decreaseItemQuality(Item $item, int $updateMultiplier): void
    {
        $minQuality = 2 * $updateMultiplier;

        if ($item->sellIn < 0 && $item->quality >= $minQuality) {
            $item->quality -= $minQuality;
            return;
        }

        $item->quality = max(0, $item->quality - $updateMultiplier);
    }

    private function increaseItemQuality(Item $item, int $multiplier): void
    {
        $item->quality = min(50, $item->quality + $multiplier);
    }


    private function updateBackstagePasses(Item $item): void
    {
        if ($item->sellIn <= 0) {
            $item->quality = 0;
            return;
        }

        if ($item->sellIn <= 5) {
            $this->increaseItemQuality($item, 3);
            return;
        }
        
        if ($item->sellIn <= 10) {
            $this->increaseItemQuality($item, 2);
            return;
        }

        $item->quality++;
    }
}
