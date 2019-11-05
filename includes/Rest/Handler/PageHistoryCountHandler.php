<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\Response;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\NameTableStoreFactory;
use RequestContext;
use User;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;
use Wikimedia\ParamValidator\ParamValidator;
use Title;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Handler class for Core REST API endpoints that perform operations on revisions
 */
class PageHistoryCountHandler extends SimpleHandler {
	/** int The maximum number of revisions to count */
	private const LIMIT = 1000;

	const ALLOWED_COUNT_TYPES = [ 'anonymous', 'bot', 'editors', 'edits', 'reverted', 'minor' ];

	// These types work identically to their similarly-named synonyms, but will be removed in the
	// next major version of the API. Callers should use the corresponding non-deprecated type.
	const DEPRECATED_COUNT_TYPES = [
		'anonedits', 'botedits', 'revertededits'
	];

	// The query for minor counts is inefficient for the database for pages with many revisions.
	// If the specified title contains more revisions than allowed, we will return an error.
	// This may be fixed with a database index, per T235572. If so, this check can be removed.
	const MINOR_QUERY_EDIT_COUNT_LIMIT = 2000;

	const REVERTED_TAG_NAMES = [ 'mw-undo', 'mw-rollback' ];

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
	 * @param string $title
	 * @param string $type
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( $title, $type ) {
		$this->validateParameterCombination( $type );
		$titleObj = Title::newFromText( $title );
		if ( !$titleObj || !$titleObj->getArticleID() ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-pagehistorycount-title-nonexistent',
					[ new ScalarParam( ParamType::PLAINTEXT, $title ) ]
				),
				404
			);
		}

		if ( !$this->permissionManager->userCan( 'read', $this->user, $titleObj ) ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-pagehistorycount-title-permission-denied',
					[ new ScalarParam( ParamType::PLAINTEXT, $title ) ]
				),
				403
			);
		}

		$count = $this->getCount( $titleObj->getArticleID(), $type );
		$response = $this->getResponseFactory()->createJson( [
				'count' => $count > self::LIMIT ? self::LIMIT : $count,
				'limit' => $count > self::LIMIT
		] );

		// Inform clients who use a deprecated "type" value, so they can adjust
		if ( in_array( $type, self::DEPRECATED_COUNT_TYPES ) ) {
			$docs = '<https://www.mediawiki.org/wiki/API:REST/History_API' .
				'#Get_page_history_counts>; rel="deprecation"';
			$response->setHeader( 'Deprecation', 'version="v1"' );
			$response->setHeader( 'Link', $docs );
		}

		return $response;
	}

	/**
	 * @param int $pageId the id of the page to load history for
	 * @param string $type the validated count type
	 * @return int the article count
	 * @throws LocalizedHttpException
	 */
	protected function getCount( $pageId, $type ) {
		switch ( $type ) {
			case 'anonedits':
			case 'anonymous':
				return $this->getAnonCount( $pageId );

			case 'botedits':
			case 'bot':
				return $this->getBotCount( $pageId );

			case 'editors':
				return $this->getEditorsCount( $pageId );

			case 'edits':
				return $this->getEditsCount( $pageId, self::LIMIT );

			case 'revertededits':
			case 'reverted':
				return $this->getRevertedCount( $pageId );

			case 'minor':
				$editsCount = $this->getEditsCount( $pageId, self::MINOR_QUERY_EDIT_COUNT_LIMIT );
				if ( $editsCount > self::MINOR_QUERY_EDIT_COUNT_LIMIT ) {
					throw new LocalizedHttpException(
						new MessageValue( 'rest-pagehistorycount-too-many-revisions' ),
						500
					);
				}
				return $this->getMinorCount( $pageId );
			// Sanity check
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
	 * @param int $pageId the id of the page to load history for
	 * @return int the count
	 */
	protected function getAnonCount( $pageId ) {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );

		$cond = [
			'rev_page' => $pageId,
			'actor_user IS NULL',
		];
		$bitmask = $this->getBitmask();
		if ( $bitmask ) {
			$cond[] = $dbr->bitAnd( 'rev_deleted', $bitmask ) . " != $bitmask";
		}

		$edits = $dbr->selectRowCount(
			[
				'revision_actor_temp',
				'revision',
				'actor'
			],
			'1',
			$cond,
			__METHOD__,
			[ 'LIMIT' => self::LIMIT + 1 ], // extra to detect truncation
			[
				'revision' => [
					'JOIN',
					'revactor_rev = rev_id AND revactor_page = rev_page'
				],
				'actor' => [
					'JOIN',
					'revactor_actor = actor_id'
				]
			]
		);
		return $edits;
	}

	/**
	 * @param int $pageId the id of the page to load history for
	 * @return int the count
	 */
	protected function getBotCount( $pageId ) {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );

		$cond = [
			'rev_page' => $pageId,
			'EXISTS(' .
				$dbr->selectSQLText(
					'user_groups',
					1,
					[
						'actor.actor_user = ug_user',
						'ug_group' => $this->permissionManager->getGroupsWithPermission( 'bot' ),
						'ug_expiry IS NULL OR ug_expiry >= ' . $dbr->addQuotes( $dbr->timestamp() )
					],
					__METHOD__
				) .
			')'
		];
		$bitmask = $this->getBitmask();
		if ( $bitmask ) {
			$cond[] = $dbr->bitAnd( 'rev_deleted', $bitmask ) . " != $bitmask";
		}

		$edits = $dbr->selectRowCount(
			[
				'revision_actor_temp',
				'revision',
				'actor',
			],
			'1',
			$cond,
			__METHOD__,
			[ 'LIMIT' => self::LIMIT + 1 ], // extra to detect truncation
			[
				'revision' => [
					'JOIN',
					'revactor_rev = rev_id AND revactor_page = rev_page'
				],
				'actor' => [
					'JOIN',
					'revactor_actor = actor_id'
				],
			]
		);
		return $edits;
	}

	/**
	 * @param int $pageId the id of the page to load history for
	 * @return int the count
	 * @throws LocalizedHttpException
	 */
	protected function getEditorsCount( $pageId ) {
		$from = $this->getValidatedParams()['from'] ?? null;
		$to = $this->getValidatedParams()['to'] ?? null;
		$fromRev = $from ? $this->getRevisionOrThrow( $from ) : null;
		$toRev = $to ? $this->getRevisionOrThrow( $to ) : null;

		// Reorder from and to parameters if they are out of order.
		if ( $fromRev && $toRev && ( $fromRev->getTimestamp() > $toRev->getTimestamp() ||
				( $fromRev->getTimestamp() === $toRev->getTimestamp() && $from > $to ) )
		) {
			$tmp = $fromRev;
			$fromRev = $toRev;
			$toRev = $tmp;
		}

		return $this->revisionStore->countAuthorsBetween( $pageId, $fromRev,
			$toRev, $this->user, self::LIMIT );
	}

	/**
	 * @param int $pageId the id of the page to load history for
	 * @return int the count
	 */
	protected function getRevertedCount( $pageId ) {
		$tagIds = [];

		foreach ( self::REVERTED_TAG_NAMES as $tagName ) {
			try {
				$tagIds[] = $this->changeTagDefStore->getId( $tagName );
			} catch ( NameTableAccessException $e ) {
				// If no revisions are tagged with a name, no tag id will be present
			}
		}
		if ( !$tagIds ) {
			return 0;
		}

		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$edits = $dbr->selectRowCount(
			[
				'revision',
				'change_tag'
			],
			'1',
			[ 'rev_page' => $pageId ],
			__METHOD__,
			[
				'LIMIT' => self::LIMIT + 1, // extra to detect truncation
				'GROUP BY' => 'rev_id'
			],
			[
				'change_tag' => [
					'JOIN',
					[
						'ct_rev_id = rev_id',
						'ct_tag_id' => $tagIds,
					]
				],
			]
		);
		return $edits;
	}

	/**
	 * @param int $pageId the id of the page to load history for
	 * @return int the count
	 */
	protected function getMinorCount( $pageId ) {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$edits = $dbr->selectRowCount( 'revision', '1',
			[
				'rev_page' => $pageId,
				'rev_minor_edit != 0'
			],
			__METHOD__,
			[ 'LIMIT' => self::LIMIT + 1 ] // extra to detect truncation
		);

		return $edits;
	}

	/**
	 * @param int $pageId the id of the page to load history for
	 * @param int $limit
	 * @return int the count
	 * @throws LocalizedHttpException
	 */
	protected function getEditsCount( $pageId, $limit ) {
		$from = $this->getValidatedParams()['from'] ?? null;
		$to = $this->getValidatedParams()['to'] ?? null;
		$fromRev = $from ? $this->getRevisionOrThrow( $from ) : null;
		$toRev = $to ? $this->getRevisionOrThrow( $to ) : null;

		// Reorder from and to parameters if they are out of order.
		if ( $fromRev && $toRev && ( $fromRev->getTimestamp() > $toRev->getTimestamp() ||
			( $fromRev->getTimestamp() === $toRev->getTimestamp() && $from > $to ) )
		) {
			$tmp = $fromRev;
			$fromRev = $toRev;
			$toRev = $tmp;
		}
		return $this->revisionStore->countRevisionsBetween(
			$pageId,
			$fromRev,
			$toRev,
			$limit // Will be increased by 1 to detect truncation
		);
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
	 * @param int $revId
	 * @return RevisionRecord
	 * @throws LocalizedHttpException
	 */
	private function getRevisionOrThrow( $revId ) {
		$rev = $this->revisionStore->getRevisionById( $revId );
		if ( !$rev ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-pagehistorycount-revision-nonexistent', [ $revId ] ),
				404
			);
		}
		return $rev;
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
			'type' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => array_merge(
					self::ALLOWED_COUNT_TYPES,
					self::DEPRECATED_COUNT_TYPES
				),
				ParamValidator::PARAM_REQUIRED => true,
			],
			'from' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => false
			],
			'to' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => false
			]
		];
	}
}
