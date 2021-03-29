<?php

namespace MediaWiki\Tests\Integration\Permissions;

use Action;
use ContentHandler;
use FauxRequest;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\SystemBlock;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Session\SessionId;
use MediaWiki\Session\TestUtils;
use MediaWikiLangTestCase;
use RequestContext;
use stdClass;
use TestAllServiceOptionsUsed;
use Title;
use User;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 *
 * See \MediaWiki\Tests\Unit\Permissions\PermissionManagerTest
 * for unit tests
 *
 * @covers \MediaWiki\Permissions\PermissionManager
 */
class PermissionManagerTest extends MediaWikiLangTestCase {
	use TestAllServiceOptionsUsed;

	/**
	 * @var string
	 */
	protected $userName, $altUserName;

	/**
	 * @var Title
	 */
	protected $title;

	/**
	 * @var User
	 */
	protected $user, $anonUser, $userUser, $altUser;

	/** Constant for self::testIsBlockedFrom */
	private const USER_TALK_PAGE = '<user talk page>';

	protected function setUp() : void {
		parent::setUp();

		$localZone = 'UTC';
		$localOffset = date( 'Z' ) / 60;

		$this->setMwGlobals( [
			'wgLocaltimezone' => $localZone,
			'wgLocalTZoffset' => $localOffset,
			'wgNamespaceProtection' => [
				NS_MEDIAWIKI => 'editinterface',
			],
			'wgRevokePermissions' => [
				'formertesters' => [
					'runtest' => true
				]
			],
			'wgAvailableRights' => [
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
		$this->altUserName = 'Altuseruser';
		date_default_timezone_set( $localZone );

		$this->title = Title::makeTitle( NS_MAIN, "Main Page" );
		if ( !isset( $this->userUser ) || !( $this->userUser instanceof User ) ) {
			$this->userUser = User::newFromName( $this->userName );

			if ( !$this->userUser->getId() ) {
				$this->userUser = User::createNew( $this->userName, [
					"email" => "test@example.com",
					"real_name" => "Test User" ] );
				$this->userUser->load();
			}

			$this->altUser = User::newFromName( $this->altUserName );
			if ( !$this->altUser->getId() ) {
				$this->altUser = User::createNew( $this->altUserName, [
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
	 * @covers MediaWiki\Permissions\PermissionManager::checkSpecialsAndNSPermissions
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

		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

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

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::checkCascadingSourcesRestrictions
	 */
	public function testCascadingSourcesRestrictions() {
		$this->setTitle( NS_MAIN, "test page" );
		$this->overrideUserPermissions( $this->user, [ "edit", "bogus" ] );

		$this->title->mCascadeSources = [
			Title::makeTitle( NS_MAIN, "Bogus" ),
			Title::makeTitle( NS_MAIN, "UnBogus" )
		];
		$this->title->mCascadingRestrictions = [
			"bogus" => [ 'bogus', "sysop", "protect", "" ]
		];

		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		$this->assertFalse( $permissionManager->userCan( 'bogus', $this->user, $this->title ) );
		$this->assertEquals( [
			[ "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n", 'bogus' ],
			[ "cascadeprotected", 2, "* [[:Bogus]]\n* [[:UnBogus]]\n", 'bogus' ],
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
	 * @covers \MediaWiki\Permissions\PermissionManager::checkActionPermissions
	 */
	public function testActionPermissions(
		$namespace,
		$titleOverrides,
		$action,
		$userPerms,
		$expectedPermErrors,
		$expectedUserCan
	) {
		$this->setTitle( $namespace, "test page" );
		$this->title->mTitleProtection['permission'] = '';
		$this->title->mTitleProtection['user'] = $this->user->getId();
		$this->title->mTitleProtection['expiry'] = 'infinity';
		$this->title->mTitleProtection['reason'] = 'test';
		$this->title->mCascadeRestriction = false;
		$this->title->mRestrictionsLoaded = true;

		if ( isset( $titleOverrides['protectedPermission' ] ) ) {
			$this->title->mTitleProtection['permission'] = $titleOverrides['protectedPermission'];
		}
		if ( isset( $titleOverrides['interwiki'] ) ) {
			$this->title->mInterwiki = $titleOverrides['interwiki'];
		}

		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		$this->overrideUserPermissions( $this->user, $userPerms );

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
	}

	/**
	 * @dataProvider provideTestCheckUserBlockActions
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserBlock
	 */
	public function testCheckUserBlockActions( $block, $restriction, $expected ) {
		$this->setMwGlobals( [
			'wgEmailConfirmToEdit' => false,
		] );

		if ( $restriction ) {
			$pageRestriction = new PageRestriction( 0, $this->title->getArticleID() );
			$pageRestriction->setTitle( $this->title );
			$block->setRestrictions( [ $pageRestriction ] );
		}

		$user = $this->getMockBuilder( User::class )
			->setMethods( [ 'getBlock' ] )
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

		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

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
					'by' => 100,
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
					'by' => 100,
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
					'by' => 100,
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
					'by' => 100,
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
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserBlock
	 */
	public function testCheckUserBlockMessage( $blockType, $blockParams, $restriction, $expected ) {
		$this->setMwGlobals( [
			'wgEmailConfirmToEdit' => false,
		] );

		$block = new $blockType( array_merge( [
			'address' => '127.0.8.1',
			'by' => $this->user->getId(),
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
			->setMethods( [ 'getBlock' ] )
			->getMock();
		$user->method( 'getBlock' )
			->willReturn( $block );

		$this->overrideUserPermissions( $user, [ 'edit' ] );

		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
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
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserBlock
	 */
	public function testCheckUserBlockEmailConfirmToEdit( $emailConfirmToEdit, $assertion ) {
		$this->setMwGlobals( [
			'wgEmailConfirmToEdit' => $emailConfirmToEdit,
			'wgEmailAuthentication' => true,
		] );

		$this->overrideUserPermissions( $this->user, [
			'edit',
			'move',
		] );

		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

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
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserBlock
	 *
	 * Tests to determine that the passed in permission does not get mixed up with
	 * an action of the same name.
	 */
	public function testCheckUserBlockActionPermission() {
		$tester = $this->getMockBuilder( Action::class )
					   ->disableOriginalConstructor()
					   ->getMock();
		$tester->method( 'getName' )
			   ->willReturn( 'tester' );
		$tester->method( 'getRestriction' )
			   ->willReturn( 'test' );
		$tester->method( 'requiresUnblock' )
			   ->willReturn( false );

		$this->setMwGlobals( [
			'wgActions' => [
				'tester' => $tester,
			],
			'wgGroupPermissions' => [
				'*' => [
					'tester' => true,
				],
			],
		] );

		$user = $this->getMockBuilder( User::class )
			->setMethods( [ 'getBlock' ] )
			->getMock();
		$user->method( 'getBlock' )
			->willReturn( new DatabaseBlock( [
				'address' => '127.0.8.1',
				'by' => $this->user->getId(),
			] ) );

		$this->assertCount( 1, MediaWikiServices::getInstance()->getPermissionManager()
			->getPermissionErrors( 'tester', $user, $this->title )
		);
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::isBlockedFrom
	 */
	public function testBlockInstanceCache() {
		// First, check the user isn't blocked
		$user = $this->getMutableTestUser()->getUser();
		$ut = Title::makeTitle( NS_USER_TALK, $user->getName() );
		$this->assertNull( $user->getBlock( false ), 'sanity check' );
		$this->assertFalse( MediaWikiServices::getInstance()->getPermissionManager()
			->isBlockedFrom( $user, $ut ), 'sanity check' );

		// Block the user
		$blocker = $this->getTestSysop()->getUser();
		$block = new DatabaseBlock( [
			'hideName' => true,
			'allowUsertalk' => false,
			'reason' => 'Because',
		] );
		$block->setTarget( $user );
		$block->setBlocker( $blocker );
		$blockStore = MediaWikiServices::getInstance()->getDatabaseBlockStore();
		$res = $blockStore->insertBlock( $block );
		$this->assertTrue( (bool)$res['id'], 'sanity check: Failed to insert block' );

		// Clear cache and confirm it loaded the block properly
		$user->clearInstanceCache();
		$this->assertInstanceOf( DatabaseBlock::class, $user->getBlock( false ) );
		$this->assertTrue( MediaWikiServices::getInstance()->getPermissionManager()
			->isBlockedFrom( $user, $ut ) );

		// Unblock
		$blockStore->deleteBlock( $block );

		// Clear cache and confirm it loaded the not-blocked properly
		$user->clearInstanceCache();
		$this->assertNull( $user->getBlock( false ) );
		$this->assertFalse( MediaWikiServices::getInstance()->getPermissionManager()
			->isBlockedFrom( $user, $ut ) );
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::isBlockedFrom
	 * @dataProvider provideIsBlockedFrom
	 * @param string|null $title Title to test.
	 * @param bool $expect Expected result from User::isBlockedFrom()
	 * @param array $options Additional test options:
	 *  - 'blockAllowsUTEdit': (bool, default true) Value for $wgBlockAllowsUTEdit
	 *  - 'allowUsertalk': (bool, default false) Passed to DatabaseBlock::__construct()
	 *  - 'pageRestrictions': (array|null) If non-empty, page restriction titles for the block.
	 */
	public function testIsBlockedFrom( $title, $expect, array $options = [] ) {
		$this->setMwGlobals( [
			'wgBlockAllowsUTEdit' => $options['blockAllowsUTEdit'] ?? true,
		] );

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
		$blockStore = MediaWikiServices::getInstance()->getDatabaseBlockStore();
		$blockStore->insertBlock( $block );

		try {
			$this->assertSame( $expect, MediaWikiServices::getInstance()->getPermissionManager()
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

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::getUserPermissions
	 */
	public function testGetUserPermissions() {
		$user = $this->getTestUser( [ 'unittesters' ] )->getUser();
		$rights = MediaWikiServices::getInstance()->getPermissionManager()
			->getUserPermissions( $user );
		$this->assertContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::getUserPermissions
	 */
	public function testGetUserPermissionsHooks() {
		$user = $this->getTestUser( [ 'unittesters', 'testwriters' ] )->getUser();
		$userWrapper = TestingAccessWrapper::newFromObject( $user );

		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
		$rights = $permissionManager->getUserPermissions( $user );
		$this->assertContains( 'test', $rights, 'sanity check' );
		$this->assertContains( 'runtest', $rights, 'sanity check' );
		$this->assertContains( 'writetest', $rights, 'sanity check' );
		$this->assertNotContains( 'nukeworld', $rights, 'sanity check' );

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
			->setMethods( [ 'getAllowedUserRights', 'deregisterSession', 'getSessionId' ] )
			->getMock();
		$mock->method( 'getAllowedUserRights' )->willReturn( [ 'test', 'writetest' ] );
		$mock->method( 'getSessionId' )->willReturn(
			new SessionId( str_repeat( 'X', 32 ) )
		);
		$session = TestUtils::getDummySession( $mock );
		$mockRequest = $this->getMockBuilder( FauxRequest::class )
			->setMethods( [ 'getSession' ] )
			->getMock();
		$mockRequest->method( 'getSession' )->willReturn( $session );
		$userWrapper->mRequest = $mockRequest;

		$this->resetServices();
		$rights = MediaWikiServices::getInstance()
			->getPermissionManager()
			->getUserPermissions( $user );
		$this->assertContains( 'test', $rights );
		$this->assertNotContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::getGroupPermissions
	 */
	public function testGroupPermissions() {
		$rights = MediaWikiServices::getInstance()->getPermissionManager()
			->getGroupPermissions( [ 'unittesters' ] );
		$this->assertContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );

		$rights = MediaWikiServices::getInstance()->getPermissionManager()
			->getGroupPermissions( [ 'unittesters', 'testwriters' ] );
		$this->assertContains( 'runtest', $rights );
		$this->assertContains( 'writetest', $rights );
		$this->assertContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::getGroupPermissions
	 */
	public function testRevokePermissions() {
		$rights = MediaWikiServices::getInstance()->getPermissionManager()
			->getGroupPermissions( [ 'unittesters', 'formertesters' ] );
		$this->assertNotContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	/**
	 * @dataProvider provideGetGroupsWithPermission
	 * @covers \MediaWiki\Permissions\PermissionManager::getGroupsWithPermission
	 */
	public function testGetGroupsWithPermission( $expected, $right ) {
		$result = MediaWikiServices::getInstance()->getPermissionManager()
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

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::userHasRight
	 */
	public function testUserHasRight() {
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

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

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::groupHasPermission
	 */
	public function testGroupHasPermission() {
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

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

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::isEveryoneAllowed
	 */
	public function testIsEveryoneAllowed() {
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		$result = $permissionManager->isEveryoneAllowed( 'editmyoptions' );
		$this->assertTrue( $result );

		$result = $permissionManager->isEveryoneAllowed( 'test' );
		$this->assertFalse( $result );
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::addTemporaryUserRights
	 */
	public function testAddTemporaryUserRights() {
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
		$this->overrideUserPermissions( $this->user, [ 'read', 'edit' ] );
		// sanity checks
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
		$content = MediaWikiServices::getInstance()->getContentHandlerFactory()
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
	 * @covers \MediaWiki\Permissions\PermissionManager::getNamespaceRestrictionLevels
	 */
	public function testGetRestrictionLevels( array $expected, $ns, array $userGroups = null ) {
		$this->setMwGlobals( [
			'wgGroupPermissions' => [
				'*' => [ 'edit' => true ],
				'autoconfirmed' => [ 'editsemiprotected' => true ],
				'sysop' => [
					'editsemiprotected' => true,
					'editprotected' => true,
				],
				'privileged' => [ 'privileged' => true ],
			],
			'wgRevokePermissions' => [
				'noeditsemiprotected' => [ 'editsemiprotected' => true ],
			],
			'wgNamespaceProtection' => [
				NS_MAIN => 'autoconfirmed',
				NS_USER => 'sysop',
				101 => [ 'editsemiprotected', 'privileged' ],
			],
			'wgRestrictionLevels' => [ '', 'autoconfirmed', 'sysop' ],
			'wgAutopromote' => []
		] );
		$user = $userGroups === null ? null : $this->getTestUser( $userGroups )->getUser();
		$this->assertSame( $expected, MediaWikiServices::getInstance()
			->getPermissionManager()
			->getNamespaceRestrictionLevels( $ns, $user ) );
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::getAllPermissions
	 */
	public function testGetAllPermissions() {
		$this->setMwGlobals( [
			'wgAvailableRights' => [ 'test_right' ]
		] );
		$this->resetServices();
		$this->assertContains(
			'test_right',
			MediaWikiServices::getInstance()
				->getPermissionManager()
				->getAllPermissions()
		);
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::getRightsCacheKey
	 * @throws \Exception
	 */
	public function testAnonPermissionsNotClash() {
		$user1 = User::newFromName( 'User1' );
		$user2 = User::newFromName( 'User2' );
		$pm = MediaWikiServices::getInstance()->getPermissionManager();
		$pm->overrideUserRightsForTesting( $user2, [] );
		$this->assertNotSame( $pm->getUserPermissions( $user1 ), $pm->getUserPermissions( $user2 ) );
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::getRightsCacheKey
	 */
	public function testAnonPermissionsNotClashOneRegistered() {
		$user1 = User::newFromName( 'User1' );
		$user2 = $this->getTestSysop()->getUser();
		$pm = MediaWikiServices::getInstance()->getPermissionManager();
		$this->assertNotSame( $pm->getUserPermissions( $user1 ), $pm->getUserPermissions( $user2 ) );
	}

	/**
	 * Test delete-redirect checks for Special:MovePage
	 */
	public function testDeleteRedirect() {
		$this->editPage( 'ExistentRedirect3', '#REDIRECT [[Existent]]' );
		$page = Title::newFromText( 'ExistentRedirect3' );
		$pm = MediaWikiServices::getInstance()->getPermissionManager();

		$user = $this->getMockBuilder( User::class )
			->setMethods( [ 'getEffectiveGroups' ] )
			->getMock();
		$user->method( 'getEffectiveGroups' )->willReturn( [ '*', 'user' ] );

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

		$permManager = MediaWikiServices::getInstance()->getPermissionManager();
		$userJs = Title::newFromText( 'Example/common.js', NS_USER );

		$this->assertTrue( $permManager->userCan( 'delete', $admin, $userJs ) );
		$this->assertTrue( $permManager->userCan( 'delete', $interfaceAdmin, $userJs ) );
		$this->assertTrue( $permManager->userCan( 'deletedhistory', $admin, $userJs ) );
		$this->assertTrue( $permManager->userCan( 'deletedhistory', $interfaceAdmin, $userJs ) );
		$this->assertTrue( $permManager->userCan( 'deletedtext', $admin, $userJs ) );
		$this->assertTrue( $permManager->userCan( 'deletedtext', $interfaceAdmin, $userJs ) );
		$this->assertFalse( $permManager->userCan( 'undelete', $admin, $userJs ) );
		$this->assertTrue( $permManager->userCan( 'undelete', $interfaceAdmin, $userJs ) );
	}
}
