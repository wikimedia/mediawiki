<?php
/**
 * @file
 * @ingroup SpecialPage
 *
 * @author Brion Vibber
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * Special:LinkSearch to search the external-links table.
 * @ingroup SpecialPage
 */

function wfSpecialLinkSearch( $par ) {

	list( $limit, $offset ) = wfCheckLimits();
	global $wgOut, $wgRequest, $wgUrlProtocols, $wgMiserMode;
	$target = $GLOBALS['wgRequest']->getVal( 'target', $par );
	$namespace = $GLOBALS['wgRequest']->getIntorNull( 'namespace', null );

	$protocols_list[] = '';
	foreach( $wgUrlProtocols as $prot ) {
		$protocols_list[] = $prot;
	}

	$target2 = $target;
	$protocol = '';
	$pr_sl = strpos($target2, '//' );
	$pr_cl = strpos($target2, ':' );
	if ( $pr_sl ) {
		// For protocols with '//'
		$protocol = substr( $target2, 0 , $pr_sl+2 );
		$target2 = substr( $target2, $pr_sl+2 );
	} elseif ( !$pr_sl && $pr_cl ) {
		// For protocols without '//' like 'mailto:'
		$protocol = substr( $target2, 0 , $pr_cl+1 );
		$target2 = substr( $target2, $pr_cl+1 );
	} elseif ( $protocol == '' && $target2 != '' ) {
		// default
		$protocol = 'http://';
	}
	if ( !in_array( $protocol, $protocols_list ) ) {
		// unsupported protocol, show original search request
		$target2 = $target;
		$protocol = '';
	}

	$self = Title::makeTitle( NS_SPECIAL, 'Linksearch' );

	$wgOut->addWikiText( wfMsg( 'linksearch-text', '<nowiki>' . implode( ', ',  $wgUrlProtocols) . '</nowiki>' ) );
	$s =	Xml::openElement( 'form', array( 'id' => 'mw-linksearch-form', 'method' => 'get', 'action' => $GLOBALS['wgScript'] ) ) .
		Xml::hidden( 'title', $self->getPrefixedDbKey() ) .
		'<fieldset>' .
		Xml::element( 'legend', array(), wfMsg( 'linksearch' ) ) .
		Xml::inputLabel( wfMsg( 'linksearch-pat' ), 'target', 'target', 50, $target ) . ' ';
	if ( !$wgMiserMode ) {
		$s .= Xml::label( wfMsg( 'linksearch-ns' ), 'namespace' ) . ' ' .
			XML::namespaceSelector( $namespace, '' );
	}
	$s .=	Xml::submitButton( wfMsg( 'linksearch-ok' ) ) .
		'</fieldset>' .
		Xml::closeElement( 'form' );
	$wgOut->addHtml( $s );

	if( $target != '' ) {
		$searcher = new LinkSearchPage( $target2, $namespace, $protocol );
		$searcher->doQuery( $offset, $limit );
	}
}

class LinkSearchPage extends QueryPage {

	function __construct( $query, $ns, $prot ) {
		$this->mQuery = $query;
		$this->mNs = $ns;
		$this->mProt = $prot;
	}

	function getName() {
		return 'LinkSearch';
	}

	/**
	 * Disable RSS/Atom feeds
	 */
	function isSyndicated() {
		return false;
	}

	/**
	 * Return an appropriately formatted LIKE query and the clause
	 */
	static function mungeQuery( $query , $prot ) {
		$field = 'el_index';
		$rv = LinkFilter::makeLike( $query , $prot );
		if ($rv === false) {
			//makeLike doesn't handle wildcard in IP, so we'll have to munge here.
			if (preg_match('/^(:?[0-9]{1,3}\.)+\*\s*$|^(:?[0-9]{1,3}\.){3}[0-9]{1,3}:[0-9]*\*\s*$/', $query)) {
				$rv = $prot . rtrim($query, " \t*") . '%';
				$field = 'el_to';
			}
		}
		return array( $rv, $field );
	}

	function linkParameters() {
		global $wgMiserMode;
		$params = array();
		$params['target'] = $this->mProt . $this->mQuery;
		if( isset( $this->mNs ) && !$wgMiserMode ) {
			$params['namespace'] = $this->mNs;
		}
		return $params;
	}

	function getSQL() {
		global $wgMiserMode;
		$dbr = wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		$externallinks = $dbr->tableName( 'externallinks' );

		/* strip everything past first wildcard, so that index-based-only lookup would be done */
		list( $munged, $clause ) = self::mungeQuery( $this->mQuery, $this->mProt );
		$stripped = substr($munged,0,strpos($munged,'%')+1);
		$encSearch = $dbr->addQuotes( $stripped );

		$encSQL = '';
		if ( isset ($this->mNs) && !$wgMiserMode )
			$encSQL = 'AND page_namespace=' . $dbr->addQuotes( $this->mNs );

		$use_index = $dbr->useIndexClause( $clause );
		return
			"SELECT
				page_namespace AS namespace,
				page_title AS title,
				el_index AS value,
				el_to AS url
			FROM
				$page,
				$externallinks $use_index
			WHERE
				page_id=el_from
				AND $clause LIKE $encSearch
				$encSQL";
	}

	function formatResult( $skin, $result ) {
		$title = Title::makeTitle( $result->namespace, $result->title );
		$url = $result->url;
		$pageLink = $skin->makeKnownLinkObj( $title );
		$urlLink = $skin->makeExternalLink( $url, $url );

		return wfMsgHtml( 'linksearch-line', $urlLink, $pageLink );
	}

	/**
	 * Override to check query validity.
	 */
	function doQuery( $offset, $limit, $shownavigation=true ) {
		global $wgOut;
		list( $this->mMungedQuery, $clause ) = LinkSearchPage::mungeQuery( $this->mQuery, $this->mProt );
		if( $this->mMungedQuery === false ) {
			$wgOut->addWikiText( wfMsg( 'linksearch-error' ) );
		} else {
			// For debugging
			// Generates invalid xhtml with patterns that contain --
			//$wgOut->addHtml( "\n<!-- " . htmlspecialchars( $this->mMungedQuery ) . " -->\n" );
			parent::doQuery( $offset, $limit, $shownavigation );
		}
	}

	/**
	 * Override to squash the ORDER BY.
	 * We do a truncated index search, so the optimizer won't trust
	 * it as good enough for optimizing sort. The implicit ordering
	 * from the scan will usually do well enough for our needs.
	 */
	function getOrder() {
		return '';
	}
}
