<?php
/**
 *
 *
 * Created on Jun 20, 2007
 *
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
class ApiRollback extends ApiBase {

	/**
	 * @var Title
	 */
	private $mTitleObj = null;

	/**
	 * @var User
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
		if ( count( $params['tags'] ) ) {
			$tagStatus = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $user );
			if ( !$tagStatus->isOK() ) {
				$this->dieStatus( $tagStatus );
			}
		}

		$retval = $pageObj->doRollback(
			$this->getRbUser( $params ),
			$summary,
			$params['token'],
			$params['markbot'],
			$details,
			$user,
			$params['tags']
		);

		// We don't care about multiple errors, just report one of them
		if ( $retval ) {
			if ( isset( $retval[0][0] ) &&
				( $retval[0][0] == 'alreadyrolled' || $retval[0][0] == 'cantrollback' )
			) {
				$error = $retval[0];
				$userMessage = $this->msg( $error[0], array_slice( $error, 1 ) );
				// dieUsageMsg() doesn't support $extraData
				$errorCode = $error[0];
				$errorInfo = isset( ApiBase::$messageMap[$errorCode] ) ?
					ApiBase::$messageMap[$errorCode]['info'] :
					$errorCode;
				$this->dieUsage( $errorInfo, $errorCode, 0, [
					'messageHtml' => $userMessage->parseAsBlock()
				] );
			}

			$this->dieUsageMsg( reset( $retval ) );
		}

		$watch = 'preferences';
		if ( isset( $params['watchlist'] ) ) {
			$watch = $params['watchlist'];
		}

		// Watch pages
		$this->setWatch( $watch, $titleObj, 'watchrollback' );

		$info = [
			'title' => $titleObj->getPrefixedText(),
			'pageid' => intval( $details['current']->getPage() ),
			'summary' => $details['summary'],
			'revid' => intval( $details['newid'] ),
			// The revision being reverted (previously the current revision of the page)
			'old_revid' => intval( $details['current']->getID() ),
			// The revision being restored (the last revision before revision(s) by the reverted user)
			'last_revid' => intval( $details['target']->getID() )
		];

		$oldUser = $details['current']->getUserText( Revision::FOR_THIS_USER );
		$lastUser = $details['target']->getUserText( Revision::FOR_THIS_USER );
		$diffUrl = $titleObj->getFullURL( [
			'diff' => $info['revid'],
			'oldid' => $info['old_revid'],
			'diffonly' => '1'
		] );
		$info['messageHtml'] = $this->msg( 'rollback-success-notify' )
			->params( $oldUser, $lastUser, $diffUrl )
			->parseAsBlock();

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
				ApiBase::PARAM_REQUIRED => true
			],
			'summary' => '',
			'markbot' => false,
			'watchlist' => [
				ApiBase::PARAM_DFLT => 'preferences',
				ApiBase::PARAM_TYPE => [
					'watch',
					'unwatch',
					'preferences',
					'nochange'
				],
			],
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
	 * @return string
	 */
	private function getRbUser( array $params ) {
		if ( $this->mUser !== null ) {
			return $this->mUser;
		}

		// We need to be able to revert IPs, but getCanonicalName rejects them
		$this->mUser = User::isIP( $params['user'] )
			? $params['user']
			: User::getCanonicalName( $params['user'] );
		if ( !$this->mUser ) {
			$this->dieUsageMsg( [ 'invaliduser', $params['user'] ] );
		}

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
				$this->dieUsageMsg( [ 'invalidtitle', $params['title'] ] );
			}
		} elseif ( isset( $params['pageid'] ) ) {
			$this->mTitleObj = Title::newFromID( $params['pageid'] );
			if ( !$this->mTitleObj ) {
				$this->dieUsageMsg( [ 'nosuchpageid', $params['pageid'] ] );
			}
		}

		if ( !$this->mTitleObj->exists() ) {
			$this->dieUsageMsg( 'notanarticle' );
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
		return 'https://www.mediawiki.org/wiki/API:Rollback';
	}
}
