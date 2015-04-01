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
	/** @var array|bool */
	private $mungedQuery = false;

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
	 * @todo query logic and rendering logic should be split and also injected
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
		global $wgLanguageCode;
		if ( !$this->linkRenderer ) {
			$lang = Language::factory( $wgLanguageCode );
			$titleFormatter = new MediaWikiTitleCodec( $lang, GenderCache::singleton() );
			$this->linkRenderer = new MediaWikiPageLinkRenderer( $titleFormatter );
		}
	}

	function isCacheable() {
		return false;
	}

	function execute( $par ) {
		$this->initServices();

		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->allowClickjacking();

		$request = $this->getRequest();
		$target = $request->getVal( 'target', $par );
		$namespace = $request->getIntOrNull( 'namespace', null );

		$protocols_list = array();
		foreach ( $this->getConfig()->get( 'UrlProtocols' ) as $prot ) {
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
			array( 'id' => 'mw-linksearch-form', 'method' => 'get', 'action' => wfScript() )
		) . "\n" .
			Html::hidden( 'title', $this->getPageTitle()->getPrefixedDBkey() ) . "\n" .
			Html::openElement( 'fieldset' ) . "\n" .
			Html::element( 'legend', array(), $this->msg( 'linksearch' )->text() ) . "\n" .
			Xml::inputLabel(
				$this->msg( 'linksearch-pat' )->text(),
				'target',
				'target',
				50,
				$target,
				array(
					// URLs are always ltr
					'dir' => 'ltr',
				)
			) . "\n";

		if ( !$this->getConfig()->get( 'MiserMode' ) ) {
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
			if ( $this->mungedQuery === false ) {
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
	 * @param string $query Search pattern to search for
	 * @param string $prot Protocol, e.g. 'http://'
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
		$params = array();
		$params['target'] = $this->mProt . $this->mQuery;
		if ( $this->mNs !== null && !$this->getConfig()->get( 'MiserMode' ) ) {
			$params['namespace'] = $this->mNs;
		}

		return $params;
	}

	function getQueryInfo() {
		$dbr = wfGetDB( DB_SLAVE );
		// strip everything past first wildcard, so that
		// index-based-only lookup would be done
		list( $this->mungedQuery, $clause ) = self::mungeQuery( $this->mQuery, $this->mProt );
		if ( $this->mungedQuery === false ) {
			// Invalid query; return no results
			return array( 'tables' => 'page', 'fields' => 'page_id', 'conds' => '0=1' );
		}

		$stripped = LinkFilter::keepOneWildcard( $this->mungedQuery );
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

		if ( $this->mNs !== null && !$this->getConfig()->get( 'MiserMode' ) ) {
			$retval['conds']['page_namespace'] = $this->mNs;
		}

		return $retval;
	}

	/**
	 * Pre-fill the link cache
	 *
	 * @param IDatabase $db
	 * @param ResultWrapper $res
	 */
	function preprocessResults( $db, $res ) {
		if ( $res->numRows() > 0 ) {
			$linkBatch = new LinkBatch();

			foreach ( $res as $row ) {
				$linkBatch->add( $row->namespace, $row->title );
			}

			$res->seek( 0 );
			$linkBatch->execute();
		}
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
