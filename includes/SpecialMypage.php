<?php
function wfSpecialMypage() {
	global $wgUser, $wgOut;
	$t = Title::makeTitle( NS_USER, $wgUser->getName() );
	$wgOut->redirect ($t->getFullURL());
}
?>