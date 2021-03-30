<?php

namespace MediaWiki\Tests\Unit\Permissions;

use Content;
use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\User\UserGroupManager;
use MediaWikiUnitTestCase;
use NamespaceInfo;
use Title;
use User;
use UserCache;
use Wikimedia\TestingAccessWrapper;

/**
 * @author DannyS712
 *
 * See \MediaWiki\Tests\Integration\Permissions\PermissionManagerTest
 * for integration tests
 *
 * @covers \MediaWiki\Permissions\PermissionManager
 */
class PermissionManagerTest extends MediaWikiUnitTestCase {

	private function getPermissionManager( $options = [] ) {
		$overrideConfig = $options['config'] ?? [];
		$baseConfig = [
			'WhitelistRead' => false,
			'WhitelistReadRegexp' => false,
			'EmailConfirmToEdit' => false,
			'BlockDisablesLogin' => false,
			'GroupPermissions' => [],
			'RevokePermissions' => [],
			'AvailableRights' => [],
			'NamespaceProtection' => [ NS_MEDIAWIKI => 'editinterface' ],
			'RestrictionLevels' => [ '', 'autoconfirmed', 'sysop' ],
			'DeleteRevisionsLimit' => false,
		];
		$config = $overrideConfig + $baseConfig;
		$specialPageFactory = $options['specialPageFactory'] ??
			$this->createMock( SpecialPageFactory::class );
		$revisionLookup = $options['revisionLookup'] ??
			$this->createMock( RevisionLookup::class );
		$namespaceInfo = $options['namespaceInfo'] ??
			$this->createMock( NamespaceInfo::class );
		$groupPermissionsLookup = $options['groupPermissionsLookup'] ??
			new GroupPermissionsLookup(
				new ServiceOptions( GroupPermissionsLookup::CONSTRUCTOR_OPTIONS, $config )
			);
		$userGroupManager = $options['userGroupManager'] ??
			$this->createMock( UserGroupManager::class );
		$blockErrorFormatter = $options['blockErrorFormatter'] ??
			$this->createMock( BlockErrorFormatter::class );
		$hookContainer = $options['hookContainer'] ??
			$this->createMock( HookContainer::class );
		$userCache = $options['userCache'] ??
			$this->createMock( UserCache::class );

		$permissionManager = new PermissionManager(
			new ServiceOptions( PermissionManager::CONSTRUCTOR_OPTIONS, $config ),
			$specialPageFactory,
			$revisionLookup,
			$namespaceInfo,
			$groupPermissionsLookup,
			$userGroupManager,
			$blockErrorFormatter,
			$hookContainer,
			$userCache
		);

		$accessPermissionManager = TestingAccessWrapper::newFromObject( $permissionManager );
		return $accessPermissionManager;
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserConfigPermissions
	 *
	 * Does not include testing the `editmyuserjsredirect` functionality, that is covered
	 * in testCheckUserConfigPermissionsForRedirect
	 *
	 * @dataProvider provideTestCheckUserConfigPermissions
	 * @param string $pageTitle Does not include the namespace prefix
	 * @param array $rights What rights the user should be given
	 * @param string $action
	 * @param string|bool $pageType 'css', 'js', 'json', or false for none of those
	 * @param array $expectedErrors
	 */
	public function testCheckUserConfigPermissions(
		string $pageTitle,
		array $rights,
		string $action,
		$pageType,
		array $expectedErrors
	) {
		$user = $this->createMock( User::class );
		$user->method( 'getId' )->willReturn( 123 );
		$user->method( 'getName' )->willReturn( 'NameOfActingUser' );

		$title = $this->createMock( Title::class );
		$title->method( 'getText' )->willReturn( $pageTitle );
		$title->method( 'isUserCssConfigPage' )->willReturn( $pageType === 'css' );
		$title->method( 'isUserJsonConfigPage' )->willReturn( $pageType === 'json' );
		$title->method( 'isUserJsConfigPage' )->willReturn( $pageType === 'js' );

		$permissionManager = $this->getPermissionManager();
		// Override user rights
		$permissionManager->overrideUserRightsForTesting( $user, $rights );

		$result = $permissionManager->checkUserConfigPermissions(
			$action,
			$user,
			[], // starting errors
			PermissionManager::RIGOR_QUICK, // unused
			true, // $short, unused
			$title
		);
		$this->assertEquals( $expectedErrors, $result );
	}

	public function provideTestCheckUserConfigPermissions() {
		yield 'Patrol ignored' => [ 'NameOfActingUser/subpage', [], 'patrol', false, [] ];
		yield 'Own non-config' => [ 'NameOfActingUser/subpage', [], 'edit', false, [] ];
		yield 'Other non-config' => [ 'NameOfAnotherUser/subpage', [], 'edit', false, [] ];
		yield 'Delete other subpage' => [ 'NameOfAnotherUser/subpage', [], 'delete', false, [] ];

		foreach ( [ 'css', 'json', 'js' ] as $type ) {
			// User editing their own subpages, everything okay
			// ensure that we don't run the checks for redirects now, those are done separately
			yield "Own $type with editmyuser*" => [
				'NameOfActingUser/subpage.' . $type,
				[ "editmyuser{$type}", 'editmyuserjsredirect' ],
				'edit',
				$type,
				[]
			];

			// Interface admin editing own subpages, everything okay
			yield "Own $type with edituser*" => [
				'NameOfActingUser/subpage.' . $type,
				[ "edituser{$type}" ],
				'edit',
				$type,
				[]
			];

			// User with no rights editing own subpages, problematic
			yield "Own $type with no rights" => [
				'NameOfActingUser/subpage.' . $type,
				[],
				'edit',
				$type,
				[ [ "mycustom{$type}protected", 'edit' ] ]
			];

			// Interface admin editing other user's subpages, everything okay
			yield "Other $type with edituser*" => [
				'NameOfAnotherUser/subpage.' . $type,
				[ "edituser{$type}" ],
				'edit',
				$type,
				[]
			];

			// Normal user editing other user's subpages, problematic
			yield "Other $type with no rights" => [
				'NameOfAnotherUser/subpage.' . $type,
				[],
				'edit',
				$type,
				[ [ "custom{$type}protected", 'edit' ] ]
			];
		}
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::checkUserConfigPermissions
	 *
	 * @dataProvider provideTestCheckUserConfigPermissionsForRedirect
	 */
	public function testCheckUserConfigPermissionsForRedirect(
		bool $canEditOwnRedirect,
		bool $isRedirect,
		int $targetNamespace,
		string $targetText,
		bool $expectErrors
	) {
		$user = $this->createMock( User::class );
		$user->method( 'getId' )->willReturn( 123 );
		$user->method( 'getName' )->willReturn( 'NameOfActingUser' );

		$title = $this->createMock( Title::class );
		$title->method( 'getText' )->willReturn( 'NameOfActingUser/common.js' );
		$title->method( 'isUserCssConfigPage' )->willReturn( false );
		$title->method( 'isUserJsonConfigPage' )->willReturn( false );
		$title->method( 'isUserJsConfigPage' )->willReturn( true );

		if ( $isRedirect ) {
			$target = $this->createMock( Title::class );
			$target->method( 'inNamespace' )
				->with( $this->equalTo( NS_USER ) )
				->willReturn( $targetNamespace === NS_USER );

			$target->method( 'getText' )->willReturn( $targetText );
		} else {
			$target = null;
		}
		$content = $this->createMock( Content::class );
		$content->method( 'getUltimateRedirectTarget' )->willReturn( $target );

		$revisionRecord = $this->createMock( RevisionRecord::class );
		$revisionRecord->method( 'getContent' )->willReturn( $content );

		$revisionLookup = $this->createMock( RevisionLookup::class );
		$revisionLookup->method( 'getRevisionByTitle' )
			->with( $this->equalTo( $title ) )
			->willReturn( $revisionRecord );

		$permissionManager = $this->getPermissionManager( [
			'revisionLookup' => $revisionLookup,
		] );

		// Override user rights
		$rights = [ 'editmyuserjs' ];
		if ( $canEditOwnRedirect ) {
			$rights[] = 'editmyuserjsredirect';
		}
		$permissionManager->overrideUserRightsForTesting( $user, $rights );

		$result = $permissionManager->checkUserConfigPermissions(
			'edit',
			$user,
			[], // starting errors
			PermissionManager::RIGOR_QUICK, // unused
			true, // $short, unused
			$title
		);
		$this->assertEquals(
			$expectErrors ? [ [ 'mycustomjsredirectprotected', 'edit' ] ] : [],
			$result
		);
	}

	public function provideTestCheckUserConfigPermissionsForRedirect() {
		yield 'With `editmyuserjsredirect`' => [ true, true, NS_USER, 'NameOfActingUser/other.js', false ];
		yield 'Not a redirect' => [ false, false, NS_USER, 'NameOfActingUser/other.js', false ];
		yield 'Redirect out of user space' => [ false, true, NS_MAIN, 'MainPage.js', true ];
		yield 'Redirect to different user' => [ false, true, NS_USER, 'NameOfAnotherUser/other.js', true ];
		yield 'Redirect to own subpage' => [ false, true, NS_USER, 'NameOfActingUser/other.js', false ];
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::checkPageRestrictions
	 *
	 * @dataProvider provideTestCheckPageRestrictions
	 */
	public function testCheckPageRestrictions(
		string $action,
		array $restrictions,
		array $rights,
		bool $cascading,
		array $expectedErrors
	) {
		$user = $this->createMock( User::class );
		$user->method( 'getId' )->willReturn( 123 );
		$user->method( 'getName' )->willReturn( 'NameOfActingUser' );

		$title = $this->createMock( Title::class );
		$title->expects( $this->once() )
			->method( 'getRestrictions' )
			->with( $this->equalTo( $action ) )
			->willReturn( $restrictions );
		$title->method( 'areRestrictionsCascading' )->willReturn( $cascading );

		$permissionManager = $this->getPermissionManager();
		$permissionManager->overrideUserRightsForTesting( $user, $rights );

		$result = $permissionManager->checkPageRestrictions(
			$action,
			$user,
			[], // starting errors
			PermissionManager::RIGOR_QUICK, // unused
			true, // $short, unused
			$title
		);
		$this->assertEquals( $expectedErrors, $result );
	}

	public function provideTestCheckPageRestrictions() {
		yield 'No restrictions' => [ 'move', [], [], true, [] ];
		yield 'Empty string' => [ 'edit', [ '' ], [], true, [] ];
		yield 'Semi-protected, with rights' => [
			'edit',
			[ 'autoconfirmed' ],
			[ 'editsemiprotected' ],
			false,
			[]
		];
		yield 'Sysop protected, no rights' => [
			'edit',
			[ 'sysop', ],
			[],
			false,
			[ [ 'protectedpagetext', 'editprotected', 'edit' ] ]
		];
		yield 'Sysop protected and cascading, no protect' => [
			'edit',
			[ 'editprotected' ],
			[ 'editprotected' ],
			true,
			[ [ 'protectedpagetext', 'protect', 'edit' ] ]
		];
		yield 'Sysop protected and cascading, with rights' => [
			'edit',
			[ 'editprotected' ],
			[ 'editprotected', 'protect' ],
			true,
			[]
		];
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::checkQuickPermissions
	 *
	 * @dataProvider provideTestCheckQuickPermissions
	 */
	public function testCheckQuickPermissions(
		int $namespace,
		string $pageTitle,
		bool $userIsAnon,
		string $action,
		array $rights,
		string $expectedError
	) {
		// Convert string single error to the array of errors PermissionManager uses
		$expectedErrors = ( $expectedError === '' ? [] : [ [ $expectedError ] ] );

		$user = $this->createMock( User::class );
		$user->method( 'getId' )->willReturn( $userIsAnon ? 0 : 123 );
		$user->method( 'getName' )->willReturn( $userIsAnon ? '1.1.1.1' : 'NameOfActingUser' );
		$user->method( 'isAnon' )->willReturn( $userIsAnon );

		// NamespaceInfo - isTalk if namespace is odd, hasSubpages if NS_USER
		$namespaceInfo = $this->createMock( NamespaceInfo::class );
		$namespaceInfo->method( 'isTalk' )
			->willReturnCallback(
				static function ( $ns ) {
					return ( $ns > NS_MAIN && $ns % 2 === 1 );
				}
			);
		$namespaceInfo->method( 'hasSubpages' )
			->willReturnCallback(
				// Only matters for user pages
				static function ( $ns ) {
					return ( $ns === NS_USER );
				}
			);

		// HookContainer - always return true (false tested separately)
		$hookContainer = $this->createMock( HookContainer::class );
		$hookContainer->method( 'run' )
			->willReturn( true );

		// Overrides needed in case `groupHasPermission` is called
		$config = [
			'GroupPermissions' => [
				'autoconfirmed' => [
					'move' => true
				]
			]
		];

		$permissionManager = $this->getPermissionManager( [
			'config' => $config,
			'namespaceInfo' => $namespaceInfo,
			'hookContainer' => $hookContainer,
		] );
		$permissionManager->overrideUserRightsForTesting( $user, $rights );

		$title = $this->createMock( Title::class );
		$title->method( 'getNamespace' )->willReturn( $namespace );
		$title->method( 'getText' )->willReturn( $pageTitle );

		// Ensure that `missingPermissionError` doesn't call User::newFatalPermissionDeniedStatus
		// which uses the global state
		$short = true;

		$result = $permissionManager->checkQuickPermissions(
			$action,
			$user,
			[], // Starting errors
			PermissionManager::RIGOR_QUICK, // unused
			$short,
			$title
		);
		$this->assertEquals( $expectedErrors, $result );
	}

	public function provideTestCheckQuickPermissions() {
		// $namespace, $pageTitle, $userIsAnon, $action, $rights, $expectedError
		// Four different possible errors when trying to create
		yield 'Anon createtalk fail' => [
			NS_TALK, 'Example', true, 'create', [], 'nocreatetext'
		];
		yield 'Anon createpage fail' => [
			NS_MAIN, 'Example', true, 'create', [], 'nocreatetext'
		];
		yield 'User createtalk fail' => [
			NS_TALK, 'Example', false, 'create', [], 'nocreate-loggedin'
		];
		yield 'User createpage fail' => [
			NS_MAIN, 'Example', false, 'create', [], 'nocreate-loggedin'
		];
		yield 'Createpage pass' => [
			NS_MAIN, 'Example', true, 'create', [ 'createpage' ], ''
		];

		// Three different namespace specific move failures, even if user has `move` rights
		yield 'Move root user page fail' => [
			NS_USER, 'Example', true, 'move', [ 'move' ], 'cant-move-user-page'
		];
		yield 'Move file fail' => [
			NS_FILE, 'Example', true, 'move', [ 'move' ], 'movenotallowedfile'
		];
		yield 'Move category fail' => [
			NS_CATEGORY, 'Example', true, 'move', [ 'move' ], 'cant-move-category-page'
		];

		// No move rights at all. Different failures depending on who is allowed to move.
		// Test method sets group permissions to [ 'autoconfirmed' => [ 'move' => true ] ]
		yield 'Anon move fail, autoconfirmed can move' => [
			NS_TALK, 'Example', true, 'move', [], 'movenologintext'
		];
		yield 'User move fail, autoconfirmed can move' => [
			NS_TALK, 'Example', false, 'move', [], 'movenotallowed'
		];
		yield 'Move pass' => [ NS_MAIN, 'Example', true, 'move', [ 'move' ], '' ];

		// Three different possible failures for move target
		yield 'Move-target no rights' => [
			NS_MAIN, 'Example', false, 'move-target', [], 'movenotallowed'
		];
		yield 'Move-target to user root' => [
			NS_USER, 'Example', false, 'move-target', [ 'move' ], 'cant-move-to-user-page'
		];
		yield 'Move-target to category' => [
			NS_CATEGORY, 'Example', false, 'move-target', [ 'move' ], 'cant-move-to-category-page'
		];
		yield 'Move-target pass' => [
			NS_MAIN, 'Example', false, 'move-target', [ 'move' ], ''
		];

		// Other actions without special handling
		yield 'Missing rights for edit' => [
			NS_MAIN, 'Example', false, 'edit', [], 'badaccess-group0'
		];
		yield 'Having rights for edit' => [
			NS_MAIN, 'Example', false, 'edit', [ 'edit', ], ''
		];
	}

	/**
	 * @covers \MediaWiki\Permissions\PermissionManager::checkQuickPermissions
	 */
	public function testCheckQuickPermissionsHook() {
		$title = $this->createMock( Title::class );
		$user = $this->createMock( User::class );
		$action = 'FakeActionGoesHere';

		$hookCallback = function ( $hookTitle, $hookUser, $hookAction, &$errors, $doExpensiveQueries, $short )
			use ( $user, $title, $action )
		{
			$this->assertSame( $title, $hookTitle );
			$this->assertSame( $user, $hookUser );
			$this->assertSame( $action, $hookAction );
			$errors[] = [ 'Hook failure goes here' ];
			return false;
		};

		$hookContainer = $this->createHookContainer( [ 'TitleQuickPermissions' => $hookCallback ] );

		$permissionManager = $this->getPermissionManager( [
			'hookContainer' => $hookContainer,
		] );
		$result = $permissionManager->checkQuickPermissions(
			$action,
			$user,
			[], // Starting errors
			PermissionManager::RIGOR_QUICK, // unused
			true, // $short, unused,
			$title
		);
		$this->assertEquals(
			[ [ 'Hook failure goes here' ] ],
			$result
		);
	}

}
