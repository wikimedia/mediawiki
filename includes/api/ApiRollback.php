<?php
/**
 * Copyright © 2007 Roan Kattouw <roan.kattouw@gmail.com>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\RollbackPageFactory;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\UserIdentity;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use Profiler;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @ingroup API
 */
class ApiRollback extends ApiBase {

	use ApiWatchlistTrait;

	private RollbackPageFactory $rollbackPageFactory;

	public function __construct(
		ApiMain $mainModule,
		string $moduleName,
		RollbackPageFactory $rollbackPageFactory,
		WatchlistManager $watchlistManager,
		WatchedItemStoreInterface $watchedItemStore,
		UserOptionsLookup $userOptionsLookup
	) {
		parent::__construct( $mainModule, $moduleName );
		$this->rollbackPageFactory = $rollbackPageFactory;

		// Variables needed in ApiWatchlistTrait trait
		$this->watchlistExpiryEnabled = $this->getConfig()->get( MainConfigNames::WatchlistExpiry );
		$this->watchlistMaxDuration =
			$this->getConfig()->get( MainConfigNames::WatchlistExpiryMaxDuration );
		$this->watchlistManager = $watchlistManager;
		$this->watchedItemStore = $watchedItemStore;
		$this->userOptionsLookup = $userOptionsLookup;
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

		// If change tagging was requested, check that the user is allowed to tag,
		// and the tags are valid. TODO: move inside rollback command?
		if ( $params['tags'] ) {
			$tagStatus = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $this->getAuthority() );
			if ( !$tagStatus->isOK() ) {
				$this->dieStatus( $tagStatus );
			}
		}

		// @TODO: remove this hack once rollback uses POST (T88044)
		$fname = __METHOD__;
		$trxLimits = $this->getConfig()->get( MainConfigNames::TrxProfilerLimits );
		$trxProfiler = Profiler::instance()->getTransactionProfiler();
		$trxProfiler->redefineExpectations( $trxLimits['POST'], $fname );
		DeferredUpdates::addCallableUpdate( static function () use ( $trxProfiler, $trxLimits, $fname ) {
			$trxProfiler->redefineExpectations( $trxLimits['PostSend-POST'], $fname );
		} );

		$rollbackResult = $this->rollbackPageFactory
			->newRollbackPage( $titleObj, $this->getAuthority(), $this->getRbUser( $params ) )
			->setSummary( $params['summary'] )
			->markAsBot( $params['markbot'] )
			->setChangeTags( $params['tags'] )
			->rollbackIfAllowed();

		if ( !$rollbackResult->isGood() ) {
			$this->dieStatus( $rollbackResult );
		}

		$watch = $params['watchlist'] ?? 'preferences';
		$watchlistExpiry = $this->getExpiryFromParams( $params, $titleObj, $user, 'watchrollback-expiry' );

		// Watch pages
		$this->setWatch( $watch, $titleObj, $user, 'watchrollback', $watchlistExpiry );

		$details = $rollbackResult->getValue();
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
			'title' => null,
			'pageid' => [
				ParamValidator::PARAM_TYPE => 'integer'
			],
			'tags' => [
				ParamValidator::PARAM_TYPE => 'tags',
				ParamValidator::PARAM_ISMULTI => true,
			],
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
				UserDef::PARAM_RETURN_OBJECT => true,
				ParamValidator::PARAM_REQUIRED => true
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

	/** @inheritDoc */
	public function needsToken() {
		return 'rollback';
	}

	private function getRbUser( array $params ): UserIdentity {
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

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$title = Title::newMainPage()->getPrefixedText();
		$mp = rawurlencode( $title );

		return [
			"action=rollback&title={$mp}&user=Example&token=123ABC" =>
				'apihelp-rollback-example-simple',
			"action=rollback&title={$mp}&user=192.0.2.5&" .
				'token=123ABC&summary=Reverting%20vandalism&markbot=1' =>
				'apihelp-rollback-example-summary',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Rollback';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiRollback::class, 'ApiRollback' );
