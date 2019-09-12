<?php
/**
 * Implements Special:Emailuser
 *
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
 * @ingroup SpecialPage
 */
use MediaWiki\MediaWikiServices;
use MediaWiki\Preferences\MultiUsernameFilter;

/**
 * A special page that allows users to send e-mails to other users
 *
 * @ingroup SpecialPage
 */
class SpecialEmailUser extends UnlistedSpecialPage {
	protected $mTarget;

	/**
	 * @var User|string $mTargetObj
	 */
	protected $mTargetObj;

	public function __construct() {
		parent::__construct( 'Emailuser' );
	}

	public function doesWrites() {
		return true;
	}

	public function getDescription() {
		$target = self::getTarget( $this->mTarget, $this->getUser() );
		if ( !$target instanceof User ) {
			return $this->msg( 'emailuser-title-notarget' )->text();
		}

		return $this->msg( 'emailuser-title-target', $target->getName() )->text();
	}

	protected function getFormFields() {
		$linkRenderer = $this->getLinkRenderer();
		return [
			'From' => [
				'type' => 'info',
				'raw' => 1,
				'default' => $linkRenderer->makeLink(
					$this->getUser()->getUserPage(),
					$this->getUser()->getName()
				),
				'label-message' => 'emailfrom',
				'id' => 'mw-emailuser-sender',
			],
			'To' => [
				'type' => 'info',
				'raw' => 1,
				'default' => $linkRenderer->makeLink(
					$this->mTargetObj->getUserPage(),
					$this->mTargetObj->getName()
				),
				'label-message' => 'emailto',
				'id' => 'mw-emailuser-recipient',
			],
			'Target' => [
				'type' => 'hidden',
				'default' => $this->mTargetObj->getName(),
			],
			'Subject' => [
				'type' => 'text',
				'default' => $this->msg( 'defemailsubject',
					$this->getUser()->getName() )->inContentLanguage()->text(),
				'label-message' => 'emailsubject',
				'maxlength' => 200,
				'size' => 60,
				'required' => true,
			],
			'Text' => [
				'type' => 'textarea',
				'rows' => 20,
				'label-message' => 'emailmessage',
				'required' => true,
			],
			'CCMe' => [
				'type' => 'check',
				'label-message' => 'emailccme',
				'default' => $this->getUser()->getBoolOption( 'ccmeonemails' ),
			],
		];
	}

	public function execute( $par ) {
		$out = $this->getOutput();
		$request = $this->getRequest();
		$out->addModuleStyles( 'mediawiki.special' );

		$this->mTarget = $par ?? $request->getVal( 'wpTarget', $request->getVal( 'target', '' ) );

		// Make sure, that HTMLForm uses the correct target.
		$request->setVal( 'wpTarget', $this->mTarget );

		// This needs to be below assignment of $this->mTarget because
		// getDescription() needs it to determine the correct page title.
		$this->setHeaders();
		$this->outputHeader();

		// error out if sending user cannot do this
		$error = self::getPermissionsError(
			$this->getUser(),
			$this->getRequest()->getVal( 'wpEditToken' ),
			$this->getConfig()
		);

		switch ( $error ) {
			case null:
				# Wahey!
				break;
			case 'badaccess':
				throw new PermissionsError( 'sendemail' );
			case 'blockedemailuser':
				throw $this->getBlockedEmailError();
			case 'actionthrottledtext':
				throw new ThrottledError;
			case 'mailnologin':
			case 'usermaildisabled':
				throw new ErrorPageError( $error, "{$error}text" );
			default:
				# It's a hook error
				list( $title, $msg, $params ) = $error;
				throw new ErrorPageError( $title, $msg, $params );
		}

		// Make sure, that a submitted form isn't submitted to a subpage (which could be
		// a non-existing username)
		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle() ); // Remove subpage
		$this->setContext( $context );

		// A little hack: HTMLForm will check $this->mTarget only, if the form was posted, not
		// if the user opens Special:EmailUser/Florian (e.g.). So check, if the user did that
		// and show the "Send email to user" form directly, if so. Show the "enter username"
		// form, otherwise.
		$this->mTargetObj = self::getTarget( $this->mTarget, $this->getUser() );
		if ( !$this->mTargetObj instanceof User ) {
			$this->userForm( $this->mTarget );
		} else {
			$this->sendEmailForm();
		}
	}

	/**
	 * Validate target User
	 *
	 * @param string $target Target user name
	 * @param User $sender User sending the email
	 * @return User|string User object on success or a string on error
	 */
	public static function getTarget( $target, User $sender ) {
		if ( $target == '' ) {
			wfDebug( "Target is empty.\n" );

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
	 * @since 1.30
	 */
	public static function validateTarget( $target, User $sender ) {
		if ( !$target instanceof User || !$target->getId() ) {
			wfDebug( "Target is invalid user.\n" );

			return 'notarget';
		}

		if ( !$target->isEmailConfirmed() ) {
			wfDebug( "User has no valid email.\n" );

			return 'noemail';
		}

		if ( !$target->canReceiveEmail() ) {
			wfDebug( "User does not allow user emails.\n" );

			return 'nowikiemail';
		}

		if ( !$target->getOption( 'email-allow-new-users' ) && $sender->isNewbie() ) {
			wfDebug( "User does not allow user emails from new users.\n" );

			return 'nowikiemail';
		}

		$blacklist = $target->getOption( 'email-blacklist', '' );
		if ( $blacklist ) {
			$blacklist = MultiUsernameFilter::splitIds( $blacklist );
			$lookup = CentralIdLookup::factory();
			$senderId = $lookup->centralIdFromLocalUser( $sender );
			if ( $senderId !== 0 && in_array( $senderId, $blacklist ) ) {
				wfDebug( "User does not allow user emails from this user.\n" );

				return 'nowikiemail';
			}
		}

		return '';
	}

	/**
	 * Check whether a user is allowed to send email
	 *
	 * @param User $user
	 * @param string $editToken Edit token
	 * @param Config|null $config optional for backwards compatibility
	 * @return null|string|array Null on success, string on error, or array on
	 *  hook error
	 */
	public static function getPermissionsError( $user, $editToken, Config $config = null ) {
		if ( $config === null ) {
			wfDebug( __METHOD__ . ' called without a Config instance passed to it' );
			$config = MediaWikiServices::getInstance()->getMainConfig();
		}
		if ( !$config->get( 'EnableEmail' ) || !$config->get( 'EnableUserEmail' ) ) {
			return 'usermaildisabled';
		}

		// Run this before $user->isAllowed, to show appropriate message to anons (T160309)
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
			wfDebug( "User is blocked from sending e-mail.\n" );

			return "blockedemailuser";
		}

		// Check the ping limiter without incrementing it - we'll check it
		// again later and increment it on a successful send
		if ( $user->pingLimiter( 'emailuser', 0 ) ) {
			wfDebug( "Ping limiter triggered.\n" );

			return 'actionthrottledtext';
		}

		$hookErr = false;

		Hooks::run( 'UserCanSendEmail', [ &$user, &$hookErr ] );
		Hooks::run( 'EmailUserPermissionsErrors', [ $user, $editToken, &$hookErr ] );

		if ( $hookErr ) {
			return $hookErr;
		}

		return null;
	}

	/**
	 * Form to ask for target user name.
	 *
	 * @param string $name User name submitted.
	 */
	protected function userForm( $name ) {
		$htmlForm = HTMLForm::factory( 'ooui', [
			'Target' => [
				'type' => 'user',
				'exists' => true,
				'label' => $this->msg( 'emailusername' )->text(),
				'id' => 'emailusertarget',
				'autofocus' => true,
				'value' => $name,
			]
		], $this->getContext() );

		$htmlForm
			->setMethod( 'post' )
			->setSubmitCallback( [ $this, 'sendEmailForm' ] )
			->setFormIdentifier( 'userForm' )
			->setId( 'askusername' )
			->setWrapperLegendMsg( 'emailtarget' )
			->setSubmitTextMsg( 'emailusernamesubmit' )
			->show();
	}

	public function sendEmailForm() {
		$out = $this->getOutput();

		$ret = $this->mTargetObj;
		if ( !$ret instanceof User ) {
			if ( $this->mTarget != '' ) {
				// Messages used here: notargettext, noemailtext, nowikiemailtext
				$ret = ( $ret == 'notarget' ) ? 'emailnotarget' : ( $ret . 'text' );
				return Status::newFatal( $ret );
			}
			return false;
		}

		$htmlForm = HTMLForm::factory( 'ooui', $this->getFormFields(), $this->getContext() );
		// By now we are supposed to be sure that $this->mTarget is a user name
		$htmlForm
			->addPreText( $this->msg( 'emailpagetext', $this->mTarget )->parse() )
			->setSubmitTextMsg( 'emailsend' )
			->setSubmitCallback( [ __CLASS__, 'submit' ] )
			->setFormIdentifier( 'sendEmailForm' )
			->setWrapperLegendMsg( 'email-legend' )
			->loadData();

		if ( !Hooks::run( 'EmailUserForm', [ &$htmlForm ] ) ) {
			return false;
		}

		$result = $htmlForm->show();

		if ( $result === true || ( $result instanceof Status && $result->isGood() ) ) {
			$out->setPageTitle( $this->msg( 'emailsent' ) );
			$out->addWikiMsg( 'emailsenttext', $this->mTarget );
			$out->returnToMain( false, $ret->getUserPage() );
		}
		return true;
	}

	/**
	 * Really send a mail. Permissions should have been checked using
	 * getPermissionsError(). It is probably also a good
	 * idea to check the edit token and ping limiter in advance.
	 *
	 * @param array $data
	 * @param IContextSource $context
	 * @return Status|bool
	 */
	public static function submit( array $data, IContextSource $context ) {
		$config = $context->getConfig();

		$target = self::getTarget( $data['Target'], $context->getUser() );
		if ( !$target instanceof User ) {
			// Messages used here: notargettext, noemailtext, nowikiemailtext
			return Status::newFatal( $target . 'text' );
		}

		$to = MailAddress::newFromUser( $target );
		$from = MailAddress::newFromUser( $context->getUser() );
		$subject = $data['Subject'];
		$text = $data['Text'];

		// Add a standard footer and trim up trailing newlines
		$text = rtrim( $text ) . "\n\n-- \n";
		$text .= $context->msg( 'emailuserfooter',
			$from->name, $to->name )->inContentLanguage()->text();

		if ( $config->get( 'EnableSpecialMute' ) ) {
			$specialMutePage = SpecialPage::getTitleFor( 'Mute', $context->getUser()->getName() );
			$text .= "\n" . $context->msg(
				'specialmute-email-footer',
				$specialMutePage->getCanonicalURL(),
				$context->getUser()->getName()
			)->inContentLanguage()->text();
		}

		// Check and increment the rate limits
		if ( $context->getUser()->pingLimiter( 'emailuser' ) ) {
			throw new ThrottledError();
		}

		$error = false;
		if ( !Hooks::run( 'EmailUser', [ &$to, &$from, &$subject, &$text, &$error ] ) ) {
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
				// Ugh. Either a raw HTML string, or something that's supposed
				// to be treated like one.
				$type = is_object( $error ) ? get_class( $error ) : gettype( $error );
				wfDeprecated( "EmailUser hook returning a $type as \$error", '1.29' );
				return Status::newFatal( new ApiRawMessage(
					[ '$1', Message::rawParam( (string)$error ) ], 'hookaborted'
				) );
			}
		}

		if ( $config->get( 'UserEmailUseReplyTo' ) ) {
			/**
			 * Put the generic wiki autogenerated address in the From:
			 * header and reserve the user for Reply-To.
			 *
			 * This is a bit ugly, but will serve to differentiate
			 * wiki-borne mails from direct mails and protects against
			 * SPF and bounce problems with some mailers (see below).
			 */
			$mailFrom = new MailAddress( $config->get( 'PasswordSender' ),
				$context->msg( 'emailsender' )->inContentLanguage()->text() );
			$replyTo = $from;
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
			$mailFrom = $from;
			$replyTo = null;
		}

		$status = UserMailer::send( $to, $mailFrom, $subject, $text, [
			'replyTo' => $replyTo,
		] );

		if ( !$status->isGood() ) {
			return $status;
		} else {
			// if the user requested a copy of this mail, do this now,
			// unless they are emailing themselves, in which case one
			// copy of the message is sufficient.
			if ( $data['CCMe'] && $to != $from ) {
				$ccTo = $from;
				$ccFrom = $from;
				$ccSubject = $context->msg( 'emailccsubject' )->plaintextParams(
					$target->getName(), $subject )->text();
				$ccText = $text;

				Hooks::run( 'EmailUserCC', [ &$ccTo, &$ccFrom, &$ccSubject, &$ccText ] );

				if ( $config->get( 'UserEmailUseReplyTo' ) ) {
					$mailFrom = new MailAddress(
						$config->get( 'PasswordSender' ),
						$context->msg( 'emailsender' )->inContentLanguage()->text()
					);
					$replyTo = $ccFrom;
				} else {
					$mailFrom = $ccFrom;
					$replyTo = null;
				}

				$ccStatus = UserMailer::send(
					$ccTo, $mailFrom, $ccSubject, $ccText, [
						'replyTo' => $replyTo,
				] );
				$status->merge( $ccStatus );
			}

			Hooks::run( 'EmailUserComplete', [ $to, $from, $subject, $text ] );

			return $status;
		}
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		$user = User::newFromName( $search );
		if ( !$user ) {
			// No prefix suggestion for invalid user
			return [];
		}
		// Autocomplete subpage as user list - public to allow caching
		return UserNamePrefixSearch::search( 'public', $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'users';
	}

	/**
	 * Builds an error message based on the block params
	 *
	 * @return ErrorPageError
	 */
	private function getBlockedEmailError() {
		$block = $this->getUser()->mBlock;
		$params = $block->getBlockErrorParams( $this->getContext() );

		$msg = $block->isSitewide() ? 'blockedtext' : 'blocked-email-user';
		return new ErrorPageError( 'blockedtitle', $msg, $params );
	}
}
