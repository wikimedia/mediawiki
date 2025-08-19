<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Deferred\LinksUpdate\ImageLinksTable;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageLookup;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Handler class for Core REST API endpoints that perform operations on revisions
 */
class MediaLinksHandler extends SimpleHandler {
	use \MediaWiki\FileRepo\File\MediaFileTrait;

	/** int The maximum number of media links to return */
	private const MAX_NUM_LINKS = 100;

	private IConnectionProvider $dbProvider;
	private RepoGroup $repoGroup;
	private PageLookup $pageLookup;

	/**
	 * @var ExistingPageRecord|false|null
	 */
	private $page = false;

	public function __construct(
		IConnectionProvider $dbProvider,
		RepoGroup $repoGroup,
		PageLookup $pageLookup
	) {
		$this->dbProvider = $dbProvider;
		$this->repoGroup = $repoGroup;
		$this->pageLookup = $pageLookup;
	}

	private function getPage(): ?ExistingPageRecord {
		if ( $this->page === false ) {
			$this->page = $this->pageLookup->getExistingPageByText(
					$this->getValidatedParams()['title']
				);
		}
		return $this->page;
	}

	/**
	 * @param string $title
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( $title ) {
		$page = $this->getPage();
		if ( !$page ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-title' )->plaintextParams( $title ),
				404
			);
		}

		if ( !$this->getAuthority()->authorizeRead( 'read', $page ) ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-permission-denied-title' )->plaintextParams( $title ),
				403
			);
		}

		// @todo: add continuation if too many links are found
		$results = $this->getDbResults( $page->getId() );
		if ( count( $results ) > $this->getMaxNumLinks() ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-media-too-many-links' )
					->plaintextParams( $title )
					->numParams( $this->getMaxNumLinks() ),
				400
			);
		}
		$response = $this->processDbResults( $results );
		return $this->getResponseFactory()->createJson( $response );
	}

	/**
	 * @param int $pageId the id of the page to load media links for
	 * @return array the results
	 */
	private function getDbResults( int $pageId ) {
		return $this->dbProvider->getReplicaDatabase( ImageLinksTable::VIRTUAL_DOMAIN )->newSelectQueryBuilder()
			->select( 'il_to' )
			->from( 'imagelinks' )
			->where( [ 'il_from' => $pageId ] )
			->orderBy( 'il_to' )
			->limit( $this->getMaxNumLinks() + 1 )
			->caller( __METHOD__ )->fetchFieldValues();
	}

	/**
	 * @param array $results database results, or an empty array if none
	 * @return array response data
	 */
	private function processDbResults( $results ) {
		// Using "private" here means an equivalent of the Action API's "anon-public-user-private"
		// caching model would be necessary, if caching is ever added to this endpoint.
		$performer = $this->getAuthority();
		$findTitles = array_map( static function ( $title ) use ( $performer ) {
			return [
				'title' => $title,
				'private' => $performer,
			];
		}, $results );

		$files = $this->repoGroup->findFiles( $findTitles );
		[ $maxWidth, $maxHeight ] = self::getImageLimitsFromOption(
			$this->getAuthority()->getUser(),
			'imagesize'
		);
		$transforms = [
			'preferred' => [
				'maxWidth' => $maxWidth,
				'maxHeight' => $maxHeight,
			]
		];
		$response = [];
		foreach ( $files as $file ) {
			$response[] = $this->getFileInfo( $file, $performer, $transforms );
		}

		$response = [
			'files' => $response
		];

		return $response;
	}

	/** @inheritDoc */
	public function needsWriteAccess() {
		return false;
	}

	/** @inheritDoc */
	public function getParamSettings() {
		return [
			'title' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-media-links-title' ),
			],
		];
	}

	/**
	 * @return string|null
	 * @throws LocalizedHttpException
	 */
	protected function getETag(): ?string {
		$page = $this->getPage();
		if ( !$page ) {
			return null;
		}

		// XXX: use hash of the rendered HTML?
		return '"' . $page->getLatest() . '@' . wfTimestamp( TS_MW, $page->getTouched() ) . '"';
	}

	/**
	 * @return string|null
	 * @throws LocalizedHttpException
	 */
	protected function getLastModified(): ?string {
		$page = $this->getPage();
		return $page ? $page->getTouched() : null;
	}

	/**
	 * @return bool
	 */
	protected function hasRepresentation() {
		return (bool)$this->getPage();
	}

	/**
	 * For testing
	 *
	 * @unstable
	 */
	protected function getMaxNumLinks(): int {
		return self::MAX_NUM_LINKS;
	}

	public function getResponseBodySchemaFileName( string $method ): ?string {
		return 'includes/Rest/Handler/Schema/MediaLinks.json';
	}
}
