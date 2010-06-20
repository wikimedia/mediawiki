<?php

/*
 * Created on May 14, 2010
 *
 * API for MediaWiki 1.17+
 *
 * Copyright (C) 2010 Sam Reed
 * Copyright (C) 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( "ApiQueryBase.php" );
}

/**
 * This gives links pointing to the given interwiki
 * @ingroup API
 */
class ApiQueryIWBacklinks extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'iwbl' );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		
		if ( isset( $params['title'] ) && !isset( $params['prefix'] ) ) {
			$this->dieUsageMsg( array( 'missingparam', 'prefix' ) );
		}

		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			if ( count( $cont ) != 3 ) {
				$this->dieUsage( 'Invalid continue param. You should pass the ' .
					'original value returned by the previous query', '_badcontinue' );
			}

			$from = intval( $cont[0] );
			$prefix = $this->getDB()->strencode( $cont[1] );
			$title = $this->getDB()->strencode( $this->titleToKey( $cont[2] ) );
			$this->addWhere(
				"iwl_from > $from OR " .
				"(iwl_from = $from AND " .
				"(iwl_prefix > '$prefix' OR " .
				"(iwl_prefix = '$prefix' AND " .
				"iwl_title >= '$title')))"
			);
		}

		$this->addTables( array( 'iwlinks', 'page' ) );
		$this->addWhere( 'iwl_from = page_id' );

		$this->addFields( array( 'page_id', 'page_title', 'page_namespace', 'page_is_redirect',
			'iwl_from', 'iwl_prefix', 'iwl_title' ) );

		if ( isset( $params['prefix'] ) ) {
			$this->addWhereFld( 'iwl_prefix', $params['prefix'] );
		}
		
		if ( isset( $params['title'] ) ) {
			$this->addWhereFld( 'iwl_title', $params['title'] );
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$this->addOption( 'ORDER BY', 'iwl_from' );

		$db = $this->getDB();
		$res = $this->select( __METHOD__ );

		$count = 0;
		$result = $this->getResult();
		while ( $row = $db->fetchObject( $res ) ) {
			if ( ++ $count > $params['limit'] ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				// Continue string preserved in case the redirect query doesn't pass the limit
				$this->setContinueEnumParameter( 'continue', "{$row->iwl_from}|{$row->iwl_prefix}|{$row->iwl_title}" );
				break;
			}
			
			$entry = array();
			
			$entry['pageid'] = intval( $row->page_id );
			$entry['ns'] = $row->page_namespace;
			$entry['title'] = $row->page_title;
			
			if ( $row->page_is_redirect ) {
				$entry['redirect'] = '';
			}

			$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $entry );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', "{$row->iwl_from}|{$row->iwl_prefix}|{$row->iwl_title}" );
				break;
			}
		}

		$this->getResult()->setIndexedTagName_internal(
			array( 'query', $this->getModuleName() ),
			'iw'
		);
	}

	public function getAllowedParams() {
		return array(
			'prefix' => null,
			'title' => null,
			'continue' => null,
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			)
		);
	}

	public function getParamDescription() {
		return array(
			'prefix' => 'Prefix for the interwiki',
			'title' => "Interwiki link to search for. Must be used with {$this->getModulePrefix()}prefix",
			'continue' => 'When more results are available, use this to continue',
			'limit' => 'How many total pages to return',
		);
	}

	public function getDescription() {
		return array('Find all pages that link to the given interwiki link.',
			'Can be used to find all links with a prefix, or',
			'all links to a title (any prefix).',
			'Using neither parameter is effectively "All IW Links"',
			);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'missingparam', 'prefix' ),
			array( 'code' => '_badcontinue', 'info' => 'Invalid continue param. You should pass the original value returned by the previous query' ),
		) );
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&list=iwbacklinks&iwbltitle=Test&iwblprefix=wikibooks',
			'api.php?action=query&generator=iwbacklinks&giwbltitle=Test&iwblprefix=wikibooks&prop=info'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
