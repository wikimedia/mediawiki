<?php
/**
 * Copyright © 2014 Wikimedia Foundation and contributors
 *
 * Heavily based on ApiQueryDeletedrevs,
 * Copyright © 2007 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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
 * Query module to enumerate all deleted revisions.
 *
 * @ingroup API
 */
class ApiQueryAllDeletedRevisions extends ApiQueryRevisionsBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'adr' );
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	protected function run( ApiPageSet $resultPageSet = null ) {
		// Before doing anything at all, let's check permissions
		$this->checkUserRightsAny( 'deletedhistory' );

		$user = $this->getUser();
		$db = $this->getDB();
		$params = $this->extractRequestParams( false );
		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();

		$result = $this->getResult();

		// If the user wants no namespaces, they get no pages.
		if ( $params['namespace'] === [] ) {
			if ( $resultPageSet === null ) {
				$result->addValue( 'query', $this->getModuleName(), [] );
			}
			return;
		}

		// This module operates in two modes:
		// 'user': List deleted revs by a certain user
		// 'all': List all deleted revs in NS
		$mode = 'all';
		if ( !is_null( $params['user'] ) ) {
			$mode = 'user';
		}

		if ( $mode == 'user' ) {
			foreach ( [ 'from', 'to', 'prefix', 'excludeuser' ] as $param ) {
				if ( !is_null( $params[$param] ) ) {
					$p = $this->getModulePrefix();
					$this->dieWithError(
						[ 'apierror-invalidparammix-cannotusewith', $p . $param, "{$p}user" ],
						'invalidparammix'
					);
				}
			}
		} else {
			foreach ( [ 'start', 'end' ] as $param ) {
				if ( !is_null( $params[$param] ) ) {
					$p = $this->getModulePrefix();
					$this->dieWithError(
						[ 'apierror-invalidparammix-mustusewith', $p . $param, "{$p}user" ],
						'invalidparammix'
					);
				}
			}
		}

		// If we're generating titles only, we can use DISTINCT for a better
		// query. But we can't do that in 'user' mode (wrong index), and we can
		// only do it when sorting ASC (because MySQL apparently can't use an
		// index backwards for grouping even though it can for ORDER BY, WTF?)
		$dir = $params['dir'];
		$optimizeGenerateTitles = false;
		if ( $mode === 'all' && $params['generatetitles'] && $resultPageSet !== null ) {
			if ( $dir === 'newer' ) {
				$optimizeGenerateTitles = true;
			} else {
				$p = $this->getModulePrefix();
				$this->addWarning( [ 'apiwarn-alldeletedrevisions-performance', $p ], 'performance' );
			}
		}

		if ( $resultPageSet === null ) {
			$this->parseParameters( $params );
			$arQuery = $revisionStore->getArchiveQueryInfo();
			$this->addTables( $arQuery['tables'] );
			$this->addJoinConds( $arQuery['joins'] );
			$this->addFields( $arQuery['fields'] );
			$this->addFields( [ 'ar_title', 'ar_namespace' ] );
		} else {
			$this->limit = $this->getParameter( 'limit' ) ?: 10;
			$this->addTables( 'archive' );
			$this->addFields( [ 'ar_title', 'ar_namespace' ] );
			if ( $optimizeGenerateTitles ) {
				$this->addOption( 'DISTINCT' );
			} else {
				$this->addFields( [ 'ar_timestamp', 'ar_rev_id', 'ar_id' ] );
			}
		}

		if ( $this->fld_tags ) {
			$this->addFields( [ 'ts_tags' => ChangeTags::makeTagSummarySubquery( 'archive' ) ] );
		}

		if ( !is_null( $params['tag'] ) ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds(
				[ 'change_tag' => [ 'JOIN', [ 'ar_rev_id=ct_rev_id' ] ] ]
			);
			$changeTagDefStore = MediaWikiServices::getInstance()->getChangeTagDefStore();
			try {
				$this->addWhereFld( 'ct_tag_id', $changeTagDefStore->getId( $params['tag'] ) );
			} catch ( NameTableAccessException $exception ) {
				// Return nothing.
				$this->addWhere( '1=0' );
			}
		}

		if ( $this->fetchContent ) {
			$this->addTables( 'text' );
			$this->addJoinConds(
				[ 'text' => [ 'LEFT JOIN', [ 'ar_text_id=old_id' ] ] ]
			);
			$this->addFields( [ 'old_text', 'old_flags' ] );

			// This also means stricter restrictions
			$this->checkUserRightsAny( [ 'deletedtext', 'undelete' ] );
		}

		$miser_ns = null;

		if ( $mode == 'all' ) {
			$namespaces = $params['namespace'] ?? MWNamespace::getValidNamespaces();
			$this->addWhereFld( 'ar_namespace', $namespaces );

			// For from/to/prefix, we have to consider the potential
			// transformations of the title in all specified namespaces.
			// Generally there will be only one transformation, but wikis with
			// some namespaces case-sensitive could have two.
			if ( $params['from'] !== null || $params['to'] !== null ) {
				$isDirNewer = ( $dir === 'newer' );
				$after = ( $isDirNewer ? '>=' : '<=' );
				$before = ( $isDirNewer ? '<=' : '>=' );
				$where = [];
				foreach ( $namespaces as $ns ) {
					$w = [];
					if ( $params['from'] !== null ) {
						$w[] = 'ar_title' . $after .
							$db->addQuotes( $this->titlePartToKey( $params['from'], $ns ) );
					}
					if ( $params['to'] !== null ) {
						$w[] = 'ar_title' . $before .
							$db->addQuotes( $this->titlePartToKey( $params['to'], $ns ) );
					}
					$w = $db->makeList( $w, LIST_AND );
					$where[$w][] = $ns;
				}
				if ( count( $where ) == 1 ) {
					$where = key( $where );
					$this->addWhere( $where );
				} else {
					$where2 = [];
					foreach ( $where as $w => $ns ) {
						$where2[] = $db->makeList( [ $w, 'ar_namespace' => $ns ], LIST_AND );
					}
					$this->addWhere( $db->makeList( $where2, LIST_OR ) );
				}
			}

			if ( isset( $params['prefix'] ) ) {
				$where = [];
				foreach ( $namespaces as $ns ) {
					$w = 'ar_title' . $db->buildLike(
						$this->titlePartToKey( $params['prefix'], $ns ),
						$db->anyString() );
					$where[$w][] = $ns;
				}
				if ( count( $where ) == 1 ) {
					$where = key( $where );
					$this->addWhere( $where );
				} else {
					$where2 = [];
					foreach ( $where as $w => $ns ) {
						$where2[] = $db->makeList( [ $w, 'ar_namespace' => $ns ], LIST_AND );
					}
					$this->addWhere( $db->makeList( $where2, LIST_OR ) );
				}
			}
		} else {
			if ( $this->getConfig()->get( 'MiserMode' ) ) {
				$miser_ns = $params['namespace'];
			} else {
				$this->addWhereFld( 'ar_namespace', $params['namespace'] );
			}
			$this->addTimestampWhereRange( 'ar_timestamp', $dir, $params['start'], $params['end'] );
		}

		if ( !is_null( $params['user'] ) ) {
			// Don't query by user ID here, it might be able to use the ar_usertext_timestamp index.
			$actorQuery = ActorMigration::newMigration()
				->getWhere( $db, 'ar_user', User::newFromName( $params['user'], false ), false );
			$this->addTables( $actorQuery['tables'] );
			$this->addJoinConds( $actorQuery['joins'] );
			$this->addWhere( $actorQuery['conds'] );
		} elseif ( !is_null( $params['excludeuser'] ) ) {
			// Here there's no chance of using ar_usertext_timestamp.
			$actorQuery = ActorMigration::newMigration()
				->getWhere( $db, 'ar_user', User::newFromName( $params['excludeuser'], false ) );
			$this->addTables( $actorQuery['tables'] );
			$this->addJoinConds( $actorQuery['joins'] );
			$this->addWhere( 'NOT(' . $actorQuery['conds'] . ')' );
		}

		if ( !is_null( $params['user'] ) || !is_null( $params['excludeuser'] ) ) {
			// Paranoia: avoid brute force searches (T19342)
			// (shouldn't be able to get here without 'deletedhistory', but
			// check it again just in case)
			if ( !$user->isAllowed( 'deletedhistory' ) ) {
				$bitmask = RevisionRecord::DELETED_USER;
			} elseif ( !$user->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
				$bitmask = RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED;
			} else {
				$bitmask = 0;
			}
			if ( $bitmask ) {
				$this->addWhere( $db->bitAnd( 'ar_deleted', $bitmask ) . " != $bitmask" );
			}
		}

		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			$op = ( $dir == 'newer' ? '>' : '<' );
			if ( $optimizeGenerateTitles ) {
				$this->dieContinueUsageIf( count( $cont ) != 2 );
				$ns = (int)$cont[0];
				$this->dieContinueUsageIf( strval( $ns ) !== $cont[0] );
				$title = $db->addQuotes( $cont[1] );
				$this->addWhere( "ar_namespace $op $ns OR " .
					"(ar_namespace = $ns AND ar_title $op= $title)" );
			} elseif ( $mode == 'all' ) {
				$this->dieContinueUsageIf( count( $cont ) != 4 );
				$ns = (int)$cont[0];
				$this->dieContinueUsageIf( strval( $ns ) !== $cont[0] );
				$title = $db->addQuotes( $cont[1] );
				$ts = $db->addQuotes( $db->timestamp( $cont[2] ) );
				$ar_id = (int)$cont[3];
				$this->dieContinueUsageIf( strval( $ar_id ) !== $cont[3] );
				$this->addWhere( "ar_namespace $op $ns OR " .
					"(ar_namespace = $ns AND " .
					"(ar_title $op $title OR " .
					"(ar_title = $title AND " .
					"(ar_timestamp $op $ts OR " .
					"(ar_timestamp = $ts AND " .
					"ar_id $op= $ar_id)))))" );
			} else {
				$this->dieContinueUsageIf( count( $cont ) != 2 );
				$ts = $db->addQuotes( $db->timestamp( $cont[0] ) );
				$ar_id = (int)$cont[1];
				$this->dieContinueUsageIf( strval( $ar_id ) !== $cont[1] );
				$this->addWhere( "ar_timestamp $op $ts OR " .
					"(ar_timestamp = $ts AND " .
					"ar_id $op= $ar_id)" );
			}
		}

		$this->addOption( 'LIMIT', $this->limit + 1 );

		$sort = ( $dir == 'newer' ? '' : ' DESC' );
		$orderby = [];
		if ( $optimizeGenerateTitles ) {
			// Targeting index name_title_timestamp
			if ( $params['namespace'] === null || count( array_unique( $params['namespace'] ) ) > 1 ) {
				$orderby[] = "ar_namespace $sort";
			}
			$orderby[] = "ar_title $sort";
		} elseif ( $mode == 'all' ) {
			// Targeting index name_title_timestamp
			if ( $params['namespace'] === null || count( array_unique( $params['namespace'] ) ) > 1 ) {
				$orderby[] = "ar_namespace $sort";
			}
			$orderby[] = "ar_title $sort";
			$orderby[] = "ar_timestamp $sort";
			$orderby[] = "ar_id $sort";
		} else {
			// Targeting index usertext_timestamp
			// 'user' is always constant.
			$orderby[] = "ar_timestamp $sort";
			$orderby[] = "ar_id $sort";
		}
		$this->addOption( 'ORDER BY', $orderby );

		$res = $this->select( __METHOD__ );
		$pageMap = []; // Maps ns&title to array index
		$count = 0;
		$nextIndex = 0;
		$generated = [];
		foreach ( $res as $row ) {
			if ( ++$count > $this->limit ) {
				// We've had enough
				if ( $optimizeGenerateTitles ) {
					$this->setContinueEnumParameter( 'continue', "$row->ar_namespace|$row->ar_title" );
				} elseif ( $mode == 'all' ) {
					$this->setContinueEnumParameter( 'continue',
						"$row->ar_namespace|$row->ar_title|$row->ar_timestamp|$row->ar_id"
					);
				} else {
					$this->setContinueEnumParameter( 'continue', "$row->ar_timestamp|$row->ar_id" );
				}
				break;
			}

			// Miser mode namespace check
			if ( $miser_ns !== null && !in_array( $row->ar_namespace, $miser_ns ) ) {
				continue;
			}

			if ( $resultPageSet !== null ) {
				if ( $params['generatetitles'] ) {
					$key = "{$row->ar_namespace}:{$row->ar_title}";
					if ( !isset( $generated[$key] ) ) {
						$generated[$key] = Title::makeTitle( $row->ar_namespace, $row->ar_title );
					}
				} else {
					$generated[] = $row->ar_rev_id;
				}
			} else {
				$revision = $revisionStore->newRevisionFromArchiveRow( $row );
				$rev = $this->extractRevisionInfo( $revision, $row );

				if ( !isset( $pageMap[$row->ar_namespace][$row->ar_title] ) ) {
					$index = $nextIndex++;
					$pageMap[$row->ar_namespace][$row->ar_title] = $index;
					$title = Title::newFromLinkTarget( $revision->getPageAsLinkTarget() );
					$a = [
						'pageid' => $title->getArticleID(),
						'revisions' => [ $rev ],
					];
					ApiResult::setIndexedTagName( $a['revisions'], 'rev' );
					ApiQueryBase::addTitleInfo( $a, $title );
					$fit = $result->addValue( [ 'query', $this->getModuleName() ], $index, $a );
				} else {
					$index = $pageMap[$row->ar_namespace][$row->ar_title];
					$fit = $result->addValue(
						[ 'query', $this->getModuleName(), $index, 'revisions' ],
						null, $rev );
				}
				if ( !$fit ) {
					if ( $mode == 'all' ) {
						$this->setContinueEnumParameter( 'continue',
							"$row->ar_namespace|$row->ar_title|$row->ar_timestamp|$row->ar_id"
						);
					} else {
						$this->setContinueEnumParameter( 'continue', "$row->ar_timestamp|$row->ar_id" );
					}
					break;
				}
			}
		}

		if ( $resultPageSet !== null ) {
			if ( $params['generatetitles'] ) {
				$resultPageSet->populateFromTitles( $generated );
			} else {
				$resultPageSet->populateFromRevisionIDs( $generated );
			}
		} else {
			$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'page' );
		}
	}

	public function getAllowedParams() {
		$ret = parent::getAllowedParams() + [
			'user' => [
				ApiBase::PARAM_TYPE => 'user'
			],
			'namespace' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace',
			],
			'start' => [
				ApiBase::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'useronly' ] ],
			],
			'end' => [
				ApiBase::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'useronly' ] ],
			],
			'dir' => [
				ApiBase::PARAM_TYPE => [
					'newer',
					'older'
				],
				ApiBase::PARAM_DFLT => 'older',
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
			],
			'from' => [
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'nonuseronly' ] ],
			],
			'to' => [
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'nonuseronly' ] ],
			],
			'prefix' => [
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'nonuseronly' ] ],
			],
			'excludeuser' => [
				ApiBase::PARAM_TYPE => 'user',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'nonuseronly' ] ],
			],
			'tag' => null,
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'generatetitles' => [
				ApiBase::PARAM_DFLT => false
			],
		];

		if ( $this->getConfig()->get( 'MiserMode' ) ) {
			$ret['user'][ApiBase::PARAM_HELP_MSG_APPEND] = [
				'apihelp-query+alldeletedrevisions-param-miser-user-namespace',
			];
			$ret['namespace'][ApiBase::PARAM_HELP_MSG_APPEND] = [
				'apihelp-query+alldeletedrevisions-param-miser-user-namespace',
			];
		}

		return $ret;
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=alldeletedrevisions&adruser=Example&adrlimit=50'
				=> 'apihelp-query+alldeletedrevisions-example-user',
			'action=query&list=alldeletedrevisions&adrdir=newer&adrnamespace=0&adrlimit=50'
				=> 'apihelp-query+alldeletedrevisions-example-ns-main',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Alldeletedrevisions';
	}
}
