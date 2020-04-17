<?php

namespace MediaWiki\Rest\Handler;

use Config;
use InvalidArgumentException;
use ISearchResultSet;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
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

	public function init(
		Router $router,
		RequestInterface $request,
		array $config,
		ResponseFactory $responseFactory
	) {
		parent::init(
			$router,
			$request,
			$config,
			$responseFactory
		);

		$this->mode = $config['mode'] ?? self::FULLTEXT_MODE;

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
	 * Execute search and return results.
	 *
	 * @param SearchEngine $searchEngine
	 * @return SearchResult[]
	 * @throws LocalizedHttpException
	 */
	private function doSearch( $searchEngine ) {
		$query = $this->getValidatedParams()['q'];

		if ( $this->mode == self::COMPLETION_MODE ) {
			$completionSearch = $searchEngine->completionSearchWithVariants( $query );
			return $this->buildOutputFromSuggestions( $completionSearch->getSuggestions() );
		} else {
			$titleSearch = $searchEngine->searchTitle( $query );
			$textSearch = $searchEngine->searchText( $query );

			$titleSearchResults = $this->getSearchResultsOrThrow( $titleSearch );
			$textSearchResults = $this->getSearchResultsOrThrow( $textSearch );

			$mergedResults = array_merge( $titleSearchResults, $textSearchResults );
			return $this->buildOutputFromSearchResults( $mergedResults );
		}
	}

	/**
	 * Remove duplicate pages and turn results into response json objects
	 *
	 * @param SearchSuggestion[] $suggestions
	 *
	 * @return array page objects
	 */
	private function buildOutputFromSuggestions( array $suggestions ) {
		$pages = [];
		$foundPageIds = [];
		foreach ( $suggestions as $sugg ) {
			$title = $sugg->getSuggestedTitle();
			if ( $title && $title->exists() ) {
				$pageID = $title->getArticleID();
				if ( !isset( $foundPageIds[$pageID] ) &&
					$this->permissionManager->quickUserCan( 'read', $this->user, $title )
				) {
					$page = [
						'id' => $pageID,
						'key' => $title->getPrefixedDBkey(),
						'title' => $title->getPrefixedText(),
						'excerpt' => $sugg->getText() ?: null,
					];
					$pages[] = $page;
					$foundPageIds[$pageID] = true;
				}
			}
		}
		return $pages;
	}

	/**
	 * Remove duplicate pages and turn results into response json objects
	 *
	 * @param SearchResult[] $searchResults
	 *
	 * @return array page objects
	 */
	private function buildOutputFromSearchResults( array $searchResults ) {
		$pages = [];
		$foundPageIds = [];
		foreach ( $searchResults as $result ) {
			if ( !$result->isBrokenTitle() && !$result->isMissingRevision() ) {
				$title = $result->getTitle();
				$pageID = $title->getArticleID();
				if ( !isset( $foundPageIds[$pageID] ) &&
					$this->permissionManager->quickUserCan( 'read', $this->user, $title )
				) {
					$page = [
						'id' => $pageID,
						'key' => $title->getPrefixedDBkey(),
						'title' => $title->getPrefixedText(),
						'excerpt' => $result->getTextSnippet() ?: null,
					];
					$pages[] = $page;
					$foundPageIds[$pageID] = true;
				}
			}
		}
		return $pages;
	}

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function execute() {
		$searchEngine = $this->createSearchEngine();
		$results = $this->doSearch( $searchEngine );
		$response = $this->getResponseFactory()->createJson( [ 'pages' => $results ] );

		if ( $this->mode === self::COMPLETION_MODE && $this->completionCacheExpiry ) {
			// Type-ahead completion matches should be cached by the client and
			// in the CDN, especially for short prefixes.
			// See also $wgSearchSuggestCacheExpiry and ApiOpenSearch
			$response->setHeader( 'Cache-Control', 'public, max-age=' . $this->completionCacheExpiry );
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
