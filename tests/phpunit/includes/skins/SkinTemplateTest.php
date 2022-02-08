<?php

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
					'wgFooterIcons' => [],
				],
				[],
				'Empty list'
			],
			// Test case 2
			[
				[
					'wgFooterIcons' => [
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
					'wgFooterIcons' => [
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
					'wgFooterIcons' => [
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
		$this->setMwGlobals( $globals );
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

	public function provideGetSectionsData(): array {
		// byteoffset and fromtitle are redacted from this test.
		$SECTION_1 = [
			'toclevel' => 1,
			'line' => 'Section 1',
			'anchor' => 'section_1',
		];
		$SECTION_1_1 = [
			'toclevel' => 2,
			'line' => 'Section 1.1',
			'anchor' => 'section_1_1',
		];
		$SECTION_1_2 = [
			'toclevel' => 2,
			'line' => 'Section 1.2',
			'anchor' => 'section_1_2',
		];
		$SECTION_1_2_1 = [
			'toclevel' => 3,
			'line' => 'Section 1.2.1',
			'anchor' => 'section_1_2_1',
		];
		$SECTION_1_3 = [
			'toclevel' => 2,
			'line' => 'Section 1.3',
			'anchor' => 'section_1_3',
		];
		$SECTION_2 = [
			'toclevel' => 1,
			'line' => 'Section 2',
			'anchor' => 'section_2',
		];

		return [
			[
				// sections data
				[],
				[]
			],
			[
				[
					$SECTION_1,
					$SECTION_2
				],
				[
					$SECTION_1 + [
						'array-sections' => [],
						],
					$SECTION_2 + [
						'array-sections' => [],
						]
				]
			],
			[
				[
					$SECTION_1,
					$SECTION_1_1,
					$SECTION_2,
				],
				[
					$SECTION_1 + [
						'array-sections' => [
							$SECTION_1_1 + [
								'array-sections' => [],
							]
						]
					],
					$SECTION_2 + [
						'array-sections' => [],
					]
				]
			],
			[
				[
					$SECTION_1,
					$SECTION_1_1,
					$SECTION_1_2,
					$SECTION_1_2_1,
					$SECTION_1_3,
					$SECTION_2,
				],
				[
					$SECTION_1 + [
						'array-sections' => [
							$SECTION_1_1 + [
								'array-sections' => [],
							],
							$SECTION_1_2 + [
								'array-sections' => [
									$SECTION_1_2_1 + [
										'array-sections' => [],
									]
								]
							],
							$SECTION_1_3 + [
								'array-sections' => [],
							]
						]
					],
					$SECTION_2 + [
						'array-sections' => [],
					]
				]
			]
		];
	}

	/**
	 * @covers Skin::getSectionsData
	 * @dataProvider provideGetSectionsData
	 *
	 * @param array $sectionsData
	 * @param array $expected
	 */
	public function testGetSectionsData( $sectionsData, $expected ) {
		$skin = new SkinTemplate();
		$context = new DerivativeContext( $skin->getContext() );
		$mock = $this->createMock( OutputPage::class );
		$mock->expects( $this->any() )
			->method( 'getSections' )
			->willReturn( $sectionsData );

		$reflectionMethod = new ReflectionMethod( Skin::class, 'getSectionsData' );
		$reflectionMethod->setAccessible( true );

		$context->setOutput( $mock );
		$skin->setContext( $context );
		$data = $reflectionMethod->invoke(
			$skin
		);
		$this->assertEquals( $expected, $data );
	}

	public function provideGetTOCData() {
		return [
			[
				false,
				null,
				'Data not provided when TOC is disabled'
			],
			[
				true,
				[
					'array-sections' => []
				],
				'Data not provided when TOC is enabled'
			],
		];
	}

	/**
	 * @covers Skin::getTOCData
	 * @dataProvider provideGetTOCData
	 */
	public function testGetTOCData( $isTOCEnabled, $expected ) {
		$skin = new SkinTemplate();
		$context = new DerivativeContext( $skin->getContext() );
		$mock = $this->createMock( OutputPage::class );
		$mock->expects( $this->any() )
			->method( 'isTOCEnabled' )
			->willReturn( $isTOCEnabled );

		$reflectionMethod = new ReflectionMethod( Skin::class, 'getTOCData' );
		$reflectionMethod->setAccessible( true );

		$context->setOutput( $mock );
		$skin->setContext( $context );
		$data = $reflectionMethod->invoke(
			$skin
		);
		$this->assertEquals( $expected, $data );
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
