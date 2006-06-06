<?php
/**
 * @package MediaWiki
 * @subpackage SpecialPage
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class MostimagesPage extends QueryPage {

	function getName() { return 'Mostimages'; }
	function isExpensive() { return true; }
	function isSyndicated() { return false; }

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'imagelinks' ) );
		return
			"
			SELECT
				'Mostimages' as type,
				" . NS_IMAGE . " as namespace,
				il_to as title,
				COUNT(*) as value
			FROM $imagelinks
			GROUP BY il_to
			HAVING COUNT(*) > 1
			";
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;

		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = $wgContLang->convert( $nt->getPrefixedText() );

		$plink = $skin->makeKnownLink( $nt->getPrefixedText(), $text );

		$nl = wfMsgExt( 'nlinks', array( 'parsemag', 'escape'),
			$wgLang->formatNum ( $result->value ) );
		$nlink = $skin->makeKnownLink( $nt->getPrefixedText() . '#filelinks', $nl );

		return wfSpecialList($plink, $nlink);
	}
}

/**
 * Constructor
 */
function wfSpecialMostimages() {
	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new MostimagesPage();

	$wpp->doQuery( $offset, $limit );
}

?>
