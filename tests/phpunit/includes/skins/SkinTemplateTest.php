<?php

use MediaWiki\MainConfigNames;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers SkinTemplate
 *
 * @group Output
 *
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

	public function makeListItemProvider() {
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

	/**
	 * @param bool $isSyndicated
	 * @param string $html
	 * @return OutputPage
	 */
	private function getMockOutputPage( $isSyndicated, $html ) {
		$mock = $this->createMock( OutputPage::class );
		$mock->expects( $this->once() )
			->method( 'isSyndicated' )
			->willReturn( $isSyndicated );
		$mock->method( 'getHTML' )
			->willReturn( $html );
		return $mock;
	}

	public function provideGetDefaultModules() {
		return [
			[
				false,
				'',
				[]
			],
			[
				true,
				'',
				[ 'mediawiki.feedlink' ]
			],
			[
				false,
				'FOO mw-ui-button BAR',
				[ 'mediawiki.ui.button' ]
			],
			[
				true,
				'FOO mw-ui-button BAR',
				[ 'mediawiki.ui.button', 'mediawiki.feedlink' ]
			],
		];
	}

	public function provideGetFooterIcons() {
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
	 * @covers SkinTemplate::getFooterIcons
	 * @dataProvider provideGetFooterIcons
	 */
	public function testGetFooterIcons( $globals, $expected, $msg ) {
		$this->overrideConfigValues( $globals );
		$wrapper = TestingAccessWrapper::newFromObject( new SkinTemplate() );
		$icons = $wrapper->getFooterIcons();

		$this->assertEquals( $expected, $icons, $msg );
	}

	/**
	 * @covers Skin::getDefaultModules
	 * @dataProvider provideGetDefaultModules
	 */
	public function testgetDefaultModules( $isSyndicated, $html, array $expectedModuleStyles ) {
		$skin = new SkinTemplate();

		$context = new DerivativeContext( $skin->getContext() );
		$context->setOutput( $this->getMockOutputPage( $isSyndicated, $html ) );
		$skin->setContext( $context );

		$modules = $skin->getDefaultModules();

		$actualStylesModule = array_merge( ...array_values( $modules['styles'] ) );
		foreach ( $expectedModuleStyles as $expected ) {
			$this->assertContains( $expected, $actualStylesModule );
		}
	}

	/**
	 * @covers SkinTemplate::injectLegacyMenusIntoPersonalTools
	 * @dataProvider provideContentNavigation
	 *
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

	public function provideContentNavigation(): array {
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

}
