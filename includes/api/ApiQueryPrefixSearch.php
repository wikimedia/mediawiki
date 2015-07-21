<?php
/**
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
 * @since 1.23
 */

/**
 * @ingroup API
 */
class ApiQueryPrefixSearch extends ApiQueryGeneratorBase {
	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'ps' );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet $resultPageSet
	 */
	private function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();
		$search = $params['search'];
		$limit = $params['limit'];
		$namespaces = $params['namespace'];
		$offset = $params['offset'];

		$searcher = new TitlePrefixSearch;
		$titles = $searcher->searchWithVariants( $search, $limit + 1, $namespaces, $offset );
		if ( $resultPageSet ) {
			if ( count( $titles ) > $limit ) {
				$this->setContinueEnumParameter( 'offset', $offset + $params['limit'] );
				array_pop( $titles );
			}
			$resultPageSet->populateFromTitles( $titles );
			foreach ( $titles as $index => $title ) {
				$resultPageSet->setGeneratorData( $title, array( 'index' => $index + $offset + 1 ) );
			}
		} else {
			$result = $this->getResult();
			$count = 0;
			foreach ( $titles as $title ) {
				if ( ++$count > $limit ) {
					$this->setContinueEnumParameter( 'offset', $offset + $params['limit'] );
					break;
				}
				$vals = array(
					'ns' => intval( $title->getNamespace() ),
					'title' => $title->getPrefixedText(),
				);
				if ( $title->isSpecialPage() ) {
					$vals['special'] = true;
				} else {
					$vals['pageid'] = intval( $title->getArticleId() );
				}
				$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'offset', $offset + $count - 1 );
					break;
				}
			}
			$result->addIndexedTagName(
				array( 'query', $this->getModuleName() ), $this->getModulePrefix()
			);
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
			return array(
				'search' => array(
					ApiBase::PARAM_TYPE => 'string',
					ApiBase::PARAM_REQUIRED => true,
				),
				'namespace' => array(
					ApiBase::PARAM_DFLT => NS_MAIN,
					ApiBase::PARAM_TYPE => 'namespace',
					ApiBase::PARAM_ISMULTI => true,
				),
				'limit' => array(
					ApiBase::PARAM_DFLT => 10,
					ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MIN => 1,
					// Non-standard value for compatibility with action=opensearch
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_MAX2 => 200,
				),
				'offset' => array(
					ApiBase::PARAM_DFLT => 0,
					ApiBase::PARAM_TYPE => 'integer',
				),
			);
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&list=prefixsearch&pssearch=meaning'
				=> 'apihelp-query+prefixsearch-example-simple',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Prefixsearch';
	}
}
