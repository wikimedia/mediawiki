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
			$rc = RecentChange::newFromId( $params['rcid'] );
			if ( !$rc ) {
				$this->dieWithError( [ 'apierror-nosuchrcid', $params['rcid'] ] );
			}
		} else {
			$rev = Revision::newFromId( $params['revid'] );
			if ( !$rev ) {
				$this->dieWithError( [ 'apierror-nosuchrevid', $params['revid'] ] );
			}
			$rc = $rev->getRecentChange();
			if ( !$rc ) {
				$this->dieWithError( [ 'apierror-notpatrollable', $params['revid'] ] );
			}
		}

		$user = $this->getUser();
		$tags = $params['tags'];

		// Check if user can add tags
		if ( !is_null( $tags ) ) {
			$ableToTag = ChangeTags::canAddTagsAccompanyingChange( $tags, $user );
			if ( !$ableToTag->isOK() ) {
				$this->dieStatus( $ableToTag );
			}
		}

		$retval = $rc->doMarkPatrolled( $user, false, $tags );

		if ( $retval ) {
			$this->dieStatus( $this->errorArrayToStatus( $retval, $user ) );
		}

		$result = [ 'rcid' => intval( $rc->getAttribute( 'rc_id' ) ) ];
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
		return [
			'rcid' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'revid' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'tags' => [
				ApiBase::PARAM_TYPE => 'tags',
				ApiBase::PARAM_ISMULTI => true,
			],
		];
	}

	public function needsToken() {
		return 'patrol';
	}

	protected function getExamplesMessages() {
		return [
			'action=patrol&token=123ABC&rcid=230672766'
				=> 'apihelp-patrol-example-rcid',
			'action=patrol&token=123ABC&revid=230672766'
				=> 'apihelp-patrol-example-revid',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Patrol';
	}
}
