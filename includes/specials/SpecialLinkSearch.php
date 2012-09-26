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
		global $wgUrlProtocols, $wgMiserMode, $wgScript;

		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->allowClickjacking();

		$request = $this->getRequest();
		$target = $request->getVal( 'target', $par );
		$namespace = $request->getIntorNull( 'namespace', null );

		$protocols_list = array();
		foreach ( $wgUrlProtocols as $prot ) {
			if ( $prot !== '//' ) {
				$protocols_list[] = $prot;
			}
		}

		$target2 = $target;
		$protocol = '';
		$pr_sl = strpos( $target2, '//' );
		$pr_cl = strpos( $target2, ':' );
		if ( $pr_sl ) {
			// For protocols with '//'
			$protocol = substr( $target2, 0, $pr_sl + 2 );
			$target2 = substr( $target2, $pr_sl + 2 );
		} elseif ( !$pr_sl && $pr_cl ) {
			// For protocols without '//' like 'mailto:'
			$protocol = substr( $target2, 0, $pr_cl + 1 );
			$target2 = substr( $target2, $pr_cl + 1 );
		} elseif ( $protocol == '' && $target2 != '' ) {
			// default
			$protocol = 'http://';
		}
		if ( $protocol != '' && !in_array( $protocol, $protocols_list ) ) {
			// unsupported protocol, show original search request
			$target2 = $target;
			$protocol = '';
		}

		$out->addWikiMsg(
			'linksearch-text',
			'<nowiki>' . $this->getLanguage()->commaList( $protocols_list ) . '</nowiki>',
			count( $protocols_list )
		);
		$s = Html::openElement(
			'form',
			array( 'id' => 'mw-linksearch-form', 'method' => 'get', 'action' => $wgScript )
		) . "\n" .
			Html::hidden( 'title', $this->getTitle()->getPrefixedDBkey() ) . "\n" .
			Html::openElement( 'fieldset' ) . "\n" .
			Html::element( 'legend', array(), $this->msg( 'linksearch' )->text() ) . "\n" .
			Xml::inputLabel(
				$this->msg( 'linksearch-pat' )->text(),
				'target',
				'target',
				50,
				$target
			) . "\n";

		if ( !$wgMiserMode ) {
			$s .= Html::namespaceSelector(
				array(
					'selected' => $namespace,
					'all' => '',
					'label' => $this->msg( 'linksearch-ns' )->text()
				), array(
					'name' => 'namespace',
					'id' => 'namespace',
					'class' => 'namespaceselector',
				)
			);
		}

		$s .= Xml::submitButton( $this->msg( 'linksearch-ok' )->text() ) . "\n" .
			Html::closeElement( 'fieldset' ) . "\n" .
			Html::closeElement( 'form' ) . "\n";
		$out->addHTML( $s );

		if ( $target != '' ) {
			$this->setParams( array(
				'query' => $target2,
				'namespace' => $namespace,
				'protocol' => $protocol ) );
			parent::execute( $par );
			if ( $this->mMungedQuery === false ) {
				$out->addWikiMsg( 'linksearch-error' );
			}
		}
	}

	/**
	 * Disable RSS/Atom feeds
	 * @return bool
	 */
	function isSyndicated() {
		return false;
	}

	/**
	 * Return an appropriately formatted LIKE query and the clause
	 *
	 * @param string $query
	 * @param string $prot
	 * @return array
	 */
	static function mungeQuery( $query, $prot ) {
		$field = 'el_index';
		$rv = LinkFilter::makeLikeArray( $query, $prot );
		if ( $rv === false ) {
			// LinkFilter doesn't handle wildcard in IP, so we'll have to munge here.
			$pattern = '/^(:?[0-9]{1,3}\.)+\*\s*$|^(:?[0-9]{1,3}\.){3}[0-9]{1,3}:[0-9]*\*\s*$/';
			if ( preg_match( $pattern, $query ) ) {
				$dbr = wfGetDB( DB_SLAVE );
				$rv = array( $prot . rtrim( $query, " \t*" ), $dbr->anyString() );
				$field = 'el_to';
			}
		}

		return array( $rv, $field );
	}

	function linkParameters() {
		global $wgMiserMode;
		$params = array();
		$params['target'] = $this->mProt . $this->mQuery;
		if ( isset( $this->mNs ) && !$wgMiserMode ) {
			$params['namespace'] = $this->mNs;
		}

		return $params;
	}

	function getQueryInfo() {
		global $wgMiserMode;
		$dbr = wfGetDB( DB_SLAVE );
		// strip everything past first wildcard, so that
		// index-based-only lookup would be done
		list( $this->mMungedQuery, $clause ) = self::mungeQuery( $this->mQuery, $this->mProt );
		if ( $this->mMungedQuery === false ) {
			// Invalid query; return no results
			return array( 'tables' => 'page', 'fields' => 'page_id', 'conds' => '0=1' );
		}

		$stripped = LinkFilter::keepOneWildcard( $this->mMungedQuery );
		$like = $dbr->buildLike( $stripped );
		$retval = array(
			'tables' => array( 'page', 'externallinks' ),
			'fields' => array(
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'el_index',
				'url' => 'el_to'
			),
			'conds' => array(
				'page_id = el_from',
				"$clause $like"
			),
			'options' => array( 'USE INDEX' => $clause )
		);

		if ( isset( $this->mNs ) && !$wgMiserMode ) {
			$retval['conds']['page_namespace'] = $this->mNs;
		}

		return $retval;
	}

	/**
	 * @param Skin $skin
	 * @param object $result Result row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		$title = Title::makeTitle( $result->namespace, $result->title );
		$url = $result->url;
		$pageLink = Linker::linkKnown( $title );
		$urlLink = Linker::makeExternalLink( $url, $url );

		return $this->msg( 'linksearch-line' )->rawParams( $urlLink, $pageLink )->escaped();
	}

	/**
	 * Override to check query validity.
	 */
	function doQuery( $offset = false, $limit = false ) {
		list( $this->mMungedQuery, ) = LinkSearchPage::mungeQuery( $this->mQuery, $this->mProt );
		if ( $this->mMungedQuery === false ) {
			$this->getOutput()->addWikiMsg( 'linksearch-error' );
		} else {
			// For debugging
			// Generates invalid xhtml with patterns that contain --
			//$this->getOutput()->addHTML( "\n<!-- " . htmlspecialchars( $this->mMungedQuery ) . " -->\n" );
			parent::doQuery( $offset, $limit );
		}
	}

	/**
	 * Override to squash the ORDER BY.
	 * We do a truncated index search, so the optimizer won't trust
	 * it as good enough for optimizing sort. The implicit ordering
	 * from the scan will usually do well enough for our needs.
	 * @return array
	 */
	function getOrderFields() {
		return array();
	}

	protected function getGroupName() {
		return 'redirects';
	}
}
