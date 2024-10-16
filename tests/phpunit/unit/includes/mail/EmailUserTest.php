<?php

namespace MediaWiki\Tests\Unit\Mail;

use Generator;
use MediaWiki\Block\AbstractBlock;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Language\RawMessage;
use MediaWiki\Mail\EmailUser;
use MediaWiki\Mail\IEmailer;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWikiUnitTestCase;
use StatusValue;
use Wikimedia\Message\IMessageFormatterFactory;
use Wikimedia\Message\ITextFormatter;

/**
 * @group Mail
 * @covers \MediaWiki\Mail\EmailUser
 */
class EmailUserTest extends MediaWikiUnitTestCase {
	private function getEmailUser(
		Authority $sender,
		?UserOptionsLookup $userOptionsLookup = null,
		?CentralIdLookup $centralIdLookup = null,
		?UserFactory $userFactory = null,
		array $configOverrides = [],
		array $hooks = [],
		?IEmailer $emailer = null
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
			$userFactory ?? $this->createMock( UserFactory::class ),
			$emailer ?? $this->createMock( IEmailer::class ),
			$this->createMock( IMessageFormatterFactory::class ),
			$this->createMock( ITextFormatter::class ),
			$sender
		);
	}

	/**
	 * @dataProvider provideValidateTarget
	 */
	public function testValidateTarget(
		User $target,
		User $sender,
		StatusValue $expected,
		?UserOptionsLookup $userOptionsLookup = null,
		?CentralIdLookup $centralIdLookup = null
	) {
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newFromAuthority' )->willReturn( $sender );
		$userFactory->method( 'newFromUserIdentity' )->willReturn( $target );
		$emailUser = $this->getEmailUser( $sender, $userOptionsLookup, $centralIdLookup, $userFactory );
		$this->assertEquals( $expected, $emailUser->validateTarget( $target ) );
	}

	public function provideValidateTarget(): Generator {
		$noopUserMock = $this->createMock( User::class );
		$noopUserMock->method( 'getUser' )->willReturnSelf();
		$validTarget = $this->getValidTarget();

		$anonTarget = $this->createMock( User::class );
		$anonTarget->expects( $this->atLeastOnce() )->method( 'getId' )->willReturn( 0 );
		$anonTarget->method( 'getUser' )->willReturnSelf();
		yield 'Target has user ID 0' => [ $anonTarget, $noopUserMock, StatusValue::newFatal( 'emailnotarget' ) ];

		$emailNotConfirmedTarget = $this->createMock( User::class );
		$emailNotConfirmedTarget->method( 'getId' )->willReturn( 1 );
		$emailNotConfirmedTarget->expects( $this->atLeastOnce() )->method( 'isEmailConfirmed' )->willReturn( false );
		$emailNotConfirmedTarget->method( 'getUser' )->willReturnSelf();
		yield 'Target does not have confirmed email' => [
			$emailNotConfirmedTarget,
			$noopUserMock,
			StatusValue::newFatal( 'noemailtext' )
		];

		$cannotReceiveEmailsTarget = $this->createMock( User::class );
		$cannotReceiveEmailsTarget->method( 'getId' )->willReturn( 1 );
		$cannotReceiveEmailsTarget->method( 'isEmailConfirmed' )->willReturn( true );
		$cannotReceiveEmailsTarget->expects( $this->atLeastOnce() )->method( 'canReceiveEmail' )->willReturn( false );
		$cannotReceiveEmailsTarget->method( 'getUser' )->willReturnSelf();
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
			->willReturnCallback(
				function ( UserIdentity $user, string $option ) use ( $validTarget, $senderCentralID ) {
					$this->assertSame( $validTarget->getUser(), $user );
					return $option === 'email-blacklist' ? (string)$senderCentralID : null;
				}
			);
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
	 * @dataProvider provideCanSend
	 * @dataProvider provideAuthorizeSend
	 */
	public function testAuthorizeSend(
		User $performerUser,
		StatusValue $expected,
		array $configOverrides = [],
		array $hooks = [],
		bool $expectDeprecation = false
	) {
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newFromAuthority' )->willReturn( $performerUser );
		if ( $expectDeprecation ) {
			$this->expectDeprecationAndContinue( '/Use of EmailUserPermissionsErrors hook/' );
		}
		$emailUser = $this->getEmailUser( $performerUser, null, null, $userFactory, $configOverrides, $hooks );
		$this->assertEquals( $expected, $emailUser->authorizeSend() );
	}

	/**
	 * @dataProvider provideCanSend
	 */
	public function testCanSend(
		User $performerUser,
		StatusValue $expected,
		array $configOverrides = [],
		array $hooks = [],
		bool $expectDeprecation = false
	) {
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newFromAuthority' )->willReturn( $performerUser );
		if ( $expectDeprecation ) {
			$this->expectDeprecationAndContinue( '/Use of EmailUserPermissionsErrors hook/' );
		}
		$emailUser = $this->getEmailUser( $performerUser, null, null, $userFactory, $configOverrides, $hooks );
		$this->assertEquals( $expected, $emailUser->canSend( 'some-token' ) );
	}

	private function newMockUser( $methods = [] ) {
		$methods['isEmailConfirmed'] = $methods['isEmailConfirmed'] ?? true;
		$methods['isAllowed'] = $methods['isAllowed'] ?? true;
		$methods['isDefinitelyAllowed'] = $methods['isDefinitelyAllowed'] ?? $methods['isAllowed'];
		$methods['authorizeAction'] = $methods['authorizeAction'] ?? $methods['isDefinitelyAllowed'];

		$user = $this->createMock( User::class );

		foreach ( $methods as $name => $value ) {
			if ( is_callable( $value ) ) {
				$user->method( $name )->willReturnCallback( $value );
			} else {
				$user->method( $name )->willReturn( $value );
			}
		}

		return $user;
	}

	public function provideCanSend(): Generator {
		$validSender = $this->newMockUser();

		yield 'Emails disabled' => [
			$validSender,
			StatusValue::newFatal( 'usermaildisabled' ),
			[ MainConfigNames::EnableEmail => false ]
		];

		yield 'User emails disabled' => [
			$validSender,
			StatusValue::newFatal( 'usermaildisabled' ),
			[ MainConfigNames::EnableUserEmail => false ]
		];

		$noEmailSender = $this->newMockUser( [ 'isEmailConfirmed' => false ] );
		yield 'Sender does not have an email' => [
			$noEmailSender,
			StatusValue::newFatal( 'mailnologin' )
		];

		$notAllowedValidSender = $this->newMockUser( [
			'isAllowed' => false,
			'isDefinitelyAllowed' => static function ( $user, StatusValue $status ) {
				$status->fatal( 'badaccess' );
				return false;
			}
		] );

		yield 'Sender is not allowed to send emails' => [
			$notAllowedValidSender,
			PermissionStatus::newFatal( 'badaccess' )
		];

		$block = $this->createMock( AbstractBlock::class );
		$block->expects( $this->atLeastOnce() )
			->method( 'appliesToRight' )
			->with( 'sendemail' )
			->willReturn( true );

		$blockedSender = $this->newMockUser( [
			'getBlock' => $block,
			'isDefinitelyAllowed' => static function ( $action, PermissionStatus $status )
			use ( $block ) {
				if ( $action === 'sendemail' ) {
					$status->setBlock( $block );
					return false;
				}
				return true;
			},
		] );

		$blockedPermission = PermissionStatus::newEmpty();
		$blockedPermission->setBlock( $block );

		yield 'Sender is blocked from emailing users' => [
			$blockedSender,
			$blockedPermission
		];

		$userCanSendEmailError = [ 'first-hook-error', 'first-hook-error-text', [] ];
		$userCanSendEmailHooks = [
			'UserCanSendEmail' => static function ( $user, &$err ) use ( $userCanSendEmailError ) {
				$err = $userCanSendEmailError;
			}
		];
		$expectedStatusFirstHook = StatusValue::newFatal( $userCanSendEmailError[1], ...$userCanSendEmailError[2] );
		$expectedStatusFirstHook->value = $userCanSendEmailError[0];
		yield 'UserCanSendEmail hook error' => [
			$validSender,
			$expectedStatusFirstHook,
			[],
			$userCanSendEmailHooks
		];

		$emailUserPermissionsErrorsError = [ 'second-hook-error', 'second-hook-error-text', [] ];
		$emailUserPermissionsErrorsHooks = [
			'EmailUserPermissionsErrors' =>
				static function ( $user, $token, &$err ) use ( $emailUserPermissionsErrorsError ) {
					$err = $emailUserPermissionsErrorsError;
				}
		];
		$expectedStatusSecondHook = StatusValue::newFatal(
			$emailUserPermissionsErrorsError[1],
			...$emailUserPermissionsErrorsError[2]
		);
		$expectedStatusSecondHook->value = $emailUserPermissionsErrorsError[0];
		yield 'EmailUserPermissionsErrors hook error' => [
			$validSender,
			$expectedStatusSecondHook,
			[],
			$emailUserPermissionsErrorsHooks,
			true
		];

		yield 'Successful' => [ $validSender, StatusValue::newGood() ];
	}

	public function provideAuthorizeSend(): Generator {
		$ratelimitedSender = $this->newMockUser( [
			'pingLimiter' => true,
			'isDefinitelyAllowed' => static function ( $action, PermissionStatus $status ) {
				if ( $action === 'sendemail' ) {
					$status->setRateLimitExceeded();
					return false;
				}
				return true;
			}
		] );

		$rateLimitStatus = PermissionStatus::newEmpty();
		$rateLimitStatus->setRateLimitExceeded();

		yield 'Sender is rate-limited' => [
			$ratelimitedSender,
			$rateLimitStatus
		];

		$validSender = $this->newMockUser();

		$emailUserAuthorizeSendError = 'my-hook-error';
		$emailUserAuthorizeSendHooks = [
			'EmailUserAuthorizeSend' =>
				static function ( $user, StatusValue $status ) use ( $emailUserAuthorizeSendError ) {
					$status->fatal( $emailUserAuthorizeSendError );
					return false;
				}
		];

		yield 'EmailUserAuthorizeSend hook error' => [
			$validSender,
			PermissionStatus::newFatal( $emailUserAuthorizeSendError ),
			[],
			$emailUserAuthorizeSendHooks
		];
	}

	/**
	 * @dataProvider provideSubmit
	 */
	public function testSubmit(
		User $target,
		Authority $sender,
		StatusValue $expected,
		array $hooks = [],
		?IEmailer $emailer = null
	) {
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newFromUserIdentity' )
			->with( $target )
			->willReturn( $target );
		$emailUser = $this->getEmailUser( $sender, null, null, $userFactory, [], $hooks, $emailer );
		$res = $emailUser->sendEmailUnsafe(
			$target,
			'subject',
			'text',
			false,
			'qqx'
		);
		$this->assertEquals( $expected, $res );
	}

	public function provideSubmit(): Generator {
		$validSender = $this->createMock( Authority::class );
		$validTarget = $this->getValidTarget();

		$invalidTarget = $this->createMock( User::class );
		$invalidTarget->method( 'getId' )->willReturn( 0 );
		$invalidTarget->method( 'getUser' )->willReturnSelf();
		yield 'Invalid target' => [ $invalidTarget, $validSender, StatusValue::newFatal( 'emailnotarget' ) ];

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
			$emailUserHookUsingMessageHooks
		];

		$emailUserSendEmailHookError = 'some-pretty-hook-error';
		$emailUserHookUsingStatusHooks = [
			'EmailUserSendEmail' => static function ( $a, $b, $c, $d, $e, $f, StatusValue $status )
				use ( $emailUserSendEmailHookError )
			{
				$status->fatal( $emailUserSendEmailHookError );
				return false;
			}
		];
		yield 'EmailUserSendEmail error as a Status' => [
			$validTarget,
			$validSender,
			StatusValue::newFatal( $emailUserSendEmailHookError ),
			$emailUserHookUsingStatusHooks
		];

		$emailerErrorStatus = StatusValue::newFatal( 'emailer-error' );
		$errorEmailer = $this->createMock( IEmailer::class );
		$errorEmailer->expects( $this->atLeastOnce() )->method( 'send' )->willReturn( $emailerErrorStatus );
		yield 'Error in the Emailer' => [
			$validTarget,
			$validSender,
			$emailerErrorStatus,
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
			[],
			$successEmailer
		];
	}

	private function getValidTarget(): User {
		$validTarget = $this->createMock( User::class );
		$validTarget->method( 'getId' )->willReturn( 1 );
		$validTarget->method( 'isEmailConfirmed' )->willReturn( true );
		$validTarget->method( 'canReceiveEmail' )->willReturn( true );
		$validTarget->method( 'getUser' )->willReturnSelf();
		return $validTarget;
	}
}
