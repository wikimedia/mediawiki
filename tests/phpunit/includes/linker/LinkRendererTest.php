<?php

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\MediaWikiServices;

/**
 * @covers MediaWiki\Linker\LinkRenderer
 */
class LinkRendererTest extends MediaWikiLangTestCase {

	/**
	 * @var LinkRendererFactory
	 */
	private $factory;

	public function setUp() {
		parent::setUp();
		$this->setMwGlobals( [
			'wgArticlePath' => '/wiki/$1',
			'wgServer' => '//example.org',
			'wgCanonicalServer' => 'http://example.org',
			'wgScriptPath' => '/w',
			'wgScript' => '/w/index.php',
		] );
		$this->factory = MediaWikiServices::getInstance()->getLinkRendererFactory();
	}

	public function testMergeAttribs() {
		$target = new TitleValue( NS_SPECIAL, 'Blankpage' );
		$linkRenderer = $this->factory->create();
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

	public function testMakeKnownLink() {
		$target = new TitleValue( NS_MAIN, 'Foobar' );
		$linkRenderer = $this->factory->create();

		// Query added
		$this->assertEquals(
			'<a href="/w/index.php?title=Foobar&amp;foo=bar" title="Foobar">Foobar</a>',
			$linkRenderer->makeKnownLink( $target, null, [], [ 'foo' => 'bar' ] )
		);

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
		$linkRenderer = $this->factory->create();

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

	public function testMakeLink() {
		$linkRenderer = $this->factory->create();
		$foobar = new TitleValue( NS_SPECIAL, 'Foobar' );
		$blankpage = new TitleValue( NS_SPECIAL, 'Blankpage' );
		$this->assertEquals(
			'<a href="/wiki/Special:Foobar" class="new" title="Special:Foobar '
			. '(page does not exist)">foo</a>',
			$linkRenderer->makeLink( $foobar, 'foo' )
		);

		$this->assertEquals(
			'<a href="/wiki/Special:BlankPage" title="Special:BlankPage">blank</a>',
			$linkRenderer->makeLink( $blankpage, 'blank' )
		);

		$this->assertEquals(
			'<a href="/wiki/Special:Foobar" class="new" title="Special:Foobar '
			. '(page does not exist)">&lt;script&gt;evil()&lt;/script&gt;</a>',
			$linkRenderer->makeLink( $foobar, '<script>evil()</script>' )
		);

		$this->assertEquals(
			'<a href="/wiki/Special:Foobar" class="new" title="Special:Foobar '
			. '(page does not exist)"><script>evil()</script></a>',
			$linkRenderer->makeLink( $foobar, new HtmlArmor( '<script>evil()</script>' ) )
		);

		$this->assertEquals(
			'<a href="#fragment">fragment</a>',
			$linkRenderer->makeLink( Title::newFromText( '#fragment' ) )
		);
	}

	public function testGetLinkClasses() {
		$wanCache = ObjectCache::getMainWANInstance();
		$titleFormatter = MediaWikiServices::getInstance()->getTitleFormatter();
		$linkCache = new LinkCache( $titleFormatter, $wanCache );
		$foobarTitle = new TitleValue( NS_MAIN, 'FooBar' );
		$redirectTitle = new TitleValue( NS_MAIN, 'Redirect' );
		$userTitle = new TitleValue( NS_USER, 'Someuser' );
		$linkCache->addGoodLinkObj(
			1, // id
			$foobarTitle,
			10, // len
			0 // redir
		);
		$linkCache->addGoodLinkObj(
			2, // id
			$redirectTitle,
			10, // len
			1 // redir
		);

		$linkCache->addGoodLinkObj(
			3, // id
			$userTitle,
			10, // len
			0 // redir
		);

		$linkRenderer = new LinkRenderer( $titleFormatter, $linkCache );
		$linkRenderer->setStubThreshold( 0 );
		$this->assertEquals(
			'',
			$linkRenderer->getLinkClasses( $foobarTitle )
		);

		$linkRenderer->setStubThreshold( 20 );
		$this->assertEquals(
			'stub',
			$linkRenderer->getLinkClasses( $foobarTitle )
		);

		$linkRenderer->setStubThreshold( 0 );
		$this->assertEquals(
			'mw-redirect',
			$linkRenderer->getLinkClasses( $redirectTitle )
		);

		$linkRenderer->setStubThreshold( 20 );
		$this->assertEquals(
			'',
			$linkRenderer->getLinkClasses( $userTitle )
		);
	}

	function tearDown() {
		Title::clearCaches();
		parent::tearDown();
	}
}
