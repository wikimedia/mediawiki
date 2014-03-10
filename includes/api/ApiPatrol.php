<?php
/**
 * API for MediaWiki 1.14+
 *
 * Created on Sep 2, 2008
 *
 * Copyright © 2008 Soxred93 soxred93@gmail.com,
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
 * Allows user to patrol pages
 * @ingroup API
 */
class ApiPatrol extends ApiBase {

	/**
	 * Patrols the article or provides the reason the patrol failed.
	 */
	public function execute() {
		$params = $this->extractRequestParams();
		$this->requireOnlyOneParameter( $params, 'rcid', 'revid' );

		if ( isset( $params['rcid'] ) ) {
			$rc = RecentChange::newFromID( $params['rcid'] );
			if ( !$rc ) {
				$this->dieUsageMsg( array( 'nosuchrcid', $params['rcid'] ) );
			}
		} else {
			$rev = Revision::newFromId( $params['revid'] );
			if ( !$rev ) {
				$this->dieUsageMsg( array( 'nosuchrevid', $params['revid'] ) );
			}
			$rc = $rev->getRecentChange();
			if ( !$rc ) {
				$this->dieUsage(
					'The revision ' . $params['revid'] . " can't be patrolled as it's too old",
					'notpatrollable'
				);
			}
		}

		$retval = $rc->doMarkPatrolled( $this->getUser() );

		if ( $retval ) {
			$this->dieUsageMsg( reset( $retval ) );
		}

		$result = array( 'rcid' => intval( $rc->getAttribute( 'rc_id' ) ) );
		ApiQueryBase::addTitleInfo( $result, $rc->getTitle() );
		$this->getResult()->addValue( null, $this->getModuleName(), $result );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'token' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'rcid' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'revid' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
		);
	}

	public function getParamDescription() {
		return array(
			'token' => 'Patrol token obtained from list=recentchanges',
			'rcid' => 'Recentchanges ID to patrol',
			'revid' => 'Revision ID to patrol',
		);
	}

	public function getResultProperties() {
		return array(
			'' => array(
				'rcid' => 'integer',
				'ns' => 'namespace',
				'title' => 'string'
			)
		);
	}

	public function getDescription() {
		return 'Patrol a page or revision.';
	}

	public function getPossibleErrors() {
		return array_merge(
			parent::getPossibleErrors(),
			parent::getRequireOnlyOneParameterErrorMessages( array( 'rcid', 'revid' ) ),
			array(
				array( 'nosuchrcid', 'rcid' ),
				array( 'nosuchrevid', 'revid' ),
				array(
					'code' => 'notpatrollable',
					'info' => "The revision can't be patrolled as it's too old"
				)
			)
		);
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return 'patrol';
	}

	public function getExamples() {
		return array(
			'api.php?action=patrol&token=123abc&rcid=230672766',
			'api.php?action=patrol&token=123abc&revid=230672766'
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Patrol';
	}
}
