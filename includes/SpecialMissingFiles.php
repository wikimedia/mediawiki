<?php
/**
 * A querypage to list the missing files - implements Special:Missingfiles
 *
 * @addtogroup SpecialPage
 *
 * @author Matěj Grabovský <65s.mg@atlas.cz>
 * @copyright Copyright © 2008, Matěj Grabovský
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
class MissingFilesPage extends QueryPage {
	function getName() {
		return 'Missingfiles';
	}
	    
	function isExpensive() {
		return true;
	}
	    
	function isSyndicated() {
		return false;
	}
	
	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $imagelinks, $page ) = $dbr->tableNamesN( 'imagelinks', 'page' );
		$name = $dbr->addQuotes( $this->getName() );
		
		return "SELECT $name as type,
			 " . NS_IMAGE . " as namespace,
			 il_to as title,
			 COUNT(*) as value
			 FROM $imagelinks
			 LEFT JOIN $page ON il_to = page_title AND page_namespace = ". NS_IMAGE ."
			 WHERE page_title IS NULL
			 GROUP BY 1,2,3
		";
	}
	
	function sortDescending() {
		return true;
	}
	
	/**
	 * Fetch user page links and cache their existence
	 */
	function preprocessResults( $db, $res ) {
		$batch = new LinkBatch;
		
		while ( $row = $db->fetchObject( $res ) )
			$batch->addObj( Title::makeTitleSafe( $row->namespace, $row->title ) );
		
		$batch->execute();
		
		// Back to start for display
		if ( $db->numRows( $res ) > 0 )
		
		// If there are no rows we get an error seeking.
		$db->dataSeek( $res, 0 );
	}
	
	public function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;
		
		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = $wgContLang->convert( $nt->getText() );
		
		$plink = $this->isCached() 
			? '<s>' . $skin->makeLinkObj( $nt, htmlspecialchars( $text ) ) . '</s>'
			: $skin->makeBrokenImageLinkObj( $nt, htmlspecialchars( $text ) );
		
		$label = wfMsgExt( 'nlinks', array( 'parsemag', 'escape' ), $wgLang->formatNum( $result->value ) );
		$nlinks = $skin->makeKnownLinkObj( SpecialPage::getTitleFor( 'Whatlinkshere' ), $label, 'target=' . $nt->getPrefixedUrl() );
		return wfSpecialList( $plink, $nlinks );
	}
}

/**
 * Constructor
 */
function wfSpecialMissingFiles() {
	list( $limit, $offset ) = wfCheckLimits();
	
	$wpp = new MissingFilesPage();
	
	$wpp->doQuery( $offset, $limit );
}