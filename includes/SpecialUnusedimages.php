<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/** */
require_once("QueryPage.php");

/**
 *
 */
class UnusedimagesPage extends QueryPage {

	function getName() {
		return 'Unusedimages';
	}
	
	function sortDescending() {
		return false;
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'image','imagelinks' ) );
		
		return 'SELECT img_name as title, img_user, img_user_text, img_timestamp as value, img_description' .
		      ' FROM '.$image.' LEFT JOIN '.$imagelinks.' ON img_name=il_to WHERE il_to IS NULL ';
	}
	
	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;
		$title = Title::makeTitle( NS_IMAGE, $result->title );
		
		$imageUrl = htmlspecialchars( Image::wfImageUrl( $result->title ) );
		$return =
		# The 'desc' linking to the image page
		'('.$skin->makeKnownLinkObj( $title, wfMsg('imgdesc') ).') '
		# Link to the image itself
		. '<a href="' . $imageUrl . '">' . htmlspecialchars( $title->getText() ) . '</a>'
		# Last modified date
		. ' . . '.$wgLang->timeanddate($result->value)
		# Link to username
		. ' . . '.$skin->makeLinkObj( Title::makeTitle( NS_USER, $result->img_user_text ), $result->img_user_text);
		
		# If there is a description, show it
		if($result->img_description != '') {
			$return .= ' <i>(' . $skin->formatComment( $result->img_description ) . ')</i>';
		}
		return $return;
	}
	
	function getPageHeader() {
		return wfMsg( "unusedimagestext" );
	}

}

/**
 * Entry point
 */
function wfSpecialUnusedimages() {
	list( $limit, $offset ) = wfCheckLimits();
	$uip = new UnusedimagesPage();

	return $uip->doQuery( $offset, $limit );
}
?>
