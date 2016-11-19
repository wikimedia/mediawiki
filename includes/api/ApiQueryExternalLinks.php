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
 * A query module to list all external URLs found on a given set of pages.
 *
 * @ingroup API
 */
class ApiQueryExternalLinks extends ApiQueryBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'el' );
	}

	public function execute() {
		if ( $this->getPageSet()->getGoodTitleCount() == 0 ) {
			return;
		}

		$params = $this->extractRequestParams();
		$db = $this->getDB();

		$query = $params['query'];
		$protocol = ApiQueryExtLinksUsage::getProtocolPrefix( $params['protocol'] );

		$this->addFields( [
			'el_from',
			'el_to'
		] );

		$this->addTables( 'externallinks' );
		$this->addWhereFld( 'el_from', array_keys( $this->getPageSet()->getGoodTitles() ) );

		$orderBy = [];

		// Don't order by el_from if it's constant in the WHERE clause
		if ( count( $this->getPageSet()->getGoodTitles() ) != 1 ) {
			$orderBy[] = 'el_from';
		}

		if ( $query !== null && $query !== '' ) {
			if ( $protocol === null ) {
				$protocol = 'http://';
			}

			// Normalize query to match the normalization applied for the externallinks table
			$query = Parser::normalizeLinkUrl( $protocol . $query );

			$conds = LinkFilter::getQueryConditions( $query, [
				'protocol' => '',
				'oneWildcard' => true,
				'db' => $db
			] );
			if ( !$conds ) {
				$this->dieWithError( 'apierror-badquery' );
			}
			$this->addWhere( $conds );
			if ( !isset( $conds['el_index_60'] ) ) {
				$orderBy[] = 'el_index_60';
			}
		} else {
			$orderBy[] = 'el_index_60';

			if ( $protocol !== null ) {
				$this->addWhere( 'el_index_60' . $db->buildLike( "$protocol", $db->anyString() ) );
			} else {
				// We're querying all protocols, filter out duplicate protocol-relative links
				$this->addWhere( $db->makeList( [
					'el_to NOT' . $db->buildLike( '//', $db->anyString() ),
					'el_index_60 ' . $db->buildLike( 'http://', $db->anyString() ),
				], LIST_OR ) );
			}
		}

		$orderBy[] = 'el_id';
		$this->addOption( 'ORDER BY', $orderBy );
		$this->addFields( $orderBy ); // Make sure

		$this->addOption( 'LIMIT', $params['limit'] + 1 );

		if ( $params['continue'] !== null ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) !== count( $orderBy ) );
			$i = count( $cont ) - 1;
			$cond = $orderBy[$i] . ' >= ' . $db->addQuotes( rawurldecode( $cont[$i] ) );
			while ( $i-- > 0 ) {
				$field = $orderBy[$i];
				$v = $db->addQuotes( rawurldecode( $cont[$i] ) );
				$cond = "($field > $v OR ($field = $v AND $cond))";
			}
			$this->addWhere( $cond );
		}

		$res = $this->select( __METHOD__ );

		$count = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that
				// there are additional pages to be had. Stop here...
				$this->setContinue( $orderBy, $row );
				break;
			}
			$entry = [];
			$to = $row->el_to;
			// expand protocol-relative urls
			if ( $params['expandurl'] ) {
				$to = wfExpandUrl( $to, PROTO_CANONICAL );
			}
			ApiResult::setContentValue( $entry, 'url', $to );
			$fit = $this->addPageSubItem( $row->el_from, $entry );
			if ( !$fit ) {
				$this->setContinue( $orderBy, $row );
				break;
			}
		}
	}

	private function setContinue( $orderBy, $row ) {
		$fields = [];
		foreach ( $orderBy as $field ) {
			$fields[] = strtr( $row->$field, [ '%' => '%25', '|' => '%7C' ] );
		}
		$this->setContinueEnumParameter( 'continue', implode( '|', $fields ) );
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
			'protocol' => [
				ApiBase::PARAM_TYPE => ApiQueryExtLinksUsage::prepareProtocols(),
				ApiBase::PARAM_DFLT => '',
			],
			'query' => null,
			'expandurl' => false,
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&prop=extlinks&titles=Main%20Page'
				=> 'apihelp-query+extlinks-example-simple',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Extlinks';
	}
}
