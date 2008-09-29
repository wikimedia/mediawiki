<?php

function wfSpecialRestrictUser( $par = null ) {
	global $wgOut, $wgRequest;
	$user = $userOrig = null;
	if( $par ) {
		$userOrig = $par;
	} elseif( $wgRequest->getVal( 'user' ) ) {
		$userOrig = $wgRequest->getVal( 'user' );
	} else {
		$wgOut->addHTML( RestrictUserForm::selectUserForm() );
		return;
	}
	$isIP = User::isIP( $userOrig );
	$user = $isIP ? $userOrig : User::getCanonicalName( $userOrig );
	$uid = User::idFromName( $user );
	if( !$uid && !$isIP ) {
		$err = '<strong class="error">' . wfMsgHtml( 'restrictuser-notfound' ) . '</strong>';
		$wgOut->addHTML( RestrictUserForm::selectUserForm( $userOrig, $err ) );
		return;
	}
	$wgOut->addHTML( RestrictUserForm::selectUserForm( $user ) );

	UserRestriction::purgeExpired();
	$old = UserRestriction::fetchForUser( $user, true );

	RestrictUserForm::pageRestrictionForm( $uid, $user, $old );
	RestrictUserForm::namespaceRestrictionForm( $uid, $user, $old );

	// Renew it after possible changes in previous two functions
	$old = UserRestriction::fetchForUser( $user, true );
	if( $old ) {
		$wgOut->addHTML( RestrictUserForm::existingRestrictions( $old ) );
	}
}

class RestrictUserForm {
	public static function selectUserForm( $val = null, $error = null ) {
		global $wgScript, $wgTitle;
		$s  = Xml::fieldset( wfMsg( 'restrictuser-userselect' ) ) . "<form action=\"{$wgScript}\">";
		if( $error )
			$s .= '<p>' . $error . '</p>';
		$s .= Xml::hidden( 'title', $wgTitle->getPrefixedDbKey() );
		$form = array( 'restrictuser-user' => Xml::input( 'user', false, $val ) );
		$s .= Xml::buildForm( $form, 'restrictuser-go' );
		$s .= "</form></fieldset>";
		return $s;
	}

	public static function existingRestrictions( $restrictions ) {
		//TODO: autoload?
		require_once( dirname( __FILE__ ) . '/SpecialListUserRestrictions.php' );
		$s  = Xml::fieldset( wfMsg( 'restrictuser-existing' ) ) . '<ul>';
		foreach( $restrictions as $r )
			$s .= UserRestrictionsPager::formatRestriction( $r );
		$s .= "</ul></fieldset>";
		return $s;
	}

	public static function pageRestrictionForm( $uid, $user, $oldRestrictions ) {
		global $wgOut, $wgTitle, $wgRequest, $wgUser;
		$error = '';
		$success = false;
		if( $wgRequest->wasPosted() && $wgRequest->getVal( 'type' ) == UserRestriction::PAGE &&
			$wgUser->matchEditToken( $wgRequest->getVal( 'edittoken' ) ) ) {

			$title = Title::newFromText( $wgRequest->getVal( 'page' ) );
			if( !$title ) {
				$error = array( 'restrictuser-badtitle', $wgRequest->getVal( 'page' ) );
			} elseif( UserRestriction::convertExpiry( $wgRequest->getVal( 'expiry' ) ) === false ) {
				$error = array( 'restrictuser-badexpiry', $wgRequest->getVal( 'expiry' ) );
			} else {
				foreach( $oldRestrictions as $r ) {
					if( $r->isPage() && $r->getPage()->equals( $title ) )
						$error = array( 'restrictuser-duptitle' );
				}
			}
			if( !$error ) {
				self::doPageRestriction( $uid, $user );
				$success = array('restrictuser-success', $user);
			}
		}
		$useRequestValues = $wgRequest->getVal( 'type' ) == UserRestriction::PAGE;
		$wgOut->addHTML( Xml::fieldset( wfMsg( 'restrictuser-legend-page' ) ) );

		self::printSuccessError( $success, $error );

		$wgOut->addHTML( Xml::openElement( 'form', array( 'action' => $wgTitle->getLocalUrl(),
			'method' => 'post' ) ) );
		$wgOut->addHTML( Xml::hidden( 'type', UserRestriction::PAGE ) );
		$wgOut->addHTML( Xml::hidden( 'edittoken', $wgUser->editToken() ) );
		$wgOut->addHTML( Xml::hidden( 'user', $user ) );
		$form = array();
		$form['restrictuser-title'] = Xml::input( 'page', false,
			$useRequestValues ? $wgRequest->getVal( 'page' ) : false );
		$form['restrictuser-expiry'] = Xml::input( 'expiry', false,
			$useRequestValues ? $wgRequest->getVal( 'expiry' ) : false );
		$form['restrictuser-reason'] = Xml::input( 'reason', false,
			$useRequestValues ? $wgRequest->getVal( 'reason' ) : false );
		$wgOut->addHTML( Xml::buildForm( $form, 'restrictuser-submit' ) );
		$wgOut->addHTML( "</form></fieldset>" );
	}

	public static function printSuccessError( $success, $error ) {
		global $wgOut;
		if ( $error )
			$wgOut->wrapWikiMsg( '<strong class="error">$1</strong>', $error );
		if ( $success )
			$wgOut->wrapWikiMsg( '<strong class="success">$1</strong>', $success );
	}

	public static function doPageRestriction( $uid, $user ) {
		global $wgUser, $wgRequest;
		$r = new UserRestriction();
		$r->setType( UserRestriction::PAGE );
		$r->setPage( Title::newFromText( $wgRequest->getVal( 'page' ) ) );
		$r->setSubjectId( $uid );
		$r->setSubjectText( $user );
		$r->setBlockerId( $wgUser->getId() );
		$r->setBlockerText( $wgUser->getName() );
		$r->setReason( $wgRequest->getVal( 'reason' ) );
		$r->setExpiry( UserRestriction::convertExpiry( $wgRequest->getVal( 'expiry' ) ) );
		$r->setTimestamp( wfTimestampNow( TS_MW ) );
		$r->commit();
		$logExpiry = $wgRequest->getVal( 'expiry' ) ? $wgRequest->getVal( 'expiry' ) : Block::infinity();
		$l = new LogPage( 'restrict' );
		$l->addEntry( 'restrict', Title::makeTitle( NS_USER, $user ), $r->getReason(),
			array( $r->getType(), $r->getPage()->getFullText(), $logExpiry) );
		self::invalidateCache( $user );
	}

	public static function namespaceRestrictionForm( $uid, $user, $oldRestrictions ) {
		global $wgOut, $wgTitle, $wgRequest, $wgUser, $wgContLang;
		$error = '';
		$success = false;
		if( $wgRequest->wasPosted() && $wgRequest->getVal( 'type' ) == UserRestriction::NAMESPACE &&
			$wgUser->matchEditToken( $wgRequest->getVal( 'edittoken' ) ) ) {
				$ns = $wgRequest->getVal( 'namespace' );
				if( $wgContLang->getNsText( $ns ) === false )
					$error = array( 'restrictuser-badnamespace' );
				elseif( UserRestriction::convertExpiry( $wgRequest->getVal( 'expiry' ) ) === false )
					$error = array( 'restrictuser-badexpiry', $wgRequest->getVal( 'expiry' ) );
				else 
					foreach( $oldRestrictions as $r )
						if( $r->isNamespace() && $r->getNamespace() == $ns )
							$error = array( 'restrictuser-dupnamespace' );
				if( !$error ) {
					self::doNamespaceRestriction( $uid, $user );
					$success = array('restrictuser-success', $user);
				}
		}
		$useRequestValues = $wgRequest->getVal( 'type' ) == UserRestriction::NAMESPACE;
		$wgOut->addHTML( Xml::fieldset( wfMsg( 'restrictuser-legend-namespace' ) ) );

		self::printSuccessError( $success, $error );

		$wgOut->addHTML( Xml::openElement( 'form', array( 'action' => $wgTitle->getLocalUrl(),
			'method' => 'post' ) ) );
		$wgOut->addHTML( Xml::hidden( 'type', UserRestriction::NAMESPACE ) );
		$wgOut->addHTML( Xml::hidden( 'edittoken', $wgUser->editToken() ) );
		$wgOut->addHTML( Xml::hidden( 'user', $user ) );
		$form = array();
		$form['restrictuser-namespace'] = Xml::namespaceSelector( $wgRequest->getVal( 'namespace' ) );
		$form['restrictuser-expiry'] = Xml::input( 'expiry', false,
			$useRequestValues ? $wgRequest->getVal( 'expiry' ) : false );
		$form['restrictuser-reason'] = Xml::input( 'reason', false,
			$useRequestValues ? $wgRequest->getVal( 'reason' ) : false );
		$wgOut->addHTML( Xml::buildForm( $form, 'restrictuser-submit' ) );
		$wgOut->addHTML( "</form></fieldset>" );
	}

	public static function doNamespaceRestriction( $uid, $user ) {
		global $wgUser, $wgRequest;
		$r = new UserRestriction();
		$r->setType( UserRestriction::NAMESPACE );
		$r->setNamespace( $wgRequest->getVal( 'namespace' ) );
		$r->setSubjectId( $uid );
		$r->setSubjectText( $user );
		$r->setBlockerId( $wgUser->getId() );
		$r->setBlockerText( $wgUser->getName() );
		$r->setReason( $wgRequest->getVal( 'reason' ) );
		$r->setExpiry( UserRestriction::convertExpiry( $wgRequest->getVal( 'expiry' ) ) );
		$r->setTimestamp( wfTimestampNow( TS_MW ) );
		$r->commit();
		$logExpiry = $wgRequest->getVal( 'expiry' ) ? $wgRequest->getVal( 'expiry' ) : Block::infinity();
		$l = new LogPage( 'restrict' );
		$l->addEntry( 'restrict', Title::makeTitle( NS_USER, $user ), $r->getReason(),
			array( $r->getType(), $r->getNamespace(), $logExpiry ) );
		self::invalidateCache( $user );
	}

	private static function invalidateCache( $user ) {
		$userObj = User::newFromName( $user, false );
		$userObj->invalidateCache();
	}
}
