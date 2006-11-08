<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class WantedPagesPage extends QueryPage {
	var $nlinks;

	function WantedPagesPage( $inc = false, $nlinks = true ) {
		$this->setListoutput( $inc );
		$this->nlinks = $nlinks;
	}

	function getName() {
		return 'Wantedpages';
	}

	function isExpensive() {
		return true;
	}
	function isSyndicated() { return false; }

	function getSQL() {
		global $wgWantedPagesThreshold;
		$count = $wgWantedPagesThreshold - 1;
		$dbr =& wfGetDB( DB_SLAVE );
		$pagelinks = $dbr->tableName( 'pagelinks' );
		$page      = $dbr->tableName( 'page' );
		return
			"SELECT 'Wantedpages' AS type,
			        pl_namespace AS namespace,
			        pl_title AS title,
			        COUNT(*) AS value
			 FROM $pagelinks
			 LEFT JOIN $page AS pg1
			 ON pl_namespace = pg1.page_namespace AND pl_title = pg1.page_title
			 LEFT JOIN $page AS pg2
			 ON pl_from = pg2.page_id
			 WHERE pg1.page_namespace IS NULL
			 AND pl_namespace NOT IN ( 2, 3 )
			 AND pg2.page_namespace != 8
			 GROUP BY 1,2,3
			 HAVING COUNT(*) > $count";
	}

	/**
	 * Cache page existence for performance
	 */
	function preprocessResults( &$db, &$res ) {
		$batch = new LinkBatch;
		while ( $row = $db->fetchObject( $res ) )
			$batch->addObj( Title::makeTitleSafe( $row->namespace, $row->title ) );
		$batch->execute();

		// Back to start for display
		if ( $db->numRows( $res ) > 0 )
			// If there are no rows we get an error seeking.
			$db->dataSeek( $res, 0 );
	}


	function formatResult( $result ) {
		global $wgLang;

		$title = Title::makeTitleSafe( $result->namespace, $result->title );

		if( $this->isCached() ) {
			# Check existence; which is stored in the link cache
			if( !$title->exists() ) {
				# Make a redlink
				$pageLink = Linker::makeBrokenLinkObj( $title );
			} else {
				# Make a a struck-out normal link
				$pageLink = "<s>" . Linker::makeLinkObj( $title ) . "</s>";
			}		
		} else {
			# Not cached? Don't bother checking existence; it can't
			$pageLink = Linker::makeBrokenLinkObj( $title );
		}
		
		# Make a link to "what links here" if it's required
		$wlhLink = $this->nlinks
					? $this->makeWlhLink( $title,
							wfMsgExt( 'nlinks', array( 'parsemag', 'escape'),
								$wgLang->formatNum( $result->value ) ) )
					: null;
					
		return wfSpecialList($pageLink, $wlhLink);
	}
	
	/**
	 * Make a "what links here" link for a specified title
	 * @param $title Title to make the link for
	 * @param $text Link text
	 * @return string
	 */
	function makeWlhLink( &$title, $text ) {
		$wlhTitle = SpecialPage::getTitleFor( 'Whatlinkshere' );
		return Linker::makeKnownLinkObj( $wlhTitle, $text, 'target=' . $title->getPrefixedUrl() );
	}
	
}

/**
 * constructor
 */
function wfSpecialWantedpages( $par = null, $specialPage ) {
	$inc = $specialPage->including();

	if ( $inc ) {
		@list( $limit, $nlinks ) = explode( '/', $par, 2 );
		$limit = (int)$limit;
		$nlinks = $nlinks === 'nlinks';
		$offset = 0;
	} else {
		list( $limit, $offset ) = wfCheckLimits();
		$nlinks = true;
	}

	$wpp = new WantedPagesPage( $inc, $nlinks );

	$wpp->doQuery( $offset, $limit, !$inc );
}

?>
