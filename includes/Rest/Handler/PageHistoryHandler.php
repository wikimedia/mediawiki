<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageLookup;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Handler\Helper\PageRedirectHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\NameTableStoreFactory;
use MediaWiki\Title\TitleFormatter;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\RawSQLExpression;

/**
 * Handler class for Core REST API endpoints that perform operations on revisions
 */
class PageHistoryHandler extends SimpleHandler {

	private const REVISIONS_RETURN_LIMIT = 20;
	private const ALLOWED_FILTER_TYPES = [ 'anonymous', 'bot', 'reverted', 'minor' ];

	private RevisionStore $revisionStore;
	private NameTableStore $changeTagDefStore;
	private GroupPermissionsLookup $groupPermissionsLookup;
	private IConnectionProvider $dbProvider;
	private PageLookup $pageLookup;
	private TitleFormatter $titleFormatter;
	private PageRestHelperFactory $helperFactory;

	/**
	 * @var ExistingPageRecord|false|null
	 */
	private $page = false;

	/**
	 * RevisionStore $revisionStore
	 *
	 * @param RevisionStore $revisionStore
	 * @param NameTableStoreFactory $nameTableStoreFactory
	 * @param GroupPermissionsLookup $groupPermissionsLookup
	 * @param IConnectionProvider $dbProvider
	 * @param PageLookup $pageLookup
	 * @param TitleFormatter $titleFormatter
	 * @param PageRestHelperFactory $helperFactory
	 */
	public function __construct(
		RevisionStore $revisionStore,
		NameTableStoreFactory $nameTableStoreFactory,
		GroupPermissionsLookup $groupPermissionsLookup,
		IConnectionProvider $dbProvider,
		PageLookup $pageLookup,
		TitleFormatter $titleFormatter,
		PageRestHelperFactory $helperFactory
	) {
		$this->revisionStore = $revisionStore;
		$this->changeTagDefStore = $nameTableStoreFactory->getChangeTagDef();
		$this->groupPermissionsLookup = $groupPermissionsLookup;
		$this->dbProvider = $dbProvider;
		$this->pageLookup = $pageLookup;
		$this->titleFormatter = $titleFormatter;
		$this->helperFactory = $helperFactory;
	}

	private function getRedirectHelper(): PageRedirectHelper {
		return $this->helperFactory->newPageRedirectHelper(
			$this->getResponseFactory(),
			$this->getRouter(),
			$this->getPath(),
			$this->getRequest()
		);
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
			foreach ( ChangeTags::REVERT_TAGS as $tagName ) {
				try {
					$tagIds[] = $this->changeTagDefStore->getId( $tagName );
				} catch ( NameTableAccessException $exception ) {
					// If no revisions are tagged with a name, no tag id will be present
				}
			}
		}

		$page = $this->getPage();

		if ( !$page ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-nonexistent-title',
					[ new ScalarParam( ParamType::PLAINTEXT, $title ) ]
				),
				404
			);
		}
		if ( !$this->getAuthority()->authorizeRead( 'read', $page ) ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-permission-denied-title',
					[ new ScalarParam( ParamType::PLAINTEXT, $title ) ] ),
				403
			);
		}

		'@phan-var \MediaWiki\Page\ExistingPageRecord $page';
		$redirectResponse = $this->getRedirectHelper()->createNormalizationRedirectResponseIfNeeded(
			$page,
			$params['title'] ?? null
		);

		if ( $redirectResponse !== null ) {
			return $redirectResponse;
		}

		$relativeRevId = $params['older_than'] ?? $params['newer_than'] ?? 0;
		if ( $relativeRevId ) {
			// Confirm the relative revision exists for this page. If so, get its timestamp.
			$rev = $this->revisionStore->getRevisionByPageId(
				$page->getId(),
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

		$res = $this->getDbResults( $page, $params, $relativeRevId, $ts, $tagIds );
		$response = $this->processDbResults( $res, $page, $params );
		return $this->getResponseFactory()->createJson( $response );
	}

	/**
	 * @param ExistingPageRecord $page object identifying the page to load history for
	 * @param array $params request parameters
	 * @param int $relativeRevId relative revision id for paging, or zero if none
	 * @param int $ts timestamp for paging, or zero if none
	 * @param array $tagIds validated tags ids, or empty array if not needed for this query
	 * @return IResultWrapper|bool the results, or false if no query was executed
	 */
	private function getDbResults( ExistingPageRecord $page, array $params, $relativeRevId, $ts, $tagIds ) {
		$dbr = $this->dbProvider->getReplicaDatabase();
		$queryBuilder = $this->revisionStore->newSelectQueryBuilder( $dbr )
			->joinComment()
			->where( [ 'rev_page' => $page->getId() ] )
			// Select one more than the return limit, to learn if there are additional revisions.
			->limit( self::REVISIONS_RETURN_LIMIT + 1 );

		if ( $params['filter'] ) {
			// The validator ensures this value, if present, is one of the expected values
			switch ( $params['filter'] ) {
				case 'bot':
					$subquery = $queryBuilder->newSubquery()
						->select( '1' )
						->from( 'user_groups' )
						->where( [
							'actor_rev_user.actor_user = ug_user',
							'ug_group' => $this->groupPermissionsLookup->getGroupsWithPermission( 'bot' ),
							$dbr->expr( 'ug_expiry', '=', null )->or( 'ug_expiry', '>=', $dbr->timestamp() )
						] );
					$queryBuilder->andWhere( new RawSQLExpression( 'EXISTS(' . $subquery->getSQL() . ')' ) );
					$bitmask = $this->getBitmask();
					if ( $bitmask ) {
						$queryBuilder->andWhere( $dbr->bitAnd( 'rev_deleted', $bitmask ) . " != $bitmask" );
					}
					break;

				case 'anonymous':
					$queryBuilder->andWhere( [ 'actor_user' => null ] );
					$bitmask = $this->getBitmask();
					if ( $bitmask ) {
						$queryBuilder->andWhere( $dbr->bitAnd( 'rev_deleted', $bitmask ) . " != $bitmask" );
					}
					break;

				case 'reverted':
					if ( !$tagIds ) {
						return false;
					}
					$subquery = $queryBuilder->newSubquery()
						->select( '1' )
						->from( 'change_tag' )
						->where( [ 'ct_rev_id = rev_id', 'ct_tag_id' => $tagIds ] );
					$queryBuilder->andWhere( new RawSQLExpression( 'EXISTS(' . $subquery->getSQL() . ')' ) );
					break;

				case 'minor':
					$queryBuilder->andWhere( $dbr->expr( 'rev_minor_edit', '!=', 0 ) );
					break;
			}
		}

		if ( $relativeRevId ) {
			$op = $params['older_than'] ? '<' : '>';
			$sort = $params['older_than'] ? 'DESC' : 'ASC';
			$queryBuilder->andWhere( $dbr->buildComparison( $op, [
				'rev_timestamp' => $dbr->timestamp( $ts ),
				'rev_id' => $relativeRevId,
			] ) );
			$queryBuilder->orderBy( [ 'rev_timestamp', 'rev_id' ], $sort );
		} else {
			$queryBuilder->orderBy( [ 'rev_timestamp', 'rev_id' ], 'DESC' );
		}

		return $queryBuilder->caller( __METHOD__ )->fetchResultSet();
	}

	/**
	 * Helper function for rev_deleted/user rights query conditions
	 *
	 * @todo Factor out rev_deleted logic per T233222
	 *
	 * @return int
	 */
	private function getBitmask() {
		if ( !$this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
			$bitmask = RevisionRecord::DELETED_USER;
		} elseif ( !$this->getAuthority()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$bitmask = RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED;
		} else {
			$bitmask = 0;
		}
		return $bitmask;
	}

	/**
	 * @param IResultWrapper|bool $res database results, or false if no query was executed
	 * @param ExistingPageRecord $page object identifying the page to load history for
	 * @param array $params request parameters
	 * @return array response data
	 */
	private function processDbResults( $res, $page, $params ) {
		$revisions = [];

		if ( $res ) {
			$sizes = [];
			foreach ( $res as $row ) {
				$rev = $this->revisionStore->newRevisionFromRow(
					$row,
					IDBAccessObject::READ_NORMAL,
					$page
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

				$comment = $rev->getComment( RevisionRecord::FOR_THIS_USER, $this->getAuthority() );
				$revision['comment'] = $comment ? $comment->text : null;

				$revUser = $rev->getUser( RevisionRecord::FOR_THIS_USER, $this->getAuthority() );
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
				// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable
				// $lastRevId is declared because $res has one element
				$temp = $lastRevId;
				// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable
				// $firstRevId is declared because $res has one element
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
				// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable
				// $lastRevId is declared because $res has one element
				$older = $lastRevId;
			}
			if ( $params['older_than'] ||
				( $params['newer_than'] && $res->numRows() > self::REVISIONS_RETURN_LIMIT )
			) {
				// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable
				// $firstRevId is declared because $res has one element
				$newer = $firstRevId;
			}
		}

		$queryParts = [];

		if ( isset( $params['filter'] ) ) {
			$queryParts['filter'] = $params['filter'];
		}

		$pathParams = [ 'title' => $this->titleFormatter->getPrefixedDBkey( $page ) ];

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
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-pagehistory-title' ),
			],
			'older_than' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-pagehistory-older-than' ),
			],
			'newer_than' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-pagehistory-newer-than' ),
			],
			'filter' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => self::ALLOWED_FILTER_TYPES,
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-pagehistory-filter' ),
			],
		];
	}

	/**
	 * Returns an ETag representing a page's latest revision.
	 *
	 * @return string|null
	 */
	protected function getETag(): ?string {
		$page = $this->getPage();
		if ( !$page ) {
			return null;
		}

		return '"' . $page->getLatest() . '"';
	}

	/**
	 * Returns the time of the last change to the page.
	 *
	 * @return string|null
	 */
	protected function getLastModified(): ?string {
		$page = $this->getPage();
		if ( !$page ) {
			return null;
		}

		$rev = $this->revisionStore->getKnownCurrentRevision( $page );
		return $rev->getTimestamp();
	}

	/**
	 * @return bool
	 */
	protected function hasRepresentation() {
		return (bool)$this->getPage();
	}

	public function getResponseBodySchemaFileName( string $method ): ?string {
		return 'includes/Rest/Handler/Schema/PageHistory.json';
	}
}
