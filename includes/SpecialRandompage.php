<?php
/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Constructor
 *
 * @param string $par the namespace to get a random page from (default NS_MAIN), 
 *               used as e.g. Special:Randompage/Category
 */
function wfSpecialRandompage( $par = NS_MAIN ) {
	global $wgOut, $wgTitle, $wgArticle, $wgExtraRandompageSQL, $wgContLang;
	$fname = 'wfSpecialRandompage';

	# Determine the namespace to get a random page from.
	$namespace = $wgContLang->getNsIndex($par);
	if (!$namespace || $namespace < NS_MAIN) {
		$namespace = NS_MAIN;
	}
	
	# NOTE! We use a literal constant in the SQL instead of the RAND()
	# function because RAND() will return a different value for every row
	# in the table. That's both very slow and returns results heavily
	# biased towards low values, as rows later in the table will likely
	# never be reached for comparison.
	#
	# Using a literal constant means the whole thing gets optimized on
	# the index, and the comparison is both fast and fair.
	
	# interpolation and sprintf() can muck up with locale-specific decimal separator
	$randstr = wfRandom();
	
	$db =& wfGetDB( DB_SLAVE );
	$use_index = $db->useIndexClause( 'page_random' );
	$page = $db->tableName( 'page' );

	$extra = $wgExtraRandompageSQL ? "AND ($wgExtraRandompageSQL)" : '';
	$sql = "SELECT page_id,page_title
		FROM $page $use_index
		WHERE page_namespace=$namespace AND page_is_redirect=0 $extra
		AND page_random>$randstr
		ORDER BY page_random
		LIMIT 1";
	$res = $db->query( $sql, $fname );
	
	$title = null;
	if( $s = $db->fetchObject( $res ) ) {
		$title =& Title::makeTitle( $namespace, $s->page_title );
	}	
	if( is_null( $title ) ) {
		# That's not supposed to happen :)
		$title =& Title::newFromText( wfMsg( 'mainpage' ) );
	}
	$wgOut->reportTime(); # for logfile
	$wgOut->redirect( $title->getFullUrl() );
}

?>
