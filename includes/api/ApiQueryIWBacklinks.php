<?php
/**
 * API for MediaWiki 1.17+
 *
 * Created on May 14, 2010
 *
 * Copyright © 2010 Sam Reed
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
 * This gives links pointing to the given interwiki
 * @ingroup API
 */
class ApiQueryIWBacklinks extends ApiQueryGeneratorBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'iwbl' );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet $resultPageSet
	 * @return void
	 */
	public function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		if ( isset( $params['title'] ) && !isset( $params['prefix'] ) ) {
			$this->dieWithError(
				[
					'apierror-invalidparammix-mustusewith',
					$this->encodeParamName( 'title' ),
					$this->encodeParamName( 'prefix' ),
				],
				'invalidparammix'
			);
		}

		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 3 );

			$db = $this->getDB();
			$op = $params['dir'] == 'descending' ? '<' : '>';
			$prefix = $db->addQuotes( $cont[0] );
			$title = $db->addQuotes( $cont[1] );
			$from = intval( $cont[2] );
			$this->addWhere(
				"iwl_prefix $op $prefix OR " .
				"(iwl_prefix = $prefix AND " .
				"(iwl_title $op $title OR " .
				"(iwl_title = $title AND " .
				"iwl_from $op= $from)))"
			);
		}

		$prop = array_flip( $params['prop'] );
		$iwprefix = isset( $prop['iwprefix'] );
		$iwtitle = isset( $prop['iwtitle'] );

		$this->addTables( [ 'iwlinks', 'page' ] );
		$this->addWhere( 'iwl_from = page_id' );

		$this->addFields( [ 'page_id', 'page_title', 'page_namespace', 'page_is_redirect',
			'iwl_from', 'iwl_prefix', 'iwl_title' ] );

		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		if ( isset( $params['prefix'] ) ) {
			$this->addWhereFld( 'iwl_prefix', $params['prefix'] );
			if ( isset( $params['title'] ) ) {
				$this->addWhereFld( 'iwl_title', $params['title'] );
				$this->addOption( 'ORDER BY', 'iwl_from' . $sort );
			} else {
				$this->addOption( 'ORDER BY', [
					'iwl_title' . $sort,
					'iwl_from' . $sort
				] );
			}
		} else {
			$this->addOption( 'ORDER BY', [
				'iwl_prefix' . $sort,
				'iwl_title' . $sort,
				'iwl_from' . $sort
			] );
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );

		$res = $this->select( __METHOD__ );

		$pages = [];

		$count = 0;
		$result = $this->getResult();
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				// Continue string preserved in case the redirect query doesn't
				// pass the limit
				$this->setContinueEnumParameter(
					'continue',
					"{$row->iwl_prefix}|{$row->iwl_title}|{$row->iwl_from}"
				);
				break;
			}

			if ( !is_null( $resultPageSet ) ) {
				$pages[] = Title::newFromRow( $row );
			} else {
				$entry = [ 'pageid' => $row->page_id ];

				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				ApiQueryBase::addTitleInfo( $entry, $title );

				if ( $row->page_is_redirect ) {
					$entry['redirect'] = true;
				}

				if ( $iwprefix ) {
					$entry['iwprefix'] = $row->iwl_prefix;
				}

				if ( $iwtitle ) {
					$entry['iwtitle'] = $row->iwl_title;
				}

				$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $entry );
				if ( !$fit ) {
					$this->setContinueEnumParameter(
						'continue',
						"{$row->iwl_prefix}|{$row->iwl_title}|{$row->iwl_from}"
					);
					break;
				}
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'iw' );
		} else {
			$resultPageSet->populateFromTitles( $pages );
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return [
			'prefix' => null,
			'title' => null,
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'limit' => [
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => '',
				ApiBase::PARAM_TYPE => [
					'iwprefix',
					'iwtitle',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
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
			'action=query&list=iwbacklinks&iwbltitle=Test&iwblprefix=wikibooks'
				=> 'apihelp-query+iwbacklinks-example-simple',
			'action=query&generator=iwbacklinks&giwbltitle=Test&giwblprefix=wikibooks&prop=info'
				=> 'apihelp-query+iwbacklinks-example-generator',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Iwbacklinks';
	}
}
