<?php

function wfSpecialDebug()
{
	global $wgUser, $wgOut;

	if ( ! $wgUser->isDeveloper() ) {
		$wgOut->developerRequired();
		return;
	}
	phpinfo();
}

