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
	if( $isIP )
		$user = $userOrig;
	else
		$user = User::getCanonicalName( $userOrig );
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

	$old = UserRestriction::fetchForUser( $user, true );	// Renew it after possible changes in previous two functions
	if( $old ) {
		$wgOut->addHTML( RestrictUserForm::existingRestrictions( $old ) );
	}
}

class RestrictUserForm {
	public static function selectUserForm( $val = null, $error = null ) {
		global $wgScript, $wgTitle;
		$legend = wfMsgHtml( 'restrictuser-userselect' );
		$s  = Xml::fieldset( $legend ) . "<form action=\"{$wgScript}\">";
		if( $error )
			$s .= '<p>' . $error . '</p>';
		$s .= Xml::hidden( 'title', $wgTitle->getPrefixedDbKey() );
		$form = array( 'restrictuser-user' => Xml::input( 'user', false, $val ) );
		$s .= Xml::buildForm( $form, 'restrictuser-go' );
		$s .= "</form></fieldset>";
		return $s;
	}

	public static function existingRestrictions( $restrictions ) {
		require_once( dirname( __FILE__ ) . '/SpecialListUserRestrictions.php' );
		$legend = wfMsgHtml( 'restrictuser-existing' );
		$s  = Xml::fieldset( $legend ) . '<ul>';
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
				if( !$title )
					$error = wfMsgExt( 'restrictuser-badtitle', 'parseinline', $wgRequest->getVal( 'page' ) );
				elseif( UserRestriction::convertExpiry( $wgRequest->getVal( 'expiry' ) ) === false )
					$error = wfMsgExt( 'restrictuser-badexpiry', 'parseinline', $wgRequest->getVal( 'expiry' ) );
				else 
					foreach( $oldRestrictions as $r )
						if( $r->isPage() && $r->getPage()->equals( $title ) )
							$error = wfMsgExt( 'restrictuser-duptitle', 'parse' );
				if( !$error ) {
					self::doPageRestriction( $uid, $user );
					$success = true;
				}
		}
		$useRequestValues = $wgRequest->getVal( 'type' ) == UserRestriction::PAGE;
		$legend = wfMsgHtml( 'restrictuser-legend-page' );
		$wgOut->addHTML( Xml::fieldset( $legend ) );
		if( $error )
			$wgOut->addHTML( '<strong class="error">' . $error . '</strong>' );
		if( $success )
			$wgOut->addHTML( '<strong class="success">' . wfMsgExt( 'restrictuser-success',
				'parseinline', $user ) . '</strong>' );
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
		$wgOut->addHTML( Xml::buildForm( $form, 'restrictuser-sumbit' ) );
		$wgOut->addHTML( "</form></fieldset>" );
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
	}

	public static function namespaceRestrictionForm( $uid, $user, $oldRestrictions ) {
		global $wgOut, $wgTitle, $wgRequest, $wgUser, $wgContLang;
		$error = '';
		$success = false;
		if( $wgRequest->wasPosted() && $wgRequest->getVal( 'type' ) == UserRestriction::NAMESPACE &&
			$wgUser->matchEditToken( $wgRequest->getVal( 'edittoken' ) ) ) {
				$ns = $wgRequest->getVal( 'namespace' );
				if( $wgContLang->getNsText( $ns ) === false )
					$error = wfMsgExt( 'restrictuser-badnamespace', 'parseinline' );
				elseif( UserRestriction::convertExpiry( $wgRequest->getVal( 'expiry' ) ) === false )
					$error = wfMsgExt( 'restrictuser-badexpiry', 'parseinline', $wgRequest->getVal( 'expiry' ) );
				else 
					foreach( $oldRestrictions as $r )
						if( $r->isNamespace() && $r->getNamespace() == $ns )
							$error = wfMsgExt( 'restrictuser-dupnamespace', 'parse' );
				if( !$error ) {
					self::doNamespaceRestriction( $uid, $user );
					$success = true;
				}
		}
		$useRequestValues = $wgRequest->getVal( 'type' ) == UserRestriction::NAMESPACE;
		$legend = wfMsgHtml( 'restrictuser-legend-namespace' );
		$wgOut->addHTML( Xml::fieldset( $legend ) );
		if( $error )
			$wgOut->addHTML( '<strong class="error">' . $error . '</strong>' );
		if( $success )
			$wgOut->addHTML( '<strong class="success">' . wfMsgExt( 'restrictuser-success',
				'parseinline', $user ) . '</strong>' );
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
		$wgOut->addHTML( Xml::buildForm( $form, 'restrictuser-sumbit' ) );
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
	}
}
