<?php
/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Constructor
 */
function wfSpecialRandompage() {
	global $wgOut, $wgTitle, $wgArticle, $wgExtraRandompageSQL;
	$fname = 'wfSpecialRandompage';

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
	$use_index = $db->useIndexClause( 'cur_random' );
	$cur = $db->tableName( 'cur' );

	if ( $wgExtraRandompageSQL ) {
		$extra = "AND ($wgExtraRandompageSQL)";
	} else {
		$extra = '';
	}
	$sqlget = "SELECT cur_id,cur_title
		FROM $cur $use_index
		WHERE cur_namespace=0 AND cur_is_redirect=0 $extra
		AND cur_random>$randstr
		ORDER BY cur_random
		LIMIT 1";
	$res = $db->query( $sqlget, $fname );
	
	$title = null;
	if( $s = $db->fetchObject( $res ) ) {
		$title =& Title::makeTitle( NS_MAIN, $s->cur_title );
	}	
	if( is_null( $title ) ) {
		# That's not supposed to happen :)
		$title =& Title::newFromText( wfMsg( 'mainpage' ) );
	}
	$wgOut->reportTime(); # for logfile
	$wgOut->redirect( $title->getFullUrl() );
}

?>
