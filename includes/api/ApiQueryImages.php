<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 * This query adds an "<images>" subelement to all pages with the list of
 * images embedded into those pages.
 *
 * @ingroup API
 */
class ApiQueryImages extends ApiQueryGeneratorBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'im' );
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
		if ( $this->getPageSet()->getGoodTitleCount() == 0 ) {
			return; // nothing to do
		}

		$params = $this->extractRequestParams();
		$this->addFields( [
			'il_from',
			'il_to'
		] );

		$this->addTables( 'imagelinks' );
		$this->addWhereFld( 'il_from', array_keys( $this->getPageSet()->getGoodTitles() ) );
		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 2 );
			$op = $params['dir'] == 'descending' ? '<' : '>';
			$ilfrom = intval( $cont[0] );
			$ilto = $this->getDB()->addQuotes( $cont[1] );
			$this->addWhere(
				"il_from $op $ilfrom OR " .
				"(il_from = $ilfrom AND " .
				"il_to $op= $ilto)"
			);
		}

		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		// Don't order by il_from if it's constant in the WHERE clause
		if ( count( $this->getPageSet()->getGoodTitles() ) == 1 ) {
			$this->addOption( 'ORDER BY', 'il_to' . $sort );
		} else {
			$this->addOption( 'ORDER BY', [
				'il_from' . $sort,
				'il_to' . $sort
			] );
		}
		$this->addOption( 'LIMIT', $params['limit'] + 1 );

		if ( $params['images'] ) {
			$images = [];
			foreach ( $params['images'] as $img ) {
				$title = Title::newFromText( $img );
				if ( !$title || $title->getNamespace() != NS_FILE ) {
					$this->addWarning( [ 'apiwarn-notfile', wfEscapeWikiText( $img ) ] );
				} else {
					$images[] = $title->getDBkey();
				}
			}
			if ( !$images ) {
				// No titles so no results
				return;
			}
			$this->addWhereFld( 'il_to', $images );
		}

		$res = $this->select( __METHOD__ );

		if ( is_null( $resultPageSet ) ) {
			$count = 0;
			foreach ( $res as $row ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue', $row->il_from . '|' . $row->il_to );
					break;
				}
				$vals = [];
				ApiQueryBase::addTitleInfo( $vals, Title::makeTitle( NS_FILE, $row->il_to ) );
				$fit = $this->addPageSubItem( $row->il_from, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', $row->il_from . '|' . $row->il_to );
					break;
				}
			}
		} else {
			$titles = [];
			$count = 0;
			foreach ( $res as $row ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue', $row->il_from . '|' . $row->il_to );
					break;
				}
				$titles[] = Title::makeTitle( NS_FILE, $row->il_to );
			}
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return [
			'limit' => [
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'images' => [
				ApiBase::PARAM_ISMULTI => true,
			],
			'dir' => [
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => [
					'ascending',
					'descending'
				]
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&prop=images&titles=Main%20Page'
				=> 'apihelp-query+images-example-simple',
			'action=query&generator=images&titles=Main%20Page&prop=info'
				=> 'apihelp-query+images-example-generator',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Images';
	}
}
