# gildedRose
Hi Justin,

I've taken an initial swing at the refactoring effort for SciMed, as requested by Adam. I set up a github for this effort just to make things a little more easily accessible. The actual source code is still contained within in a similar organization to the original code, but I've put effort into abstracting out useful functions. I've also implemented PHPUnit tests to check all of the use cases for correct operation.

SOURCE CODE:
------------
	https://github.com/benconley/gildedRose

ASSUMPTIONS:
------------
A couple items were not entirely clear from the instructions, so I made some assumptions and am listing them here:
	* Aged brie increases in quality and has a "sell-by" date, but is not explicitly identified as perishable like the concert tickets. It is assumed that brie continues to increase in quality, even beyond the "sell-by" date, up to a maximum of 50.
	* It is indicated that items degrade twice as fast after their "sell-by" date. It is assumed that brie increases quality, but it is not explicitly indicated that brie increases twice as fast after the "sell-by" date. Brie will age at a standard rate in this exercise.

UNIT TESTS:
------------
* Pretty thorough test suite contained within: gildedRose/php/test/gilded_rose_unittests.php
* EXAMPLE:
	Ben@Ben-Notebook /cygdrive/c/xampp/htdocs/gildedRose/php/test
	$ phpunit gilded_rose_unittests.php
	PHPUnit 3.7.21 by Sebastian Bergmann.

	.....

	Time: 0 seconds, Memory: 2.00Mb

	OK (5 tests, 54 assertions)


SIMPLE TEST FIXTURE WITH OUTPUT:
--------------------------------
* Object array contained within: gildedRose/php/src/texttest_fixture.php
* EXAMPLE:
	Ben@Ben-Notebook /cygdrive/c/xampp/htdocs/gildedRose/php/src
	$ php ./texttest_fixture.php
	-------- day 0 --------
	name, sellIn, quality
	+5 Dexterity Vest, 10, 20
	+5 Dexterity Vest, 2, 30
	Aged Brie, 2, 0
	Elixir of the Mongoose, 5, 7
	Sulfuras, Hand of Ragnaros, 0, 80
	Sulfuras, Hand of Ragnaros, -1, 80
	Backstage passes to a TAFKAL80ETC concert, 15, 20
	Backstage passes to a TAFKAL80ETC concert, 10, 49
	Backstage passes to a TAFKAL80ETC concert, 3, 49
	Conjured Mana Cake, 3, 6

	-------- day 1 --------
	name, sellIn, quality
	+5 Dexterity Vest, 9, 19
	+5 Dexterity Vest, 1, 29
	Aged Brie, 1, 1
	Elixir of the Mongoose, 4, 6
	Sulfuras, Hand of Ragnaros, 0, 80
	Sulfuras, Hand of Ragnaros, 0, 80
	Backstage passes to a TAFKAL80ETC concert, 14, 21
	Backstage passes to a TAFKAL80ETC concert, 9, 50
	Backstage passes to a TAFKAL80ETC concert, 2, 50
	Conjured Mana Cake, 2, 4
