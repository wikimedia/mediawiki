<?php

/**
 * Special page to direct the user to a random redirect page (minus the second redirect)
 *
 * @addtogroup SpecialPage
 * @author Rob Church <robchur@gmail.com>, Ilmari Karonen
 * @license GNU General Public Licence 2.0 or later
 */

/**
 * Main execution point
 * @param $par Namespace to select the redirect from
 */
function wfSpecialRandomredirect( $par = null ) {
	global $wgOut, $wgContLang;

	$rnd = new RandomPage();
	$rnd->setNamespace( $wgContLang->getNsIndex( $par ) );
	$rnd->setRedirect( true );

	$title = $rnd->getRandomTitle();

	if( is_null( $title ) ) {
		$wgOut->addWikiText( wfMsg( 'randomredirect-nopages' ) );
		return;
	}

	$wgOut->reportTime();
	$wgOut->redirect( $title->getFullUrl( 'redirect=no' ) );
}

?>
