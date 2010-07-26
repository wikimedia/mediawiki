<?php

/*
 * Created on Feb 13, 2009
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2009 Roan Kattouw <Firstname>.<Lastname>@home.nl
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
	require_once ( 'ApiQueryBase.php' );
}

/**
 * Query module to enumerate all create-protected pages.
 *
 * @ingroup API
 */
class ApiQueryProtectedTitles extends ApiQueryGeneratorBase {

	public function __construct( $query, $moduleName ) {
		parent :: __construct( $query, $moduleName, 'pt' );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	private function run( $resultPageSet = null ) {
		$db = $this->getDB();
		$params = $this->extractRequestParams();

		$this->addTables( 'protected_titles' );
		$this->addFields( array( 'pt_namespace', 'pt_title', 'pt_timestamp' ) );

		$prop = array_flip( $params['prop'] );
		$this->addFieldsIf( 'pt_user', isset( $prop['user'] ) );
		$this->addFieldsIf( 'pt_reason', isset( $prop['comment'] ) || isset( $prop['parsedcomment'] ) );
		$this->addFieldsIf( 'pt_expiry', isset( $prop['expiry'] ) );
		$this->addFieldsIf( 'pt_create_perm', isset( $prop['level'] ) );

		$this->addWhereRange( 'pt_timestamp', $params['dir'], $params['start'], $params['end'] );
		$this->addWhereFld( 'pt_namespace', $params['namespace'] );
		$this->addWhereFld( 'pt_create_perm', $params['level'] );
		
		if ( isset( $prop['user'] ) )
		{
			$this->addTables( 'user' );
			$this->addFields( 'user_name' );
			$this->addJoinConds( array( 'user' => array( 'LEFT JOIN',
				'user_id=pt_user'
			) ) );
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$res = $this->select( __METHOD__ );

		$count = 0;
		$result = $this->getResult();
		while ( $row = $db->fetchObject( $res ) ) {
			if ( ++ $count > $params['limit'] ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->pt_timestamp ) );
				break;
			}

			$title = Title::makeTitle( $row->pt_namespace, $row->pt_title );
			if ( is_null( $resultPageSet ) ) {
				$vals = array();
				ApiQueryBase::addTitleInfo( $vals, $title );
				if ( isset( $prop['timestamp'] ) )
					$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->pt_timestamp );
					
				if ( isset( $prop['user'] ) && !is_null( $row->user_name ) )
					$vals['user'] = $row->user_name;
					
				if ( isset( $prop['comment'] ) )
					$vals['comment'] = $row->pt_reason;
					
				if ( isset( $prop['parsedcomment'] ) ) {
					global $wgUser;
					$vals['parsedcomment'] = $wgUser->getSkin()->formatComment( $row->pt_reason, $title );
				}
					
				if ( isset( $prop['expiry'] ) )
					$vals['expiry'] = Block::decodeExpiry( $row->pt_expiry, TS_ISO_8601 );
					
				if ( isset( $prop['level'] ) )
					$vals['level'] = $row->pt_create_perm;
				
				$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'start',
						wfTimestamp( TS_ISO_8601, $row->pt_timestamp ) );
					break;
				}
			} else {
				$titles[] = $title;
			}
		}
		$db->freeResult( $res );
		if ( is_null( $resultPageSet ) )
			$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), $this->getModulePrefix() );
		else
			$resultPageSet->populateFromTitles( $titles );
	}

	public function getCacheMode( $params ) {
		if ( !is_null( $params['prop'] ) && in_array( 'parsedcomment', $params['prop'] ) ) {
			// formatComment() calls wfMsg() among other things
			return 'anon-public-user-private';
		} else {
			return 'public';
		}
	}

	public function getAllowedParams() {
		global $wgRestrictionLevels;
		return array (
			'namespace' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => 'namespace',
			),
			'level' => array(
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array_diff( $wgRestrictionLevels, array( '' ) )
			),
			'limit' => array (
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX => ApiBase :: LIMIT_BIG1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_BIG2
			),
			'dir' => array (
				ApiBase :: PARAM_DFLT => 'older',
				ApiBase :: PARAM_TYPE => array (
					'older',
					'newer'
				)
			),
			'start' => array(
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'end' => array(
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'prop' => array(
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_DFLT => 'timestamp|level',
				ApiBase :: PARAM_TYPE => array(
					'timestamp',
					'user',
					'comment',
					'parsedcomment',
					'expiry',
					'level'
				)
			),
		);
	}

	public function getParamDescription() {
		return array (
			'namespace' => 'Only list titles in these namespaces',
			'start' => 'Start listing at this protection timestamp',
			'end' => 'Stop listing at this protection timestamp',
			'dir' => 'The direction in which to list',
			'limit' => 'How many total pages to return.',
			'prop' => 'Which properties to get',
			'level' => 'Only list titles with these protection levels',
		);
	}

	public function getDescription() {
		return 'List all titles protected from creation';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&list=protectedtitles',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
