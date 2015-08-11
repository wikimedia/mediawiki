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

		$multi = false;
		$rcs = array();
		if ( isset( $params['rcid'] ) ) {
			$multi = count( $params['rcid'] ) > 1;
			foreach ( $params['rcid'] as $rcid ) {
				$rc = RecentChange::newFromId( $rcid );
				if ( !$rc ) {
					$this->dieUsageMsg( array( 'nosuchrcid', $params['rcid'] ) );
				}
				$rcs[] = $rc;
			}
		} else {
			$multi = count( $params['revid'] ) > 1;
			foreach ( $params['revid'] as $revid ) {
				$rev = Revision::newFromId( $revid );
				if ( !$rev ) {
					$this->dieUsageMsg( array( 'nosuchrevid', $revid ) );
				}
				$rc = $rev->getRecentChange();
				if ( !$rc ) {
					$this->dieUsage(
						'The revision ' . $revid . " can't be patrolled as it's too old",
						'notpatrollable'
					);
				}
				$rcs[] = $rc;
			}
		}

		$user = $this->getUser();
		$res = array();
		foreach ( $rcs as $rc ) {
			$retval = $rc->doMarkPatrolled( $user );
			if ( $retval ) {
				$this->dieUsageMsg( reset( $retval ) );
			}
			$result = array( 'rcid' => intval( $rc->getAttribute( 'rc_id' ) ) );
			ApiQueryBase::addTitleInfo( $result, $rc->getTitle() );
			$res[] = $result;
		}

		if ( !$multi && count( $res ) === 1 ) {
			$res = $res[0];
		}

		$this->getResult()->addValue( null, $this->getModuleName(), $res );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'rcid' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_ISMULTI => true
			),
			'revid' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_ISMULTI => true
			),
		);
	}

	public function needsToken() {
		return 'patrol';
	}

	protected function getExamplesMessages() {
		return array(
			'action=patrol&token=123ABC&rcid=230672766'
				=> 'apihelp-patrol-example-rcid',
			'action=patrol&token=123ABC&revid=230672766'
				=> 'apihelp-patrol-example-revid',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Patrol';
	}
}
