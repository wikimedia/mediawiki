<?php

use MediaWiki\Cache\LinkCache;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use Wikimedia\HtmlArmor\HtmlArmor;

/**
 * @covers \MediaWiki\Linker\LinkRenderer
 */
class LinkRendererTest extends MediaWikiLangTestCase {
	use LinkCacheTestTrait;
	use MockTitleTrait;

	/**
	 * @var LinkRendererFactory
	 */
	private $factory;

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValues( [
			MainConfigNames::Server => '//example.org',
			MainConfigNames::CanonicalServer => 'http://example.org',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
		] );
		$this->factory = $this->getServiceContainer()->getLinkRendererFactory();
	}

	public static function provideMergeAttribs() {
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
			'<a href="/wiki/Special:BlankPage" class="foobar new" '
			. 'title="Special:BlankPage (page does not exist)" bar="baz">'
			. 'Special:BlankPage</a>',
			$link
		);
	}

	public static function provideMakeKnownLink() {
		yield [ new TitleValue( NS_MAIN, 'Foobar' ) ];
		yield [ new PageReferenceValue( NS_MAIN, 'Foobar', PageReference::LOCAL ) ];
	}

	/**
	 * @dataProvider provideMakeKnownLink
	 * @covers \MediaWiki\Linker\LinkRenderer::makeKnownLink
	 */
	public function testMakeKnownLink( $target ) {
		$linkCache = $this->createMock( LinkCache::class );
		$linkCache->method( 'addLinkObj' )->willReturn( 42 );
		$this->setService( 'LinkCache', $linkCache );
		$linkRenderer = $this->getServiceContainer()->getLinkRendererFactory()->create();

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

	public static function provideMakeBrokenLink() {
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
				'<a href="/w/index.php?title=Foobar&amp;action=edit&amp;redlink=1" class="new" '
				. 'title="Foobar (page does not exist)">Foobar</a>',
				$linkRenderer->makeBrokenLink( $target->createFragmentTarget( 'foobar' ) )
			);
		}
	}

	public static function provideMakeLink() {
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

	public static function provideMakeRedirectHeader() {
		return [
			[
				[
					'title' => 'Main_Page',
				],
				'<div class="redirectMsg"><p>Redirect to:</p><ul class="redirectText"><li><a class="new" title="Main Page (page does not exist)">Main Page</a></li></ul></div><link rel="mw:PageProp/redirect">'
			],
			[
				[
					'title' => 'Redirect',
					'redirect' => true,
				],
				'<div class="redirectMsg"><p>Redirect to:</p><ul class="redirectText"><li><a class="new" title="Redirect (page does not exist)">Redirect</a></li></ul></div><link rel="mw:PageProp/redirect">'
			],
			// Test 'addLinkTag' => false
			[
				[
					'title' => 'Redirect',
					'redirect' => true,
					'addLinkTag' => false,
				],
				'<div class="redirectMsg"><p>Redirect to:</p><ul class="redirectText"><li><a class="new" title="Redirect (page does not exist)">Redirect</a></li></ul></div>'
			],
			// Test "forceKnown"; change namespace to NS_SPECIAL so we don't
			// have to mock the LinkCache.
			[
				[
					'title' => 'Main_Page',
					'namespace' => NS_SPECIAL,
					'forceKnown' => true,
				],
				'<div class="redirectMsg"><p>Redirect to:</p><ul class="redirectText"><li><a title="Special:Main Page">Special:Main Page</a></li></ul></div><link rel="mw:PageProp/redirect">',
			],
			[
				[
					'title' => 'Redirect',
					'namespace' => NS_SPECIAL,
					'redirect' => true,
					'forceKnown' => true,
				],
				'<div class="redirectMsg"><p>Redirect to:</p><ul class="redirectText"><li><a href="/w/index.php?title=Special:Redirect&amp;redirect=no" title="Special:Redirect">Special:Redirect</a></li></ul></div><link rel="mw:PageProp/redirect">',
			],
		];
	}

	/**
	 * @dataProvider provideMakeRedirectHeader
	 * @covers \MediaWiki\Linker\LinkRenderer::makeRedirectHeader
	 */
	public function testMakeRedirectHeader( $test, $expected ) {
		$lang = $this->getServiceContainer()->getContentLanguage();
		$target = $this->makeMockTitle( $test['title'], $test );
		$forceKnown = $test['forceKnown'] ?? false;
		$addLinkTag = $test['addLinkTag'] ?? true;

		$linkRenderer = $this->factory->create();
		$this->assertEquals(
			$expected,
			$linkRenderer->makeRedirectHeader( $lang, $target, $forceKnown, $addLinkTag )
		);
	}

	public static function provideGetLinkClasses() {
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
		$services = $this->getServiceContainer();
		$titleFormatter = $services->getTitleFormatter();
		$specialPageFactory = $services->getSpecialPageFactory();
		$hookContainer = $services->getHookContainer();
		$linkCache = $services->getLinkCache();
		if ( $foobarTitle instanceof PageReference ) {
			$cacheTitle = Title::newFromPageReference( $foobarTitle );
		} else {
			$cacheTitle = $foobarTitle;
		}
		$this->addGoodLinkObject( 1, $cacheTitle, 10, 0 );
		if ( $redirectTitle instanceof PageReference ) {
			$cacheTitle = Title::newFromPageReference( $redirectTitle );
		} else {
			$cacheTitle = $redirectTitle;
		}
		$this->addGoodLinkObject( 2, $cacheTitle, 10, 1 );

		if ( $userTitle instanceof PageReference ) {
			$cacheTitle = Title::newFromPageReference( $userTitle );
		} else {
			$cacheTitle = $userTitle;
		}
		$this->addGoodLinkObject( 3, $cacheTitle, 10, 0 );

		$linkRenderer = new LinkRenderer(
			$titleFormatter,
			$linkCache,
			$specialPageFactory,
			$hookContainer,
			new ServiceOptions( LinkRenderer::CONSTRUCTOR_OPTIONS, [ 'renderForComment' => false ] )
		);
		$this->assertSame(
			'',
			$linkRenderer->getLinkClasses( $foobarTitle )
		);
		$this->assertEquals(
			'mw-redirect',
			$linkRenderer->getLinkClasses( $redirectTitle )
		);
	}
}
