<?php

class MediaWikiTest extends MediaWikiTestCase {
	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgServer' => 'http://example.org',
			'wgScriptPath' => '/w',
			'wgScript' => '/w/index.php',
			'wgArticlePath' => '/wiki/$1',
			'wgActionPaths' => array(),
		) );
	}

	public static function provideTryNormaliseRedirect() {
		return array(
			array(
				// View: Canonical
				'url' => 'http://example.org/wiki/Foo_Bar',
				'query' => array(),
				'title' => 'Foo_Bar',
				'redirect' => false,
			),
			array(
				// View: Escaped title
				'url' => 'http://example.org/wiki/Foo%20Bar',
				'query' => array(),
				'title' => 'Foo_Bar',
				'redirect' => 'http://example.org/wiki/Foo_Bar',
			),
			array(
				// View: Script path
				'url' => 'http://example.org/w/index.php?title=Foo_Bar',
				'query' => array( 'title' => 'Foo_Bar' ),
				'title' => 'Foo_Bar',
				'redirect' => 'http://example.org/wiki/Foo_Bar',
			),
			array(
				// View: Script path with implicit title from page id
				'url' => 'http://example.org/w/index.php?curid=123',
				'query' => array( 'curid' => '123' ),
				'title' => 'Foo_Bar',
				'redirect' => false,
			),
			array(
				// View: Script path with implicit title from revision id
				'url' => 'http://example.org/w/index.php?oldid=123',
				'query' => array( 'oldid' => '123' ),
				'title' => 'Foo_Bar',
				'redirect' => false,
			),
			array(
				// View: Script path without title
				'url' => 'http://example.org/w/index.php',
				'query' => array(),
				'title' => 'Main_Page',
				'redirect' => 'http://example.org/wiki/Main_Page',
			),
			array(
				// View: Script path with empty title
				'url' => 'http://example.org/w/index.php?title=',
				'query' => array( 'title' => '' ),
				'title' => 'Main_Page',
				'redirect' => 'http://example.org/wiki/Main_Page',
			),
			array(
				// View: Index with escaped title
				'url' => 'http://example.org/w/index.php?title=Foo%20Bar',
				'query' => array( 'title' => 'Foo Bar' ),
				'title' => 'Foo_Bar',
				'redirect' => 'http://example.org/wiki/Foo_Bar',
			),
			array(
				// View: Script path with escaped title
				'url' => 'http://example.org/w/?title=Foo_Bar',
				'query' => array( 'title' => 'Foo_Bar' ),
				'title' => 'Foo_Bar',
				'redirect' => 'http://example.org/wiki/Foo_Bar',
			),
			array(
				// View: Root path with escaped title
				'url' => 'http://example.org/?title=Foo_Bar',
				'query' => array( 'title' => 'Foo_Bar' ),
				'title' => 'Foo_Bar',
				'redirect' => 'http://example.org/wiki/Foo_Bar',
			),
			array(
				// View: Canonical with redundant query
				'url' => 'http://example.org/wiki/Foo_Bar?action=view',
				'query' => array( 'action' => 'view' ),
				'title' => 'Foo_Bar',
				'redirect' => 'http://example.org/wiki/Foo_Bar',
			),
			array(
				// Edit: Canonical view url with action query
				'url' => 'http://example.org/wiki/Foo_Bar?action=edit',
				'query' => array( 'action' => 'edit' ),
				'title' => 'Foo_Bar',
				'redirect' => false,
			),
			array(
				// View: Index with action query
				'url' => 'http://example.org/w/index.php?title=Foo_Bar&action=view',
				'query' => array( 'title' => 'Foo_Bar', 'action' => 'view' ),
				'title' => 'Foo_Bar',
				'redirect' => 'http://example.org/wiki/Foo_Bar',
			),
			array(
				// Edit: Index with action query
				'url' => 'http://example.org/w/index.php?title=Foo_Bar&action=edit',
				'query' => array( 'title' => 'Foo_Bar', 'action' => 'edit' ),
				'title' => 'Foo_Bar',
				'redirect' => false,
			),
		);
	}

	/**
	 * @dataProvider provideTryNormaliseRedirect
	 * @covers MediaWiki::tryNormaliseRedirect
	 */
	public function testTryNormaliseRedirect( $url, $query, $title, $expectedRedirect = false ) {
		// Set SERVER because interpolateTitle() doesn't use getRequestURL(),
		// whereas tryNormaliseRedirect does().
		$_SERVER['REQUEST_URI'] = $url;

		$req = new FauxRequest( $query );
		$req->setRequestURL( $url );
		// This adds a virtual 'title' query parameter. Normally called from Setup.php
		$req->interpolateTitle();

		$titleObj = Title::newFromText( $title );

		// Set global context since some involved code paths don't yet have context
		$context = RequestContext::getMain();
		$context->setRequest( $req );
		$context->setTitle( $titleObj );

		$mw = new MediaWiki( $context );

		$method = new ReflectionMethod( $mw, 'tryNormaliseRedirect' );
		$method->setAccessible( true );
		$ret = $method->invoke( $mw, $titleObj );

		$this->assertEquals(
			$expectedRedirect !== false,
			$ret,
			'Return true only when redirecting'
		);

		$this->assertEquals(
			$expectedRedirect ?: '',
			$context->getOutput()->getRedirect()
		);
	}
}
