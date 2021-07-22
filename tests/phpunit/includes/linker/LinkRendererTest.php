<?php

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;

/**
 * @covers MediaWiki\Linker\LinkRenderer
 */
class LinkRendererTest extends MediaWikiLangTestCase {

	/**
	 * @var LinkRendererFactory
	 */
	private $factory;

	protected function setUp(): void {
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

	public function provideMergeAttribs() {
		yield [ new TitleValue( NS_SPECIAL, 'BlankPage' ) ];
		yield [ new PageReferenceValue( NS_SPECIAL, 'BlankPage', PageReference::LOCAL ) ];
	}

	/**
	 * @dataProvider provideMergeAttribs
	 * @covers \MediaWiki\Linker\LinkRenderer::makeBrokenLink
	 */
	public function testMergeAttribs( $target ) {
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

	public function provideMakeKnownLink() {
		yield [ new TitleValue( NS_MAIN, 'Foobar' ) ];
		yield [ new PageReferenceValue( NS_MAIN, 'Foobar', PageReference::LOCAL ) ];
	}

	/**
	 * @dataProvider provideMakeKnownLink
	 * @covers \MediaWiki\Linker\LinkRenderer::makeKnownLink
	 */
	public function testMakeKnownLink( $target ) {
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

	public function provideMakeBrokenLink() {
		yield [
			new TitleValue( NS_MAIN, 'Foobar' ),
			new TitleValue( NS_SPECIAL, 'Foobar' )
		];
		yield [
			new PageReferenceValue( NS_MAIN, 'Foobar', PageReference::LOCAL ),
			new PageReferenceValue( NS_SPECIAL, 'Foobar', PageReference::LOCAL )
		];
	}

	/**
	 * @dataProvider provideMakeBrokenLink
	 * @covers \MediaWiki\Linker\LinkRenderer::makeBrokenLink
	 */
	public function testMakeBrokenLink( $target, $special ) {
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
		if ( $target instanceof LinkTarget ) {
			$this->assertEquals(
				'<a href="/w/index.php?title=Foobar&amp;action=foobar" class="new" '
				. 'title="Foobar (page does not exist)">Foobar</a>',
				$linkRenderer->makeBrokenLink( $target->createFragmentTarget( 'foobar' ) )
			);
		}
	}

	public function provideMakeLink() {
		yield [
			new TitleValue( NS_SPECIAL, 'Foobar' ),
			new TitleValue( NS_SPECIAL, 'BlankPage' )
		];
		yield [
			new PageReferenceValue( NS_SPECIAL, 'Foobar', PageReference::LOCAL ),
			new PageReferenceValue( NS_SPECIAL, 'BlankPage', PageReference::LOCAL )
		];
	}

	/**
	 * @dataProvider provideMakeLink
	 * @covers \MediaWiki\Linker\LinkRenderer::makeLink
	 */
	public function testMakeLink( $foobar, $blankpage ) {
		$linkRenderer = $this->factory->create();
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

	public function provideGetLinkClasses() {
		yield [
			new TitleValue( NS_MAIN, 'FooBar' ),
			new TitleValue( NS_MAIN, 'Redirect' ),
			new TitleValue( NS_USER, 'Someuser' )
		];
		yield [
			new PageReferenceValue( NS_MAIN, 'FooBar', PageReference::LOCAL ),
			new PageReferenceValue( NS_MAIN, 'Redirect', PageReference::LOCAL ),
			new PageReferenceValue( NS_USER, 'Someuser', PageReference::LOCAL )
		];
	}

	/**
	 * @dataProvider provideGetLinkClasses
	 * @covers \MediaWiki\Linker\LinkRenderer::getLinkClasses
	 */
	public function testGetLinkClasses( $foobarTitle, $redirectTitle, $userTitle ) {
		$services = MediaWikiServices::getInstance();
		$wanCache = $services->getMainWANObjectCache();
		$titleFormatter = $services->getTitleFormatter();
		$nsInfo = $services->getNamespaceInfo();
		$specialPageFactory = $services->getSpecialPageFactory();
		$hookContainer = $services->getHookContainer();
		$loadBalancer = $services->getDBLoadBalancer();
		$linkCache = new LinkCache( $titleFormatter, $wanCache, $nsInfo, $loadBalancer );
		if ( $foobarTitle instanceof PageReference ) {
			$cacheTitle = Title::castFromPageReference( $foobarTitle );
		} else {
			$cacheTitle = $foobarTitle;
		}
		$linkCache->addGoodLinkObj(
			1, // id
			$cacheTitle,
			10, // len
			0 // redir
		);
		if ( $redirectTitle instanceof PageReference ) {
			$cacheTitle = Title::castFromPageReference( $redirectTitle );
		} else {
			$cacheTitle = $redirectTitle;
		}
		$linkCache->addGoodLinkObj(
			2, // id
			$cacheTitle,
			10, // len
			1 // redir
		);

		if ( $userTitle instanceof PageReference ) {
			$cacheTitle = Title::castFromPageReference( $userTitle );
		} else {
			$cacheTitle = $userTitle;
		}
		$linkCache->addGoodLinkObj(
			3, // id
			$cacheTitle,
			10, // len
			0 // redir
		);

		$linkRenderer = new LinkRenderer( $titleFormatter, $linkCache,
			$nsInfo, $specialPageFactory, $hookContainer );
		$linkRenderer->setStubThreshold( 0 );
		$this->assertSame(
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
		$this->assertSame(
			'',
			$linkRenderer->getLinkClasses( $userTitle )
		);
	}

	protected function tearDown(): void {
		Title::clearCaches();
		parent::tearDown();
	}
}
