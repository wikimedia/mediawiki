<?php
/**
 * Copyright © 2014 Wikimedia Foundation and contributors
 *
 * Heavily based on ApiQueryDeletedrevs,
 * Copyright © 2007 Roan Kattouw <roan.kattouw@gmail.com>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Title\Title;
use MediaWiki\User\TempUser\TempUserCreator;
use MediaWiki\User\UserFactory;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Query module to enumerate deleted revisions for pages.
 *
 * @ingroup API
 */
class ApiQueryDeletedRevisions extends ApiQueryRevisionsBase {

	private RevisionStore $revisionStore;
	private NameTableStore $changeTagDefStore;
	private ChangeTagsStore $changeTagsStore;
	private LinkBatchFactory $linkBatchFactory;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		RevisionStore $revisionStore,
		IContentHandlerFactory $contentHandlerFactory,
		ParserFactory $parserFactory,
		SlotRoleRegistry $slotRoleRegistry,
		NameTableStore $changeTagDefStore,
		ChangeTagsStore $changeTagsStore,
		LinkBatchFactory $linkBatchFactory,
		ContentRenderer $contentRenderer,
		ContentTransformer $contentTransformer,
		CommentFormatter $commentFormatter,
		TempUserCreator $tempUserCreator,
		UserFactory $userFactory
	) {
		parent::__construct(
			$query,
			$moduleName,
			'drv',
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
		$this->changeTagDefStore = $changeTagDefStore;
		$this->changeTagsStore = $changeTagsStore;
		$this->linkBatchFactory = $linkBatchFactory;
	}

	protected function run( ?ApiPageSet $resultPageSet = null ) {
		$pageSet = $this->getPageSet();
		$pageMap = $pageSet->getGoodAndMissingTitlesByNamespace();
		$pageCount = count( $pageSet->getGoodAndMissingPages() );
		$revCount = $pageSet->getRevisionCount();
		if ( $revCount === 0 && $pageCount === 0 ) {
			// Nothing to do
			return;
		}
		if ( $revCount !== 0 && count( $pageSet->getDeletedRevisionIDs() ) === 0 ) {
			// Nothing to do, revisions were supplied but none are deleted
			return;
		}

		$params = $this->extractRequestParams( false );

		$db = $this->getDB();

		$this->requireMaxOneParameter( $params, 'user', 'excludeuser' );

		if ( $resultPageSet === null ) {
			$this->parseParameters( $params );
			$arQuery = $this->revisionStore->getArchiveQueryInfo();
			$this->addTables( $arQuery['tables'] );
			$this->addFields( $arQuery['fields'] );
			$this->addJoinConds( $arQuery['joins'] );
			$this->addFields( [ 'ar_title', 'ar_namespace' ] );
		} else {
			$this->limit = $this->getParameter( 'limit' ) ?: 10;
			$this->addTables( 'archive' );
			$this->addFields( [ 'ar_title', 'ar_namespace', 'ar_timestamp', 'ar_rev_id', 'ar_id' ] );
		}

		if ( $this->fld_tags ) {
			$this->addFields( [
				'ts_tags' => $this->changeTagsStore->makeTagSummarySubquery( 'archive' )
			] );
		}

		if ( $params['tag'] !== null ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds(
				[ 'change_tag' => [ 'JOIN', [ 'ar_rev_id=ct_rev_id' ] ] ]
			);
			try {
				$this->addWhereFld( 'ct_tag_id', $this->changeTagDefStore->getId( $params['tag'] ) );
			} catch ( NameTableAccessException ) {
				// Return nothing.
				$this->addWhere( '1=0' );
			}
		}

		// This means stricter restrictions
		if ( ( $this->fld_comment || $this->fld_parsedcomment ) &&
			!$this->getAuthority()->isAllowed( 'deletedhistory' )
		) {
			$this->dieWithError( 'apierror-cantview-deleted-comment', 'permissiondenied' );
		}
		if ( $this->fetchContent && !$this->getAuthority()->isAllowedAny( 'deletedtext', 'undelete' ) ) {
			$this->dieWithError( 'apierror-cantview-deleted-revision-content', 'permissiondenied' );
		}

		$dir = $params['dir'];

		if ( $revCount !== 0 ) {
			$this->addWhere( [
				'ar_rev_id' => array_keys( $pageSet->getDeletedRevisionIDs() )
			] );
		} else {
			// We need a custom WHERE clause that matches all titles.
			$lb = $this->linkBatchFactory->newLinkBatch( $pageSet->getGoodAndMissingPages() );
			$where = $lb->constructSet( 'ar', $db );
			$this->addWhere( $where );
		}

		if ( $params['user'] !== null || $params['excludeuser'] !== null ) {
			// In the non-generator case, the actor join will already be present.
			if ( $resultPageSet !== null ) {
				$this->addTables( 'actor' );
				$this->addJoinConds( [ 'actor' => [ 'JOIN', 'actor_id=ar_actor' ] ] );
			}
			if ( $params['user'] !== null ) {
				$this->addWhereFld( 'actor_name', $params['user'] );
			} elseif ( $params['excludeuser'] !== null ) {
				$this->addWhere( $db->expr( 'actor_name', '!=', $params['excludeuser'] ) );
			}
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
				$this->addWhere( $db->bitAnd( 'ar_deleted', $bitmask ) . " != $bitmask" );
			}
		}

		if ( $params['continue'] !== null ) {
			$op = ( $dir == 'newer' ? '>=' : '<=' );
			if ( $revCount !== 0 ) {
				$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'int', 'int' ] );
				$this->addWhere( $db->buildComparison( $op, [
					'ar_rev_id' => $cont[0],
					'ar_id' => $cont[1],
				] ) );
			} else {
				$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'int', 'string', 'timestamp', 'int' ] );
				$this->addWhere( $db->buildComparison( $op, [
					'ar_namespace' => $cont[0],
					'ar_title' => $cont[1],
					'ar_timestamp' => $db->timestamp( $cont[2] ),
					'ar_id' => $cont[3],
				] ) );
			}
		}

		$this->addOption( 'LIMIT', $this->limit + 1 );

		if ( $revCount !== 0 ) {
			// Sort by ar_rev_id when querying by ar_rev_id
			$this->addWhereRange( 'ar_rev_id', $dir, null, null );
		} else {
			// Sort by ns and title in the same order as timestamp for efficiency
			// But only when not already unique in the query
			if ( count( $pageMap ) > 1 ) {
				$this->addWhereRange( 'ar_namespace', $dir, null, null );
			}
			$oneTitle = key( reset( $pageMap ) );
			foreach ( $pageMap as $pages ) {
				if ( count( $pages ) > 1 || key( $pages ) !== $oneTitle ) {
					$this->addWhereRange( 'ar_title', $dir, null, null );
					break;
				}
			}
			$this->addTimestampWhereRange( 'ar_timestamp', $dir, $params['start'], $params['end'] );
		}
		// Include in ORDER BY for uniqueness
		$this->addWhereRange( 'ar_id', $dir, null, null );

		$res = $this->select( __METHOD__ );
		$count = 0;
		$generated = [];
		foreach ( $res as $row ) {
			if ( ++$count > $this->limit ) {
				// We've had enough
				$this->setContinueEnumParameter( 'continue',
					$revCount
						? "$row->ar_rev_id|$row->ar_id"
						: "$row->ar_namespace|$row->ar_title|$row->ar_timestamp|$row->ar_id"
				);
				break;
			}

			if ( $resultPageSet !== null ) {
				$generated[] = $row->ar_rev_id;
			} else {
				if ( !isset( $pageMap[$row->ar_namespace][$row->ar_title] ) ) {
					// Was it converted?
					$title = Title::makeTitle( $row->ar_namespace, $row->ar_title );
					$converted = $pageSet->getConvertedTitles();
					if ( $title && isset( $converted[$title->getPrefixedText()] ) ) {
						$title = Title::newFromText( $converted[$title->getPrefixedText()] );
						if ( $title && isset( $pageMap[$title->getNamespace()][$title->getDBkey()] ) ) {
							$pageMap[$row->ar_namespace][$row->ar_title] =
								$pageMap[$title->getNamespace()][$title->getDBkey()];
						}
					}
				}
				if ( !isset( $pageMap[$row->ar_namespace][$row->ar_title] ) ) {
					ApiBase::dieDebug(
						__METHOD__,
						"Found row in archive (ar_id={$row->ar_id}) that didn't get processed by ApiPageSet"
					);
				}

				$fit = $this->addPageSubItem(
					$pageMap[$row->ar_namespace][$row->ar_title],
					$this->extractRevisionInfo( $this->revisionStore->newRevisionFromArchiveRow( $row ), $row ),
					'rev'
				);
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue',
						$revCount
							? "$row->ar_rev_id|$row->ar_id"
							: "$row->ar_namespace|$row->ar_title|$row->ar_timestamp|$row->ar_id"
					);
					break;
				}
			}
		}

		if ( $resultPageSet !== null ) {
			$resultPageSet->populateFromRevisionIDs( $generated );
		}
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return parent::getAllowedParams() + [
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
			'tag' => null,
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
			],
			'excludeuser' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$title = Title::newMainPage();
		$talkTitle = $title->getTalkPageIfDefined();
		$examples = [
			'action=query&prop=deletedrevisions&revids=123456'
				=> 'apihelp-query+deletedrevisions-example-revids',
		];

		if ( $talkTitle ) {
			$title = rawurlencode( $title->getPrefixedText() );
			$talkTitle = rawurlencode( $talkTitle->getPrefixedText() );
			$examples["action=query&prop=deletedrevisions&titles={$title}|{$talkTitle}&" .
				'drvslots=*&drvprop=user|comment|content'] = 'apihelp-query+deletedrevisions-example-titles';
		}

		return $examples;
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Deletedrevisions';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryDeletedRevisions::class, 'ApiQueryDeletedRevisions' );
