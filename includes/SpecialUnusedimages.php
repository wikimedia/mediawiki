<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class UnusedimagesPage extends QueryPage {

	function getName() {
		return 'Unusedimages';
	}

	function sortDescending() {
		return false;
	}
	function isSyndicated() { return false; }

	function getSQL() {
		global $wgCountCategorizedImagesAsUsed;
		$dbr =& wfGetDB( DB_SLAVE );

		if ( $wgCountCategorizedImagesAsUsed ) {
			extract( $dbr->tableNames( 'page', 'image', 'imagelinks', 'categorylinks' ) );

			return 'SELECT img_name as title, img_user, img_user_text, img_timestamp as value, img_description
					FROM ((('.$page.' AS I LEFT JOIN '.$categorylinks.' AS L ON I.page_id = L.cl_from)
						LEFT JOIN '.$imagelinks.' AS P ON I.page_title = P.il_to)
						INNER JOIN '.$image.' AS G ON I.page_title = G.img_name)
					WHERE I.page_namespace = '.NS_IMAGE.' AND L.cl_from IS NULL AND P.il_to IS NULL';
		} else {
			extract( $dbr->tableNames( 'image','imagelinks' ) );

			return 'SELECT img_name as title, img_user, img_user_text, img_timestamp as value, img_description' .
			' FROM '.$image.' LEFT JOIN '.$imagelinks.' ON img_name=il_to WHERE il_to IS NULL ';
		}
	}

	function formatResult( $result ) {
		global $wgLang, $wgContLang;
		$title = Title::makeTitle( NS_IMAGE, $result->title );

		$imageUrl = htmlspecialchars( Image::imageUrl( $result->title ) );
		$dirmark = $wgContLang->getDirMark(); // To keep text in correct order

		$return =
		# The 'desc' linking to the image page
		'('.Linker::makeKnownLinkObj( $title, wfMsg('imgdesc') ).') ' . $dirmark .

		# Link to the image itself
		'<a href="' . $imageUrl . '">' . htmlspecialchars( $title->getText() ) .
			'</a> . . ' . $dirmark .

		# Last modified date
		$wgLang->timeanddate($result->value) . ' . . ' . $dirmark .

		# Link to username
		Linker::makeLinkObj( Title::makeTitle( NS_USER, $result->img_user_text ),
			$result->img_user_text) . $dirmark .

		# If there is a description, show it
		Linker::commentBlock( $wgContLang->convert( $result->img_description ) );

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
