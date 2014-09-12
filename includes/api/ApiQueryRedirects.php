<?php
/**
 * API module to return redirects to a page
 *
 * Created on Dec 30, 2013
 *
 * Copyright © 2013 Brad Jorsch <bjorsch@wikimedia.org>
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
 * @since 1.23
 */

/**
 * This query lists redirects to the given pages.
 *
 * @ingroup API
 */
class ApiQueryRedirects extends ApiQueryGeneratorBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'rd' );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param $resultPageSet ApiPageSet
	 */
	private function run( ApiPageSet $resultPageSet = null ) {
		$db = $this->getDB();
		$params = $this->extractRequestParams();
		$emptyString = $db->addQuotes( '' );

		$pageSet = $this->getPageSet();
		$titles = $pageSet->getGoodTitles() + $pageSet->getMissingTitles();

		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 3 );
			$rd_namespace = (int)$cont[0];
			$this->dieContinueUsageIf( $rd_namespace != $cont[0] );
			$rd_title = $db->addQuotes( $cont[1] );
			$rd_from = (int)$cont[2];
			$this->dieContinueUsageIf( $rd_from != $cont[2] );
			$this->addWhere(
				"rd_namespace > $rd_namespace OR " .
				"(rd_namespace = $rd_namespace AND " .
				"(rd_title > $rd_title OR " .
				"(rd_title = $rd_title AND " .
				"rd_from >= $rd_from)))"
			);

			// Remove titles that we're past already
			$titles = array_filter( $titles, function ( $t ) use ( $rd_namespace, $rd_title ) {
				$ns = $t->getNamespace();
				return ( $ns > $rd_namespace ||
					$ns == $rd_namespace && $t->getDBKey() >= $rd_title
				);
			} );
		}

		if ( !$titles ) {
			return; // nothing to do
		}

		$this->addTables( array( 'redirect', 'page' ) );
		$this->addFields( array(
			'rd_from',
			'rd_namespace',
			'rd_title',
		) );

		if ( is_null( $resultPageSet ) ) {
			$prop = array_flip( $params['prop'] );
			$fld_pageid = isset( $prop['pageid'] );
			$fld_title = isset( $prop['title'] );
			$fld_fragment = isset( $prop['fragment'] );

			$this->addFieldsIf( 'rd_fragment', $fld_fragment );
			$this->addFieldsIf( array( 'page_namespace', 'page_title' ), $fld_title );
		} else {
			$this->addFields( array( 'page_namespace', 'page_title' ) );
		}

		$lb = new LinkBatch( $titles );
		$this->addWhere( array(
			'rd_from = page_id',
			"rd_interwiki = $emptyString OR rd_interwiki IS NULL",
			$lb->constructSet( 'rd', $db ),
		) );

		if ( $params['show'] !== null ) {
			$show = array_flip( $params['show'] );
			if ( isset( $show['fragment'] ) && isset( $show['!fragment'] ) ) {
				$this->dieUsageMsg( 'show' );
			}
			$this->addWhereIf( "rd_fragment != $emptyString", isset( $show['fragment'] ) );
			$this->addWhereIf( "rd_fragment = $emptyString OR rd_fragment IS NULL", isset( $show['!fragment'] ) );
		}

		$map = $pageSet->getAllTitlesByNamespace();

		// Why, MySQL? Why do you do this to us?
		$sortby = array();
		if ( count( $map ) > 1 ) {
			$sortby[] = 'rd_namespace';
		}
		$theTitle = null;
		foreach ( $map as $nsTitles ) {
			reset( $nsTitles );
			$key = key( $nsTitles );
			if ( $theTitle === null ) {
				$theTitle = $key;
			}
			if ( count( $nsTitles ) > 1 || $key !== $theTitle ) {
				$sortby[] = 'rd_title';
				break;
			}
		}
		$sortby[] = 'rd_from';
		$this->addOption( 'ORDER BY', $sortby );

		$this->addOption( 'LIMIT', $params['limit'] + 1 );

		$res = $this->select( __METHOD__ );

		if ( is_null( $resultPageSet ) ) {
			$count = 0;
			foreach ( $res as $row ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue',
						"$row->rd_namespace|$row->rd_title|$row->rd_from"
					);
					break;
				}

				# Get the ID of the current page
				$id = $map[$row->rd_namespace][$row->rd_title];

				$vals = array();
				if ( $fld_pageid ) {
					$vals['pageid'] = $row->rd_from;
				}
				if ( $fld_title ) {
					ApiQueryBase::addTitleInfo( $vals,
						Title::makeTitle( $row->page_namespace, $row->page_title )
					);
				}
				if ( $fld_fragment && $row->rd_fragment !== null && $row->rd_fragment !== '' ) {
					$vals['fragment'] = $row->rd_fragment;
				}
				$fit = $this->addPageSubItem( $id, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue',
						"$row->rd_namespace|$row->rd_title|$row->rd_from"
					);
					break;
				}
			}
		} else {
			$titles = array();
			$count = 0;
			foreach ( $res as $row ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue',
						"$row->rd_namespace|$row->rd_title|$row->rd_from"
					);
					break;
				}
				$titles[] = Title::makeTitle( $row->page_namespace, $row->page_title );
			}
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return array(
			'prop' => array(
				ApiBase::PARAM_TYPE => array(
					'pageid',
					'title',
					'fragment',
				),
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'pageid|title',
			),
			'show' => array(
				ApiBase::PARAM_TYPE => array(
					'fragment', '!fragment',
				),
				ApiBase::PARAM_ISMULTI => true,
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'continue' => null,
		);
	}

	public function getParamDescription() {
		return array(
			'prop' => array(
				'Which properties to get:',
				' pageid   - Page id of each redirect',
				' title    - Title of each redirect',
				' fragment - Fragment of each redirect, if any',
			),
			'show' => array(
				'Show only items that meet this criteria.',
				' fragment  - Only show redirects with a fragment',
				' !fragment - Only show redirects without a fragment',
			),
			'limit' => 'How many redirects to return',
			'continue' => 'When more results are available, use this to continue',
		);
	}

	public function getDescription() {
		return 'Returns all redirects to the given page(s).';
	}

	public function getExamples() {
		return array(
			'api.php?action=query&prop=redirects&titles=Main%20Page'
				=> 'Get a list of redirects to the [[Main Page]]',
			'api.php?action=query&generator=redirects&titles=Main%20Page&prop=info'
				=> 'Get information about all redirects to the [[Main Page]]',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Properties#redirects_.2F_rd';
	}
}
