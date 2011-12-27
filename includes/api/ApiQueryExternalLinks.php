<?php
/**
 *
 *
 * Created on May 13, 2007
 *
 * Copyright © 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'el' );
	}

	public function execute() {
		if ( $this->getPageSet()->getGoodTitleCount() == 0 ) {
			return;
		}

		$params = $this->extractRequestParams();

		$query = $params['query'];
		$protocol = ApiQueryExtLinksUsage::getProtocolPrefix( $params['protocol'] );

		$this->addFields( array(
			'el_from',
			'el_to'
		) );

		$this->addTables( 'externallinks' );
		$this->addWhereFld( 'el_from', array_keys( $this->getPageSet()->getGoodTitles() ) );

		$whereQuery = $this->prepareUrlQuerySearchString( $query, $protocol );

		if ( $whereQuery !== null ) {
			$this->addWhere( $whereQuery );
		}

		// Don't order by el_from if it's constant in the WHERE clause
		if ( count( $this->getPageSet()->getGoodTitles() ) != 1 ) {
			$this->addOption( 'ORDER BY', 'el_from' );
		}

		// If we're querying all protocols, use DISTINCT to avoid repeating protocol-relative links twice
		if ( $protocol === null ) {
			$this->addOption( 'DISTINCT' );
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$offset = isset( $params['offset'] ) ? $params['offset'] : 0;
		if ( $offset ) {
			$this->addOption( 'OFFSET', $params['offset'] );
		}

		$res = $this->select( __METHOD__ );

		$count = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that
				// there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'offset', $offset + $params['limit'] );
				break;
			}
			$entry = array();
			// We *could* run this through wfExpandUrl() but I think it's better to output the link verbatim, even if it's protocol-relative --Roan
			ApiResult::setContent( $entry, $row->el_to );
			$fit = $this->addPageSubItem( $row->el_from, $entry );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'offset', $offset + $count - 1 );
				break;
			}
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return array(
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'offset' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'protocol' => array(
				ApiBase::PARAM_TYPE => ApiQueryExtLinksUsage::prepareProtocols(),
				ApiBase::PARAM_DFLT => '',
			),
			'query' => null,
		);
	}

	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'limit' => 'How many links to return',
			'offset' => 'When more results are available, use this to continue',
			'protocol' => array(
				"Protocol of the url. If empty and {$p}query set, the protocol is http.",
				"Leave both this and {$p}query empty to list all external links"
			),
			'query' => 'Search string without protocol. Useful for checking whether a certain page contains a certain external url',
		);
	}

	public function getDescription() {
		return 'Returns all external urls (not interwikies) from the given page(s)';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'bad_query', 'info' => 'Invalid query' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&prop=extlinks&titles=Main%20Page' => 'Get a list of external links on the [[Main Page]]',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Properties#extlinks_.2F_el';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
