<?php

function wfSpecialSpecialpages()
{
	global $wgLang, $wgOut, $wgUser, $wgSpecialPages;
	
	$wgOut->setRobotpolicy( "index,nofollow" );
	$sk = $wgUser->getSkin();	

	# Categorise special pages

	$pages = array(
	  "" => array(),
	  "sysop" => array(),
	  "developer" => array()
	);

	foreach ( $wgSpecialPages as $page ) {
		$pages[$page->getRestriction()][$page->getName()] = $page;
	}


	# all users special pages
	wfSpecialSpecialpages_gen($pages[""],"spheading",$sk);

	# sysops only special pages
	if ( $wgUser->isSysop() ) {
		wfSpecialSpecialpages_gen($pages["sysop"],"sysopspheading",$sk);
	}

	# developers only special pages
	if ( $wgUser->isDeveloper() ) {
		wfSpecialSpecialpages_gen($pages["developer"],"developerspheading",$sk);

	}
}

# sub function generating the list of pages
#   $pages   : the list of pages
#   $heading : header to be used
#   $sk      : skin object ???

function wfSpecialSpecialpages_gen($pages,$heading,$sk)
{
	global $wgLang, $wgOut, $wgAllowSysopQueries;

	$wgOut->addHTML( "<h2>" . wfMsg( $heading ) . "</h2>\n<ul>" );
	foreach ( $pages as $name => $page ) {
		if( !$page->isListed() ) {
			continue;
		}
		$link = $sk->makeKnownLinkObj( $page->getTitle(), $page->getDescription() );
		$wgOut->addHTML( "<li>{$link}</li>\n" );
	}
	$wgOut->addHTML( "</ul>\n" );
}

?>
