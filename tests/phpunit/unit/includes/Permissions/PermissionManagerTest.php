<?php

namespace MediaWiki\Tests\Unit\Permissions;

use MediaWiki\Actions\ActionFactory;
use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\TempUser\RealTempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use MediaWikiUnitTestCase;
use Title;
use TitleFormatter;
use User;
use UserCache;
use Wikimedia\TestingAccessWrapper;

/**
 * For the integration tests, see \MediaWiki\Tests\Integration\Permissions\PermissionManagerTest.
 *
 * @author DannyS712
 * @covers \MediaWiki\Permissions\PermissionManager
 */
class PermissionManagerTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;

	private function getPermissionManager( $options = [] ) {
		$overrideConfig = $options['config'] ?? [];
		$baseConfig = [
			MainConfigNames::WhitelistRead => false,
			MainConfigNames::WhitelistReadRegexp => false,
			MainConfigNames::EmailConfirmToEdit => false,
			MainConfigNames::BlockDisablesLogin => false,
			MainConfigNames::EnablePartialActionBlocks => false,
			MainConfigNames::GroupPermissions => [],
			MainConfigNames::RevokePermissions => [],
			MainConfigNames::GroupInheritsPermissions => [],
			MainConfigNames::AvailableRights => [],
			MainConfigNames::NamespaceProtection => [ NS_MEDIAWIKI => 'editinterface' ],
			MainConfigNames::RestrictionLevels => [ '', 'autoconfirmed', 'sysop' ],
			MainConfigNames::DeleteRevisionsLimit => false,
		];
		$config = $overrideConfig + $baseConfig;
		$specialPageFactory = $options['specialPageFactory'] ??
			$this->createMock( SpecialPageFactory::class );

		// DummyServicesTrait::getDummyNamespaceInfo
		$namespaceInfo = $this->getDummyNamespaceInfo();

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
		$redirectLookup = $options['redirectLookup'] ??
			$this->createMock( RedirectLookup::class );
		$restrictionStore = $options['restrictionStore'] ??
			$this->createMock( RestrictionStore::class );
		$titleFormatter = $options['titleFormatter'] ??
			$this->createMock( TitleFormatter::class );
		$tempUserConfig = $options['tempUserConfig'] ??
			new RealTempUserConfig( [] );
		$userFactory = $options['userFactory'] ??
			$this->createMock( UserFactory::class );
		$actionFactory = $options['actionFactory'] ??
			$this->createMock( ActionFactory::class );

		$permissionManager = new PermissionManager(
			new ServiceOptions( PermissionManager::CONSTRUCTOR_OPTIONS, $config ),
			$specialPageFactory,
			$namespaceInfo,
			$groupPermissionsLookup,
			$userGroupManager,
			$blockErrorFormatter,
			$hookContainer,
			$userCache,
			$redirectLookup,
			$restrictionStore,
			$titleFormatter,
			$tempUserConfig,
			$userFactory,
			$actionFactory
		);

		$accessPermissionManager = TestingAccessWrapper::newFromObject( $permissionManager );
		return $accessPermissionManager;
	}

	/**
	 * Does not cover the `editmyuserjsredirect` functionality, which is covered
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
				->with( NS_USER )
				->willReturn( $targetNamespace === NS_USER );

			$target->method( 'getText' )->willReturn( $targetText );
		} else {
			$target = null;
		}

		$redirectLookup = $this->createMock( RedirectLookup::class );
		$redirectLookup->method( 'getRedirectTarget' )->with( $title )
			->willReturn( $target );

		$permissionManager = $this->getPermissionManager( [
			'redirectLookup' => $redirectLookup,
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

		$restrictionStore = $this->createMock( RestrictionStore::class );
		$restrictionStore->expects( $this->once() )
			->method( 'getRestrictions' )
			->with( $title, $action )
			->willReturn( $restrictions );
		$restrictionStore->method( 'areRestrictionsCascading' )->willReturn( $cascading );

		$permissionManager = $this->getPermissionManager( [
			'restrictionStore' => $restrictionStore,
		] );
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
	 * @dataProvider provideTestCheckQuickPermissions
	 */
	public function testCheckQuickPermissions(
		int $namespace,
		string $pageTitle,
		string $userType,
		string $action,
		array $rights,
		string $expectedError
	) {
		// Convert string single error to the array of errors PermissionManager uses
		$expectedErrors = ( $expectedError === '' ? [] : [ [ $expectedError ] ] );

		$userIsAnon = $userType === 'anon';
		$userIsTemp = $userType === 'temp';
		$userIsNamed = $userType === 'user';
		$user = $this->createMock( User::class );
		$user->method( 'getId' )->willReturn( $userIsAnon ? 0 : 123 );
		$user->method( 'getName' )->willReturn( $userIsAnon ? '1.1.1.1' : 'NameOfActingUser' );
		$user->method( 'isAnon' )->willReturn( $userIsAnon );
		$user->method( 'isNamed' )->willReturn( $userIsNamed );
		$user->method( 'isTemp' )->willReturn( $userIsTemp );

		// HookContainer - always return true (false tested separately)
		$hookContainer = $this->createMock( HookContainer::class );
		$hookContainer->method( 'run' )
			->willReturn( true );

		// Overrides needed in case `groupHasPermission` is called
		$config = [
			MainConfigNames::GroupPermissions => [
				'autoconfirmed' => [
					'move' => true
				]
			]
		];

		$permissionManager = $this->getPermissionManager( [
			'config' => $config,
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
			NS_TALK, 'Example', 'anon', 'create', [], 'nocreatetext'
		];
		yield 'Anon createpage fail' => [
			NS_MAIN, 'Example', 'anon', 'create', [], 'nocreatetext'
		];
		yield 'User createtalk fail' => [
			NS_TALK, 'Example', 'user', 'create', [], 'nocreate-loggedin'
		];
		yield 'User createpage fail' => [
			NS_MAIN, 'Example', 'user', 'create', [], 'nocreate-loggedin'
		];
		yield 'Temp user createpage fail' => [
			NS_MAIN, 'Example', 'temp', 'create', [], 'nocreatetext'
		];

		yield 'Createpage pass' => [
			NS_MAIN, 'Example', 'anon', 'create', [ 'createpage' ], ''
		];

		// Three different namespace specific move failures, even if user has `move` rights
		yield 'Move root user page fail' => [
			NS_USER, 'Example', 'anon', 'move', [ 'move' ], 'cant-move-user-page'
		];
		yield 'Move file fail' => [
			NS_FILE, 'Example', 'anon', 'move', [ 'move' ], 'movenotallowedfile'
		];
		yield 'Move category fail' => [
			NS_CATEGORY, 'Example', 'anon', 'move', [ 'move' ], 'cant-move-category-page'
		];

		// No move rights at all. Different failures depending on who is allowed to move.
		// Test method sets group permissions to [ 'autoconfirmed' => [ 'move' => true ] ]
		yield 'Anon move fail, autoconfirmed can move' => [
			NS_TALK, 'Example', 'anon', 'move', [], 'movenologintext'
		];
		yield 'User move fail, autoconfirmed can move' => [
			NS_TALK, 'Example', 'user', 'move', [], 'movenotallowed'
		];
		yield 'Temp user move fail, autoconfirmed can move' => [
			NS_TALK, 'Example', 'temp', 'move', [], 'movenologintext'
		];
		yield 'Move pass' => [ NS_MAIN, 'Example', 'anon', 'move', [ 'move' ], '' ];

		// Three different possible failures for move target
		yield 'Move-target no rights' => [
			NS_MAIN, 'Example', 'user', 'move-target', [], 'movenotallowed'
		];
		yield 'Move-target to user root' => [
			NS_USER, 'Example', 'user', 'move-target', [ 'move' ], 'cant-move-to-user-page'
		];
		yield 'Move-target to category' => [
			NS_CATEGORY, 'Example', 'user', 'move-target', [ 'move' ], 'cant-move-to-category-page'
		];
		yield 'Move-target pass' => [
			NS_MAIN, 'Example', 'user', 'move-target', [ 'move' ], ''
		];

		// Other actions without special handling
		yield 'Missing rights for edit' => [
			NS_MAIN, 'Example', 'user', 'edit', [], 'badaccess-group0'
		];
		yield 'Having rights for edit' => [
			NS_MAIN, 'Example', 'user', 'edit', [ 'edit', ], ''
		];
	}

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
