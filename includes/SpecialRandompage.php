<?php
/**
 * @version # $Id$
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Constructor
 */
function wfSpecialRandompage() {
	global $wgOut, $wgTitle, $wgArticle, $wgExtraRandompageSQL;
	$fname = 'wfSpecialRandompage';

	$rand = mt_rand() / mt_getrandmax();
	# interpolation and sprintf() can muck up with locale-specific decimal separator
	$randstr = number_format( $rand, 12, ".", "" );
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
	if( $s = $db->fetchObject( $res ) ) {
		$rt = wfUrlEncode( $s->cur_title );
	} else {
		# No articles?!
		$rt = "";
	}

	$wgOut->reportTime(); # for logfile
	$wgOut->redirect( wfLocalUrl( $rt ) );
}

?>
