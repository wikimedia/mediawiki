<?php

function wfSpecialDismissnotice() {
	global $wgOut, $wgTitle, $wgUser, $wgRequest, $wgMajorSiteNoticeID;
	global $wgCookiePath, $wgCookiePrefix, $wgCookieDomain, $wgCookieExpiration;
	
	# Logged-out users cannot dismiss notice
	if($wgUser->isAnon()) {
		$wgOut->addWikiText( wfMsg('dismissnotice-nologin') );
		return;
	}
	
	# Set the cookie and redirect back to where they came from (or Main Page if they just typed it in the URL)
	$id = intval( $wgMajorSiteNoticeID ) . "." . intval( wfMsgForContent( 'sitenotice_id' ) );
	#not using WebResponse's setcookie method because the cookie cannot be httpOnly
	setcookie( $wgCookiePrefix . 'DismissSiteNotice',
		$id,
		time() + $wgCookieExpiration,
		$wgCookiePath,
		$wgCookieDomain,
		false,
		false
	);
	
	$titleObj = Title::newFromText( $wgRequest->getVal('returnto') );
	if ( !$titleObj instanceof Title ) {
		$titleObj = Title::newMainPage();
	}

	$wgOut->redirect( $titleObj->getFullURL() );
}