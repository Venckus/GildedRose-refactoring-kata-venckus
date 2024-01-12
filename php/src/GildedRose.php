<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    private const AGED_BRIE = 'Aged Brie';
    private const BACKSTAGE_PASSES = 'Backstage passes to a TAFKAL80ETC concert';
    private const SULFURAS = 'Sulfuras, Hand of Ragnaros';
    private const CONJURED = 'Conjured';

    private const MIN_QUALITY = 0;
    private const MAX_QUALITY = 50;

    private const QUALITY_CHANGE_MULTIPLIER_1 = 1;
    private const QUALITY_CHANGE_MULTIPLIER_2 = 2;

    private const QUALITY_CHANGE_ADDITION_1 = 2;
    private const QUALITY_CHANGE_ADDITION_2 = 3;

    private const MIN_SELL_IN = 0;
    private const EXPIRED_SELLIN_MULTIPLIER = 2;

    private const BACKSTAGE_PASS_SELLIN_THRESHOLD_1 = 5;
    private const BACKSTAGE_PASS_SELLIN_THRESHOLD_2 = 10;


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
                $this->increaseItemQuality($item, self::QUALITY_CHANGE_MULTIPLIER_1);
                continue;
            }

            if ($item->name === self::BACKSTAGE_PASSES) {
                $this->updateBackstagePasses($item);
                continue;
            }

            if ($item->quality <= self::MIN_QUALITY) {
                continue;
            }

            if (str_contains($item->name, self::CONJURED)) {
                $this->decreaseItemQuality($item, self::QUALITY_CHANGE_MULTIPLIER_2);
                continue;
            }

            $this->decreaseItemQuality($item, self::QUALITY_CHANGE_MULTIPLIER_1);
        }
    }


    private function decreaseItemQuality(Item $item, int $updateMultiplier): void
    {
        $minQuality = self::EXPIRED_SELLIN_MULTIPLIER * $updateMultiplier;

        if ($item->sellIn < self::MIN_SELL_IN && $item->quality >= $minQuality) {
            $item->quality -= $minQuality;
            return;
        }

        $item->quality = max(self::MIN_QUALITY, $item->quality - $updateMultiplier);
    }


    private function increaseItemQuality(Item $item, int $addition): void
    {
        $item->quality = min(self::MAX_QUALITY, $item->quality + $addition);
    }


    private function updateBackstagePasses(Item $item): void
    {
        if ($item->sellIn <= self::MIN_SELL_IN) {
            $item->quality = self::MIN_QUALITY;
            return;
        }

        if ($item->sellIn <= self::BACKSTAGE_PASS_SELLIN_THRESHOLD_1) {
            $this->increaseItemQuality($item, self::QUALITY_CHANGE_ADDITION_2);
            return;
        }
        
        if ($item->sellIn <= self::BACKSTAGE_PASS_SELLIN_THRESHOLD_2) {
            $this->increaseItemQuality($item, self::QUALITY_CHANGE_ADDITION_1);
            return;
        }

        $item->quality++;
    }
}
