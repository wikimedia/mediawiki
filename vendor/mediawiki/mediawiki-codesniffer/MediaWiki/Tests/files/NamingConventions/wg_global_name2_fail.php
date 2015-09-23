<?php

function wfFooFoo () {
	// The first global is fine, the second isn't
	global $wgContLang, $LocalInterwikis;
	$wgContLang = 'en';
	$LocalInterwikis = false;
}
