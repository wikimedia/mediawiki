<?php
/**
 * Created on December 31, 2012
 *
 * Copyright © 2012 Brad Jorsch <bjorsch@wikimedia.org>
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
 * @since 1.21
 * @author Brad Jorsch
 */

/**
 * A query module to enumerate pages that use a particular prop
 *
 * @ingroup API
 * @since 1.21
 */
class ApiQueryPagesWithProp extends ApiQueryGeneratorBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'pwp' );
	}

	public function execute() {
		$this->run();
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet $resultPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		$prop = array_flip( $params['prop'] );
		$fld_ids = isset( $prop['ids'] );
		$fld_title = isset( $prop['title'] );
		$fld_value = isset( $prop['value'] );

		if ( $resultPageSet === null ) {
			$this->addFields( array( 'page_id' ) );
			$this->addFieldsIf( array( 'page_title', 'page_namespace' ), $fld_title );
			$this->addFieldsIf( 'pp_value', $fld_value );
		} else {
			$this->addFields( $resultPageSet->getPageTableFields() );
		}
		$this->addTables( array( 'page_props', 'page' ) );
		$this->addWhere( 'pp_page=page_id' );
		$this->addWhereFld( 'pp_propname', $params['propname'] );

		$dir = ( $params['dir'] == 'ascending' ) ? 'newer' : 'older';

		if ( $params['continue'] ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 1 );

			// Add a WHERE clause
			$from = (int)$cont[0];
			$this->addWhereRange( 'pp_page', $dir, $from, null );
		}

		$sort = ( $params['dir'] === 'descending' ? ' DESC' : '' );
		$this->addOption( 'ORDER BY', 'pp_page' . $sort );

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );

		$result = $this->getResult();
		$count = 0;
		foreach ( $this->select( __METHOD__ ) as $row ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', $row->page_id );
				break;
			}

			if ( $resultPageSet === null ) {
				$vals = array(
					ApiResult::META_TYPE => 'assoc',
				);
				if ( $fld_ids ) {
					$vals['pageid'] = (int)$row->page_id;
				}
				if ( $fld_title ) {
					$title = Title::makeTitle( $row->page_namespace, $row->page_title );
					ApiQueryBase::addTitleInfo( $vals, $title );
				}
				if ( $fld_value ) {
					$vals['value'] = $row->pp_value;
				}
				$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', $row->page_id );
					break;
				}
			} else {
				$resultPageSet->processDbRow( $row );
			}
		}

		if ( $resultPageSet === null ) {
			$result->addIndexedTagName( array( 'query', $this->getModuleName() ), 'page' );
		}
	}

	public function getAllowedParams() {
		return array(
			'propname' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
			'prop' => array(
				ApiBase::PARAM_DFLT => 'ids|title',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array(
					'ids',
					'title',
					'value',
				)
			),
			'continue' => array(
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			),
			'limit' => array(
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'dir' => array(
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => array(
					'ascending',
					'descending',
				)
			),
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&list=pageswithprop&pwppropname=displaytitle&pwpprop=ids|title|value'
				=> 'apihelp-query+pageswithprop-example-simple',
			'action=query&generator=pageswithprop&gpwppropname=notoc&prop=info'
				=> 'apihelp-query+pageswithprop-example-generator',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Pageswithprop';
	}
}
