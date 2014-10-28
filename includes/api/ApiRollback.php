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
		$user = $this->getUser();
		$params = $this->extractRequestParams();

		// WikiPage::doRollback needs a Web UI token, so get one of those if we
		// validated based on an API rollback token.
		$token = $params['token'];
		if ( $user->matchEditToken( $token, 'rollback', $this->getRequest() ) ) {
			$token = $this->getUser()->getEditToken(
				$this->getWebUITokenSalt( $params ),
				$this->getRequest()
			);
		}

		$titleObj = $this->getRbTitle( $params );
		$pageObj = WikiPage::factory( $titleObj );
		$summary = $params['summary'];
		$details = array();
		$retval = $pageObj->doRollback(
			$this->getRbUser( $params ),
			$summary,
			$token,
			$params['markbot'],
			$details,
			$user
		);

		if ( $retval ) {
			// We don't care about multiple errors, just report one of them
			$this->dieUsageMsg( reset( $retval ) );
		}

		$watch = 'preferences';
		if ( isset( $params['watchlist'] ) ) {
			$watch = $params['watchlist'];
		}

		// Watch pages
		$this->setWatch( $watch, $titleObj, 'watchrollback' );

		$info = array(
			'title' => $titleObj->getPrefixedText(),
			'pageid' => intval( $details['current']->getPage() ),
			'summary' => $details['summary'],
			'revid' => intval( $details['newid'] ),
			'old_revid' => intval( $details['current']->getID() ),
			'last_revid' => intval( $details['target']->getID() )
		);

		$this->getResult()->addValue( null, $this->getModuleName(), $info );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'title' => null,
			'pageid' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'user' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'summary' => '',
			'markbot' => false,
			'watchlist' => array(
				ApiBase::PARAM_DFLT => 'preferences',
				ApiBase::PARAM_TYPE => array(
					'watch',
					'unwatch',
					'preferences',
					'nochange'
				),
			),
			'token' => array(
				// Standard definition automatically inserted
				ApiBase::PARAM_HELP_MSG_APPEND => array( 'api-help-param-token-webui' ),
			),
		);
	}

	public function needsToken() {
		return 'rollback';
	}

	protected function getWebUITokenSalt( array $params ) {
		return array(
			$this->getRbTitle( $params )->getPrefixedText(),
			$this->getRbUser( $params )
		);
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
			$this->dieUsageMsg( array( 'invaliduser', $params['user'] ) );
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
				$this->dieUsageMsg( array( 'invalidtitle', $params['title'] ) );
			}
		} elseif ( isset( $params['pageid'] ) ) {
			$this->mTitleObj = Title::newFromID( $params['pageid'] );
			if ( !$this->mTitleObj ) {
				$this->dieUsageMsg( array( 'nosuchpageid', $params['pageid'] ) );
			}
		}

		if ( !$this->mTitleObj->exists() ) {
			$this->dieUsageMsg( 'notanarticle' );
		}

		return $this->mTitleObj;
	}

	protected function getExamplesMessages() {
		return array(
			'action=rollback&title=Main%20Page&user=Example&token=123ABC' =>
				'apihelp-rollback-example-simple',
			'action=rollback&title=Main%20Page&user=192.0.2.5&' .
				'token=123ABC&summary=Reverting%20vandalism&markbot=1' =>
				'apihelp-rollback-example-summary',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Rollback';
	}
}
