<?php
/**
 * Copyright © 2015 Wikimedia Foundation and contributors
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\MainConfigNames;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\ActorMigration;
use MediaWiki\User\TempUser\TempUserCreator;
use MediaWiki\User\UserFactory;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Query module to enumerate all revisions.
 *
 * @ingroup API
 * @since 1.27
 */
class ApiQueryAllRevisions extends ApiQueryRevisionsBase {

	private RevisionStore $revisionStore;
	private ActorMigration $actorMigration;
	private NamespaceInfo $namespaceInfo;
	private ChangeTagsStore $changeTagsStore;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		RevisionStore $revisionStore,
		IContentHandlerFactory $contentHandlerFactory,
		ParserFactory $parserFactory,
		SlotRoleRegistry $slotRoleRegistry,
		ActorMigration $actorMigration,
		NamespaceInfo $namespaceInfo,
		ChangeTagsStore $changeTagsStore,
		ContentRenderer $contentRenderer,
		ContentTransformer $contentTransformer,
		CommentFormatter $commentFormatter,
		TempUserCreator $tempUserCreator,
		UserFactory $userFactory
	) {
		parent::__construct(
			$query,
			$moduleName,
			'arv',
			$revisionStore,
			$contentHandlerFactory,
			$parserFactory,
			$slotRoleRegistry,
			$contentRenderer,
			$contentTransformer,
			$commentFormatter,
			$tempUserCreator,
			$userFactory
		);
		$this->revisionStore = $revisionStore;
		$this->actorMigration = $actorMigration;
		$this->namespaceInfo = $namespaceInfo;
		$this->changeTagsStore = $changeTagsStore;
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	protected function run( ?ApiPageSet $resultPageSet = null ) {
		$db = $this->getDB();
		$params = $this->extractRequestParams( false );

		$result = $this->getResult();

		$this->requireMaxOneParameter( $params, 'user', 'excludeuser' );

		$tsField = 'rev_timestamp';
		$idField = 'rev_id';
		$pageField = 'rev_page';

		// Namespace check is likely to be desired, but can't be done
		// efficiently in SQL.
		$miser_ns = null;
		$needPageTable = false;
		if ( $params['namespace'] !== null ) {
			$params['namespace'] = array_unique( $params['namespace'] );
			sort( $params['namespace'] );
			if ( $params['namespace'] != $this->namespaceInfo->getValidNamespaces() ) {
				$needPageTable = true;
				if ( $this->getConfig()->get( MainConfigNames::MiserMode ) ) {
					$miser_ns = $params['namespace'];
				} else {
					$this->addWhere( [ 'page_namespace' => $params['namespace'] ] );
				}
			}
		}

		if ( $resultPageSet === null ) {
			$this->parseParameters( $params );
			$queryBuilder = $this->revisionStore->newSelectQueryBuilder( $db )
				->joinComment()
				->joinPage();
			$this->getQueryBuilder()->merge( $queryBuilder );
		} else {
			$this->limit = $this->getParameter( 'limit' ) ?: 10;
			$this->addTables( [ 'revision' ] );
			$this->addFields( [ 'rev_timestamp', 'rev_id' ] );

			if ( $params['generatetitles'] ) {
				$this->addFields( [ 'rev_page' ] );
			}

			if ( $params['user'] !== null || $params['excludeuser'] !== null ) {
				$this->getQueryBuilder()->join( 'actor', 'actor_rev_user', 'actor_rev_user.actor_id = rev_actor' );
			}

			if ( $needPageTable ) {
				$this->getQueryBuilder()->join( 'page', null, [ "$pageField = page_id" ] );
				if ( (bool)$miser_ns ) {
					$this->addFields( [ 'page_namespace' ] );
				}
			}
		}

		// Seems to be needed to avoid a planner bug (T113901)
		$this->addOption( 'STRAIGHT_JOIN' );

		$dir = $params['dir'];
		$this->addTimestampWhereRange( $tsField, $dir, $params['start'], $params['end'] );

		if ( $this->fld_tags ) {
			$this->addFields( [
				'ts_tags' => $this->changeTagsStore->makeTagSummarySubquery( 'revision' )
			] );
		}

		if ( $params['user'] !== null ) {
			$actorQuery = $this->actorMigration->getWhere( $db, 'rev_user', $params['user'] );
			$this->addWhere( $actorQuery['conds'] );
		} elseif ( $params['excludeuser'] !== null ) {
			$actorQuery = $this->actorMigration->getWhere( $db, 'rev_user', $params['excludeuser'] );
			$this->addWhere( 'NOT(' . $actorQuery['conds'] . ')' );
		}

		if ( $params['user'] !== null || $params['excludeuser'] !== null ) {
			// Paranoia: avoid brute force searches (T19342)
			if ( !$this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
				$bitmask = RevisionRecord::DELETED_USER;
			} elseif ( !$this->getAuthority()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
				$bitmask = RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED;
			} else {
				$bitmask = 0;
			}
			if ( $bitmask ) {
				$this->addWhere( $db->bitAnd( 'rev_deleted', $bitmask ) . " != $bitmask" );
			}
		}

		if ( $params['continue'] !== null ) {
			$op = ( $dir == 'newer' ? '>=' : '<=' );
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'timestamp', 'int' ] );
			$this->addWhere( $db->buildComparison( $op, [
				$tsField => $db->timestamp( $cont[0] ),
				$idField => $cont[1],
			] ) );
		}

		$this->addOption( 'LIMIT', $this->limit + 1 );

		$sort = ( $dir == 'newer' ? '' : ' DESC' );
		$orderby = [];
		// Targeting index rev_timestamp, user_timestamp, usertext_timestamp, or actor_timestamp.
		// But 'user' is always constant for the latter three, so it doesn't matter here.
		$orderby[] = "rev_timestamp $sort";
		$orderby[] = "rev_id $sort";
		$this->addOption( 'ORDER BY', $orderby );

		$hookData = [];
		$res = $this->select( __METHOD__, [], $hookData );

		if ( $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__ );
		}

		$pageMap = []; // Maps rev_page to array index
		$count = 0;
		$nextIndex = 0;
		$generated = [];
		foreach ( $res as $row ) {
			if ( $count === 0 && $resultPageSet !== null ) {
				// Set the non-continue since the list of all revisions is
				// prone to having entries added at the start frequently.
				$this->getContinuationManager()->addGeneratorNonContinueParam(
					$this, 'continue', "$row->rev_timestamp|$row->rev_id"
				);
			}
			if ( ++$count > $this->limit ) {
				// We've had enough
				$this->setContinueEnumParameter( 'continue', "$row->rev_timestamp|$row->rev_id" );
				break;
			}

			// Miser mode namespace check
			if ( $miser_ns !== null && !in_array( $row->page_namespace, $miser_ns ) ) {
				continue;
			}

			if ( $resultPageSet !== null ) {
				if ( $params['generatetitles'] ) {
					$generated[$row->rev_page] = $row->rev_page;
				} else {
					$generated[] = $row->rev_id;
				}
			} else {
				$revision = $this->revisionStore->newRevisionFromRow( $row, 0, Title::newFromRow( $row ) );
				$rev = $this->extractRevisionInfo( $revision, $row );

				if ( !isset( $pageMap[$row->rev_page] ) ) {
					$index = $nextIndex++;
					$pageMap[$row->rev_page] = $index;
					$title = Title::newFromPageIdentity( $revision->getPage() );
					$a = [
						'pageid' => $title->getArticleID(),
						'revisions' => [ $rev ],
					];
					ApiResult::setIndexedTagName( $a['revisions'], 'rev' );
					ApiQueryBase::addTitleInfo( $a, $title );
					$fit = $this->processRow( $row, $a['revisions'][0], $hookData ) &&
						$result->addValue( [ 'query', $this->getModuleName() ], $index, $a );
				} else {
					$index = $pageMap[$row->rev_page];
					$fit = $this->processRow( $row, $rev, $hookData ) &&
						$result->addValue( [ 'query', $this->getModuleName(), $index, 'revisions' ], null, $rev );
				}
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', "$row->rev_timestamp|$row->rev_id" );
					break;
				}
			}
		}

		if ( $resultPageSet !== null ) {
			if ( $params['generatetitles'] ) {
				$resultPageSet->populateFromPageIDs( $generated );
			} else {
				$resultPageSet->populateFromRevisionIDs( $generated );
			}
		} else {
			$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'page' );
		}
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		$ret = parent::getAllowedParams() + [
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
				UserDef::PARAM_RETURN_OBJECT => true,
			],
			'namespace' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'namespace',
				ParamValidator::PARAM_DEFAULT => null,
			],
			'start' => [
				ParamValidator::PARAM_TYPE => 'timestamp',
			],
			'end' => [
				ParamValidator::PARAM_TYPE => 'timestamp',
			],
			'dir' => [
				ParamValidator::PARAM_TYPE => [
					'newer',
					'older'
				],
				ParamValidator::PARAM_DEFAULT => 'older',
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'newer' => 'api-help-paramvalue-direction-newer',
					'older' => 'api-help-paramvalue-direction-older',
				],
			],
			'excludeuser' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
				UserDef::PARAM_RETURN_OBJECT => true,
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'generatetitles' => [
				ParamValidator::PARAM_DEFAULT => false,
			],
		];

		if ( $this->getConfig()->get( MainConfigNames::MiserMode ) ) {
			$ret['namespace'][ApiBase::PARAM_HELP_MSG_APPEND] = [
				'api-help-param-limited-in-miser-mode',
			];
		}

		return $ret;
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=allrevisions&arvuser=Example&arvlimit=50'
				=> 'apihelp-query+allrevisions-example-user',
			'action=query&list=allrevisions&arvdir=newer&arvlimit=50'
				=> 'apihelp-query+allrevisions-example-ns-any',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Allrevisions';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryAllRevisions::class, 'ApiQueryAllRevisions' );
