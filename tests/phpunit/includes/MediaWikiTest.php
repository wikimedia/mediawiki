<?php

class MediaWikiTest extends MediaWikiTestCase {
	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgServer' => 'http://example.org',
			'wgScriptPath' => '/w',
			'wgScript' => '/w/index.php',
			'wgArticlePath' => '/wiki/$1',
			'wgActionPaths' => [],
		] );
	}

	public static function provideTryNormaliseRedirect() {
		return [
			[
				// View: Canonical
				'url' => 'http://example.org/wiki/Foo_Bar',
				'query' => [],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// View: Escaped title
				'url' => 'http://example.org/wiki/Foo%20Bar',
				'query' => [],
				'title' => 'Foo_Bar',
				'redirect' => 'http://example.org/wiki/Foo_Bar',
			],
			[
				// View: Script path
				'url' => 'http://example.org/w/index.php?title=Foo_Bar',
				'query' => [ 'title' => 'Foo_Bar' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// View: Script path with implicit title from page id
				'url' => 'http://example.org/w/index.php?curid=123',
				'query' => [ 'curid' => '123' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// View: Script path with implicit title from revision id
				'url' => 'http://example.org/w/index.php?oldid=123',
				'query' => [ 'oldid' => '123' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// View: Script path without title
				'url' => 'http://example.org/w/index.php',
				'query' => [],
				'title' => 'Main_Page',
				'redirect' => 'http://example.org/wiki/Main_Page',
			],
			[
				// View: Script path with empty title
				'url' => 'http://example.org/w/index.php?title=',
				'query' => [ 'title' => '' ],
				'title' => 'Main_Page',
				'redirect' => 'http://example.org/wiki/Main_Page',
			],
			[
				// View: Index with escaped title
				'url' => 'http://example.org/w/index.php?title=Foo%20Bar',
				'query' => [ 'title' => 'Foo Bar' ],
				'title' => 'Foo_Bar',
				'redirect' => 'http://example.org/wiki/Foo_Bar',
			],
			[
				// View: Script path with escaped title
				'url' => 'http://example.org/w/?title=Foo_Bar',
				'query' => [ 'title' => 'Foo_Bar' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// View: Root path with escaped title
				'url' => 'http://example.org/?title=Foo_Bar',
				'query' => [ 'title' => 'Foo_Bar' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// View: Canonical with redundant query
				'url' => 'http://example.org/wiki/Foo_Bar?action=view',
				'query' => [ 'action' => 'view' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// Edit: Canonical view url with action query
				'url' => 'http://example.org/wiki/Foo_Bar?action=edit',
				'query' => [ 'action' => 'edit' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// View: Index with action query
				'url' => 'http://example.org/w/index.php?title=Foo_Bar&action=view',
				'query' => [ 'title' => 'Foo_Bar', 'action' => 'view' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// Edit: Index with action query
				'url' => 'http://example.org/w/index.php?title=Foo_Bar&action=edit',
				'query' => [ 'title' => 'Foo_Bar', 'action' => 'edit' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
		];
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
