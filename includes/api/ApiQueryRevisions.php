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

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\NameTableAccessException;

/**
 * A query action to enumerate revisions of a given page, or show top revisions
 * of multiple pages. Various pieces of information may be shown - flags,
 * comments, and the actual wiki markup of the rev. In the enumeration mode,
 * ranges of revisions may be requested and filtered.
 *
 * @ingroup API
 */
class ApiQueryRevisions extends ApiQueryRevisionsBase {

	private $token = null;

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'rv' );
	}

	private $tokenFunctions;

	/** @deprecated since 1.24 */
	protected function getTokenFunctions() {
		// tokenname => function
		// function prototype is func($pageid, $title, $rev)
		// should return token or false

		// Don't call the hooks twice
		if ( isset( $this->tokenFunctions ) ) {
			return $this->tokenFunctions;
		}

		// If we're in a mode that breaks the same-origin policy, no tokens can
		// be obtained
		if ( $this->lacksSameOriginSecurity() ) {
			return [];
		}

		$this->tokenFunctions = [
			'rollback' => [ self::class, 'getRollbackToken' ]
		];
		Hooks::run( 'APIQueryRevisionsTokens', [ &$this->tokenFunctions ] );

		return $this->tokenFunctions;
	}

	/**
	 * @deprecated since 1.24
	 * @param int $pageid
	 * @param Title $title
	 * @param Revision $rev
	 * @return bool|string
	 */
	public static function getRollbackToken( $pageid, $title, $rev ) {
		global $wgUser;
		if ( !$wgUser->isAllowed( 'rollback' ) ) {
			return false;
		}

		return $wgUser->getEditToken( 'rollback' );
	}

	protected function run( ApiPageSet $resultPageSet = null ) {
		global $wgChangeTagsSchemaMigrationStage;

		$params = $this->extractRequestParams( false );
		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();

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
				[ 'apierror-revisions-nolist', $this->getModulePrefix() ], 'invalidparammix'
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

		if ( $resultPageSet === null ) {
			$this->parseParameters( $params );
			$this->token = $params['token'];
			$opts = [];
			if ( $this->token !== null || $pageCount > 0 ) {
				$opts[] = 'page';
			}
			if ( $this->fetchContent ) {
				$opts[] = 'text';
			}
			if ( $this->fld_user ) {
				$opts[] = 'user';
			}
			$revQuery = $revisionStore->getQueryInfo( $opts );
			$this->addTables( $revQuery['tables'] );
			$this->addFields( $revQuery['fields'] );
			$this->addJoinConds( $revQuery['joins'] );
		} else {
			$this->limit = $this->getParameter( 'limit' ) ?: 10;
			// Always join 'page' so orphaned revisions are filtered out
			$this->addTables( [ 'revision', 'page' ] );
			$this->addJoinConds(
				[ 'page' => [ 'INNER JOIN', [ 'page_id = rev_page' ] ] ]
			);
			$this->addFields( [ 'rev_id', 'rev_timestamp', 'rev_page' ] );
		}

		if ( $this->fld_tags ) {
			$this->addTables( 'tag_summary' );
			$this->addJoinConds(
				[ 'tag_summary' => [ 'LEFT JOIN', [ 'rev_id=ts_rev_id' ] ] ]
			);
			$this->addFields( 'ts_tags' );
		}

		if ( $params['tag'] !== null ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds(
				[ 'change_tag' => [ 'INNER JOIN', [ 'rev_id=ct_rev_id' ] ] ]
			);
			if ( $wgChangeTagsSchemaMigrationStage > MIGRATION_WRITE_BOTH ) {
				$changeTagDefStore = MediaWikiServices::getInstance()->getChangeTagDefStore();
				try {
					$this->addWhereFld( 'ct_tag_id', $changeTagDefStore->getId( $params['tag'] ) );
				} catch ( NameTableAccessException $exception ) {
					// Return nothing.
					$this->addWhere( '1=0' );
				}
			} else {
				$this->addWhereFld( 'ct_tag', $params['tag'] );
			}
		}

		if ( $resultPageSet === null && $this->fetchContent ) {
			// For each page we will request, the user must have read rights for that page
			$user = $this->getUser();
			$status = Status::newGood();
			/** @var Title $title */
			foreach ( $pageSet->getGoodTitles() as $title ) {
				if ( !$title->userCan( 'read', $user ) ) {
					$status->fatal( ApiMessage::create(
						[ 'apierror-cannotviewtitle', wfEscapeWikiText( $title->getPrefixedText() ) ],
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
			//  page_user_timestamp if we have a logged-in rvuser
			//  page_timestamp or usertext_timestamp if we have an IP rvuser

			// This is mostly to prevent parameter errors (and optimize SQL?)
			$this->requireMaxOneParameter( $params, 'startid', 'start' );
			$this->requireMaxOneParameter( $params, 'endid', 'end' );
			$this->requireMaxOneParameter( $params, 'user', 'excludeuser' );

			if ( $params['continue'] !== null ) {
				$cont = explode( '|', $params['continue'] );
				$this->dieContinueUsageIf( count( $cont ) != 2 );
				$op = ( $params['dir'] === 'newer' ? '>' : '<' );
				$continueTimestamp = $db->addQuotes( $db->timestamp( $cont[0] ) );
				$continueId = (int)$cont[1];
				$this->dieContinueUsageIf( $continueId != $cont[1] );
				$this->addWhere( "rev_timestamp $op $continueTimestamp OR " .
					"(rev_timestamp = $continueTimestamp AND " .
					"rev_id $op= $continueId)"
				);
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
				$sql = $db->unionQueries( [
					$db->selectSQLText(
						'revision',
						[ 'id' => 'rev_id', 'ts' => 'rev_timestamp' ],
						[ 'rev_id' => $revids ],
						__METHOD__
					),
					$db->selectSQLText(
						'archive',
						[ 'id' => 'ar_rev_id', 'ts' => 'ar_timestamp' ],
						[ 'ar_rev_id' => $revids ],
						__METHOD__
					),
				], false );
				$res = $db->query( $sql, __METHOD__ );
				foreach ( $res as $row ) {
					if ( (int)$row->id === (int)$params['startid'] ) {
						$params['start'] = $row->ts;
					}
					if ( (int)$row->id === (int)$params['endid'] ) {
						$params['end'] = $row->ts;
					}
				}
				if ( $params['startid'] !== null && $params['start'] === null ) {
					$p = $this->encodeParamName( 'startid' );
					$this->dieWithError( [ 'apierror-revisions-badid', $p ], "badid_$p" );
				}
				if ( $params['endid'] !== null && $params['end'] === null ) {
					$p = $this->encodeParamName( 'endid' );
					$this->dieWithError( [ 'apierror-revisions-badid', $p ], "badid_$p" );
				}

				if ( $params['start'] !== null ) {
					$op = ( $params['dir'] === 'newer' ? '>' : '<' );
					$ts = $db->addQuotes( $db->timestampOrNull( $params['start'] ) );
					if ( $params['startid'] !== null ) {
						$this->addWhere( "rev_timestamp $op $ts OR "
							. "rev_timestamp = $ts AND rev_id $op= " . intval( $params['startid'] ) );
					} else {
						$this->addWhere( "rev_timestamp $op= $ts" );
					}
				}
				if ( $params['end'] !== null ) {
					$op = ( $params['dir'] === 'newer' ? '<' : '>' ); // Yes, opposite of the above
					$ts = $db->addQuotes( $db->timestampOrNull( $params['end'] ) );
					if ( $params['endid'] !== null ) {
						$this->addWhere( "rev_timestamp $op $ts OR "
							. "rev_timestamp = $ts AND rev_id $op= " . intval( $params['endid'] ) );
					} else {
						$this->addWhere( "rev_timestamp $op= $ts" );
					}
				}
			} else {
				$this->addTimestampWhereRange( 'rev_timestamp', $params['dir'],
					$params['start'], $params['end'] );
			}

			$sort = ( $params['dir'] === 'newer' ? '' : 'DESC' );
			$this->addOption( 'ORDER BY', [ "rev_timestamp $sort", "rev_id $sort" ] );

			// There is only one ID, use it
			$ids = array_keys( $pageSet->getGoodTitles() );
			$this->addWhereFld( 'rev_page', reset( $ids ) );

			if ( $params['user'] !== null ) {
				$actorQuery = ActorMigration::newMigration()
					->getWhere( $db, 'rev_user', User::newFromName( $params['user'], false ) );
				$this->addTables( $actorQuery['tables'] );
				$this->addJoinConds( $actorQuery['joins'] );
				$this->addWhere( $actorQuery['conds'] );
			} elseif ( $params['excludeuser'] !== null ) {
				$actorQuery = ActorMigration::newMigration()
					->getWhere( $db, 'rev_user', User::newFromName( $params['excludeuser'], false ) );
				$this->addTables( $actorQuery['tables'] );
				$this->addJoinConds( $actorQuery['joins'] );
				$this->addWhere( 'NOT(' . $actorQuery['conds'] . ')' );
			}
			if ( $params['user'] !== null || $params['excludeuser'] !== null ) {
				// Paranoia: avoid brute force searches (T19342)
				if ( !$this->getUser()->isAllowed( 'deletedhistory' ) ) {
					$bitmask = RevisionRecord::DELETED_USER;
				} elseif ( !$this->getUser()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
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
				$this->addWhere( 'rev_id >= ' . intval( $params['continue'] ) );
			}
			$this->addOption( 'ORDER BY', 'rev_id' );
		} elseif ( $pageCount > 0 ) {
			// Always targets the rev_page_id index

			$titles = $pageSet->getGoodTitles();

			// When working in multi-page non-enumeration mode,
			// limit to the latest revision only
			$this->addWhere( 'page_latest=rev_id' );

			// Get all page IDs
			$this->addWhereFld( 'page_id', array_keys( $titles ) );
			// Every time someone relies on equality propagation, god kills a kitten :)
			$this->addWhereFld( 'rev_page', array_keys( $titles ) );

			if ( $params['continue'] !== null ) {
				$cont = explode( '|', $params['continue'] );
				$this->dieContinueUsageIf( count( $cont ) != 2 );
				$pageid = intval( $cont[0] );
				$revid = intval( $cont[1] );
				$this->addWhere(
					"rev_page > $pageid OR " .
					"(rev_page = $pageid AND " .
					"rev_id >= $revid)"
				);
			}
			$this->addOption( 'ORDER BY', [
				'rev_page',
				'rev_id'
			] );
		} else {
			ApiBase::dieDebug( __METHOD__, 'param validation?' );
		}

		$this->addOption( 'LIMIT', $this->limit + 1 );

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
						$row->rev_timestamp . '|' . intval( $row->rev_id ) );
				} elseif ( $revCount > 0 ) {
					$this->setContinueEnumParameter( 'continue', intval( $row->rev_id ) );
				} else {
					$this->setContinueEnumParameter( 'continue', intval( $row->rev_page ) .
						'|' . intval( $row->rev_id ) );
				}
				break;
			}

			if ( $resultPageSet !== null ) {
				$generated[] = $row->rev_id;
			} else {
				$revision = $revisionStore->newRevisionFromRow( $row );
				$rev = $this->extractRevisionInfo( $revision, $row );

				if ( $this->token !== null ) {
					$title = Title::newFromLinkTarget( $revision->getPageAsLinkTarget() );
					$revisionCompat = new Revision( $revision );
					$tokenFunctions = $this->getTokenFunctions();
					foreach ( $this->token as $t ) {
						$val = call_user_func( $tokenFunctions[$t], $title->getArticleID(), $title, $revisionCompat );
						if ( $val === false ) {
							$this->addWarning( [ 'apiwarn-tokennotallowed', $t ] );
						} else {
							$rev[$t . 'token'] = $val;
						}
					}
				}

				$fit = $this->processRow( $row, $rev, $hookData ) &&
					$this->addPageSubItem( $row->rev_page, $rev, 'rev' );
				if ( !$fit ) {
					if ( $enumRevMode ) {
						$this->setContinueEnumParameter( 'continue',
							$row->rev_timestamp . '|' . intval( $row->rev_id ) );
					} elseif ( $revCount > 0 ) {
						$this->setContinueEnumParameter( 'continue', intval( $row->rev_id ) );
					} else {
						$this->setContinueEnumParameter( 'continue', intval( $row->rev_page ) .
							'|' . intval( $row->rev_id ) );
					}
					break;
				}
			}
		}

		if ( $resultPageSet !== null ) {
			$resultPageSet->populateFromRevisionIDs( $generated );
		}
	}

	public function getCacheMode( $params ) {
		if ( isset( $params['token'] ) ) {
			return 'private';
		}
		return parent::getCacheMode( $params );
	}

	public function getAllowedParams() {
		$ret = parent::getAllowedParams() + [
			'startid' => [
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'singlepageonly' ] ],
			],
			'endid' => [
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'singlepageonly' ] ],
			],
			'start' => [
				ApiBase::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'singlepageonly' ] ],
			],
			'end' => [
				ApiBase::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'singlepageonly' ] ],
			],
			'dir' => [
				ApiBase::PARAM_DFLT => 'older',
				ApiBase::PARAM_TYPE => [
					'newer',
					'older'
				],
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'singlepageonly' ] ],
			],
			'user' => [
				ApiBase::PARAM_TYPE => 'user',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'singlepageonly' ] ],
			],
			'excludeuser' => [
				ApiBase::PARAM_TYPE => 'user',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'singlepageonly' ] ],
			],
			'tag' => null,
			'token' => [
				ApiBase::PARAM_DEPRECATED => true,
				ApiBase::PARAM_TYPE => array_keys( $this->getTokenFunctions() ),
				ApiBase::PARAM_ISMULTI => true
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];

		$ret['limit'][ApiBase::PARAM_HELP_MSG_INFO] = [ [ 'singlepageonly' ] ];

		return $ret;
	}

	protected function getExamplesMessages() {
		return [
			'action=query&prop=revisions&titles=API|Main%20Page&' .
				'rvslots=*&rvprop=timestamp|user|comment|content'
				=> 'apihelp-query+revisions-example-content',
			'action=query&prop=revisions&titles=Main%20Page&rvlimit=5&' .
				'rvprop=timestamp|user|comment'
				=> 'apihelp-query+revisions-example-last5',
			'action=query&prop=revisions&titles=Main%20Page&rvlimit=5&' .
				'rvprop=timestamp|user|comment&rvdir=newer'
				=> 'apihelp-query+revisions-example-first5',
			'action=query&prop=revisions&titles=Main%20Page&rvlimit=5&' .
				'rvprop=timestamp|user|comment&rvdir=newer&rvstart=2006-05-01T00:00:00Z'
				=> 'apihelp-query+revisions-example-first5-after',
			'action=query&prop=revisions&titles=Main%20Page&rvlimit=5&' .
				'rvprop=timestamp|user|comment&rvexcludeuser=127.0.0.1'
				=> 'apihelp-query+revisions-example-first5-not-localhost',
			'action=query&prop=revisions&titles=Main%20Page&rvlimit=5&' .
				'rvprop=timestamp|user|comment&rvuser=MediaWiki%20default'
				=> 'apihelp-query+revisions-example-first5-user',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Revisions';
	}
}
