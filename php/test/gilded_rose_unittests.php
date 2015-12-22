<?php
require_once 'bootstrap.php';
require_once 'gilded_rose.php';
class GildedRoseTest extends PHPUnit_Framework_TestCase {
	/* Test progression of a standard depreciating item */
    function testNormalItem() {
        $items = array(new Item('Elixir of the Mongoose', 2, 5));
        $gildedRose = new GildedRose($items);
        
        // check that normal item degrades as expected
        $gildedRose->update_quality();
        $this->assertEquals(1, $items[0]->sell_in);
        $this->assertEquals(4, $items[0]->quality);

        // second iteration for assurance 
        $gildedRose->update_quality();
        $this->assertEquals(0, $items[0]->sell_in);
        $this->assertEquals(3, $items[0]->quality);

        // now that item is expired, it should degrade twice as fast 
        $gildedRose->update_quality();
        $this->assertEquals(0, $items[0]->sell_in);
        $this->assertEquals(1, $items[0]->quality);

        // ensure item does not degrade below a quality of 0
        $gildedRose->update_quality();
        $this->assertEquals(0, $items[0]->sell_in);
        $this->assertEquals(0, $items[0]->quality);
    }

    /* Test progression of an appreciating, non-perishable item */
    function testAppreciatingNonperishableItem() {
        $items = array(new Item('Aged Brie', 2, 0));
        $gildedRose = new GildedRose($items);
        
        // check that item increases quality as expected
        $gildedRose->update_quality();
        $this->assertEquals(1, $items[0]->sell_in);
        $this->assertEquals(1, $items[0]->quality);

        // second iteration for assurance 
        $gildedRose->update_quality();
        $this->assertEquals(0, $items[0]->sell_in);
        $this->assertEquals(2, $items[0]->quality);

        // ensure item does not degrade below a quality of 0
        $gildedRose->update_quality();
        $this->assertEquals(0, $items[0]->sell_in);
        $this->assertEquals(3, $items[0]->quality);
    }

    /* Test progression of an appreciating, perishable item */
    function testPerishableItems() {
        $items = array(
            new Item('Backstage passes to a TAFKAL80ETC concert', 11, 20)
            , new Item('Backstage passes to a TAFKAL80ETC concert', 6, 30)
            , new Item('Backstage passes to a TAFKAL80ETC concert', 2, 40)
            , new Item('Backstage passes to a TAFKAL80ETC concert', 20, 50)
        );
        $gildedRose = new GildedRose($items);
        
        // test assertions
        $gildedRose->update_quality();
        // over 10 days remaining should increase quality by 1
        $this->assertEquals(10, $items[0]->sell_in);
        $this->assertEquals(21, $items[0]->quality);

        // 6-10 days remaining should increase quality by 2
        $this->assertEquals(5, $items[1]->sell_in);
        $this->assertEquals(32, $items[1]->quality);

        // 1-5 days remaining should increase quality by 3
        $this->assertEquals(1, $items[2]->sell_in);
        $this->assertEquals(43, $items[2]->quality);

        // quality should never increase beyond 50
        $this->assertEquals(19, $items[3]->sell_in);
        $this->assertEquals(50, $items[3]->quality);

        // second iteration to test progressions
        $gildedRose->update_quality();
        // 6-10 days remaining should increase quality by 2
        $this->assertEquals(9, $items[0]->sell_in);
        $this->assertEquals(23, $items[0]->quality);

        // 1-5 days remaining should increase quality by 3
        $this->assertEquals(4, $items[1]->sell_in);
        $this->assertEquals(35, $items[1]->quality);

        // once product expires, the quality should fall to 0
        $this->assertEquals(0, $items[2]->sell_in);
        $this->assertEquals(0, $items[2]->quality);

        // quality should never increase beyond 50
        $this->assertEquals(18, $items[3]->sell_in);
        $this->assertEquals(50, $items[3]->quality);
    }

    /* Test progression of a legendary item */
    function testLegendaryItem() {
        $items = array(
            new Item('Sulfuras, Hand of Ragnaros', 16, 60)
            , new Item('Sulfuras, Hand of Ragnaros', 0, 40)
            , new Item('Sulfuras, Hand of Ragnaros', -6, 90)
        );
        $gildedRose = new GildedRose($items);
        
        // test assertions
        $gildedRose->update_quality();
        // All legendary items should come back with no expiration date and 80 quality
        $this->assertEquals(0, $items[0]->sell_in);
        $this->assertEquals(80, $items[0]->quality);

        // ensure expiration date doesn't increment from 0 to a negative number, and quality increased to 80
        $this->assertEquals(0, $items[1]->sell_in);
        $this->assertEquals(80, $items[1]->quality);

        // ensure negative expiration dates are irrelevant, and quality can not be above 80
        $this->assertEquals(0, $items[2]->sell_in);
        $this->assertEquals(80, $items[2]->quality);

        // second iteration to test progressions
        $gildedRose->update_quality();
        // All legendary items should come back with no expiration date and 80 quality
        $this->assertEquals(0, $items[0]->sell_in);
        $this->assertEquals(80, $items[0]->quality);

        // ensure expiration date doesn't increment from 0 to a negative number, and quality increased to 80
        $this->assertEquals(0, $items[1]->sell_in);
        $this->assertEquals(80, $items[1]->quality);

        // ensure negative expiration dates are irrelevant, and quality can not be above 80
        $this->assertEquals(0, $items[2]->sell_in);
        $this->assertEquals(80, $items[2]->quality);

    }

    /* Test progression of a conjured items */
    function testConjuredItems() {
        $items = array(
            new Item('Conjured Mana Cake', 3, 8)
            , new Item('Conjured Mana Cake', 1, 8)
            , new Item('Conjured Mana Cake', 0, 3)
        );
        $gildedRose = new GildedRose($items);
        
        // test assertions
        $gildedRose->update_quality();
        // Conjured items degrade 2 in a normal day
        $this->assertEquals(2, $items[0]->sell_in);
        $this->assertEquals(6, $items[0]->quality);

        // check for a degrade of 2 that will increase to 4 upon expiration 
        $this->assertEquals(0, $items[1]->sell_in);
        $this->assertEquals(6, $items[1]->quality);

        // ensure can't return negative sell_in values, and non-negative qualities
        $this->assertEquals(0, $items[2]->sell_in);
        $this->assertEquals(0, $items[2]->quality);
        
        // second iteration to test progressions
        $gildedRose->update_quality();
        // Conjured items degrade 2 in a normal day
        $this->assertEquals(1, $items[0]->sell_in);
        $this->assertEquals(4, $items[0]->quality);

        // check for a degrade of 2 that will increase to 4 upon expiration 
        $this->assertEquals(0, $items[1]->sell_in);
        $this->assertEquals(2, $items[1]->quality);

        // ensure can't return negative sell_in values, and non-negative qualities
        $this->assertEquals(0, $items[2]->sell_in);
        $this->assertEquals(0, $items[2]->quality);

    }

}