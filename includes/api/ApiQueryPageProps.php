<?php
/**
 *
 *
 * Created on Aug 7, 2010
 *
 * Copyright © 2010 soxred93, Bryan Tong Minh
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
 */

/**
 * A query module to show basic page information.
 *
 * @ingroup API
 */
class ApiQueryPageProps extends ApiQueryBase {

	private $params;

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'pp' );
	}

	public function execute() {
		# Only operate on existing pages
		$pages = $this->getPageSet()->getGoodTitles();
		if ( !count( $pages ) ) {
			# Nothing to do
			return;
		}

		$this->params = $this->extractRequestParams();

		$this->addTables( 'page_props' );
		$this->addFields( array( 'pp_page', 'pp_propname', 'pp_value' ) );
		$this->addWhereFld( 'pp_page', array_keys( $pages ) );

		if ( $this->params['continue'] ) {
			$this->addWhere( 'pp_page >=' . intval( $this->params['continue'] ) );
		}

		if ( $this->params['prop'] ) {
			$this->addWhereFld( 'pp_propname', $this->params['prop'] );
		}

		# Force a sort order to ensure that properties are grouped by page
		# But only if pp_page is not constant in the WHERE clause.
		if ( count( $pages ) > 1 ) {
			$this->addOption( 'ORDER BY', 'pp_page' );
		}

		$res = $this->select( __METHOD__ );
		$currentPage = 0; # Id of the page currently processed
		$props = array();
		$result = $this->getResult();

		foreach ( $res as $row ) {
			if ( $currentPage != $row->pp_page ) {
				# Different page than previous row, so add the properties to
				# the result and save the new page id

				if ( $currentPage ) {
					if ( !$this->addPageProps( $result, $currentPage, $props ) ) {
						# addPageProps() indicated that the result did not fit
						# so stop adding data. Reset props so that it doesn't
						# get added again after loop exit

						$props = array();
						break;
					}

					$props = array();
				}

				$currentPage = $row->pp_page;
			}

			$props[$row->pp_propname] = $row->pp_value;
		}

		if ( count( $props ) ) {
			# Add any remaining properties to the results
			$this->addPageProps( $result, $currentPage, $props );
		}
	}

	/**
	 * Add page properties to an ApiResult, adding a continue
	 * parameter if it doesn't fit.
	 *
	 * @param ApiResult $result
	 * @param int $page
	 * @param array $props
	 * @return bool True if it fits in the result
	 */
	private function addPageProps( $result, $page, $props ) {
		ApiResult::setArrayType( $props, 'assoc' );
		$fit = $result->addValue( array( 'query', 'pages', $page ), 'pageprops', $props );

		if ( !$fit ) {
			$this->setContinueEnumParameter( 'continue', $page );
		}

		return $fit;
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return array(
			'continue' => array(
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			),
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
			),
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&prop=pageprops&titles=Main%20Page|MediaWiki'
				=> 'apihelp-query+pageprops-example-simple',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Pageprops';
	}
}
