<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{

    private $items;
    private const BACKSTAGE = 'Backstage passes to a TAFKAL80ETC concert';
    private const SULFURAS = 'Sulfuras, Hand of Ragnaros';
    private const AGED_BRIE = 'Aged Brie';
    private const CONJURED = 'Conjured';
    public const SULFURAS_QUALITY = 80;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {

            if ($this->isSulfuras($item)) {
                $this->setQuality($item, self::SULFURAS_QUALITY);
                continue;
            }

            if($this->isBackstagePasses($item)){
                $this->updateBackStacgePasses($item);
                continue;
            }

            if($this->isAgedBrie($item)){
                $this->updateAgedBrie($item);
                continue;
            }

            if ($this->isConjuredItem($item)){
                $this->updateConjuredItem($item);
                continue;
            }

            $this->updateNormalItem($item);
        }
    }

    private function reduceSellIn(Item $item, $days)
    {
        $item->sell_in -= $days;
    }

    private function reduceQuality(Item $item, $quality)
    {
        $item->quality -= $quality;
    }

    private function addQuality(Item $item, $quality)
    {
        $item->quality += $quality;
    }

    private function isSellInBiggerThanZero(Item $item)
    {
        return $item->sell_in > 0;
    }

    private function isQualityBiggerThanZero(Item $item)
    {
        return $item->quality > 0;
    }

    private function isAgedBrie(Item $item)
    {
        return $item->name=== self::AGED_BRIE;
    }

    private function isSulfuras(Item $item)
    {
        return $item->name=== self::SULFURAS;
    }

    private function isBackstagePasses(Item $item)
    {
        return $item->name === self::BACKSTAGE;
    }

    private function isQualityBiggerThanfifty(Item $item)
    {
        if($item->quality > 50){
            $this->setQuality($item, 50);
        }
    }

    private function setQuality(Item $item, $quality)
    {
        $item->quality = $quality;
    }

    private function isSellInBiggerThan(Item $item, $days){
        return $item->sell_in > $days;
    }

    private function updateBackStacgePasses(Item $item)
    {

        if ($this->isSellInBiggerThan($item, 10)) {
            $this->addQuality($item, 1);
        }elseif($this->isSellInBiggerThan($item, 5)){
            $this->addQuality($item, 2);
        }elseif ($this->isSellInBiggerThan($item, 1)){
            $this->addQuality($item, 3);
        }else{
            $this->setQuality($item, 0);
        }
        $this->reduceSellIn($item, 1);
        $this->isQualityBiggerThanfifty($item);

    }

    private function updateNormalItem($item){
        if ($this->isSellInBiggerThanZero($item)) {

            if ($this->isQualityBiggerThanZero($item)) {
                $this->reduceQuality($item, 1);
            }
        } else {

            if ($this->isQualityBiggerThanZero($item)) {
                $this->reduceQuality($item, 2);
            }
        }

        $this->reduceSellIn($item, 1);
        $this->isQualityBiggerThanfifty($item);
    }

    private function updateAgedBrie($item){
        if ($this->isSellInBiggerThanZero($item)){
            $this->addQuality($item, 1);
        }else{
            $this->addQuality($item, 2);
        }
        $this->reduceSellIn($item, 1);

        $this->isQualityBiggerThanfifty($item);
    }

    private function isConjuredItem(Item $item){
        return str_contains($item->name, self::CONJURED);
    }

    private function updateConjuredItem(Item $item){
        if ($this->isQualityBiggerThanZero($item)) {
                $this->reduceQuality($item, 2);
            }

        $this->reduceSellIn($item, 1);
        $this->isQualityBiggerThanfifty($item);
    }
}
