<?php

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
								'srcset' => '/w/resources/assets/poweredby_mediawiki_132x47.png 1.5x,' .
									' /w/resources/assets/poweredby_mediawiki_176x62.png 2x',
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
							'srcset' => '/w/resources/assets/poweredby_mediawiki_132x47.png 1.5x,' .
								' /w/resources/assets/poweredby_mediawiki_176x62.png 2x',
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
			new SkinTemplate( [ 'template' => 'SkinQuickTemplateTest', 'name' => 'test' ] )
		);

		$wrapper->getContext()->setTitle( Title::makeTitle( NS_MAIN, 'PrepareQuickTemplateTest' ) );
		$tpl = $wrapper->prepareQuickTemplate();
		$contentNav = $tpl->get( 'content_navigation' );

		$this->assertEquals( [ 'dock-bottom', 'namespaces', 'views', 'actions', 'variants' ], array_keys( $contentNav ) );
	}
}
