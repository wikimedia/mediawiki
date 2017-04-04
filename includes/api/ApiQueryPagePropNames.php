<?php
/**
 * Created on January 21, 2013
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
 * @since 1.21
 * @author Brad Jorsch
 */

/**
 * A query module to list used page props
 *
 * @ingroup API
 * @since 1.21
 */
class ApiQueryPagePropNames extends ApiQueryBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'ppn' );
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$this->addTables( 'page_props' );
		$this->addFields( 'pp_propname' );
		$this->addOption( 'DISTINCT' );
		$this->addOption( 'ORDER BY', 'pp_propname' );

		if ( $params['continue'] ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 1 );

			// Add a WHERE clause
			$this->addWhereRange( 'pp_propname', 'newer', $cont[0], null );
		}

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );

		$result = $this->getResult();
		$count = 0;
		foreach ( $this->select( __METHOD__ ) as $row ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', $row->pp_propname );
				break;
			}

			$vals = [];
			$vals['propname'] = $row->pp_propname;
			$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $vals );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', $row->pp_propname );
				break;
			}
		}

		$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'p' );
	}

	public function getAllowedParams() {
		return [
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'limit' => [
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=pagepropnames'
				=> 'apihelp-query+pagepropnames-example-simple',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Pagepropnames';
	}
}
