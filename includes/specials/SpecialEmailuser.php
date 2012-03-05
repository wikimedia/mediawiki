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
	
	public function __construct(){
		parent::__construct( 'Emailuser' );
	}
	
	protected function getFormFields(){
		global $wgUser;
		return array(
			'From' => array(
				'type' => 'info',
				'raw' => 1,
				'default' => $wgUser->getSkin()->link( 
					$wgUser->getUserPage(), 
					htmlspecialchars( $wgUser->getName() ) 
				),
				'label-message' => 'emailfrom',
				'id' => 'mw-emailuser-sender',
			),
			'To' => array(
				'type' => 'info',
				'raw' => 1,
				'default' => $wgUser->getSkin()->link( 
					$this->mTargetObj->getUserPage(), 
					htmlspecialchars( $this->mTargetObj->getName() )
				),
				'label-message' => 'emailto',
				'id' => 'mw-emailuser-recipient',
			),
			'Target' => array(
				'name' => 'wpTarget',
				'type' => 'hidden',
				'default' => $this->mTargetObj->getName(),
			),
			'Subject' => array(
				'type' => 'text',
				'default' => wfMsgExt( 'defemailsubject', array( 'content', 'parsemag' ) ),
				'label-message' => 'emailsubject',
				'maxlength' => 200,
				'size' => 60,
				'required' => 1,
			),
			'Text' => array(
				'type' => 'textarea',
				'rows' => 20,
				'cols' => 80,
				'label-message' => 'emailmessage',
				'required' => 1,
			),
			'CCMe' => array(
				'type' => 'check',
				'label-message' => 'emailccme',
				'default' => $wgUser->getBoolOption( 'ccmeonemails' ),
			),
		);
	}
	
	public function execute( $par ) {
		global $wgRequest, $wgOut, $wgUser;
		$this->mTarget = is_null( $par )
			? $wgRequest->getVal( 'wpTarget', '' )
			: $par;
			
		$ret = self::getTarget( $this->mTarget );
		if( $ret instanceof User ){
			$this->mTargetObj = $ret;
		} else {
			$wgOut->showErrorPage( "{$ret}title", "{$ret}text" );
			return false;
		}
	
		$error = self::getPermissionsError( $wgUser, $wgRequest->getVal( 'wpEditToken' ) );
		switch ( $error ) {
			case null:
				# Wahey!
				break;
			case 'badaccess':
				$wgOut->permissionRequired( 'sendemail' );
				return;
			case 'blockedemailuser':
				$wgOut->blockedPage();
				return;
			case 'actionthrottledtext':
				$wgOut->rateLimited();
				return;
			case 'mailnologin':
			case 'usermaildisabled':
				$wgOut->showErrorPage( $error, "{$error}text" );
				return;
			default:
				# It's a hook error
				list( $title, $msg, $params ) = $error;
				$wgOut->showErrorPage( $title, $msg, $params );
				return;
		}
		
		$form = new HTMLForm( $this->getFormFields() );
		$form->addPreText( wfMsgExt( 'emailpagetext', 'parseinline' ) );
		$form->setSubmitText( wfMsg( 'emailsend' ) );
		$form->setTitle( $this->getTitle() );
		$form->setSubmitCallback( array( __CLASS__, 'submit' ) );
		$form->setWrapperLegend( wfMsgExt( 'email-legend', 'parsemag' ) );
		$form->loadData();
		
		if( !wfRunHooks( 'EmailUserForm', array( &$form ) ) ){
			return false;
		}
		
		$wgOut->setPagetitle( wfMsg( 'emailpage' ) );
		$result = $form->show();
		
		if( $result === true ){
			$wgOut->setPagetitle( wfMsg( 'emailsent' ) );
			$wgOut->addWikiMsg( 'emailsenttext' );
			$wgOut->returnToMain( false, $this->mTargetObj->getUserPage() );
		}
	}

	/**
	 * Validate target User
	 *
	 * @param $target String: target user name
	 * @return User object on success or a string on error
	 */
	public static function getTarget( $target ) {
		if ( $target == '' ) {
			wfDebug( "Target is empty.\n" );
			return 'notarget';
		}
		
		$nu = User::newFromName( $target );
		if( !$nu instanceof User || !$nu->getId() ) {
			wfDebug( "Target is invalid user.\n" );
			return 'notarget';
		} else if ( !$nu->isEmailConfirmed() ) {
			wfDebug( "User has no valid email.\n" );
			return 'noemail';
		} else if ( !$nu->canReceiveEmail() ) {
			wfDebug( "User does not allow user emails.\n" );
			return 'nowikiemail';
		}

		return $nu;
	}

	/**
	 * Check whether a user is allowed to send email
	 *
	 * @param $user User object
	 * @param $editToken String: edit token
	 * @return null on success or string on error
	 */
	public static function getPermissionsError( $user, $editToken ) {
		global $wgEnableEmail, $wgEnableUserEmail;
		if( !$wgEnableEmail || !$wgEnableUserEmail ){
			return 'usermaildisabled';
		}
		
		if( !$user->isAllowed( 'sendemail' ) ) {
			return 'badaccess';
		}
		
		if( !$user->isEmailConfirmed() ){
			return 'mailnologin';
		}

		if( $user->isBlockedFromEmailuser() ) {
			wfDebug( "User is blocked from sending e-mail.\n" );
			return "blockedemailuser";
		}

		if( $user->pingLimiter( 'emailuser' ) ) {
			wfDebug( "Ping limiter triggered.\n" );
			return 'actionthrottledtext';
		}

		$hookErr = false;
		wfRunHooks( 'UserCanSendEmail', array( &$user, &$hookErr ) );
		wfRunHooks( 'EmailUserPermissionsErrors', array( $user, $editToken, &$hookErr ) );
		if ( $hookErr ) {
			return $hookErr;
		}

		return null;
	}

	/**
	 * Really send a mail. Permissions should have been checked using
	 * getPermissionsError(). It is probably also a good 
	 * idea to check the edit token and ping limiter in advance.
	 *
	 * @return Mixed: True on success, String on error
	 */
	public static function submit( $data ) {
		global $wgUser, $wgUserEmailUseReplyTo;

		$target = self::getTarget( $data['Target'] );
		if( !$target instanceof User ){
			return wfMsgExt( $target . 'text', 'parse' );
		}
		$to = new MailAddress( $target );
		$from = new MailAddress( $wgUser );
		$subject = $data['Subject'];
		$text = $data['Text'];

		// Add a standard footer and trim up trailing newlines
		$text = rtrim( $text ) . "\n\n-- \n";
		$text .= wfMsgExt( 
			'emailuserfooter',
			array( 'content', 'parsemag' ), 
			array( $from->name, $to->name ) 
		);

		$error = '';
		if( !wfRunHooks( 'EmailUser', array( &$to, &$from, &$subject, &$text, &$error ) ) ) {
			return $error;
		}
		
		if( $wgUserEmailUseReplyTo ) {
			// Put the generic wiki autogenerated address in the From:
			// header and reserve the user for Reply-To.
			//
			// This is a bit ugly, but will serve to differentiate
			// wiki-borne mails from direct mails and protects against
			// SPF and bounce problems with some mailers (see below).
			global $wgPasswordSender;
			$mailFrom = new MailAddress( $wgPasswordSender );
			$replyTo = $from;
		} else {
			// Put the sending user's e-mail address in the From: header.
			//
			// This is clean-looking and convenient, but has issues.
			// One is that it doesn't as clearly differentiate the wiki mail
			// from "directly" sent mails.
			//
			// Another is that some mailers (like sSMTP) will use the From
			// address as the envelope sender as well. For open sites this
			// can cause mails to be flunked for SPF violations (since the
			// wiki server isn't an authorized sender for various users'
			// domains) as well as creating a privacy issue as bounces
			// containing the recipient's e-mail address may get sent to
			// the sending user.
			$mailFrom = $from;
			$replyTo = null;
		}

		$mailResult = UserMailer::send( $to, $mailFrom, $subject, $text, $replyTo );

		if( WikiError::isError( $mailResult ) && false ) {
			return $mailResult->getMessage();
		} else {
			// if the user requested a copy of this mail, do this now,
			// unless they are emailing themselves, in which case one 
			// copy of the message is sufficient.
			if ( $data['CCMe'] && $to != $from ) {
				$cc_subject = wfMsg(
					'emailccsubject', 
					$target->getName(), 
					$subject
				);
				wfRunHooks( 'EmailUserCC', array( &$from, &$from, &$cc_subject, &$text ) );
				$ccResult = UserMailer::send( $from, $from, $cc_subject, $text );
				if( WikiError::isError( $ccResult ) ) {
					// At this stage, the user's CC mail has failed, but their
					// original mail has succeeded. It's unlikely, but still, 
					// what to do? We can either show them an error, or we can 
					// say everything was fine, or we can say we sort of failed 
					// AND sort of succeeded. Of these options, simply saying 
					// there was an error is probably best.
					return $ccResult->getMessage();
				}
			}

			wfRunHooks( 'EmailUserComplete', array( $to, $from, $subject, $text ) );
			return true;
		}
	}
}
