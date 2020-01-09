<?php
/**
 * API for MediaWiki 1.17+
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
 * A query module to list all interwiki links on a page
 *
 * @ingroup API
 */
class ApiQueryIWLinks extends ApiQueryBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'iw' );
	}

	public function execute() {
		if ( $this->getPageSet()->getGoodTitleCount() == 0 ) {
			return;
		}

		$params = $this->extractRequestParams();
		$prop = array_flip( (array)$params['prop'] );

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

		// Handle deprecated param
		$this->requireMaxOneParameter( $params, 'url', 'prop' );
		if ( $params['url'] ) {
			$prop = [ 'url' => 1 ];
		}

		$this->addFields( [
			'iwl_from',
			'iwl_prefix',
			'iwl_title'
		] );

		$this->addTables( 'iwlinks' );
		$this->addWhereFld( 'iwl_from', array_keys( $this->getPageSet()->getGoodTitles() ) );

		if ( $params['continue'] !== null ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 3 );
			$op = $params['dir'] == 'descending' ? '<' : '>';
			$db = $this->getDB();
			$iwlfrom = (int)$cont[0];
			$iwlprefix = $db->addQuotes( $cont[1] );
			$iwltitle = $db->addQuotes( $cont[2] );
			$this->addWhere(
				"iwl_from $op $iwlfrom OR " .
				"(iwl_from = $iwlfrom AND " .
				"(iwl_prefix $op $iwlprefix OR " .
				"(iwl_prefix = $iwlprefix AND " .
				"iwl_title $op= $iwltitle)))"
			);
		}

		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		if ( isset( $params['prefix'] ) ) {
			$this->addWhereFld( 'iwl_prefix', $params['prefix'] );
			if ( isset( $params['title'] ) ) {
				$this->addWhereFld( 'iwl_title', $params['title'] );
				$this->addOption( 'ORDER BY', 'iwl_from' . $sort );
			} else {
				$this->addOption( 'ORDER BY', [
					'iwl_from' . $sort,
					'iwl_title' . $sort
				] );
			}
		} else {
			// Don't order by iwl_from if it's constant in the WHERE clause
			if ( count( $this->getPageSet()->getGoodTitles() ) == 1 ) {
				$this->addOption( 'ORDER BY', 'iwl_prefix' . $sort );
			} else {
				$this->addOption( 'ORDER BY', [
					'iwl_from' . $sort,
					'iwl_prefix' . $sort,
					'iwl_title' . $sort
				] );
			}
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$res = $this->select( __METHOD__ );

		$count = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that
				// there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter(
					'continue',
					"{$row->iwl_from}|{$row->iwl_prefix}|{$row->iwl_title}"
				);
				break;
			}
			$entry = [ 'prefix' => $row->iwl_prefix ];

			if ( isset( $prop['url'] ) ) {
				$title = Title::newFromText( "{$row->iwl_prefix}:{$row->iwl_title}" );
				if ( $title ) {
					$entry['url'] = wfExpandUrl( $title->getFullURL(), PROTO_CURRENT );
				}
			}

			ApiResult::setContentValue( $entry, 'title', $row->iwl_title );
			$fit = $this->addPageSubItem( $row->iwl_from, $entry );
			if ( !$fit ) {
				$this->setContinueEnumParameter(
					'continue',
					"{$row->iwl_from}|{$row->iwl_prefix}|{$row->iwl_title}"
				);
				break;
			}
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return [
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					'url',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'prefix' => null,
			'title' => null,
			'dir' => [
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => [
					'ascending',
					'descending'
				]
			],
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
			'url' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&prop=iwlinks&titles=Main%20Page'
				=> 'apihelp-query+iwlinks-example-simple',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Iwlinks';
	}
}
