<?php

function wfSpecialRemoveRestrictions() {
	global $wgOut, $wgRequest, $wgUser, $wgLang, $wgTitle;
	$sk = $wgUser->getSkin();

	$id = $wgRequest->getVal( 'id' );
	if( !is_numeric( $id ) ) {
		$wgOut->addWikiMsg( 'removerestrictions-noid' );
		return;
	}

	UserRestriction::purgeExpired();
	$r = UserRestriction::newFromId( $id, true );
	if( !$r ) {
		$wgOut->addWikiMsg( 'removerestrictions-wrongid' );
		return;
	}

	$form = array();
	$form['removerestrictions-user'] = $sk->userLink( $r->getSubjectId(), $r->getSubjectText() ) .
		$sk->userToolLinks( $r->getSubjectId(), $r->getSubjectText() );
	$form['removerestrictions-type'] = UserRestriction::formatType( $r->getType() );
	if( $r->isPage() )
		$form['removerestrictions-page'] = $sk->link( $r->getPage() );
	if( $r->isNamespace() )
		$form['removerestrictions-namespace'] = $wgLang->getDisplayNsText( $r->getNamespace() );
	$form['removerestrictions-reason'] = Xml::input( 'reason' );

	$result = null;
	if( $wgRequest->wasPosted() && $wgUser->matchEditToken( $wgRequest->getVal( 'edittoken' ) ) )
		$result = wfSpecialRemoveRestrictionsProcess( $r );

	$wgOut->addWikiMsg( 'removerestrictions-intro' );
	$wgOut->addHTML( Xml::fieldset( wfMsgHtml( 'removerestrictions-legend' ) ) );
	if( $result )
		$wgOut->addHTML( '<strong class="success">' . wfMsgExt( 'removerestrictions-success',
			'parseinline', $r->getSubjectText() ) . '</strong>' );
	$wgOut->addHTML( Xml::openElement( 'form', array( 'action' => $wgTitle->getLocalUrl( array( 'id' => $id ) ),
		'method' => 'post' ) ) );
	$wgOut->addHTML( Xml::buildForm( $form, 'removerestrictions-submit' ) );
	$wgOut->addHTML( Xml::hidden( 'id', $r->getId() ) );
	$wgOut->addHTML( Xml::hidden( 'title', $wgTitle->getPrefixedDbKey() ) );
	$wgOut->addHTML( Xml::hidden( 'edittoken', $wgUser->editToken() ) );
	$wgOut->addHTML( "</form></fieldset>" );
}

function wfSpecialRemoveRestrictionsProcess( $r ) {
	global $wgUser, $wgRequest;
	$reason = $wgRequest->getVal( 'reason' );
	$result = $r->delete();
	$log = new LogPage( 'restrict' );
	$params = array( $r->getType() );
	if( $r->isPage() )
		$params[] = $r->getPage()->getPrefixedDbKey();
	if( $r->isNamespace() )
		$params[] = $r->getNamespace();
	$log->addEntry( 'remove', Title::makeTitle( NS_USER, $r->getSubjectText() ), $reason, $params );
	return $result;
}
