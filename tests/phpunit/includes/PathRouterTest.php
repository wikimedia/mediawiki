<?php

/**
 * Tests for the PathRouter parsing.
 *
 * @covers PathRouter
 */
class PathRouterTest extends MediaWikiTestCase {

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

	/**
	 * Test basic path parsing
	 */
	public function testBasic() {
		$matches = $this->basicRouter->parse( "/wiki/Foo" );
		$this->assertEquals( $matches, array( 'title' => "Foo" ) );
	}

	/**
	 * Test loose path auto-$1
	 */
	public function testLoose() {
		$router = new PathRouter;
		$router->add( "/" ); # Should be the same as "/$1"
		$matches = $router->parse( "/Foo" );
		$this->assertEquals( $matches, array( 'title' => "Foo" ) );

		$router = new PathRouter;
		$router->add( "/wiki" ); # Should be the same as /wiki/$1
		$matches = $router->parse( "/wiki/Foo" );
		$this->assertEquals( $matches, array( 'title' => "Foo" ) );

		$router = new PathRouter;
		$router->add( "/wiki/" ); # Should be the same as /wiki/$1
		$matches = $router->parse( "/wiki/Foo" );
		$this->assertEquals( $matches, array( 'title' => "Foo" ) );
	}

	/**
	 * Test to ensure that path is based on specifity, not order
	 */
	public function testOrder() {
		$router = new PathRouter;
		$router->add( "/$1" );
		$router->add( "/a/$1" );
		$router->add( "/b/$1" );
		$matches = $router->parse( "/a/Foo" );
		$this->assertEquals( $matches, array( 'title' => "Foo" ) );

		$router = new PathRouter;
		$router->add( "/b/$1" );
		$router->add( "/a/$1" );
		$router->add( "/$1" );
		$matches = $router->parse( "/a/Foo" );
		$this->assertEquals( $matches, array( 'title' => "Foo" ) );
	}

	/**
	 * Test the handling of key based arrays with a url parameter
	 */
	public function testKeyParameter() {
		$router = new PathRouter;
		$router->add( array( 'edit' => "/edit/$1" ), array( 'action' => '$key' ) );
		$matches = $router->parse( "/edit/Foo" );
		$this->assertEquals( $matches, array( 'title' => "Foo", 'action' => 'edit' ) );
	}

	/**
	 * Test the handling of $2 inside paths
	 */
	public function testAdditionalParameter() {
		// Basic $2
		$router = new PathRouter;
		$router->add( '/$2/$1', array( 'test' => '$2' ) );
		$matches = $router->parse( "/asdf/Foo" );
		$this->assertEquals( $matches, array( 'title' => "Foo", 'test' => 'asdf' ) );
	}

	/**
	 * Test additional restricted value parameter
	 */
	public function testRestrictedValue() {
		$router = new PathRouter;
		$router->add( '/$2/$1',
			array( 'test' => '$2' ),
			array( '$2' => array( 'a', 'b' ) )
		);
		$router->add( '/$2/$1',
			array( 'test2' => '$2' ),
			array( '$2' => 'c' )
		);
		$router->add( '/$1' );

		$matches = $router->parse( "/asdf/Foo" );
		$this->assertEquals( $matches, array( 'title' => "asdf/Foo" ) );

		$matches = $router->parse( "/a/Foo" );
		$this->assertEquals( $matches, array( 'title' => "Foo", 'test' => 'a' ) );

		$matches = $router->parse( "/c/Foo" );
		$this->assertEquals( $matches, array( 'title' => "Foo", 'test2' => 'c' ) );
	}

	public function callbackForTest( &$matches, $data ) {
		$matches['x'] = $data['$1'];
		$matches['foo'] = $data['foo'];
	}

	public function testCallback() {
		$router = new PathRouter;
		$router->add( "/$1",
			array( 'a' => 'b', 'data:foo' => 'bar' ),
			array( 'callback' => array( $this, 'callbackForTest' ) )
		);
		$matches = $router->parse( '/Foo' );
		$this->assertEquals( $matches, array(
			'title' => "Foo",
			'x' => 'Foo',
			'a' => 'b',
			'foo' => 'bar'
		) );
	}

	/**
	 * Test to ensure that matches are not made if a parameter expects nonexistent input
	 */
	public function testFail() {
		$router = new PathRouter;
		$router->add( "/wiki/$1", array( 'title' => "$1$2" ) );
		$matches = $router->parse( "/wiki/A" );
		$this->assertEquals( array(), $matches );
	}

	/**
	 * Test to ensure weight of paths is handled correctly
	 */
	public function testWeight() {
		$router = new PathRouter;
		$router->addStrict( "/Bar", array( 'ping' => 'pong' ) );
		$router->add( "/asdf-$1", array( 'title' => 'qwerty-$1' ) );
		$router->add( "/$1" );
		$router->add( "/qwerty-$1", array( 'title' => 'asdf-$1' ) );
		$router->addStrict( "/Baz", array( 'marco' => 'polo' ) );
		$router->add( "/a/$1" );
		$router->add( "/asdf/$1" );
		$router->add( "/$2/$1", array( 'unrestricted' => '$2' ) );
		$router->add( array( 'qwerty' => "/qwerty/$1" ), array( 'qwerty' => '$key' ) );
		$router->add( "/$2/$1", array( 'restricted-to-y' => '$2' ), array( '$2' => 'y' ) );

		foreach (
			array(
				'/Foo' => array( 'title' => 'Foo' ),
				'/Bar' => array( 'ping' => 'pong' ),
				'/Baz' => array( 'marco' => 'polo' ),
				'/asdf-foo' => array( 'title' => 'qwerty-foo' ),
				'/qwerty-bar' => array( 'title' => 'asdf-bar' ),
				'/a/Foo' => array( 'title' => 'Foo' ),
				'/asdf/Foo' => array( 'title' => 'Foo' ),
				'/qwerty/Foo' => array( 'title' => 'Foo', 'qwerty' => 'qwerty' ),
				'/baz/Foo' => array( 'title' => 'Foo', 'unrestricted' => 'baz' ),
				'/y/Foo' => array( 'title' => 'Foo', 'restricted-to-y' => 'y' ),
			) as $path => $result
		) {
			$this->assertEquals( $router->parse( $path ), $result );
		}
	}

	/**
	 * Make sure the router handles titles like Special:Recentchanges correctly
	 */
	public function testSpecial() {
		$matches = $this->basicRouter->parse( "/wiki/Special:Recentchanges" );
		$this->assertEquals( $matches, array( 'title' => "Special:Recentchanges" ) );
	}

	/**
	 * Make sure the router decodes urlencoding properly
	 */
	public function testUrlencoding() {
		$matches = $this->basicRouter->parse( "/wiki/Title_With%20Space" );
		$this->assertEquals( $matches, array( 'title' => "Title_With Space" ) );
	}

	public static function provideRegexpChars() {
		return array(
			array( "$" ),
			array( "$1" ),
			array( "\\" ),
			array( "\\$1" ),
		);
	}

	/**
	 * Make sure the router doesn't break on special characters like $ used in regexp replacements
	 * @dataProvider provideRegexpChars
	 */
	public function testRegexpChars( $char ) {
		$matches = $this->basicRouter->parse( "/wiki/$char" );
		$this->assertEquals( $matches, array( 'title' => "$char" ) );
	}

	/**
	 * Make sure the router handles characters like +&() properly
	 */
	public function testCharacters() {
		$matches = $this->basicRouter->parse( "/wiki/Plus+And&Dollar\\Stuff();[]{}*" );
		$this->assertEquals( $matches, array( 'title' => "Plus+And&Dollar\\Stuff();[]{}*" ) );
	}

	/**
	 * Make sure the router handles unicode characters correctly
	 * @depends testSpecial
	 * @depends testUrlencoding
	 * @depends testCharacters
	 */
	public function testUnicode() {
		$matches = $this->basicRouter->parse( "/wiki/Spécial:Modifications_récentes" );
		$this->assertEquals( $matches, array( 'title' => "Spécial:Modifications_récentes" ) );

		$matches = $this->basicRouter->parse( "/wiki/Sp%C3%A9cial:Modifications_r%C3%A9centes" );
		$this->assertEquals( $matches, array( 'title' => "Spécial:Modifications_récentes" ) );
	}

	/**
	 * Ensure the router doesn't choke on long paths.
	 */
	public function testLength() {
		$matches = $this->basicRouter->parse( "/wiki/Lorem_ipsum_dolor_sit_amet,_consectetur_adipisicing_elit,_sed_do_eiusmod_tempor_incididunt_ut_labore_et_dolore_magna_aliqua._Ut_enim_ad_minim_veniam,_quis_nostrud_exercitation_ullamco_laboris_nisi_ut_aliquip_ex_ea_commodo_consequat._Duis_aute_irure_dolor_in_reprehenderit_in_voluptate_velit_esse_cillum_dolore_eu_fugiat_nulla_pariatur._Excepteur_sint_occaecat_cupidatat_non_proident,_sunt_in_culpa_qui_officia_deserunt_mollit_anim_id_est_laborum." );
		$this->assertEquals( $matches, array( 'title' => "Lorem_ipsum_dolor_sit_amet,_consectetur_adipisicing_elit,_sed_do_eiusmod_tempor_incididunt_ut_labore_et_dolore_magna_aliqua._Ut_enim_ad_minim_veniam,_quis_nostrud_exercitation_ullamco_laboris_nisi_ut_aliquip_ex_ea_commodo_consequat._Duis_aute_irure_dolor_in_reprehenderit_in_voluptate_velit_esse_cillum_dolore_eu_fugiat_nulla_pariatur._Excepteur_sint_occaecat_cupidatat_non_proident,_sunt_in_culpa_qui_officia_deserunt_mollit_anim_id_est_laborum." ) );
	}

	/**
	 * Ensure that the php passed site of parameter values are not urldecoded
	 */
	public function testPatternUrlencoding() {
		$router = new PathRouter;
		$router->add( "/wiki/$1", array( 'title' => '%20:$1' ) );
		$matches = $router->parse( "/wiki/Foo" );
		$this->assertEquals( $matches, array( 'title' => '%20:Foo' ) );
	}

	/**
	 * Ensure that raw parameter values do not have any variable replacements or urldecoding
	 */
	public function testRawParamValue() {
		$router = new PathRouter;
		$router->add( "/wiki/$1", array( 'title' => array( 'value' => 'bar%20$1' ) ) );
		$matches = $router->parse( "/wiki/Foo" );
		$this->assertEquals( $matches, array( 'title' => 'bar%20$1' ) );
	}
}
