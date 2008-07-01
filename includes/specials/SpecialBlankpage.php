<?php

function wfSpecialBlankpage() {
	global $wgOut;
	$wgOut->addHTML('intentionallyblankpage');
}
