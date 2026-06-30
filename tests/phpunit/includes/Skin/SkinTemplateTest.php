<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Skin\BaseTemplate;
use MediaWiki\Skin\QuickTemplate;
use MediaWiki\Skin\SkinFallback;
use MediaWiki\Skin\SkinTemplate;
use MediaWiki\Title\Title;
use Wikimedia\TestingAccessWrapper;

// phpcs:ignore MediaWiki.Files.ClassMatchesFilename.NotMatch
class SkinQuickTemplateTest extends QuickTemplate {
	public function execute() {
	}
}

/**
 * @covers \MediaWiki\Skin\SkinTemplate
 * @covers \MediaWiki\Skin\Skin
 * @covers \MediaWiki\Skin\Components\SkinComponentFooter
 * @group Skin
 * @group Database
 * @author Bene* < benestar.wikimedia@gmail.com >
 */
class SkinTemplateTest extends MediaWikiIntegrationTestCase {
	/**
	 * @dataProvider makeListItemProvider
	 */
	public function testMakeListItem( $expected, $key, array $item, array $options, $message ) {
		$template = $this->getMockForAbstractClass( BaseTemplate::class );
		$template->set( 'skin', new SkinFallback( [
			'name' => 'fallback',
			'templateDirectory' => __DIR__,
		] ) );

		$this->assertEquals(
			$expected,
			$template->makeListItem( $key, $item, $options ),
			$message
		);
	}

	public static function makeListItemProvider() {
		return [
			[
				'<li class="class mw-list-item" title="itemtitle"><a href="url" title="title">text</a></li>',
				'',
				[
					'class' => 'class',
					'itemtitle' => 'itemtitle',
					'href' => 'url',
					'title' => 'title',
					'text' => 'text'
				],
				[],
				'Test makeListItem with normal values'
			]
		];
	}

	public static function provideGetFooterIcons() {
		return [
			// Test case 1
			[
				[
					MainConfigNames::FooterIcons => [],
				],
				[],
				'Empty list'
			],
			// Test case 2
			[
				[
					MainConfigNames::FooterIcons => [
						'poweredby' => [
							'mediawiki' => [
								'src' => '/w/resources/assets/poweredby_mediawiki_88x31.png',
								'url' => 'https://www.mediawiki.org/',
								'alt' => 'Powered by MediaWiki',
								'srcset' => '/w/resources/assets/poweredby_mediawiki_176x62.png 2x',
							]
						]
					],
				],
				[
					'poweredby' => [
						[
							'src' => '/w/resources/assets/poweredby_mediawiki_88x31.png',
							'url' => 'https://www.mediawiki.org/',
							'alt' => 'Powered by MediaWiki',
							'srcset' => '/w/resources/assets/poweredby_mediawiki_176x62.png 2x',
							'width' => 88,
							'height' => 31,
						]
					]
				],
				'Width and height are hardcoded if not provided'
			],
			// Test case 3
			[
				[
					MainConfigNames::FooterIcons => [
						'copyright' => [
							'copyright' => [],
						],
					],
				],
				[],
				'Empty arrays are filtered out'
			],
			// Test case 4
			[
				[
					MainConfigNames::FooterIcons => [
						'copyright' => [
							'copyright' => [
								'alt' => 'Wikimedia Foundation',
								'url' => 'https://wikimediafoundation.org'
							],
						],
					],
				],
				[],
				'Icons with no icon are filtered out'
			]
		];
	}

	/**
	 * @dataProvider provideGetFooterIcons
	 */
	public function testGetFooterIcons( $globals, $expected, $msg ) {
		$this->overrideConfigValues( $globals );
		$wrapper = TestingAccessWrapper::newFromObject( new SkinTemplate() );
		$icons = $wrapper->getFooterIcons();

		$this->assertEquals( $expected, $icons, $msg );
	}

	/**
	 * @dataProvider provideContentNavigation
	 * @param array $contentNavigation
	 * @param array $expected
	 */
	public function testInjectLegacyMenusIntoPersonalTools(
		array $contentNavigation,
		array $expected
	) {
		$wrapper = TestingAccessWrapper::newFromObject( new SkinTemplate() );

		$this->hideDeprecated( 'MediaWiki\Skin\SkinTemplate::injectLegacyMenusIntoPersonalTools' );
		$this->assertEquals(
			$expected,
			$wrapper->injectLegacyMenusIntoPersonalTools( $contentNavigation )
		);
	}

	public static function provideContentNavigation(): array {
		return [
			'No userpage set' => [
				'contentNavigation' => [
					'notifications' => [
						'notification 1' => []
					],
					'user-menu' => [
						'item 1' => [],
						'item 2' => [],
						'item 3' => []
					]
				],
				'expected' => [
					'item 1' => [],
					'item 2' => [],
					'item 3' => []
				]
			],
			'userpage set, no notifications' => [
				'contentNavigation' => [
					'notifications' => [],
					'user-menu' => [
						'item 1' => [],
						'userpage' => [],
						'item 2' => [],
						'item 3' => []
					]
				],
				'expected' => [
					'item 1' => [],
					'userpage' => [],
					'item 2' => [],
					'item 3' => []
				]
			],
			'userpage set, notification defined' => [
				'contentNavigation' => [
					'notifications' => [
						'notification 1' => []
					],
					'user-menu' => [
						'item 1' => [],
						'userpage' => [],
						'item 2' => [],
						'item 3' => []
					]
				],
				'expected' => [
					'item 1' => [],
					'userpage' => [],
					'notification 1' => [],
					'item 2' => [],
					'item 3' => []
				]
			],
			'userpage set, notification defined, user interface preferences set' => [
				'contentNavigation' => [
					'notifications' => [
						'notification 1' => []
					],
					'user-menu' => [
						'item 1' => [],
						'userpage' => [],
						'item 2' => [],
						'item 3' => []
					],
					'user-interface-preferences' => [
						'uls' => [],
					],
				],
				'expected' => [
					'uls' => [],
					'item 1' => [],
					'userpage' => [],
					'notification 1' => [],
					'item 2' => [],
					'item 3' => []
				]
			],
			'no userpage, no notifications, no user-interface-preferences' => [
				'contentNavigation' => [
					'user-menu' => [
						'item 1' => [],
						'item 2' => [],
						'item 3' => []
					],
				],
				'expected' => [
					'item 1' => [],
					'item 2' => [],
					'item 3' => []
				]
			]
		];
	}

	public function testGenerateHTML() {
		$wrapper = TestingAccessWrapper::newFromObject(
			new SkinTemplate( [
				'menus' => [ 'actions', 'associated-pages', 'dock-bottom', 'variants', 'views' ],
				'template' => 'SkinQuickTemplateTest',
				'name' => 'test'
			] )
		);

		$wrapper->getContext()->setTitle( Title::makeTitle( NS_MAIN, 'PrepareQuickTemplateTest' ) );
		$tpl = $wrapper->prepareQuickTemplate();
		$contentNav = $tpl->get( 'content_navigation' );

		$this->assertEqualsCanonicalizing(
			[
				'associated-pages', 'dock-bottom', 'views', 'actions', 'variants'
			],
			array_keys( $contentNav )
		);
	}

	/**
	 * Verify that footer keys exist in buildContentNavigationUrlsInternal output
	 * as they are now populated from SkinComponentFooter (T318376).
	 *
	 * @covers \MediaWiki\Skin\SkinTemplate::buildContentNavigationUrlsInternal
	 */
	public function testFooterKeysInContentNavigation() {
		$wrapper = TestingAccessWrapper::newFromObject(
			new SkinTemplate( [
				'menus' => [ 'actions', 'associated-pages', 'dock-bottom', 'variants', 'views' ],
				'template' => 'SkinQuickTemplateTest',
				'name' => 'test'
			] )
		);

		$wrapper->getContext()->setTitle( Title::makeTitle( NS_MAIN, 'FooterKeysTest' ) );
		$contentNav = $wrapper->buildContentNavigationUrlsInternal();

		$this->assertArrayHasKey( 'footer-info', $contentNav,
			'footer-info key must exist in content navigation' );
		$this->assertArrayHasKey( 'footer-places', $contentNav,
			'footer-places key must exist in content navigation' );
		$this->assertArrayHasKey( 'footer-icons', $contentNav,
			'footer-icons key must exist in content navigation' );
	}

	/**
	 * Verify that footer keys are excluded from the template's content_navigation
	 * data when the skin does not opt in via menus (T318376).
	 *
	 * @covers \MediaWiki\Skin\SkinTemplate::prepareQuickTemplate
	 */
	public function testFooterKeysExcludedFromContentNavigation() {
		$wrapper = TestingAccessWrapper::newFromObject(
			new SkinTemplate( [
				'menus' => [ 'actions', 'associated-pages', 'dock-bottom', 'variants', 'views' ],
				'template' => 'SkinQuickTemplateTest',
				'name' => 'test'
			] )
		);

		$wrapper->getContext()->setTitle( Title::makeTitle( NS_MAIN, 'FooterExclusionTest' ) );
		$tpl = $wrapper->prepareQuickTemplate();
		$contentNav = $tpl->get( 'content_navigation' );

		$this->assertArrayNotHasKey( 'footer-info', $contentNav,
			'footer-info must not appear in content_navigation when not in menus' );
		$this->assertArrayNotHasKey( 'footer-places', $contentNav,
			'footer-places must not appear in content_navigation when not in menus' );
		$this->assertArrayNotHasKey( 'footer-icons', $contentNav,
			'footer-icons must not appear in content_navigation when not in menus' );
	}

	/**
	 * Verify that items added via the SkinTemplateNavigation::Universal hook
	 * appear in the content_navigation footer keys (T318376).
	 *
	 * @covers \MediaWiki\Skin\SkinTemplate::buildContentNavigationUrlsInternal
	 */
	public function testUniversalHookFooterItemsAppear() {
		$this->setTemporaryHook( 'SkinTemplateNavigation::Universal',
			static function ( $skin, &$links ) {
				$links['footer-places']['test-item'] = [
					'id' => 'footer-places-test-item',
					'html' => '<a href="#">Test Link</a>',
				];
			}
		);

		$wrapper = TestingAccessWrapper::newFromObject(
			new SkinTemplate( [
				'menus' => [ 'actions', 'associated-pages', 'dock-bottom', 'variants', 'views' ],
				'template' => 'SkinQuickTemplateTest',
				'name' => 'test'
			] )
		);

		$wrapper->getContext()->setTitle( Title::makeTitle( NS_MAIN, 'HookIntegrationTest' ) );
		$contentNav = $wrapper->buildContentNavigationUrlsInternal();

		$this->assertArrayHasKey( 'test-item', $contentNav['footer-places'],
			'Item added via Universal hook must appear in footer-places' );
		$this->assertSame( 'footer-places-test-item',
			$contentNav['footer-places']['test-item']['id'],
			'Item id must match what was set in the hook' );
	}

	/**
	 * Verify that footer items added via the Universal hook are rendered
	 * through the portlet pipeline in template data (T318376).
	 *
	 * @covers \MediaWiki\Skin\SkinTemplate::getTemplateData
	 */
	public function testUniversalHookFooterItemsInPortlets() {
		$this->setTemporaryHook( 'SkinTemplateNavigation::Universal',
			static function ( $skin, &$links ) {
				$links['footer-places']['custom-link'] = [
					'id' => 'footer-places-custom-link',
					'html' => '<a href="#">Custom Footer</a>',
				];
			}
		);

		$skin = new SkinTemplate( [
			'menus' => [
				'actions', 'associated-pages', 'dock-bottom', 'variants', 'views',
				'footer-places',
			],
			'template' => 'SkinQuickTemplateTest',
			'name' => 'test'
		] );

		$skin->getContext()->setTitle( Title::makeTitle( NS_MAIN, 'FooterPortletTest' ) );
		$data = $skin->getTemplateData();

		$this->assertArrayHasKey( 'data-portlets', $data,
			'Template data must contain data-portlets' );
		$portlets = $data['data-portlets'];
		$this->assertArrayHasKey( 'data-footer-places', $portlets,
			'Footer places portlet must appear in data-portlets when opted in via menus' );
	}

	/**
	 * Verify that footer data in prepareQuickTemplate is read after
	 * content navigation hooks have run, so SkinAddFooterLinks items
	 * appear in the legacy footerlinks template variable (T426358).
	 *
	 * @covers \MediaWiki\Skin\SkinTemplate::prepareQuickTemplate
	 */
	public function testFooterDataReadAfterHooks() {
		$this->setTemporaryHook( 'SkinAddFooterLinks',
			static function ( $skin, $key, &$footerLinks ) {
				if ( $key === 'places' ) {
					$footerLinks['hook-link'] = '<a href="#">Hook Footer</a>';
				}
			}
		);

		$wrapper = TestingAccessWrapper::newFromObject(
			new SkinTemplate( [
				// Legacy skin: does NOT opt into footer menus
				'menus' => [ 'actions', 'associated-pages', 'dock-bottom', 'variants', 'views' ],
				'template' => 'SkinQuickTemplateTest',
				'name' => 'test'
			] )
		);

		$wrapper->getContext()->setTitle( Title::makeTitle( NS_MAIN, 'FooterOrderingTest' ) );
		$tpl = $wrapper->prepareQuickTemplate();
		$footerlinks = $tpl->get( 'footerlinks' );

		$this->assertArrayHasKey( 'places', $footerlinks,
			'footerlinks must have a places section' );
		$this->assertContains( 'hook-link', $footerlinks['places'],
			'SkinAddFooterLinks item must appear in legacy footerlinks' );
	}

	/**
	 * Verify that footer items added via the Universal hook appear in
	 * legacy skins' footerlinks template variable even when the skin
	 * has NOT opted into footer menus (T426358).
	 *
	 * @covers \MediaWiki\Skin\SkinTemplate::runOnSkinTemplateNavigationHooks
	 * @covers \MediaWiki\Skin\SkinTemplate::prepareQuickTemplate
	 */
	public function testUniversalHookFooterItemsInLegacyFooterlinks() {
		$this->setTemporaryHook( 'SkinTemplateNavigation::Universal',
			static function ( $skin, &$links ) {
				$links['footer-places']['ext-link'] = [
					'id' => 'footer-places-ext-link',
					'html' => '<a href="#">Extension Footer</a>',
				];
			}
		);

		$wrapper = TestingAccessWrapper::newFromObject(
			new SkinTemplate( [
				// Legacy skin: does NOT opt into footer menus
				'menus' => [ 'actions', 'associated-pages', 'dock-bottom', 'variants', 'views' ],
				'template' => 'SkinQuickTemplateTest',
				'name' => 'test'
			] )
		);

		$wrapper->getContext()->setTitle( Title::makeTitle( NS_MAIN, 'UniversalHookLegacyTest' ) );
		$tpl = $wrapper->prepareQuickTemplate();
		$footerlinks = $tpl->get( 'footerlinks' );

		$this->assertArrayHasKey( 'places', $footerlinks,
			'footerlinks must have a places section' );
		$this->assertContains( 'ext-link', $footerlinks['places'],
			'Universal hook footer item must appear in legacy footerlinks (T426358)' );
	}

	public function testCopyrightShownForKnownButNonExistentTitle() {
		$this->overrideConfigValues( [
			MainConfigNames::RightsPage => '',
			MainConfigNames::RightsUrl => 'https://example.com/licence',
			MainConfigNames::RightsText => 'Test Licence',
			MainConfigNames::MaxCredits => 0,
		] );

		// An always-known special page: isKnown() is true, but it has no DB row so exists() is false.
		$knownTitle = Title::makeTitle( NS_SPECIAL, 'Recentchanges' );
		$this->assertTrue( $knownTitle->isKnown(), 'precondition: special page is known' );
		$this->assertFalse( $knownTitle->exists(), 'precondition: special page does not exist in the DB' );

		$context = new RequestContext();
		$context->setTitle( $knownTitle );
		$context->getOutput()->setCopyright( true );
		$skin = $context->getSkin();
		$skin->setRelevantTitle( $knownTitle );

		$this->assertNotSame(
			'',
			$skin->getCopyright(),
			'Copyright is shown for a known but non-existent title that opts in via setCopyright()'
		);

		// A genuine redlink is neither known nor existing, so copyright stays suppressed.
		$redlinkTitle = Title::makeTitle( NS_MAIN, 'Surely no such page exists 0xDEADBEEF' );
		$this->assertFalse( $redlinkTitle->isKnown(), 'precondition: redlink is not known' );

		$redContext = new RequestContext();
		$redContext->setTitle( $redlinkTitle );
		$redContext->getOutput()->setCopyright( true );
		$redSkin = $redContext->getSkin();
		$redSkin->setRelevantTitle( $redlinkTitle );

		$this->assertSame(
			'',
			$redSkin->getCopyright(),
			'Copyright stays suppressed for a genuine redlink even when it opts in'
		);
	}
}
