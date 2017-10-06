<?php
/**
 * Copyright © 2007 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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
 * @ingroup API
 */
class ApiUndelete extends ApiBase {

	public function execute() {
		$this->useTransactionalTimeLimit();

		$params = $this->extractRequestParams();

		$user = $this->getUser();
		if ( $user->isBlocked() ) {
			$this->dieBlocked( $user->getBlock() );
		}

		$titleObj = Title::newFromText( $params['title'] );
		if ( !$titleObj || $titleObj->isExternal() ) {
			$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $params['title'] ) ] );
		}

		if ( !$titleObj->userCan( 'undelete', $user, 'secure' ) ) {
			$this->dieWithError( 'permdenied-undelete' );
		}

		// Check if user can add tags
		if ( !is_null( $params['tags'] ) ) {
			$ableToTag = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $user );
			if ( !$ableToTag->isOK() ) {
				$this->dieStatus( $ableToTag );
			}
		}

		// Convert timestamps
		if ( !isset( $params['timestamps'] ) ) {
			$params['timestamps'] = [];
		}
		if ( !is_array( $params['timestamps'] ) ) {
			$params['timestamps'] = [ $params['timestamps'] ];
		}
		foreach ( $params['timestamps'] as $i => $ts ) {
			$params['timestamps'][$i] = wfTimestamp( TS_MW, $ts );
		}

		$pa = new PageArchive( $titleObj, $this->getConfig() );
		$retval = $pa->undelete(
			( $params['timestamps'] ?? [] ),
			$params['reason'],
			$params['fileids'],
			false,
			$user,
			$params['tags']
		);
		if ( !is_array( $retval ) ) {
			$this->dieWithError( 'apierror-cantundelete' );
		}

		if ( $retval[1] ) {
			Hooks::run( 'FileUndeleteComplete',
				[ $titleObj, $params['fileids'], $this->getUser(), $params['reason'] ] );
		}

		$this->setWatch( $params['watchlist'], $titleObj );

		$info['title'] = $titleObj->getPrefixedText();
		$info['revisions'] = intval( $retval[0] );
		$info['fileversions'] = intval( $retval[1] );
		$info['reason'] = $retval[2];
		$this->getResult()->addValue( null, $this->getModuleName(), $info );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return [
			'title' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			],
			'reason' => '',
			'tags' => [
				ApiBase::PARAM_TYPE => 'tags',
				ApiBase::PARAM_ISMULTI => true,
			],
			'timestamps' => [
				ApiBase::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_ISMULTI => true,
			],
			'fileids' => [
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_ISMULTI => true,
			],
			'watchlist' => [
				ApiBase::PARAM_DFLT => 'preferences',
				ApiBase::PARAM_TYPE => [
					'watch',
					'unwatch',
					'preferences',
					'nochange'
				],
			],
		];
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return [
			'action=undelete&title=Main%20Page&token=123ABC&reason=Restoring%20main%20page'
				=> 'apihelp-undelete-example-page',
			'action=undelete&title=Main%20Page&token=123ABC' .
				'&timestamps=2007-07-03T22:00:45Z|2007-07-02T19:48:56Z'
				=> 'apihelp-undelete-example-revisions',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Undelete';
	}
}
