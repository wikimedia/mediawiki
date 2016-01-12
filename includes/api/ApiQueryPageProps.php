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

		$pageProps = PageProps::getInstance();

		$this->params = $this->extractRequestParams();

		if ( $this->params['continue'] ) {
			$continueValue = intval( $this->params['continue'] );
		} else {
			$continueValue = null;
		}

		$props = array();
		$result = $this->getResult();
		if ( $this->params['prop'] ) {
			$propnames = $this->params['prop'];
			$properties = array();
			foreach ( $propnames as $propname ) {
				$values = $pageProps->getProperty( $pages, $propname, $continueValue );
				foreach ( $values as $page => $value ) {
					if ( !isset( $properties[$page] ) ) {
						$properties[$page] = array();
					}
					$properties[$page][$propname] = $value;
				}
			}
		} else {
			$properties = $pageProps->getProperties( $pages, $continueValue );
		}
		foreach ( $properties as $page => $props ) {
			$this->addPageProps( $result, $page, $props );
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
