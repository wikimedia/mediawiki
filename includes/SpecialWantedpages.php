<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once ( 'QueryPage.php' ) ;

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class WantedPagesPage extends QueryPage {

	function getName() {
		return 'Wantedpages';
	}

	function isExpensive() {
		return true;
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		$brokenlinks = $dbr->tableName( 'brokenlinks' );

		# We cheat and return the full-text from bl_to in the title.
		# In the future, a pre-parsed name will be available.
		$agrvalue=$dbr->aggregateValue('COUNT(DISTINCT bl_from)');
		return
			"SELECT 'Wantedpages' as type,
			        0 as namespace,
			        bl_to as title,
			        COUNT(DISTINCT bl_from) as value
			FROM $brokenlinks
			GROUP BY bl_to
			HAVING $agrvalue > 1
			ORDER BY $agrvalue ".
			($this->sortDescending() ? 'DESC' : '');
	}

        function getOrder() {
                return '';
        }

	function formatResult( $skin, $result ) {
		global $wgLang;

		$nt = Title::newFromDBkey( $result->title );
		if( is_null( $nt ) ) {
			return "<!-- Bad title '" . htmlspecialchars( $result->title ) . "' -->";
		}
		$plink = $skin->makeBrokenLink( $nt->getPrefixedText(), "" );
		$nl = wfMsg( "nlinks", $result->value );
		$nlink = $skin->makeKnownLink( $wgLang->specialPage( "Whatlinkshere" ), $nl,
		  "target=" . $nt->getPrefixedURL() );

		return "{$plink} ({$nlink})";
	}
}

/**
 * constructor
 */
function wfSpecialWantedpages() {
	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new WantedPagesPage();

	$wpp->doQuery( $offset, $limit );
}

?>
