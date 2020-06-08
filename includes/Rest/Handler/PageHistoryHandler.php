<?php

namespace MediaWiki\Rest\Handler;

use IDBAccessObject;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\NameTableStoreFactory;
use RequestContext;
use Title;
use User;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Handler class for Core REST API endpoints that perform operations on revisions
 */
class PageHistoryHandler extends SimpleHandler {
	private const REVISIONS_RETURN_LIMIT = 20;
	private const REVERTED_TAG_NAMES = [ 'mw-undo', 'mw-rollback' ];
	private const ALLOWED_FILTER_TYPES = [ 'anonymous', 'bot', 'reverted', 'minor' ];

	/** @var RevisionStore */
	private $revisionStore;

	/** @var NameTableStore */
	private $changeTagDefStore;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var User */
	private $user;

	/**
	 * @var Title|bool|null
	 */
	private $title = null;

	/**
	 * RevisionStore $revisionStore
	 *
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
				new MessageValue( 'rest-nonexistent-title',
					[ new ScalarParam( ParamType::PLAINTEXT, $title ) ]
				),
				404
			);
		}
		if ( !$this->permissionManager->userCan( 'read', $this->user, $titleObj ) ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-permission-denied-title',
					[ new ScalarParam( ParamType::PLAINTEXT, $title ) ] ),
				403
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
					new MessageValue( 'rest-nonexistent-title-revision',
						[ $relativeRevId, new ScalarParam( ParamType::PLAINTEXT, $title ) ]
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
					$cond[] = 'EXISTS(' . $dbr->selectSQLText(
							'user_groups',
							'1',
							[
								'actor_rev_user.actor_user = ug_user',
								'ug_group' => $this->permissionManager->getGroupsWithPermission( 'bot' ),
								'ug_expiry IS NULL OR ug_expiry >= ' . $dbr->addQuotes( $dbr->timestamp() )
							],
							__METHOD__
						) . ')';
					$bitmask = $this->getBitmask();
					if ( $bitmask ) {
						$cond[] = $dbr->bitAnd( 'rev_deleted', $bitmask ) . " != $bitmask";
					}
					break;

				case 'anonymous':
					$cond[] = "actor_user IS NULL";
					$bitmask = $this->getBitmask();
					if ( $bitmask ) {
						$cond[] = $dbr->bitAnd( 'rev_deleted', $bitmask ) . " != $bitmask";
					}
					break;

				case 'reverted':
					if ( !$tagIds ) {
						return false;
					}
					$cond[] = 'EXISTS(' . $dbr->selectSQLText(
							'change_tag',
							'1',
							[ 'ct_rev_id = rev_id', 'ct_tag_id' => $tagIds ],
							__METHOD__
						) . ')';
					break;

				case 'minor':
					$cond[] = 'rev_minor_edit != 0';
					break;
			}
		}

		if ( $relativeRevId ) {
			$op = $params['older_than'] ? '<' : '>';
			$sort = $params['older_than'] ? 'DESC' : 'ASC';
			$ts = $dbr->addQuotes( $dbr->timestamp( $ts ) );
			$cond[] = "rev_timestamp $op $ts OR " .
				"(rev_timestamp = $ts AND rev_id $op $relativeRevId)";
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
	 * Helper function for rev_deleted/user rights query conditions
	 *
	 * @todo Factor out rev_deleted logic per T233222
	 *
	 * @return int
	 */
	private function getBitmask() {
		if ( !$this->permissionManager->userHasRight( $this->user, 'deletedhistory' ) ) {
			$bitmask = RevisionRecord::DELETED_USER;
		} elseif ( !$this->permissionManager
			->userHasAnyRight( $this->user, 'suppressrevision', 'viewsuppressed' )
		) {
			$bitmask = RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED;
		} else {
			$bitmask = 0;
		}
		return $bitmask;
	}

	/**
	 * @param IResultWrapper|bool $res database results, or false if no query was executed
	 * @param Title $titleObj title object identifying the page to load history for
	 * @param array $params request parameters
	 * @return array response data
	 */
	private function processDbResults( $res, $titleObj, $params ) {
		$revisions = [];

		if ( $res ) {
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
					'minor' => $rev->isMinor(),
					'size' => $rev->getSize()
				];

				// Remember revision sizes and parent ids for calculating deltas. If a revision's
				// parent id is unknown, we will be unable to supply the delta for that revision.
				$sizes[$rev->getId()] = $rev->getSize();
				$parentId = $rev->getParentId();
				if ( $parentId ) {
					$revision['parent_id'] = $parentId;
				}

				$comment = $rev->getComment( RevisionRecord::FOR_THIS_USER, $this->user );
				$revision['comment'] = $comment ? $comment->text : null;

				$revUser = $rev->getUser( RevisionRecord::FOR_THIS_USER, $this->user );
				if ( $revUser ) {
					$revision['user'] = [
						'id' => $revUser->isRegistered() ? $revUser->getId() : null,
						'name' => $revUser->getName()
					];
				} else {
					$revision['user'] = null;
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
				$revision['delta'] = null;
				if ( isset( $revision['parent_id'] ) ) {
					if ( isset( $sizes[$revision['parent_id']] ) ) {
						$revision['delta'] = $revision['size'] - $sizes[$revision['parent_id']];
					}

					// We only remembered this for delta calculations. We do not want to return it.
					unset( $revision['parent_id'] );
				}
			}

			if ( $revisions && $params['newer_than'] ) {
				$revisions = array_reverse( $revisions );
				$temp = $lastRevId;
				$lastRevId = $firstRevId;
				$firstRevId = $temp;
			}
		}

		$response = [
			'revisions' => $revisions
		];

		// Omit newer/older if there are no additional corresponding revisions.
		// This facilitates clients doing "paging" style api operations.
		if ( $revisions ) {
			if ( $params['newer_than'] || $res->numRows() > self::REVISIONS_RETURN_LIMIT ) {
				$older = $lastRevId;
			}
			if ( $params['older_than'] ||
				( $params['newer_than'] && $res->numRows() > self::REVISIONS_RETURN_LIMIT )
			) {
				$newer = $firstRevId;
			}
		}

		$queryParts = [];

		if ( isset( $params['filter'] ) ) {
			$queryParts['filter'] = $params['filter'];
		}

		$pathParams = [ 'title' => $titleObj->getPrefixedDBkey() ];

		$response['latest'] = $this->getRouteUrl( $pathParams, $queryParts );

		if ( isset( $older ) ) {
			$response['older'] =
				$this->getRouteUrl( $pathParams, $queryParts + [ 'older_than' => $older ] );
		}
		if ( isset( $newer ) ) {
			$response['newer'] =
				$this->getRouteUrl( $pathParams, $queryParts + [ 'newer_than' => $newer ] );
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

	/**
	 * Returns an ETag representing a page's latest revision.
	 *
	 * @return string|null
	 */
	protected function getETag(): ?string {
		$title = $this->getTitle();
		if ( !$title || !$title->getArticleID() ) {
			return null;
		}

		return '"' . $title->getLatestRevID() . '"';
	}

	/**
	 * Returns the time of the last change to the page.
	 *
	 * @return string|null
	 */
	protected function getLastModified(): ?string {
		$title = $this->getTitle();
		if ( !$title || !$title->getArticleID() ) {
			return null;
		}

		$rev = $this->revisionStore->getKnownCurrentRevision( $title );
		return $rev->getTimestamp();
	}

	/**
	 * @return bool
	 */
	protected function hasRepresentation() {
		$title = $this->getTitle();
		return $title ? $title->exists() : false;
	}
}
