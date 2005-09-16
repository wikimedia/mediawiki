<?php
/**
 * Search for pages in NS_IMAGE that link to a certain page in NS_TEMPLATE
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/* */
require_once 'QueryPage.php';

/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class LicensesearchPage extends QueryPage {
	var $license;

	function LicensesearchPage( $license ) {
		$this->license = $license;
	}
	
	function getName() { return 'Licensesearch'; }
	
	/**
	 * Due to this page relying upon extra fields being passed in the SELECT it
	 * will fail if it's set as expensive and misermode is on
	 */
	function isExpensive() { return false; }
	function isSyndicated() { return false; }
	
	function linkParameters() {
		return array( 'license' => $this->license );
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'page', 'pagelinks' ) );
		$license = $dbr->addQuotes( $this->license );
		
		return
			"SELECT 'Licensesearch' as type,
				" . NS_IMAGE . " as namespace,
				page_title as title,
				page_title as value
			FROM $pagelinks
			LEFT JOIN $page ON page_id = pl_from
			WHERE page_namespace = " . NS_IMAGE . " AND pl_namespace = " . NS_TEMPLATE  . " AND pl_title = $license
			";
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;

		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = $wgContLang->convert( $nt->getText() );
		$plink = $skin->makeLink( $nt->getPrefixedText(), $text );

		return $plink;
	}
}

/**
 * constructor
 */
function wfSpecialLicensesearch( $par = null ) {
	global $wgRequest, $wgTitle, $wgOut;

	$license = isset( $par ) ? $par : $wgRequest->getText( 'license' );

	$wgOut->addHTML(
		wfElement( 'form',
			array(
				'id' => 'speciallicensesearch',
				'method' => 'get',
				'action' => $wgTitle->escapeLocalUrl()
			),
			null
		) .
			wfOpenElement( 'label' ) .
				wfMsgHtml( 'licensesearch_license' ) .
				wfElement( 'input', array(
						'type' => 'text',
						'size' => 20,
						'name' => 'license',
						'value' => $license
					),
					''
				) .
				' ' .
				wfElement( 'input', array(
						'type' => 'submit',
						'value' => wfMsg( 'ilsubmit' )
					),
					''
				) .
			wfCloseElement( 'label' ) .
		wfCloseElement( 'form' )
	);

	$license =  trim( $license, ' ' );
	if ($license == '')
		return;

	$license = wfSpecialLicensesearchLicense( $license );
	$wpp = new LicensesearchPage( $license );
	
	list( $limit, $offset ) = wfCheckLimits();
	$wpp->doQuery( $offset, $limit );
}

function wfSpecialLicensesearchLicense( $license ) {
	$aliases = wfSpecialLicensesearchLicenseParser();
	
	if ( @$aliases[$license] !== null )
		return $aliases[$license];
	else
		return $license;
}

function wfSpecialLicensesearchLicenseParser() {
	$aliases = array();
	
	$licenses = explode( "\n", wfMsgForContent( 'licensesearch_licenses' ) );
	foreach ($licenses as $line) {
		if ( strpos( $line, '*' ) !== 0 || strpos( $line, '|' ) === false)
			continue;
		else {
			$line = ltrim( $line, '* ' );
			list( $template, $alias ) = explode( '|', $line, 2 );
			$aliases[$alias] = $template;
		}
	}

	return $aliases;
}
?>
