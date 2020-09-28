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

use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\User\UserIdentity;

/**
 * @ingroup API
 */
class ApiRollback extends ApiBase {

	use ApiWatchlistTrait;

	public function __construct( ApiMain $mainModule, $moduleName, $modulePrefix = '' ) {
		parent::__construct( $mainModule, $moduleName, $modulePrefix );

		$this->watchlistExpiryEnabled = $this->getConfig()->get( 'WatchlistExpiry' );
		$this->watchlistMaxDuration = $this->getConfig()->get( 'WatchlistExpiryMaxDuration' );
	}

	/**
	 * @var Title
	 */
	private $mTitleObj = null;

	/**
	 * @var UserIdentity
	 */
	private $mUser = null;

	public function execute() {
		$this->useTransactionalTimeLimit();

		$user = $this->getUser();
		$params = $this->extractRequestParams();

		$titleObj = $this->getRbTitle( $params );
		$pageObj = WikiPage::factory( $titleObj );
		$summary = $params['summary'];
		$details = [];

		// If change tagging was requested, check that the user is allowed to tag,
		// and the tags are valid
		if ( $params['tags'] ) {
			$tagStatus = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $user );
			if ( !$tagStatus->isOK() ) {
				$this->dieStatus( $tagStatus );
			}
		}

		// @TODO: remove this hack once rollback uses POST (T88044)
		$fname = __METHOD__;
		$trxLimits = $this->getConfig()->get( 'TrxProfilerLimits' );
		$trxProfiler = Profiler::instance()->getTransactionProfiler();
		$trxProfiler->redefineExpectations( $trxLimits['POST'], $fname );
		DeferredUpdates::addCallableUpdate( function () use ( $trxProfiler, $trxLimits, $fname ) {
			$trxProfiler->redefineExpectations( $trxLimits['PostSend-POST'], $fname );
		} );

		$retval = $pageObj->doRollback(
			$this->getRbUser( $params )->getName(),
			$summary,
			$params['token'],
			$params['markbot'],
			$details,
			$user,
			$params['tags']
		);

		if ( $retval ) {
			$this->dieStatus( $this->errorArrayToStatus( $retval, $user ) );
		}

		$watch = $params['watchlist'] ?? 'preferences';
		$watchlistExpiry = $this->getExpiryFromParams( $params );

		// Watch pages
		$this->setWatch( $watch, $titleObj, $user, 'watchrollback', $watchlistExpiry );

		$currentRevisionRecord = $details['current-revision-record'];
		$targetRevisionRecord = $details['target-revision-record'];

		$info = [
			'title' => $titleObj->getPrefixedText(),
			'pageid' => $currentRevisionRecord->getPageId(),
			'summary' => $details['summary'],
			'revid' => (int)$details['newid'],
			// The revision being reverted (previously the current revision of the page)
			'old_revid' => $currentRevisionRecord->getID(),
			// The revision being restored (the last revision before revision(s) by the reverted user)
			'last_revid' => $targetRevisionRecord->getID()
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
		$params = [
			'title' => null,
			'pageid' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'tags' => [
				ApiBase::PARAM_TYPE => 'tags',
				ApiBase::PARAM_ISMULTI => true,
			],
			'user' => [
				ApiBase::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'id', 'interwiki' ],
				UserDef::PARAM_RETURN_OBJECT => true,
				ApiBase::PARAM_REQUIRED => true
			],
			'summary' => '',
			'markbot' => false,
		];

		// Params appear in the docs in the order they are defined,
		// which is why this is here (we want it above the token param).
		$params += $this->getWatchlistParams();

		return $params + [
			'token' => [
				// Standard definition automatically inserted
				ApiBase::PARAM_HELP_MSG_APPEND => [ 'api-help-param-token-webui' ],
			],
		];
	}

	public function needsToken() {
		return 'rollback';
	}

	/**
	 * @param array $params
	 *
	 * @return UserIdentity
	 */
	private function getRbUser( array $params ) : UserIdentity {
		if ( $this->mUser !== null ) {
			return $this->mUser;
		}

		$this->mUser = $params['user'];

		return $this->mUser;
	}

	/**
	 * @param array $params
	 *
	 * @return Title
	 */
	private function getRbTitle( array $params ) {
		if ( $this->mTitleObj !== null ) {
			return $this->mTitleObj;
		}

		$this->requireOnlyOneParameter( $params, 'title', 'pageid' );

		if ( isset( $params['title'] ) ) {
			$this->mTitleObj = Title::newFromText( $params['title'] );
			if ( !$this->mTitleObj || $this->mTitleObj->isExternal() ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $params['title'] ) ] );
			}
		} elseif ( isset( $params['pageid'] ) ) {
			$this->mTitleObj = Title::newFromID( $params['pageid'] );
			if ( !$this->mTitleObj ) {
				$this->dieWithError( [ 'apierror-nosuchpageid', $params['pageid'] ] );
			}
		}

		if ( !$this->mTitleObj->exists() ) {
			$this->dieWithError( 'apierror-missingtitle' );
		}

		return $this->mTitleObj;
	}

	protected function getExamplesMessages() {
		return [
			'action=rollback&title=Main%20Page&user=Example&token=123ABC' =>
				'apihelp-rollback-example-simple',
			'action=rollback&title=Main%20Page&user=192.0.2.5&' .
				'token=123ABC&summary=Reverting%20vandalism&markbot=1' =>
				'apihelp-rollback-example-summary',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Rollback';
	}
}
