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

use Wikimedia\Rdbms\ResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * Special:LinkSearch to search the external-links table.
 * @ingroup SpecialPage
 */
class LinkSearchPage extends QueryPage {
	/** @var array|bool */
	private $mungedQuery = false;

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

	function isCacheable() {
		return false;
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->allowClickjacking();

		$request = $this->getRequest();
		$target = $request->getVal( 'target', $par );
		$namespace = $request->getIntOrNull( 'namespace' );

		$protocols_list = [];
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
		$fields = [
			'target' => [
				'type' => 'text',
				'name' => 'target',
				'id' => 'target',
				'size' => 50,
				'label-message' => 'linksearch-pat',
				'default' => $target,
				'dir' => 'ltr',
			]
		];
		if ( !$this->getConfig()->get( 'MiserMode' ) ) {
			$fields += [
				'namespace' => [
					'type' => 'namespaceselect',
					'name' => 'namespace',
					'label-message' => 'linksearch-ns',
					'default' => $namespace,
					'id' => 'namespace',
					'all' => '',
					'cssclass' => 'namespaceselector',
				],
			];
		}
		$hiddenFields = [
			'title' => $this->getPageTitle()->getPrefixedDBkey(),
		];
		$htmlForm = HTMLForm::factory( 'ooui', $fields, $this->getContext() );
		$htmlForm->addHiddenFields( $hiddenFields );
		$htmlForm->setSubmitTextMsg( 'linksearch-ok' );
		$htmlForm->setWrapperLegendMsg( 'linksearch' );
		$htmlForm->setAction( wfScript() );
		$htmlForm->setMethod( 'get' );
		$htmlForm->prepareForm()->displayForm( false );
		$this->addHelpLink( 'Help:Linksearch' );

		if ( $target != '' ) {
			$this->setParams( [
				'query' => Parser::normalizeLinkUrl( $target2 ),
				'namespace' => $namespace,
				'protocol' => $protocol ] );
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
		$dbr = wfGetDB( DB_REPLICA );

		if ( $query === '*' && $prot !== '' ) {
			// Allow queries like 'ftp://*' to find all ftp links
			$rv = [ $prot, $dbr->anyString() ];
		} else {
			$rv = LinkFilter::makeLikeArray( $query, $prot );
		}

		if ( $rv === false ) {
			// LinkFilter doesn't handle wildcard in IP, so we'll have to munge here.
			$pattern = '/^(:?[0-9]{1,3}\.)+\*\s*$|^(:?[0-9]{1,3}\.){3}[0-9]{1,3}:[0-9]*\*\s*$/';
			if ( preg_match( $pattern, $query ) ) {
				$rv = [ $prot . rtrim( $query, " \t*" ), $dbr->anyString() ];
				$field = 'el_to';
			}
		}

		return [ $rv, $field ];
	}

	function linkParameters() {
		$params = [];
		$params['target'] = $this->mProt . $this->mQuery;
		if ( $this->mNs !== null && !$this->getConfig()->get( 'MiserMode' ) ) {
			$params['namespace'] = $this->mNs;
		}

		return $params;
	}

	public function getQueryInfo() {
		$dbr = wfGetDB( DB_REPLICA );
		// strip everything past first wildcard, so that
		// index-based-only lookup would be done
		list( $this->mungedQuery, $clause ) = self::mungeQuery( $this->mQuery, $this->mProt );
		if ( $this->mungedQuery === false ) {
			// Invalid query; return no results
			return [ 'tables' => 'page', 'fields' => 'page_id', 'conds' => '0=1' ];
		}

		$stripped = LinkFilter::keepOneWildcard( $this->mungedQuery );
		$like = $dbr->buildLike( $stripped );
		$retval = [
			'tables' => [ 'page', 'externallinks' ],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'el_index',
				'url' => 'el_to'
			],
			'conds' => [
				'page_id = el_from',
				"$clause $like"
			],
			'options' => [ 'USE INDEX' => $clause ]
		];

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
		$this->executeLBFromResultWrapper( $res );
	}

	/**
	 * @param Skin $skin
	 * @param object $result Result row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		$title = new TitleValue( (int)$result->namespace, $result->title );
		$pageLink = $this->getLinkRenderer()->makeLink( $title );

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
		return [];
	}

	protected function getGroupName() {
		return 'redirects';
	}

	/**
	 * enwiki complained about low limits on this special page
	 *
	 * @see T130058
	 * @todo FIXME This special page should not use LIMIT for paging
	 */
	protected function getMaxResults() {
		return max( parent::getMaxResults(), 60000 );
	}
}
