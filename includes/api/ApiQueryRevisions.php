<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\Page\PageIdentity;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Status\Status;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\ActorMigration;
use MediaWiki\User\TempUser\TempUserCreator;
use MediaWiki\User\UserFactory;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * A query action to enumerate revisions of a given page, or show top revisions
 * of multiple pages. Various pieces of information may be shown - flags,
 * comments, and the actual wiki markup of the rev. In the enumeration mode,
 * ranges of revisions may be requested and filtered.
 *
 * @ingroup API
 */
class ApiQueryRevisions extends ApiQueryRevisionsBase {

	private RevisionStore $revisionStore;
	private NameTableStore $changeTagDefStore;
	private ChangeTagsStore $changeTagsStore;
	private ActorMigration $actorMigration;
	private TitleFormatter $titleFormatter;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		RevisionStore $revisionStore,
		IContentHandlerFactory $contentHandlerFactory,
		ParserFactory $parserFactory,
		SlotRoleRegistry $slotRoleRegistry,
		NameTableStore $changeTagDefStore,
		ChangeTagsStore $changeTagsStore,
		ActorMigration $actorMigration,
		ContentRenderer $contentRenderer,
		ContentTransformer $contentTransformer,
		CommentFormatter $commentFormatter,
		TempUserCreator $tempUserCreator,
		UserFactory $userFactory,
		TitleFormatter $titleFormatter
	) {
		parent::__construct(
			$query,
			$moduleName,
			'rv',
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
		$this->actorMigration = $actorMigration;
		$this->titleFormatter = $titleFormatter;
	}

	protected function run( ?ApiPageSet $resultPageSet = null ) {
		$params = $this->extractRequestParams( false );

		// If any of those parameters are used, work in 'enumeration' mode.
		// Enum mode can only be used when exactly one page is provided.
		// Enumerating revisions on multiple pages make it extremely
		// difficult to manage continuations and require additional SQL indexes
		$enumRevMode = ( $params['user'] !== null || $params['excludeuser'] !== null ||
			$params['limit'] !== null || $params['startid'] !== null ||
			$params['endid'] !== null || $params['dir'] === 'newer' ||
			$params['start'] !== null || $params['end'] !== null );

		$pageSet = $this->getPageSet();
		$pageCount = $pageSet->getGoodTitleCount();
		$revCount = $pageSet->getRevisionCount();

		// Optimization -- nothing to do
		if ( $revCount === 0 && $pageCount === 0 ) {
			// Nothing to do
			return;
		}
		if ( $revCount > 0 && count( $pageSet->getLiveRevisionIDs() ) === 0 ) {
			// We're in revisions mode but all given revisions are deleted
			return;
		}

		if ( $revCount > 0 && $enumRevMode ) {
			$this->dieWithError(
				[ 'apierror-revisions-norevids', $this->getModulePrefix() ], 'invalidparammix'
			);
		}

		if ( $pageCount > 1 && $enumRevMode ) {
			$this->dieWithError(
				[ 'apierror-revisions-singlepage', $this->getModulePrefix() ], 'invalidparammix'
			);
		}

		// In non-enum mode, rvlimit can't be directly used. Use the maximum
		// allowed value.
		if ( !$enumRevMode ) {
			$this->setParsedLimit = false;
			$params['limit'] = 'max';
		}

		$db = $this->getDB();

		$idField = 'rev_id';
		$tsField = 'rev_timestamp';
		$pageField = 'rev_page';

		$ignoreIndex = [
			// T224017: `rev_timestamp` is never the correct index to use for this module, but
			// MariaDB sometimes insists on trying to use it anyway. Tell it not to.
			// Last checked with MariaDB 10.4.13
			'revision' => 'rev_timestamp',
		];
		$useIndex = [];
		if ( $resultPageSet === null ) {
			$this->parseParameters( $params );
			$queryBuilder = $this->revisionStore->newSelectQueryBuilder( $db )
				->joinComment()
				->joinPage();
			if ( $this->fld_user ) {
				$queryBuilder->joinUser();
			}
			$this->getQueryBuilder()->merge( $queryBuilder );
		} else {
			$this->limit = $this->getParameter( 'limit' ) ?: 10;
			// Always join 'page' so orphaned revisions are filtered out
			$this->addTables( [ 'revision', 'page' ] );
			$this->addJoinConds(
				[ 'page' => [ 'JOIN', [ 'page_id = rev_page' ] ] ]
			);
			$this->addFields( [
				'rev_id' => $idField, 'rev_timestamp' => $tsField, 'rev_page' => $pageField
			] );
		}

		if ( $this->fld_tags ) {
			$this->addFields( [
				'ts_tags' => $this->changeTagsStore->makeTagSummarySubquery( 'revision' )
			] );
		}

		if ( $params['tag'] !== null ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds(
				[ 'change_tag' => [ 'JOIN', [ 'rev_id=ct_rev_id' ] ] ]
			);
			try {
				$this->addWhereFld( 'ct_tag_id', $this->changeTagDefStore->getId( $params['tag'] ) );
			} catch ( NameTableAccessException ) {
				// Return nothing.
				$this->addWhere( '1=0' );
			}
		}

		if ( $resultPageSet === null && $this->fetchContent ) {
			// For each page we will request, the user must have read rights for that page
			$status = Status::newGood();

			/** @var PageIdentity $pageIdentity */
			foreach ( $pageSet->getGoodPages() as $pageIdentity ) {
				if ( !$this->getAuthority()->authorizeRead( 'read', $pageIdentity ) ) {
					$status->fatal( ApiMessage::create(
						[
							'apierror-cannotviewtitle',
							wfEscapeWikiText( $this->titleFormatter->getPrefixedText( $pageIdentity ) ),
						],
						'accessdenied'
					) );
				}
			}
			if ( !$status->isGood() ) {
				$this->dieStatus( $status );
			}
		}

		if ( $enumRevMode ) {
			// Indexes targeted:
			//  page_timestamp if we don't have rvuser
			//  page_actor_timestamp (on revision_actor_temp) if we have rvuser in READ_NEW mode
			//  page_user_timestamp if we have a logged-in rvuser
			//  page_timestamp or usertext_timestamp if we have an IP rvuser

			// This is mostly to prevent parameter errors (and optimize SQL?)
			$this->requireMaxOneParameter( $params, 'startid', 'start' );
			$this->requireMaxOneParameter( $params, 'endid', 'end' );
			$this->requireMaxOneParameter( $params, 'user', 'excludeuser' );

			if ( $params['continue'] !== null ) {
				$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'timestamp', 'int' ] );
				$op = ( $params['dir'] === 'newer' ? '>=' : '<=' );
				$continueTimestamp = $db->timestamp( $cont[0] );
				$continueId = (int)$cont[1];
				$this->addWhere( $db->buildComparison( $op, [
					$tsField => $continueTimestamp,
					$idField => $continueId,
				] ) );
			}

			// Convert startid/endid to timestamps (T163532)
			$revids = [];
			if ( $params['startid'] !== null ) {
				$revids[] = (int)$params['startid'];
			}
			if ( $params['endid'] !== null ) {
				$revids[] = (int)$params['endid'];
			}
			if ( $revids ) {
				$db = $this->getDB();
				$uqb = $db->newUnionQueryBuilder();
				$uqb->add(
					$db->newSelectQueryBuilder()
						->select( [ 'id' => 'rev_id', 'ts' => 'rev_timestamp' ] )
						->from( 'revision' )
						->where( [ 'rev_id' => $revids ] )
				);
				$uqb->add(
					$db->newSelectQueryBuilder()
						->select( [ 'id' => 'ar_rev_id', 'ts' => 'ar_timestamp' ] )
						->from( 'archive' )
						->where( [ 'ar_rev_id' => $revids ] )
				);
				$res = $uqb->caller( __METHOD__ )->fetchResultSet();
				foreach ( $res as $row ) {
					if ( (int)$row->id === (int)$params['startid'] ) {
						$params['start'] = $row->ts;
					}
					if ( (int)$row->id === (int)$params['endid'] ) {
						$params['end'] = $row->ts;
					}
				}
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
				if ( $params['startid'] !== null && $params['start'] === null ) {
					$p = $this->encodeParamName( 'startid' );
					$this->dieWithError( [ 'apierror-revisions-badid', $p ], "badid_$p" );
				}
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
				if ( $params['endid'] !== null && $params['end'] === null ) {
					$p = $this->encodeParamName( 'endid' );
					$this->dieWithError( [ 'apierror-revisions-badid', $p ], "badid_$p" );
				}

				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
				if ( $params['start'] !== null ) {
					$op = ( $params['dir'] === 'newer' ? '>=' : '<=' );
					// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
					$ts = $db->timestampOrNull( $params['start'] );
					if ( $params['startid'] !== null ) {
						$this->addWhere( $db->buildComparison( $op, [
							$tsField => $ts,
							$idField => (int)$params['startid'],
						] ) );
					} else {
						$this->addWhere( $db->buildComparison( $op, [ $tsField => $ts ] ) );
					}
				}
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
				if ( $params['end'] !== null ) {
					$op = ( $params['dir'] === 'newer' ? '<=' : '>=' ); // Yes, opposite of the above
					// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
					$ts = $db->timestampOrNull( $params['end'] );
					if ( $params['endid'] !== null ) {
						$this->addWhere( $db->buildComparison( $op, [
							$tsField => $ts,
							$idField => (int)$params['endid'],
						] ) );
					} else {
						$this->addWhere( $db->buildComparison( $op, [ $tsField => $ts ] ) );
					}
				}
			} else {
				$this->addTimestampWhereRange( $tsField, $params['dir'],
					$params['start'], $params['end'] );
			}

			$sort = ( $params['dir'] === 'newer' ? '' : 'DESC' );
			$this->addOption( 'ORDER BY', [ "rev_timestamp $sort", "rev_id $sort" ] );

			// There is only one ID, use it
			$ids = array_keys( $pageSet->getGoodPages() );
			$this->addWhereFld( $pageField, reset( $ids ) );

			if ( $params['user'] !== null ) {
				$actorQuery = $this->actorMigration->getWhere( $db, 'rev_user', $params['user'] );
				$this->addTables( $actorQuery['tables'] );
				$this->addJoinConds( $actorQuery['joins'] );
				$this->addWhere( $actorQuery['conds'] );
			} elseif ( $params['excludeuser'] !== null ) {
				$actorQuery = $this->actorMigration->getWhere( $db, 'rev_user', $params['excludeuser'] );
				$this->addTables( $actorQuery['tables'] );
				$this->addJoinConds( $actorQuery['joins'] );
				$this->addWhere( 'NOT(' . $actorQuery['conds'] . ')' );
			} else {
				// T258480: MariaDB ends up using rev_page_actor_timestamp in some cases here.
				// Last checked with MariaDB 10.4.13
				// Unless we are filtering by user (see above), we always want to use the
				// "history" index on the revision table, namely page_timestamp.
				$useIndex['revision'] = 'rev_page_timestamp';
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
		} elseif ( $revCount > 0 ) {
			// Always targets the PRIMARY index

			$revs = $pageSet->getLiveRevisionIDs();

			// Get all revision IDs
			$this->addWhereFld( 'rev_id', array_keys( $revs ) );

			if ( $params['continue'] !== null ) {
				$this->addWhere( $db->buildComparison( '>=', [
					'rev_id' => (int)$params['continue']
				] ) );
			}
			$this->addOption( 'ORDER BY', 'rev_id' );
		} elseif ( $pageCount > 0 ) {
			// Always targets the rev_page_id index

			$pageids = array_keys( $pageSet->getGoodPages() );

			// When working in multi-page non-enumeration mode,
			// limit to the latest revision only
			$this->addWhere( 'page_latest=rev_id' );

			// Get all page IDs
			$this->addWhereFld( 'page_id', $pageids );
			// Every time someone relies on equality propagation, god kills a kitten :)
			$this->addWhereFld( 'rev_page', $pageids );

			if ( $params['continue'] !== null ) {
				$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'int', 'int' ] );
				$this->addWhere( $db->buildComparison( '>=', [
					'rev_page' => $cont[0],
					'rev_id' => $cont[1],
				] ) );
			}
			$this->addOption( 'ORDER BY', [
				'rev_page',
				'rev_id'
			] );
		} else {
			ApiBase::dieDebug( __METHOD__, 'param validation?' );
		}

		$this->addOption( 'LIMIT', $this->limit + 1 );

		$this->addOption( 'IGNORE INDEX', $ignoreIndex );

		if ( $useIndex ) {
			$this->addOption( 'USE INDEX', $useIndex );
		}

		$count = 0;
		$generated = [];
		$hookData = [];
		$res = $this->select( __METHOD__, [], $hookData );

		foreach ( $res as $row ) {
			if ( ++$count > $this->limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				if ( $enumRevMode ) {
					$this->setContinueEnumParameter( 'continue',
						$row->rev_timestamp . '|' . (int)$row->rev_id );
				} elseif ( $revCount > 0 ) {
					$this->setContinueEnumParameter( 'continue', (int)$row->rev_id );
				} else {
					$this->setContinueEnumParameter( 'continue', (int)$row->rev_page .
						'|' . (int)$row->rev_id );
				}
				break;
			}

			if ( $resultPageSet !== null ) {
				$generated[] = $row->rev_id;
			} else {
				$revision = $this->revisionStore->newRevisionFromRow( $row, 0, Title::newFromRow( $row ) );
				$rev = $this->extractRevisionInfo( $revision, $row );
				$fit = $this->processRow( $row, $rev, $hookData ) &&
					$this->addPageSubItem( $row->rev_page, $rev, 'rev' );
				if ( !$fit ) {
					if ( $enumRevMode ) {
						$this->setContinueEnumParameter( 'continue',
							$row->rev_timestamp . '|' . (int)$row->rev_id );
					} elseif ( $revCount > 0 ) {
						$this->setContinueEnumParameter( 'continue', (int)$row->rev_id );
					} else {
						$this->setContinueEnumParameter( 'continue', (int)$row->rev_page .
							'|' . (int)$row->rev_id );
					}
					break;
				}
			}
		}

		if ( $resultPageSet !== null ) {
			$resultPageSet->populateFromRevisionIDs( $generated );
		}
	}

	public function getAllowedParams() {
		$ret = parent::getAllowedParams() + [
			'startid' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'singlepageonly' ] ],
			],
			'endid' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'singlepageonly' ] ],
			],
			'start' => [
				ParamValidator::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'singlepageonly' ] ],
			],
			'end' => [
				ParamValidator::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'singlepageonly' ] ],
			],
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'older',
				ParamValidator::PARAM_TYPE => [
					'newer',
					'older'
				],
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'newer' => 'api-help-paramvalue-direction-newer',
					'older' => 'api-help-paramvalue-direction-older',
				],
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'singlepageonly' ] ],
			],
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
				UserDef::PARAM_RETURN_OBJECT => true,
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'singlepageonly' ] ],
			],
			'excludeuser' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
				UserDef::PARAM_RETURN_OBJECT => true,
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'singlepageonly' ] ],
			],
			'tag' => null,
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];

		$ret['limit'][ApiBase::PARAM_HELP_MSG_INFO] = [ [ 'singlepageonly' ] ];

		return $ret;
	}

	protected function getExamplesMessages() {
		$title = Title::newMainPage()->getPrefixedText();
		$mp = rawurlencode( $title );

		return [
			"action=query&prop=revisions&titles=API|{$mp}&" .
				'rvslots=*&rvprop=timestamp|user|comment|content'
				=> 'apihelp-query+revisions-example-content',
			"action=query&prop=revisions&titles={$mp}&rvlimit=5&" .
				'rvprop=timestamp|user|comment'
				=> 'apihelp-query+revisions-example-last5',
			"action=query&prop=revisions&titles={$mp}&rvlimit=5&" .
				'rvprop=timestamp|user|comment&rvdir=newer'
				=> 'apihelp-query+revisions-example-first5',
			"action=query&prop=revisions&titles={$mp}&rvlimit=5&" .
				'rvprop=timestamp|user|comment&rvdir=newer&rvstart=2006-05-01T00:00:00Z'
				=> 'apihelp-query+revisions-example-first5-after',
			"action=query&prop=revisions&titles={$mp}&rvlimit=5&" .
				'rvprop=timestamp|user|comment&rvexcludeuser=127.0.0.1'
				=> 'apihelp-query+revisions-example-first5-not-localhost',
			"action=query&prop=revisions&titles={$mp}&rvlimit=5&" .
				'rvprop=timestamp|user|comment&rvuser=MediaWiki%20default'
				=> 'apihelp-query+revisions-example-first5-user',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Revisions';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryRevisions::class, 'ApiQueryRevisions' );
