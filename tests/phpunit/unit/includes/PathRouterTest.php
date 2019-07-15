<?php

/**
 * Tests for the PathRouter parsing.
 *
 * @covers PathRouter
 */
class PathRouterTest extends MediaWikiUnitTestCase {

	/**
	 * @var PathRouter
	 */
	protected $basicRouter;

	protected function setUp() {
		parent::setUp();
		$router = new PathRouter;
		$router->add( "/wiki/$1" );
		$this->basicRouter = $router;
	}

	public static function provideParse() {
		$tests = [
			// Basic path parsing
			'Basic path parsing' => [
				"/wiki/$1",
				"/wiki/Foo",
				[ 'title' => "Foo" ]
			],
			//
			'Loose path auto-$1: /$1' => [
				"/",
				"/Foo",
				[ 'title' => "Foo" ]
			],
			'Loose path auto-$1: /wiki' => [
				"/wiki",
				"/wiki/Foo",
				[ 'title' => "Foo" ]
			],
			'Loose path auto-$1: /wiki/' => [
				"/wiki/",
				"/wiki/Foo",
				[ 'title' => "Foo" ]
			],
			// Ensure that path is based on specificity, not order
			'Order, /$1 added first' => [
				[ "/$1", "/a/$1", "/b/$1" ],
				"/a/Foo",
				[ 'title' => "Foo" ]
			],
			'Order, /$1 added last' => [
				[ "/b/$1", "/a/$1", "/$1" ],
				"/a/Foo",
				[ 'title' => "Foo" ]
			],
			// Handling of key based arrays with a url parameter
			'Key based array' => [
				[ [
					'path' => [ 'edit' => "/edit/$1" ],
					'params' => [ 'action' => '$key' ],
				] ],
				"/edit/Foo",
				[ 'title' => "Foo", 'action' => 'edit' ]
			],
			// Additional parameter
			'Basic $2' => [
				[ [
					'path' => '/$2/$1',
					'params' => [ 'test' => '$2' ]
				] ],
				"/asdf/Foo",
				[ 'title' => "Foo", 'test' => 'asdf' ]
			],
		];
		// Shared patterns for restricted value parameter tests
		$restrictedPatterns = [
			[
				'path' => '/$2/$1',
				'params' => [ 'test' => '$2' ],
				'options' => [ '$2' => [ 'a', 'b' ] ]
			],
			[
				'path' => '/$2/$1',
				'params' => [ 'test2' => '$2' ],
				'options' => [ '$2' => 'c' ]
			],
			'/$1'
		];
		$tests += [
			// Restricted value parameter tests
			'Restricted 1' => [
				$restrictedPatterns,
				"/asdf/Foo",
				[ 'title' => "asdf/Foo" ]
			],
			'Restricted 2' => [
				$restrictedPatterns,
				"/a/Foo",
				[ 'title' => "Foo", 'test' => 'a' ]
			],
			'Restricted 3' => [
				$restrictedPatterns,
				"/c/Foo",
				[ 'title' => "Foo", 'test2' => 'c' ]
			],

			// Callback test
			'Callback' => [
				[ [
					'path' => "/$1",
					'params' => [ 'a' => 'b', 'data:foo' => 'bar' ],
					'options' => [ 'callback' => [ __CLASS__, 'callbackForTest' ] ]
				] ],
				'/Foo',
				[
					'title' => "Foo",
					'x' => 'Foo',
					'a' => 'b',
					'foo' => 'bar'
				]
			],

			// Test to ensure that matches are not made if a parameter expects nonexistent input
			'Fail' => [
				[ [
					'path' => "/wiki/$1",
					'params' => [ 'title' => "$1$2" ],
				] ],
				"/wiki/A",
				[]
			],

			// Make sure the router handles titles like Special:Recentchanges correctly
			'Special title' => [
				"/wiki/$1",
				"/wiki/Special:Recentchanges",
				[ 'title' => "Special:Recentchanges" ]
			],

			// Make sure the router decodes urlencoding properly
			'URL encoding' => [
				"/wiki/$1",
				"/wiki/Title_With%20Space",
				[ 'title' => "Title_With Space" ]
			],

			// Double slash and dot expansion
			'Double slash in prefix' => [
				'/wiki/$1',
				'//wiki/Foo',
				[ 'title' => 'Foo' ]
			],
			'Double slash at start of $1' => [
				'/wiki/$1',
				'/wiki//Foo',
				[ 'title' => '/Foo' ]
			],
			'Double slash in middle of $1' => [
				'/wiki/$1',
				'/wiki/.hack//SIGN',
				[ 'title' => '.hack//SIGN' ]
			],
			'Dots removed 1' => [
				'/wiki/$1',
				'/x/../wiki/Foo',
				[ 'title' => 'Foo' ]
			],
			'Dots removed 2' => [
				'/wiki/$1',
				'/./wiki/Foo',
				[ 'title' => 'Foo' ]
			],
			'Dots retained 1' => [
				'/wiki/$1',
				'/wiki/../wiki/Foo',
				[ 'title' => '../wiki/Foo' ]
			],
			'Dots retained 2' => [
				'/wiki/$1',
				'/wiki/./Foo',
				[ 'title' => './Foo' ]
			],
			'Triple slash' => [
				'/wiki/$1',
				'///wiki/Foo',
				[ 'title' => 'Foo' ]
			],
			// '..' only traverses one slash, see e.g. RFC 3986
			'Dots traversing double slash 1' => [
				'/wiki/$1',
				'/a//b/../../wiki/Foo',
				[]
			],
			'Dots traversing double slash 2' => [
				'/wiki/$1',
				'/a//b/../../../wiki/Foo',
				[ 'title' => 'Foo' ]
			],
		];

		// Make sure the router doesn't break on special characters like $ used in regexp replacements
		foreach ( [ "$", "$1", "\\", "\\$1" ] as $char ) {
			$tests["Regexp character $char"] = [
				"/wiki/$1",
				"/wiki/$char",
				[ 'title' => "$char" ]
			];
		}

		$tests += [
			// Make sure the router handles characters like +&() properly
			"Special characters" => [
				"/wiki/$1",
				"/wiki/Plus+And&Dollar\\Stuff();[]{}*",
				[ 'title' => "Plus+And&Dollar\\Stuff();[]{}*" ],
			],

			// Make sure the router handles unicode characters correctly
			"Unicode 1" => [
				"/wiki/$1",
				"/wiki/Spécial:Modifications_récentes" ,
				[ 'title' => "Spécial:Modifications_récentes" ],
			],

			"Unicode 2" => [
				"/wiki/$1",
				"/wiki/Sp%C3%A9cial:Modifications_r%C3%A9centes",
				[ 'title' => "Spécial:Modifications_récentes" ],
			]
		];

		// Ensure the router doesn't choke on long paths.
		$lorem = "Lorem_ipsum_dolor_sit_amet,_consectetur_adipisicing_elit,_sed_do_eiusmod_" .
			"tempor_incididunt_ut_labore_et_dolore_magna_aliqua._Ut_enim_ad_minim_veniam,_quis_" .
			 "nostrud_exercitation_ullamco_laboris_nisi_ut_aliquip_ex_ea_commodo_consequat._" .
			 "Duis_aute_irure_dolor_in_reprehenderit_in_voluptate_velit_esse_cillum_dolore_" .
			 "eu_fugiat_nulla_pariatur._Excepteur_sint_occaecat_cupidatat_non_proident,_sunt_" .
			 "in_culpa_qui_officia_deserunt_mollit_anim_id_est_laborum.";

		$tests += [
			"Long path" => [
				"/wiki/$1",
				"/wiki/$lorem",
				[ 'title' => $lorem ]
			],

			// Ensure that the php passed site of parameter values are not urldecoded
			"Pattern urlencoding" => [
				[ [ 'path' => "/wiki/$1", 'params' => [ 'title' => '%20:$1' ] ] ],
				"/wiki/Foo",
				[ 'title' => '%20:Foo' ]
			],

			// Ensure that raw parameter values do not have any variable replacements or urldecoding
			"Raw param value" => [
				[ [ 'path' => "/wiki/$1", 'params' => [ 'title' => [ 'value' => 'bar%20$1' ] ] ] ],
				"/wiki/Foo",
				[ 'title' => 'bar%20$1' ]
			]
		];

		return $tests;
	}

	/**
	 * Test path parsing
	 * @dataProvider provideParse
	 */
	public function testParse( $patterns, $path, $expected ) {
		$patterns = (array)$patterns;

		$router = new PathRouter;
		foreach ( $patterns as $pattern ) {
			if ( is_array( $pattern ) ) {
				$router->add( $pattern['path'], $pattern['params'] ?? [],
					$pattern['options'] ?? [] );
			} else {
				$router->add( $pattern );
			}
		}
		$matches = $router->parse( $path );
		$this->assertEquals( $matches, $expected );
	}

	public static function callbackForTest( &$matches, $data ) {
		$matches['x'] = $data['$1'];
		$matches['foo'] = $data['foo'];
	}

	public static function provideWeight() {
		return [
			[ '/Foo', [ 'title' => 'Foo' ] ],
			[ '/Bar', [ 'ping' => 'pong' ] ],
			[ '/Baz', [ 'marco' => 'polo' ] ],
			[ '/asdf-foo', [ 'title' => 'qwerty-foo' ] ],
			[ '/qwerty-bar', [ 'title' => 'asdf-bar' ] ],
			[ '/a/Foo', [ 'title' => 'Foo' ] ],
			[ '/asdf/Foo', [ 'title' => 'Foo' ] ],
			[ '/qwerty/Foo', [ 'title' => 'Foo', 'qwerty' => 'qwerty' ] ],
			[ '/baz/Foo', [ 'title' => 'Foo', 'unrestricted' => 'baz' ] ],
			[ '/y/Foo', [ 'title' => 'Foo', 'restricted-to-y' => 'y' ] ],
		];
	}

	/**
	 * Test to ensure weight of paths is handled correctly
	 * @dataProvider provideWeight
	 */
	public function testWeight( $path, $expected ) {
		$router = new PathRouter;
		$router->addStrict( "/Bar", [ 'ping' => 'pong' ] );
		$router->add( "/asdf-$1", [ 'title' => 'qwerty-$1' ] );
		$router->add( "/$1" );
		$router->add( "/qwerty-$1", [ 'title' => 'asdf-$1' ] );
		$router->addStrict( "/Baz", [ 'marco' => 'polo' ] );
		$router->add( "/a/$1" );
		$router->add( "/asdf/$1" );
		$router->add( "/$2/$1", [ 'unrestricted' => '$2' ] );
		$router->add( [ 'qwerty' => "/qwerty/$1" ], [ 'qwerty' => '$key' ] );
		$router->add( "/$2/$1", [ 'restricted-to-y' => '$2' ], [ '$2' => 'y' ] );

		$this->assertEquals( $router->parse( $path ), $expected );
	}
}
