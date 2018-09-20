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
		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();

		$result = $this->getResult();

		$this->requireMaxOneParameter( $params, 'user', 'excludeuser' );

		// Namespace check is likely to be desired, but can't be done
		// efficiently in SQL.
		$miser_ns = null;
		$needPageTable = false;
		if ( $params['namespace'] !== null ) {
			$params['namespace'] = array_unique( $params['namespace'] );
			sort( $params['namespace'] );
			if ( $params['namespace'] != MWNamespace::getValidNamespaces() ) {
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
			$revQuery = $revisionStore->getQueryInfo(
				$this->fetchContent ? [ 'page', 'text' ] : [ 'page' ]
			);
			$this->addTables( $revQuery['tables'] );
			$this->addFields( $revQuery['fields'] );
			$this->addJoinConds( $revQuery['joins'] );

			// Review this depeneding on the outcome of T113901
			$this->addOption( 'STRAIGHT_JOIN' );
		} else {
			$this->limit = $this->getParameter( 'limit' ) ?: 10;
			$this->addTables( 'revision' );
			$this->addFields( [ 'rev_timestamp', 'rev_id' ] );
			if ( $params['generatetitles'] ) {
				$this->addFields( [ 'rev_page' ] );
			}

			if ( $needPageTable ) {
				$this->addTables( 'page' );
				$this->addJoinConds(
					[ 'page' => [ 'INNER JOIN', [ 'rev_page = page_id' ] ] ]
				);
				$this->addFieldsIf( [ 'page_namespace' ], (bool)$miser_ns );

				// Review this depeneding on the outcome of T113901
				$this->addOption( 'STRAIGHT_JOIN' );
			}
		}

		$dir = $params['dir'];
		$this->addTimestampWhereRange( 'rev_timestamp', $dir, $params['start'], $params['end'] );

		if ( $this->fld_tags ) {
			$this->addTables( 'tag_summary' );
			$this->addJoinConds(
				[ 'tag_summary' => [ 'LEFT JOIN', [ 'rev_id=ts_rev_id' ] ] ]
			);
			$this->addFields( 'ts_tags' );
		}

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

		if ( $params['continue'] !== null ) {
			$op = ( $dir == 'newer' ? '>' : '<' );
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 2 );
			$ts = $db->addQuotes( $db->timestamp( $cont[0] ) );
			$rev_id = (int)$cont[1];
			$this->dieContinueUsageIf( strval( $rev_id ) !== $cont[1] );
			$this->addWhere( "rev_timestamp $op $ts OR " .
				"(rev_timestamp = $ts AND " .
				"rev_id $op= $rev_id)" );
		}

		$this->addOption( 'LIMIT', $this->limit + 1 );

		$sort = ( $dir == 'newer' ? '' : ' DESC' );
		$orderby = [];
		// Targeting index rev_timestamp, user_timestamp, or usertext_timestamp
		// But 'user' is always constant for the latter two, so it doesn't matter here.
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
