<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * 	Constructor for Special:Emailuser.
 */
function wfSpecialEmailuser( $par ) {
	global $wgRequest, $wgUser, $wgOut;

	if ( !EmailUserForm::userEmailEnabled() ) {
		$wgOut->showErrorPage( 'nosuchspecialpage', 'nospecialpagetext' );
		return;
	}

	$action = $wgRequest->getVal( 'action' );
	$target = isset($par) ? $par : $wgRequest->getVal( 'target' );
	$targetUser = EmailUserForm::validateEmailTarget( $target );
	
	if ( !( $targetUser instanceof User ) ) {
		$wgOut->showErrorPage( $targetUser.'title', $targetUser.'text' );
		return;
	}
	
	$form = new EmailUserForm( $targetUser,
			$wgRequest->getText( 'wpText' ),
			$wgRequest->getText( 'wpSubject' ),
			$wgRequest->getBool( 'wpCCMe' ) );
	if ( $action == 'success' ) {
		$form->showSuccess();
		return;
	}
					
	$error = EmailUserForm::getPermissionsError( $wgUser, $wgRequest->getVal( 'wpEditToken' ) );
	if ( $error ) {
		switch ( $error ) {
			case 'blockedemailuser':
				$wgOut->blockedPage();
				return;
			case 'actionthrottledtext':
				$wgOut->rateLimited();
				return;
			case 'sessionfailure':
				$form->showForm();
				return;
			case 'mailnologin':
				$wgOut->showErrorPage( 'mailnologin', 'mailnologintext' );
				return;
			default:
				// It's a hook error
				list( $title, $msg, $params ) = $error;
				$wgOut->showErrorPage( $title, $msg, $params );
				return;
				
		}
	}	
	
	if ( "submit" == $action && $wgRequest->wasPosted() ) {
		$result = $form->doSubmit();
		
		if ( !is_null( $result ) ) {
			$wgOut->addHTML( wfMsg( "usermailererror" ) .
					' ' . htmlspecialchars( $result->getMessage() ) );
		} else {
			$titleObj = SpecialPage::getTitleFor( "Emailuser" );
			$encTarget = wfUrlencode( $form->getTarget()->getName() );
			$wgOut->redirect( $titleObj->getFullURL( "target={$encTarget}&action=success" ) );
		}
	} else {
		$form->showForm();
	}
}

/**
 * Implements the Special:Emailuser web interface, and invokes userMailer for sending the email message.
 * @ingroup SpecialPage
 */
class EmailUserForm {

	var $target;
	var $text, $subject;
	var $cc_me;     // Whether user requested to be sent a separate copy of their email.

	/**
	 * @param User $target
	 */
	function EmailUserForm( $target, $text, $subject, $cc_me ) {
		$this->target = $target;
		$this->text = $text;
		$this->subject = $subject;
		$this->cc_me = $cc_me;
	}

	function showForm() {
		global $wgOut, $wgUser;
		$skin = $wgUser->getSkin();

		$wgOut->setPagetitle( wfMsg( "emailpage" ) );
		$wgOut->addWikiMsg( "emailpagetext" );

		if ( $this->subject === "" ) {
			$this->subject = wfMsgExt( 'defemailsubject', array( 'content', 'parsemag' ) );
		}

		$titleObj = SpecialPage::getTitleFor( "Emailuser" );
		$action = $titleObj->getLocalURL( "target=" .
			urlencode( $this->target->getName() ) . "&action=submit" );

		$wgOut->addHTML(  
			Xml::openElement( 'form', array( 'method' => 'post', 'action' => $action, 'id' => 'emailuser' ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsgExt( 'email-legend', 'parsemag' ) ) .
			Xml::openElement( 'table', array( 'class' => 'mw-emailuser-table' ) ) .
			"<tr>
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'emailfrom' ), 'emailfrom' ) .
				"</td>
				<td class='mw-input' id='mw-emailuser-sender'>" .
					$skin->link( $wgUser->getUserPage(), htmlspecialchars( $wgUser->getName() ) ) .
				"</td>
			</tr>
			<tr>
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'emailto' ), 'emailto' ) .
				"</td>
				<td class='mw-input' id='mw-emailuser-recipient'>" .
					$skin->link( $this->target->getUserPage(), htmlspecialchars( $this->target->getName() ) ) .
				"</td>
			</tr>
			<tr>
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'emailsubject' ), 'wpSubject' ) .
				"</td>
				<td class='mw-input'>" .
					Xml::input( 'wpSubject', 60, $this->subject, array( 'type' => 'text', 'maxlength' => 200 ) ) .
				"</td>
			</tr>
			<tr>
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'emailmessage' ), 'wpText' ) .
				"</td>
				<td class='mw-input'>" .
					Xml::textarea( 'wpText', $this->text, 80, 20, array( 'id' => 'wpText' ) ) .
				"</td>
			</tr>
			<tr>
				<td></td>
				<td class='mw-input'>" .
					Xml::checkLabel( wfMsg( 'emailccme' ), 'wpCCMe', 'wpCCMe', $wgUser->getBoolOption( 'ccmeonemails' ) ) .
				"</td>
			</tr>
			<tr>
				<td></td>
				<td class='mw-submit'>" .
					Xml::submitButton( wfMsg( 'emailsend' ), array( 'name' => 'wpSend', 'accesskey' => 's' ) ) .
				"</td>
			</tr>" .
			Xml::hidden( 'wpEditToken', $wgUser->editToken() ) .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' )
		);
	}

	/*
	 * Really send a mail. Permissions should have been checked using 
	 * EmailUserForm::getPermissionsError. It is probably also a good idea to
	 * check the edit token and ping limiter in advance.
	 */
	function doSubmit() {
		global $wgUser, $wgUserEmailUseReplyTo, $wgSiteName;

		$to = new MailAddress( $this->target );
		$from = new MailAddress( $wgUser );
		$subject = $this->subject;

		// Add a standard footer and trim up trailing newlines
		$this->text = rtrim($this->text) . "\n\n-- \n" . wfMsgExt( 'emailuserfooter',
			array( 'content', 'parsemag' ), array( $from->name, $to->name ) );
		
		if( wfRunHooks( 'EmailUser', array( &$to, &$from, &$subject, &$this->text ) ) ) {

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
			
			$mailResult = UserMailer::send( $to, $mailFrom, $subject, $this->text, $replyTo );

			if( WikiError::isError( $mailResult ) ) {
				return $mailResult;
				
			} else {

				// if the user requested a copy of this mail, do this now,
				// unless they are emailing themselves, in which case one copy of the message is sufficient.
				if ($this->cc_me && $to != $from) {
					$cc_subject = wfMsg('emailccsubject', $this->target->getName(), $subject);
					if( wfRunHooks( 'EmailUser', array( &$from, &$from, &$cc_subject, &$this->text ) ) ) {
						$ccResult = UserMailer::send( $from, $from, $cc_subject, $this->text );
						if( WikiError::isError( $ccResult ) ) {
							// At this stage, the user's CC mail has failed, but their
							// original mail has succeeded. It's unlikely, but still, what to do?
							// We can either show them an error, or we can say everything was fine,
							// or we can say we sort of failed AND sort of succeeded. Of these options,
							// simply saying there was an error is probably best.
							return $ccResult;
						}
					}
				}

				wfRunHooks( 'EmailUserComplete', array( $to, $from, $subject, $this->text ) );
				return;
			}
		}
	}

	function showSuccess( &$user = null ) {
		global $wgOut;
		
		if ( is_null($user) )
			$user = $this->target;

		$wgOut->setPagetitle( wfMsg( "emailsent" ) );
		$wgOut->addWikiMsg( 'emailsenttext' );

		$wgOut->returnToMain( false, $user->getUserPage() );
	}
	
	function getTarget() {
		return $this->target;
	}
	
	static function userEmailEnabled() {
		global $wgEnableEmail, $wgEnableUserEmail;
		return $wgEnableEmail && $wgEnableUserEmail;
		
	}
	static function validateEmailTarget ( $target ) {
		if ( $target == "" ) {
			wfDebug( "Target is empty.\n" );
			return "notarget";
		}
	
		$nt = Title::newFromURL( $target );
		if ( is_null( $nt ) ) {
			wfDebug( "Target is invalid title.\n" );
			return "notarget";
		}
	
		$nu = User::newFromName( $nt->getText() );
		if( !$nu instanceof User || !$nu->getId() ) {
			wfDebug( "Target is invalid user.\n" );
			return "notarget";
		} else if ( !$nu->isEmailConfirmed() ) {
			wfDebug( "User has no valid email.\n" );
			return "noemail";
		} else if ( !$nu->canReceiveEmail() ) {
			wfDebug( "User does not allow user emails.\n" );
			return "nowikiemail";
		}
		
		return $nu;
	}
	static function getPermissionsError ( $user, $editToken ) {
		if( !$user->canSendEmail() ) {
			wfDebug( "User can't send.\n" );
			// FIXME: this is also the error if user is in a group
			//        that is not allowed to send e-mail (no right
			//        'sendemail'). Error messages should probably
			//        be more fine grained.
			return "mailnologin";
		}
		
		if( $user->isBlockedFromEmailuser() ) {
			wfDebug( "User is blocked from sending e-mail.\n" );
			return "blockedemailuser";
		}
		
		if( $user->pingLimiter( 'emailuser' ) ) {
			wfDebug( "Ping limiter triggered.\n" );	
			return 'actionthrottledtext';
		}
		
		$hookErr = null;
		wfRunHooks( 'EmailUserPermissionsErrors', array( $user, $editToken, &$hookErr ) );
		
		if ($hookErr) {
			return $hookErr;
		}
		
		if( !$user->matchEditToken( $editToken ) ) {
			wfDebug( "Matching edit token failed.\n" );
			return 'sessionfailure';
		}
	}
	
	static function newFromURL( $target, $text, $subject, $cc_me )
	{
		$nt = Title::newFromURL( $target );
		$nu = User::newFromName( $nt->getText() );
		return new EmailUserForm( $nu, $text, $subject, $cc_me );
	}
}
