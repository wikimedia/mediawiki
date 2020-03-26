<?php

namespace MediaWiki\Rest\Handler;

use MediaFileTrait;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use RepoGroup;
use RequestContext;
use Title;
use User;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Handler class for Core REST API endpoints that perform operations on revisions
 */
class MediaLinksHandler extends SimpleHandler {
	use MediaFileTrait;

	/** int The maximum number of media links to return */
	private const MAX_NUM_LINKS = 100;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var RepoGroup */
	private $repoGroup;

	/** @var User */
	private $user;

	/**
	 * @var Title|bool|null
	 */
	private $title = null;

	/**
	 * @param PermissionManager $permissionManager
	 * @param ILoadBalancer $loadBalancer
	 * @param RepoGroup $repoGroup
	 */
	public function __construct(
		PermissionManager $permissionManager,
		ILoadBalancer $loadBalancer,
		RepoGroup $repoGroup
	) {
		$this->permissionManager = $permissionManager;
		$this->loadBalancer = $loadBalancer;
		$this->repoGroup = $repoGroup;

		// @todo Inject this, when there is a good way to do that
		$this->user = RequestContext::getMain()->getUser();
	}

	/**
	 * @return Title|bool Title or false if unable to retrieve title
	 */
	private function getTitle() {
		if ( $this->title === null ) {
			$this->title = Title::newFromText( $this->getValidatedParams()['title'] ) ?? false;
		}
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( $title ) {
		$titleObj = Title::newFromText( $title );
		if ( !$titleObj || !$titleObj->getArticleID() ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-title' )->plaintextParams( $title ),
				404
			);
		}

		if ( !$this->permissionManager->userCan( 'read', $this->user, $titleObj ) ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-permission-denied-title' )->plaintextParams( $title ),
				403
			);
		}

		// @todo: add continuation if too many links are found
		$results = $this->getDbResults( $titleObj->getArticleID() );
		if ( count( $results ) > self::MAX_NUM_LINKS ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-media-too-many-links' )
					->plaintextParams( $title )
					->numParams( self::MAX_NUM_LINKS ),
				500
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
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		return $dbr->selectFieldValues(
			'imagelinks',
			'il_to',
			[ 'il_from' => $pageId ],
			__METHOD__,
			[
				'ORDER BY' => 'il_to',
				'LIMIT' => self::MAX_NUM_LINKS + 1,
			]
		);
	}

	/**
	 * @param array $results database results, or an empty array if none
	 * @return array response data
	 */
	private function processDbResults( $results ) {
		// Using "private" here means an equivalent of the Action API's "anon-public-user-private"
		// caching model would be necessary, if caching is ever added to this endpoint.
		$findTitles = array_map( function ( $title ) {
			return [
				'title' => $title,
				'private' => $this->user,
			];
		}, $results );

		$files = $this->repoGroup->findFiles( $findTitles );
		list( $maxWidth, $maxHeight ) = self::getImageLimitsFromOption( $this->user, 'imagesize' );
		$transforms = [
			'preferred' => [
				'maxWidth' => $maxWidth,
				'maxHeight' => $maxHeight,
			]
		];
		$response = [];
		foreach ( $files as $file ) {
			$response[] = $this->getFileInfo( $file, $this->user, $transforms );
		}

		$response = [
			'files' => $response
		];

		return $response;
	}

	public function needsWriteAccess() {
		return false;
	}

	public function getParamSettings() {
		return [
			'title' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}

	/**
	 * @return string|null
	 * @throws LocalizedHttpException
	 */
	protected function getETag(): ?string {
		$title = $this->getTitle();
		if ( !$title || !$title->getArticleID() ) {
			return null;
		}

		// XXX: use hash of the rendered HTML?
		return '"' . $title->getLatestRevID() . '@' . wfTimestamp( TS_MW, $title->getTouched() ) . '"';
	}

	/**
	 * @return string|null
	 * @throws LocalizedHttpException
	 */
	protected function getLastModified(): ?string {
		$title = $this->getTitle();
		if ( !$title || !$title->getArticleID() ) {
			return null;
		}

		return $title->getTouched();
	}

	/**
	 * @return bool
	 */
	protected function hasRepresentation() {
		$title = $this->getTitle();
		return $title ? $title->exists() : false;
	}
}
