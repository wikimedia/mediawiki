<?php

/**
 * Changing the visibility of methods to be able to test them.
 */
class TestableNestedMenuParser extends NestedMenuParser {
	public function parseLines( array $lines, array $maxChildrenAtLevel = array() ) {
		return parent::parseLines( $lines, $maxChildrenAtLevel );
	}

	public function parseOneLine( $line ) {
		return parent::parseOneLine( $line );
	}
}


/**
 * @covers NestedMenuParser
 * @group Database
 */
class NestedMenuParserTest extends MediaWikiTestCase {
	public static function setUpBeforeClass() {
		// Inject ParserTest well-known interwikis
		ParserTest::setupInterwikis();
	}

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgLanguageCode' => 'en',
			'wgLang' => Language::factory( 'en' ),
			'wgContLang' => Language::factory( 'en' ),
			'wgMemc' => new EmptyBagOStuff,
		) );
	}

	public static function tearDownAfterClass() {
		ParserTest::tearDownInterwikis();
		parent::tearDownAfterClass();
	}

	public function testParseOneLine() {
		$service = new TestableNestedMenuParser();

		$this->assertEquals(
			array(
				'original' => 'Yet another child',
				'text' => 'This text differs from the page name',
				'href' => '/wiki/index.php/Yet_another_child'
			),
			$service->parseOneLine( '** Yet another child|This text differs from the page name' )
		);

		$this->assertEquals(
			array(
				'original' => 'wikipedia:Commandos (series)',
				'text' => 'The Commandos series',
				'href' => 'http://en.wikipedia.org/wiki/Commandos_(series)'
			),
			$service->parseOneLine( '** wikipedia:Commandos (series)|The Commandos series' )
		);

		$this->assertEquals(
			array(
				'original' => 'Pokémon',
				'text' => 'Pokémon',
				'href' => '/wiki/index.php/Pok%C3%A9mon'
			),
			$service->parseOneLine( '** Pokémon' )
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
			array(
				array(
					'children' => array( 1, 5, 9 )
				),
				array(
					'original' => 'Computers',
					'text' => 'Computers',
					'href' => '/wiki/index.php/Computers',
					'parentIndex' => 0,
					'depth' => 1,
					'children' => array( 2, 3, 4 )
				),
				array(
					'original' => 'Laptops',
					'text' => 'Those smaller ones',
					'href' => '/wiki/index.php/Laptops',
					'parentIndex' => 1,
					'depth' => 2
				),
				array(
					'original' => 'Desktops',
					'text' => 'Those bigger ones',
					'href' => '/wiki/index.php/Desktops',
					'parentIndex' => 1,
					'depth' => 2
				),
				array(
					'original' => 'Mobile phones',
					'text' => 'Phones',
					'href' => '/wiki/index.php/Mobile_phones',
					'parentIndex' => 1,
					'depth' => 2
				),
				array(
					'original' => 'Games',
					'text' => 'Games',
					'href' => '/wiki/index.php/Games',
					'parentIndex' => 0,
					'depth' => 1,
					'children' => array( 6, 7, 8 )
				),
				array(
					'original' => 'Super Mario',
					'text' => "It's-a-me, Mario!",
					'href' => '/wiki/index.php/Super_Mario',
					'parentIndex' => 5,
					'depth' => 2
				),
				array(
					'original' => 'Pokémon',
					'text' => 'Pokémon',
					'href' => '/wiki/index.php/Pok%C3%A9mon',
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
					'href' => '/wiki/index.php/TV_series',
					'parentIndex' => 0,
					'depth' => 1,
					'children' => array( 10, 13, 15 ),
				),
				array(
					'original' => '24',
					'text' => '24',
					'href' => '/wiki/index.php/24',
					'parentIndex' => 9,
					'depth' => 2,
					'children' => array( 11, 12 )
				),
				array(
					'original' => '24/Season 1',
					'text' => 'Season 1',
					'href' => '/wiki/index.php/24/Season_1',
					'parentIndex' => 10,
					'depth' => 3
				),
				array(
					'original' => '24:Live Another Day',
					'text' => 'Live Another Day',
					'href' => '/wiki/index.php/24:Live_Another_Day',
					'parentIndex' => 10,
					'depth' => 3
				),
				array(
					'original' => 'House',
					'text' => 'House',
					'href' => '/wiki/index.php/House',
					'parentIndex' => 9,
					'depth' => 2,
					'children' => array( 14 ),
				),
				array(
					'original' => 'List of House characters',
					'text' => 'Characters',
					'href' => '/wiki/index.php/List_of_House_characters',
					'parentIndex' => 13,
					'depth' => 3
				),
				array(
					'original' => 'Without a Trace',
					'text' => 'Without a Trace',
					'href' => '/wiki/index.php/Without_a_Trace',
					'parentIndex' => 9,
					'depth' => 2
				)
			),
			$service->parseLines( $message, array( 10, 10, 10, 10, 10, 10 ) )
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
			array(
				array(
					'children' => array( 1, 5, 9 )
				),
				array(
					'original' => 'Computers',
					'text' => 'Computers',
					'href' => '/wiki/index.php/Computers',
					'parentIndex' => 0,
					'children' => array( 2, 3, 4 )
				),
				array(
					'original' => 'Laptops',
					'text' => 'Those smaller ones',
					'href' => '/wiki/index.php/Laptops',
					'parentIndex' => 1,
					'depth' => 2
				),
				array(
					'original' => 'Desktops',
					'text' => 'Those bigger ones',
					'href' => '/wiki/index.php/Desktops',
					'parentIndex' => 1,
					'depth' => 2
				),
				array(
					'original' => 'Mobile phones',
					'text' => 'Phones',
					'href' => '/wiki/index.php/Mobile_phones',
					'parentIndex' => 1,
					'depth' => 2
				),
				array(
					'original' => 'Games',
					'text' => 'Games',
					'href' => '/wiki/index.php/Games',
					'parentIndex' => 0,
					'depth' => 1,
					'children' => array( 6, 7, 8 )
				),
				array(
					'original' => 'Super Mario',
					'text' => "It's-a-me, Mario!",
					'href' => '/wiki/index.php/Super_Mario',
					'parentIndex' => 5,
					'depth' => 2
				),
				array(
					'original' => 'Pokémon',
					'text' => 'Pokémon',
					'href' => '/wiki/index.php/Pok%C3%A9mon',
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
					'href' => '/wiki/index.php/TV_series',
					'parentIndex' => 0,
					'depth' => 1,
					'children' => array( 10, 13, 15 ),
				),
				array(
					'original' => '24',
					'text' => '24',
					'href' => '/wiki/index.php/24',
					'parentIndex' => 9,
					'depth' => 2,
					'children' => array( 11, 12 )
				),
				array(
					'original' => '24/Season 1',
					'text' => 'Season 1',
					'href' => '/wiki/index.php/24/Season_1',
					'parentIndex' => 10,
					'depth' => 3
				),
				array(
					'original' => '24:Live Another Day',
					'text' => 'Live Another Day',
					'href' => '/wiki/index.php/24:Live_Another_Day',
					'parentIndex' => 10,
					'depth' => 3
				),
				array(
					'original' => 'House',
					'text' => 'House',
					'href' => '/wiki/index.php/House',
					'parentIndex' => 9,
					'depth' => 2,
					'children' => array( 14 ),
				),
				array(
					'original' => 'List of House characters',
					'text' => 'Characters',
					'href' => '/wiki/index.php/List_of_House_characters',
					'parentIndex' => 13,
					'depth' => 3
				),
				array(
					'original' => 'Without a Trace',
					'text' => 'Without a Trace',
					'href' => '/wiki/index.php/Without_a_Trace',
					'parentIndex' => 9,
					'depth' => 2
				)
			),
			$service->parseMessage(
				$message,
				array( 10, 10, 10, 10, 10, 10 ),
				60 * 60 * 3 /* 3h cache time */
			)
		);
	}
}
