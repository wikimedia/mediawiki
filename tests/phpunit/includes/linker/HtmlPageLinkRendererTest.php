<?php

use MediaWiki\Linker\HtmlPageLinkRenderer;
use MediaWiki\MediaWikiServices;

/**
 * @covers HtmlPageLinkRenderer
 */
class HtmlPageLinkRendererTest extends MediaWikiLangTestCase {

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	public function setUp() {
		parent::setUp();
		$this->setMwGlobals( [
			'wgArticlePath' => '/wiki/$1',
			'wgServer' => '//example.org',
			'wgCanonicalServer' => 'http://example.org',
			'wgScriptPath' => '/w',
			'wgScript' => '/w/index.php',
		] );
		$this->titleFormatter = MediaWikiServices::getInstance()->getTitleFormatter();
	}

	public static function provideNewFromLegacyOptions() {
		return [
			[
				[ 'noclasses' ],
				'getNoClasses',
				true
			],
			[
				[ 'forcearticlepath' ],
				'getForceArticlePath',
				true
			],
			[
				[ 'http' ],
				'getExpandURLs',
				PROTO_HTTP
			],
			[
				[ 'https' ],
				'getExpandURLs',
				PROTO_HTTPS
			],
			[
				[ 'stubThreshold' => 150 ],
				'getStubThreshold',
				150
			],
		];
	}

	/**
	 * @dataProvider provideNewFromLegacyOptions
	 */
	public function testNewFromLegacyOptions( $options, $func, $val ) {
		$linkRenderer = HtmlPageLinkRenderer::newFromLegacyOptions(
			$this->titleFormatter,
			$options
		);
		$this->assertEquals( $val, $linkRenderer->$func() );
	}

	public function testMergeAttribs() {
		$target = new TitleValue( NS_SPECIAL, 'Blankpage' );
		$linkRenderer = new HtmlPageLinkRenderer( $this->titleFormatter );
		$link = $linkRenderer->makeBrokenLink( $target, null, [
			// Appended to class
			'class' => 'foobar',
			// Suppresses href attribute
			'href' => false,
			// Extra attribute
			'bar' => 'baz'
		] );
		$this->assertEquals(
			'<a href="/wiki/Special:BlankPage" class="new foobar" '
			. 'title="Special:BlankPage (page does not exist)" bar="baz">'
			. 'Special:BlankPage</a>',
			$link
		);
	}

	public function testGetLinkURL() {
		$target = new TitleValue( NS_MAIN, 'Foobar' );
		$linkRenderer = new HtmlPageLinkRenderer( $this->titleFormatter );

		// Query added
		$this->assertEquals(
			'<a href="/w/index.php?title=Foobar&amp;foo=bar" title="Foobar">Foobar</a>',
			$linkRenderer->makeKnownLink( $target, null, [], [ 'foo' => 'bar' ] )
		);

		// forcearticlepath
		$linkRenderer->setForceArticlePath( true );
		$this->assertEquals(
			'<a href="/wiki/Foobar?foo=bar" title="Foobar">Foobar</a>',
			$linkRenderer->makeKnownLink( $target, null, [], [ 'foo' => 'bar' ] )
		);

		// expand = HTTPS
		$linkRenderer->setForceArticlePath( false );
		$linkRenderer->setExpandURLs( PROTO_HTTPS );
		$this->assertEquals(
			'<a href="https://example.org/wiki/Foobar" title="Foobar">Foobar</a>',
			$linkRenderer->makeKnownLink( $target )
		);
	}

	public function testMakeBrokenLink() {
		$target = new TitleValue( NS_MAIN, 'Foobar' );
		$special = new TitleValue( NS_SPECIAL, 'Foobar' );
		$linkRenderer = new HtmlPageLinkRenderer( $this->titleFormatter );

		// action=edit&redlink=1 added
		$this->assertEquals(
			'<a href="/w/index.php?title=Foobar&amp;action=edit&amp;redlink=1" '
			. 'class="new" title="Foobar (page does not exist)">Foobar</a>',
			$linkRenderer->makeBrokenLink( $target )
		);

		// action=edit&redlink=1 not added due to action query parameter
		$this->assertEquals(
			'<a href="/w/index.php?title=Foobar&amp;action=foobar" class="new" '
			. 'title="Foobar (page does not exist)">Foobar</a>',
			$linkRenderer->makeBrokenLink( $target, null, [], [ 'action' => 'foobar' ] )
		);

		// action=edit&redlink=1 not added due to NS_SPECIAL
		$this->assertEquals(
			'<a href="/wiki/Special:Foobar" class="new" title="Special:Foobar '
			. '(page does not exist)">Special:Foobar</a>',
			$linkRenderer->makeBrokenLink( $special )
		);

		// fragment stripped
		$this->assertEquals(
			'<a href="/w/index.php?title=Foobar&amp;action=edit&amp;redlink=1" '
			. 'class="new" title="Foobar (page does not exist)">Foobar</a>',
			$linkRenderer->makeBrokenLink( $target->createFragmentTarget( 'foobar' ) )
		);
	}
}
