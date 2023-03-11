<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Mail;

use BadMethodCallException;
use CentralIdLookup;
use Config;
use MailAddress;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Preferences\MultiUsernameFilter;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserOptionsLookup;
use MessageLocalizer;
use MessageSpecifier;
use RuntimeException;
use SpecialPage;
use StatusValue;
use ThrottledError;
use UnexpectedValueException;
use User;

/**
 * Command for sending emails to users.
 *
 * @since 1.40
 * @unstable
 */
class EmailUser {
	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::EnableEmail,
		MainConfigNames::EnableUserEmail,
		MainConfigNames::EnableSpecialMute,
		MainConfigNames::PasswordSender,
		MainConfigNames::UserEmailUseReplyTo,
	];

	/** @var ServiceOptions */
	private ServiceOptions $options;
	/** @var HookRunner */
	private HookRunner $hookRunner;
	/** @var UserOptionsLookup */
	private UserOptionsLookup $userOptionsLookup;
	/** @var CentralIdLookup */
	private CentralIdLookup $centralIdLookup;
	/** @var PermissionManager */
	private PermissionManager $permissionManager;
	/** @var UserFactory */
	private UserFactory $userFactory;
	/** @var IEmailer */
	private IEmailer $emailer;

	/** @var ServiceOptions|null Temporary property for BC with SpecialEmailUser */
	private ?ServiceOptions $oldOptions;

	/**
	 * @param ServiceOptions $options
	 * @param HookContainer $hookContainer
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param CentralIdLookup $centralIdLookup
	 * @param PermissionManager $permissionManager
	 * @param UserFactory $userFactory
	 * @param IEmailer $emailer
	 */
	public function __construct(
		ServiceOptions $options,
		HookContainer $hookContainer,
		UserOptionsLookup $userOptionsLookup,
		CentralIdLookup $centralIdLookup,
		PermissionManager $permissionManager,
		UserFactory $userFactory,
		IEmailer $emailer
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userOptionsLookup = $userOptionsLookup;
		$this->centralIdLookup = $centralIdLookup;
		$this->permissionManager = $permissionManager;
		$this->userFactory = $userFactory;
		$this->emailer = $emailer;
	}

	/**
	 * Validate target User
	 *
	 * @param string $target Target user name
	 * @param User $sender User sending the email
	 * @return User|string User object on success or a string on error
	 */
	public function getTarget( string $target, User $sender ) {
		$nu = $this->userFactory->newFromName( $target );
		if ( !$nu instanceof User ) {
			return 'notarget';
		}
		$error = $this->validateTarget( $nu, $sender );
		return $error ?: $nu;
	}

	/**
	 * Validate target User
	 *
	 * @param User $target Target user
	 * @param User $sender User sending the email
	 * @return string Error message or empty string if valid.
	 */
	public function validateTarget( User $target, User $sender ): string {
		if ( !$target->getId() ) {
			return 'notarget';
		}

		if ( !$target->isEmailConfirmed() ) {
			return 'noemail';
		}

		if ( !$target->canReceiveEmail() ) {
			return 'nowikiemail';
		}

		if ( !$this->userOptionsLookup->getOption( $target, 'email-allow-new-users' ) && $sender->isNewbie() ) {
			return 'nowikiemail';
		}

		$muteList = $this->userOptionsLookup->getOption(
			$target,
			'email-blacklist',
			''
		);
		if ( $muteList ) {
			$muteList = MultiUsernameFilter::splitIds( $muteList );
			$senderId = $this->centralIdLookup->centralIdFromLocalUser( $sender );
			if ( $senderId !== 0 && in_array( $senderId, $muteList ) ) {
				return 'nowikiemail';
			}
		}

		return '';
	}

	/**
	 * @param Config $config
	 * @internal Kept only for BC with SpecialEmailUser
	 * @codeCoverageIgnore
	 */
	public function overrideOptionsFromConfig( Config $config ): void {
		$this->oldOptions = $this->options;
		$this->options = new ServiceOptions( self::CONSTRUCTOR_OPTIONS, $config );
	}

	/**
	 * @internal Kept only for BC with SpecialEmailUser
	 * @codeCoverageIgnore
	 */
	public function restoreOriginalOptions(): void {
		if ( !$this->oldOptions ) {
			throw new BadMethodCallException( 'Did not override options.' );
		}
		$this->options = $this->oldOptions;
	}

	/**
	 * Check whether a user is allowed to send email
	 *
	 * @param User $user
	 * @param string $editToken
	 * @return null|string|array Null on success, string on error, or array on
	 *  hook error
	 */
	public function getPermissionsError( User $user, string $editToken ) {
		if (
			!$this->options->get( MainConfigNames::EnableEmail ) ||
			!$this->options->get( MainConfigNames::EnableUserEmail )
		) {
			return 'usermaildisabled';
		}

		// Run this before checking 'sendemail' permission
		// to show appropriate message to anons (T160309)
		if ( !$user->isEmailConfirmed() ) {
			return 'mailnologin';
		}

		if ( !$this->permissionManager->userHasRight( $user, 'sendemail' ) ) {
			return 'badaccess';
		}

		if ( $user->isBlockedFromEmailuser() ) {
			return "blockedemailuser";
		}

		// Check the ping limiter without incrementing it - we'll check it
		// again later and increment it on a successful send
		if ( $user->pingLimiter( 'sendemail', 0 ) ) {
			return 'actionthrottledtext';
		}

		$hookErr = false;

		$this->hookRunner->onUserCanSendEmail( $user, $hookErr );
		$this->hookRunner->onEmailUserPermissionsErrors( $user, $editToken, $hookErr );

		return $hookErr ?: null;
	}

	/**
	 * Really send a mail. Permissions should have been checked using
	 * getPermissionsError(). It is probably also a good
	 * idea to check the edit token and ping limiter in advance.
	 *
	 * @param string $targetName
	 * @param string $subject
	 * @param string $text
	 * @param bool $CCMe
	 * @param User $sender
	 * @param MessageLocalizer $messageLocalizer
	 * @return StatusValue
	 */
	public function submit(
		string $targetName,
		string $subject,
		string $text,
		bool $CCMe,
		User $sender,
		MessageLocalizer $messageLocalizer
	): StatusValue {
		$target = $this->getTarget( $targetName, $sender );
		if ( !$target instanceof User ) {
			// Messages used here: notargettext, noemailtext, nowikiemailtext
			return StatusValue::newFatal( $target . 'text' );
		}

		$toAddress = MailAddress::newFromUser( $target );
		$fromAddress = MailAddress::newFromUser( $sender );

		// Add a standard footer and trim up trailing newlines
		$text = rtrim( $text ) . "\n\n-- \n";
		$text .= $messageLocalizer->msg(
			'emailuserfooter',
			$fromAddress->name,
			$toAddress->name
		)->inContentLanguage()->text();

		if ( $this->options->get( MainConfigNames::EnableSpecialMute ) ) {
			$text .= "\n" . $messageLocalizer->msg(
					'specialmute-email-footer',
					$this->getSpecialMuteCanonicalURL( $sender->getName() ),
					$sender->getName()
				)->inContentLanguage()->text();
		}

		// Check and increment the rate limits
		if ( $sender->pingLimiter( 'sendemail' ) ) {
			throw $this->getThrottledError();
		}

		$error = false;
		// FIXME Replace this hook with a new one that returns errors in a SINGLE format.
		if ( !$this->hookRunner->onEmailUser( $toAddress, $fromAddress, $subject, $text, $error ) ) {
			if ( $error instanceof StatusValue ) {
				return $error;
			} elseif ( $error === false || $error === '' || $error === [] ) {
				// Possibly to tell HTMLForm to pretend there was no submission?
				return StatusValue::newFatal( 'hookaborted' );
			} elseif ( $error === true ) {
				// Hook sent the mail itself and indicates success?
				return StatusValue::newGood();
			} elseif ( is_array( $error ) ) {
				$status = StatusValue::newGood();
				foreach ( $error as $e ) {
					$status->fatal( $e );
				}
				return $status;
			} elseif ( $error instanceof MessageSpecifier ) {
				return StatusValue::newFatal( $error );
			} else {
				// Setting $error to something else was deprecated in 1.29 and
				// removed in 1.36, and so an exception is now thrown
				$type = is_object( $error ) ? get_class( $error ) : gettype( $error );
				throw new UnexpectedValueException(
					'EmailUser hook set $error to unsupported type ' . $type
				);
			}
		}

		[ $mailFrom, $replyTo ] = $this->getFromAndReplyTo( $fromAddress, $messageLocalizer );

		$status = $this->emailer->send(
			$toAddress,
			$mailFrom,
			$subject,
			$text,
			null,
			[ 'replyTo' => $replyTo ]
		);

		if ( !$status->isGood() ) {
			return $status;
		}

		// if the user requested a copy of this mail, do this now,
		// unless they are emailing themselves, in which case one
		// copy of the message is sufficient.
		if ( $CCMe && !$toAddress->equals( $fromAddress ) ) {
			$ccTo = $fromAddress;
			$ccFrom = $fromAddress;
			$ccSubject = $messageLocalizer->msg( 'emailccsubject' )->plaintextParams(
				$target->getName(),
				$subject
			)->text();
			$ccText = $text;

			$this->hookRunner->onEmailUserCC( $ccTo, $ccFrom, $ccSubject, $ccText );

			[ $mailFrom, $replyTo ] = $this->getFromAndReplyTo( $ccFrom, $messageLocalizer );

			$ccStatus = $this->emailer->send(
				$ccTo,
				$mailFrom,
				$ccSubject,
				$ccText,
				null,
				[ 'replyTo' => $replyTo ]
			);
			$status->merge( $ccStatus );
		}

		$this->hookRunner->onEmailUserComplete( $toAddress, $fromAddress, $subject, $text );

		return $status;
	}

	/**
	 * @param MailAddress $fromAddress
	 * @param MessageLocalizer $messageLocalizer
	 * @return array
	 * @phan-return array{0:MailAddress,1:?MailAddress}
	 */
	private function getFromAndReplyTo( MailAddress $fromAddress, MessageLocalizer $messageLocalizer ): array {
		if ( $this->options->get( MainConfigNames::UserEmailUseReplyTo ) ) {
			/**
			 * Put the generic wiki autogenerated address in the From:
			 * header and reserve the user for Reply-To.
			 *
			 * This is a bit ugly, but will serve to differentiate
			 * wiki-borne mails from direct mails and protects against
			 * SPF and bounce problems with some mailers (see below).
			 */
			$mailFrom = new MailAddress(
				$this->options->get( MainConfigNames::PasswordSender ),
				$messageLocalizer->msg( 'emailsender' )->inContentLanguage()->text()
			);
			$replyTo = $fromAddress;
		} else {
			/**
			 * Put the sending user's e-mail address in the From: header.
			 *
			 * This is clean-looking and convenient, but has issues.
			 * One is that it doesn't as clearly differentiate the wiki mail
			 * from "directly" sent mails.
			 *
			 * Another is that some mailers (like sSMTP) will use the From
			 * address as the envelope sender as well. For open sites this
			 * can cause mails to be flunked for SPF violations (since the
			 * wiki server isn't an authorized sender for various users'
			 * domains) as well as creating a privacy issue as bounces
			 * containing the recipient's e-mail address may get sent to
			 * the sending user.
			 */
			$mailFrom = $fromAddress;
			$replyTo = null;
		}
		return [ $mailFrom, $replyTo ];
	}

	/**
	 * @param string $targetName
	 * @return string
	 * XXX This code is still heavily reliant on global state, so temporarily skip it in tests.
	 * @codeCoverageIgnore
	 */
	private function getSpecialMuteCanonicalURL( string $targetName ): string {
		if ( defined( 'MW_PHPUNIT_TEST' ) ) {
			return "Ceci n'est pas une URL";
		}
		return SpecialPage::getTitleFor( 'Mute', $targetName )->getCanonicalURL();
	}

	/**
	 * @return RuntimeException|ThrottledError
	 * XXX ErrorPageError (that ThrottledError inherits from) runs heavy logic involving the global state in the
	 * constructor, and cannot be used in unit tests. See T281935.
	 * @codeCoverageIgnore
	 */
	private function getThrottledError() {
		if ( defined( 'MW_PHPUNIT_TEST' ) ) {
			return new RuntimeException( "You are throttled, and I am not running heavy logic in the constructor" );
		}
		return new ThrottledError();
	}
}
