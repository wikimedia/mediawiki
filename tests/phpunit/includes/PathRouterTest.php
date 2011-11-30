<?php
/**
 * Tests for the PathRouter parsing
 */

class PathRouterTest extends MediaWikiTestCase {

	/**
	 * Test basic path parsing
	 */
	public function testBasic() {
		$router = new PathRouter;
		$router->add("/$1");
		$matches = $router->parse( "/Foo" );
		$this->assertEquals( $matches, array( 'title' => "Foo" ) );
	}

	/**
	 * Test loose path auto-$1
	 */
	public function testLoose() {
		$router = new PathRouter;
		$router->add("/"); # Should be the same as "/$1"
		$matches = $router->parse( "/Foo" );
		$this->assertEquals( $matches, array( 'title' => "Foo" ) );

		$router = new PathRouter;
		$router->add("/wiki"); # Should be the same as /wiki/$1
		$matches = $router->parse( "/wiki/Foo" );
		$this->assertEquals( $matches, array( 'title' => "Foo" ) );

		$router = new PathRouter;
		$router->add("/wiki/"); # Should be the same as /wiki/$1
		$matches = $router->parse( "/wiki/Foo" );
		$this->assertEquals( $matches, array( 'title' => "Foo" ) );
	}

	/**
	 * Test to ensure that path is based on specifity, not order
	 */
	public function testOrder() {
		$router = new PathRouter;
		$router->add("/$1");
		$router->add("/a/$1");
		$router->add("/b/$1");
		$matches = $router->parse( "/a/Foo" );
		$this->assertEquals( $matches, array( 'title' => "Foo" ) );

		$router = new PathRouter;
		$router->add("/b/$1");
		$router->add("/a/$1");
		$router->add("/$1");
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
		$matches = $router->parse( '/Foo');
		$this->assertEquals( $matches, array(
			'title' => "Foo",
			'x' => 'Foo',
			'a' => 'b',
			'foo' => 'bar'
		) );
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

		foreach( array(
			"/Foo" => array( 'title' => "Foo" ),
			"/Bar" => array( 'ping' => 'pong' ),
			"/Baz" => array( 'marco' => 'polo' ),
			"/asdf-foo" => array( 'title' => "qwerty-foo" ),
			"/qwerty-bar" => array( 'title' => "asdf-bar" ),
			"/a/Foo" => array( 'title' => "Foo" ),
			"/asdf/Foo" => array( 'title' => "Foo" ),
			"/qwerty/Foo" => array( 'title' => "Foo", 'qwerty' => 'qwerty' ),
			"/baz/Foo" => array( 'title' => "Foo", 'unrestricted' => 'baz' ),
			"/y/Foo" => array( 'title' => "Foo", 'restricted-to-y' => 'y' ),
		) as $path => $result ) {
			$this->assertEquals( $router->parse( $path ), $result );
		}
	}

}
