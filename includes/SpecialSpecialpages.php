<?php

function wfSpecialSpecialpages()
{
	global $wgLang, $wgOut, $wgUser;
	
	# sub function generating the list of pages
	#   $SP      : the list of pages
	#   $heading : header to be used
	#   $sk      : skin object ???
	
	function wfSpecialSpecialpages_gen($SP,$heading,$sk)
	{
		global $wgLang, $wgOut;

		$wgOut->addHTML( "<h2>" . wfMsg( $heading ) . "</h2>\n<ul>" );
		foreach ( $SP as $name => $desc ) {
			if ( "" == $desc ) { continue; }
			$link = $sk->makeKnownLink( $wgLang->specialPage( $name ), $desc );
			$wgOut->addHTML( "<li>{$link}</li>\n" );
		}
		$wgOut->addHTML( "</ul>\n" );
	}

	$wgOut->setRobotpolicy( "index,nofollow" );
	$sk = $wgUser->getSkin();	

	# all users special pages
	wfSpecialSpecialpages_gen($wgLang->getValidSpecialPages(),"spheading",$sk);

	# sysops only special pages
	if ( $wgUser->isSysop() ) {
		wfSpecialSpecialpages_gen($wgLang->getSysopSpecialPages(),"sysopspheading",$sk);
	}

	# developers only special pages
	if ( $wgUser->isDeveloper() ) {
		wfSpecialSpecialpages_gen($wgLang->getDeveloperSpecialPages(),"developerspheading",$sk);

	}
}

?>
