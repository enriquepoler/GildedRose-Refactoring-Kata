<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{

    public function testGuildedRoseUpdateNormalItem()
    {
        $days = random_int(1, 10);
        $quality = random_int(1, 50);
        $items = [
                new Item('a', $days, $quality),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $updatedItems = $gildedRose->getItems();

        $this->assertSame('a', $updatedItems[0]->name);
        $this->assertSame($days - 1, $updatedItems[0]->sell_in);
        $this->assertSame($quality - 1, $updatedItems[0]->quality);
    }

    public function testGuildedRoseUpdateQualityBeforeSellDay()
    {
        $days = 0;
        $quality = random_int(2, 50);
        $items = [
                new Item('a', $days, $quality),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $updatedItems = $gildedRose->getItems();

        $this->assertSame('a', $updatedItems[0]->name);
        $this->assertSame($days - 1, $updatedItems[0]->sell_in);
        $this->assertSame($quality - 2, $updatedItems[0]->quality);
    }

    public function testQualityCannotBeNegative()
    {
        $days = 0;
        $quality = 0;
        $items = [
                new Item('a', $days, $quality),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $updatedItems = $gildedRose->getItems();

        $this->assertSame('a', $updatedItems[0]->name);
        $this->assertSame($days - 1, $updatedItems[0]->sell_in);
        $this->assertSame($quality, $updatedItems[0]->quality);
    }

    public function testQualityCannotBeBiggerThanFifty()
    {
        $days = 0;
        $quality = 49;
        $items = [
                new Item('Aged Brie', $days, $quality),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $updatedItems = $gildedRose->getItems();

        $this->assertSame('Aged Brie', $updatedItems[0]->name);
        $this->assertSame($days - 1, $updatedItems[0]->sell_in);
        $this->assertSame(50, $updatedItems[0]->quality);
    }

    public function testQualityIncrementsWhenIsAgedBrie()
    {
        $days = random_int(1, 10);
        $quality = random_int(10, 30);
        $items = [
                new Item('Aged Brie', $days, $quality),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $updatedItems = $gildedRose->getItems();

        $this->assertSame('Aged Brie', $updatedItems[0]->name);
        $this->assertSame($days - 1, $updatedItems[0]->sell_in);
        $this->assertSame($quality + 1, $updatedItems[0]->quality);
    }

    public function testQualityIncrementsDoubleWhenIsAgedBrieAndSellDayIsZero()
    {
        $days = 0;
        $quality = random_int(10, 30);
        $items = [
                new Item('Aged Brie', $days, $quality),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $updatedItems = $gildedRose->getItems();

        $this->assertSame('Aged Brie', $updatedItems[0]->name);
        $this->assertSame($days - 1, $updatedItems[0]->sell_in);
        $this->assertSame($quality + 2, $updatedItems[0]->quality);
    }

    public function testSulfurasDoesntChangeAttributes()
    {
        $days = random_int(1, 10);
        $quality = random_int(10, 30);
        $sulfurasFixedValue = 80;

        $items = [
                new Item('Sulfuras, Hand of Ragnaros', $days, $quality),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $updatedItems = $gildedRose->getItems();

        $this->assertSame('Sulfuras, Hand of Ragnaros', $updatedItems[0]->name);
        $this->assertSame($days, $updatedItems[0]->sell_in);
        $this->assertSame($sulfurasFixedValue, $updatedItems[0]->quality);
    }

    public function testBackstageIncreaseQualityBeforeTenDays()
    {
        $days = random_int(10, 20);
        $quality = random_int(10, 30);
        $items = [
                new Item('Backstage passes to a TAFKAL80ETC concert', $days, $quality),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $updatedItems = $gildedRose->getItems();

        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $updatedItems[0]->name);
        $this->assertSame($days - 1, $updatedItems[0]->sell_in);
        $this->assertSame($quality + 1, $updatedItems[0]->quality);
    }

    public function testBackstageIncreaseDoubleQualityBeforeFiveDays()
    {
        $days = random_int(6, 10);
        $quality = random_int(10, 30);
        $items = [
                new Item('Backstage passes to a TAFKAL80ETC concert', $days, $quality),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $updatedItems = $gildedRose->getItems();

        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $updatedItems[0]->name);
        $this->assertSame($days - 1, $updatedItems[0]->sell_in);
        $this->assertSame($quality + 2, $updatedItems[0]->quality);
    }

    public function testBackstageIncreaseTripleQualityBeforeOneDay()
    {
        $days = random_int(1, 5);
        $quality = random_int(10, 30);
        $items = [
                new Item('Backstage passes to a TAFKAL80ETC concert', $days, $quality),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $updatedItems = $gildedRose->getItems();

        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $updatedItems[0]->name);
        $this->assertSame($days - 1, $updatedItems[0]->sell_in);
        $this->assertSame($quality + 3, $updatedItems[0]->quality);
    }

    public function testBackstageLosesAllQuallityAfterSellDay()
    {
        $days = 0;
        $quality = random_int(10, 30);
        $items = [
                new Item('Backstage passes to a TAFKAL80ETC concert', $days, $quality),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $updatedItems = $gildedRose->getItems();

        $this->assertSame('Backstage passes to a TAFKAL80ETC concert', $updatedItems[0]->name);
        $this->assertSame($days - 1, $updatedItems[0]->sell_in);
        $this->assertSame(0, $updatedItems[0]->quality);
    }

    public function testConjuredItem()
    {
        $days = random_int(1, 10);
        $quality = random_int(10, 30);
        $items = [
                new Item('Conjured Meat', $days, $quality),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $updatedItems = $gildedRose->getItems();

        $this->assertSame('Conjured Meat', $updatedItems[0]->name);
        $this->assertSame($days - 1, $updatedItems[0]->sell_in);
        $this->assertSame($quality - 2, $updatedItems[0]->quality);
    }
}
