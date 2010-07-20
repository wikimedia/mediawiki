<?php

/*
 * Created on Sep 2, 2008
 *
 * API for MediaWiki 1.14+
 *
 * Copyright (C) 2008 Soxred93 soxred93@gmail.com,
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
	require_once ( 'ApiBase.php' );
}

/**
 * Allows user to patrol pages
 * @ingroup API
 */
class ApiPatrol extends ApiBase {

	public function __construct( $main, $action ) {
		parent :: __construct( $main, $action );
	}

	/**
	 * Patrols the article or provides the reason the patrol failed.
	 */
	public function execute() {
		$params = $this->extractRequestParams();
		
		if ( !isset( $params['rcid'] ) )
			$this->dieUsageMsg( array( 'missingparam', 'rcid' ) );

		$rc = RecentChange::newFromID( $params['rcid'] );
		if ( !$rc instanceof RecentChange )
			$this->dieUsageMsg( array( 'nosuchrcid', $params['rcid'] ) );
		$retval = RecentChange::markPatrolled( $params['rcid'] );
			
		if ( $retval )
			$this->dieUsageMsg( reset( $retval ) );
		
		$result = array( 'rcid' => intval( $rc->getAttribute( 'rc_id' ) ) );
		ApiQueryBase::addTitleInfo( $result, $rc->getTitle() );
		$this->getResult()->addValue( null, $this->getModuleName(), $result );
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array (
			'token' => null,
			'rcid' => array(
				ApiBase :: PARAM_TYPE => 'integer'
			),
		);
	}

	public function getParamDescription() {
		return array (
			'token' => 'Patrol token obtained from list=recentchanges',
			'rcid' => 'Recentchanges ID to patrol',
		);
	}

	public function getDescription() {
		return array (
			'Patrol a page or revision. '
		);
	}
	
    public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'missingparam', 'rcid' ),
			array( 'nosuchrcid', 'rcid' ),
        ) );
	}
	
	public function getTokenSalt() {
		return '';
	}

	protected function getExamples() {
		return array(
			'api.php?action=patrol&token=123abc&rcid=230672766'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}