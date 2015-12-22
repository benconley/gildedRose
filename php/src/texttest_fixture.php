<?php
require_once 'gilded_rose.php';

$items = array(
    new Item('+5 Dexterity Vest', 10, 20),
    new Item('+5 Dexterity Vest', 2, 30),
    new Item('Aged Brie', 2, 0),
    new Item('Elixir of the Mongoose', 5, 7),
    new Item('Sulfuras, Hand of Ragnaros', 0, 80),
    new Item('Sulfuras, Hand of Ragnaros', -1, 80),
    new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
    new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49),
    new Item('Backstage passes to a TAFKAL80ETC concert', 3, 49),
    // this conjured item does not work properly yet
    new Item('Conjured Mana Cake', 3, 6)
);
$app = new GildedRose($items);
$days = 5;
if (isset($argv) && count($argv) > 1) {
    $days = (int) $argv[1];
}
echo("<pre>");
for ($i = 0; $i < $days; $i++) {
    echo("-------- day $i --------\n");
    echo("name, sellIn, quality\n");
    foreach ($items as $item) {
        echo $item . PHP_EOL;
    }
    echo PHP_EOL;
    $app->update_quality();
}
echo("</pre>");