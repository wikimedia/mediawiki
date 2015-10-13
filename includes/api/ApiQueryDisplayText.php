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
 */

/**
 * Query module to get display text for a page. Default display text
 * is Title::getPrefixedText, unless there is a displaytitle or
 * other display text set via a hook.
 *
 * @ingroup API
 */
class ApiQueryDisplayText extends ApiQueryBase {

	/**
	 * @var array
	 */
	private $params;

	/**
	 * @param ApiQuery $query
	 * @param string $moduleName
	 */
	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'dt' );
	}

	public function execute() {
		$this->params = $this->extractRequestParams();

		$titles = $this->getPageSet()->getGoodTitles();

		if ( !count( $titles ) ) {
			return;
		}

		$result = $this->getResult();
		$displayTexts = $this->getDisplayTexts( $titles );

		foreach ( $titles as $pageId => $title ) {
			if ( !isset( $displayTexts[$pageId] ) ) {
				throw new RuntimeException( '$displayTexts not set for $pageId ' . $pageId );
			}

			$result->addValue(
				array( 'query', 'pages', $pageId ),
				'displaytext',
				$displayTexts[$pageId]
			);
		}
	}

	/**
	 * @param Title[] $titles
	 *
	 * @return array page_id -> display text
	 */
	private function getDisplayTexts( array $titles ) {
		$displayTexts = $this->getDisplayTitlePageProps( $titles );

		Hooks::run(
			'ApiQueryDisplayText',
			array( &$displayTexts, $titles, $this->getLanguage()->getCode() )
		);

		foreach ( $titles as $pageId => $title ) {
			if ( !isset( $displayTexts[$pageId] ) ) {
				$displayTexts[$pageId] = $title->getPrefixedText();
			}
		}

		return $displayTexts;
	}

	/**
	 * @param Title[] $titles
	 *
	 * @return array page_id -> display title value
	 */
	private function getDisplayTitlePageProps( array $titles ) {
		$this->addTables( 'page_props' );
		$this->addFields( array( 'pp_page', 'pp_propname', 'pp_value' ) );
		$this->addWhereFld( 'pp_page', array_keys( $titles ) );

		if ( $this->params['continue'] ) {
			$this->addWhere( 'pp_page >=' . intval( $this->params['continue'] ) );
		}

		$this->addWhereFld( 'pp_propname', 'displaytitle' );

		$res = $this->select( __METHOD__ );

		$displayTitles = array();

		foreach ( $res as $row ) {
			$displayTitles[$row->pp_page] = $row->pp_value;
		}

		return $displayTitles;
	}

	public function getAllowedParams() {
		return array(
			'continue' => array(
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			),
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&prop=displaytext&titles=Main%20Page'
				=> 'apihelp-query+displaytext-example-simple',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Displaytext';
	}

}
