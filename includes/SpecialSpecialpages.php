<?php

function wfSpecialSpecialpages()
{
	global $wgUser, $wgOut, $wgLang, $wgAllowSysopQueries;

	$wgOut->setRobotpolicy( "index,nofollow" );

	$sk = $wgUser->getSkin();
	$validSP = $wgLang->getValidSpecialPages();
	$wgOut->addHTML( "<h2>" . wfMsg( "spheading" ) . "</h2>\n<ul>" );

	foreach ( $validSP as $name => $desc ) {
		if ( "" == $desc ) { continue; }
		$link = $sk->makeKnownLink( $wgLang->specialPage( $name ), $desc );
		$wgOut->addHTML( "<li>{$link}</li>\n" );
	}
	$wgOut->addHTML( "</ul>\n" );

	if ( $wgUser->isSysop() ) {
		$sysopSP = $wgLang->getSysopSpecialPages();
		$wgOut->addHTML( "<h2>" . wfMsg( "sysopspheading" ) . "</h2>\n<ul>" );

		foreach ( $sysopSP as $name => $desc ) {
			if ( "" == $desc ) { continue; }
			if( "Asksql" == $name && !$wgAllowSysopQueries ) {
				continue;
			}
			$link = $sk->makeKnownLink( $wgLang->specialPage( $name ), $desc );
			$wgOut->addHTML( "<li>{$link}</li>\n" );
		}
		$wgOut->addHTML( "</ul>\n" );
	}

	if ( $wgUser->isDeveloper() ) {
		$devSP = $wgLang->getDeveloperSpecialPages();
		$wgOut->addHTML( "<h2>" . wfMsg( "developerspheading" ) .
		  "</h2>\n<ul>" );

		foreach ( $devSP as $name => $desc ) {
			if ( "" == $desc ) { continue; }
			$link = $sk->makeKnownLink( $wgLang->specialPage( $name ), $desc );
			$wgOut->addHTML( "<li>{$link}</li>\n" );
		}
		$wgOut->addHTML( "</ul>\n" );
	}
}

?>
