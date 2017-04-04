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
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $params['from'] ) ] );
			}
		} elseif ( isset( $params['fromid'] ) ) {
			$fromTitle = Title::newFromID( $params['fromid'] );
			if ( !$fromTitle ) {
				$this->dieWithError( [ 'apierror-nosuchpageid', $params['fromid'] ] );
			}
		}

		if ( !$fromTitle->exists() ) {
			$this->dieWithError( 'apierror-missingtitle' );
		}
		$fromTalk = $fromTitle->getTalkPage();

		$toTitle = Title::newFromText( $params['to'] );
		if ( !$toTitle || $toTitle->isExternal() ) {
			$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $params['to'] ) ] );
		}
		$toTalk = $toTitle->canTalk() ? $toTitle->getTalkPage() : null;

		if ( $toTitle->getNamespace() == NS_FILE
			&& !RepoGroup::singleton()->getLocalRepo()->findFile( $toTitle )
			&& wfFindFile( $toTitle )
		) {
			if ( !$params['ignorewarnings'] && $user->isAllowed( 'reupload-shared' ) ) {
				$this->dieWithError( 'apierror-fileexists-sharedrepo-perm' );
			} elseif ( !$user->isAllowed( 'reupload-shared' ) ) {
				$this->dieWithError( 'apierror-cantoverwrite-sharedfile' );
			}
		}

		// Rate limit
		if ( $user->pingLimiter( 'move' ) ) {
			$this->dieWithError( 'apierror-ratelimited' );
		}

		// Check if the user is allowed to add the specified changetags
		if ( $params['tags'] ) {
			$ableToTag = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $user );
			if ( !$ableToTag->isOK() ) {
				$this->dieStatus( $ableToTag );
			}
		}

		// Move the page
		$toTitleExists = $toTitle->exists();
		$status = $this->movePage( $fromTitle, $toTitle, $params['reason'], !$params['noredirect'],
			$params['tags'] ?: [] );
		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
		}

		$r = [
			'from' => $fromTitle->getPrefixedText(),
			'to' => $toTitle->getPrefixedText(),
			'reason' => $params['reason']
		];

		// NOTE: we assume that if the old title exists, it's because it was re-created as
		// a redirect to the new title. This is not safe, but what we did before was
		// even worse: we just determined whether a redirect should have been created,
		// and reported that it was created if it should have, without any checks.
		// Also note that isRedirect() is unreliable because of T39209.
		$r['redirectcreated'] = $fromTitle->exists();

		$r['moveoverredirect'] = $toTitleExists;

		// Move the talk page
		if ( $params['movetalk'] && $toTalk && $fromTalk->exists() && !$fromTitle->isTalkPage() ) {
			$toTalkExists = $toTalk->exists();
			$status = $this->movePage(
				$fromTalk,
				$toTalk,
				$params['reason'],
				!$params['noredirect'],
				$params['tags'] ?: []
			);
			if ( $status->isOK() ) {
				$r['talkfrom'] = $fromTalk->getPrefixedText();
				$r['talkto'] = $toTalk->getPrefixedText();
				$r['talkmoveoverredirect'] = $toTalkExists;
			} else {
				// We're not going to dieWithError() on failure, since we already changed something
				$r['talkmove-errors'] = $this->getErrorFormatter()->arrayFromStatus( $status );
			}
		}

		$result = $this->getResult();

		// Move subpages
		if ( $params['movesubpages'] ) {
			$r['subpages'] = $this->moveSubpages(
				$fromTitle,
				$toTitle,
				$params['reason'],
				$params['noredirect'],
				$params['tags'] ?: []
			);
			ApiResult::setIndexedTagName( $r['subpages'], 'subpage' );

			if ( $params['movetalk'] ) {
				$r['subpages-talk'] = $this->moveSubpages(
					$fromTalk,
					$toTalk,
					$params['reason'],
					$params['noredirect'],
					$params['tags'] ?: []
				);
				ApiResult::setIndexedTagName( $r['subpages-talk'], 'subpage' );
			}
		}

		$watch = 'preferences';
		if ( isset( $params['watchlist'] ) ) {
			$watch = $params['watchlist'];
		} elseif ( $params['watch'] ) {
			$watch = 'watch';
		} elseif ( $params['unwatch'] ) {
			$watch = 'unwatch';
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
	 * @param array $changeTags Applied to the entry in the move log and redirect page revision
	 * @return Status
	 */
	protected function movePage( Title $from, Title $to, $reason, $createRedirect, $changeTags ) {
		$mp = new MovePage( $from, $to );
		$valid = $mp->isValidMove();
		if ( !$valid->isOK() ) {
			return $valid;
		}

		$user = $this->getUser();
		$permStatus = $mp->checkPermissions( $user, $reason );
		if ( !$permStatus->isOK() ) {
			return $permStatus;
		}

		// Check suppressredirect permission
		if ( !$user->isAllowed( 'suppressredirect' ) ) {
			$createRedirect = true;
		}

		return $mp->move( $user, $reason, $createRedirect, $changeTags );
	}

	/**
	 * @param Title $fromTitle
	 * @param Title $toTitle
	 * @param string $reason
	 * @param bool $noredirect
	 * @param array $changeTags Applied to the entry in the move log and redirect page revisions
	 * @return array
	 */
	public function moveSubpages( $fromTitle, $toTitle, $reason, $noredirect, $changeTags = [] ) {
		$retval = [];

		$success = $fromTitle->moveSubpages( $toTitle, true, $reason, !$noredirect, $changeTags );
		if ( isset( $success[0] ) ) {
			$status = $this->errorArrayToStatus( $success );
			return [ 'errors' => $this->getErrorFormatter()->arrayFromStatus( $status ) ];
		}

		// At least some pages could be moved
		// Report each of them separately
		foreach ( $success as $oldTitle => $newTitle ) {
			$r = [ 'from' => $oldTitle ];
			if ( is_array( $newTitle ) ) {
				$status = $this->errorArrayToStatus( $newTitle );
				$r['errors'] = $this->getErrorFormatter()->arrayFromStatus( $status );
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
		return [
			'from' => null,
			'fromid' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'to' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			],
			'reason' => '',
			'movetalk' => false,
			'movesubpages' => false,
			'noredirect' => false,
			'watch' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			],
			'unwatch' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
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
			'ignorewarnings' => false,
			'tags' => [
				ApiBase::PARAM_TYPE => 'tags',
				ApiBase::PARAM_ISMULTI => true,
			],
		];
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return [
			'action=move&from=Badtitle&to=Goodtitle&token=123ABC&' .
				'reason=Misspelled%20title&movetalk=&noredirect='
				=> 'apihelp-move-example-move',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Move';
	}
}
