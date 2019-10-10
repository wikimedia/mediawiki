<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\Response;
use MediaWiki\Revision\RevisionRecord;
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
	const ALLOWED_COUNT_TYPES = [ 'anonedits', 'botedits', 'editors', 'edits', 'revertededits' ];
	const REVERTED_TAG_NAMES = [ 'mw-undo', 'mw-rollback' ];

	/** @var NameTableStore */
	private $changeTagDefStore;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var User */
	private $user;

	/**
	 * @param NameTableStoreFactory $nameTableStoreFactory
	 * @param PermissionManager $permissionManager
	 * @param ILoadBalancer $loadBalancer
	 */
	public function __construct(
		NameTableStoreFactory $nameTableStoreFactory,
		PermissionManager $permissionManager,
		ILoadBalancer $loadBalancer
	) {
		$this->changeTagDefStore = $nameTableStoreFactory->getChangeTagDef();
		$this->permissionManager = $permissionManager;
		$this->loadBalancer = $loadBalancer;

		// @todo Inject this, when there is a good way to do that
		$this->user = RequestContext::getMain()->getUser();
	}

	/**
	 * @param string $title
	 * @param string $type
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( $title, $type ) {
		$titleObj = Title::newFromText( $title );
		if ( !$titleObj || !$titleObj->getArticleID() ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-pagehistorycount-title-nonexistent',
					[ new ScalarParam( ParamType::PLAINTEXT, $title ) ]
				),
				404
			);
		}

		$count = $this->getCount( $titleObj, $type );
		return $this->getResponseFactory()->createJson( [ 'count' => $count ] );
	}

	/**
	 * @param Title $titleObj title object identifying the page to load history for
	 * @param string $type the validated count type
	 * @return int the count
	 * @throws LocalizedHttpException
	 */
	protected function getCount( $titleObj, $type ) {
		switch ( $type ) {
			case 'anonedits':
				return $this->getAnonEditsCount( $titleObj );

			case 'botedits':
				return $this->getBotEditsCount( $titleObj );

			case 'editors':
				return $this->getEditorsCount( $titleObj );

			case 'edits':
				return $this->getEditsCount( $titleObj );

			case 'revertededits':
				return $this->getRevertedEditsCount( $titleObj );

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
	 * @param Title $titleObj title object identifying the page to load history for
	 * @return int the count
	 */
	protected function getAnonEditsCount( $titleObj ) {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );

		$cond = [
			'rev_page' => $titleObj->getArticleID(),
			'actor_user IS NULL',
		];
		$bitmask = $this->getBitmask();
		if ( $bitmask ) {
			$cond[] = $dbr->bitAnd( 'rev_deleted', $bitmask ) . " != $bitmask";
		}

		$edits = (int)$dbr->selectField(
			[
				'revision_actor_temp',
				'revision',
				'actor'
			],
			'COUNT(*)',
			$cond,
			__METHOD__,
			[],
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
	 * @param Title $titleObj title object identifying the page to load history for
	 * @return int the count
	 */
	protected function getBotEditsCount( $titleObj ) {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );

		$cond = [
			'rev_page' => $titleObj->getArticleID(),
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

		$edits = (int)$dbr->selectField(
			[
				'revision_actor_temp',
				'revision',
				'actor',
			],
			'COUNT(*)',
			$cond,
			__METHOD__,
			[],
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
	 * @param Title $titleObj title object identifying the page to load history for
	 * @return int the count
	 */
	protected function getEditorsCount( $titleObj ) {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );

		$cond = [
			'rev_page' => $titleObj->getArticleID(),
		];
		$bitmask = $this->getBitmask();
		if ( $bitmask ) {
			$cond[] = $dbr->bitAnd( 'rev_deleted', $bitmask ) . " != $bitmask";
		}

		$edits = (int)$dbr->selectField(
			[
				'revision_actor_temp',
				'revision'
			],
			'COUNT(DISTINCT revactor_actor)',
			$cond,
			__METHOD__,
			[],
			[
				'revision' => [
					'JOIN',
					'revactor_rev = rev_id AND revactor_page = rev_page'
				]
			]
		);
		return $edits;
	}

	/**
	 * @param Title $titleObj title object identifying the page to load history for
	 * @return int the count
	 */
	protected function getEditsCount( $titleObj ) {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$edits = (int)$dbr->selectField(
			'revision',
			'COUNT(*)',
			[ 'rev_page' => $titleObj->getArticleID() ],
			__METHOD__
		);
		return $edits;
	}

	/**
	 * @param Title $titleObj title object identifying the page to load history for
	 * @return int the count
	 */
	protected function getRevertedEditsCount( $titleObj ) {
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
		$edits = (int)$dbr->selectField(
			[
				'revision',
				'change_tag'
			],
			'COUNT(DISTINCT rev_id)',
			[ 'rev_page' => $titleObj->getArticleID() ],
			__METHOD__,
			[],
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
				ParamValidator::PARAM_TYPE => self::ALLOWED_COUNT_TYPES,
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}
}
