<?php

namespace MediaWiki\Tests\Unit\Mail;

use CentralIdLookup;
use Generator;
use MediaWiki\Block\AbstractBlock;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Language\RawMessage;
use MediaWiki\Mail\EmailUser;
use MediaWiki\Mail\IEmailer;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
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
		Authority $sender,
		UserOptionsLookup $userOptionsLookup = null,
		CentralIdLookup $centralIdLookup = null,
		UserFactory $userFactory = null,
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
			$userFactory ?? $this->createMock( UserFactory::class ),
			$emailer ?? $this->createMock( IEmailer::class ),
			$sender
		);
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
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newFromAuthority' )->willReturn( $sender );
		$userFactory->method( 'newFromUserIdentity' )->willReturn( $target );
		$emailUser = $this->getEmailUser( $sender, $userOptionsLookup, $centralIdLookup, $userFactory );
		$this->assertEquals( $expected, $emailUser->validateTarget( $target ) );
	}

	public function provideValidateTarget(): Generator {
		$noopUserMock = $this->createMock( User::class );
		$noopUserMock->method( 'getUser' )->willReturn( $noopUserMock );
		$validTarget = $this->getValidTarget();

		$anonTarget = $this->createMock( User::class );
		$anonTarget->expects( $this->atLeastOnce() )->method( 'getId' )->willReturn( 0 );
		$anonTarget->method( 'getUser' )->willReturn( $anonTarget );
		yield 'Target has user ID 0' => [ $anonTarget, $noopUserMock, StatusValue::newFatal( 'emailnotarget' ) ];

		$emailNotConfirmedTarget = $this->createMock( User::class );
		$emailNotConfirmedTarget->method( 'getId' )->willReturn( 1 );
		$emailNotConfirmedTarget->expects( $this->atLeastOnce() )->method( 'isEmailConfirmed' )->willReturn( false );
		$emailNotConfirmedTarget->method( 'getUser' )->willReturn( $emailNotConfirmedTarget );
		yield 'Target does not have confirmed email' => [
			$emailNotConfirmedTarget,
			$noopUserMock,
			StatusValue::newFatal( 'noemailtext' )
		];

		$cannotReceiveEmailsTarget = $this->createMock( User::class );
		$cannotReceiveEmailsTarget->method( 'getId' )->willReturn( 1 );
		$cannotReceiveEmailsTarget->method( 'isEmailConfirmed' )->willReturn( true );
		$cannotReceiveEmailsTarget->expects( $this->atLeastOnce() )->method( 'canReceiveEmail' )->willReturn( false );
		$cannotReceiveEmailsTarget->method( 'getUser' )->willReturn( $cannotReceiveEmailsTarget );
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
		$this->assertEquals( $expected, $emailUser->getPermissionsError( 'some-token' ) );
	}

	public function providePermissionsError(): Generator {
		$validSender = $this->createMock( User::class );
		$validSender->method( 'isEmailConfirmed' )->willReturn( true );
		$validSender->method( 'isAllowed' )->willReturn( true );

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

		$noEmailSender = $this->createMock( User::class );
		$noEmailSender->expects( $this->atLeastOnce() )->method( 'isEmailConfirmed' )->willReturn( false );
		yield 'Sender does not have an email' => [ $noEmailSender, StatusValue::newFatal( 'mailnologin' ) ];

		$notAllowedValidSender = $this->createMock( User::class );
		$notAllowedValidSender->method( 'isEmailConfirmed' )->willReturn( true );
		$notAllowedValidSender->expects( $this->atLeastOnce() )
			->method( 'isAllowed' )
			->with( 'sendemail' )
			->willReturn( false );
		yield 'Sender is not allowed to send emails' => [
			$notAllowedValidSender,
			StatusValue::newFatal( 'badaccess' )
		];

		$blockedSender = $this->createMock( User::class );
		$blockedSender->method( 'isEmailConfirmed' )->willReturn( true );
		$blockedSender->method( 'isAllowed' )->with( 'sendemail' )->willReturn( true );
		$block = $this->createMock( AbstractBlock::class );
		$block->expects( $this->atLeastOnce() )
			->method( 'appliesToRight' )
			->with( 'sendemail' )
			->willReturn( true );
		$blockedSender->expects( $this->atLeastOnce() )
			->method( 'getBlock' )
			->willReturn( $block );
		yield 'Sender is blocked from emailing users' => [
			$blockedSender,
			StatusValue::newFatal( new RawMessage( 'You shall not send' ) )
		];

		$ratelimitedSender = $this->createMock( User::class );
		$ratelimitedSender->method( 'isEmailConfirmed' )->willReturn( true );
		$ratelimitedSender->method( 'isAllowed' )->with( 'sendemail' )->willReturn( true );
		$ratelimitedSender->expects( $this->atLeastOnce() )
			->method( 'pingLimiter' )
			->with( 'sendemail' )
			->willReturn( true );
		yield 'Sender is rate-limited' => [
			$ratelimitedSender,
			StatusValue::newFatal( 'actionthrottledtext' )
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

	/**
	 * @covers ::submit
	 * @dataProvider provideSubmit
	 */
	public function testSubmit(
		User $target,
		Authority $sender,
		StatusValue $expected,
		array $hooks = [],
		IEmailer $emailer = null
	) {
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newFromUserIdentity' )
			->with( $target )
			->willReturn( $target );
		$emailUser = $this->getEmailUser( $sender, null, null, $userFactory, [], $hooks, $emailer );
		$res = $emailUser->submit(
			$target,
			'subject',
			'text',
			false,
			$this->getMockMessageLocalizer()
		);
		$this->assertEquals( $expected, $res );
	}

	public function provideSubmit(): Generator {
		$validSender = $this->createMock( Authority::class );
		$validTarget = $this->getValidTarget();

		$invalidTarget = $this->createMock( User::class );
		$invalidTarget->method( 'getId' )->willReturn( 0 );
		$invalidTarget->method( 'getUser' )->willReturn( $invalidTarget );
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

	/**
	 * @covers ::submit
	 */
	public function testSubmit__rateLimited() {
		$senderUser = $this->createMock( User::class );
		$senderUser->method( 'pingLimiter' )->with( 'sendemail' )->willReturn( true );
		$senderUserFactory = $this->createMock( UserFactory::class );
		$senderUserFactory->method( 'newFromAuthority' )->willReturn( $senderUser );
		$senderUserFactory->method( 'newFromUserIdentity' )->willReturnArgument( 0 );

		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'You are throttled' );
		$res = $this->getEmailUser( $senderUser, null, null, $senderUserFactory )->submit(
			$this->getValidTarget(),
			'subject',
			'text',
			false,
			$this->getMockMessageLocalizer()
		);
		// This assertion should not be reached if the test passes, but it can be helpful to determine why
		// the test is failing.
		$this->assertStatusGood( $res );
	}

	private function getValidTarget(): User {
		$validTarget = $this->createMock( User::class );
		$validTarget->method( 'getId' )->willReturn( 1 );
		$validTarget->method( 'isEmailConfirmed' )->willReturn( true );
		$validTarget->method( 'canReceiveEmail' )->willReturn( true );
		$validTarget->method( 'getUser' )->willReturn( $validTarget );
		return $validTarget;
	}
}
