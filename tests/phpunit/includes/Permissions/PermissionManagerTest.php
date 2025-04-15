<?php

namespace MediaWiki\Tests\Integration\Permissions;

use Action;
use MediaWiki\Api\ApiMessage;
use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\SystemBlock;
use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Session\SessionId;
use MediaWiki\Tests\Session\TestUtils;
use MediaWiki\Tests\Unit\MockBlockTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiLangTestCase;
use stdClass;
use TestAllServiceOptionsUsed;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * For the pure unit tests, see \MediaWiki\Tests\Unit\Permissions\PermissionManagerTest.
 *
 * @group Database
 * @covers \MediaWiki\Permissions\PermissionManager
 */
class PermissionManagerTest extends MediaWikiLangTestCase {
	use TestAllServiceOptionsUsed;
	use MockBlockTrait;
	use TempUserTestTrait;

	protected string $userName;
	protected Title $title;
	protected User $user;
	protected User $anonUser;
	protected User $userUser;
	protected User $altUser;

	private const USER_TALK_PAGE = '<user talk page>';

	protected function setUp(): void {
		parent::setUp();

		$localZone = 'UTC';
		$localOffset = date( 'Z' ) / 60;

		$this->overrideConfigValues( [
			MainConfigNames::BlockDisablesLogin => false,
			MainConfigNames::Localtimezone => $localZone,
			MainConfigNames::LocalTZoffset => $localOffset,
			MainConfigNames::ImplicitRights => [
				'limitabletest'
			],
			MainConfigNames::RevokePermissions => [
				'formertesters' => [
					'runtest' => true
				]
			],
			MainConfigNames::AvailableRights => [
				'test',
				'runtest',
				'writetest',
				'nukeworld',
				'modifytest',
				'editmyoptions',
				'editinterface',

				// Interface admin
				'editsitejs',
				'edituserjs',

				// Admin
				'delete',
				'undelete',
				'deletedhistory',
				'deletedtext',
			]
		] );

		$this->setGroupPermissions( 'unittesters', 'test', true );
		$this->setGroupPermissions( 'unittesters', 'runtest', true );
		$this->setGroupPermissions( 'unittesters', 'writetest', false );
		$this->setGroupPermissions( 'unittesters', 'nukeworld', false );

		$this->setGroupPermissions( 'testwriters', 'test', true );
		$this->setGroupPermissions( 'testwriters', 'writetest', true );
		$this->setGroupPermissions( 'testwriters', 'modifytest', true );

		$this->setGroupPermissions( '*', 'editmyoptions', true );

		$this->setGroupPermissions( 'deleted-viewer', 'deletedhistory', true );
		$this->setGroupPermissions( 'deleted-viewer', 'deletedtext', true );
		$this->setGroupPermissions( 'deleted-viewer', 'viewsuppressed', true );

		$this->setGroupPermissions( 'interface-admin', 'editinterface', true );
		$this->setGroupPermissions( 'interface-admin', 'editsitejs', true );
		$this->setGroupPermissions( 'interface-admin', 'edituserjs', true );
		$this->setGroupPermissions( 'sysop', 'editinterface', true );
		$this->setGroupPermissions( 'sysop', 'delete', true );
		$this->setGroupPermissions( 'sysop', 'undelete', true );
		$this->setGroupPermissions( 'sysop', 'deletedhistory', true );
		$this->setGroupPermissions( 'sysop', 'deletedtext', true );

		// Without this testUserBlock will use a non-English context on non-English MediaWiki
		// installations (because of how Title::checkUserBlock is implemented) and fail.
		RequestContext::resetMain();

		$this->userName = 'Useruser';
		$altUserName = 'Altuseruser';
		date_default_timezone_set( $localZone );

		/**
		 * TODO: We should provision title object(s) via providers not in here
		 * in order for us to avoid setting mInterwiki via reflection property.
		 */
		$this->title = Title::makeTitle( NS_MAIN, "Main Page" );
		if ( !isset( $this->userUser ) || !( $this->userUser instanceof User ) ) {
			$this->userUser = User::newFromName( $this->userName );

			if ( !$this->userUser->getId() ) {
				$this->userUser = User::createNew( $this->userName, [
					"email" => "test@example.com",
					"real_name" => "Test User" ] );
				$this->userUser->load();
			}

			$this->altUser = User::newFromName( $altUserName );
			if ( !$this->altUser->getId() ) {
				$this->altUser = User::createNew( $altUserName, [
					"email" => "alttest@example.com",
					"real_name" => "Test User Alt" ] );
				$this->altUser->load();
			}

			$this->anonUser = User::newFromId( 0 );

			$this->user = $this->userUser;
		}
	}

	protected function setTitle( $ns, $title = "Main_Page" ) {
		$this->title = Title::makeTitle( $ns, $title );
	}

	protected function setUser( $userName = null ) {
		if ( $userName === 'anon' ) {
			$this->user = $this->anonUser;
		} elseif ( $userName === null || $userName === $this->userName ) {
			$this->user = $this->userUser;
		} else {
			$this->user = $this->altUser;
		}
	}

	/**
	 * @dataProvider provideSpecialsAndNSPermissions
	 */
	public function testSpecialsAndNSPermissions(
		$namespace,
		$userPerms,
		$namespaceProtection,
		$expectedPermErrors,
		$expectedUserCan
	) {
		$this->setUser( $this->userName );
		$this->setTitle( $namespace );

		$this->mergeMwGlobalArrayValue( 'wgNamespaceProtection', $namespaceProtection );
		$this->overrideConfigValue(
			MainConfigNames::NamespaceProtection,
			$namespaceProtection + [ NS_MEDIAWIKI => 'editinterface' ]
		);

		$permissionManager = $this->getServiceContainer()->getPermissionManager();

		$this->overrideUserPermissions( $this->user, $userPerms );

		$this->assertEquals(
			$expectedPermErrors,
			$permissionManager->getPermissionErrors( 'bogus', $this->user, $this->title )
		);
		$this->assertSame(
			$expectedUserCan,
			$permissionManager->userCan( 'bogus', $this->user, $this->title )
		);
	}

	public static function provideSpecialsAndNSPermissions() {
		yield [
			'namespace' => NS_SPECIAL,
			'user permissions' => [],
			'namespace protection' => [],
			'expected permission errors' => [ [ 'badaccess-group0' ], [ 'ns-specialprotected' ] ],
			'user can' => false,
		];
		yield [
			'namespace' => NS_MAIN,
			'user permissions' => [ 'bogus' ],
			'namespace protection' => [],
			'expected permission errors' => [],
			'user can' => true,
		];
		yield [
			'namespace' => NS_MAIN,
			'user permissions' => [],
			'namespace protection' => [],
			'expected permission errors' => [ [ 'badaccess-group0' ] ],
			'user can' => false,
		];
		yield [
			'namespace' => NS_USER,
			'user permissions' => [],
			'namespace protection' => [ NS_USER => [ 'bogus' ] ],
			'expected permission errors' => [ [ 'badaccess-group0' ], [ 'namespaceprotected', 'User', 'bogus' ] ],
			'user can' => false,
		];
		yield [
			'namespace' => NS_MEDIAWIKI,
			'user permissions' => [ 'bogus' ],
			'namespace protection' => [],
			'expected permission errors' => [ [ 'protectedinterface', 'bogus' ] ],
			'user can' => false,
		];
		yield [
			'namespace' => NS_MAIN,
			'user permissions' => [ 'bogus' ],
			'namespace protection' => [],
			'expected permission errors' => [],
			'user can' => true,
		];
	}

	public function testCascadingSourcesRestrictions() {
		$this->setTitle( NS_MAIN, "Test page" );
		$this->overrideUserPermissions( $this->user, [ "edit", "bogus", 'createpage' ] );

		$rs = $this->getServiceContainer()->getRestrictionStore();
		$wrapper = TestingAccessWrapper::newFromObject( $rs );
		$wrapper->cache = [ CacheKeyHelper::getKeyForPage( $this->title ) => [
			'cascade_sources' => [
				[
					Title::makeTitle( NS_MAIN, "Bogus" ),
					Title::makeTitle( NS_MAIN, "UnBogus" )
				],
				[
					"bogus" => [ 'bogus', "sysop", "protect", "" ],
				],
				[
					Title::makeTitle( NS_MAIN, "Bogus" ),
					Title::makeTitle( NS_MAIN, "UnBogus" )
				],
				[]
			],
		] ];

		$permissionManager = $this->getServiceContainer()->getPermissionManager();

		$this->assertFalse( $permissionManager->userCan( 'bogus', $this->user, $this->title ) );
		$this->assertEquals( [
			[ "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n", 'bogus' ] ],
			$permissionManager->getPermissionErrors(
				'bogus', $this->user, $this->title ) );

		$this->assertTrue( $permissionManager->userCan( 'edit', $this->user, $this->title ) );
		$this->assertEquals(
			[],
			$permissionManager->getPermissionErrors( 'edit', $this->user, $this->title )
		);
	}

	public function testCascadingSourcesRestrictionsForFile() {
		$this->setTitle( NS_FILE, 'Test.jpg' );
		$this->overrideUserPermissions( $this->user, [ 'edit', 'move', 'upload', 'movefile', 'createpage' ] );

		$rs = $this->getServiceContainer()->getRestrictionStore();
		$wrapper = TestingAccessWrapper::newFromObject( $rs );
		$wrapper->cache = [ CacheKeyHelper::getKeyForPage( $this->title ) => [
				'cascade_sources' => [
					[
						Title::makeTitle( NS_MAIN, 'FileTemplate' ),
						Title::makeTitle( NS_MAIN, 'FileUser' )
					],
					[
						'edit' => [ 'sysop' ],
					],
					[
						Title::makeTitle( NS_MAIN, 'FileTemplate' )
					],
					[
						Title::makeTitle( NS_MAIN, 'FileUser' )
					]
				],
			] ];

		$permissionManager = $this->getServiceContainer()->getPermissionManager();

		$this->assertFalse( $permissionManager->userCan( 'upload', $this->user, $this->title ) );
		$this->assertEquals( [
			[ 'cascadeprotected', 2, "* [[:FileTemplate]]\n* [[:FileUser]]\n", 'upload' ] ],
			$permissionManager->getPermissionErrors( 'upload', $this->user, $this->title )
		);

		$this->assertFalse( $permissionManager->userCan( 'move', $this->user, $this->title ) );
		$this->assertEquals( [
			[ 'cascadeprotected', 2, "* [[:FileTemplate]]\n* [[:FileUser]]\n", 'move' ] ],
			$permissionManager->getPermissionErrors( 'move', $this->user, $this->title )
		);

		$this->assertFalse( $permissionManager->userCan( 'edit', $this->user, $this->title ) );
		$this->assertEquals( [
			[ 'cascadeprotected', 2, "* [[:FileTemplate]]\n* [[:FileUser]]\n", 'edit' ] ],
			$permissionManager->getPermissionErrors( 'edit', $this->user, $this->title )
		);
	}

	/**
	 * @dataProvider provideActionPermissions
	 */
	public function testActionPermissions(
		$namespace,
		$titleOverrides,
		$action,
		$userPerms,
		$expectedPermErrors,
		$expectedUserCan
	) {
		$this->setTitle( $namespace, "Test page" );

		$this->overrideUserPermissions( $this->user, $userPerms );

		$permissionManager = $this->getServiceContainer()->getPermissionManager();

		$rs = $this->getServiceContainer()->getRestrictionStore();
		$wrapper = TestingAccessWrapper::newFromObject( $rs );
		$wrapper->cache = [ CacheKeyHelper::getKeyForPage( $this->title ) => [
			'create_protection' => [
				'permission' => $titleOverrides['protectedPermission'] ?? '',
				'user' => $this->user->getId(),
				'expiry' => 'infinity',
				'reason' => 'test',
			],
			'has_cascading' => false,
			// XXX This is bogus, restrictions won't be empty if there's create protection
			'restrictions' => [],
		] ];

		if ( isset( $titleOverrides['interwiki'] ) ) {
			$reflectedTitle = TestingAccessWrapper::newFromObject( $this->title );
			$reflectedTitle->mInterwiki = $titleOverrides['interwiki'];
		}

		$this->assertEquals(
			$expectedPermErrors,
			$permissionManager->getPermissionErrors( $action, $this->user, $this->title )
		);
		$this->assertSame(
			$expectedUserCan,
			$permissionManager->userCan( $action, $this->user, $this->title )
		);
	}

	public static function provideActionPermissions() {
		// title overrides can include "protectedPermission" to override
		// $title->mTitleProtection['permission'], and "interwiki" to override
		// $title->mInterwiki, for the few cases those are needed
		yield [
			'namespace' => NS_MAIN,
			'title overrides' => [],
			'action' => 'create',
			'user permissions' => [ 'createpage' ],
			'expected permission errors' => [ [ 'titleprotected', 'Useruser', 'test' ] ],
			'user can' => false,
		];
		yield [
			'namespace' => NS_MAIN,
			'title overrides' => [ 'protectedPermission' => 'editprotected' ],
			'action' => 'create',
			'user permissions' => [ 'createpage', 'protect' ],
			'expected permission errors' => [ [ 'titleprotected', 'Useruser', 'test' ] ],
			'user can' => false,
		];
		yield [
			'namespace' => NS_MAIN,
			'title overrides' => [ 'protectedPermission' => 'editprotected' ],
			'action' => 'create',
			'user permissions' => [ 'createpage', 'editprotected' ],
			'expected permission errors' => [],
			'user can' => true,
		];
		yield [
			'namespace' => NS_MEDIA,
			'title overrides' => [],
			'action' => 'move',
			'user permissions' => [ 'move' ],
			'expected permission errors' => [ [ 'immobile-source-namespace', 'Media' ] ],
			'user can' => false,
		];
		yield [
			'namespace' => NS_HELP,
			'title overrides' => [],
			'action' => 'move',
			'user permissions' => [ 'move' ],
			'expected permission errors' => [],
			'user can' => true,
		];
		yield [
			'namespace' => NS_HELP,
			'title overrides' => [ 'interwiki' => 'no' ],
			'action' => 'move',
			'user permissions' => [ 'move' ],
			'expected permission errors' => [ [ 'immobile-source-page' ] ],
			'user can' => false,
		];
		yield [
			'namespace' => NS_MEDIA,
			'title overrides' => [],
			'action' => 'move-target',
			'user permissions' => [ 'move' ],
			'expected permission errors' => [ [ 'immobile-target-namespace', 'Media' ] ],
			'user can' => false,
		];
		yield [
			'namespace' => NS_HELP,
			'title overrides' => [],
			'action' => 'move-target',
			'user permissions' => [ 'move' ],
			'expected permission errors' => [],
			'user can' => true,
		];
		yield [
			'namespace' => NS_HELP,
			'title overrides' => [ 'interwiki' => 'no' ],
			'action' => 'move-target',
			'user permissions' => [ 'move' ],
			'expected permission errors' => [ [ 'immobile-target-page' ] ],
			'user can' => false,
		];
		yield [
			'namespace' => NS_MAIN,
			'title overrides' => [],
			'action' => 'edit',
			'user permissions' => [ 'createpage', 'edit' ],
			'expected permission errors' => [ [ 'titleprotected', 'Useruser', 'test' ] ],
			'user can' => false,
		];
		yield [
			'namespace' => NS_MAIN,
			'title overrides' => [],
			'action' => 'edit',
			'user permissions' => [ 'edit' ],
			'expected permission errors' => [ [ 'nocreate-loggedin' ] ],
			'user can' => false,
		];
	}

	public function testEditActionPermissionWithExistingPage() {
		$title = $this->getExistingTestPage( 'test page' )->getTitle();

		$permissionManager = $this->getServiceContainer()->getPermissionManager();

		$this->overrideUserPermissions( $this->user, [ 'edit' ] );

		$this->assertSame( [], $permissionManager->getPermissionErrors( 'edit', $this->user, $title ) );
		$this->assertTrue( $permissionManager->userCan( 'edit', $this->user, $title ) );
	}

	public function testAutocreatePermissionsHack() {
		$this->enableAutoCreateTempUser();
		$this->overrideConfigValue( MainConfigNames::GroupPermissions, [
			'*' => [ 'edit' => false ],
			'temp' => [ 'edit' => true, 'createpage' => true ],
		] );
		$services = $this->getServiceContainer();
		$permissionManager = $services->getPermissionManager();
		$user = $services->getUserFactory()->newAnonymous();
		$title = $this->getNonexistingTestPage()->getTitle();
		$this->assertNotEmpty(
			$permissionManager->getPermissionErrors(
				'edit',
				$user,
				$title
			)
		);
		$this->assertSame(
			[],
			$permissionManager->getPermissionErrors(
				'edit',
				$user,
				$title,
				PermissionManager::RIGOR_QUICK
			)
		);
	}

	/**
	 * @dataProvider provideTestCheckUserBlockActions
	 */
	public function testCheckUserBlockActions( $block, $restriction, $expected ) {
		$this->overrideConfigValues( [
			MainConfigNames::EmailConfirmToEdit => false,
			MainConfigNames::EnablePartialActionBlocks => true,
		] );

		if ( $restriction ) {
			$pageRestriction = new PageRestriction( 0, $this->title->getArticleID() );
			$pageRestriction->setTitle( $this->title );
			$block->setRestrictions( [ $pageRestriction ] );
		}

		$user = $this->createUserWithBlock( $block );

		$this->overrideUserPermissions( $user, [
			'createpage',
			'edit',
			'move',
			'rollback',
			'patrol',
			'upload',
			'purge'
		] );

		$permissionManager = $this->getServiceContainer()->getPermissionManager();

		// Check that user is blocked or unblocked from specific actions using getPermissionErrors
		foreach ( $expected as $action => $blocked ) {
			$expectedErrorCount = $blocked ? 1 : 0;
			$this->assertCount(
				$expectedErrorCount,
				$permissionManager->getPermissionErrors(
					$action,
					$user,
					$this->title
				),
				"Number of permission errors for action \"$action\""
			);
		}

		// Check that user is blocked or unblocked from specific actions using getApplicableBlock
		foreach ( $expected as $action => $blocked ) {
			$this->assertSame(
				$blocked,
				$permissionManager->getApplicableBlock(
					$action,
					$user,
					PermissionManager::RIGOR_FULL,
					$this->title,
					null
				) !== null,
				"Block returned by getApplicableBlock() for action \"$action\""
			);
		}

		// quickUserCan should ignore user blocks
		$this->assertTrue(
			$permissionManager->quickUserCan( 'move-target', $this->user, $this->title )
		);
	}

	/**
	 * Create a user that is blocked in global state
	 *
	 * @param array $options $block
	 * @return User
	 */
	private function createUserWithBlock( $options = [] ) {
		$newUser = new User();
		$newUser->setId( 12345 );
		$newUser->setName( 'BlockedUser' );

		$this->installMockBlockManager( $options, $newUser );
		return $newUser;
	}

	/**
	 * Regression test for T348451
	 */
	public function testGetApplicableBlockForSpecialPage() {
		$block = new DatabaseBlock( [
			'address' => '127.0.8.1',
			'by' => new UserIdentityValue( 100, 'TestUser' ),
			'auto' => true,
		] );

		$user = $this->createUserWithBlock( $block );
		$title = Title::makeTitle( NS_SPECIAL, 'Blankpage' );

		$this->overrideUserPermissions( $user, [
			'createpage',
			'edit',
		] );

		$permissionManager = $this->getServiceContainer()->getPermissionManager();

		// The block is applicable even if the target page is a special page
		// for which we cannot instantiate an Action object.
		$this->assertSame(
			$block,
			$permissionManager->getApplicableBlock(
				'edit',
				$user,
				PermissionManager::RIGOR_FULL,
				$title,
				null
			)
		);
	}

	/**
	 * Regression test for T350202
	 */
	public function testGetApplicableBlockForImplicitRight() {
		$block = new DatabaseBlock( [
			'address' => '127.0.8.1',
			'by' => new UserIdentityValue( 100, 'TestUser' ),
			'auto' => true,
		] );

		$user = $this->createUserWithBlock( $block );
		$title = Title::makeTitle( NS_MAIN, 'Test' );

		$permissionManager = $this->getServiceContainer()->getPermissionManager();

		// The block is not applicable because the purge permission is implicit.
		$this->assertNull(
			$permissionManager->getApplicableBlock(
				'purge',
				$user,
				PermissionManager::RIGOR_FULL,
				$title,
				null
			)
		);
	}

	public static function provideTestCheckUserBlockActions() {
		return [
			'Sitewide autoblock' => [
				new DatabaseBlock( [
					'address' => '127.0.8.1',
					'by' => new UserIdentityValue( 100, 'TestUser' ),
					'auto' => true,
				] ),
				false,
				[
					'edit' => true,
					'move-target' => true,
					'rollback' => true,
					'patrol' => true,
					'upload' => true,
					'purge' => false,
				]
			],
			'Sitewide block' => [
				new DatabaseBlock( [
					'address' => '127.0.8.1',
					'by' => new UserIdentityValue( 100, 'TestUser' ),
				] ),
				false,
				[
					'edit' => true,
					'move-target' => true,
					'rollback' => true,
					'patrol' => true,
					'upload' => true,
					'purge' => false,
				]
			],
			'Partial block without restriction against this page' => [
				new DatabaseBlock( [
					'address' => '127.0.8.1',
					'by' => new UserIdentityValue( 100, 'TestUser' ),
					'sitewide' => false,
				] ),
				false,
				[
					'edit' => false,
					'move-target' => false,
					'rollback' => false,
					'patrol' => false,
					'upload' => false,
					'purge' => false,
				]
			],
			'Partial block with restriction against this page' => [
				new DatabaseBlock( [
					'address' => '127.0.8.1',
					'by' => new UserIdentityValue( 100, 'TestUser' ),
					'sitewide' => false,
				] ),
				true,
				[
					'edit' => true,
					'move-target' => true,
					'rollback' => true,
					'patrol' => true,
					'upload' => false,
					'purge' => false,
				]
			],
			'Partial block with action restriction against uploading' => [
				( new DatabaseBlock( [
					'address' => '127.0.8.1',
					'by' => UserIdentityValue::newRegistered( 100, 'Test' ),
					'sitewide' => false,
				] ) )->setRestrictions( [
					new ActionRestriction( 0, BlockActionInfo::ACTION_UPLOAD )
				] ),
				false,
				[
					'edit' => false,
					'move-target' => false,
					'rollback' => false,
					'patrol' => false,
					'upload' => true,
					'purge' => false,
				]
			],
			'System block' => [
				new SystemBlock( [
					'address' => '127.0.8.1',
					'by' => 100,
					'systemBlock' => 'test',
				] ),
				false,
				[
					'edit' => true,
					'move-target' => true,
					'rollback' => true,
					'patrol' => true,
					'upload' => true,
					'purge' => false,
				]
			],
			'No block' => [
				null,
				false,
				[
					'edit' => false,
					'move-target' => false,
					'rollback' => false,
					'patrol' => false,
					'upload' => false,
					'purge' => false,
				]
			]
		];
	}

	/**
	 * A test of the filter() calls in getApplicableBlock()
	 */
	public function testGetApplicableBlockCompositeFilter() {
		$this->overrideConfigValues( [
			MainConfigNames::EnablePartialActionBlocks => true,
		] );
		$blockOptions = [
			'address' => '127.0.8.1',
			'by' => UserIdentityValue::newRegistered( 100, 'Test' ),
			'sitewide' => false,
		];

		$uploadBlock = new DatabaseBlock( $blockOptions );
		$uploadBlock->setRestrictions( [
			new ActionRestriction( 0, BlockActionInfo::ACTION_UPLOAD )
		] );

		$emailBlock = new DatabaseBlock(
			[
				'blockEmail' => true,
				'sitewide' => true
			] + $blockOptions
		);

		$page = $this->getExistingTestPage();
		$page2 = $this->getExistingTestPage( __FUNCTION__ . ' page2' );
		$pageBlock = new DatabaseBlock( $blockOptions );
		$pageBlock->setRestrictions( [
			new PageRestriction( 0, $page->getId() )
		] );

		$compositeBlock = new CompositeBlock( [
			'originalBlocks' => [
				$uploadBlock,
				$emailBlock,
				$pageBlock
			]
		] );
		$user = $this->createUserWithBlock( $compositeBlock );
		$permissionManager = $this->getServiceContainer()->getPermissionManager();

		// The email block, being a sitewide block with an additional
		// blockEmail option, also blocks upload.
		// assertEquals() gives nicer failure messages than assertSame().
		$this->assertEquals(
			[ $uploadBlock, $emailBlock ],
			$permissionManager->getApplicableBlock(
				'upload', $user, PermissionManager::RIGOR_FULL, null, null
			)->toArray()
		);

		// Emailing is only blocked by the email block
		$this->assertEquals(
			[ $emailBlock ],
			$permissionManager->getApplicableBlock(
				'sendemail', $user, PermissionManager::RIGOR_FULL, null, null
			)->toArray()
		);

		// As for upload, the email block applies to sitewide editing
		$this->assertEquals(
			[ $emailBlock, $pageBlock ],
			$permissionManager->getApplicableBlock(
				'edit', $user, PermissionManager::RIGOR_FULL, $page->getTitle(), null
			)->toArray()
		);

		// Test filtering by page -- we use $page2 so $pageBlock does not apply
		$this->assertEquals(
			[ $emailBlock ],
			$permissionManager->getApplicableBlock(
				'edit', $user, PermissionManager::RIGOR_FULL, $page2->getTitle(), null
			)->toArray()
		);
	}

	/**
	 * @dataProvider provideTestCheckUserBlockMessage
	 */
	public function testCheckUserBlockMessage( $blockType, $blockParams, $restriction, $expected ) {
		$this->overrideConfigValue(
			MainConfigNames::EmailConfirmToEdit, false
		);
		$block = new $blockType( array_merge( [
			'address' => '127.0.8.1',
			'by' => $this->user,
			'reason' => 'Test reason',
			'timestamp' => '20000101000000',
			'expiry' => 0,
		], $blockParams ) );

		if ( $restriction ) {
			$pageRestriction = new PageRestriction( 0, $this->title->getArticleID() );
			$pageRestriction->setTitle( $this->title );
			$block->setRestrictions( [ $pageRestriction ] );
		}

		$user = $this->createUserWithBlock( $block );
		$this->overrideUserPermissions( $user, [ 'edit', 'createpage' ] );

		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		$errors = $permissionManager->getPermissionErrors(
			'edit',
			$user,
			$this->title
		);

		$this->assertEquals(
			$expected['message'],
			$errors[0][0]
		);
	}

	public static function provideTestCheckUserBlockMessage() {
		return [
			'Sitewide autoblock' => [
				DatabaseBlock::class,
				[ 'auto' => true ],
				false,
				[
					'message' => 'autoblockedtext',
				],
			],
			'Sitewide block' => [
				DatabaseBlock::class,
				[],
				false,
				[
					'message' => 'blockedtext',
				],
			],
			'Partial block with restriction against this page' => [
				DatabaseBlock::class,
				[ 'sitewide' => false ],
				true,
				[
					'message' => 'blockedtext-partial',
				],
			],
			'System block' => [
				SystemBlock::class,
				[ 'systemBlock' => 'test' ],
				false,
				[
					'message' => 'systemblockedtext',
				],
			],
		];
	}

	/**
	 * @dataProvider provideTestCheckUserBlockEmailConfirmToEdit
	 */
	public function testCheckUserBlockEmailConfirmToEdit( $emailConfirmToEdit, $assertion ) {
		$this->overrideConfigValues( [
			MainConfigNames::EmailConfirmToEdit => $emailConfirmToEdit,
			MainConfigNames::EmailAuthentication => true,
		] );

		$this->overrideUserPermissions( $this->user, [
			'edit',
			'move',
		] );

		$permissionManager = $this->getServiceContainer()->getPermissionManager();

		$this->$assertion( [ 'confirmedittext' ],
			$permissionManager->getPermissionErrors( 'edit', $this->user, $this->title ) );

		// $wgEmailConfirmToEdit only applies to 'edit' action
		$this->assertEquals( [],
			$permissionManager->getPermissionErrors( 'move-target', $this->user, $this->title ) );
	}

	public static function provideTestCheckUserBlockEmailConfirmToEdit() {
		return [
			'User must confirm email to edit' => [
				true,
				'assertContains',
			],
			'User may edit without confirming email' => [
				false,
				'assertNotContains',
			],
		];
	}

	/**
	 * Determine that the passed-in permission does not get mixed up with
	 * an action of the same name.
	 */
	public function testCheckUserBlockActionPermission() {
		$tester = $this->createMock( Action::class );
		$tester->method( 'getName' )
			->willReturn( 'tester' );
		$tester->method( 'getRestriction' )
			->willReturn( 'test' );
		$tester->method( 'requiresUnblock' )
			->willReturn( false );
		$tester->method( 'requiresWrite' )
			->willReturn( false );
		$tester->method( 'needsReadRights' )
			->willReturn( false );

		$this->overrideConfigValues( [
			MainConfigNames::Actions => [
				'tester' => $tester,
			],
			MainConfigNames::GroupPermissions => [
				'*' => [
					'tester' => true,
				],
			],
		] );

		$user = $this->createUserWithBlock( new DatabaseBlock( [
			'address' => '127.0.8.1',
			'by' => $this->user,
		] ) );
		$this->assertCount( 1, $this->getServiceContainer()->getPermissionManager()
			->getPermissionErrors( 'tester', $user, $this->title )
		);
	}

	public function testBlockInstanceCache() {
		// First, check the user isn't blocked
		$user = $this->getMutableTestUser()->getUser();
		$ut = Title::makeTitle( NS_USER_TALK, $user->getName() );
		$this->assertNull( $user->getBlock( false ) );
		$this->assertFalse( $this->getServiceContainer()->getPermissionManager()
			->isBlockedFrom( $user, $ut ) );

		// Block the user
		$blocker = $this->getTestSysop()->getUser();
		$block = new DatabaseBlock( [
			'hideName' => true,
			'allowUsertalk' => false,
			'reason' => 'Because',
		] );
		$block->setTarget( $user );
		$block->setBlocker( $blocker );
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$res = $blockStore->insertBlock( $block );
		$this->assertTrue( (bool)$res['id'], 'Failed to insert block' );

		// Clear cache and confirm it loaded the block properly
		$user->clearInstanceCache();
		$this->assertInstanceOf( DatabaseBlock::class, $user->getBlock( false ) );
		$this->assertTrue( $this->getServiceContainer()->getPermissionManager()
			->isBlockedFrom( $user, $ut ) );

		// Unblock
		$blockStore->deleteBlock( $block );

		// Clear cache and confirm it loaded the not-blocked properly
		$user->clearInstanceCache();
		$this->assertNull( $user->getBlock( false ) );
		$this->assertFalse( $this->getServiceContainer()->getPermissionManager()
			->isBlockedFrom( $user, $ut ) );
	}

	/**
	 * @dataProvider provideIsBlockedFrom
	 * @param string|null $title Title to test.
	 * @param bool $expect Expected result from User::isBlockedFrom()
	 * @param array $options Additional test options:
	 *  - 'blockAllowsUTEdit': (bool, default true) Value for $wgBlockAllowsUTEdit
	 *  - 'allowUsertalk': (bool, default false) Passed to DatabaseBlock::__construct()
	 *  - 'pageRestrictions': (array|null) If non-empty, page restriction titles for the block.
	 */
	public function testIsBlockedFrom( $title, $expect, array $options = [] ) {
		$this->overrideConfigValue(
			MainConfigNames::BlockAllowsUTEdit,
			$options['blockAllowsUTEdit'] ?? true
		);

		$user = $this->getTestUser()->getUser();

		if ( $title === self::USER_TALK_PAGE ) {
			$title = $user->getTalkPage();
		} else {
			$title = Title::newFromText( $title );
		}

		$restrictions = [];
		foreach ( $options['pageRestrictions'] ?? [] as $pagestr ) {
			$page = $this->getExistingTestPage(
				$pagestr === self::USER_TALK_PAGE ? $user->getTalkPage() : $pagestr
			);
			$restrictions[] = new PageRestriction( 0, $page->getId() );
		}
		foreach ( $options['namespaceRestrictions'] ?? [] as $ns ) {
			$restrictions[] = new NamespaceRestriction( 0, $ns );
		}

		$block = new DatabaseBlock( [
			'expiry' => wfTimestamp( TS_MW, wfTimestamp() + ( 40 * 60 * 60 ) ),
			'allowUsertalk' => $options['allowUsertalk'] ?? false,
			'sitewide' => !$restrictions,
		] );
		$block->setTarget( $user );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		if ( $restrictions ) {
			$block->setRestrictions( $restrictions );
		}
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$blockStore->insertBlock( $block );

		$this->assertSame( $expect, $this->getServiceContainer()->getPermissionManager()
			->isBlockedFrom( $user, $title ) );
	}

	public static function provideIsBlockedFrom() {
		return [
			'Sitewide block, basic operation' => [ 'Test page', true ],
			'Sitewide block, not allowing user talk' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => false,
				]
			],
			'Sitewide block, allowing user talk' => [
				self::USER_TALK_PAGE, false, [
					'allowUsertalk' => true,
				]
			],
			'Sitewide block, allowing user talk but $wgBlockAllowsUTEdit is false' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => true,
					'blockAllowsUTEdit' => false,
				]
			],
			'Partial block, blocking the page' => [
				'Test page', true, [
					'pageRestrictions' => [ 'Test page' ],
				]
			],
			'Partial block, not blocking the page' => [
				'Test page 2', false, [
					'pageRestrictions' => [ 'Test page' ],
				]
			],
			'Partial block, not allowing user talk but user talk page is not blocked' => [
				self::USER_TALK_PAGE, false, [
					'allowUsertalk' => false,
					'pageRestrictions' => [ 'Test page' ],
				]
			],
			'Partial block, allowing user talk but user talk page is blocked' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => true,
					'pageRestrictions' => [ self::USER_TALK_PAGE ],
				]
			],
			'Partial block, user talk page is not blocked but $wgBlockAllowsUTEdit is false' => [
				self::USER_TALK_PAGE, false, [
					'allowUsertalk' => false,
					'pageRestrictions' => [ 'Test page' ],
					'blockAllowsUTEdit' => false,
				]
			],
			'Partial block, user talk page is blocked and $wgBlockAllowsUTEdit is false' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => true,
					'pageRestrictions' => [ self::USER_TALK_PAGE ],
					'blockAllowsUTEdit' => false,
				]
			],
			'Partial user talk namespace block, not allowing user talk' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => false,
					'namespaceRestrictions' => [ NS_USER_TALK ],
				]
			],
			'Partial user talk namespace block, allowing user talk' => [
				self::USER_TALK_PAGE, false, [
					'allowUsertalk' => true,
					'namespaceRestrictions' => [ NS_USER_TALK ],
				]
			],
			'Partial user talk namespace block, where $wgBlockAllowsUTEdit is false' => [
				self::USER_TALK_PAGE, true, [
					'allowUsertalk' => true,
					'namespaceRestrictions' => [ NS_USER_TALK ],
					'blockAllowsUTEdit' => false,
				]
			],
		];
	}

	public function testGetUserPermissions() {
		$user = $this->getTestUser( [ 'unittesters' ] )->getUser();
		$rights = $this->getServiceContainer()->getPermissionManager()
			->getUserPermissions( $user );
		$this->assertContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	public function testGetUserPermissionsHooks() {
		$user = $this->getTestUser( [ 'unittesters', 'testwriters' ] )->getUser();
		$userWrapper = TestingAccessWrapper::newFromObject( $user );

		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		$rights = $permissionManager->getUserPermissions( $user );
		$this->assertContains( 'test', $rights );
		$this->assertContains( 'runtest', $rights );
		$this->assertContains( 'writetest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );

		// Add a hook manipluating the rights
		$this->setTemporaryHook( 'UserGetRights', static function ( $user, &$rights ) {
			$rights[] = 'nukeworld';
			$rights = array_diff( $rights, [ 'writetest' ] );
		} );

		$permissionManager->invalidateUsersRightsCache( $user );
		$rights = $permissionManager->getUserPermissions( $user );
		$this->assertContains( 'test', $rights );
		$this->assertContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertContains( 'nukeworld', $rights );

		// Add a Session that limits rights. We're mocking a stdClass because the Session
		// class is final, and thus not mockable.
		$mock = $this->getMockBuilder( stdClass::class )
			->addMethods( [ 'getAllowedUserRights', 'deregisterSession', 'getSessionId' ] )
			->getMock();
		$mock->method( 'getAllowedUserRights' )->willReturn( [ 'test', 'writetest' ] );
		$mock->method( 'getSessionId' )->willReturn(
			new SessionId( str_repeat( 'X', 32 ) )
		);
		$session = TestUtils::getDummySession( $mock );
		$mockRequest = $this->getMockBuilder( FauxRequest::class )
			->onlyMethods( [ 'getSession' ] )
			->getMock();
		$mockRequest->method( 'getSession' )->willReturn( $session );
		$userWrapper->mRequest = $mockRequest;

		$this->resetServices();
		$rights = $this->getServiceContainer()
			->getPermissionManager()
			->getUserPermissions( $user );
		$this->assertContains( 'test', $rights );
		$this->assertNotContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	public function testUserHasRight() {
		$permissionManager = $this->getServiceContainer()->getPermissionManager();

		$result = $permissionManager->userHasRight(
			$this->getTestUser( 'unittesters' )->getUser(),
			'test'
		);
		$this->assertTrue( $result, 'right was granted to group, so should be allowed' );

		$result = $permissionManager->userHasRight(
			$this->getTestUser( 'unittesters' )->getUser(),
			'limitabletest'
		);
		$this->assertTrue( $result, 'not granted, but listed as implicit' );

		$result = $permissionManager->userHasRight(
			$this->getTestUser( 'unittesters' )->getUser(),
			'mailpassword'
		);
		$this->assertTrue( $result, 'not granted, but has a limit, so should be allowed' );

		$result = $permissionManager->userHasRight(
			$this->getTestUser( 'unittesters' )->getUser(),
			'rollback'
		);
		$this->assertFalse( $result, 'not granted, has a limit but is listed as available, so should not be allowed' );

		$result = $permissionManager->userHasRight(
			$this->getTestUser( 'formertesters' )->getUser(),
			'runtest'
		);
		$this->assertFalse( $result, 'not granted, should not be allowed' );

		$result = $permissionManager->userHasRight(
			$this->getTestUser( 'formertesters' )->getUser(),
			''
		);
		$this->assertTrue( $result, 'empty action should always be granted' );
	}

	public function testIsEveryoneAllowed() {
		$permissionManager = $this->getServiceContainer()->getPermissionManager();

		$result = $permissionManager->isEveryoneAllowed( 'editmyoptions' );
		$this->assertTrue( $result );

		$result = $permissionManager->isEveryoneAllowed( 'test' );
		$this->assertFalse( $result );
	}

	public function testAddTemporaryUserRights() {
		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		$this->overrideUserPermissions( $this->user, [ 'read', 'edit' ] );

		$this->assertEquals( [ 'read', 'edit' ], $permissionManager->getUserPermissions( $this->user ) );
		$this->assertFalse( $permissionManager->userHasRight( $this->user, 'move' ) );

		$scope = $permissionManager->addTemporaryUserRights( $this->user, [ 'move', 'delete' ] );
		$this->assertEquals( [ 'read', 'edit', 'move', 'delete' ],
			$permissionManager->getUserPermissions( $this->user ) );
		$this->assertTrue( $permissionManager->userHasRight( $this->user, 'move' ) );

		$scope2 = $permissionManager->addTemporaryUserRights( $this->user, [ 'delete', 'upload' ] );
		$this->assertEquals( [ 'read', 'edit', 'move', 'delete', 'upload' ],
			$permissionManager->getUserPermissions( $this->user ) );

		ScopedCallback::consume( $scope );
		$this->assertEquals( [ 'read', 'edit', 'delete', 'upload' ],
			$permissionManager->getUserPermissions( $this->user ) );
		ScopedCallback::consume( $scope2 );
		$this->assertEquals( [ 'read', 'edit' ],
			$permissionManager->getUserPermissions( $this->user ) );
		$this->assertFalse( $permissionManager->userHasRight( $this->user, 'move' ) );

		( function () use ( $permissionManager ) {
			$scope = $permissionManager->addTemporaryUserRights( $this->user, 'move' );
			$this->assertTrue( $permissionManager->userHasRight( $this->user, 'move' ) );
		} )();
		$this->assertFalse( $permissionManager->userHasRight( $this->user, 'move' ) );
	}

	public static function provideGetRestrictionLevels() {
		return [
			'No namespace restriction' => [ [ '', 'autoconfirmed', 'sysop' ], NS_TALK ],
			'Restricted to autoconfirmed' => [ [ '', 'sysop' ], NS_MAIN ],
			'Restricted to sysop' => [ [ '' ], NS_USER ],
			'Restricted to someone in two groups' => [ [ '', 'sysop' ], 101 ],
			'No special permissions' => [
				[ '' ],
				NS_TALK,
				[]
			],
			'autoconfirmed' => [
				[ '', 'autoconfirmed' ],
				NS_TALK,
				[ 'autoconfirmed' ]
			],
			'autoconfirmed revoked' => [
				[ '' ],
				NS_TALK,
				[ 'autoconfirmed', 'noeditsemiprotected' ]
			],
			'sysop' => [
				[ '', 'autoconfirmed', 'sysop' ],
				NS_TALK,
				[ 'sysop' ]
			],
			'sysop with autoconfirmed revoked (a bit silly)' => [
				[ '', 'sysop' ],
				NS_TALK,
				[ 'sysop', 'noeditsemiprotected' ]
			],
		];
	}

	/**
	 * @dataProvider provideGetRestrictionLevels
	 */
	public function testGetRestrictionLevels( array $expected, $ns, ?array $userGroups = null ) {
		$this->overrideConfigValues( [
			MainConfigNames::GroupPermissions => [
				'*' => [ 'edit' => true ],
				'autoconfirmed' => [ 'editsemiprotected' => true ],
				'sysop' => [
					'editsemiprotected' => true,
					'editprotected' => true,
				],
				'privileged' => [ 'privileged' => true ],
			],
			MainConfigNames::RevokePermissions => [
				'noeditsemiprotected' => [ 'editsemiprotected' => true ],
			],
			MainConfigNames::NamespaceProtection => [
				NS_MAIN => 'autoconfirmed',
				NS_USER => 'sysop',
				101 => [ 'editsemiprotected', 'privileged' ],
			],
			MainConfigNames::RestrictionLevels => [ '', 'autoconfirmed', 'sysop' ],
			MainConfigNames::Autopromote => []
		] );
		$user = $userGroups === null ? null : $this->getTestUser( $userGroups )->getUser();
		$this->assertSame( $expected, $this->getServiceContainer()
			->getPermissionManager()
			->getNamespaceRestrictionLevels( $ns, $user ) );
	}

	public function testGetAllPermissions() {
		$this->overrideConfigValue( MainConfigNames::AvailableRights, [ 'test_right' ] );
		$this->assertContains(
			'test_right',
			$this->getServiceContainer()
				->getPermissionManager()
				->getAllPermissions()
		);
	}

	public function testAnonPermissionsNotClash() {
		$user1 = User::newFromName( 'User1' );
		$user2 = User::newFromName( 'User2' );
		$pm = $this->getServiceContainer()->getPermissionManager();
		$pm->overrideUserRightsForTesting( $user2, [] );
		$this->assertNotSame( $pm->getUserPermissions( $user1 ), $pm->getUserPermissions( $user2 ) );
	}

	public function testAnonPermissionsNotClashOneRegistered() {
		$user1 = User::newFromName( 'User1' );
		$user2 = $this->getTestSysop()->getUser();
		$pm = $this->getServiceContainer()->getPermissionManager();
		$this->assertNotSame( $pm->getUserPermissions( $user1 ), $pm->getUserPermissions( $user2 ) );
	}

	/**
	 * Test delete-redirect checks for Special:MovePage
	 */
	public function testDeleteRedirect() {
		$this->editPage( 'ExistentRedirect3', '#REDIRECT [[Existent]]' );
		$page = Title::makeTitle( NS_MAIN, 'ExistentRedirect3' );
		$pm = $this->getServiceContainer()->getPermissionManager();

		$user = $this->getTestUser()->getUser();

		$this->assertFalse( $pm->quickUserCan( 'delete-redirect', $user, $page ) );

		$pm->overrideUserRightsForTesting( $user, 'delete-redirect' );

		$this->assertTrue( $pm->quickUserCan( 'delete-redirect', $user, $page ) );
		$this->assertArrayEquals( [], $pm->getPermissionErrors( 'delete-redirect', $user, $page ) );
	}

	/**
	 * Ensure normal admins can view deleted javascript, but not restore it
	 * See T202989
	 */
	public function testSysopInterfaceAdminRights() {
		$interfaceAdmin = $this->getTestUser( [ 'interface-admin', 'sysop' ] )->getUser();
		$admin = $this->getTestSysop()->getUser();

		$permManager = $this->getServiceContainer()->getPermissionManager();
		$userJs = Title::makeTitle( NS_USER, 'Example/common.js' );

		$this->assertTrue( $permManager->userCan( 'delete', $admin, $userJs ) );
		$this->assertTrue( $permManager->userCan( 'delete', $interfaceAdmin, $userJs ) );
		$this->assertTrue( $permManager->userCan( 'deletedhistory', $admin, $userJs ) );
		$this->assertTrue( $permManager->userCan( 'deletedhistory', $interfaceAdmin, $userJs ) );
		$this->assertTrue( $permManager->userCan( 'deletedtext', $admin, $userJs ) );
		$this->assertTrue( $permManager->userCan( 'deletedtext', $interfaceAdmin, $userJs ) );
		$this->assertFalse( $permManager->userCan( 'undelete', $admin, $userJs ) );
		$this->assertTrue( $permManager->userCan( 'undelete', $interfaceAdmin, $userJs ) );
	}

	/**
	 * Ensure specific users can view deleted contents regardless of Namespace
	 * Protection, but not restore it
	 * See T362536
	 *
	 * @dataProvider provideDeletedViewerRights
	 */
	public function testDeletedViewerRights(
		$userGroup,
		$userPerms,
		$expectedUserCan
	) {
		$currentUser = $this->getTestUser( $userGroup )->getUser();
		$permManager = $this->getServiceContainer()->getPermissionManager();
		$targetPage = Title::makeTitle( NS_MEDIAWIKI, 'Example' );
		foreach ( $userPerms as $userPerm ) {
			$this->assertSame(
				$expectedUserCan,
				$permManager->userCan( $userPerm, $currentUser, $targetPage )
			);
		}
	}

	public static function provideDeletedViewerRights() {
		yield [
			'usergroup' => '*',
			'user permissions' => [
				'delete',
				'deletedhistory',
				'deletedtext',
				'suppressrevision',
				'undelete',
				'viewsuppressed'
			],
			'user can' => false
		];
		yield [
			'usergroup' => 'deleted-viewer',
			'user permissions' => [
				'delete',
				'suppressrevision',
				'undelete'
			],
			'user can' => false
		];
		yield [
			'usergroup' => 'deleted-viewer',
			'user permissions' => [
				'deletedhistory',
				'deletedtext',
				'viewsuppressed'
			],
			'user can' => true
		];
	}

	/**
	 * Regression test for T306358 -- proper page assertion when checking
	 * blocked status on a special page
	 */
	public function testBlockedFromNonProperPage() {
		$page = Title::makeTitle( NS_SPECIAL, 'Blankpage' );
		$pm = $this->getServiceContainer()->getPermissionManager();
		$user = $this->getMockBuilder( User::class )
			->onlyMethods( [ 'getBlock' ] )
			->getMock();
		$user->method( 'getBlock' )
			->willReturn( new DatabaseBlock( [
				'address' => '127.0.8.1',
				'by' => $this->user,
			] ) );
		$errors = $pm->getPermissionErrors( 'test', $user, $page );
		$this->assertNotEmpty( $errors );
	}

	/**
	 * Test interaction with $wgWhitelistRead.
	 *
	 * @dataProvider provideWhitelistRead
	 */
	public function testWhitelistRead( array $whitelist, string $title, bool $shouldAllow ) {
		$this->overrideConfigValues( [
			MainConfigNames::LanguageCode => 'es',
			MainConfigNames::WhitelistRead => $whitelist,
		] );
		$this->setGroupPermissions( '*', 'read', false );

		$title = Title::newFromText( $title );
		$pm = $this->getServiceContainer()->getPermissionManager();
		$errors = $pm->getPermissionErrors( 'read', new User, $title );
		if ( $shouldAllow ) {
			$this->assertSame( [], $errors );
		} else {
			$this->assertNotEmpty( $errors );
		}
	}

	public static function provideWhitelistRead() {
		yield 'no match' => [ [ 'Bar', 'Baz' ], 'Foo', false ];
		yield 'match' => [ [ 'Bar', 'Foo', 'Baz' ], 'Foo', true ];
		yield 'text form' => [ [ 'Foo bar' ], 'Foo_bar', true ];
		yield 'dbkey form' => [ [ 'Foo_bar' ], 'Foo bar', true ];
		yield 'local namespace' => [ [ 'Usuario:Foo' ], 'User:Foo', true ];
		yield 'legacy mainspace' => [ [ ':Foo' ], 'Foo', true ];
		yield 'local special' => [ [ 'Especial:Todas' ], 'Special:Allpages', true ];
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::getPermissionErrors
	 */
	public function testGetPermissionErrors_ignoreErrors() {
		$hookCallback = static function ( $title, $user, $action, &$result ) {
			$result = [
				[ 'ignore', 'param' ],
				[ 'noignore', 'param' ],
				'ignore',
				'noignore',
				new Message( 'ignore' ),
			];
			return false;
		};
		$this->setTemporaryHook( 'getUserPermissionsErrors', $hookCallback );

		$pm = $this->getServiceContainer()->getPermissionManager();
		$errors = $pm->getPermissionErrors(
			'read',
			$this->user,
			$this->title,
			$pm::RIGOR_QUICK,
			[ 'ignore' ]
		);

		$this->assertSame( [
			[ 'noignore', 'param' ],
			[ 'noignore' ],
		], $errors );
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::checkQuickPermissions
	 */
	public function testGetPermissionErrors_objectFromHookResult() {
		$msg = ApiMessage::create( 'mymessage', 'mymessagecode', [ 'mydata' => true ] );
		$this->setTemporaryHook(
			'TitleQuickPermissions',
			static function ( $hookTitle, $hookUser, $hookAction, &$errors, $doExpensiveQueries, $short ) use ( $msg ) {
				$errors[] = [ $msg ];
				return false;
			}
		);

		$pm = $this->getServiceContainer()->getPermissionManager();

		$errorsStatus = $pm->getPermissionStatus( 'create', $this->user, $this->title );
		$errorsArray = $pm->getPermissionErrors( 'create', $this->user, $this->title );

		$this->assertSame(
			[ $msg ],
			$errorsStatus->getMessages(),
			'getPermissionStatus() preserves ApiMessage objects'
		);
	}

	public function testShouldLimitPermissionsForBlockedUserWhenBlockDisablesLogin(): void {
		$this->overrideConfigValues( [
			MainConfigNames::BlockDisablesLogin => true,
			MainConfigNames::GroupPermissions => [
				'*' => [ 'edit' => true ],
				'user' => [ 'edit' => true, 'move' => true ],
				'sysop' => [ 'block' => true ],
			],
		] );

		$testUser = $this->getTestUser()->getUserIdentity();
		$this->blockUser( $testUser );

		$permissions = $this->getServiceContainer()->getPermissionManager()->getUserPermissions( $testUser );

		$this->assertSame( [ 'edit' ], $permissions );
	}

	public function testShouldLimitPermissionsForBlockedUserShouldAllowPermissionChecksInGetUserBlock(): void {
		$this->overrideConfigValues( [
			MainConfigNames::BlockDisablesLogin => true,
			MainConfigNames::GroupPermissions => [
				'*' => [ 'edit' => true ],
				'user' => [ 'edit' => true, 'move' => true ],
				'sysop' => [ 'block' => true ],
			],
		] );

		$testUser = $this->getTestUser()->getUserIdentity();
		$hookRan = false;

		$this->setTemporaryHook(
			'GetUserBlock',
			function ( UserIdentity $user ) use ( $testUser, &$hookRan ): void {
				if ( $user->equals( $testUser ) ) {
					// Trigger an arbitrary permissions check to verify that they do not cause an infinite loop
					// when BlockDisablesLogin = true (T384197).
					$this->getServiceContainer()->getPermissionManager()
						->userHasRight( $user, 'test' );

					$hookRan = true;
				}
			}
		);

		$testUser = $this->getTestUser()->getUserIdentity();
		$this->blockUser( $testUser );

		$permissions = $this->getServiceContainer()->getPermissionManager()->getUserPermissions( $testUser );

		$this->assertSame( [ 'edit' ], $permissions );
		$this->assertTrue( $hookRan );
	}

	/**
	 * Convenience function to block a given user.
	 * @param UserIdentity $user
	 * @return void
	 */
	private function blockUser( UserIdentity $user ): void {
		$status = $this->getServiceContainer()
			->getBlockUserFactory()
			->newBlockUser( $user, $this->getTestSysop()->getAuthority(), 'infinity' )
			->placeBlock();

		$this->assertStatusGood( $status );
	}
}
