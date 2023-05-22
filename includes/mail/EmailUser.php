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
use MailAddress;
use MediaWiki\Block\AbstractBlock;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\RawMessage;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\Preferences\MultiUsernameFilter;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserOptionsLookup;
use Message;
use MessageLocalizer;
use MessageSpecifier;
use RequestContext;
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
	/** @var UserFactory */
	private UserFactory $userFactory;
	/** @var IEmailer */
	private IEmailer $emailer;

	/** @var Authority */
	private Authority $sender;

	/**
	 * @param ServiceOptions $options
	 * @param HookContainer $hookContainer
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param CentralIdLookup $centralIdLookup
	 * @param UserFactory $userFactory
	 * @param IEmailer $emailer
	 * @param Authority $sender
	 */
	public function __construct(
		ServiceOptions $options,
		HookContainer $hookContainer,
		UserOptionsLookup $userOptionsLookup,
		CentralIdLookup $centralIdLookup,
		UserFactory $userFactory,
		IEmailer $emailer,
		Authority $sender
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userOptionsLookup = $userOptionsLookup;
		$this->centralIdLookup = $centralIdLookup;
		$this->userFactory = $userFactory;
		$this->emailer = $emailer;

		$this->sender = $sender;
	}

	/**
	 * Validate target User
	 *
	 * @param UserEmailContact $target Target user
	 * @return StatusValue
	 */
	public function validateTarget( UserEmailContact $target ): StatusValue {
		$targetIdentity = $target->getUser();
		$targetUser = $this->userFactory->newFromUserIdentity( $targetIdentity );

		if ( !$targetIdentity->getId() ) {
			return StatusValue::newFatal( 'emailnotarget' );
		}

		if ( !$target->isEmailConfirmed() ) {
			return StatusValue::newFatal( 'noemailtext' );
		}

		if ( !$targetUser->canReceiveEmail() ) {
			return StatusValue::newFatal( 'nowikiemailtext' );
		}

		$senderUser = $this->userFactory->newFromAuthority( $this->sender );
		if (
			!$this->userOptionsLookup->getOption( $targetIdentity, 'email-allow-new-users' ) &&
			$senderUser->isNewbie()
		) {
			return StatusValue::newFatal( 'nowikiemailtext' );
		}

		$muteList = $this->userOptionsLookup->getOption(
			$targetIdentity,
			'email-blacklist',
			''
		);
		if ( $muteList ) {
			$muteList = MultiUsernameFilter::splitIds( $muteList );
			$senderId = $this->centralIdLookup->centralIdFromLocalUser( $this->sender->getUser() );
			if ( $senderId !== 0 && in_array( $senderId, $muteList ) ) {
				return StatusValue::newFatal( 'nowikiemailtext' );
			}
		}

		return StatusValue::newGood();
	}

	/**
	 * Check whether a user is allowed to send email
	 *
	 * @param string $editToken
	 * @return StatusValue For BC, the StatusValue's value can be set to a string representing a message key to use
	 * with ErrorPageError.
	 */
	public function getPermissionsError( string $editToken ): StatusValue {
		if (
			!$this->options->get( MainConfigNames::EnableEmail ) ||
			!$this->options->get( MainConfigNames::EnableUserEmail )
		) {
			return StatusValue::newFatal( 'usermaildisabled' );
		}

		$user = $this->userFactory->newFromAuthority( $this->sender );

		// Run this before checking 'sendemail' permission
		// to show appropriate message to anons (T160309)
		if ( !$user->isEmailConfirmed() ) {
			return StatusValue::newFatal( 'mailnologin' );
		}

		if ( !$this->sender->isAllowed( 'sendemail' ) ) {
			return StatusValue::newFatal( 'badaccess' );
		}

		$block = $this->sender->getBlock();
		if ( $block instanceof AbstractBlock && $block->appliesToRight( 'sendemail' ) ) {
			return StatusValue::newFatal( $this->getBlockedMessage( $user ) );
		}

		// Check the ping limiter without incrementing it - we'll check it
		// again later and increment it on a successful send
		if ( $user->pingLimiter( 'sendemail', 0 ) ) {
			return StatusValue::newFatal( 'actionthrottledtext' );
		}

		$hookErr = false;

		// XXX Replace these hooks with versions that simply use StatusValue for errors.
		$this->hookRunner->onUserCanSendEmail( $user, $hookErr );
		$this->hookRunner->onEmailUserPermissionsErrors( $user, $editToken, $hookErr );
		if ( is_array( $hookErr ) ) {
			// SpamBlacklist uses null for the third element, and there might be more handlers not using an array.
			$msgParamsArray = is_array( $hookErr[2] ) ? $hookErr[2] : [];
			$ret = StatusValue::newFatal( $hookErr[1], ...$msgParamsArray );
			$ret->value = $hookErr[0];
			return $ret;
		}

		return StatusValue::newGood();
	}

	/**
	 * Really send a mail. Permissions should have been checked using
	 * getPermissionsError(). It is probably also a good
	 * idea to check the edit token and ping limiter in advance.
	 *
	 * @param UserEmailContact $target
	 * @param string $subject
	 * @param string $text
	 * @param bool $CCMe
	 * @param MessageLocalizer $messageLocalizer
	 * @return StatusValue
	 */
	public function submit(
		UserEmailContact $target,
		string $subject,
		string $text,
		bool $CCMe,
		MessageLocalizer $messageLocalizer
	): StatusValue {
		$senderIdentity = $this->sender->getUser();
		$senderUser = $this->userFactory->newFromAuthority( $this->sender );
		$targetStatus = $this->validateTarget( $target );
		if ( !$targetStatus->isGood() ) {
			return $targetStatus;
		}

		// Check and increment the rate limits
		if ( $senderUser->pingLimiter( 'sendemail' ) ) {
			throw $this->getThrottledError();
		}

		$toAddress = MailAddress::newFromUser( $target );
		$fromAddress = MailAddress::newFromUser( $senderUser );

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
					$this->getSpecialMuteCanonicalURL( $senderIdentity->getName() ),
					$senderIdentity->getName()
				)->inContentLanguage()->text();
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
				$target->getUser()->getName(),
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

	/**
	 * XXX Temporary method to obtain a message for blocked users. This code shouldn't be here, and we should just
	 * use Authority/PermissionManager to obtain a message. So don't bother making this pretty.
	 * @param User $user
	 * @return Message
	 * @codeCoverageIgnore
	 */
	private function getBlockedMessage( User $user ): Message {
		if ( defined( 'MW_PHPUNIT_TEST' ) ) {
			return new RawMessage( 'You shall not send' );
		}
		$blockErrorFormatter = MediaWikiServices::getInstance()->getBlockErrorFormatter();
		$block = $user->getBlock();
		if ( !$block ) {
			throw new BadMethodCallException( 'This method should only be called if the user is blocked' );
		}
		return $blockErrorFormatter->getMessage(
			$block,
			$user,
			RequestContext::getMain()->getLanguage(),
			RequestContext::getMain()->getRequest()->getIP()
		);
	}
}
