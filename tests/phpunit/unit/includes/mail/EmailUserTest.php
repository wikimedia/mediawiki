<?php

namespace MediaWiki\Tests\Unit\Mail;

use CentralIdLookup;
use Generator;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Language\RawMessage;
use MediaWiki\Mail\EmailUser;
use MediaWiki\Mail\IEmailer;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserOptionsLookup;
use MediaWikiUnitTestCase;
use Message;
use MessageLocalizer;
use RuntimeException;
use StatusValue;
use User;

/**
 * @coversDefaultClass \MediaWiki\Mail\EmailUser
 * @covers ::__construct
 */
class EmailUserTest extends MediaWikiUnitTestCase {
	private function getEmailUser(
		UserOptionsLookup $userOptionsLookup = null,
		CentralIdLookup $centralIdLookup = null,
		UserFactory $userFactory = null,
		PermissionManager $permissionManager = null,
		array $configOverrides = [],
		array $hooks = [],
		IEmailer $emailer = null
	): EmailUser {
		$options = new ServiceOptions(
			EmailUser::CONSTRUCTOR_OPTIONS,
			$configOverrides + [
				MainConfigNames::EnableEmail => true,
				MainConfigNames::EnableUserEmail => true,
				MainConfigNames::EnableSpecialMute => true,
				MainConfigNames::PasswordSender => 'foo@bar.baz',
				MainConfigNames::UserEmailUseReplyTo => true,
			]
		);

		return new EmailUser(
			$options,
			$this->createHookContainer( $hooks ),
			$userOptionsLookup ?? $this->createMock( UserOptionsLookup::class ),
			$centralIdLookup ?? $this->createMock( CentralIdLookup::class ),
			$permissionManager ?? $this->createMock( PermissionManager::class ),
			$userFactory ?? $this->createMock( UserFactory::class ),
			$emailer ?? $this->createMock( IEmailer::class )
		);
	}

	private function getValidTargetUserFactory( string $targetName ): UserFactory {
		$validTarget = $this->createMock( User::class );
		$validTarget->method( 'getId' )->willReturn( 1 );
		$validTarget->method( 'isEmailConfirmed' )->willReturn( true );
		$validTarget->method( 'canReceiveEmail' )->willReturn( true );
		$validTargetUserFactory = $this->createMock( UserFactory::class );
		$validTargetUserFactory->expects( $this->atLeastOnce() )
			->method( 'newFromName' )
			->with( $targetName )
			->willReturn( $validTarget );
		return $validTargetUserFactory;
	}

	/**
	 * Returns a valid MessageLocalizer mock. We don't care about MessageLocalizer at all, but since the return value
	 * of ::msg() is not typehinted, we're forced to specify a mocked Message to return so that chained method calls
	 * won't break.
	 * @return MessageLocalizer
	 */
	private function getMockMessageLocalizer(): MessageLocalizer {
		$messageLocalizer = $this->createMock( MessageLocalizer::class );
		$messageLocalizer->method( 'msg' )->willReturn( $this->createMock( Message::class ) );
		return $messageLocalizer;
	}

	/**
	 * @param mixed $target
	 * @param User $sender
	 * @param StatusValue|null $expected StatusValue object to compare for errors, or null to indicate that we're
	 * expecting a good StatusValue with a User object as value.
	 * @param UserFactory|null $userFactory
	 * @covers ::getTarget
	 * @dataProvider provideGetTarget
	 */
	public function testGetTarget( $target, User $sender, ?StatusValue $expected, UserFactory $userFactory = null ) {
		$emailUser = $this->getEmailUser( null, null, $userFactory );
		$actualStatus = $emailUser->getTarget( $target, $sender );
		if ( $expected === null ) {
			$this->assertStatusGood( $actualStatus );
			$this->assertInstanceOf( User::class, $actualStatus->getValue() );
		} else {
			$this->assertEquals( $expected, $actualStatus );
		}
	}

	public function provideGetTarget(): Generator {
		$targetName = 'John Doe';
		$noopSender = $this->createMock( User::class );

		$invalidUsername = '123<>|[]';
		$invalidUsernameUserFactory = $this->createMock( UserFactory::class );
		$invalidUsernameUserFactory->expects( $this->atLeastOnce() )
			->method( 'newFromName' )
			->with( $invalidUsername )
			->willReturn( null );
		yield 'Invalid username' => [
			$invalidUsername,
			$noopSender,
			StatusValue::newFatal( 'emailnotarget' ),
			$invalidUsernameUserFactory
		];

		$noEmailTarget = $this->createMock( User::class );
		$noEmailTarget->method( 'getId' )->willReturn( 1 );
		$noEmailTarget->method( 'isEmailConfirmed' )->willReturn( false );
		$noEmailTargetUserFactory = $this->createMock( UserFactory::class );
		$noEmailTargetUserFactory->expects( $this->atLeastOnce() )
			->method( 'newFromName' )
			->with( $targetName )
			->willReturn( $noEmailTarget );
		yield 'Invalid target' => [
			$targetName,
			$noopSender,
			StatusValue::newFatal( 'noemailtext' ),
			$noEmailTargetUserFactory
		];

		$validTargetUserFactory = $this->getValidTargetUserFactory( $targetName );
		yield 'Valid target' => [ $targetName, $noopSender, null, $validTargetUserFactory ];
	}

	/**
	 * @covers ::validateTarget
	 * @dataProvider provideValidateTarget
	 */
	public function testValidateTarget(
		User $target,
		User $sender,
		StatusValue $expected,
		UserOptionsLookup $userOptionsLookup = null,
		CentralIdLookup $centralIdLookup = null
	) {
		$emailUser = $this->getEmailUser( $userOptionsLookup, $centralIdLookup );
		$this->assertEquals( $expected, $emailUser->validateTarget( $target, $sender ) );
	}

	public function provideValidateTarget(): Generator {
		$noopUserMock = $this->createMock( User::class );
		$validTarget = $this->createMock( User::class );
		$validTarget->method( 'getId' )->willReturn( 1 );
		$validTarget->method( 'isEmailConfirmed' )->willReturn( true );
		$validTarget->method( 'canReceiveEmail' )->willReturn( true );

		$anonTarget = $this->createMock( User::class );
		$anonTarget->expects( $this->atLeastOnce() )->method( 'getId' )->willReturn( 0 );
		yield 'Target has user ID 0' => [ $anonTarget, $noopUserMock, StatusValue::newFatal( 'emailnotarget' ) ];

		$emailNotConfirmedTarget = $this->createMock( User::class );
		$emailNotConfirmedTarget->method( 'getId' )->willReturn( 1 );
		$emailNotConfirmedTarget->expects( $this->atLeastOnce() )->method( 'isEmailConfirmed' )->willReturn( false );
		yield 'Target does not have confirmed email' => [
			$emailNotConfirmedTarget,
			$noopUserMock,
			StatusValue::newFatal( 'noemailtext' )
		];

		$cannotReceiveEmailsTarget = $this->createMock( User::class );
		$cannotReceiveEmailsTarget->method( 'getId' )->willReturn( 1 );
		$cannotReceiveEmailsTarget->method( 'isEmailConfirmed' )->willReturn( true );
		$cannotReceiveEmailsTarget->expects( $this->atLeastOnce() )->method( 'canReceiveEmail' )->willReturn( false );
		yield 'Target cannot receive emails' => [
			$cannotReceiveEmailsTarget,
			$noopUserMock,
			StatusValue::newFatal( 'nowikiemailtext' )
		];

		$newbieSender = $this->createMock( User::class );
		$newbieSender->expects( $this->atLeastOnce() )->method( 'isNewbie' )->willReturn( true );
		$noNewbieEmailsOptionsLookup = $this->createMock( UserOptionsLookup::class );
		$noNewbieEmailsOptionsLookup->expects( $this->atLeastOnce() )
			->method( 'getOption' )
			->with( $validTarget, 'email-allow-new-users' )
			->willReturn( false );
		yield 'Target does not allow emails from newbie and sender is newbie' => [
			$validTarget,
			$newbieSender,
			StatusValue::newFatal( 'nowikiemailtext' ),
			$noNewbieEmailsOptionsLookup
		];

		$senderCentralID = 42;
		$muteListOptionsLookup = $this->createMock( UserOptionsLookup::class );
		$muteListOptionsLookup->expects( $this->atLeast( 2 ) )
			->method( 'getOption' )
			->willReturnCallback( static function ( $_, $option ) use ( $senderCentralID ) {
				return $option === 'email-blacklist' ? (string)$senderCentralID : true;
			} );
		$centralIdLookup = $this->createMock( CentralIdLookup::class );
		$centralIdLookup->expects( $this->atLeastOnce() )
			->method( 'centralIdFromLocalUser' )
			->with( $noopUserMock )
			->willReturn( $senderCentralID );
		yield 'Target muted the sender' => [
			$validTarget,
			$noopUserMock,
			StatusValue::newFatal( 'nowikiemailtext' ),
			$muteListOptionsLookup,
			$centralIdLookup
		];

		yield 'Valid' => [ $validTarget, $noopUserMock, StatusValue::newGood() ];
	}

	/**
	 * @covers ::getPermissionsError
	 * @dataProvider providePermissionsError
	 */
	public function testGetPermissionsError(
		User $user,
		$expected,
		PermissionManager $permissionManager = null,
		array $configOverrides = [],
		array $hooks = []
	) {
		$emailUser = $this->getEmailUser( null, null, null, $permissionManager, $configOverrides, $hooks );
		$this->assertSame( $expected, $emailUser->getPermissionsError( $user, 'some-token' ) );
	}

	public function providePermissionsError(): Generator {
		$validSender = $this->createMock( User::class );
		$validSender->method( 'isEmailConfirmed' )->willReturn( true );

		yield 'Emails disabled' => [
			$validSender,
			'usermaildisabled',
			null,
			[ MainConfigNames::EnableEmail => false ]
		];

		yield 'User emails disabled' => [
			$validSender,
			'usermaildisabled',
			null,
			[ MainConfigNames::EnableUserEmail => false ]
		];

		$noEmailSender = $this->createMock( User::class );
		$noEmailSender->expects( $this->atLeastOnce() )->method( 'isEmailConfirmed' )->willReturn( false );
		yield 'Sender does not have an email' => [ $noEmailSender, 'mailnologin' ];

		$notAllowedPermManager = $this->createMock( PermissionManager::class );
		$notAllowedPermManager->expects( $this->atLeastOnce() )
			->method( 'userHasRight' )
			->with( $validSender, 'sendemail' )
			->willReturn( false );
		yield 'Sender is not allowed to send emails' => [ $validSender, 'badaccess', $notAllowedPermManager ];

		$allowedPermManager = $this->createMock( PermissionManager::class );
		$allowedPermManager->method( 'userHasRight' )
			->with( $this->anything(), 'sendemail' )
			->willReturn( true );

		$blockedSender = $this->createMock( User::class );
		$blockedSender->method( 'isEmailConfirmed' )->willReturn( true );
		$blockedSender->expects( $this->atLeastOnce() )->method( 'isBlockedFromEmailuser' )->willReturn( true );
		yield 'Sender is blocked from emailing users' => [ $blockedSender, 'blockedemailuser', $allowedPermManager ];

		$ratelimitedSender = $this->createMock( User::class );
		$ratelimitedSender->method( 'isEmailConfirmed' )->willReturn( true );
		$ratelimitedSender->expects( $this->atLeastOnce() )
			->method( 'pingLimiter' )
			->with( 'sendemail' )
			->willReturn( true );
		yield 'Sender is rate-limited' => [ $ratelimitedSender, 'actionthrottledtext', $allowedPermManager ];

		$userCanSendEmailError = 'first-hook-error';
		$userCanSendEmailHooks = [
			'UserCanSendEmail' => static function ( $user, &$err ) use ( $userCanSendEmailError ) {
				$err = $userCanSendEmailError;
			}
		];
		yield 'UserCanSendEmail hook error' => [
			$validSender,
			$userCanSendEmailError,
			$allowedPermManager,
			[],
			$userCanSendEmailHooks
		];

		$emailUserPermissionsErrorsError = 'second-hook-error';
		$emailUserPermissionsErrorsHooks = [
			'EmailUserPermissionsErrors' =>
				static function ( $user, $token, &$err ) use ( $emailUserPermissionsErrorsError ) {
					$err = $emailUserPermissionsErrorsError;
				}
		];
		yield 'EmailUserPermissionsErrors hook error' => [
			$validSender,
			$emailUserPermissionsErrorsError,
			$allowedPermManager,
			[],
			$emailUserPermissionsErrorsHooks
		];

		yield 'Successful' => [ $validSender, null, $allowedPermManager ];
	}

	/**
	 * @covers ::submit
	 * @dataProvider provideSubmit
	 */
	public function testSubmit(
		string $targetName,
		User $sender,
		$expected,
		UserFactory $userFactory = null,
		array $hooks = [],
		IEmailer $emailer = null
	) {
		$emailUser = $this->getEmailUser( null, null, $userFactory, null, [], $hooks, $emailer );
		$res = $emailUser->submit(
			$targetName,
			'subject',
			'text',
			false,
			$sender,
			$this->getMockMessageLocalizer()
		);
		if ( $expected instanceof StatusValue ) {
			$this->assertEquals( $expected, $res );
		} else {
			$this->assertSame( $expected, $res );
		}
	}

	public function provideSubmit(): Generator {
		$validSender = $this->createMock( User::class );
		$validTarget = 'John Doe';
		$validTargetUserFactory = $this->getValidTargetUserFactory( $validTarget );

		yield 'Invalid target' => [ '', $validSender, StatusValue::newFatal( 'emailnotarget' ) ];

		$hookStatusError = StatusValue::newFatal( 'some-hook-error' );
		$emailUserHookUsingStatusHooks = [
			'EmailUser' => static function ( $a, $b, $c, $d, &$err ) use ( $hookStatusError ) {
				$err = $hookStatusError;
				return false;
			}
		];
		yield 'EmailUserHook error as a Status' => [
			$validTarget,
			$validSender,
			$hookStatusError,
			$validTargetUserFactory,
			$emailUserHookUsingStatusHooks
		];

		$emailUserHookUsingBooleanFalseHooks = [
			'EmailUser' => static function ( $a, $b, $c, $d, &$err ) {
				$err = false;
				return false;
			}
		];
		yield 'EmailUserHook error as boolean false' => [
			$validTarget,
			$validSender,
			StatusValue::newFatal( 'hookaborted' ),
			$validTargetUserFactory,
			$emailUserHookUsingBooleanFalseHooks
		];

		$emailUserHookUsingBooleanTrueHooks = [
			'EmailUser' => static function ( $a, $b, $c, $d, &$err ) {
				$err = true;
				return false;
			}
		];
		yield 'EmailUserHook error as boolean true' => [
			$validTarget,
			$validSender,
			StatusValue::newGood(),
			$validTargetUserFactory,
			$emailUserHookUsingBooleanTrueHooks
		];

		$hookError = 'a-hook-error';
		$emailUserHookUsingArrayHooks = [
			'EmailUser' => static function ( $a, $b, $c, $d, &$err ) use ( $hookError ) {
				$err = [ $hookError ];
				return false;
			}
		];
		yield 'EmailUserHook error as array' => [
			$validTarget,
			$validSender,
			StatusValue::newFatal( $hookError ),
			$validTargetUserFactory,
			$emailUserHookUsingArrayHooks
		];

		$hookErrorMsg = new RawMessage( 'Some hook error' );
		$emailUserHookUsingMessageHooks = [
			'EmailUser' => static function ( $a, $b, $c, $d, &$err ) use ( $hookErrorMsg ) {
				$err = $hookErrorMsg;
				return false;
			}
		];
		yield 'EmailUserHook error as MessageSpecifier' => [
			$validTarget,
			$validSender,
			StatusValue::newFatal( $hookErrorMsg ),
			$validTargetUserFactory,
			$emailUserHookUsingMessageHooks
		];

		$emailerErrorStatus = StatusValue::newFatal( 'emailer-error' );
		$errorEmailer = $this->createMock( IEmailer::class );
		$errorEmailer->expects( $this->atLeastOnce() )->method( 'send' )->willReturn( $emailerErrorStatus );
		yield 'Error in the Emailer' => [
			$validTarget,
			$validSender,
			$emailerErrorStatus,
			$validTargetUserFactory,
			[],
			$errorEmailer
		];

		$emailerSuccessStatus = StatusValue::newGood( 42 );
		$successEmailer = $this->createMock( IEmailer::class );
		$successEmailer->expects( $this->atLeastOnce() )->method( 'send' )->willReturn( $emailerSuccessStatus );
		yield 'Successful' => [
			$validTarget,
			$validSender,
			$emailerSuccessStatus,
			$validTargetUserFactory,
			[],
			$successEmailer
		];
	}

	/**
	 * @covers ::submit
	 */
	public function testSubmit__rateLimited() {
		$sender = $this->createMock( User::class );
		$sender->method( 'pingLimiter' )->with( 'sendemail' )->willReturn( true );
		$validTarget = $this->createMock( User::class );
		$validTarget->method( 'getId' )->willReturn( 1 );
		$validTarget->method( 'isEmailConfirmed' )->willReturn( true );
		$validTarget->method( 'canReceiveEmail' )->willReturn( true );
		$validUserFactory = $this->createMock( UserFactory::class );
		$validUserFactory->method( 'newFromName' )->willReturn( $validTarget );

		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'You are throttled' );
		$res = $this->getEmailUser( null, null, $validUserFactory )->submit(
			'Some target',
			'subject',
			'text',
			false,
			$sender,
			$this->getMockMessageLocalizer()
		);
		// This assertion should not be reached if the test passes, but it can be helpful to determine why
		// the test is failing.
		$this->assertStatusGood( $res );
	}
}
