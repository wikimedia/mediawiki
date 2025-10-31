<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFormatter;

/**
 * API interface for page purging
 * @ingroup API
 */
class ApiPurge extends ApiBase {
	/** @var ApiPageSet|null */
	private $mPageSet = null;

	private WikiPageFactory $wikiPageFactory;
	private TitleFormatter $titleFormatter;

	public function __construct(
		ApiMain $mainModule,
		string $moduleName,
		WikiPageFactory $wikiPageFactory,
		TitleFormatter $titleFormatter
	) {
		parent::__construct( $mainModule, $moduleName );
		$this->wikiPageFactory = $wikiPageFactory;
		$this->titleFormatter = $titleFormatter;
	}

	/**
	 * Purges the cache of a page
	 */
	public function execute() {
		$authority = $this->getAuthority();

		// Fail early if the user is sitewide blocked.
		$block = $authority->getBlock();
		if ( $block && $block->isSitewide() ) {
			$this->dieBlocked( $block );
		}

		$params = $this->extractRequestParams();

		$continuationManager = new ApiContinuationManager( $this, [], [] );
		$this->setContinuationManager( $continuationManager );

		$forceLinkUpdate = $params['forcelinkupdate'];
		$forceRecursiveLinkUpdate = $params['forcerecursivelinkupdate'];
		$pageSet = $this->getPageSet();
		$pageSet->execute();

		$result = $pageSet->getInvalidTitlesAndRevisions();
		$userName = $authority->getUser()->getName();
		$now = wfTimestampNow();

		foreach ( $pageSet->getGoodPages() as $pageIdentity ) {
			$title = $this->titleFormatter->getPrefixedText( $pageIdentity );
			$r = [
				'ns' => $pageIdentity->getNamespace(),
				'title' => $title,
			];
			$page = $this->wikiPageFactory->newFromTitle( $pageIdentity );

			$purgeAuthStatus = PermissionStatus::newEmpty();
			if ( $authority->authorizeAction( 'purge', $purgeAuthStatus ) ) {
				// Directly purge and skip the UI part of purge()
				$page->doPurge();
				$r['purged'] = true;
			} else {
				if ( $purgeAuthStatus->isRateLimitExceeded() ) {
					$this->addWarning( 'apierror-ratelimited' );
				} else {
					$this->addWarning( Status::wrap( $purgeAuthStatus )->getMessage() );
				}
			}

			if ( $forceLinkUpdate || $forceRecursiveLinkUpdate ) {
				$linkpurgeAuthStatus = PermissionStatus::newEmpty();
				if ( $authority->authorizeAction( 'linkpurge', $linkpurgeAuthStatus ) ) {
					# Logging to better see expensive usage patterns
					if ( $forceRecursiveLinkUpdate ) {
						LoggerFactory::getInstance( 'RecursiveLinkPurge' )->info(
							"Recursive link purge enqueued for {title}",
							[
								'user' => $userName,
								'title' => $title
							]
						);
					}

					$page->updateParserCache( [
						'causeAction' => 'api-purge',
						'causeAgent' => $userName,
					] );
					$page->doSecondaryDataUpdates( [
						'recursive' => $forceRecursiveLinkUpdate,
						'causeAction' => 'api-purge',
						'causeAgent' => $userName,
						'defer' => DeferredUpdates::PRESEND,
						'freshness' => $now,
					] );
					$r['linkupdate'] = true;
				} else {
					if ( $linkpurgeAuthStatus->isRateLimitExceeded() ) {
						$this->addWarning( 'apierror-ratelimited' );
						$forceLinkUpdate = false;
						$forceRecursiveLinkUpdate = false;
					} else {
						$this->addWarning( Status::wrap( $linkpurgeAuthStatus )->getMessage() );
					}
				}
			}

			$result[] = $r;
		}
		$apiResult = $this->getResult();
		ApiResult::setIndexedTagName( $result, 'page' );
		$apiResult->addValue( null, $this->getModuleName(), $result );

		$values = $pageSet->getNormalizedTitlesAsResult( $apiResult );
		if ( $values ) {
			$apiResult->addValue( null, 'normalized', $values );
		}
		$values = $pageSet->getConvertedTitlesAsResult( $apiResult );
		if ( $values ) {
			$apiResult->addValue( null, 'converted', $values );
		}
		$values = $pageSet->getRedirectTitlesAsResult( $apiResult );
		if ( $values ) {
			$apiResult->addValue( null, 'redirects', $values );
		}

		$this->setContinuationManager( null );
		$continuationManager->setContinuationIntoResult( $apiResult );
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
	public function isWriteMode() {
		return true;
	}

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/** @inheritDoc */
	public function getAllowedParams( $flags = 0 ) {
		$result = [
			'forcelinkupdate' => false,
			'forcerecursivelinkupdate' => false,
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
		if ( $flags ) {
			$result += $this->getPageSet()->getFinalParams( $flags );
		}

		return $result;
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$title = Title::newMainPage()->getPrefixedText();
		$mp = rawurlencode( $title );

		return [
			"action=purge&titles={$mp}|API"
				=> 'apihelp-purge-example-simple',
			'action=purge&generator=allpages&gapnamespace=0&gaplimit=10'
				=> 'apihelp-purge-example-generator',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Purge';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiPurge::class, 'ApiPurge' );
