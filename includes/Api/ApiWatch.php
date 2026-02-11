<?php
/**
 * Copyright © 2008 Yuri Astrakhan "<Firstname><Lastname>@gmail.com",
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\User;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistLabel;
use MediaWiki\Watchlist\WatchlistLabelStore;
use MediaWiki\Watchlist\WatchlistManager;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;

/**
 * API module to allow users to watch a page
 *
 * @ingroup API
 */
class ApiWatch extends ApiBase {
	/** @var ApiPageSet|null */
	private $mPageSet = null;

	/** @var bool Whether watchlist expiries are enabled. */
	private $expiryEnabled;

	/** @var string Relative maximum expiry. */
	private $maxDuration;

	/** @var bool Whether watchlist labels are enabled. */
	private $labelsEnabled;

	public function __construct(
		ApiMain $mainModule,
		string $moduleName,
		private readonly WatchlistManager $watchlistManager,
		private readonly TitleFormatter $titleFormatter,
		private readonly WatchlistLabelStore $watchlistLabelStore,
		private readonly WatchedItemStoreInterface $watchedItemStore,
		private readonly NamespaceInfo $namespaceInfo,
	) {
		parent::__construct( $mainModule, $moduleName );

		$this->expiryEnabled = $this->getConfig()->get( MainConfigNames::WatchlistExpiry );
		$this->maxDuration = $this->getConfig()->get( MainConfigNames::WatchlistExpiryMaxDuration );
		$this->labelsEnabled = $this->getConfig()->get( MainConfigNames::EnableWatchlistLabels );
	}

	public function execute() {
		$user = $this->getUser();
		if ( !$user->isRegistered()
			|| ( $user->isTemp() && !$user->isAllowed( 'editmywatchlist' ) )
		) {
			$this->dieWithError( 'watchlistanontext', 'notloggedin' );
		}

		$this->checkUserRightsAny( 'editmywatchlist' );

		$params = $this->extractRequestParams();

		$continuationManager = new ApiContinuationManager( $this, [], [] );
		$this->setContinuationManager( $continuationManager );

		// Validate labels
		$validLabels = [];
		$labelError = null;
		if ( isset( $params['labels'] ) && $params['labels'] ) {
			$validationResult = $this->validateLabels( $user, $params['labels'] );
			$validLabels = $validationResult['labels'];
			$labelError = $validationResult['error'];
		}

		$pageSet = $this->getPageSet();
		// by default we use pageset to extract the page to work on.
		// title is still supported for backward compatibility
		if ( !isset( $params['title'] ) ) {
			$pageSet->execute();
			$res = $pageSet->getInvalidTitlesAndRevisions( [
				'invalidTitles',
				'special',
				'missingIds',
				'missingRevIds',
				'interwikiTitles'
			] );

			foreach ( $pageSet->getMissingPages() as $page ) {
				$r = $this->watchTitle( $page, $user, $params, false, $validLabels, $labelError );
				$r['missing'] = true;
				$res[] = $r;
			}

			foreach ( $pageSet->getGoodPages() as $page ) {
				$r = $this->watchTitle( $page, $user, $params, false, $validLabels, $labelError );
				$res[] = $r;
			}
			ApiResult::setIndexedTagName( $res, 'w' );
		} else {
			// dont allow use of old title parameter with new pageset parameters.
			$extraParams = array_keys( array_filter( $pageSet->extractRequestParams(), static function ( $x ) {
				return $x !== null && $x !== false;
			} ) );

			if ( $extraParams ) {
				$this->dieWithError(
					[
						'apierror-invalidparammix-cannotusewith',
						$this->encodeParamName( 'title' ),
						$pageSet->encodeParamName( $extraParams[0] )
					],
					'invalidparammix'
				);
			}

			$title = Title::newFromText( $params['title'] );
			if ( !$title || !$this->watchlistManager->isWatchable( $title ) ) {
				$this->dieWithError( [ 'invalidtitle', $params['title'] ] );
			}
			$res = $this->watchTitle( $title, $user, $params, true, $validLabels, $labelError );
		}
		$this->getResult()->addValue( null, $this->getModuleName(), $res );

		$this->setContinuationManager( null );
		$continuationManager->setContinuationIntoResult( $this->getResult() );
	}

	private function watchTitle( PageIdentity $page, User $user, array $params,
		bool $compatibilityMode = false,
		array $validLabels = [],
		?array $labelError = null
	): array {
		$res = [ 'title' => $this->titleFormatter->getPrefixedText( $page ), 'ns' => $page->getNamespace() ];

		if ( !$this->watchlistManager->isWatchable( $page ) ) {
			$res['watchable'] = 0;
			return $res;
		}

		if ( $params['unwatch'] ) {
			$status = $this->watchlistManager->removeWatch( $user, $page );
			$res['unwatched'] = $status->isOK();
		} else {
			$expiry = null;

			// NOTE: If an expiry parameter isn't given, any existing expiries remain unchanged.
			if ( $this->expiryEnabled && isset( $params['expiry'] ) ) {
				$expiry = $params['expiry'];
				$res['expiry'] = ApiResult::formatExpiry( $expiry );
			}

			$status = $this->watchlistManager->addWatch( $user, $page, $expiry );
			$res['watched'] = $status->isOK();

			// Apply labels if provided and watching was successful
			if ( $status->isOK() && $validLabels ) {
				$this->applyLabelsToWatchedPage( $user, $page, $validLabels, $compatibilityMode, $res );
				// Add error to response if there were invalid labels but we applied the valid ones
				if ( $labelError ) {
					if ( !isset( $res['errors'] ) ) {
						$res['errors'] = [];
					}
					$res['errors'][] = $labelError;
				}
			} elseif ( $status->isOK() && $labelError ) {
				// Add label error to response if labels were requested but had an error
				$res['errors'] = [ $labelError ];
			}
		}

		if ( !$status->isOK() ) {
			if ( $compatibilityMode ) {
				$this->dieStatus( $status );
			}
			$res['errors'] = $this->getErrorFormatter()->arrayFromStatus( $status, 'error' );
			$res['warnings'] = $this->getErrorFormatter()->arrayFromStatus( $status, 'warning' );
			if ( !$res['warnings'] ) {
				unset( $res['warnings'] );
			}
		}

		return $res;
	}

	/**
	 * Apply watchlist labels to a newly watched page.
	 *
	 * Applies labels to both the page and its talk page, replacing any existing labels.
	 *
	 * @param User $user The user applying the labels
	 * @param PageIdentity $page The page being watched
	 * @param array $validLabels The pre-validated label objects to apply
	 * @param bool $compatibilityMode Whether to use legacy error reporting via dieWithError
	 * @param array &$res The response array to populate with label data or errors
	 */
	private function applyLabelsToWatchedPage(
		User $user,
		PageIdentity $page,
		array $validLabels,
		bool $compatibilityMode,
		array &$res
	): void {
		if ( !$this->labelsEnabled ) {
			$res['errors'] = [ $this->getErrorFormatter()->formatMessage(
				[ 'apierror-labels-disabled', 'labels-disabled' ]
			) ];
			if ( $compatibilityMode ) {
				$this->dieWithError( 'apierror-labels-disabled', 'labels-disabled' );
			}
			return;
		}

		$title = Title::newFromPageIdentity( $page );
		$pagesToWatch = [ $page ];

		// Also watch the talk page if this page can have one
		if ( $this->namespaceInfo->canHaveTalkPage( $title ) ) {
			$talkPageTarget = $this->namespaceInfo->getTalkPage( $title );
			// Convert LinkTarget to PageReferenceValue for consistency
			$talkPage = PageReferenceValue::localReference(
				$talkPageTarget->getNamespace(),
				$talkPageTarget->getDBkey()
			);
			$pagesToWatch[] = $talkPage;
		}

		// Get existing labels to remove
		foreach ( $pagesToWatch as $pageToWatch ) {
			$watchedItem = $this->watchedItemStore->loadWatchedItem( $user, $pageToWatch );
			if ( $watchedItem ) {
				$existingLabels = $watchedItem->getLabels();
				// Remove all existing labels before adding new ones
				if ( $existingLabels ) {
					$this->watchedItemStore->removeLabels( $user, [ $pageToWatch ], $existingLabels );
				}
			}
		}

		// Add the new labels
		if ( $validLabels ) {
			$this->watchedItemStore->addLabels( $user, $pagesToWatch, $validLabels );
			// Return the labels that we just saved
			$res['labels'] = array_map( static function ( WatchlistLabel $label ) {
				return [
					'id' => $label->getId(),
					'name' => $label->getName(),
				];
			}, $validLabels );
		}
	}

	/**
	 * Validate and retrieve label objects for the given label IDs.
	 *
	 * @param User $user The user whose labels to validate
	 * @param array $labelIds The label IDs to validate
	 * @return array An associative array with 'labels' (array of validated labels) and 'error' (null or error message)
	 */
	private function validateLabels( User $user, array $labelIds ): array {
		// Check if labels are enabled
		if ( !$this->labelsEnabled ) {
			return [
				'labels' => [],
				'error' => $this->getErrorFormatter()->formatMessage(
					[ 'apierror-labels-disabled', 'labels-disabled' ]
				)
			];
		}

		$validLabels = $this->watchlistLabelStore->loadByIds( $user, $labelIds );
		$hasError = count( $labelIds ) !== count( $validLabels );

		return [
			'labels' => $validLabels,
			'error' => $hasError ? $this->getErrorFormatter()->formatMessage(
				[ 'apierror-invalid-label-id', 'invalid-label-id' ]
			) : null
		];
	}

	/**
	 * Get a cached instance of an ApiPageSet object
	 * @return ApiPageSet
	 */
	private function getPageSet() {
		$this->mPageSet ??= new ApiPageSet( $this );

		return $this->mPageSet;
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
	public function needsToken() {
		return 'watch';
	}

	/** @inheritDoc */
	public function getAllowedParams( $flags = 0 ) {
		$result = [
			'title' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'expiry' => [
				ParamValidator::PARAM_TYPE => 'expiry',
				ExpiryDef::PARAM_MAX => $this->maxDuration,
				ExpiryDef::PARAM_USE_MAX => true,
			],
			'labels' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG => 'apihelp-watch-param-labels',
			],
			'unwatch' => false,
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];

		// If expiry is not enabled, don't accept the parameter.
		if ( !$this->expiryEnabled ) {
			unset( $result['expiry'] );
		}

		if ( $flags ) {
			$result += $this->getPageSet()->getFinalParams( $flags );
		}

		return $result;
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$title = Title::newMainPage()->getPrefixedText();
		$mp = rawurlencode( $title );

		// Logically expiry example should go before unwatch examples.
		$examples = [
			"action=watch&titles={$mp}&token=123ABC"
				=> 'apihelp-watch-example-watch',
		];
		if ( $this->expiryEnabled ) {
			$examples["action=watch&titles={$mp}|Foo|Bar&expiry=1%20month&token=123ABC"]
				= 'apihelp-watch-example-watch-expiry';
		}

		// Add example with labels
		$examples["action=watch&titles={$mp}&labels=1%7C2&token=123ABC"]
			= 'apihelp-watch-example-watch-labels';

		return array_merge( $examples, [
			"action=watch&titles={$mp}&unwatch=&token=123ABC"
				=> 'apihelp-watch-example-unwatch',
			'action=watch&generator=allpages&gapnamespace=0&token=123ABC'
				=> 'apihelp-watch-example-generator',
		] );
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Watch';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiWatch::class, 'ApiWatch' );
