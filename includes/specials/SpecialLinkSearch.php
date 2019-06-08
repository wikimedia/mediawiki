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

use Wikimedia\Rdbms\IResultWrapper;
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

		$target2 = Parser::normalizeLinkUrl( $target );
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
				'query' => $target2,
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

		if ( $this->mQuery === '*' && $this->mProt !== '' ) {
			$this->mungedQuery = [
				'el_index_60' . $dbr->buildLike( $this->mProt, $dbr->anyString() ),
			];
		} else {
			$this->mungedQuery = LinkFilter::getQueryConditions( $this->mQuery, [
				'protocol' => $this->mProt,
				'oneWildcard' => true,
				'db' => $dbr
			] );
		}
		if ( $this->mungedQuery === false ) {
			// Invalid query; return no results
			return [ 'tables' => 'page', 'fields' => 'page_id', 'conds' => '0=1' ];
		}

		$orderBy = [];
		if ( !isset( $this->mungedQuery['el_index_60'] ) ) {
			$orderBy[] = 'el_index_60';
		}
		$orderBy[] = 'el_id';

		$retval = [
			'tables' => [ 'page', 'externallinks' ],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'el_index',
				'url' => 'el_to'
			],
			'conds' => array_merge(
				[
					'page_id = el_from',
				],
				$this->mungedQuery
			),
			'options' => [ 'ORDER BY' => $orderBy ]
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
	 * @param IResultWrapper $res
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
	 * Not much point in descending order here.
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
	 * @return int
	 */
	protected function getMaxResults() {
		return max( parent::getMaxResults(), 60000 );
	}
}
