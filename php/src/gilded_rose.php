<?php
class GildedRose {
    private $items;
    private $maxQuality = 50;
    private $legendaryItems = array('Sulfuras, Hand of Ragnaros'=>80);
    private $appreciatingItems = array(
        'Aged Brie'=>1
        , 'Backstage passes to a TAFKAL80ETC concert'=>1
    );
    private $perishableItems = array('Backstage passes to a TAFKAL80ETC concert'=>1);
    private $conjuredItems = array('Conjured Mana Cake'=>1);

    function __construct($items) {
        $this->items = $items;
    }

    function update_quality() {
        foreach ($this->items as $item) {

            // legendary items neither degrade with age not have an expiration date
            if ($this->isLegendary($item)) {
                $item->sell_in = 0;
                $item->quality = $this->legendaryItems[$item->name];

            // items that increase value with age
            } else if ($this->isAppreciating($item)) {
                $this->adjustAppreciatingItemQuality($item);

            // items that degrade
            } else {
                $qualityAdjust = -1;

                // conjured items decrease at an accelerated rate
                if ($this->isConjured($item)) {
                    $qualityAdjust--;
                }
                // items past their sell-by date lost quality twice as fast
                if($item->sell_in == 0) {
                    $qualityAdjust *= 2;   
                }
                $tmpQuality = $item->quality + $qualityAdjust;
                // item quality can never decrease below zero
                $item->quality = max(0, $tmpQuality);
            }

            // adjust the sell_in date for non-legendary items
            if (!$this->isLegendary($item)) {
                if ($item->sell_in > 0) {
                    $item->sell_in--;
                }
            }
            // perishable items are worth nothing when they reach expiration date
            if ($this->isPerishable($item)) {
                if ($item->sell_in == 0) {
                    $item->quality = 0;
                }
            }
        }
    }
    

    /****************************************************************************
        FUNC: isLegendary
        DESC: The array of legendary items uses full-string keys, but could be
            enhanced to use partial matches if we start to accumulate several.
    ****************************************************************************/
    function isLegendary($item) {
        // legendaryItems array will contain keys that match the legendary items
        return isset( $this->legendaryItems[$item->name] );
    }

    /****************************************************************************
        FUNC: isAppreciating
        DESC: Appreciating items gain value as they age
    ****************************************************************************/
    function isAppreciating($item) {
        return isset( $this->appreciatingItems[$item->name] );
    }

    /****************************************************************************
        FUNC: isPerishable
        DESC: Perishable items gain value at an increasing rate, but lose all
            value one they expire
    ****************************************************************************/
    function isPerishable($item) {
        return isset( $this->perishableItems[$item->name] );
    }

    /****************************************************************************
        FUNC: isConjured
        DESC: Conjured items degrade twice as fast as normal items
    ****************************************************************************/
    function isConjured($item) {
        return isset( $this->conjuredItems[$item->name] );
    }

    /****************************************************************************
        FUNC: adjustAppreciatingItemQuality
        DESC: Appreciating items gain quality at an increasing rate. Perishable
            items increase at an increasing rate as they near the sell by date
    ****************************************************************************/
    function adjustAppreciatingItemQuality($item) {
        if ($item->quality < $this->maxQuality) {
            // non perishables simply increase by an integer per iteration
            $item->quality++;
            // perishable items increase in quality with some rules
            if ( $this->isPerishable($item) ) {
                if ($item->sell_in < 11 && $item->quality < $this->maxQuality) {
                    $item->quality = $item->quality + 1;
                }
                if ($item->sell_in < 6 && $item->quality < $this->maxQuality) {
                    $item->quality = $item->quality + 1;
                }
            }
        }
    }


// end GildedRose class    
}

class Item {
    public $name;
    public $sell_in;
    public $quality;
    function __construct($name, $sell_in, $quality) {
        $this->name = $name;
        $this->sell_in = $sell_in;
        $this->quality = $quality;
    }
    public function __toString() {
        return "{$this->name}, {$this->sell_in}, {$this->quality}";
    }
// end Item class
}