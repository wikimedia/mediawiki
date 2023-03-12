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

use Config;
use Hooks;
use IContextSource;
use MailAddress;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Preferences\MultiUsernameFilter;
use MessageSpecifier;
use MWException;
use SpecialPage;
use Status;
use ThrottledError;
use User;

/**
 * Command for sending emails to users.
 *
 * @since 1.40
 * @unstable
 */
class EmailUser {
	/**
	 * Validate target User
	 *
	 * @param string $target Target user name
	 * @param User $sender User sending the email
	 * @return User|string User object on success or a string on error
	 */
	public static function getTarget( $target, User $sender ) {
		if ( $target == '' ) {
			wfDebug( "Target is empty." );

			return 'notarget';
		}

		$nu = User::newFromName( $target );
		$error = self::validateTarget( $nu, $sender );

		return $error ?: $nu;
	}

	/**
	 * Validate target User
	 *
	 * @param User $target Target user
	 * @param User $sender User sending the email
	 * @return string Error message or empty string if valid.
	 */
	public static function validateTarget( $target, User $sender ) {
		if ( !$target instanceof User || !$target->getId() ) {
			wfDebug( "Target is invalid user." );

			return 'notarget';
		}

		if ( !$target->isEmailConfirmed() ) {
			wfDebug( "User has no valid email." );

			return 'noemail';
		}

		if ( !$target->canReceiveEmail() ) {
			wfDebug( "User does not allow user emails." );

			return 'nowikiemail';
		}

		$userOptionsLookup = MediaWikiServices::getInstance()
			->getUserOptionsLookup();
		if ( !$userOptionsLookup->getOption(
				$target,
				'email-allow-new-users'
			) && $sender->isNewbie()
		) {
			wfDebug( "User does not allow user emails from new users." );

			return 'nowikiemail';
		}

		$muteList = $userOptionsLookup->getOption(
			$target,
			'email-blacklist',
			''
		);
		if ( $muteList ) {
			$muteList = MultiUsernameFilter::splitIds( $muteList );
			$senderId = MediaWikiServices::getInstance()
				->getCentralIdLookup()
				->centralIdFromLocalUser( $sender );
			if ( $senderId !== 0 && in_array( $senderId, $muteList ) ) {
				wfDebug( "User does not allow user emails from this user." );

				return 'nowikiemail';
			}
		}

		return '';
	}

	/**
	 * Check whether a user is allowed to send email
	 *
	 * @param User $user
	 * @param string $editToken
	 * @param Config|null $config optional for backwards compatibility
	 * @return null|string|array Null on success, string on error, or array on
	 *  hook error
	 */
	public static function getPermissionsError( $user, $editToken, Config $config = null ) {
		if ( $config === null ) {
			wfDebug( __METHOD__ . ' called without a Config instance passed to it' );
			$config = MediaWikiServices::getInstance()->getMainConfig();
		}
		if ( !$config->get( MainConfigNames::EnableEmail ) ||
			!$config->get( MainConfigNames::EnableUserEmail ) ) {
			return 'usermaildisabled';
		}

		// Run this before checking 'sendemail' permission
		// to show appropriate message to anons (T160309)
		if ( !$user->isEmailConfirmed() ) {
			return 'mailnologin';
		}

		if ( !MediaWikiServices::getInstance()
			->getPermissionManager()
			->userHasRight( $user, 'sendemail' )
		) {
			return 'badaccess';
		}

		if ( $user->isBlockedFromEmailuser() ) {
			wfDebug( "User is blocked from sending e-mail." );

			return "blockedemailuser";
		}

		// Check the ping limiter without incrementing it - we'll check it
		// again later and increment it on a successful send
		if ( $user->pingLimiter( 'sendemail', 0 ) ) {
			wfDebug( "Ping limiter triggered." );

			return 'actionthrottledtext';
		}

		$hookErr = false;

		Hooks::runner()->onUserCanSendEmail( $user, $hookErr );
		Hooks::runner()->onEmailUserPermissionsErrors( $user, $editToken, $hookErr );

		return $hookErr ?: null;
	}

	/**
	 * Really send a mail. Permissions should have been checked using
	 * getPermissionsError(). It is probably also a good
	 * idea to check the edit token and ping limiter in advance.
	 *
	 * @param array $data
	 * @param IContextSource $context
	 * @return Status|false
	 * @throws MWException if EmailUser hook sets the error to something unsupported
	 */
	public static function submit( array $data, IContextSource $context ) {
		$config = $context->getConfig();

		$sender = $context->getUser();
		$target = self::getTarget( $data['Target'], $sender );
		if ( !$target instanceof User ) {
			// Messages used here: notargettext, noemailtext, nowikiemailtext
			return Status::newFatal( $target . 'text' );
		}

		$toAddress = MailAddress::newFromUser( $target );
		$fromAddress = MailAddress::newFromUser( $sender );
		$subject = $data['Subject'];
		$text = $data['Text'];

		// Add a standard footer and trim up trailing newlines
		$text = rtrim( $text ) . "\n\n-- \n";
		$text .= $context->msg(
			'emailuserfooter',
			$fromAddress->name,
			$toAddress->name
		)->inContentLanguage()->text();

		if ( $config->get( MainConfigNames::EnableSpecialMute ) ) {
			$specialMutePage = SpecialPage::getTitleFor( 'Mute', $sender->getName() );
			$text .= "\n" . $context->msg(
					'specialmute-email-footer',
					$specialMutePage->getCanonicalURL(),
					$sender->getName()
				)->inContentLanguage()->text();
		}

		// Check and increment the rate limits
		if ( $sender->pingLimiter( 'sendemail' ) ) {
			throw new ThrottledError();
		}

		// Services that are needed, will be injected once this is moved to EmailUserUtils
		// service, see T265541
		$hookRunner = Hooks::runner();
		$emailer = MediaWikiServices::getInstance()->getEmailer();

		$error = false;
		if ( !$hookRunner->onEmailUser( $toAddress, $fromAddress, $subject, $text, $error ) ) {
			if ( $error instanceof Status ) {
				return $error;
			} elseif ( $error === false || $error === '' || $error === [] ) {
				// Possibly to tell HTMLForm to pretend there was no submission?
				return false;
			} elseif ( $error === true ) {
				// Hook sent the mail itself and indicates success?
				return Status::newGood();
			} elseif ( is_array( $error ) ) {
				$status = Status::newGood();
				foreach ( $error as $e ) {
					$status->fatal( $e );
				}
				return $status;
			} elseif ( $error instanceof MessageSpecifier ) {
				return Status::newFatal( $error );
			} else {
				// Setting $error to something else was deprecated in 1.29 and
				// removed in 1.36, and so an exception is now thrown
				$type = is_object( $error ) ? get_class( $error ) : gettype( $error );
				throw new MWException(
					'EmailUser hook set $error to unsupported type ' . $type
				);
			}
		}

		if ( $config->get( MainConfigNames::UserEmailUseReplyTo ) ) {
			/**
			 * Put the generic wiki autogenerated address in the From:
			 * header and reserve the user for Reply-To.
			 *
			 * This is a bit ugly, but will serve to differentiate
			 * wiki-borne mails from direct mails and protects against
			 * SPF and bounce problems with some mailers (see below).
			 */
			$mailFrom = new MailAddress(
				$config->get( MainConfigNames::PasswordSender ),
				$context->msg( 'emailsender' )->inContentLanguage()->text()
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

		$status = Status::wrap( $emailer->send(
			$toAddress,
			$mailFrom,
			$subject,
			$text,
			null,
			[ 'replyTo' => $replyTo ]
		) );

		if ( !$status->isGood() ) {
			return $status;
		}

		// if the user requested a copy of this mail, do this now,
		// unless they are emailing themselves, in which case one
		// copy of the message is sufficient.
		if ( $data['CCMe'] && $toAddress != $fromAddress ) {
			$ccTo = $fromAddress;
			$ccFrom = $fromAddress;
			$ccSubject = $context->msg( 'emailccsubject' )->plaintextParams(
				$target->getName(),
				$subject
			)->text();
			$ccText = $text;

			$hookRunner->onEmailUserCC( $ccTo, $ccFrom, $ccSubject, $ccText );

			if ( $config->get( MainConfigNames::UserEmailUseReplyTo ) ) {
				$mailFrom = new MailAddress(
					$config->get( MainConfigNames::PasswordSender ),
					$context->msg( 'emailsender' )->inContentLanguage()->text()
				);
				$replyTo = $ccFrom;
			} else {
				$mailFrom = $ccFrom;
				$replyTo = null;
			}

			$ccStatus = $emailer->send(
				$ccTo,
				$mailFrom,
				$ccSubject,
				$ccText,
				null,
				[ 'replyTo' => $replyTo ]
			);
			$status->merge( $ccStatus );
		}

		$hookRunner->onEmailUserComplete( $toAddress, $fromAddress, $subject, $text );

		return $status;
	}
}
