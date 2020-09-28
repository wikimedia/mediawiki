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

use MediaWiki\MediaWikiServices;

/**
 * API Module to move pages
 * @ingroup API
 */
class ApiMove extends ApiBase {

	use ApiWatchlistTrait;

	public function __construct( ApiMain $mainModule, $moduleName, $modulePrefix = '' ) {
		parent::__construct( $mainModule, $moduleName, $modulePrefix );

		$this->watchlistExpiryEnabled = $this->getConfig()->get( 'WatchlistExpiry' );
		$this->watchlistMaxDuration = $this->getConfig()->get( 'WatchlistExpiryMaxDuration' );
	}

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
		$toTalk = $toTitle->getTalkPageIfDefined();

		$repoGroup = MediaWikiServices::getInstance()->getRepoGroup();
		if ( $toTitle->getNamespace() == NS_FILE
			&& !$repoGroup->getLocalRepo()->findFile( $toTitle )
			&& $repoGroup->findFile( $toTitle )
		) {
			if ( !$params['ignorewarnings'] &&
				 $this->getPermissionManager()->userHasRight( $user, 'reupload-shared' ) ) {
				$this->dieWithError( 'apierror-fileexists-sharedrepo-perm' );
			} elseif ( !$this->getPermissionManager()->userHasRight( $user, 'reupload-shared' ) ) {
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
			$user->spreadAnyEditBlock();
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
		}
		$watchlistExpiry = $this->getExpiryFromParams( $params );

		// Watch pages
		$this->setWatch( $watch, $fromTitle, $user, 'watchmoves', $watchlistExpiry );
		$this->setWatch( $watch, $toTitle, $user, 'watchmoves', $watchlistExpiry );

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
		$mp = MediaWikiServices::getInstance()->getMovePageFactory()->newMovePage( $from, $to );
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
		if ( !$this->getPermissionManager()->userHasRight( $user, 'suppressredirect' ) ) {
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

		$mp = new MovePage( $fromTitle, $toTitle );
		$result =
			$mp->moveSubpagesIfAllowed( $this->getUser(), $reason, !$noredirect, $changeTags );
		if ( !$result->isOK() ) {
			// This means the whole thing failed
			return [ 'errors' => $this->getErrorFormatter()->arrayFromStatus( $result ) ];
		}

		// At least some pages could be moved
		// Report each of them separately
		foreach ( $result->getValue() as $oldTitle => $status ) {
			/** @var Status $status */
			$r = [ 'from' => $oldTitle ];
			if ( $status->isOK() ) {
				$r['to'] = $status->getValue();
			} else {
				$r['errors'] = $this->getErrorFormatter()->arrayFromStatus( $status );
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
		$params = [
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
		];

		// Params appear in the docs in the order they are defined,
		// which is why this is here and not at the bottom.
		$params += $this->getWatchlistParams();

		return $params + [
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
