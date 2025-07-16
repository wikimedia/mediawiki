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

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Preferences\MultiUsernameFilter;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\UserFactory;
use StatusValue;
use UnexpectedValueException;
use Wikimedia\Message\IMessageFormatterFactory;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;

/**
 * Send email between two wiki users.
 *
 * Obtain via EmailUserFactory
 *
 * This class is stateless and can be used for multiple sends.
 *
 * @since 1.40
 * @ingroup Mail
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

	private ServiceOptions $options;
	private HookRunner $hookRunner;
	private UserOptionsLookup $userOptionsLookup;
	private CentralIdLookup $centralIdLookup;
	private UserFactory $userFactory;
	private IEmailer $emailer;
	private IMessageFormatterFactory $messageFormatterFactory;
	private ITextFormatter $contLangMsgFormatter;
	private Authority $sender;

	/** @var string Temporary property to support the deprecated EmailUserPermissionsErrors hook */
	private string $editToken = '';

	/**
	 * @internal For use by EmailUserFactory.
	 *
	 * @param ServiceOptions $options
	 * @param HookContainer $hookContainer
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param CentralIdLookup $centralIdLookup
	 * @param UserFactory $userFactory
	 * @param IEmailer $emailer
	 * @param IMessageFormatterFactory $messageFormatterFactory
	 * @param ITextFormatter $contLangMsgFormatter
	 * @param Authority $sender
	 */
	public function __construct(
		ServiceOptions $options,
		HookContainer $hookContainer,
		UserOptionsLookup $userOptionsLookup,
		CentralIdLookup $centralIdLookup,
		UserFactory $userFactory,
		IEmailer $emailer,
		IMessageFormatterFactory $messageFormatterFactory,
		ITextFormatter $contLangMsgFormatter,
		Authority $sender
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userOptionsLookup = $userOptionsLookup;
		$this->centralIdLookup = $centralIdLookup;
		$this->userFactory = $userFactory;
		$this->emailer = $emailer;
		$this->messageFormatterFactory = $messageFormatterFactory;
		$this->contLangMsgFormatter = $contLangMsgFormatter;

		$this->sender = $sender;
	}

	/**
	 * @internal
	 * @todo This method might perhaps be moved to a UserEmailContactLookup or something.
	 *
	 * @param UserEmailContact $target Target user
	 * @return StatusValue
	 */
	public function validateTarget( UserEmailContact $target ): StatusValue {
		$targetIdentity = $target->getUser();

		if ( !$targetIdentity->getId() ) {
			return StatusValue::newFatal( 'emailnotarget' );
		}

		if ( !$target->isEmailConfirmed() ) {
			return StatusValue::newFatal( 'noemailtext' );
		}

		$targetUser = $this->userFactory->newFromUserIdentity( $targetIdentity );
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
	 * Checks whether email sending is allowed.
	 *
	 * @return StatusValue For BC, the StatusValue's value can be set to a string representing
	 * a message key to use with ErrorPageError. Only SpecialEmailUser should rely on this.
	 */
	public function canSend(): StatusValue {
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

		$status = PermissionStatus::newGood();
		if ( !$this->sender->isDefinitelyAllowed( 'sendemail', $status ) ) {
			return $status;
		}

		$hookErr = false;

		// TODO Remove deprecated hooks
		$this->hookRunner->onUserCanSendEmail( $user, $hookErr );
		$this->hookRunner->onEmailUserPermissionsErrors( $user, $this->editToken, $hookErr );
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
	 * Authorize the email sending, checking permissions etc.
	 *
	 * @return StatusValue For BC, the StatusValue's value can be set to a string representing
	 * a message key to use with ErrorPageError. Only SpecialEmailUser should rely on this.
	 */
	public function authorizeSend(): StatusValue {
		$status = $this->canSend();
		if ( !$status->isOK() ) {
			return $status;
		}

		$status = PermissionStatus::newGood();
		if ( !$this->sender->authorizeAction( 'sendemail', $status ) ) {
			return $status;
		}

		$hookRes = $this->hookRunner->onEmailUserAuthorizeSend( $this->sender, $status );
		if ( !$hookRes && !$status->isGood() ) {
			return $status;
		}

		return StatusValue::newGood();
	}

	/**
	 * Really send a mail, without permission checks.
	 *
	 * @param UserEmailContact $target
	 * @param string $subject
	 * @param string $text
	 * @param bool $CCMe
	 * @param string $langCode Code of the language to be used for interface messages
	 * @return StatusValue
	 */
	public function sendEmailUnsafe(
		UserEmailContact $target,
		string $subject,
		string $text,
		bool $CCMe,
		string $langCode
	): StatusValue {
		$senderIdentity = $this->sender->getUser();
		$targetStatus = $this->validateTarget( $target );
		if ( !$targetStatus->isGood() ) {
			return $targetStatus;
		}

		$senderUser = $this->userFactory->newFromAuthority( $this->sender );

		$toAddress = MailAddress::newFromUser( $target );
		$fromAddress = MailAddress::newFromUser( $senderUser );

		// Add a standard footer and trim up trailing newlines
		$text = rtrim( $text ) . "\n\n-- \n";
		$text .= $this->contLangMsgFormatter->format(
			MessageValue::new( 'emailuserfooter', [ $fromAddress->name, $toAddress->name ] )
		);

		if ( $this->options->get( MainConfigNames::EnableSpecialMute ) ) {
			$text .= "\n" . $this->contLangMsgFormatter->format(
				MessageValue::new(
					'specialmute-email-footer',
					[
						$this->getSpecialMuteCanonicalURL( $senderIdentity->getName() ),
						$senderIdentity->getName()
					]
				)
			);
		}

		$error = false;
		// TODO Remove deprecated ugly hook
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
				$type = get_debug_type( $error );
				throw new UnexpectedValueException(
					'EmailUser hook set $error to unsupported type ' . $type
				);
			}
		}

		$hookStatus = StatusValue::newGood();
		$hookRes = $this->hookRunner->onEmailUserSendEmail(
			$this->sender,
			$fromAddress,
			$target,
			$toAddress,
			$subject,
			$text,
			$hookStatus
		);
		if ( !$hookRes && !$hookStatus->isGood() ) {
			return $hookStatus;
		}

		[ $mailFrom, $replyTo ] = $this->getFromAndReplyTo( $fromAddress );

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
			$userMsgFormatter = $this->messageFormatterFactory->getTextFormatter( $langCode );
			$ccTo = $fromAddress;
			$ccFrom = $fromAddress;
			$ccSubject = $userMsgFormatter->format(
				MessageValue::new( 'emailccsubject' )->plaintextParams(
					$target->getUser()->getName(),
					$subject
				)
			);
			$ccText = $text;

			$this->hookRunner->onEmailUserCC( $ccTo, $ccFrom, $ccSubject, $ccText );

			[ $mailFrom, $replyTo ] = $this->getFromAndReplyTo( $ccFrom );

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
	 * @return array
	 * @phan-return array{0:MailAddress,1:?MailAddress}
	 */
	private function getFromAndReplyTo( MailAddress $fromAddress ): array {
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
				$this->contLangMsgFormatter->format( MessageValue::new( 'emailsender' ) )
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
	 * @internal Only for BC with SpecialEmailUser
	 * @param string $token
	 */
	public function setEditToken( string $token ): void {
		$this->editToken = $token;
	}

}
