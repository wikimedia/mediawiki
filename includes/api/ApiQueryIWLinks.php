<?php
/**
 * API for MediaWiki 1.17+
 *
 * Created on May 14, 2010
 *
 * Copyright © 2010 Sam Reed
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
 * A query module to list all interwiki links on a page
 *
 * @ingroup API
 */
class ApiQueryIWLinks extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'iw' );
	}

	public function execute() {
		if ( $this->getPageSet()->getGoodTitleCount() == 0 ) {
			return;
		}

		$params = $this->extractRequestParams();

		if ( isset( $params['title'] ) && !isset( $params['prefix'] ) ) {
			$this->dieUsageMsg( array( 'missingparam', 'prefix' ) );
		}

		$this->addFields( array(
			'iwl_from',
			'iwl_prefix',
			'iwl_title'
		) );

		$this->addTables( 'iwlinks' );
		$this->addWhereFld( 'iwl_from', array_keys( $this->getPageSet()->getGoodTitles() ) );

		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			if ( count( $cont ) != 3 ) {
				$this->dieUsage( 'Invalid continue param. You should pass the ' .
					'original value returned by the previous query', '_badcontinue' );
			}
			$iwlfrom = intval( $cont[0] );
			$iwlprefix = $this->getDB()->strencode( $cont[1] );
			$iwltitle = $this->getDB()->strencode( $this->titleToKey( $cont[2] ) );
			$this->addWhere(
				"iwl_from > $iwlfrom OR " .
				"(iwl_from = $iwlfrom AND " .
				"(iwl_prefix > '$iwlprefix' OR " .
				"(iwl_prefix = '$iwlprefix' AND " .
				"iwl_title >= '$iwltitle')))"
			);
		}

		$dir = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		if ( isset( $params['prefix'] ) ) {
			$this->addWhereFld( 'iwl_prefix', $params['prefix'] );
			if ( isset( $params['title'] ) ) {
				$this->addWhereFld( 'iwl_title', $params['title'] );
				$this->addOption( 'ORDER BY', 'iwl_from' . $dir );
			} else {
				$this->addOption( 'ORDER BY', array(
						'iwl_title' . $dir,
						'iwl_from' . $dir
				));
			}
		} else {
			// Don't order by iwl_from if it's constant in the WHERE clause
			if ( count( $this->getPageSet()->getGoodTitles() ) == 1 ) {
				$this->addOption( 'ORDER BY', 'iwl_prefix' . $dir );
			} else {
				$this->addOption( 'ORDER BY', array (
						'iwl_from' . $dir,
						'iwl_prefix' . $dir
				));
			}
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$res = $this->select( __METHOD__ );

		$count = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that
				// there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', "{$row->iwl_from}|{$row->iwl_prefix}|{$row->iwl_title}" );
				break;
			}
			$entry = array( 'prefix' => $row->iwl_prefix );

			if ( $params['url'] ) {
				$title = Title::newFromText( "{$row->iwl_prefix}:{$row->iwl_title}" );
				if ( $title ) {
					$entry['url'] = wfExpandUrl( $title->getFullURL(), PROTO_CURRENT );
				}
			}

			ApiResult::setContent( $entry, $row->iwl_title );
			$fit = $this->addPageSubItem( $row->iwl_from, $entry );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', "{$row->iwl_from}|{$row->iwl_prefix}|{$row->iwl_title}" );
				break;
			}
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return array(
			'url' => false,
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'continue' => null,
			'prefix' => null,
			'title' => null,
			'dir' => array(
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => array(
					'ascending',
					'descending'
				)
			),
		);
	}

	public function getParamDescription() {
		return array(
			'url' => 'Whether to get the full URL',
			'limit' => 'How many interwiki links to return',
			'continue' => 'When more results are available, use this to continue',
			'prefix' => 'Prefix for the interwiki',
			'title' => "Interwiki link to search for. Must be used with {$this->getModulePrefix()}prefix",
			'dir' => 'The direction in which to list',
		);
	}

	public function getDescription() {
		return 'Returns all interwiki links from the given page(s)';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'missingparam', 'prefix' ),
			array( 'code' => '_badcontinue', 'info' => 'Invalid continue param. You should pass the original value returned by the previous query' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&prop=iwlinks&titles=Main%20Page' => 'Get interwiki links from the [[Main Page]]',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
