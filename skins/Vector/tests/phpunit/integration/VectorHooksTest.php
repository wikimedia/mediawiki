<?php
/*
 * @file
 * @ingroup skins
 */

namespace MediaWiki\Skins\Vector\Tests\Integration;

use FauxRequest;
use HashConfig;
use MediaWiki\Skins\Vector\Constants;
use MediaWiki\Skins\Vector\FeatureManagement\Requirements\LimitedWidthContentRequirement;
use MediaWiki\Skins\Vector\Hooks;
use MediaWiki\Skins\Vector\SkinVector22;
use MediaWiki\Skins\Vector\SkinVectorLegacy;
use MediaWiki\User\UserOptionsManager;
use MediaWikiIntegrationTestCase;
use ReflectionMethod;
use RequestContext;
use ResourceLoaderContext;
use RuntimeException;
use Title;
use User;

/**
 * Integration tests for Vector Hooks.
 *
 * @group Vector
 * @coversDefaultClass \MediaWiki\Skins\Vector\Hooks
 */
class VectorHooksTest extends MediaWikiIntegrationTestCase {

	/**
	 * @param bool $excludeMainPage
	 * @param array $excludeNamespaces
	 * @param array $include
	 * @param array $querystring
	 * @return array
	 */
	private static function makeMaxWidthConfig(
		$excludeMainPage,
		$excludeNamespaces = [],
		$include = [],
		$querystring = []
	) {
		return [
			'exclude' => [
				'mainpage' => $excludeMainPage,
				'namespaces' => $excludeNamespaces,
				'querystring' => $querystring,
			],
			'include' => $include
		];
	}

	public static function provideGetActiveABTest() {
		return [
			[
				[
					'VectorWebABTestEnrollment' => [],
				],
				[]
			],
			[
				[
					'VectorWebABTestEnrollment' => [
						'name' => 'vector.sticky_header',
						'enabled' => true,
						'buckets' => [
								'unsampled' => [
										'samplingRate' => 1,
								],
								'control' => [
										'samplingRate' => 0
								],
								'stickyHeaderEnabled' => [
										'samplingRate' => 0
								],
								'stickyHeaderDisabled' => [
										'samplingRate' => 0
								],
						],
					],
				],
				[
					'name' => 'vector.sticky_header',
					'enabled' => true,
					'buckets' => [
							'unsampled' => [
									'samplingRate' => 1,
							],
							'control' => [
									'samplingRate' => 0
							],
							'stickyHeaderEnabled' => [
									'samplingRate' => 0
							],
							'stickyHeaderDisabled' => [
									'samplingRate' => 0
							],
					],
				]
			],
		];
	}

	public static function provideGetActiveABTestWithExceptions() {
		return [
			# Bad experiment (no buckets)
			[
				[
					'VectorSearchApiUrl' => 'https://en.wikipedia.org/w/rest.php',
					'VectorWebABTestEnrollment' => [
						'name' => 'vector.sticky_header',
						'enabled' => true,
					],
				]
			],
			# Bad experiment (no unsampled bucket)
			[
				[
					'VectorSearchApiUrl' => 'https://en.wikipedia.org/w/rest.php',
					'VectorWebABTestEnrollment' => [
						'name' => 'vector.sticky_header',
						'enabled' => true,
						'buckets' => [
							'a' => [
								'samplingRate' => 0
							],
						]
					],
				]
			],
			# Bad experiment (wrong format)
			[
				[
					'VectorSearchApiUrl' => 'https://en.wikipedia.org/w/rest.php',
					'VectorWebABTestEnrollment' => [
						'name' => 'vector.sticky_header',
						'enabled' => true,
						'buckets' => [
							'unsampled' => 1,
						]
					],
				]
			],
			# Bad experiment (samplingRate defined as string)
			[
				[
					'VectorSearchApiUrl' => 'https://en.wikipedia.org/w/rest.php',
					'VectorWebABTestEnrollment' => [
						'name' => 'vector.sticky_header',
						'enabled' => true,
						'buckets' => [
							'unsampled' => [
								'samplingRate' => '1'
							],
						]
					],
				]
			],
		];
	}

	/**
	 * @covers ::shouldDisableMaxWidth
	 */
	public static function providerShouldDisableMaxWidth() {
		$excludeTalkFooConfig = self::makeMaxWidthConfig(
			false,
			[ NS_TALK ],
			[ 'Talk:Foo' ],
			[]
		);

		return [
			[
				'No options, nothing disables max width',
				[],
				Title::makeTitle( NS_MAIN, 'Foo' ),
				[],
				false
			],
			[
				'Main page disables max width if exclude.mainpage set',
				self::makeMaxWidthConfig( true ),
				Title::newMainPage(),
				[],
				true
			],
			[
				'Namespaces can be excluded',
				self::makeMaxWidthConfig( false, [ NS_CATEGORY ] ),
				Title::makeTitle( NS_CATEGORY, 'Category' ),
				[],
				true
			],
			[
				'Namespaces are included if not excluded',
				self::makeMaxWidthConfig( false, [ NS_CATEGORY ] ),
				Title::makeTitle( NS_SPECIAL, 'SpecialPages' ),
				[],
				false
			],
			[
				'More than one namespace can be included',
				self::makeMaxWidthConfig( false, [ NS_CATEGORY, NS_SPECIAL ] ),
				Title::makeTitle( NS_SPECIAL, 'Specialpages' ),
				[],
				true
			],
			[
				'Can be disabled on history page',
				self::makeMaxWidthConfig(
					false,
					[
						/* no namespaces excluded */
					],
					[
						/* no includes */
					],
					[ 'action' => 'history' ]
				),
				Title::makeTitle( NS_MAIN, 'History page' ),
				[ 'action' => 'history' ],
				true
			],
			[
				'Can be disabled with a regex match.',
				self::makeMaxWidthConfig(
					false,
					[
						/* no namespaces excluded */
					],
					[
						/* no includes */
					],
					[ 'foo' => '[0-9]+' ]
				),
				Title::makeTitle( NS_MAIN, 'Test' ),
				[ 'foo' => '1234' ],
				true
			],
			[
				'Can be disabled with a regex match, also for falsy 0.',
				self::makeMaxWidthConfig(
					false,
					[
						/* no namespaces excluded */
					],
					[
						/* no includes */
					],
					[ 'foo' => '[0-9]+' ]
				),
				Title::makeTitle( NS_MAIN, 'Test' ),
				[ 'foo' => '0' ],
				true
			],
			[
				'Can be disabled with a non-regex wildcard (for backwards compatibility).',
				self::makeMaxWidthConfig(
					false,
					[
						/* no namespaces excluded */
					],
					[
						/* no includes */
					],
					[ 'foo' => '*' ]
				),
				Title::makeTitle( NS_MAIN, 'Test' ),
				[ 'foo' => 'bar' ],
				true
			],
			[
				'Include can override exclusions',
				self::makeMaxWidthConfig(
					false,
					[ NS_CATEGORY, NS_SPECIAL ],
					[ 'Special:Specialpages' ],
					[ 'action' => 'history' ]
				),
				Title::makeTitle( NS_SPECIAL, 'Specialpages' ),
				[ 'action' => 'history' ],
				false
			],
			[
				'Max width can be disabled on talk pages',
				$excludeTalkFooConfig,
				Title::makeTitle( NS_TALK, 'A talk page' ),
				[],
				true
			],
			[
				'includes can be used to override any page in a disabled namespace',
				$excludeTalkFooConfig,
				Title::makeTitle( NS_TALK, 'Foo' ),
				[],
				false
			],
			[
				'Excludes/includes are based on root title so should apply to subpages',
				$excludeTalkFooConfig,
				Title::makeTitle( NS_TALK, 'Foo/subpage' ),
				[],
				false
			]
		];
	}

	/**
	 * @todo move into MediaWiki\Skins\Vector\FeatureManagement\Requirements\LimitedWidthContentRequirement
	 *  test in future.
	 * @covers MediaWiki\Skins\Vector\FeatureManagement\Requirements\LimitedWidthContentRequirement::isMet
	 * @dataProvider providerShouldDisableMaxWidth
	 */
	public function testShouldDisableMaxWidth(
		$msg,
		$options,
		$title,
		$requestValues,
		$shouldDisableMaxWidth
	) {
		$requirement = new LimitedWidthContentRequirement(
			new HashConfig( [ 'VectorMaxWidthOptions' => $options ] ),
			new FauxRequest( $requestValues ),
			$title
		);
		$this->assertSame(
			!$shouldDisableMaxWidth,
			$requirement->isMet(),
			$msg
		);
	}

	/**
	 * @covers ::getActiveABTest
	 * @dataProvider provideGetActiveABTest
	 */
	public function testGetActiveABTest( $configData, $expected ) {
		$config = new HashConfig( $configData );
		$vectorConfig = Hooks::getActiveABTest(
			$this->createMock( ResourceLoaderContext::class ),
			$config
		);

		$this->assertSame(
			$expected,
			$vectorConfig
		);
	}

	/**
	 * @covers ::getActiveABTest
	 * @dataProvider provideGetActiveABTestWithExceptions
	 */
	public function testGetActiveABTestWithExceptions( $configData ) {
		$config = new HashConfig( $configData );
		$this->expectException( RuntimeException::class );
		Hooks::getActiveABTest(
			$this->createMock( ResourceLoaderContext::class ),
			$config
		);
	}

	/**
	 * @covers ::onLocalUserCreated
	 */
	public function testOnLocalUserCreatedLegacy() {
		$this->setMwGlobals( [
			'wgVectorDefaultSkinVersionForNewAccounts' => Constants::SKIN_VERSION_LEGACY,
		] );

		$user = $this->createMock( User::class );
		$userOptionsManager = $this->createMock( UserOptionsManager::class );
		$userOptionsManager->expects( $this->once() )
			->method( 'setOption' )
			->with( $user, 'skin', Constants::SKIN_NAME_LEGACY );
		$this->setService( 'UserOptionsManager', $userOptionsManager );

		// NOTE: Using $this->getServiceContainer()->getHookContainer()->run( ... )
		// will instead call Echo's legacy hook as that is already registered which
		// will break this test. Use Vector's hook handler instead.
		( new Hooks() )->onLocalUserCreated( $user, false );
	}

	/**
	 * @covers ::onLocalUserCreated
	 */
	public function testOnLocalUserCreatedLatest() {
		$this->setMwGlobals( [
			'wgVectorDefaultSkinVersionForNewAccounts' => Constants::SKIN_VERSION_LATEST,
		] );

		$user = $this->createMock( User::class );
		$userOptionsManager = $this->createMock( UserOptionsManager::class );
		$userOptionsManager->expects( $this->once() )
			->method( 'setOption' )
			->with( $user, 'skin', Constants::SKIN_NAME_MODERN );
		$this->setService( 'UserOptionsManager', $userOptionsManager );

		// NOTE: Using $this->getServiceContainer()->getHookContainer()->run( ... )
		// will instead call Echo's legacy hook as that is already registered which
		// will break this test. Use Vector's hook handler instead.
		( new Hooks() )->onLocalUserCreated( $user, false );
	}

	/**
	 * @covers ::onSkinTemplateNavigation
	 */
	public function testOnSkinTemplateNavigation() {
		$this->setMwGlobals( [
			'wgVectorUseIconWatch' => true,
		] );
		$skin = new SkinVector22( [ 'name' => 'vector' ] );
		$skin->getContext()->setTitle( Title::newFromText( 'Foo' ) );
		$contentNavWatch = [
			'associated-pages' => [],
			'views' => [],
			'actions' => [
				'watch' => [ 'class' => [ 'watch' ], 'icon' => 'star' ],
			]
		];
		$contentNavUnWatch = [
			'associated-pages' => [],
			'views' => [],
			'actions' => [
				'move' => [ 'class' => [ 'move' ] ],
				'unwatch' => [ 'icon' => 'unStar' ],
			],
		];

		Hooks::onSkinTemplateNavigation( $skin, $contentNavUnWatch );
		Hooks::onSkinTemplateNavigation( $skin, $contentNavWatch );

		$this->assertContains(
			'icon', $contentNavWatch['views']['watch']['class'],
			'Watch list items require an "icon" class'
		);
		$this->assertContains(
			'icon', $contentNavUnWatch['views']['unwatch']['class'],
			'Unwatch list items require an "icon" class'
		);
		$this->assertNotContains(
			'icon', $contentNavUnWatch['actions']['move']['class'],
			'List item other than watch or unwatch should not have an "icon" class'
		);
	}

	/**
	 * @covers ::updateUserLinksItems
	 */
	public function testUpdateUserLinksItems() {
		$vector2022Skin = new SkinVector22( [ 'name' => 'vector-2022' ] );
		$contentNav = [
			'associated-pages' => [],
			'views' => [],
			'user-page' => [
				'userpage' => [ 'class' => [], 'icon' => 'userpage' ],
			],
			'user-menu' => [
				'login' => [ 'class' => [], 'icon' => 'login' ],
			]
		];
		$vectorLegacySkin = new SkinVectorLegacy( [ 'name' => 'vector' ] );
		$contentNavLegacy = [
			'associated-pages' => [],
			'views' => [],
			'user-page' => [
				'userpage' => [ 'class' => [], 'icon' => 'userpage' ],
			]
		];

		Hooks::onSkinTemplateNavigation( $vector2022Skin, $contentNav );
		$this->assertFalse( isset( $contentNav['user-page']['login'] ),
			'updateUserLinksDropdownItems is called when not legacy'
		);
		Hooks::onSkinTemplateNavigation( $vectorLegacySkin, $contentNavLegacy );
		$this->assertFalse( isset( $contentNavLegacy['user-page'] ),
			'user-page is unset for legacy vector'
		);
	}

	/**
	 * @covers ::updateUserLinksDropdownItems
	 */
	public function testUpdateUserLinksDropdownItems() {
		$updateUserLinksDropdownItems = new ReflectionMethod(
			Hooks::class,
			'updateUserLinksDropdownItems'
		);
		$updateUserLinksDropdownItems->setAccessible( true );

		// Anon users
		$skin = new SkinVector22( [ 'name' => 'vector-2022' ] );
		$contentAnon = [
			'user-menu' => [
				'anonuserpage' => [ 'class' => [], 'icon' => 'anonuserpage' ],
				'createaccount' => [ 'class' => [], 'icon' => 'createaccount' ],
				'login' => [ 'class' => [], 'icon' => 'login' ],
				'anontalk' => [ 'class' => [], 'icon' => 'anontalk' ],
				'anoncontribs' => [ 'class' => [], 'icon' => 'anoncontribs' ],
			],
		];
		$updateUserLinksDropdownItems->invokeArgs( null, [ $skin, &$contentAnon ] );
		$this->assertTrue(
			count( $contentAnon['user-menu'] ) === 2 &&
			isset( $contentAnon['user-menu']['createaccount'] ) &&
			isset( $contentAnon['user-menu']['login'] ),
			'Anon user page, anon talk, anon contribs links are removed from user-menu'
		);

		$this->assertTrue(
			count( $contentAnon['user-menu'] ) === 2 &&
			isset( $contentAnon['user-menu-anon-editor']['anontalk'] ) &&
			isset( $contentAnon['user-menu-anon-editor']['anoncontribs'] ),
			'Anon talk, anon contribs links are moved to user-menu-anon-editor'
		);

		// Registered user
		$registeredUser = $this->createMock( User::class );
		$registeredUser->method( 'isRegistered' )->willReturn( true );
		$context = new RequestContext();
		$context->setUser( $registeredUser );
		$skin->setContext( $context );
		$contentRegistered = [
			'user-menu' => [
				'userpage' => [ 'class' => [], 'icon' => 'userpage' ],
				'watchlist' => [ 'class' => [], 'icon' => 'watchlist' ],
				'logout' => [ 'class' => [], 'icon' => 'logout' ],
			],
		];
		$updateUserLinksDropdownItems->invokeArgs( null, [ $skin, &$contentRegistered ] );
		$this->assertContains( 'user-links-collapsible-item', $contentRegistered['user-menu']['userpage']['class'],
			'User page link in user links dropdown requires collapsible class'
		);
		$this->assertEquals(
			'<span class="mw-ui-icon mw-ui-icon-userpage mw-ui-icon-wikimedia-userpage"></span>',
			$contentRegistered['user-menu']['userpage']['link-html'],
			'User page link in user links dropdown has link-html'
		);
		$this->assertContains( 'user-links-collapsible-item', $contentRegistered['user-menu']['watchlist']['class'],
			'Watchlist link in user links dropdown requires collapsible class'
		);
		$this->assertEquals(
			'<span class="mw-ui-icon mw-ui-icon-watchlist mw-ui-icon-wikimedia-watchlist"></span>',
			$contentRegistered['user-menu']['watchlist']['link-html'],
			'Watchlist link in user links dropdown has link-html'
		);
		$this->assertFalse( isset( $contentRegistered['user-menu']['logout'] ),
			'Logout link in user links dropdown is not set'
		);
		$this->assertTrue( isset( $contentRegistered['user-menu-logout']['logout'] ),
			'Logout link in user links dropdown is not set'
		);
	}

	/**
	 * @covers ::updateUserLinksOverflowItems
	 */
	public function testUpdateUserLinksOverflowItems() {
		$updateUserLinksOverflowItems = new ReflectionMethod(
			Hooks::class,
			'updateUserLinksOverflowItems'
		);
		$updateUserLinksOverflowItems->setAccessible( true );
		$skin = new SkinVector22( [ 'name' => 'vector-2022' ] );

		// Registered user
		$registeredUser = $this->createMock( User::class );
		$registeredUser->method( 'isRegistered' )->willReturn( true );
		$context = new RequestContext();
		$context->setUser( $registeredUser );
		$skin->setContext( $context );
		$content = [
			'notifications' => [
				'alert' => [ 'class' => [], 'icon' => 'alert' ],
			],
			'user-interface-preferences' => [
				'uls' => [ 'class' => [], 'icon' => 'uls' ],
			],
			'user-page' => [
				'userpage' => [ 'class' => [], 'icon' => 'userpage' ],
				'watchlist' => [ 'class' => [], 'icon' => 'watchlist' ],
			],
			'user-menu' => [
				'watchlist' => [ 'class' => [], 'icon' => 'watchlist' ],
			],
		];
		$updateUserLinksOverflowItems->invokeArgs( null, [ $skin, &$content ] );
		$this->assertContains( 'user-links-collapsible-item',
			$content['vector-user-menu-overflow']['uls']['class'],
			'ULS link in user links overflow requires collapsible class'
		);
		$this->assertContains( 'user-links-collapsible-item',
			$content['vector-user-menu-overflow']['userpage']['class'],
			'User page link in user links overflow requires collapsible class'
		);
		$this->assertNotContains( 'mw-ui-icon',
			$content['vector-user-menu-overflow']['userpage']['class'],
			'User page link in user links overflow does not have icon classes'
		);
		$this->assertContains( 'user-links-collapsible-item',
			$content['vector-user-menu-overflow']['watchlist']['class'],
			'Watchlist link in user links overflow requires collapsible class'
		);
		$this->assertContains( 'mw-ui-button',
			$content['vector-user-menu-overflow']['watchlist']['link-class'],
			'Watchlist link in user links overflow requires button classes'
		);
		$this->assertContains( 'mw-ui-quiet',
			$content['vector-user-menu-overflow']['watchlist']['link-class'],
			'Watchlist link in user links overflow requires quiet button classes'
		);
		$this->assertContains( 'mw-ui-icon-element',
			$content['vector-user-menu-overflow']['watchlist']['link-class'],
			'Watchlist link in user links overflow hides text'
		);
		$this->assertTrue(
			$content['vector-user-menu-overflow']['watchlist']['id'] === 'pt-watchlist-2',
			'Watchlist link in user links has unique id'
		);
	}

	public static function provideAppendClassToItem() {
		return [
			// Add array class to array
			[
				[],
				[ 'array1', 'array2' ],
				[ 'array1', 'array2' ],
			],
			// Add string class to array
			[
				[],
				'array1 array2',
				[ 'array1 array2' ],
			],
			// Add array class to string
			[
				'',
				[ 'array1', 'array2' ],
				'array1 array2',
			],
			// Add string class to string
			[
				'',
				'array1 array2',
				'array1 array2',
			],
			// Add string class to undefined
			[
				null,
				'array1 array2',
				'array1 array2',
			],
			// Add array class to undefined
			[
				null,
				[ 'array1', 'array2' ],
				[ 'array1', 'array2' ],
			],
		];
	}

	/**
	 * @covers ::appendClassToItem
	 * @dataProvider provideAppendClassToItem
	 */
	public function testAppendClassToItem(
		$item,
		$classes,
		$expected
	) {
		$appendClassToItem = new ReflectionMethod(
			Hooks::class,
			'appendClassToItem'
		);
		$appendClassToItem->setAccessible( true );
		$appendClassToItem->invokeArgs( null, [ &$item, $classes ] );
		$this->assertEquals( $expected, $item );
	}

	public static function provideUpdateItemData() {
		return [
			// Removes extra attributes
			[
				[ 'class' => [], 'icon' => '', 'button' => false, 'text-hidden' => false, 'collapsible' => false ],
				'link-class',
				'link-html',
				[ 'class' => [] ],
			],
			// Adds icon html
			[
				[ 'class' => [], 'icon' => 'userpage' ],
				'link-class',
				'link-html',
				[
					'class' => [],
					'link-html' =>
					'<span class="mw-ui-icon mw-ui-icon-userpage mw-ui-icon-wikimedia-userpage"></span>'
				],
			],
			// Adds collapsible class
			[
				[ 'class' => [], 'collapsible' => true ],
				'link-class',
				'link-html',
				[ 'class' => [ 'user-links-collapsible-item' ] ],
			],
			// Adds button classes
			[
				[ 'class' => [], 'button' => true ],
				'link-class',
				'link-html',
				[ 'class' => [], 'link-class' => [ 'mw-ui-button', 'mw-ui-quiet' ] ],
			],
			// Adds text hidden classes
			[
				[ 'class' => [], 'text-hidden' => true, 'icon' => 'userpage' ],
				'link-class',
				'link-html',
				[
					'class' => [],
					'link-class' => [ 'mw-ui-icon-element' ],
					'link-html' => '<span class="mw-ui-icon mw-ui-icon-userpage mw-ui-icon-wikimedia-userpage"></span>'
				],
			],
			// Adds button and icon classes
			[
				[ 'class' => [], 'button' => true, 'icon' => 'userpage' ],
				'class',
				'link-html',
				[
					'class' => [ 'mw-ui-button', 'mw-ui-quiet' ],
					'link-html' => '<span class="mw-ui-icon mw-ui-icon-userpage mw-ui-icon-wikimedia-userpage"></span>'
				],
			]
		];
	}

	/**
	 * @covers ::updateItemData
	 * @dataProvider provideUpdateItemData
	 */
	public function testUpdateItemData(
		array $item,
		string $buttonClassProp,
		string $iconHtmlProp,
		array $expected
	) {
		$updateItemData = new ReflectionMethod(
			Hooks::class,
			'updateItemData'
		);
		$updateItemData->setAccessible( true );
		$data = $updateItemData->invokeArgs( null, [ $item, $buttonClassProp, $iconHtmlProp ] );
		$this->assertArraySubmapSame( $expected, $data );
	}
}
