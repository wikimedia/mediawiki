<?php

function wfSpecialBlankpage() {
	global $wgOut;
	$wgOut->addWikiMsg('intentionallyblankpage');
}
