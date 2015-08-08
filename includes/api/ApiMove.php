<?php
/**
 *
 *
 * Created on Oct 31, 2007
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
 * API Module to move pages
 * @ingroup API
 */
class ApiMove extends ApiBase {

	public function execute() {
		$this->useTransactionalTimeLimit();

		$user = $this->getUser();
		$params = $this->extractRequestParams();

		$this->requireOnlyOneParameter( $params, 'from', 'fromid' );

		if ( isset( $params['from'] ) ) {
			$fromTitle = Title::newFromText( $params['from'] );
			if ( !$fromTitle || $fromTitle->isExternal() ) {
				$this->dieUsageMsg( array( 'invalidtitle', $params['from'] ) );
			}
		} elseif ( isset( $params['fromid'] ) ) {
			$fromTitle = Title::newFromID( $params['fromid'] );
			if ( !$fromTitle ) {
				$this->dieUsageMsg( array( 'nosuchpageid', $params['fromid'] ) );
			}
		}

		if ( !$fromTitle->exists() ) {
			$this->dieUsageMsg( 'notanarticle' );
		}
		$fromTalk = $fromTitle->getTalkPage();

		$toTitle = Title::newFromText( $params['to'] );
		if ( !$toTitle || $toTitle->isExternal() ) {
			$this->dieUsageMsg( array( 'invalidtitle', $params['to'] ) );
		}
		$toTalk = $toTitle->getTalkPage();

		if ( $toTitle->getNamespace() == NS_FILE
			&& !RepoGroup::singleton()->getLocalRepo()->findFile( $toTitle )
			&& wfFindFile( $toTitle )
		) {
			if ( !$params['ignorewarnings'] && $user->isAllowed( 'reupload-shared' ) ) {
				$this->dieUsageMsg( 'sharedfile-exists' );
			} elseif ( !$user->isAllowed( 'reupload-shared' ) ) {
				$this->dieUsageMsg( 'cantoverwrite-sharedfile' );
			}
		}

		// Move the page
		$toTitleExists = $toTitle->exists();
		$status = $this->movePage( $fromTitle, $toTitle, $params['reason'], !$params['noredirect'] );
		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
		}

		$r = array(
			'from' => $fromTitle->getPrefixedText(),
			'to' => $toTitle->getPrefixedText(),
			'reason' => $params['reason']
		);

		//NOTE: we assume that if the old title exists, it's because it was re-created as
		// a redirect to the new title. This is not safe, but what we did before was
		// even worse: we just determined whether a redirect should have been created,
		// and reported that it was created if it should have, without any checks.
		// Also note that isRedirect() is unreliable because of bug 37209.
		$r['redirectcreated'] = $fromTitle->exists();

		$r['moveoverredirect'] = $toTitleExists;

		// Move the talk page
		if ( $params['movetalk'] && $fromTalk->exists() && !$fromTitle->isTalkPage() ) {
			$toTalkExists = $toTalk->exists();
			$status = $this->movePage( $fromTalk, $toTalk, $params['reason'], !$params['noredirect'] );
			if ( $status->isOK() ) {
				$r['talkfrom'] = $fromTalk->getPrefixedText();
				$r['talkto'] = $toTalk->getPrefixedText();
				$r['talkmoveoverredirect'] = $toTalkExists;
			} else {
				// We're not gonna dieUsage() on failure, since we already changed something
				$error = $this->getErrorFromStatus( $status );
				$r['talkmove-error-code'] = $error[0];
				$r['talkmove-error-info'] = $error[1];
			}
		}

		$result = $this->getResult();

		// Move subpages
		if ( $params['movesubpages'] ) {
			$r['subpages'] = $this->moveSubpages( $fromTitle, $toTitle,
				$params['reason'], $params['noredirect'] );
			ApiResult::setIndexedTagName( $r['subpages'], 'subpage' );

			if ( $params['movetalk'] ) {
				$r['subpages-talk'] = $this->moveSubpages( $fromTalk, $toTalk,
					$params['reason'], $params['noredirect'] );
				ApiResult::setIndexedTagName( $r['subpages-talk'], 'subpage' );
			}
		}

		$watch = 'preferences';
		if ( isset( $params['watchlist'] ) ) {
			$watch = $params['watchlist'];
		} elseif ( $params['watch'] ) {
			$watch = 'watch';
			$this->logFeatureUsage( 'action=move&watch' );
		} elseif ( $params['unwatch'] ) {
			$watch = 'unwatch';
			$this->logFeatureUsage( 'action=move&unwatch' );
		}

		// Watch pages
		$this->setWatch( $watch, $fromTitle, 'watchmoves' );
		$this->setWatch( $watch, $toTitle, 'watchmoves' );

		$result->addValue( null, $this->getModuleName(), $r );
	}

	/**
	 * @param Title $from
	 * @param Title $to
	 * @param string $reason
	 * @param bool $createRedirect
	 * @return Status
	 */
	protected function movePage( Title $from, Title $to, $reason, $createRedirect ) {
		$mp = new MovePage( $from, $to );
		$valid = $mp->isValidMove();
		if ( !$valid->isOK() ) {
			return $valid;
		}

		$permStatus = $mp->checkPermissions( $this->getUser(), $reason );
		if ( !$permStatus->isOK() ) {
			return $permStatus;
		}

		// Check suppressredirect permission
		if ( !$this->getUser()->isAllowed( 'suppressredirect' ) ) {
			$createRedirect = true;
		}

		return $mp->move( $this->getUser(), $reason, $createRedirect );
	}

	/**
	 * @param Title $fromTitle
	 * @param Title $toTitle
	 * @param string $reason
	 * @param bool $noredirect
	 * @return array
	 */
	public function moveSubpages( $fromTitle, $toTitle, $reason, $noredirect ) {
		$retval = array();
		$success = $fromTitle->moveSubpages( $toTitle, true, $reason, !$noredirect );
		if ( isset( $success[0] ) ) {
			return array( 'error' => $this->parseMsg( $success ) );
		}

		// At least some pages could be moved
		// Report each of them separately
		foreach ( $success as $oldTitle => $newTitle ) {
			$r = array( 'from' => $oldTitle );
			if ( is_array( $newTitle ) ) {
				$r['error'] = $this->parseMsg( reset( $newTitle ) );
			} else {
				// Success
				$r['to'] = $newTitle;
			}
			$retval[] = $r;
		}

		return $retval;
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'from' => null,
			'fromid' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'to' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'reason' => '',
			'movetalk' => false,
			'movesubpages' => false,
			'noredirect' => false,
			'watch' => array(
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			),
			'unwatch' => array(
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			),
			'watchlist' => array(
				ApiBase::PARAM_DFLT => 'preferences',
				ApiBase::PARAM_TYPE => array(
					'watch',
					'unwatch',
					'preferences',
					'nochange'
				),
			),
			'ignorewarnings' => false
		);
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return array(
			'action=move&from=Badtitle&to=Goodtitle&token=123ABC&' .
				'reason=Misspelled%20title&movetalk=&noredirect='
				=> 'apihelp-move-example-move',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Move';
	}
}
