<?php

/**
 * Special page to direct the user to a random redirect page (minus the second redirect)
 *
 * @addtogroup Special pages
 * @author Rob Church <robchur@gmail.com>
 * @licence GNU General Public Licence 2.0 or later
 */

/**
 * Main execution point
 * @param $par Namespace to select the redirect from
 */
function wfSpecialRandomredirect( $par = NULL ) {
	global $wgOut, $wgExtraRandompageSQL, $wgContLang;
	$fname = 'wfSpecialRandomredirect';

	# Validate the namespace
	$namespace = $wgContLang->getNsIndex( $par );
	if( $namespace === false || $namespace < NS_MAIN )
		$namespace = NS_MAIN;

	# Same logic as RandomPage
	$randstr = wfRandom();

	$dbr = wfGetDB( DB_SLAVE );
	$use_index = $dbr->useIndexClause( 'page_random' );
	$page = $dbr->tableName( 'page' );

	$extra = $wgExtraRandompageSQL ? "AND ($wgExtraRandompageSQL)" : '';
	$sql = "SELECT page_id,page_title
		FROM $page $use_index
		WHERE page_namespace = $namespace AND page_is_redirect = 1 $extra
		AND page_random > $randstr
		ORDER BY page_random";
		
	$sql = $dbr->limitResult( $sql, 1, 0 );
	$res = $dbr->query( $sql, $fname );

	$title = NULL;
	if( $row = $dbr->fetchObject( $res ) )
		$title = Title::makeTitleSafe( $namespace, $row->page_title );

	# Catch dud titles and return to the main page
	if( is_null( $title ) )
		$title = Title::newMainPage();
		
	$wgOut->reportTime();
	$wgOut->redirect( $title->getFullUrl( 'redirect=no' ) );
}

?>
