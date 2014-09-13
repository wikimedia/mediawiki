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

	/**
	 * @var PageLinkRenderer
	 */
	protected $linkRenderer = null;

	function setParams( $params ) {
		$this->mQuery = $params['query'];
		$this->mNs = $params['namespace'];
		$this->mProt = $params['protocol'];
	}

	function __construct( $name = 'LinkSearch' ) {
		parent::__construct( $name );

		// Since we don't control the constructor parameters, we can't inject services that way.
		// Instead, we initialize services in the execute() method, and allow them to be overridden
		// using the setServices() method.
	}

	/**
	 * Initialize or override the PageLinkRenderer LinkSearchPage collaborates with.
	 * Useful mainly for testing.
	 *
	 * @todo: query logic and rendering logic should be split and also injected
	 *
	 * @param PageLinkRenderer $linkRenderer
	 */
	public function setPageLinkRenderer(
		PageLinkRenderer $linkRenderer
	) {
		$this->linkRenderer = $linkRenderer;
	}

	/**
	 * Initialize any services we'll need (unless it has already been provided via a setter).
	 * This allows for dependency injection even though we don't control object creation.
	 */
	private function initServices() {
		if ( !$this->linkRenderer ) {
			$lang = $this->getContext()->getLanguage();
			$titleFormatter = new MediaWikiTitleCodec( $lang, GenderCache::singleton() );
			$this->linkRenderer = new MediaWikiPageLinkRenderer( $titleFormatter );
		}
	}

	function isCacheable() {
		return false;
	}

	function execute( $par ) {
		global $wgUrlProtocols, $wgMiserMode, $wgScript;

		$this->initServices();

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
		// Get protocol, default is http://
		$protocol = 'http://';
		$bits = wfParseUrl( $target );
		if ( isset( $bits['scheme'] ) && isset( $bits['delimiter'] ) ) {
			$protocol = $bits['scheme'] . $bits['delimiter'];
			// Make sure wfParseUrl() didn't make some well-intended correction in the
			// protocol
			if ( strcasecmp( $protocol, substr( $target, 0, strlen( $protocol ) ) ) === 0 ) {
				$target2 = substr( $target, strlen( $protocol ) );
			} else {
				// If it did, let LinkFilter::makeLikeArray() handle this
				$protocol = '';
			}
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
			Html::hidden( 'title', $this->getPageTitle()->getPrefixedDBkey() ) . "\n" .
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
	 * @param String $query Search pattern to search for
	 * @param String $prot Protocol, e.g. 'http://'
	 *
	 * @return array
	 */
	static function mungeQuery( $query, $prot ) {
		$field = 'el_index';
		$dbr = wfGetDB( DB_SLAVE );

		if ( $query === '*' && $prot !== '' ) {
			// Allow queries like 'ftp://*' to find all ftp links
			$rv = array( $prot, $dbr->anyString() );
		} else {
			$rv = LinkFilter::makeLikeArray( $query, $prot );
		}

		if ( $rv === false ) {
			// LinkFilter doesn't handle wildcard in IP, so we'll have to munge here.
			$pattern = '/^(:?[0-9]{1,3}\.)+\*\s*$|^(:?[0-9]{1,3}\.){3}[0-9]{1,3}:[0-9]*\*\s*$/';
			if ( preg_match( $pattern, $query ) ) {
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
		$title = new TitleValue( (int)$result->namespace, $result->title );
		$pageLink = $this->linkRenderer->renderHtmlLink( $title );

		$url = $result->url;
		$urlLink = Linker::makeExternalLink( $url, $url );

		return $this->msg( 'linksearch-line' )->rawParams( $urlLink, $pageLink )->escaped();
	}

	/**
	 * Override to check query validity.
	 *
	 * @param mixed $offset Numerical offset or false for no offset
	 * @param mixed $limit Numerical limit or false for no limit
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
