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
use MediaWiki\User\TempUser\TempUserConfig;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\RawSQLExpression;

/**
 * Handler class for Core REST API endpoints that perform operations on revisions
 */
class PageHistoryCountHandler extends SimpleHandler {

	/** The maximum number of counts to return per type of revision */
	private const COUNT_LIMITS = [
		'anonymous' => 10000,
		'temporary' => 10000,
		'bot' => 10000,
		'editors' => 25000,
		'edits' => 30000,
		'minor' => 1000,
		'reverted' => 30000
	];

	private const DEPRECATED_COUNT_TYPES = [
		'anonedits' => 'anonymous',
		'botedits' => 'bot',
		'revertededits' => 'reverted'
	];

	private const MAX_AGE_200 = 60;

	private RevisionStore $revisionStore;
	private NameTableStore $changeTagDefStore;
	private GroupPermissionsLookup $groupPermissionsLookup;
	private IConnectionProvider $dbProvider;
	private PageLookup $pageLookup;
	private WANObjectCache $cache;
	private PageRestHelperFactory $helperFactory;
	private TempUserConfig $tempUserConfig;

	/** @var RevisionRecord|false|null */
	private $revision = false;

	/** @var array|null */
	private $lastModifiedTimes;

	/** @var ExistingPageRecord|false|null */
	private $page = false;

	/**
	 * @param RevisionStore $revisionStore
	 * @param NameTableStoreFactory $nameTableStoreFactory
	 * @param GroupPermissionsLookup $groupPermissionsLookup
	 * @param IConnectionProvider $dbProvider
	 * @param WANObjectCache $cache
	 * @param PageLookup $pageLookup
	 * @param PageRestHelperFactory $helperFactory
	 * @param TempUserConfig $tempUserConfig
	 */
	public function __construct(
		RevisionStore $revisionStore,
		NameTableStoreFactory $nameTableStoreFactory,
		GroupPermissionsLookup $groupPermissionsLookup,
		IConnectionProvider $dbProvider,
		WANObjectCache $cache,
		PageLookup $pageLookup,
		PageRestHelperFactory $helperFactory,
		TempUserConfig $tempUserConfig
	) {
		$this->revisionStore = $revisionStore;
		$this->changeTagDefStore = $nameTableStoreFactory->getChangeTagDef();
		$this->groupPermissionsLookup = $groupPermissionsLookup;
		$this->dbProvider = $dbProvider;
		$this->cache = $cache;
		$this->pageLookup = $pageLookup;
		$this->helperFactory = $helperFactory;
		$this->tempUserConfig = $tempUserConfig;
	}

	private function getRedirectHelper(): PageRedirectHelper {
		return $this->helperFactory->newPageRedirectHelper(
			$this->getResponseFactory(),
			$this->getRouter(),
			$this->getPath(),
			$this->getRequest()
		);
	}

	private function normalizeType( string $type ): string {
		return self::DEPRECATED_COUNT_TYPES[$type] ?? $type;
	}

	/**
	 * Validates that the provided parameter combination is supported.
	 *
	 * @param string $type
	 * @throws LocalizedHttpException
	 */
	private function validateParameterCombination( $type ) {
		$params = $this->getValidatedParams();
		if ( !$params ) {
			return;
		}

		if ( $params['from'] || $params['to'] ) {
			if ( $type === 'edits' || $type === 'editors' ) {
				if ( !$params['from'] || !$params['to'] ) {
					throw new LocalizedHttpException(
						new MessageValue( 'rest-pagehistorycount-parameters-invalid' ),
						400
					);
				}
			} else {
				throw new LocalizedHttpException(
					new MessageValue( 'rest-pagehistorycount-parameters-invalid' ),
					400
				);
			}
		}
	}

	/**
	 * @param string $title the title of the page to load history for
	 * @param string $type the validated count type
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( $title, $type ) {
		$normalizedType = $this->normalizeType( $type );
		$this->validateParameterCombination( $normalizedType );
		$params = $this->getValidatedParams();
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
					[ new ScalarParam( ParamType::PLAINTEXT, $title ) ]
				),
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

		$count = $this->getCount( $normalizedType );
		$countLimit = self::COUNT_LIMITS[$normalizedType];
		$response = $this->getResponseFactory()->createJson( [
				'count' => $count > $countLimit ? $countLimit : $count,
				'limit' => $count > $countLimit
		] );
		$response->setHeader( 'Cache-Control', 'max-age=' . self::MAX_AGE_200 );

		// Inform clients who use a deprecated "type" value, so they can adjust
		if ( isset( self::DEPRECATED_COUNT_TYPES[$type] ) ) {
			$docs = '<https://www.mediawiki.org/wiki/API:REST/History_API' .
				'#Get_page_history_counts>; rel="deprecation"';
			$response->setHeader( 'Deprecation', 'version="v1"' );
			$response->setHeader( 'Link', $docs );
		}

		return $response;
	}

	/**
	 * @param string $type the validated count type
	 * @return int the article count
	 * @throws LocalizedHttpException
	 */
	private function getCount( $type ) {
		$pageId = $this->getPage()->getId();
		switch ( $type ) {
			case 'anonymous':
				return $this->getCachedCount( $type,
					function ( ?RevisionRecord $fromRev = null ) use ( $pageId ) {
						return $this->getAnonCount( $pageId, $fromRev );
					}
				);

			case 'temporary':
				return $this->getCachedCount( $type,
					function ( ?RevisionRecord $fromRev = null ) use ( $pageId ) {
						return $this->getTempCount( $pageId, $fromRev );
					}
				);

			case 'bot':
				return $this->getCachedCount( $type,
					function ( ?RevisionRecord $fromRev = null ) use ( $pageId ) {
						return $this->getBotCount( $pageId, $fromRev );
					}
				);

			case 'editors':
				$from = $this->getValidatedParams()['from'] ?? null;
				$to = $this->getValidatedParams()['to'] ?? null;
				if ( $from || $to ) {
					return $this->getEditorsCount(
						$pageId,
						$from ? $this->getRevisionOrThrow( $from ) : null,
						$to ? $this->getRevisionOrThrow( $to ) : null
					);
				} else {
					return $this->getCachedCount( $type,
						function ( ?RevisionRecord $fromRev = null ) use ( $pageId ) {
							return $this->getEditorsCount( $pageId, $fromRev );
						} );
				}

			case 'edits':
				$from = $this->getValidatedParams()['from'] ?? null;
				$to = $this->getValidatedParams()['to'] ?? null;
				if ( $from || $to ) {
					return $this->getEditsCount(
						$pageId,
						$from ? $this->getRevisionOrThrow( $from ) : null,
						$to ? $this->getRevisionOrThrow( $to ) : null
					);
				} else {
					return $this->getCachedCount( $type,
						function ( ?RevisionRecord $fromRev = null ) use ( $pageId ) {
							return $this->getEditsCount( $pageId, $fromRev );
						}
					);
				}

			case 'reverted':
				return $this->getCachedCount( $type,
					function ( ?RevisionRecord $fromRev = null ) use ( $pageId ) {
						return $this->getRevertedCount( $pageId, $fromRev );
					}
				);

			case 'minor':
				// The query for minor counts is inefficient for the database for pages with many revisions.
				// If the specified title contains more revisions than allowed, we will return an error.
				$editsCount = $this->getCachedCount( 'edits',
					function ( ?RevisionRecord $fromRev = null ) use ( $pageId ) {
						return $this->getEditsCount( $pageId, $fromRev );
					}
				);
				if ( $editsCount > self::COUNT_LIMITS[$type] * 2 ) {
					throw new LocalizedHttpException(
						new MessageValue( 'rest-pagehistorycount-too-many-revisions' ),
						500
					);
				}
				return $this->getCachedCount( $type,
					function ( ?RevisionRecord $fromRev = null ) use ( $pageId ) {
						return $this->getMinorCount( $pageId, $fromRev );
					}
				);

			default:
				throw new LocalizedHttpException(
					new MessageValue( 'rest-pagehistorycount-type-unrecognized',
						[ new ScalarParam( ParamType::PLAINTEXT, $type ) ]
					),
					500
				);
		}
	}

	/**
	 * @return RevisionRecord|null current revision or false if unable to retrieve revision
	 */
	private function getCurrentRevision(): ?RevisionRecord {
		if ( $this->revision === false ) {
			$page = $this->getPage();
			if ( $page ) {
				$this->revision = $this->revisionStore->getKnownCurrentRevision( $page ) ?: null;
			} else {
				$this->revision = null;
			}
		}
		return $this->revision;
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
	 * Returns latest of 2 timestamps:
	 * 1. Current revision
	 * 2. OR entry from the DB logging table for the given page
	 * @return int|null
	 */
	protected function getLastModified() {
		$lastModifiedTimes = $this->getLastModifiedTimes();
		if ( $lastModifiedTimes ) {
			return max( array_values( $lastModifiedTimes ) );
		}
		return null;
	}

	/**
	 * Returns array with 2 timestamps:
	 * 1. Current revision
	 * 2. OR entry from the DB logging table for the given page
	 * @return array|null
	 */
	protected function getLastModifiedTimes() {
		$currentRev = $this->getCurrentRevision();
		if ( !$currentRev ) {
			return null;
		}
		if ( $this->lastModifiedTimes === null ) {
			$currentRevTime = (int)wfTimestampOrNull( TS_UNIX, $currentRev->getTimestamp() );
			$loggingTableTime = $this->loggingTableTime( $currentRev->getPageId() );
			$this->lastModifiedTimes = [
				'currentRevTS' => $currentRevTime,
				'dependencyModTS' => $loggingTableTime
			];
		}
		return $this->lastModifiedTimes;
	}

	/**
	 * Return timestamp of latest entry in logging table for given page id
	 * @param int $pageId
	 * @return int|null
	 */
	private function loggingTableTime( $pageId ) {
		$res = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
			->select( 'MAX(log_timestamp)' )
			->from( 'logging' )
			->where( [ 'log_page' => $pageId ] )
			->caller( __METHOD__ )->fetchField();
		return $res ? (int)wfTimestamp( TS_UNIX, $res ) : null;
	}

	/**
	 * Choosing to not implement etags in this handler.
	 * Generating an etag when getting revision counts must account for things like visibility settings
	 * (e.g. rev_deleted bit) which requires hitting the database anyway. The response for these
	 * requests are so small that we wouldn't be gaining much efficiency.
	 * Etags are strong validators and if provided would take precedence over
	 * last modified time, a weak validator. We want to ensure only last modified time is used
	 * since it is more efficient than using etags for this particular case.
	 * @return null
	 */
	protected function getEtag() {
		return null;
	}

	/**
	 * @param string $type
	 * @param callable $fetchCount
	 * @return int
	 */
	private function getCachedCount( $type,
		callable $fetchCount
	) {
		$pageId = $this->getPage()->getId();
		return $this->cache->getWithSetCallback(
			$this->cache->makeKey( 'rest', 'pagehistorycount', $pageId, $type ),
			WANObjectCache::TTL_WEEK,
			function ( $oldValue ) use ( $fetchCount ) {
				$currentRev = $this->getCurrentRevision();
				if ( $oldValue ) {
					// Last modified timestamp was NOT a dependency change (e.g. revdel)
					$doIncrementalUpdate = (
						// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
						$this->getLastModified() != $this->getLastModifiedTimes()['dependencyModTS']
					);
					if ( $doIncrementalUpdate ) {
						$rev = $this->revisionStore->getRevisionById( $oldValue['revision'] );
						if ( $rev ) {
							$additionalCount = $fetchCount( $rev );
							return [
								'revision' => $currentRev->getId(),
								'count' => $oldValue['count'] + $additionalCount,
								// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
								'dependencyModTS' => $this->getLastModifiedTimes()['dependencyModTS']
							];
						}
					}
				}
				// Nothing was previously stored, or incremental update was done for too long,
				// recalculate from scratch.
				return [
					'revision' => $currentRev->getId(),
					'count' => $fetchCount(),
					// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
					'dependencyModTS' => $this->getLastModifiedTimes()['dependencyModTS']
				];
			},
			[
				'touchedCallback' => function (){
					return $this->getLastModified();
				},
				'version' => 2,
				'lockTSE' => WANObjectCache::TTL_MINUTE * 5
			]
		)['count'];
	}

	/**
	 * @param int $pageId the id of the page to load history for
	 * @param RevisionRecord|null $fromRev
	 * @return int the count
	 */
	protected function getAnonCount( $pageId, ?RevisionRecord $fromRev = null ) {
		$dbr = $this->dbProvider->getReplicaDatabase();
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( '1' )
			->from( 'revision' )
			->join( 'actor', null, 'rev_actor = actor_id' )
			->where( [
				'rev_page' => $pageId,
				'actor_user' => null,
				$dbr->bitAnd( 'rev_deleted',
					RevisionRecord::DELETED_TEXT | RevisionRecord::DELETED_USER ) => 0,
			] )
			->limit( self::COUNT_LIMITS['anonymous'] + 1 ); // extra to detect truncation

		if ( $fromRev ) {
			$queryBuilder->andWhere( $dbr->buildComparison( '>', [
				'rev_timestamp' => $dbr->timestamp( $fromRev->getTimestamp() ),
				'rev_id' => $fromRev->getId(),
			] ) );
		}

		return $queryBuilder->caller( __METHOD__ )->fetchRowCount();
	}

	/**
	 * @param int $pageId the id of the page to load history for
	 * @param RevisionRecord|null $fromRev
	 * @return int the count
	 */
	protected function getTempCount( $pageId, ?RevisionRecord $fromRev = null ) {
		if ( !$this->tempUserConfig->isKnown() ) {
			return 0;
		}

		$dbr = $this->dbProvider->getReplicaDatabase();
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( '1' )
			->from( 'revision' )
			->join( 'actor', null, 'rev_actor = actor_id' )
			->where( [
				'rev_page' => $pageId,
				$this->tempUserConfig->getMatchCondition(
					$dbr,
					'actor_name',
					IExpression::LIKE
				),
			] )
			->andWhere( [
				$dbr->bitAnd(
					'rev_deleted',
					RevisionRecord::DELETED_TEXT | RevisionRecord::DELETED_USER
				) => 0
			] )
			->limit( self::COUNT_LIMITS['temporary'] + 1 ); // extra to detect truncation

		if ( $fromRev ) {
			$queryBuilder->andWhere( $dbr->buildComparison( '>', [
				'rev_timestamp' => $dbr->timestamp( $fromRev->getTimestamp() ),
				'rev_id' => $fromRev->getId(),
			] ) );
		}

		return $queryBuilder->caller( __METHOD__ )->fetchRowCount();
	}

	/**
	 * @param int $pageId the id of the page to load history for
	 * @param RevisionRecord|null $fromRev
	 * @return int the count
	 */
	protected function getBotCount( $pageId, ?RevisionRecord $fromRev = null ) {
		$dbr = $this->dbProvider->getReplicaDatabase();

		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( '1' )
			->from( 'revision' )
			->join( 'actor', 'actor_rev_user', 'actor_rev_user.actor_id = rev_actor' )
			->where( [ 'rev_page' => intval( $pageId ) ] )
			->andWhere( [
				$dbr->bitAnd(
					'rev_deleted',
					RevisionRecord::DELETED_TEXT | RevisionRecord::DELETED_USER
				) => 0
			] )
			->limit( self::COUNT_LIMITS['bot'] + 1 ); // extra to detect truncation
		$subquery = $queryBuilder->newSubquery()
			->select( '1' )
			->from( 'user_groups' )
			->where( [
				'actor_rev_user.actor_user = ug_user',
				'ug_group' => $this->groupPermissionsLookup->getGroupsWithPermission( 'bot' ),
				$dbr->expr( 'ug_expiry', '=', null )->or( 'ug_expiry', '>=', $dbr->timestamp() )
			] );

		$queryBuilder->andWhere( new RawSQLExpression( 'EXISTS(' . $subquery->getSQL() . ')' ) );
		if ( $fromRev ) {
			$queryBuilder->andWhere( $dbr->buildComparison( '>', [
				'rev_timestamp' => $dbr->timestamp( $fromRev->getTimestamp() ),
				'rev_id' => $fromRev->getId(),
			] ) );
		}

		return $queryBuilder->caller( __METHOD__ )->fetchRowCount();
	}

	/**
	 * @param int $pageId the id of the page to load history for
	 * @param RevisionRecord|null $fromRev
	 * @param RevisionRecord|null $toRev
	 * @return int the count
	 */
	protected function getEditorsCount( $pageId,
		?RevisionRecord $fromRev = null,
		?RevisionRecord $toRev = null
	) {
		[ $fromRev, $toRev ] = $this->orderRevisions( $fromRev, $toRev );
		return $this->revisionStore->countAuthorsBetween( $pageId, $fromRev,
			$toRev, $this->getAuthority(), self::COUNT_LIMITS['editors'] );
	}

	/**
	 * @param int $pageId the id of the page to load history for
	 * @param RevisionRecord|null $fromRev
	 * @return int the count
	 */
	protected function getRevertedCount( $pageId, ?RevisionRecord $fromRev = null ) {
		$tagIds = [];

		foreach ( ChangeTags::REVERT_TAGS as $tagName ) {
			try {
				$tagIds[] = $this->changeTagDefStore->getId( $tagName );
			} catch ( NameTableAccessException ) {
				// If no revisions are tagged with a name, no tag id will be present
			}
		}
		if ( !$tagIds ) {
			return 0;
		}

		$dbr = $this->dbProvider->getReplicaDatabase();
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( '1' )
			->from( 'revision' )
			->join( 'change_tag', null, 'ct_rev_id = rev_id' )
			->where( [
				'rev_page' => $pageId,
				'ct_tag_id' => $tagIds,
				$dbr->bitAnd( 'rev_deleted', RevisionRecord::DELETED_TEXT ) . " = 0"
			] )
			->groupBy( 'rev_id' )
			->limit( self::COUNT_LIMITS['reverted'] + 1 ); // extra to detect truncation

		if ( $fromRev ) {
			$queryBuilder->andWhere( $dbr->buildComparison( '>', [
				'rev_timestamp' => $dbr->timestamp( $fromRev->getTimestamp() ),
				'rev_id' => $fromRev->getId(),
			] ) );
		}

		return $queryBuilder->caller( __METHOD__ )->fetchRowCount();
	}

	/**
	 * @param int $pageId the id of the page to load history for
	 * @param RevisionRecord|null $fromRev
	 * @return int the count
	 */
	protected function getMinorCount( $pageId, ?RevisionRecord $fromRev = null ) {
		$dbr = $this->dbProvider->getReplicaDatabase();
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( '1' )
			->from( 'revision' )
			->where( [
				'rev_page' => $pageId,
				$dbr->expr( 'rev_minor_edit', '!=', 0 ),
				$dbr->bitAnd( 'rev_deleted', RevisionRecord::DELETED_TEXT ) . " = 0"
			] )
			->limit( self::COUNT_LIMITS['minor'] + 1 ); // extra to detect truncation

		if ( $fromRev ) {
			$queryBuilder->andWhere( $dbr->buildComparison( '>', [
				'rev_timestamp' => $dbr->timestamp( $fromRev->getTimestamp() ),
				'rev_id' => $fromRev->getId(),
			] ) );
		}

		return $queryBuilder->caller( __METHOD__ )->fetchRowCount();
	}

	/**
	 * @param int $pageId the id of the page to load history for
	 * @param RevisionRecord|null $fromRev
	 * @param RevisionRecord|null $toRev
	 * @return int the count
	 */
	protected function getEditsCount(
		$pageId,
		?RevisionRecord $fromRev = null,
		?RevisionRecord $toRev = null
	) {
		[ $fromRev, $toRev ] = $this->orderRevisions( $fromRev, $toRev );
		return $this->revisionStore->countRevisionsBetween(
			$pageId,
			$fromRev,
			$toRev,
			self::COUNT_LIMITS['edits'] // Will be increased by 1 to detect truncation
		);
	}

	/**
	 * @param int $revId
	 * @return RevisionRecord
	 * @throws LocalizedHttpException
	 */
	private function getRevisionOrThrow( $revId ) {
		$rev = $this->revisionStore->getRevisionById( $revId );
		if ( !$rev ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-nonexistent-revision', [ $revId ] ),
				404
			);
		}
		return $rev;
	}

	/**
	 * Reorders revisions if they are present
	 * @param RevisionRecord|null $fromRev
	 * @param RevisionRecord|null $toRev
	 * @return array
	 * @phan-return array{0:RevisionRecord|null,1:RevisionRecord|null}
	 */
	private function orderRevisions(
		?RevisionRecord $fromRev = null,
		?RevisionRecord $toRev = null
	) {
		if ( $fromRev && $toRev && ( $fromRev->getTimestamp() > $toRev->getTimestamp() ||
				( $fromRev->getTimestamp() === $toRev->getTimestamp()
					&& $fromRev->getId() > $toRev->getId() ) )
		) {
			return [ $toRev, $fromRev ];
		}
		return [ $fromRev, $toRev ];
	}

	public function needsWriteAccess() {
		return false;
	}

	protected function getResponseBodySchemaFileName( string $method ): ?string {
		return 'includes/Rest/Handler/Schema/PageHistoryCount.json';
	}

	public function getParamSettings() {
		return [
			'title' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-pagehistory-count-title' ),
			],
			'type' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => array_merge(
					array_keys( self::COUNT_LIMITS ),
					array_keys( self::DEPRECATED_COUNT_TYPES )
				),
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-pagehistory-count-type' ),
			],
			'from' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-pagehistory-count-from' ),
			],
			'to' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-pagehistory-count-to' ),
			]
		];
	}
}
