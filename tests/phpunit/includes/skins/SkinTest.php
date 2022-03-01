<?php

use MediaWiki\Block\BlockManager;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Permissions\Authority;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;

class SkinTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	/**
	 * @covers Skin
	 */
	public function testGetSkinName() {
		$skin = new SkinFallback();
		$this->assertEquals( 'fallback', $skin->getSkinName(), 'Default' );
		$skin = new SkinFallback( 'testname' );
		$this->assertEquals( 'testname', $skin->getSkinName(), 'Constructor argument' );
	}

	/**
	 * @covers Skin::getDefaultModules
	 */
	public function testGetDefaultModules() {
		$skin = $this->getMockBuilder( Skin::class )
			->onlyMethods( [ 'outputPage' ] )
			->getMock();

		$modules = $skin->getDefaultModules();
		$this->assertTrue( isset( $modules['core'] ), 'core key is set by default' );
		$this->assertTrue( isset( $modules['styles'] ), 'style key is set by default' );
	}

	public function provideGetDefaultModulesWatchWrite() {
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
	 * @dataProvider provideGetDefaultModulesWatchWrite
	 * @covers Skin::getDefaultModules
	 */
	public function testGetDefaultModulesWatchWrite( Authority $authority, bool $hasModule ) {
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
	 * @covers Skin::getPageClasses
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
	 * @covers Skin::isResponsive
	 *
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

	public function provideSkinResponsiveOptions() {
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

	/**
	 * @covers Skin::makeLink
	 */
	public function testMakeLinkLinkClass() {
		$skin = new class extends Skin {
			public function outputPage() {
			}
		};

		$link = $skin->makeLink(
			'test',
			[
				'text' => 'Test',
				'href' => '',
				'class' => [
					'class1',
					'class2'
				]
			],
			[ 'link-class' => 'link-class' ]
		);

		$this->assertHTMLEquals(
			'<a href="" class="class1 class2 link-class">Test</a>',
			$link
		);
	}

	/**
	 * @covers Skin::setRelevantUser
	 * @covers Skin::getRelevantUser
	 */
	public function testGetRelevantUser_get_set() {
		$skin = new class extends Skin {
			public function outputPage() {
			}
		};
		$relevantUser = UserIdentityValue::newRegistered( 1, '123.123.123.123' );
		$skin->setRelevantUser( $relevantUser );
		$this->assertSame( $relevantUser, $skin->getRelevantUser() );

		$blockManagerMock = $this->createNoOpMock( BlockManager::class, [ 'getUserBlock' ] );
		$blockManagerMock->method( 'getUserBlock' )
			->with( $relevantUser )
			->willReturn( new DatabaseBlock( [
				'address' => $relevantUser,
				'wiki' => $relevantUser->getWikiId(),
				'by' => UserIdentityValue::newAnonymous( '123.123.123.123' ),
				'hideName' => true
			] ) );
		$this->setService( 'BlockManager', $blockManagerMock );
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

	public function provideGetRelevantUser_load_from_title() {
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
	 * @covers Skin::getRelevantUser
	 */
	public function testGetRelevantUser_load_from_title(
		PageReferenceValue $relevantPage,
		?UserIdentity $expectedUser
	) {
		$skin = new class extends Skin {
			public function outputPage() {
			}
		};
		$skin->setRelevantTitle( Title::castFromPageReference( $relevantPage ) );
		$relevantUser = $skin->getRelevantUser();
		if ( $expectedUser ) {
			$this->assertTrue( $expectedUser->equals( $relevantUser ) );
		} else {
			$this->assertNull( $relevantUser );
		}
	}

	/**
	 * @covers Skin::getRelevantUser
	 */
	public function testGetRelevantUser_load_existing() {
		$skin = new class extends Skin {
			public function outputPage() {
			}
		};
		$existingUser = $this->getTestSysop()->getUserIdentity();
		$skin->setRelevantTitle(
			Title::makeTitle( NS_USER, $this->getTestSysop()->getUserIdentity()->getName() )
		);
		$this->assertTrue( $existingUser->equals( $skin->getRelevantUser() ) );
		$this->assertSame( $existingUser->getId(), $skin->getRelevantUser()->getId() );
	}
}
