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

	public function getDescription() {
		$target = self::getTarget( $this->mTarget );
		if ( !$target instanceof User ) {
			return $this->msg( 'emailuser-title-notarget' )->text();
		}

		return $this->msg( 'emailuser-title-target', $target->getName() )->text();
	}

	protected function getFormFields() {
		return array(
			'From' => array(
				'type' => 'info',
				'raw' => 1,
				'default' => Linker::link(
					$this->getUser()->getUserPage(),
					htmlspecialchars( $this->getUser()->getName() )
				),
				'label-message' => 'emailfrom',
				'id' => 'mw-emailuser-sender',
			),
			'To' => array(
				'type' => 'info',
				'raw' => 1,
				'default' => Linker::link(
					$this->mTargetObj->getUserPage(),
					htmlspecialchars( $this->mTargetObj->getName() )
				),
				'label-message' => 'emailto',
				'id' => 'mw-emailuser-recipient',
			),
			'Target' => array(
				'type' => 'hidden',
				'default' => $this->mTargetObj->getName(),
			),
			'Subject' => array(
				'type' => 'text',
				'default' => $this->msg( 'defemailsubject',
					$this->getUser()->getName() )->inContentLanguage()->text(),
				'label-message' => 'emailsubject',
				'maxlength' => 200,
				'size' => 60,
				'required' => true,
			),
			'Text' => array(
				'type' => 'textarea',
				'rows' => 20,
				'label-message' => 'emailmessage',
				'required' => true,
			),
			'CCMe' => array(
				'type' => 'check',
				'label-message' => 'emailccme',
				'default' => $this->getUser()->getBoolOption( 'ccmeonemails' ),
			),
		);
	}

	public function execute( $par ) {
		$out = $this->getOutput();
		$request = $this->getRequest();
		$out->addModuleStyles( 'mediawiki.special' );

		$this->mTarget = is_null( $par )
			? $request->getVal( 'wpTarget', '' )
			: $par;

		// make sure, that HTMLForm uses the correct target
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
				throw new UserBlockedError( $this->getUser()->mBlock );
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

		// a little hack: HTMLForm will check $this->mTarget only, if the form was posted, not
		// if the user opens Special:EmailUser/Florian (e.g.). So check, if the user did that,
		// and show the "Send email to user" form directly, if so. Show the "enter username"
		// form, otherwise.
		$this->mTargetObj = self::getTarget( $this->mTarget );
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
	 * @return User User object on success or a string on error
	 */
	public static function getTarget( $target ) {
		if ( $target == '' ) {
			wfDebug( "Target is empty.\n" );

			return 'notarget';
		}

		$nu = User::newFromName( $target );
		if ( !$nu instanceof User || !$nu->getId() ) {
			wfDebug( "Target is invalid user.\n" );

			return 'notarget';
		} elseif ( !$nu->isEmailConfirmed() ) {
			wfDebug( "User has no valid email.\n" );

			return 'noemail';
		} elseif ( !$nu->canReceiveEmail() ) {
			wfDebug( "User does not allow user emails.\n" );

			return 'nowikiemail';
		}

		return $nu;
	}

	/**
	 * Check whether a user is allowed to send email
	 *
	 * @param User $user
	 * @param string $editToken Edit token
	 * @param Config $config optional for backwards compatibility
	 * @return string|null Null on success or string on error
	 */
	public static function getPermissionsError( $user, $editToken, Config $config = null ) {
		if ( $config === null ) {
			wfDebug( __METHOD__ . ' called without a Config instance passed to it' );
			$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		}
		if ( !$config->get( 'EnableEmail' ) || !$config->get( 'EnableUserEmail' ) ) {
			return 'usermaildisabled';
		}

		if ( !$user->isAllowed( 'sendemail' ) ) {
			return 'badaccess';
		}

		if ( !$user->isEmailConfirmed() ) {
			return 'mailnologin';
		}

		if ( $user->isBlockedFromEmailuser() ) {
			wfDebug( "User is blocked from sending e-mail.\n" );

			return "blockedemailuser";
		}

		if ( $user->pingLimiter( 'emailuser' ) ) {
			wfDebug( "Ping limiter triggered.\n" );

			return 'actionthrottledtext';
		}

		$hookErr = false;

		Hooks::run( 'UserCanSendEmail', array( &$user, &$hookErr ) );
		Hooks::run( 'EmailUserPermissionsErrors', array( $user, $editToken, &$hookErr ) );

		if ( $hookErr ) {
			return $hookErr;
		}

		return null;
	}

	/**
	 * Form to ask for target user name.
	 *
	 * @param string $name User name submitted.
	 * @return string Form asking for user name.
	 */
	protected function userForm( $name ) {
		$form = HTMLForm::factory( 'ooui', array(
			'Target' => array(
				'type' => 'user',
				'exists' => true,
				'label' => $this->msg( 'emailusername' )->text(),
				'id' => 'emailusertarget',
				'autofocus' => true,
				'value' => $name,
			),
		), $this->getContext() );

		$form
			->setMethod( 'post' )
			->setSubmitCallback( array( $this, 'sendEmailForm' ) )
			->setSubmitProgressive()
			->setId( 'askusername' )
			->addHiddenField( 'title', $this->getPageTitle()->getPrefixedText() )
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

		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle() ); // Remove subpage
		$form = HTMLForm::factory( 'ooui', $this->getFormFields(), $context );
		// By now we are supposed to be sure that $this->mTarget is a user name
		$form->addPreText( $this->msg( 'emailpagetext', $this->mTarget )->parse() );
		$form->setSubmitTextMsg( 'emailsend' );
		$form->setSubmitCallback( array( __CLASS__, 'uiSubmit' ) );
		$form->setWrapperLegendMsg( 'email-legend' );
		$form->loadData();

		if ( !Hooks::run( 'EmailUserForm', array( &$form ) ) ) {
			return false;
		}

		$result = $form->show();

		if ( $result === true || ( $result instanceof Status && $result->isGood() ) ) {
			$out->setPageTitle( $this->msg( 'emailsent' ) );
			$out->addWikiMsg( 'emailsenttext', $this->mTarget );
			$out->returnToMain( false, $ret->getUserPage() );
		}
		return true;
	}

	/**
	 * Submit callback for an HTMLForm object, will simply call submit().
	 *
	 * @since 1.20
	 * @param array $data
	 * @param HTMLForm $form
	 * @return Status|string|bool
	 */
	public static function uiSubmit( array $data, HTMLForm $form ) {
		return self::submit( $data, $form->getContext() );
	}

	/**
	 * Really send a mail. Permissions should have been checked using
	 * getPermissionsError(). It is probably also a good
	 * idea to check the edit token and ping limiter in advance.
	 *
	 * @param array $data
	 * @param IContextSource $context
	 * @return Status|string|bool Status object, or potentially a String on error
	 * or maybe even true on success if anything uses the EmailUser hook.
	 */
	public static function submit( array $data, IContextSource $context ) {
		$config = $context->getConfig();

		$target = self::getTarget( $data['Target'] );
		if ( !$target instanceof User ) {
			// Messages used here: notargettext, noemailtext, nowikiemailtext
			return $context->msg( $target . 'text' )->parseAsBlock();
		}

		$to = MailAddress::newFromUser( $target );
		$from = MailAddress::newFromUser( $context->getUser() );
		$subject = $data['Subject'];
		$text = $data['Text'];

		// Add a standard footer and trim up trailing newlines
		$text = rtrim( $text ) . "\n\n-- \n";
		$text .= $context->msg( 'emailuserfooter',
			$from->name, $to->name )->inContentLanguage()->text();

		$error = '';
		if ( !Hooks::run( 'EmailUser', array( &$to, &$from, &$subject, &$text, &$error ) ) ) {
			return $error;
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
				wfMessage( 'emailsender' )->inContentLanguage()->text() );
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

		$status = UserMailer::send( $to, $mailFrom, $subject, $text, array(
			'replyTo' => $replyTo,
		) );

		if ( !$status->isGood() ) {
			return $status;
		} else {
			// if the user requested a copy of this mail, do this now,
			// unless they are emailing themselves, in which case one
			// copy of the message is sufficient.
			if ( $data['CCMe'] && $to != $from ) {
				$cc_subject = $context->msg( 'emailccsubject' )->rawParams(
					$target->getName(), $subject )->text();

				// target and sender are equal, because this is the CC for the sender
				Hooks::run( 'EmailUserCC', array( &$from, &$from, &$cc_subject, &$text ) );

				$ccStatus = UserMailer::send( $from, $from, $cc_subject, $text );
				$status->merge( $ccStatus );
			}

			Hooks::run( 'EmailUserComplete', array( $to, $from, $subject, $text ) );

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
			return array();
		}
		// Autocomplete subpage as user list - public to allow caching
		return UserNamePrefixSearch::search( 'public', $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'users';
	}
}
