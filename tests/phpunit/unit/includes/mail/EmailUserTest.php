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
		$targetSpec,
		$senderSpec,
		StatusValue $expected,
		?array $userOptions = null,
		?int $senderCentralID = null
	) {
		if ( $targetSpec === 'anon' ) {
			$target = $this->createMock( User::class );
			$target->expects( $this->atLeastOnce() )->method( 'getId' )->willReturn( 0 );
			$target->method( 'getUser' )->willReturnSelf();
		} elseif ( $targetSpec === 'nonConfirmed' ) {
			$target = $this->createMock( User::class );
			$target->method( 'getId' )->willReturn( 1 );
			$target->expects( $this->atLeastOnce() )->method( 'isEmailConfirmed' )->willReturn( false );
			$target->method( 'getUser' )->willReturnSelf();
		} elseif ( $targetSpec === 'cannotReceive' ) {
			$target = $this->createMock( User::class );
			$target->method( 'getId' )->willReturn( 1 );
			$target->method( 'isEmailConfirmed' )->willReturn( true );
			$target->expects( $this->atLeastOnce() )->method( 'canReceiveEmail' )->willReturn( false );
			$target->method( 'getUser' )->willReturnSelf();
		} else {
			$target = $this->getValidTarget();
		}
		if ( $senderSpec === 'newbie' ) {
			$sender = $this->createMock( User::class );
			$sender->expects( $this->atLeastOnce() )->method( 'isNewbie' )->willReturn( true );
		} else {
			$sender = $this->createMock( User::class );
			$sender->method( 'getUser' )->willReturnSelf();
		}
		if ( $userOptions !== null ) {
			$userOptionsLookup = $this->createMock( UserOptionsLookup::class );
			$optionName = array_key_first( $userOptions );
			$optionValue = $userOptions[$optionName];
			$userOptionsLookup->expects( $this->atLeastOnce() )
				->method( 'getOption' )
				->willReturnCallback(
					function ( UserIdentity $user, string $option ) use ( $target, $optionName, $optionValue ) {
						$this->assertSame( $target->getUser(), $user );
						return $option === $optionName ? $optionValue : null;
					}
				);
		} else {
			$userOptionsLookup = null;
		}
		if ( $senderCentralID !== null ) {
			$centralIdLookup = $this->createMock( CentralIdLookup::class );
			$centralIdLookup->expects( $this->atLeastOnce() )
				->method( 'centralIdFromLocalUser' )
				->with( $sender )
				->willReturn( $senderCentralID );
		} else {
			$centralIdLookup = null;
		}

		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newFromAuthority' )->willReturn( $sender );
		$userFactory->method( 'newFromUserIdentity' )->willReturn( $target );
		$emailUser = $this->getEmailUser( $sender, $userOptionsLookup, $centralIdLookup, $userFactory );
		$this->assertEquals( $expected, $emailUser->validateTarget( $target ) );
	}

	public static function provideValidateTarget(): Generator {
		yield 'Target has user ID 0' => [ 'anon', 'noop', StatusValue::newFatal( 'emailnotarget' ) ];

		yield 'Target does not have confirmed email' => [
			'nonConfirmed',
			'noop',
			StatusValue::newFatal( 'noemailtext' )
		];

		yield 'Target cannot receive emails' => [
			'cannotReceive',
			'noop',
			StatusValue::newFatal( 'nowikiemailtext' )
		];

		yield 'Target does not allow emails from newbie and sender is newbie' => [
			'valid',
			'newbie',
			StatusValue::newFatal( 'nowikiemailtext' ),
			[ 'email-allow-new-users' => false ],
		];

		$senderCentralID = 42;
		yield 'Target muted the sender' => [
			'valid',
			'noop',
			StatusValue::newFatal( 'nowikiemailtext' ),
			[ 'email-blacklist' => (string)$senderCentralID ],
			$senderCentralID
		];

		yield 'Valid' => [ 'valid', 'noop', StatusValue::newGood() ];
	}

	/**
	 * @dataProvider provideCanSend
	 * @dataProvider provideAuthorizeSend
	 */
	public function testAuthorizeSend(
		$performerUserSpec,
		$expected,
		array $configOverrides = [],
		array $hooks = [],
		bool $expectDeprecation = false
	) {
		if ( is_callable( $performerUserSpec ) ) {
			$performerUserSpec = $performerUserSpec( $this );
		}
		if ( is_callable( $expected ) ) {
			$expected = $expected( $this );
		}
		$performerUser = $this->newMockUser( $performerUserSpec );
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
		$performerUserSpec,
		$expected,
		array $configOverrides = [],
		array $hooks = [],
		bool $expectDeprecation = false
	) {
		if ( is_callable( $performerUserSpec ) ) {
			$performerUserSpec = $performerUserSpec( $this );
		}
		if ( is_callable( $expected ) ) {
			$expected = $expected( $this );
		}
		$performerUser = $this->newMockUser( $performerUserSpec );
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

	public static function provideCanSend(): Generator {
		$validSender = [];

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

		$noEmailSender = [ 'isEmailConfirmed' => false ];
		yield 'Sender does not have an email' => [
			$noEmailSender,
			StatusValue::newFatal( 'mailnologin' )
		];

		$notAllowedValidSender = [
			'isAllowed' => false,
			'isDefinitelyAllowed' => static function ( $user, StatusValue $status ) {
				$status->fatal( 'badaccess' );
				return false;
			}
		];

		yield 'Sender is not allowed to send emails' => [
			$notAllowedValidSender,
			PermissionStatus::newFatal( 'badaccess' )
		];

		$blockedSender = static function ( $testCase ) {
			$block = $testCase->createMock( AbstractBlock::class );
			return [
				'getBlock' => $block,
				'isDefinitelyAllowed' => static function ( $action, PermissionStatus $status ) use ( $block ) {
					if ( $action === 'sendemail' ) {
						$status->setBlock( $block );
						return false;
					}
					return true;
				},
			];
		};

		$blockedPermission = static function ( $testCase ) {
			$block = $testCase->createMock( AbstractBlock::class );
			$blockedPermission = PermissionStatus::newEmpty();
			$blockedPermission->setBlock( $block );
			return $blockedPermission;
		};

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

	public static function provideAuthorizeSend(): Generator {
		$ratelimitedSender = [
			'pingLimiter' => true,
			'isDefinitelyAllowed' => static function ( $action, PermissionStatus $status ) {
				if ( $action === 'sendemail' ) {
					$status->setRateLimitExceeded();
					return false;
				}
				return true;
			}
		];

		$rateLimitStatus = PermissionStatus::newEmpty();
		$rateLimitStatus->setRateLimitExceeded();

		yield 'Sender is rate-limited' => [
			$ratelimitedSender,
			$rateLimitStatus
		];

		$validSender = [];

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
		string $targetSpec,
		StatusValue $expected,
		array $hooks = [],
		?StatusValue $emailerStatus = null
	) {
		if ( $targetSpec === 'invalid' ) {
			$target = $this->createMock( User::class );
			$target->method( 'getId' )->willReturn( 0 );
			$target->method( 'getUser' )->willReturnSelf();
		} else {
			$target = $this->getValidTarget();
		}
		$sender = $this->createMock( Authority::class );
		if ( $emailerStatus !== null ) {
			$emailer = $this->createMock( IEmailer::class );
			$emailer->expects( $this->atLeastOnce() )->method( 'send' )->willReturn( $emailerStatus );
		} else {
			$emailer = null;
		}

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

	public static function provideSubmit(): Generator {
		yield 'Invalid target' => [ 'invalid', StatusValue::newFatal( 'emailnotarget' ) ];

		$hookStatusError = StatusValue::newFatal( 'some-hook-error' );
		$emailUserHookUsingStatusHooks = [
			'EmailUser' => static function ( $a, $b, $c, $d, &$err ) use ( $hookStatusError ) {
				$err = $hookStatusError;
				return false;
			}
		];
		yield 'EmailUserHook error as a Status' => [
			'valid',
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
			'valid',
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
			'valid',
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
			'valid',
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
			'valid',
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
			'valid',
			StatusValue::newFatal( $emailUserSendEmailHookError ),
			$emailUserHookUsingStatusHooks
		];

		$emailerErrorStatus = StatusValue::newFatal( 'emailer-error' );
		yield 'Error in the Emailer' => [
			'valid',
			$emailerErrorStatus,
			[],
			$emailerErrorStatus
		];

		$emailerSuccessStatus = StatusValue::newGood( 42 );
		yield 'Successful' => [
			'valid',
			$emailerSuccessStatus,
			[],
			$emailerSuccessStatus
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
