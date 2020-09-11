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

	use ApiWatchlistTrait;

	public function __construct( ApiMain $mainModule, $moduleName, $modulePrefix = '' ) {
		parent::__construct( $mainModule, $moduleName, $modulePrefix );

		$this->watchlistExpiryEnabled = $this->getConfig()->get( 'WatchlistExpiry' );
		$this->watchlistMaxDuration = $this->getConfig()->get( 'WatchlistExpiryMaxDuration' );
	}

	public function execute() {
		$this->useTransactionalTimeLimit();

		$params = $this->extractRequestParams();

		$user = $this->getUser();
		$block = $user->getBlock();
		if ( $block && $block->isSitewide() ) {
			$this->dieBlocked( $user->getBlock() );
		}

		$titleObj = Title::newFromText( $params['title'] );
		if ( !$titleObj || $titleObj->isExternal() ) {
			$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $params['title'] ) ] );
		}

		if ( !$this->getPermissionManager()->userCan( 'undelete', $this->getUser(), $titleObj ) ) {
			$this->dieWithError( 'permdenied-undelete' );
		}

		// Check if user can add tags
		if ( $params['tags'] !== null ) {
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
		$retval = $pa->undeleteAsUser(
			( $params['timestamps'] ?? [] ),
			$user,
			$params['reason'],
			$params['fileids'],
			false,
			$params['tags']
		);
		if ( !is_array( $retval ) ) {
			$this->dieWithError( 'apierror-cantundelete' );
		}

		if ( $retval[1] ) {
			$this->getHookRunner()->onFileUndeleteComplete(
				$titleObj, $params['fileids'],
				$this->getUser(), $params['reason'] );
		}

		$watchlistExpiry = $this->getExpiryFromParams( $params );
		$this->setWatch( $params['watchlist'], $titleObj, $user, null, $watchlistExpiry );

		$info = [
			'title' => $titleObj->getPrefixedText(),
			'revisions' => (int)$retval[0],
			'fileversions' => (int)$retval[1],
			'reason' => $retval[2]
		];
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
		] + $this->getWatchlistParams();
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
