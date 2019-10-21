<?php
/**
 * Copyright © 2015 Wikimedia Foundation and contributors
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

/**
 * Query module to enumerate all revisions.
 *
 * @ingroup API
 * @since 1.27
 */
class ApiQueryAllRevisions extends ApiQueryRevisionsBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'arv' );
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	protected function run( ApiPageSet $resultPageSet = null ) {
		$db = $this->getDB();
		$params = $this->extractRequestParams( false );
		$services = MediaWikiServices::getInstance();
		$revisionStore = $services->getRevisionStore();

		$result = $this->getResult();

		$this->requireMaxOneParameter( $params, 'user', 'excludeuser' );

		$tsField = 'rev_timestamp';
		$idField = 'rev_id';
		$pageField = 'rev_page';
		if ( $params['user'] !== null ) {
			// The query is probably best done using the actor_timestamp index on
			// revision_actor_temp. Use the denormalized fields from that table.
			$tsField = 'revactor_timestamp';
			$idField = 'revactor_rev';
			$pageField = 'revactor_page';
		}

		// Namespace check is likely to be desired, but can't be done
		// efficiently in SQL.
		$miser_ns = null;
		$needPageTable = false;
		if ( $params['namespace'] !== null ) {
			$params['namespace'] = array_unique( $params['namespace'] );
			sort( $params['namespace'] );
			if ( $params['namespace'] != $services->getNamespaceInfo()->getValidNamespaces() ) {
				$needPageTable = true;
				if ( $this->getConfig()->get( 'MiserMode' ) ) {
					$miser_ns = $params['namespace'];
				} else {
					$this->addWhere( [ 'page_namespace' => $params['namespace'] ] );
				}
			}
		}

		if ( $resultPageSet === null ) {
			$this->parseParameters( $params );
			$revQuery = $revisionStore->getQueryInfo( [ 'page' ] );
		} else {
			$this->limit = $this->getParameter( 'limit' ) ?: 10;
			$revQuery = [
				'tables' => [ 'revision' ],
				'fields' => [ 'rev_timestamp', 'rev_id' ],
				'joins' => [],
			];

			if ( $params['generatetitles'] ) {
				$revQuery['fields'][] = 'rev_page';
			}

			if ( $params['user'] !== null || $params['excludeuser'] !== null ) {
				$actorQuery = ActorMigration::newMigration()->getJoin( 'rev_user' );
				$revQuery['tables'] += $actorQuery['tables'];
				$revQuery['joins'] += $actorQuery['joins'];
			}

			if ( $needPageTable ) {
				$revQuery['tables'][] = 'page';
				$revQuery['joins']['page'] = [ 'JOIN', [ "$pageField = page_id" ] ];
				if ( (bool)$miser_ns ) {
					$revQuery['fields'][] = 'page_namespace';
				}
			}
		}

		// If we're going to be using actor_timestamp, we need to swap the order of `revision`
		// and `revision_actor_temp` in the query (for the straight join) and adjust some field aliases.
		if ( $idField !== 'rev_id' && isset( $revQuery['tables']['temp_rev_user'] ) ) {
			$aliasFields = [ 'rev_id' => $idField, 'rev_timestamp' => $tsField, 'rev_page' => $pageField ];
			$revQuery['fields'] = array_merge(
				$aliasFields,
				array_diff( $revQuery['fields'], array_keys( $aliasFields ) )
			);
			unset( $revQuery['tables']['temp_rev_user'] );
			$revQuery['tables'] = array_merge(
				[ 'temp_rev_user' => 'revision_actor_temp' ],
				$revQuery['tables']
			);
			$revQuery['joins']['revision'] = $revQuery['joins']['temp_rev_user'];
			unset( $revQuery['joins']['temp_rev_user'] );
		}

		$this->addTables( $revQuery['tables'] );
		$this->addFields( $revQuery['fields'] );
		$this->addJoinConds( $revQuery['joins'] );

		// Seems to be needed to avoid a planner bug (T113901)
		$this->addOption( 'STRAIGHT_JOIN' );

		$dir = $params['dir'];
		$this->addTimestampWhereRange( $tsField, $dir, $params['start'], $params['end'] );

		if ( $this->fld_tags ) {
			$this->addFields( [ 'ts_tags' => ChangeTags::makeTagSummarySubquery( 'revision' ) ] );
		}

		if ( $params['user'] !== null ) {
			$actorQuery = ActorMigration::newMigration()
				->getWhere( $db, 'rev_user', User::newFromName( $params['user'], false ) );
			$this->addWhere( $actorQuery['conds'] );
		} elseif ( $params['excludeuser'] !== null ) {
			$actorQuery = ActorMigration::newMigration()
				->getWhere( $db, 'rev_user', User::newFromName( $params['excludeuser'], false ) );
			$this->addWhere( 'NOT(' . $actorQuery['conds'] . ')' );
		}

		if ( $params['user'] !== null || $params['excludeuser'] !== null ) {
			// Paranoia: avoid brute force searches (T19342)
			if ( !$this->getPermissionManager()->userHasRight( $this->getUser(), 'deletedhistory' ) ) {
				$bitmask = RevisionRecord::DELETED_USER;
			} elseif ( !$this->getPermissionManager()
				->userHasAnyRight( $this->getUser(), 'suppressrevision', 'viewsuppressed' )
			) {
				$bitmask = RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED;
			} else {
				$bitmask = 0;
			}
			if ( $bitmask ) {
				$this->addWhere( $db->bitAnd( 'rev_deleted', $bitmask ) . " != $bitmask" );
			}
		}

		if ( $params['continue'] !== null ) {
			$op = ( $dir == 'newer' ? '>' : '<' );
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 2 );
			$ts = $db->addQuotes( $db->timestamp( $cont[0] ) );
			$rev_id = (int)$cont[1];
			$this->dieContinueUsageIf( strval( $rev_id ) !== $cont[1] );
			$this->addWhere( "$tsField $op $ts OR " .
				"($tsField = $ts AND " .
				"$idField $op= $rev_id)" );
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
				$revision = $revisionStore->newRevisionFromRow( $row );
				$rev = $this->extractRevisionInfo( $revision, $row );

				if ( !isset( $pageMap[$row->rev_page] ) ) {
					$index = $nextIndex++;
					$pageMap[$row->rev_page] = $index;
					$title = Title::newFromLinkTarget( $revision->getPageAsLinkTarget() );
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

	public function getAllowedParams() {
		$ret = parent::getAllowedParams() + [
			'user' => [
				ApiBase::PARAM_TYPE => 'user',
			],
			'namespace' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace',
				ApiBase::PARAM_DFLT => null,
			],
			'start' => [
				ApiBase::PARAM_TYPE => 'timestamp',
			],
			'end' => [
				ApiBase::PARAM_TYPE => 'timestamp',
			],
			'dir' => [
				ApiBase::PARAM_TYPE => [
					'newer',
					'older'
				],
				ApiBase::PARAM_DFLT => 'older',
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
			],
			'excludeuser' => [
				ApiBase::PARAM_TYPE => 'user',
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'generatetitles' => [
				ApiBase::PARAM_DFLT => false,
			],
		];

		if ( $this->getConfig()->get( 'MiserMode' ) ) {
			$ret['namespace'][ApiBase::PARAM_HELP_MSG_APPEND] = [
				'api-help-param-limited-in-miser-mode',
			];
		}

		return $ret;
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=allrevisions&arvuser=Example&arvlimit=50'
				=> 'apihelp-query+allrevisions-example-user',
			'action=query&list=allrevisions&arvdir=newer&arvlimit=50'
				=> 'apihelp-query+allrevisions-example-ns-main',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Allrevisions';
	}
}
