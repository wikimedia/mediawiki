<?php
/**
 * Implements Special:LinkSearch
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SpecialPage
 * @author Brion Vibber
 */
 

/**
 * Special:LinkSearch to search the external-links table.
 * @ingroup SpecialPage
 */
class LinkSearchPage extends QueryPage {
	function setParams( $params ) {
		$this->mQuery = $params['query'];
		$this->mNs = $params['namespace'];
		$this->mProt = $params['protocol'];
	}

	function __construct( $name = 'LinkSearch' ) {
		parent::__construct( $name );
	}
	
	function isCacheable() {
		return false;
	}
	
	function execute( $par ) {
		global $wgOut, $wgRequest, $wgUrlProtocols, $wgMiserMode, $wgLang, $wgScript;
		$this->setHeaders();
		
		$target = $wgRequest->getVal( 'target', $par );
		$namespace = $wgRequest->getIntorNull( 'namespace', null );

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

		$self = $this->getTitle();

		$wgOut->addWikiMsg( 'linksearch-text', '<nowiki>' . $wgLang->commaList( $wgUrlProtocols ) . '</nowiki>' );
		$s = Xml::openElement( 'form', array( 'id' => 'mw-linksearch-form', 'method' => 'get', 'action' => $GLOBALS['wgScript'] ) ) .
			Html::hidden( 'title', $self->getPrefixedDbKey() ) .
			'<fieldset>' .
			Xml::element( 'legend', array(), wfMsg( 'linksearch' ) ) .
			Xml::inputLabel( wfMsg( 'linksearch-pat' ), 'target', 'target', 50, $target ) . ' ';
		if ( !$wgMiserMode ) {
			$s .= Xml::label( wfMsg( 'linksearch-ns' ), 'namespace' ) . ' ' .
				Xml::namespaceSelector( $namespace, '' );
		}
		$s .=	Xml::submitButton( wfMsg( 'linksearch-ok' ) ) .
			'</fieldset>' .
			Xml::closeElement( 'form' );
		$wgOut->addHTML( $s );

		if( $target != '' ) {
			$this->setParams( array( 
				'query' => $target2,
				'namespace' => $namespace,
				'protocol' => $protocol ) );
			parent::execute( $par );
			if( $this->mMungedQuery === false )
				$wgOut->addWikiText( wfMsg( 'linksearch-error' ) );
		}
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
		if ( $rv === false ) {
			// LinkFilter doesn't handle wildcard in IP, so we'll have to munge here.
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

	function getQueryInfo() {
		global $wgMiserMode;
		$dbr = wfGetDB( DB_SLAVE );
		// strip everything past first wildcard, so that
		// index-based-only lookup would be done
		list( $this->mMungedQuery, $clause ) = self::mungeQuery(
				$this->mQuery, $this->mProt );
		if( $this->mMungedQuery === false )
			// Invalid query; return no results
			return array( 'tables' => 'page', 'fields' => 'page_id', 'conds' => '0=1' );
		
		$stripped = substr( $this->mMungedQuery, 0, strpos( $this->mMungedQuery, '%' ) + 1 );
		$encSearch = $dbr->addQuotes( $stripped );
		$retval = array (
			'tables' => array ( 'page', 'externallinks' ),
			'fields' => array ( 'page_namespace AS namespace',
					'page_title AS title',
					'el_index AS value', 'el_to AS url' ),
			'conds' => array ( 'page_id = el_from',
					"$clause LIKE $encSearch" ),
			'options' => array( 'USE INDEX' => $clause )
		);
		if ( isset( $this->mNs ) && !$wgMiserMode ) {
			$retval['conds']['page_namespace'] = $this->mNs;
		}
		return $retval;
	}

	function formatResult( $skin, $result ) {
		$title = Title::makeTitle( $result->namespace, $result->title );
		$url = $result->url;
		$pageLink = $skin->linkKnown( $title );
		$urlLink = $skin->makeExternalLink( $url, $url );

		return wfMsgHtml( 'linksearch-line', $urlLink, $pageLink );
	}

	/**
	 * Override to check query validity.
	 */
	function doQuery( $limit, $offset = false ) {
		global $wgOut;
		list( $this->mMungedQuery,  ) = LinkSearchPage::mungeQuery( $this->mQuery, $this->mProt );
		if( $this->mMungedQuery === false ) {
			$wgOut->addWikiMsg( 'linksearch-error' );
		} else {
			// For debugging
			// Generates invalid xhtml with patterns that contain --
			//$wgOut->addHTML( "\n<!-- " . htmlspecialchars( $this->mMungedQuery ) . " -->\n" );
			parent::doQuery( $limit, $offset );
		}
	}

	/**
	 * Override to squash the ORDER BY.
	 * We do a truncated index search, so the optimizer won't trust
	 * it as good enough for optimizing sort. The implicit ordering
	 * from the scan will usually do well enough for our needs.
	 */
	function getOrderFields() {
		return array();
	}
}
