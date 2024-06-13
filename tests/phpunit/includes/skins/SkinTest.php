<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Permissions\Authority;
use MediaWiki\Tests\Unit\MockBlockTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \Skin
 * @group Database
 */
class SkinTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;
	use MockBlockTrait;

	/**
	 * @covers \Skin
	 */
	public function testGetSkinName() {
		$skin = new SkinFallback();
		$this->assertEquals( 'fallback', $skin->getSkinName(), 'Default' );
		$skin = new SkinFallback( 'testname' );
		$this->assertEquals( 'testname', $skin->getSkinName(), 'Constructor argument' );
	}

	public function testGetDefaultModules() {
		$skin = $this->getMockBuilder( Skin::class )
			->onlyMethods( [ 'outputPage' ] )
			->getMock();

		$modules = $skin->getDefaultModules();
		$this->assertTrue( isset( $modules['core'] ), 'core key is set by default' );
		$this->assertTrue( isset( $modules['styles'] ), 'style key is set by default' );
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

	public static function provideGetDefaultModulesForOutput() {
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

	/**
	 * @dataProvider provideGetDefaultModulesForOutput
	 */
	public function testGetDefaultModulesForContent( $isSyndicated, $html, array $expectedModuleStyles ) {
		$skin = new class extends Skin {
			public function outputPage() {
			}
		};
		$fakeContext = new RequestContext();
		$fakeContext->setTitle( Title::makeTitle( NS_MAIN, 'Test' ) );
		$fakeContext->setOutput( $this->getMockOutputPage( $isSyndicated, $html ) );
		$skin->setContext( $fakeContext );

		$modules = $skin->getDefaultModules();

		$actualStylesModule = array_merge( ...array_values( $modules['styles'] ) );
		foreach ( $expectedModuleStyles as $expected ) {
			$this->assertContains( $expected, $actualStylesModule );
		}
	}

	public function provideGetDefaultModulesForRights() {
		yield 'no rights' => [
			$this->mockRegisteredNullAuthority(), // $authority
			false, // $hasModule
		];
		yield 'has all rights' => [
			$this->mockRegisteredUltimateAuthority(), // $authority
			true, // $hasModule
		];
	}

	/**
	 * @dataProvider provideGetDefaultModulesForRights
	 */
	public function testGetDefaultModulesForRights( Authority $authority, bool $hasModule ) {
		$skin = new class extends Skin {
			public function outputPage() {
			}
		};
		$fakeContext = new RequestContext();
		$fakeContext->setAuthority( $authority );
		$fakeContext->setTitle( Title::makeTitle( NS_MAIN, 'Test' ) );
		$skin->setContext( $fakeContext );

		$defaultModules = $skin->getDefaultModules();
		$this->assertArrayHasKey( 'watch', $defaultModules );
		if ( $hasModule ) {
			$this->assertContains( 'mediawiki.page.watch.ajax', $defaultModules['watch'] );
		} else {
			$this->assertNotContains( 'mediawiki.page.watch.ajax', $defaultModules['watch'] );
		}
	}

	public function providGetPageClasses() {
		yield 'normal page has namespace' => [
			new TitleValue( NS_MAIN, 'Test' ), // $title
			$this->mockRegisteredUltimateAuthority(), // $authority
			[ 'ns-0' ], // $expectedClasses
		];
		yield 'valid special page' => [
			new TitleValue( NS_SPECIAL, 'Userlogin' ), // $title
			$this->mockRegisteredUltimateAuthority(), // $authority
			[ 'mw-special-Userlogin' ], // $expectedClasses
		];
		yield 'invalid special page' => [
			new TitleValue( NS_SPECIAL, 'BLABLABLABLA_I_AM_INVALID' ), // $title
			$this->mockRegisteredUltimateAuthority(), // $authority
			[ 'mw-invalidspecialpage' ], // $expectedClasses
		];
		yield 'talk page' => [
			new TitleValue( NS_TALK, 'Test' ), // $title
			$this->mockRegisteredUltimateAuthority(), // $authority
			[ 'ns-talk' ], // $expectedClasses
		];
		yield 'subject' => [
			new TitleValue( NS_MAIN, 'Test' ), // $title
			$this->mockRegisteredUltimateAuthority(), // $authority
			[ 'ns-subject' ], // $expectedClasses
		];
		yield 'editable' => [
			new TitleValue( NS_MAIN, 'Test' ), // $title
			$this->mockRegisteredAuthorityWithPermissions( [ 'edit' ] ), // $authority
			[ 'mw-editable' ], // $expectedClasses
		];
		yield 'not editable' => [
			new TitleValue( NS_MAIN, 'Test' ), // $title
			$this->mockRegisteredNullAuthority(), // $authority
			[], // $expectedClasses
			[ 'mw-editable' ], // $unexpectedClasses
		];
	}

	/**
	 * @dataProvider providGetPageClasses
	 */
	public function testGetPageClasses(
		LinkTarget $title,
		Authority $authority,
		array $expectedClasses,
		array $unexpectedClasses = []
	) {
		$skin = new class extends Skin {
			public function outputPage() {
			}
		};
		$fakeContext = new RequestContext();
		$fakeContext->setAuthority( $authority );
		$skin->setContext( $fakeContext );
		$classes = $skin->getPageClasses( Title::newFromLinkTarget( $title ) );
		foreach ( $expectedClasses as $class ) {
			$this->assertStringContainsString( $class, $classes );
		}
		foreach ( $unexpectedClasses as $class ) {
			$this->assertStringNotContainsString( $class, $classes );
		}
	}

	/**
	 * @dataProvider provideSkinResponsiveOptions
	 */
	public function testIsResponsive( array $options, bool $expected ) {
		$skin = new class( $options ) extends Skin {

			/**
			 * @inheritDoc
			 */
			public function outputPage() {
			}

			/**
			 * @inheritDoc
			 */
			public function getUser() {
				$user = TestUserRegistry::getImmutableTestUser( [] )->getUser();
				\MediaWiki\MediaWikiServices::getInstance()->getUserOptionsManager()->setOption(
					$user,
					'skin-responsive',
					$this->options['userPreference']
				);
				return $user;
			}
		};

		$this->assertSame( $expected, $skin->isResponsive() );
	}

	public static function provideSkinResponsiveOptions() {
		yield 'responsive not set' => [
			[ 'name' => 'test', 'userPreference' => true ],
			false
		];
		yield 'responsive false' => [
			[ 'name' => 'test', 'responsive' => false, 'userPreference' => true ],
			false
		];
		yield 'responsive true' => [
			[ 'name' => 'test', 'responsive' => true, 'userPreference' => true ],
			true
		];
		yield 'responsive true, user preference false' => [
			[ 'name' => 'test', 'responsive' => true, 'userPreference' => false ],
			false
		];
		yield 'responsive false, user preference false' => [
			[ 'name' => 'test', 'responsive' => false, 'userPreference' => false ],
			false
		];
	}

	public static function provideMakeLink() {
		return [
			'Empty href with link class' => [
				[
					'text' => 'Test',
					'href' => '',
					'class' => [
						'class1',
						'class2'
					]
				],
				[ 'link-class' => 'link-class' ],
				'<a href="" class="class1 class2 link-class">Test</a>',
			],
			'link with link-html' => [
				[
					'text' => '',
					'href' => '#go',
					'link-html' => '<i>label</i>'
				],
				[ 'text-wrapper' => [ 'tag' => 'span' ] ],
				'<a href="#go"><i>label</i> </a>',
			],
			'Basic text wrapper' => [
				[
					'text' => 'Test',
				],
				[ 'text-wrapper' => [ 'tag' => 'span' ] ],
				'<span>Test</span>'
			],
			'Text wrapper with tooltip ID in id attribute' => [
				[
					'text' => 'Test',
					'id' => 'ii'
				],
				[ 'text-wrapper' => [ 'tag' => 'span' ] ],
				'<span title="(tooltip-ii)">Test</span>'
			],
			'Text wrapper with tooltip ID in single-id' => [
				[
					'text' => 'Test',
					'id' => 'foo',
					'single-id' => 'ii'
				],
				[ 'text-wrapper' => [ 'tag' => 'span' ] ],
				'<span title="(tooltip-ii)">Test</span>'
			],
			'Multi-level text wrapper with tooltip' => [
				[
					'text' => 'Test',
					'id' => 'ii'
				],
				[ 'text-wrapper' => [
					[ 'tag' => 'b' ],
					[ 'tag' => 'i' ]
				] ],
				'<b title="(tooltip-ii)"><i>Test</i></b>'
			],
			'Multi-level text wrapper with link' => [
				[
					'text' => 'Test',
					'id' => 'ii',
					'href' => '#',
				],
				[ 'text-wrapper' => [
					[ 'tag' => 'b' ],
					[ 'tag' => 'i' ]
				] ],
				'<a id="ii" href="#" title="(tooltip-ii)(word-separator)(brackets: (accesskey-ii))" ' .
				'accesskey="(accesskey-ii)"><b><i>Test</i></b></a>'
			],
			'Specified HTML' => [
				[
					'html' => '<b>1</b>',
				],
				[],
				'<b>1</b>'
			],
			'Data attribute' => [
				[
					'text' => 'Test',
					'href' => '#',
					'data' => [ 'foo' => 'bar' ]
				],
				[],
				'<a href="#" data-foo="bar">Test</a>'
			],
			'tooltip only' => [
				[
					'text' => 'Save',
					'id' => 'save',
					'href' => '#',
					'tooltiponly' => true,
				],
				[],
				'<a id="save" href="#" title="(tooltip-save)">Save</a>'
			]
		];
	}

	/**
	 * @dataProvider provideMakeLink
	 * @param array $data
	 * @param array $options
	 * @param string $expected
	 */
	public function testMakeLinkLink( array $data, array $options, string $expected ) {
		$this->setUserLang( 'qqx' );
		$skin = new class extends Skin {
			public function outputPage() {
			}
		};

		$link = $skin->makeLink(
			'test',
			$data,
			$options
		);

		$this->assertHTMLEquals(
			$expected,
			$link
		);
	}

	public static function provideGetPersonalToolsForMakeListItem() {
		return [
			[
				[
					'foo' => [
						'class' => 'foo',
						'link-html' => '<i>text</i>',
						'text' => 'Hello',
					],
				],
				false,
				[
					'foo' => [
						'links' => [
							[
								'single-id' => 'pt-foo',
								'text' => 'Hello',
								'link-html' => '<i>text</i>',
								'class' => 'foo',
							]
						],
						'id' => 'pt-foo',
					]
				],
			],
			[
				[
					'foo' => [
						'class' => 'foo',
						'link-html' => '<i>text</i>',
						'text' => 'Hello',
					],
				],
				true,
				[
					'foo' => [
						'links' => [
							[
								'single-id' => 'pt-foo',
								'text' => 'Hello',
								'link-html' => '<i>text</i>',
							]
						],
						'id' => 'pt-foo',
						'class' => 'foo',
					]
				],
			]
		];
	}

	/**
	 * @dataProvider provideGetPersonalToolsForMakeListItem
	 * @param array $urls
	 * @param bool $applyClassesToListItems
	 * @param array $expected
	 */
	public function testGetPersonalToolsForMakeListItem( array $urls, bool $applyClassesToListItems, array $expected ) {
		$skin = new class extends Skin {
			public function outputPage() {
			}
		};

		$this->assertSame(
			$expected,
			$skin->getPersonalToolsForMakeListItem(
				$urls,
				$applyClassesToListItems
			)
		);
	}

	public function testGetRelevantUser_get_set() {
		$skin = new class extends Skin {
			public function outputPage() {
			}
		};
		$relevantUser = UserIdentityValue::newRegistered( 1, 'SomeUser' );
		$skin->setRelevantUser( $relevantUser );
		$this->assertSame( $relevantUser, $skin->getRelevantUser() );

		$this->installMockBlockManager(
			[
				'target' => $relevantUser,
				'hideName' => true,
			]
		);

		$ctx = RequestContext::getMain();
		$ctx->setAuthority( $this->mockAnonNullAuthority() );
		$skin->setContext( $ctx );
		$this->assertNull( $skin->getRelevantUser() );

		$ctx->setAuthority( $this->mockAnonUltimateAuthority() );
		$skin->setContext( $ctx );
		$skin->setRelevantUser( $relevantUser );
		$skin->setRelevantUser( $relevantUser );
		$this->assertSame( $relevantUser, $skin->getRelevantUser() );
	}

	public static function provideGetRelevantUser_load_from_title() {
		yield 'Not user namespace' => [
			'relevantPage' => PageReferenceValue::localReference( NS_MAIN, '123.123.123.123' ),
			'expectedUser' => null
		];
		yield 'User namespace' => [
			'relevantPage' => PageReferenceValue::localReference( NS_USER, '123.123.123.123' ),
			'expectedUser' => UserIdentityValue::newAnonymous( '123.123.123.123' )
		];
		yield 'User talk namespace' => [
			'relevantPage' => PageReferenceValue::localReference( NS_USER_TALK, '123.123.123.123' ),
			'expectedUser' => UserIdentityValue::newAnonymous( '123.123.123.123' )
		];
		yield 'User page subpage' => [
			'relevantPage' => PageReferenceValue::localReference( NS_USER, '123.123.123.123/bla' ),
			'expectedUser' => UserIdentityValue::newAnonymous( '123.123.123.123' )
		];
		yield 'Non-registered user with name' => [
			'relevantPage' => PageReferenceValue::localReference( NS_USER, 'I_DO_NOT_EXIST' ),
			'expectedUser' => null
		];
	}

	/**
	 * @dataProvider provideGetRelevantUser_load_from_title
	 */
	public function testGetRelevantUser_load_from_title(
		PageReferenceValue $relevantPage,
		?UserIdentity $expectedUser
	) {
		$skin = new class extends Skin {
			public function outputPage() {
			}
		};
		$skin->setRelevantTitle( Title::newFromPageReference( $relevantPage ) );
		$relevantUser = $skin->getRelevantUser();
		if ( $expectedUser ) {
			$this->assertTrue( $expectedUser->equals( $relevantUser ) );
		} else {
			$this->assertNull( $relevantUser );
		}
	}

	public function testGetRelevantUser_load_existing() {
		$skin = new class extends Skin {
			public function outputPage() {
			}
		};
		$user = new UserIdentityValue( 42, 'foo' );
		$userIdentityLookup = $this->createMock( UserIdentityLookup::class );
		$userIdentityLookup->method( 'getUserIdentityByName' )
			->willReturnCallback( function ( $name ) use ( $user ) {
				if ( $name === $user->getName() ) {
					return $user;
				}
				return $this->createMock( UserIdentity::class );
			} );
		$this->setService( 'UserIdentityLookup', $userIdentityLookup );
		$skin->setRelevantTitle(
			Title::makeTitle( NS_USER, $user->getName() )
		);
		$this->assertTrue( $user->equals( $skin->getRelevantUser() ) );
		$this->assertSame( $user->getId(), $skin->getRelevantUser()->getId() );
	}

	public function testBuildSidebarCache() {
		// T303007: Skin subclasses and Skin hooks may vary their sidebar contents.
		$this->overrideConfigValues( [
			MainConfigNames::UseDatabaseMessages => true,
			MainConfigNames::EnableSidebarCache => true,
			MainConfigNames::SidebarCacheExpiry => 3600,
		] );
		// Mock time (T344191)
		$clock = 1301644800.0;
		$this->getServiceContainer()->getMainWANObjectCache()->setMockTime( $clock );
		$id = 0;
		$this->setTemporaryHook( 'SkinBuildSidebar',
			static function ( Skin $skin, array &$bar ) use ( &$id, &$clock ) {
				$id++;
				$clock += 1.0;
				if ( $skin->getSkinName() === 'foo' ) {
					$bar['myhook'] = "foo $id";
				}
				if ( $skin->getSkinName() === 'bar' ) {
					$bar['myhook'] = "bar $id";
				}
			}
		);
		$context = RequestContext::newExtraneousContext( Title::makeTitle( NS_SPECIAL, 'Blankpage' ) );
		$foo1 = new class( 'foo' ) extends Skin {
			public function outputPage() {
			}
		};
		$foo2 = new class( 'foo' ) extends Skin {
			public function outputPage() {
			}
		};
		$bar = new class( 'bar' ) extends Skin {
			public function outputPage() {
			}
		};
		$foo1->setContext( $context );
		$foo2->setContext( $context );
		$bar->setContext( $context );
		$this->assertArrayContains( [ 'myhook' => 'foo 1' ], $foo1->buildSidebar(), 'fresh' );
		$clock += 0.01;
		$this->assertArrayContains( [ 'myhook' => 'foo 1' ], $foo2->buildSidebar(), 'cache hit' );
		$this->assertArrayContains( [ 'myhook' => 'bar 2' ], $bar->buildSidebar(), 'cache miss' );
	}

	public function testBuildSidebarWithUserAddedContent() {
		$this->overrideConfigValues( [
			MainConfigNames::UseDatabaseMessages => true,
			MainConfigNames::EnableSidebarCache => false
		] );
		$foo1 = new class( 'foo' ) extends Skin {
			public function outputPage() {
			}
		};
		$this->editPage( 'MediaWiki:Sidebar', <<<EOS
		* navigation
		** mainpage|mainpage-description
		** recentchanges-url|recentchanges
		** randompage-url|randompage
		** helppage|help-mediawiki
		* SEARCH
		* TOOLBOX
		** A|B
		* LANGUAGES
		** C|D
		EOS );

		$context = RequestContext::newExtraneousContext( Title::makeTitle( NS_MAIN, 'Main Page' ) );
		$foo1->setContext( $context );

		$this->assertArrayContains( [ [ 'id' => 'n-B', 'text' => 'B' ] ], $foo1->buildSidebar()['TOOLBOX'], 'Toolbox has user defined links' );

		$hasUserDefinedLinks = false;
		$languageLinks = $foo1->buildSidebar()['LANGUAGES'];
		foreach ( $languageLinks as $languageLink ) {
			if ( $languageLink['id'] === 'n-D' ) {
				$hasUserDefinedLinks = true;
				break;
			}
		}

		$this->assertSame( false, $hasUserDefinedLinks, 'Languages does not support user defined links' );
	}
}
