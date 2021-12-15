<?php

namespace MediaWiki\Rest\Handler;

use Config;
use InvalidArgumentException;
use ISearchResultSet;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\Entity\SearchResultPageIdentityValue;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Search\Entity\SearchResultThumbnail;
use RequestContext;
use SearchEngine;
use SearchEngineConfig;
use SearchEngineFactory;
use SearchResult;
use SearchSuggestion;
use Status;
use User;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * Handler class for Core REST API endpoint that handles basic search
 */
class SearchHandler extends Handler {

	/** @var PermissionManager */
	private $permissionManager;

	/** @var SearchEngineFactory */
	private $searchEngineFactory;

	/** @var SearchEngineConfig */
	private $searchEngineConfig;

	/** @var User */
	private $user;

	/**
	 * Search page body and titles.
	 */
	public const FULLTEXT_MODE = 'fulltext';

	/**
	 * Search title completion matches.
	 */
	public const COMPLETION_MODE = 'completion';

	/**
	 * Supported modes
	 */
	private const SUPPORTED_MODES = [ self::FULLTEXT_MODE, self::COMPLETION_MODE ];

	/**
	 * @var string
	 */
	private $mode = null;

	/** Limit results to 50 pages per default */
	private const LIMIT = 50;

	/** Hard limit results to 100 pages */
	private const MAX_LIMIT = 100;

	/** Default to first page */
	private const OFFSET = 0;

	/**
	 * Expiry time for use as max-age value in the cache-control header
	 * of completion search responses.
	 * @see $wgSearchSuggestCacheExpiry
	 * @var int|null
	 */
	private $completionCacheExpiry;

	/**
	 * @param Config $config
	 * @param PermissionManager $permissionManager
	 * @param SearchEngineFactory $searchEngineFactory
	 * @param SearchEngineConfig $searchEngineConfig
	 */
	public function __construct(
		Config $config,
		PermissionManager $permissionManager,
		SearchEngineFactory $searchEngineFactory,
		SearchEngineConfig $searchEngineConfig
	) {
		$this->permissionManager = $permissionManager;
		$this->searchEngineFactory = $searchEngineFactory;
		$this->searchEngineConfig = $searchEngineConfig;

		// @todo Inject this, when there is a good way to do that, see T239753
		$this->user = RequestContext::getMain()->getUser();

		// @todo Avoid injecting the entire config, see T246377
		$this->completionCacheExpiry = $config->get( 'SearchSuggestCacheExpiry' );
	}

	protected function postInitSetup() {
		$this->mode = $this->getConfig()['mode'] ?? self::FULLTEXT_MODE;

		if ( !in_array( $this->mode, self::SUPPORTED_MODES ) ) {
			throw new InvalidArgumentException(
				"Unsupported search mode `{$this->mode}` configured. Supported modes: " .
				implode( ', ', self::SUPPORTED_MODES )
			);
		}
	}

	/**
	 * @return SearchEngine
	 */
	private function createSearchEngine() {
		$limit = $this->getValidatedParams()['limit'];

		$searchEngine = $this->searchEngineFactory->create();
		$searchEngine->setNamespaces( $this->searchEngineConfig->defaultNamespaces() );
		$searchEngine->setLimitOffset( $limit, self::OFFSET );
		return $searchEngine;
	}

	public function needsWriteAccess() {
		return false;
	}

	/**
	 * Get SearchResults when results are either SearchResultSet or Status objects
	 * @param ISearchResultSet|Status|null $results
	 * @return SearchResult[]
	 * @throws LocalizedHttpException
	 */
	private function getSearchResultsOrThrow( $results ) {
		if ( $results ) {
			if ( $results instanceof Status ) {
				$status = $results;
				if ( !$status->isOK() ) {
					list( $error ) = $status->splitByErrorType();
					if ( $error->getErrors() ) { // Only throw for errors, suppress warnings (for now)
						$errorMessages = $error->getMessage();
						throw new LocalizedHttpException(
							new MessageValue( "rest-search-error", [ $errorMessages->getKey() ] )
						);
					}
				}
				$statusValue = $status->getValue();
				if ( $statusValue instanceof ISearchResultSet ) {
					return $statusValue->extractResults();
				}
			} else {
				return $results->extractResults();
			}
		}
		return [];
	}

	/**
	 * Execute search and return info about pages for further processing.
	 *
	 * @param SearchEngine $searchEngine
	 * @return array[]
	 * @throws LocalizedHttpException
	 */
	private function doSearch( $searchEngine ) {
		$query = $this->getValidatedParams()['q'];

		if ( $this->mode == self::COMPLETION_MODE ) {
			$completionSearch = $searchEngine->completionSearchWithVariants( $query );
			return $this->buildPageInfosFromSuggestions( $completionSearch->getSuggestions() );
		} else {
			$titleSearch = $searchEngine->searchTitle( $query );
			$textSearch = $searchEngine->searchText( $query );

			$titleSearchResults = $this->getSearchResultsOrThrow( $titleSearch );
			$textSearchResults = $this->getSearchResultsOrThrow( $textSearch );

			$mergedResults = array_merge( $titleSearchResults, $textSearchResults );
			return $this->buildPageInfosFromSearchResults( $mergedResults );
		}
	}

	/**
	 * Remove duplicate pages and turn suggestions into array with
	 * information needed for further processing:
	 * pageId => [ $title, $suggestion, null ]
	 *
	 * @param SearchSuggestion[] $suggestions
	 *
	 * @return array[] of pageId => [ $title, $suggestion, null ]
	 */
	private function buildPageInfosFromSuggestions( array $suggestions ): array {
		$pageInfos = [];

		foreach ( $suggestions as $sugg ) {
			$title = $sugg->getSuggestedTitle();
			if ( $title && $title->exists() ) {
				$pageID = $title->getArticleID();
				if ( !isset( $pageInfos[$pageID] ) &&
					$this->permissionManager->quickUserCan( 'read', $this->user, $title )
				) {
					$pageInfos[ $pageID ] = [ $title, $sugg, null ];
				}
			}
		}
		return $pageInfos;
	}

	/**
	 * Remove duplicate pages and turn search results into array with
	 * information needed for further processing:
	 * pageId => [ $title, null, $result ]
	 *
	 * @param SearchResult[] $searchResults
	 *
	 * @return array[] of pageId => [ $title, null, $result ]
	 */
	private function buildPageInfosFromSearchResults( array $searchResults ): array {
		$pageInfos = [];

		foreach ( $searchResults as $result ) {
			if ( !$result->isBrokenTitle() && !$result->isMissingRevision() ) {
				$title = $result->getTitle();
				$pageID = $title->getArticleID();
				if ( !isset( $pageInfos[$pageID] ) &&
					$this->permissionManager->quickUserCan( 'read', $this->user, $title )
				) {
					$pageInfos[$pageID] = [ $title, null, $result ];
				}
			}
		}
		return $pageInfos;
	}

	/**
	 * Turn array of page info into serializable array with common information about the page
	 *
	 * @param array[] $pageInfos
	 *
	 * @return array[] of pageId => [ $title, null, $result ]
	 */
	private function buildResultFromPageInfos( array $pageInfos ): array {
		return array_map( function ( $pageInfo ) {
			list( $title, $sugg, $result ) = $pageInfo;
			return [
				'id' => $title->getArticleID(),
				'key' => $title->getPrefixedDBkey(),
				'title' => $title->getPrefixedText(),
				'excerpt' => ( $sugg ? $sugg->getText() : $result->getTextSnippet() ) ?: null,
			];
		},
		$pageInfos );
	}

	/**
	 * Converts SearchResultThumbnail object into serializable array
	 *
	 * @param SearchResultThumbnail|null $thumbnail
	 *
	 * @return array|null
	 */
	private function serializeThumbnail( ?SearchResultThumbnail $thumbnail ) : ?array {
		if ( $thumbnail == null ) {
			return null;
		}

		return [
			'mimetype' => $thumbnail->getMimeType(),
			'size' => $thumbnail->getSize(),
			'width' => $thumbnail->getWidth(),
			'height' => $thumbnail->getHeight(),
			'duration' => $thumbnail->getDuration(),
			'url' => $thumbnail->getUrl(),
		];
	}

	/**
	 * Turn page info into serializable array with description field for the page.
	 *
	 * The information about description should be provided by extension by implementing
	 * 'SearchResultProvideDescription' hook. Description is set to null if no extensions
	 * implement the hook.
	 * @param array $pageIdentities
	 *
	 * @return array
	 */
	private function buildDescriptionsFromPageIdentities( array $pageIdentities ) {
		$descriptions = array_fill_keys( array_keys( $pageIdentities ), null );

		$this->getHookRunner()->onSearchResultProvideDescription( $pageIdentities, $descriptions );

		return array_map( function ( $description ) {
			return [ 'description' => $description ];
		}, $descriptions );
	}

	/**
	 * Turn page info into serializable array with thumbnail information for the page.
	 *
	 * The information about thumbnail should be provided by extension by implementing
	 * 'SearchResultProvideThumbnail' hook. Thumbnail is set to null if no extensions implement
	 * the hook.
	 *
	 * @param array $pageIdentities
	 *
	 * @return array
	 */
	private function buildThumbnailsFromPageIdentities( array $pageIdentities ) {
		$thumbnails = array_fill_keys( array_keys( $pageIdentities ), null );

		$this->getHookRunner()->onSearchResultProvideThumbnail( $pageIdentities, $thumbnails );

		return array_map( function ( $thumbnail ) {
			return [ 'thumbnail' => $this->serializeThumbnail( $thumbnail ) ];
		}, $thumbnails );
	}

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function execute() {
		$searchEngine = $this->createSearchEngine();
		$pageInfos = $this->doSearch( $searchEngine );
		$pageIdentities = array_map( function ( $pageInfo ) {
			list( $title ) = $pageInfo;
			return new SearchResultPageIdentityValue(
				$title->getArticleID(),
				$title->getNamespace(),
				$title->getDBkey()
			);
		}, $pageInfos );

		$result = array_map( "array_merge",
			$this->buildResultFromPageInfos( $pageInfos ),
			$this->buildDescriptionsFromPageIdentities( $pageIdentities ),
			$this->buildThumbnailsFromPageIdentities( $pageIdentities )
		);
		$response = $this->getResponseFactory()->createJson( [ 'pages' => $result ] );

		if ( $this->mode === self::COMPLETION_MODE && $this->completionCacheExpiry ) {
			// Type-ahead completion matches should be cached by the client and
			// in the CDN, especially for short prefixes.
			// See also $wgSearchSuggestCacheExpiry and ApiOpenSearch
			 if ( $this->permissionManager->isEveryoneAllowed( 'read' ) ) {
				$response->setHeader( 'Cache-Control', 'public, max-age=' . $this->completionCacheExpiry );
			 } else {
				 $response->setHeader( 'Cache-Control', 'no-store, max-age=0' );
			 }
		}

		return $response;
	}

	public function getParamSettings() {
		return [
			'q' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'limit' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => self::LIMIT,
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => self::MAX_LIMIT,
			],
		];
	}
}
