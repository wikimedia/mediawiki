<?php

namespace MediaWiki\Tests\Integration\Permissions;

use Action;
use ContentHandler;
use FauxRequest;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\SystemBlock;
use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Session\SessionId;
use MediaWiki\Session\TestUtils;
use MediaWiki\User\UserIdentityValue;
use MediaWikiLangTestCase;
use Message;
use RequestContext;
use stdClass;
use TestAllServiceOptionsUsed;
use Title;
use User;
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

	/** @var string */
	protected $userName;
	/** @var Title */
	protected $title;
	/** @var User */
	protected $user;
	/** @var User */
	protected $anonUser;
	/** @var User */
	protected $userUser;
	/** @var User */
	protected $altUser;

	private const USER_TALK_PAGE = '<user talk page>';

	protected function setUp(): void {
		parent::setUp();

		$localZone = 'UTC';
		$localOffset = date( 'Z' ) / 60;

		$this->overrideConfigValues( [
			MainConfigNames::Localtimezone => $localZone,
			MainConfigNames::LocalTZoffset => $localOffset,
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

	public function provideSpecialsAndNSPermissions() {
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
				], [
					"bogus" => [ 'bogus', "sysop", "protect", "" ],
				]
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

	public function provideActionPermissions() {
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

		$this->assertEmpty( $permissionManager->getPermissionErrors( 'edit', $this->user, $title ) );
		$this->assertTrue( $permissionManager->userCan( 'edit', $this->user, $title ) );
	}

	public function testAutocreatePermissionsHack() {
		$this->overrideConfigValues( [
			MainConfigNames::AutoCreateTempUser => [
				'enabled' => true,
				'actions' => [ 'edit' ],
				'serialProvider' => [ 'type' => 'local' ],
				'serialMapping' => [ 'type' => 'plain-numeric' ],
				'matchPattern' => '*Unregistered $1',
				'genPattern' => '*Unregistered $1'
			],
			MainConfigNames::GroupPermissions => [
				'*' => [ 'edit' => false ],
				'user' => [ 'edit' => true, 'createpage' => true ],
			]
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
		$this->assertEmpty(
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

		$user = $this->getMockBuilder( User::class )
			->onlyMethods( [ 'getBlock' ] )
			->getMock();
		$user->method( 'getBlock' )
			->willReturn( $block );

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

		// Check that user is blocked or unblocked from specific actions
		foreach ( $expected as $action => $blocked ) {
			$expectedErrorCount = $blocked ? 1 : 0;
			$this->assertCount(
				$expectedErrorCount,
				$permissionManager->getPermissionErrors(
					$action,
					$user,
					$this->title
				)
			);
		}

		// quickUserCan should ignore user blocks
		$this->assertTrue(
			$permissionManager->quickUserCan( 'move-target', $this->user, $this->title )
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
					new ActionRestriction( 0, 1 )
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

		$user = $this->getMockBuilder( User::class )
			->onlyMethods( [ 'getBlock' ] )
			->getMock();
		$user->method( 'getBlock' )
			->willReturn( $block );

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

		$user = $this->getMockBuilder( User::class )
			->onlyMethods( [ 'getBlock' ] )
			->getMock();
		$user->method( 'getBlock' )
			->willReturn( new DatabaseBlock( [
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

		try {
			$this->assertSame( $expect, $this->getServiceContainer()->getPermissionManager()
				->isBlockedFrom( $user, $title ) );
		} finally {
			$blockStore->deleteBlock( $block );
		}
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

	public function testGroupPermissions() {
		$rights = $this->getServiceContainer()->getPermissionManager()
			->getGroupPermissions( [ 'unittesters' ] );
		$this->assertContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );

		$rights = $this->getServiceContainer()->getPermissionManager()
			->getGroupPermissions( [ 'unittesters', 'testwriters' ] );
		$this->assertContains( 'runtest', $rights );
		$this->assertContains( 'writetest', $rights );
		$this->assertContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	public function testRevokePermissions() {
		$rights = $this->getServiceContainer()->getPermissionManager()
			->getGroupPermissions( [ 'unittesters', 'formertesters' ] );
		$this->assertNotContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	/**
	 * @dataProvider provideGetGroupsWithPermission
	 */
	public function testGetGroupsWithPermission( $expected, $right ) {
		$result = $this->getServiceContainer()->getPermissionManager()
			->getGroupsWithPermission( $right );
		sort( $result );
		sort( $expected );

		$this->assertEquals( $expected, $result, "Groups with permission $right" );
	}

	public static function provideGetGroupsWithPermission() {
		return [
			[
				[ 'unittesters', 'testwriters' ],
				'test'
			],
			[
				[ 'unittesters' ],
				'runtest'
			],
			[
				[ 'testwriters' ],
				'writetest'
			],
			[
				[ 'testwriters' ],
				'modifytest'
			],
		];
	}

	public function testUserHasRight() {
		$permissionManager = $this->getServiceContainer()->getPermissionManager();

		$result = $permissionManager->userHasRight(
			$this->getTestUser( 'unittesters' )->getUser(),
			'test'
		);
		$this->assertTrue( $result );

		$result = $permissionManager->userHasRight(
			$this->getTestUser( 'formertesters' )->getUser(),
			'runtest'
		);
		$this->assertFalse( $result );

		$result = $permissionManager->userHasRight(
			$this->getTestUser( 'formertesters' )->getUser(),
			''
		);
		$this->assertTrue( $result );
	}

	public function testGroupHasPermission() {
		$permissionManager = $this->getServiceContainer()->getPermissionManager();

		$result = $permissionManager->groupHasPermission(
			'unittesters',
			'test'
		);
		$this->assertTrue( $result );

		$result = $permissionManager->groupHasPermission(
			'formertesters',
			'runtest'
		);
		$this->assertFalse( $result );
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

	/**
	 * Create a RevisionRecord with a single Javascript main slot.
	 * @param Title $title
	 * @param User $user
	 * @param string $text
	 * @return MutableRevisionRecord
	 */
	private function getJavascriptRevision( Title $title, User $user, $text ) {
		$content = ContentHandler::makeContent( $text, $title, CONTENT_MODEL_JAVASCRIPT );
		$revision = new MutableRevisionRecord( $title );
		$revision->setContent( 'main', $content );
		return $revision;
	}

	/**
	 * Create a RevisionRecord with a single Javascript redirect main slot.
	 * @param Title $title
	 * @param Title $redirectTargetTitle
	 * @param User $user
	 * @return MutableRevisionRecord
	 */
	private function getJavascriptRedirectRevision(
		Title $title, Title $redirectTargetTitle, User $user
	) {
		$content = $this->getServiceContainer()->getContentHandlerFactory()
			->getContentHandler( CONTENT_MODEL_JAVASCRIPT )
			->makeRedirectContent( $redirectTargetTitle );
		$revision = new MutableRevisionRecord( $title );
		$revision->setContent( 'main', $content );
		return $revision;
	}

	public function provideGetRestrictionLevels() {
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
	public function testGetRestrictionLevels( array $expected, $ns, array $userGroups = null ) {
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
	 * Enuser normal admins can view deleted javascript, but not restore it
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
		$this->overrideConfigValue( MainConfigNames::WhitelistRead, $whitelist );
		$this->setGroupPermissions( '*', 'read', false );

		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'es' );

		$title = Title::newFromText( $title );
		$pm = $this->getServiceContainer()->getPermissionManager();
		$errors = $pm->getPermissionErrors( 'read', new User, $title );
		if ( $shouldAllow ) {
			$this->assertSame( [], $errors );
		} else {
			$this->assertNotEmpty( $errors );
		}
	}

	public function provideWhitelistRead() {
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
			'noignore',
		], $errors );
	}
}
