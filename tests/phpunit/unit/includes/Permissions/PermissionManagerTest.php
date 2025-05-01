<?php

namespace MediaWiki\Tests\Unit\Permissions;

use MediaWiki\Actions\ActionFactory;
use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Block\BlockManager;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\TempUser\RealTempUserConfig;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentityLookup;
use MediaWikiUnitTestCase;
use StatusValue;
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
			MainConfigNames::RateLimits => [],
			MainConfigNames::ImplicitRights => [],
		];
		$config = $overrideConfig + $baseConfig;

		$hookContainer = $options['hookContainer'] ??
			$this->createMock( HookContainer::class );
		$redirectLookup = $options['redirectLookup'] ??
			$this->createMock( RedirectLookup::class );
		$restrictionStore = $options['restrictionStore'] ??
			$this->createMock( RestrictionStore::class );

		$permissionManager = new PermissionManager(
			new ServiceOptions( PermissionManager::CONSTRUCTOR_OPTIONS, $config ),
			$this->createMock( SpecialPageFactory::class ),
			$this->getDummyNamespaceInfo(),
			new GroupPermissionsLookup(
				new ServiceOptions( GroupPermissionsLookup::CONSTRUCTOR_OPTIONS, $config )
			),
			$this->createMock( UserGroupManager::class ),
			$this->createMock( BlockManager::class ),
			$this->createMock( BlockErrorFormatter::class ),
			$hookContainer,
			$this->createMock( UserIdentityLookup::class ),
			$redirectLookup,
			$restrictionStore,
			$this->createMock( TitleFormatter::class ),
			new RealTempUserConfig( [] ),
			$this->createMock( UserFactory::class ),
			$this->createMock( ActionFactory::class )
		);

		return TestingAccessWrapper::newFromObject( $permissionManager );
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
	 * @param StatusValue $expectedStatus
	 */
	public function testCheckUserConfigPermissions(
		string $pageTitle,
		array $rights,
		string $action,
		$pageType,
		StatusValue $expectedStatus
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

		$result = PermissionStatus::newEmpty();
		$permissionManager->checkUserConfigPermissions(
			$action,
			$user,
			$result,
			PermissionManager::RIGOR_QUICK, // unused
			true, // $short, unused
			$title
		);
		$this->assertStatusMessagesExactly( $expectedStatus, $result );
	}

	public static function provideTestCheckUserConfigPermissions() {
		yield 'Patrol ignored' => [ 'NameOfActingUser/subpage', [], 'patrol', false, StatusValue::newGood() ];
		yield 'Own non-config' => [ 'NameOfActingUser/subpage', [], 'edit', false, StatusValue::newGood() ];
		yield 'Other non-config' => [ 'NameOfAnotherUser/subpage', [], 'edit', false, StatusValue::newGood() ];
		yield 'Delete other subpage' => [ 'NameOfAnotherUser/subpage', [], 'delete', false, StatusValue::newGood() ];

		foreach ( [ 'css', 'json', 'js' ] as $type ) {
			// User editing their own subpages, everything okay
			// ensure that we don't run the checks for redirects now, those are done separately
			yield "Own $type with editmyuser*" => [
				'NameOfActingUser/subpage.' . $type,
				[ "editmyuser{$type}", 'editmyuserjsredirect' ],
				'edit',
				$type,
				StatusValue::newGood()
			];

			// Interface admin editing own subpages, everything okay
			yield "Own $type with edituser*" => [
				'NameOfActingUser/subpage.' . $type,
				[ "edituser{$type}" ],
				'edit',
				$type,
				StatusValue::newGood()
			];

			// User with no rights editing own subpages, problematic
			yield "Own $type with no rights" => [
				'NameOfActingUser/subpage.' . $type,
				[],
				'edit',
				$type,
				StatusValue::newFatal( "mycustom{$type}protected", 'edit' )
			];

			// Interface admin editing other user's subpages, everything okay
			yield "Other $type with edituser*" => [
				'NameOfAnotherUser/subpage.' . $type,
				[ "edituser{$type}" ],
				'edit',
				$type,
				StatusValue::newGood()
			];

			// Normal user editing other user's subpages, problematic
			yield "Other $type with no rights" => [
				'NameOfAnotherUser/subpage.' . $type,
				[],
				'edit',
				$type,
				StatusValue::newFatal( "custom{$type}protected", 'edit' )
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

		$result = PermissionStatus::newEmpty();
		$permissionManager->checkUserConfigPermissions(
			'edit',
			$user,
			$result,
			PermissionManager::RIGOR_QUICK, // unused
			true, // $short, unused
			$title
		);
		$this->assertStatusMessagesExactly(
			$expectErrors ? StatusValue::newFatal( 'mycustomjsredirectprotected', 'edit' ) : StatusValue::newGood(),
			$result
		);
	}

	public static function provideTestCheckUserConfigPermissionsForRedirect() {
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
		StatusValue $expectedStatus
	) {
		$user = $this->createMock( User::class );
		$user->method( 'getId' )->willReturn( 123 );
		$user->method( 'getName' )->willReturn( 'NameOfActingUser' );

		$title = $this->createMock( Title::class );

		$restrictionStore = $this->createMock( RestrictionStore::class );
		$restrictionStore->expects( $this->any() )
			->method( 'listApplicableRestrictionTypes' )
			->with( $title )
			->willReturn( [ 'other-action', $action ] );
		$restrictionStore->expects( $this->once() )
			->method( 'getRestrictions' )
			->with( $title, $action )
			->willReturn( $restrictions );
		$restrictionStore->method( 'areRestrictionsCascading' )->willReturn( $cascading );

		$permissionManager = $this->getPermissionManager( [
			'restrictionStore' => $restrictionStore,
		] );
		$permissionManager->overrideUserRightsForTesting( $user, $rights );

		$result = PermissionStatus::newEmpty();
		$permissionManager->checkPageRestrictions(
			$action,
			$user,
			$result,
			PermissionManager::RIGOR_QUICK, // unused
			true, // $short, unused
			$title
		);
		$this->assertStatusMessagesExactly( $expectedStatus, $result );
	}

	public static function provideTestCheckPageRestrictions() {
		yield 'No restrictions' => [ 'move', [], [], true, StatusValue::newGood() ];
		yield 'Empty string' => [ 'edit', [ '' ], [], true, StatusValue::newGood() ];
		yield 'Semi-protected, with rights' => [
			'edit',
			[ 'autoconfirmed' ],
			[ 'editsemiprotected' ],
			false,
			StatusValue::newGood()
		];
		yield 'Sysop protected, no rights' => [
			'edit',
			[ 'sysop', ],
			[],
			false,
			StatusValue::newFatal( 'protectedpagetext', 'editprotected', 'edit' )
		];
		yield 'Sysop protected and cascading, no protect' => [
			'edit',
			[ 'editprotected' ],
			[ 'editprotected' ],
			true,
			StatusValue::newFatal( 'protectedpagetext', 'protect', 'edit' )
		];
		yield 'Sysop protected and cascading, with rights' => [
			'edit',
			[ 'editprotected' ],
			[ 'editprotected', 'protect' ],
			true,
			StatusValue::newGood()
		];
	}

	/**
	 * @dataProvider provideTestCheckQuickPermissionsHook
	 */
	public function testCheckQuickPermissionsHook( array $hookErrors, StatusValue $expectedStatus ) {
		$title = $this->createMock( Title::class );
		$user = $this->createMock( User::class );
		$action = 'FakeActionGoesHere';

		$hookCallback = function ( $hookTitle, $hookUser, $hookAction, &$errors, $doExpensiveQueries, $short )
			use ( $user, $title, $action, $hookErrors )
		{
			$this->assertSame( $title, $hookTitle );
			$this->assertSame( $user, $hookUser );
			$this->assertSame( $action, $hookAction );
			$errors = $hookErrors;
			return false;
		};

		$hookContainer = $this->createHookContainer( [ 'TitleQuickPermissions' => $hookCallback ] );

		$permissionManager = $this->getPermissionManager( [
			'hookContainer' => $hookContainer,
		] );
		$result = PermissionStatus::newEmpty();
		$permissionManager->checkQuickPermissions(
			$action,
			$user,
			$result,
			PermissionManager::RIGOR_QUICK, // unused
			true, // $short, unused
			$title
		);
		$this->assertStatusMessagesExactly( $expectedStatus, $result );
	}

	public static function provideTestCheckQuickPermissionsHook() {
		// test name => [ $hookErrors, $expectedStatus ]
		yield 'Hook returns false but no errors' => [
			[],
			StatusValue::newGood()
		];
		yield 'One error' => [
			[ 'error-key' ],
			StatusValue::newFatal( 'error-key' )
		];
		yield 'One error with params as array' => [
			[ 'error-key', 'param' ],
			StatusValue::newFatal( 'error-key', 'param' )
		];
		yield 'Multiple errors' => [
			[ [ 'error-key' ], [ 'error-key-2' ] ],
			( new StatusValue() )->fatal( 'error-key' )->fatal( 'error-key-2' )
		];
	}

}
