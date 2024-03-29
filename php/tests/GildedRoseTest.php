<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    /**
     * @dataProvider agedBrieProvider
     */
    public function testAgedBrie(int $startingQuality, int $qualityAfterUpdate): void
    {
        $items = [new Item('Aged Brie', 2, $startingQuality)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame($qualityAfterUpdate, $items[0]->quality);
        $this->assertSame(1, $items[0]->sellIn);
    }

    /**
     * @return array<string, array<int>>
     */
    public static function agedBrieProvider(): array
    {
        return [
            'quality increases' => [0, 1],
            'quality does not increase over 50' => [50, 50],
        ];
    }

    public function testSulfuras(): void
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', 2, 80)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(80, $items[0]->quality);
        $this->assertSame(2, $items[0]->sellIn);
    }

    /**
     * @dataProvider backstagePassesProvider
     */
    public function testBackstagePasses(
        int $startingSellIn,
        int $startingQuality,
        int $qualityAfterUpdate
    ): void {
        $items = [new Item(
            'Backstage passes to a TAFKAL80ETC concert',
            $startingSellIn,
            $startingQuality
        )];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame($qualityAfterUpdate, $items[0]->quality);
    }

    /**
     * @return array<string, array<int>>
     */
    public static function backstagePassesProvider(): array
    {
        return [
            'quality increases by 1 when sellIn is greater than 10' => [15, 20, 21],
            'quality increases by 2 when sellIn is between 10 and 6' => [10, 20, 22],
            'quality increases by 3 when sellIn is between 5 and 1' => [5, 20, 23],
            'quality does not increase over 50' => [9, 50, 50],
            'quality drops to 0 when sellIn is 0' => [0, 20, 0],
        ];
    }

    /**
     * @dataProvider otherItemsProvider
     */
    public function testOtherItems(
        int $startingSellIn,
        int $startingQuality,
        int $qualityAfterUpdate,
    ): void {
        $items = [new Item('foo', $startingSellIn, $startingQuality)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame($qualityAfterUpdate, $items[0]->quality);
    }

    /**
     * @return array<string, array<int>>
     */
    public static function otherItemsProvider(): array
    {
        return [
            'quality decreases by 1 when sellIn is greater than 0' => [2, 20, 19],
            'quality decreases by 2 when sellIn is 0' => [0, 20, 18],
            'quality does not decrease below 0' => [0, 0, 0],
            'quality does not decrease below 0' => [-5, 2, 0],
            'quality does not decrease below 0' => [-5, 1, 0],
        ];
    }

    /**
     * @dataProvider conjuredItemsProvider
     */
    public function testConjuredItems(
        int $startingSellIn,
        int $startingQuality,
        int $qualityAfterUpdate,
    ): void {
        $items = [new Item('Conjured Mana Cake', $startingSellIn, $startingQuality)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame($qualityAfterUpdate, $items[0]->quality);
    }

    /**
     * @return array<string, array<int>>
     */
    public static function conjuredItemsProvider(): array
    {
        return [
            'quality decreases by 2 when sellIn is greater than 0' => [2, 20, 18],
            'quality decreases by 4 when sellIn is 0' => [0, 20, 16],
            'quality does not decrease below 0' => [0, 0, 0],
            'quality does not decrease below 0' => [-5, 2, 0],
            'quality does not decrease below 0' => [-5, 1, 0],
        ];
    }
}
