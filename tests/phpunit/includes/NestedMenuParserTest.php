<?php

/**
 * Changing the visibility of methods to be able to test them.
 */
class TestableNestedMenuParser extends NestedMenuParser {
	public function parseLines( array $lines, array $maxChildrenAtLevel = array() ) {
		parent::parseLines( $lines, $maxChildrenAtLevel );
	}

	public function parseOneLine( $line ) {
		parent::parseOneLine( $line );
	}
}


/**
 * @covers NestedMenuParser
 * @group Database
 */
class NestedMenuParserTest extends MediaWikiTestCase {
	public function testParseOneLine() {
		$service = new TestableNestedMenuParser();

		$this->assertEquals(
			$service->parseOneLine( '** Yet another child|This text differs from the page name' ),
			array(
				'original' => 'Yet another child',
				'text' => 'This text differs from the page name',
				'href' => '/trunk/index.php/Yet_another_child'
			)
		);

		$this->assertEquals(
			$service->parseOneLine( '** wikipedia:Commandos (series)|The Commandos series' ),
			array(
				'original' => 'wikipedia:Commandos (series)',
				'text' => 'The Commandos series',
				'href' => 'http://en.wikipedia.org/wiki/Commandos_(series)'
			)
		);

		$this->assertEquals(
			$service->parseOneLine( '** Pokémon' ),
			array(
				'original' => 'Pokémon',
				'text' => 'Pokémon',
				'href' => '/trunk/index.php/Pok%C3%A9mon'
			)
		);
	}

	public function testParseLines() {
		$service = new TestableNestedMenuParser();

		$message = array(
			'* Computers',
			'** Laptops|Those smaller ones',
			'** Desktops|Those bigger ones',
			'** Mobile phones|Phones',

			'* Games',
			"** Super Mario|It's-a-me, Mario!",
			'** Pokémon',
			'** wikipedia:Commandos (series)|The Commandos series',

			'* TV series',
			'** 24',
			'*** 24/Season 1|Season 1',
			'*** 24:Live Another Day|Live Another Day',
			'** House',
			'*** List of House characters|Characters',
			'** Without a Trace',
		);
		$this->assertEquals(
			$service->parseLines( $message, array( 10, 10, 10, 10, 10, 10 ) ),
			array(
				array(
					'children' => array( 1, 5, 9 )
				),
				array(
					'original' => 'Computers',
					'text' => 'Computers',
					'href' => '/trunk/index.php/Computers',
					'parentIndex' => 0,
					'children' => array( 2, 3, 4 )
				),
				array(
					'original' => 'Laptops',
					'text' => 'Those smaller ones',
					'href' => '/trunk/index.php/Laptops',
					'parentIndex' => 1,
					'depth' => 2
				),
				array(
					'original' => 'Desktops',
					'text' => 'Those bigger ones',
					'href' => '/trunk/index.php/Desktops',
					'parentIndex' => 1,
					'depth' => 2
				),
				array(
					'original' => 'Mobile phones',
					'text' => 'Phones',
					'href' => '/trunk/index.php/Mobile_phones',
					'parentIndex' => 1,
					'depth' => 2
				),
				array(
					'original' => 'Games',
					'text' => 'Games',
					'href' => '/trunk/index.php/Games',
					'parentIndex' => 0,
					'depth' => 1,
					'children' => array( 6, 7, 8 )
				),
				array(
					'original' => 'Super Mario',
					'text' => "It's-a-me, Mario!",
					'href' => '/trunk/index.php/Super_Mario',
					'parentIndex' => 5,
					'depth' => 2
				),
				array(
					'original' => 'Pokémon',
					'text' => 'Pokémon',
					'href' => '/trunk/index.php/Pok%C3%A9mon',
					'parentIndex' => 5,
					'depth' => 2
				),
				array(
					'original' => 'wikipedia:Commandos (series)',
					'text' => 'The Commandos series',
					'href' => 'http://en.wikipedia.org/wiki/Commandos_(series)',
					'parentIndex' => 5,
					'depth' => 2
				),
				array(
					'original' => 'TV series',
					'text' => 'TV series',
					'href' => '/trunk/index.php/TV_series',
					'parentIndex' => 0,
					'depth' => 1,
					'children' => array( 10, 13, 15 ),
				),
				array(
					'original' => '24',
					'text' => '24',
					'href' => '/trunk/index.php/24',
					'parentIndex' => 9,
					'depth' => 2,
					'children' => array( 11, 12 )
				),
				array(
					'original' => '24/Season 1',
					'text' => 'Season 1',
					'href' => '/trunk/index.php/24/Season_1',
					'parentIndex' => 10,
					'depth' => 3
				),
				array(
					'original' => '24:Live Another Day',
					'text' => 'Live Another Day',
					'href' => '/trunk/index.php/24:Live_Another_Day',
					'parentIndex' => 10,
					'depth' => 3
				),
				array(
					'original' => 'House',
					'text' => 'House',
					'href' => '/trunk/index.php/House',
					'parentIndex' => 9,
					'depth' => 2,
					'children' => array( 14 ),
				),
				array(
					'original' => 'List of House characters',
					'text' => 'Characters',
					'href' => '/trunk/index.php/List_of_House_characters',
					'parentIndex' => 13,
					'depth' => 3
				),
				array(
					'original' => 'Without a Trace',
					'text' => 'Without a Trace',
					'href' => '/trunk/index.php/Without_a_Trace',
					'parentIndex' => 9,
					'depth' => 2
				)
			)
		);
	}

	/**
	 * parseMessage() basically just returns the output given by parseLines(),
	 * but whatever.
	 */
	public function testParseMessage() {
		$service = new TestableNestedMenuParser();

		$message = <<<MESS
* Computers
** Laptops|Those smaller ones
** Desktops|Those bigger ones
** Mobile phones|Phones

* Games
** Super Mario|It's-a-me, Mario!
** Pokémon
** wikipedia:Commandos (series)|The Commandos series

* TV series
** 24
*** 24/Season 1|Season 1
*** 24:Live Another Day|Live Another Day
** House
*** List of House characters|Characters
** Without a Trace
MESS;
		$this->assertEquals(
			$service->parseMessage( $message, array( 10, 10, 10, 10, 10, 10 ) ),
			array(
				array(
					'children' => array( 1, 5, 9 )
				),
				array(
					'original' => 'Computers',
					'text' => 'Computers',
					'href' => '/trunk/index.php/Computers',
					'parentIndex' => 0,
					'children' => array( 2, 3, 4 )
				),
				array(
					'original' => 'Laptops',
					'text' => 'Those smaller ones',
					'href' => '/trunk/index.php/Laptops',
					'parentIndex' => 1,
					'depth' => 2
				),
				array(
					'original' => 'Desktops',
					'text' => 'Those bigger ones',
					'href' => '/trunk/index.php/Desktops',
					'parentIndex' => 1,
					'depth' => 2
				),
				array(
					'original' => 'Mobile phones',
					'text' => 'Phones',
					'href' => '/trunk/index.php/Mobile_phones',
					'parentIndex' => 1,
					'depth' => 2
				),
				array(
					'original' => 'Games',
					'text' => 'Games',
					'href' => '/trunk/index.php/Games',
					'parentIndex' => 0,
					'depth' => 1,
					'children' => array( 6, 7, 8 )
				),
				array(
					'original' => 'Super Mario',
					'text' => "It's-a-me, Mario!",
					'href' => '/trunk/index.php/Super_Mario',
					'parentIndex' => 5,
					'depth' => 2
				),
				array(
					'original' => 'Pokémon',
					'text' => 'Pokémon',
					'href' => '/trunk/index.php/Pok%C3%A9mon',
					'parentIndex' => 5,
					'depth' => 2
				),
				array(
					'original' => 'wikipedia:Commandos (series)',
					'text' => 'The Commandos series',
					'href' => 'http://en.wikipedia.org/wiki/Commandos_(series)',
					'parentIndex' => 5,
					'depth' => 2
				),
				array(
					'original' => 'TV series',
					'text' => 'TV series',
					'href' => '/trunk/index.php/TV_series',
					'parentIndex' => 0,
					'depth' => 1,
					'children' => array( 10, 13, 15 ),
				),
				array(
					'original' => '24',
					'text' => '24',
					'href' => '/trunk/index.php/24',
					'parentIndex' => 9,
					'depth' => 2,
					'children' => array( 11, 12 )
				),
				array(
					'original' => '24/Season 1',
					'text' => 'Season 1',
					'href' => '/trunk/index.php/24/Season_1',
					'parentIndex' => 10,
					'depth' => 3
				),
				array(
					'original' => '24:Live Another Day',
					'text' => 'Live Another Day',
					'href' => '/trunk/index.php/24:Live_Another_Day',
					'parentIndex' => 10,
					'depth' => 3
				),
				array(
					'original' => 'House',
					'text' => 'House',
					'href' => '/trunk/index.php/House',
					'parentIndex' => 9,
					'depth' => 2,
					'children' => array( 14 ),
				),
				array(
					'original' => 'List of House characters',
					'text' => 'Characters',
					'href' => '/trunk/index.php/List_of_House_characters',
					'parentIndex' => 13,
					'depth' => 3
				),
				array(
					'original' => 'Without a Trace',
					'text' => 'Without a Trace',
					'href' => '/trunk/index.php/Without_a_Trace',
					'parentIndex' => 9,
					'depth' => 2
				)
			)
		);
	}
}
