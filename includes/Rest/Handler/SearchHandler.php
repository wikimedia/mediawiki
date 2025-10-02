<?php

namespace MediaWiki\Rest\Handler;

use InvalidArgumentException;
use ISearchResultSet;
use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Config\Config;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageStore;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Handler\Helper\RestStatusTrait;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Search\Entity\SearchResultThumbnail;
use MediaWiki\Search\SearchResultThumbnailProvider;
use MediaWiki\Title\TitleFormatter;
use SearchEngine;
use SearchEngineConfig;
use SearchEngineFactory;
use SearchResult;
use SearchSuggestion;
use StatusValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * Handler class for Core REST API endpoint that handles basic search
 */
class SearchHandler extends Handler {
	use RestStatusTrait;

	private SearchEngineFactory $searchEngineFactory;
	private SearchEngineConfig $searchEngineConfig;
	private SearchResultThumbnailProvider $searchResultThumbnailProvider;
	private PermissionManager $permissionManager;
	private RedirectLookup $redirectLookup;
	private PageStore $pageStore;
	private TitleFormatter $titleFormatter;

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

	/** Limit results to 50 pages by default */
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

	public function __construct(
		Config $config,
		SearchEngineFactory $searchEngineFactory,
		SearchEngineConfig $searchEngineConfig,
		SearchResultThumbnailProvider $searchResultThumbnailProvider,
		PermissionManager $permissionManager,
		RedirectLookup $redirectLookup,
		PageStore $pageStore,
		TitleFormatter $titleFormatter
	) {
		$this->searchEngineFactory = $searchEngineFactory;
		$this->searchEngineConfig = $searchEngineConfig;
		$this->searchResultThumbnailProvider = $searchResultThumbnailProvider;
		$this->permissionManager = $permissionManager;
		$this->redirectLookup = $redirectLookup;
		$this->pageStore = $pageStore;
		$this->titleFormatter = $titleFormatter;

		// @todo Avoid injecting the entire config, see T246377
		$this->completionCacheExpiry = $config->get( MainConfigNames::SearchSuggestCacheExpiry );
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
	 * @param ISearchResultSet|StatusValue|null $results
	 * @return SearchResult[]
	 * @throws LocalizedHttpException
	 */
	private function getSearchResultsOrThrow( $results ) {
		if ( $results ) {
			if ( $results instanceof StatusValue ) {
				$status = $results;
				if ( !$status->isOK() ) {
					if ( $status->getMessages( 'error' ) ) { // Only throw for errors, suppress warnings (for now)
						$this->throwExceptionForStatus( $status, 'rest-search-error', 500 );
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
			return $this->buildPageObjects( $completionSearch->getSuggestions() );
		} else {
			$titleSearch = $searchEngine->searchTitle( $query );
			$textSearch = $searchEngine->searchText( $query );

			$titleSearchResults = $this->getSearchResultsOrThrow( $titleSearch );
			$textSearchResults = $this->getSearchResultsOrThrow( $textSearch );

			$mergedResults = array_merge( $titleSearchResults, $textSearchResults );
			return $this->buildPageObjects( $mergedResults );
		}
	}

	/**
	 * Build an array of pageInfo objects.
	 * @param SearchSuggestion[]|SearchResult[] $searchResponse
	 *
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-return array{int:array{pageIdentity:PageIdentity,suggestion:?SearchSuggestion,result:?SearchResult,redirect:?PageIdentity}} $pageInfos
	 * @return array Associative array mapping pageID to pageInfo objects:
	 *   - pageIdentity: PageIdentity of page to return as the match
	 *   - suggestion: SearchSuggestion or null if $searchResponse is SearchResults[]
	 *   - result: SearchResult or null if $searchResponse is SearchSuggestions[]
	 *   - redirect: PageIdentity or null if the SearchResult|SearchSuggestion was not a redirect
	 */
	private function buildPageObjects( array $searchResponse ): array {
		$pageInfos = [];
		foreach ( $searchResponse as $response ) {
			$isSearchResult = $response instanceof SearchResult;
			if ( $isSearchResult ) {
				if ( $response->isBrokenTitle() || $response->isMissingRevision() ) {
					continue;
				}
				$title = $response->getTitle();
			} else {
				$title = $response->getSuggestedTitle();
			}
			$pageObj = $this->buildSinglePage( $title, $response );
			if ( $pageObj ) {
				$pageNsAndID = CacheKeyHelper::getKeyForPage( $pageObj['pageIdentity'] );
				// This handles the edge case where we have both the redirect source and redirect target page come back
				// in our search results. In such event, we prefer (and thus replace) with  the redirect target page.
				if ( isset( $pageInfos[$pageNsAndID] ) ) {
					if ( $pageInfos[$pageNsAndID]['redirect'] !== null ) {
						$pageInfos[$pageNsAndID]['result'] = $isSearchResult ? $response : null;
						$pageInfos[$pageNsAndID]['suggestion'] = $isSearchResult ? null : $response;
					}
					continue;
				}
				$pageInfos[$pageNsAndID] = $pageObj;
			}
		}
		return $pageInfos;
	}

	/**
	 * Build one pageInfo object from either a SearchResult or SearchSuggestion.
	 * @param PageIdentity $title
	 * @param SearchResult|SearchSuggestion $result
	 *
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-return (false|array{pageIdentity:PageIdentity,suggestion:?SearchSuggestion,result:?SearchResult,redirect:?PageIdentity}) $pageInfos
	 * @return bool|array Objects representing a given page:
	 *   - pageIdentity: PageIdentity of page to return as the match
	 *   - suggestion: SearchSuggestion or null if $searchResponse is SearchResults
	 *   - result: SearchResult or null if $searchResponse is SearchSuggestions
	 *   - redirect: PageIdentity|null depending on if the SearchResult|SearchSuggestion was a redirect
	 */
	private function buildSinglePage( $title, $result ) {
		$redirectTarget = $title->canExist() ? $this->redirectLookup->getRedirectTarget( $title ) : null;
		// Our page has a redirect that is not in a virtual namespace and is not an interwiki link.
		// See T301346, T303352
		if ( $redirectTarget && $redirectTarget->getNamespace() > -1 && !$redirectTarget->isExternal() ) {
			$redirectSource = $title;
			$title = $this->pageStore->getPageForLink( $redirectTarget );
		} else {
			$redirectSource = null;
		}
		if ( !$title || !$this->getAuthority()->probablyCan( 'read', $title ) ) {
			return false;
		}
		return [
			'pageIdentity' => $title,
			'suggestion' => $result instanceof SearchSuggestion ? $result : null,
			'result' => $result instanceof SearchResult ? $result : null,
			'redirect' => $redirectSource
		];
	}

	/**
	 * Turn array of page info into serializable array with common information about the page
	 * @param array $pageInfos Page Info objects
	 * @param array $thumbsAndDesc Associative array mapping pageId to array of description and thumbnail
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-param array<int,array{pageIdentity:PageIdentity,suggestion:SearchSuggestion,result:SearchResult,redirect:?PageIdentity}> $pageInfos
	 * @phan-param array<int,array{description:array,thumbnail:array}> $thumbsAndDesc
	 *
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-return array<int,array{id:int,key:string,title:string,excerpt:?string,matched_title:?string, description:?array, thumbnail:?array}> $pages
	 * @return array[] of [ id, key, title, excerpt, matched_title ]
	 */
	private function buildResultFromPageInfos( array $pageInfos, array $thumbsAndDesc ): array {
		$pages = [];
		foreach ( $pageInfos as $pageInfo ) {
			[
				'pageIdentity' => $page,
				'suggestion' => $sugg,
				'result' => $result,
				'redirect' => $redirect
			] = $pageInfo;
			$excerpt = $sugg ? $sugg->getText() : $result->getTextSnippet();
			$id = ( $page instanceof PageIdentity && $page->canExist() ) ? $page->getId() : 0;
			$pages[] = [
				'id' => $id,
				'key' => $this->titleFormatter->getPrefixedDBkey( $page ),
				'title' => $this->titleFormatter->getPrefixedText( $page ),
				'excerpt' => $excerpt ?: null,
				'matched_title' => $redirect ? $this->titleFormatter->getPrefixedText( $redirect ) : null,
				'description' => $id > 0 ? $thumbsAndDesc[$id]['description'] : null,
				'thumbnail' => $id > 0 ? $thumbsAndDesc[$id]['thumbnail'] : null,
			];
		}
		return $pages;
	}

	/**
	 * Converts SearchResultThumbnail object into serializable array
	 *
	 * @param SearchResultThumbnail|null $thumbnail
	 *
	 * @return array|null
	 */
	private function serializeThumbnail( ?SearchResultThumbnail $thumbnail ): ?array {
		if ( $thumbnail == null ) {
			return null;
		}

		return [
			'mimetype' => $thumbnail->getMimeType(),
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
	 * @param PageIdentity[] $pageIdentities
	 *
	 * @return array
	 */
	private function buildDescriptionsFromPageIdentities( array $pageIdentities ) {
		$descriptions = array_fill_keys( array_keys( $pageIdentities ), null );

		$this->getHookRunner()->onSearchResultProvideDescription( $pageIdentities, $descriptions );

		return array_map( static function ( $description ) {
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
	 * @param PageIdentity[] $pageIdentities
	 *
	 * @return array
	 */
	private function buildThumbnailsFromPageIdentities( array $pageIdentities ) {
		$thumbnails = $this->searchResultThumbnailProvider->getThumbnails( $pageIdentities );
		$thumbnails += array_fill_keys( array_keys( $pageIdentities ), null );

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

		// We can only pass validated "real" PageIdentities to our hook handlers below
		$pageIdentities = array_reduce(
			array_values( $pageInfos ),
			static function ( $realPages, $item ) {
				$page = $item['pageIdentity'];
				if ( $page instanceof PageIdentity && $page->exists() ) {
					$realPages[$item['pageIdentity']->getId()] = $item['pageIdentity'];
				}
				return $realPages;
			}, []
		);

		$descriptions = $this->buildDescriptionsFromPageIdentities( $pageIdentities );
		$thumbs = $this->buildThumbnailsFromPageIdentities( $pageIdentities );

		$thumbsAndDescriptions = [];
		foreach ( $descriptions as $pageId => $description ) {
			$thumbsAndDescriptions[$pageId] = $description + $thumbs[$pageId];
		}

		$result = $this->buildResultFromPageInfos( $pageInfos, $thumbsAndDescriptions );

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

	public function getResponseBodySchemaFileName( string $method ): ?string {
		return 'includes/Rest/Handler/Schema/SearchResults.json';
	}
}
