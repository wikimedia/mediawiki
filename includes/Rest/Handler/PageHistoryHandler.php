<?php

namespace MediaWiki\Rest\Handler;

use GuzzleHttp\Psr7\Uri;
use IDBAccessObject;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\Response;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use Message;
use Wikimedia\Message\MessageValue;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\NameTableStoreFactory;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IResultWrapper;
use Title;

/**
 * Handler class for Core REST API endpoints that perform operations on revisions
 */
class PageHistoryHandler extends SimpleHandler {
	const REVISIONS_RETURN_LIMIT = 20;
	const REVERTED_TAG_NAMES = [ 'mw-undo', 'mw-rollback' ];
	const ALLOWED_FILTER_TYPES = [ 'anonymous', 'bot', 'reverted' ];

	/** @var RevisionStore */
	private $revisionStore;

	/** @var NameTableStore */
	private $changeTagDefStore;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/**
	 * @param RevisionStore $revisionStore
	 * @param NameTableStoreFactory $nameTableStoreFactory
	 * @param PermissionManager $permissionManager
	 * @param ILoadBalancer $loadBalancer
	 */
	public function __construct(
		RevisionStore $revisionStore,
		NameTableStoreFactory $nameTableStoreFactory,
		PermissionManager $permissionManager,
		ILoadBalancer $loadBalancer
	) {
		$this->revisionStore = $revisionStore;
		$this->changeTagDefStore = $nameTableStoreFactory->getChangeTagDef();
		$this->permissionManager = $permissionManager;
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * At most one of older_than and newer_than may be specified. Keep in mind that revision ids
	 * are not monotonically increasing, so a revision may be older than another but have a
	 * higher revision id.
	 *
	 * @param string $title
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( $title ) {
		$params = $this->getValidatedParams();
		if ( $params['older_than'] !== null && $params['newer_than'] !== null ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-pagehistory-incompatible-params' ), 400 );
		}

		if ( ( $params['older_than'] !== null && $params['older_than'] < 1 ) ||
			( $params['newer_than'] !== null && $params['newer_than'] < 1 )
		) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-pagehistory-param-range-error' ), 400 );
		}

		$tagIds = [];
		if ( $params['filter'] === 'reverted' ) {
			foreach ( self::REVERTED_TAG_NAMES as $tagName ) {
				try {
					$tagIds[] = $this->changeTagDefStore->getId( $tagName );
				} catch ( NameTableAccessException $exception ) {
					// If no revisions are tagged with a name, no tag id will be present
				}
			}
		}

		$titleObj = Title::newFromText( $title );
		if ( !$titleObj || !$titleObj->getArticleID() ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-pagehistory-title-nonexistent',
					[ Message::plaintextParam( $title ) ]
				),
				404
			);
		}

		$relativeRevId = $params['older_than'] ?? $params['newer_than'] ?? 0;
		if ( $relativeRevId ) {
			// Confirm the relative revision exists for this page. If so, get its timestamp.
			$rev = $this->revisionStore->getRevisionByPageId(
				$titleObj->getArticleID(),
				$relativeRevId
			);
			if ( !$rev ) {
				throw new LocalizedHttpException(
					new MessageValue( 'rest-pagehistory-revision-nonexistent',
						[ $relativeRevId, Message::plaintextParam( $title ) ]
					),
					404
				);
			}
			$ts = $rev->getTimestamp();
			if ( $ts === null ) {
				throw new LocalizedHttpException(
					new MessageValue( 'rest-pagehistory-timestamp-error',
						[ $relativeRevId ]
					),
					500
				);
			}
		} else {
			$ts = 0;
		}

		$res = $this->getDbResults( $titleObj, $params, $relativeRevId, $ts, $tagIds );
		if ( $res === false || $res->numRows() == 0 ) {
			if ( $relativeRevId ) {
				throw new LocalizedHttpException(
					new MessageValue( 'rest-pagehistory-revisions-nonexistent-with-params',
						[ Message::plaintextParam( $title ) ]
					),
					404
				);
			} else {
				throw new LocalizedHttpException(
					new MessageValue( 'rest-pagehistory-revisions-nonexistent',
						[ Message::plaintextParam( $title ) ] ),
					404
				);
			}
		}

		// If we make it here, we will always have at least one revision to return.
		$response = $this->processDbResults( $res, $titleObj, $params );
		return $this->getResponseFactory()->createJson( $response );
	}

	/**
	 * @param Title $titleObj title object identifying the page to load history for
	 * @param array $params request parameters
	 * @param int $relativeRevId relative revision id for paging, or zero if none
	 * @param int $ts timestamp for paging, or zero if none
	 * @param array $tagIds validated tags ids, or empty array if not needed for this query
	 * @return IResultWrapper|bool the results, or false if no query was executed
	 */
	private function getDbResults( Title $titleObj, array $params, $relativeRevId, $ts, $tagIds ) {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$revQuery = $this->revisionStore->getQueryInfo();
		$cond = [
			'rev_page' => $titleObj->getArticleID()
		];

		if ( $params['filter'] ) {
			// This redundant join condition tells MySQL that rev_page and revactor_page are the
			// same, so it can propagate the condition
			$revQuery['joins']['temp_rev_user'][1] =
				"temp_rev_user.revactor_rev = rev_id AND revactor_page = rev_page";

			// The validator ensures this value, if present, is one of the expected values
			switch ( $params['filter'] ) {
				case 'bot':
					// TODO: per T231599, it is possible we will need a STRAIGHT JOIN HERE.
					$revQuery['tables']['user_groups'] = 'user_groups';
					$revQuery['joins']['user_groups'] = [
						'JOIN',
						[
							'actor_rev_user.actor_user = ug_user',
							'ug_group' => $this->permissionManager->getGroupsWithPermission( 'bot' ),
							'ug_expiry IS NULL OR ug_expiry >= ' . $dbr->addQuotes( $dbr->timestamp() )
						]
					];
					$cond[] = $dbr->bitAnd( 'rev_deleted', RevisionRecord::DELETED_USER ) . " = 0";
					break;

				case 'anonymous':
					$cond[] = "actor_user IS NULL";
					$cond[] = $dbr->bitAnd( 'rev_deleted', RevisionRecord::DELETED_USER ) . " = 0";
					break;

				case 'reverted':
					if ( !$tagIds ) {
						return false;
					}
					$cond[] = 'EXISTS(' . $dbr->selectSQLText(
							'change_tag',
							1,
							[ 'ct_rev_id = rev_id', 'ct_tag_id' => $tagIds ],
							__METHOD__
						) . ')';
					break;
			}
		}

		if ( $relativeRevId ) {
			$op = $params['older_than'] ? '<' : '>';
			$sort = $params['older_than'] ? 'DESC' : 'ASC';
			$cond[] = "rev_timestamp $op $ts OR " .
				"(rev_timestamp = $ts AND rev_id $op {$relativeRevId})";
			$orderBy = "rev_timestamp $sort, rev_id $sort";
		} else {
			$orderBy = "rev_timestamp DESC, rev_id DESC";
		}

		// Select one more than the return limit, to learn if there are additional revisions.
		$limit = self::REVISIONS_RETURN_LIMIT + 1;

		$res = $dbr->select(
			$revQuery['tables'],
			$revQuery['fields'],
			$cond,
			__METHOD__,
			[
				'ORDER BY' => $orderBy,
				'LIMIT' => $limit,
			],
			$revQuery['joins']
		);

		return $res;
	}

	/**
	 * @param IResultWrapper $res database results to process
	 * @param Title $titleObj title object identifying the page to load history for
	 * @param array $params request parameters
	 * @return array response data
	 */
	private function processDbResults( $res, $titleObj, $params ) {
		$revisions = [];

		$sizes = [];
		foreach ( $res as $row ) {
			$rev = $this->revisionStore->newRevisionFromRow(
				$row,
				IDBAccessObject::READ_NORMAL,
				$titleObj
			);
			if ( !$revisions ) {
				$firstRevId = $row->rev_id;
			}
			$lastRevId = $row->rev_id;

			$revision = [
				'id' => $rev->getId(),
				'timestamp' => wfTimestamp( TS_ISO_8601, $rev->getTimestamp() ),
				'size' => $rev->getSize(),
			];

			// Remember revision sizes and parent ids for calculating deltas. If a revision's
			// parent id is unknown, we will be unable to supply the delta for that revision.
			$sizes[$rev->getId()] = $rev->getSize();
			$parentId = $rev->getParentId();
			if ( $parentId ) {
				$revision['parent_id'] = $parentId;
			}

			$comment = $rev->getComment();
			if ( $comment ) {
				$revision['comment'] = $comment->text;
			}

			$user = $rev->getUser();
			if ( $user ) {
				$revision['user'] = [
					'name' => $user->getName(),
					'id' => $user->getId(),
				];
			}

			$revisions[] = $revision;

			// Break manually at the return limit. We may have more results than we can return.
			if ( count( $revisions ) == self::REVISIONS_RETURN_LIMIT ) {
				break;
			}
		}

		// Request any parent sizes that we do not already know, then calculate deltas
		$unknownSizes = [];
		foreach ( $revisions as $revision ) {
			if ( isset( $revision['parent_id'] ) && !isset( $sizes[$revision['parent_id']] ) ) {
				$unknownSizes[] = $revision['parent_id'];
			}
		}
		if ( $unknownSizes ) {
			$sizes += $this->revisionStore->getRevisionSizes( $unknownSizes );
		}
		foreach ( $revisions as &$revision ) {
			if ( isset( $revision['parent_id'] ) ) {
				if ( isset( $sizes[$revision['parent_id']] ) ) {
					$revision['delta'] = $revision['size'] - $sizes[$revision['parent_id']];
				}

				// We only remembered this for delta calculations. We do not want to return it.
				unset( $revision['parent_id'] );
			}
		}

		if ( $params['newer_than'] ) {
			$revisions = array_reverse( $revisions );
			$temp = $lastRevId;
			$lastRevId = $firstRevId;
			$firstRevId = $temp;
		}

		$response = [
			'revisions' => $revisions
		];

		// Omit newer/older if there are no additional corresponding revisions.
		// This facilitates clients doing "paging" style api operations.
		if ( $params['newer_than'] || $res->numRows() > self::REVISIONS_RETURN_LIMIT ) {
			$older = $lastRevId;
		}
		if ( $params['older_than'] ||
			( $params['newer_than'] && $res->numRows() > self::REVISIONS_RETURN_LIMIT )
		) {
			$newer = $firstRevId;
		}

		$wr = new \WebRequest();
		$urlParts = wfParseUrl( $wr->getFullRequestUrl() );
		if ( $urlParts ) {
			$queryParts = wfCgiToArray( $urlParts['query'] );
			unset( $urlParts['query'] );
			unset( $queryParts['older_than'] );
			unset( $queryParts['newer_than'] );

			$uri = Uri::fromParts( $urlParts );
			$response['latest'] = Uri::withQueryValues( $uri, $queryParts )->__toString();
			if ( isset( $older ) ) {
				$response['older'] = Uri::withQueryValues(
					$uri,
					$queryParts + [ 'older_than' => $older ]
				)->__toString();
			}
			if ( isset( $newer ) ) {
				$response[' newer'] = Uri::withQueryValues(
					$uri,
					$queryParts + [ 'newer_than' => $newer ]
				)->__toString();
			}
		}

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
			'older_than' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'newer_than' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'filter' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => self::ALLOWED_FILTER_TYPES,
				ParamValidator::PARAM_REQUIRED => false,
			],
		];
	}
}
