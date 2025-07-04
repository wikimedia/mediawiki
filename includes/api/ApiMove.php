<?php
/**
 * Copyright © 2007 Roan Kattouw <roan.kattouw@gmail.com>
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

namespace MediaWiki\Api;

use LogicException;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * API Module to move pages
 * @ingroup API
 */
class ApiMove extends ApiBase {

	use ApiWatchlistTrait;

	private MovePageFactory $movePageFactory;
	private RepoGroup $repoGroup;

	public function __construct(
		ApiMain $mainModule,
		string $moduleName,
		MovePageFactory $movePageFactory,
		RepoGroup $repoGroup,
		WatchlistManager $watchlistManager,
		WatchedItemStoreInterface $watchedItemStore,
		UserOptionsLookup $userOptionsLookup
	) {
		parent::__construct( $mainModule, $moduleName );

		$this->movePageFactory = $movePageFactory;
		$this->repoGroup = $repoGroup;

		// Variables needed in ApiWatchlistTrait trait
		$this->watchlistExpiryEnabled = $this->getConfig()->get( MainConfigNames::WatchlistExpiry );
		$this->watchlistMaxDuration =
			$this->getConfig()->get( MainConfigNames::WatchlistExpiryMaxDuration );
		$this->watchlistManager = $watchlistManager;
		$this->watchedItemStore = $watchedItemStore;
		$this->userOptionsLookup = $userOptionsLookup;
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
		} else {
			throw new LogicException( 'Unreachable due to requireOnlyOneParameter' );
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

		if ( $toTitle->getNamespace() === NS_FILE
			&& !$this->repoGroup->getLocalRepo()->findFile( $toTitle )
			&& $this->repoGroup->findFile( $toTitle )
		) {
			if ( !$params['ignorewarnings'] &&
				$this->getAuthority()->isAllowed( 'reupload-shared' ) ) {
				$this->dieWithError( 'apierror-fileexists-sharedrepo-perm' );
			} elseif ( !$this->getAuthority()->isAllowed( 'reupload-shared' ) ) {
				$this->dieWithError( 'apierror-cantoverwrite-sharedfile' );
			}
		}

		// Move the page
		$toTitleExists = $toTitle->exists();
		$mp = $this->movePageFactory->newMovePage( $fromTitle, $toTitle );
		$status = $mp->moveIfAllowed(
			$this->getAuthority(),
			$params['reason'],
			!$params['noredirect'],
			$params['tags'] ?: []
		);
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
		$r['redirectcreated'] = $fromTitle->exists();

		$r['moveoverredirect'] = $toTitleExists;

		// Move the talk page
		if ( $params['movetalk'] && $toTalk && $fromTalk->exists() && !$fromTitle->isTalkPage() ) {
			$toTalkExists = $toTalk->exists();
			$mp = $this->movePageFactory->newMovePage( $fromTalk, $toTalk );
			$status = $mp->moveIfAllowed(
				$this->getAuthority(),
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

			if ( $params['movetalk'] && $toTalk ) {
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

		$watch = $params['watchlist'] ?? 'preferences';
		$watchlistExpiryFrom = $this->getExpiryFromParams( $params, $fromTitle, $user );
		$watchlistExpiryTo = $this->getExpiryFromParams( $params, $toTitle, $user );

		// Watch pages
		$this->setWatch( $watch, $fromTitle, $user, 'watchmoves', $watchlistExpiryFrom );
		$this->setWatch( $watch, $toTitle, $user, 'watchmoves', $watchlistExpiryTo );

		$result->addValue( null, $this->getModuleName(), $r );
	}

	/**
	 * @param Title $fromTitle
	 * @param Title $toTitle
	 * @param string $reason
	 * @param bool $noredirect
	 * @param string[] $changeTags Applied to the entry in the move log and redirect page revisions
	 * @return array
	 */
	public function moveSubpages( $fromTitle, $toTitle, $reason, $noredirect, $changeTags = [] ) {
		$retval = [];

		$mp = $this->movePageFactory->newMovePage( $fromTitle, $toTitle );
		$result =
			$mp->moveSubpagesIfAllowed( $this->getAuthority(), $reason, !$noredirect, $changeTags );
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

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/** @inheritDoc */
	public function isWriteMode() {
		return true;
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		$params = [
			'from' => null,
			'fromid' => [
				ParamValidator::PARAM_TYPE => 'integer'
			],
			'to' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true
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
				ParamValidator::PARAM_TYPE => 'tags',
				ParamValidator::PARAM_ISMULTI => true,
			],
		];
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'csrf';
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=move&from=Badtitle&to=Goodtitle&token=123ABC&' .
				'reason=Misspelled%20title&movetalk=&noredirect='
				=> 'apihelp-move-example-move',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Move';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiMove::class, 'ApiMove' );
