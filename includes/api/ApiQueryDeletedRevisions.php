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

/**
 * Query module to enumerate deleted revisions for pages.
 *
 * @ingroup API
 */
class ApiQueryDeletedRevisions extends ApiQueryRevisionsBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'drv' );
	}

	protected function run( ApiPageSet $resultPageSet = null ) {
		$user = $this->getUser();
		// Before doing anything at all, let's check permissions
		$this->checkUserRightsAny( 'deletedhistory' );

		$pageSet = $this->getPageSet();
		$pageMap = $pageSet->getGoodAndMissingTitlesByNamespace();
		$pageCount = count( $pageSet->getGoodAndMissingTitles() );
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
			$arQuery = Revision::getArchiveQueryInfo();
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
			$this->addTables( 'tag_summary' );
			$this->addJoinConds(
				[ 'tag_summary' => [ 'LEFT JOIN', [ 'ar_rev_id=ts_rev_id' ] ] ]
			);
			$this->addFields( 'ts_tags' );
		}

		if ( !is_null( $params['tag'] ) ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds(
				[ 'change_tag' => [ 'INNER JOIN', [ 'ar_rev_id=ct_rev_id' ] ] ]
			);
			$this->addWhereFld( 'ct_tag', $params['tag'] );
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

		$dir = $params['dir'];

		if ( $revCount !== 0 ) {
			$this->addWhere( [
				'ar_rev_id' => array_keys( $pageSet->getDeletedRevisionIDs() )
			] );
		} else {
			// We need a custom WHERE clause that matches all titles.
			$lb = new LinkBatch( $pageSet->getGoodAndMissingTitles() );
			$where = $lb->constructSet( 'ar', $db );
			$this->addWhere( $where );
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
				$bitmask = Revision::DELETED_USER;
			} elseif ( !$user->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
				$bitmask = Revision::DELETED_USER | Revision::DELETED_RESTRICTED;
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
			if ( $revCount !== 0 ) {
				$this->dieContinueUsageIf( count( $cont ) != 2 );
				$rev = intval( $cont[0] );
				$this->dieContinueUsageIf( strval( $rev ) !== $cont[0] );
				$ar_id = (int)$cont[1];
				$this->dieContinueUsageIf( strval( $ar_id ) !== $cont[1] );
				$this->addWhere( "ar_rev_id $op $rev OR " .
					"(ar_rev_id = $rev AND " .
					"ar_id $op= $ar_id)" );
			} else {
				$this->dieContinueUsageIf( count( $cont ) != 4 );
				$ns = intval( $cont[0] );
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
					$this->extractRevisionInfo( Revision::newFromArchiveRow( $row ), $row ),
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

	public function getAllowedParams() {
		return parent::getAllowedParams() + [
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
			'tag' => null,
			'user' => [
				ApiBase::PARAM_TYPE => 'user'
			],
			'excludeuser' => [
				ApiBase::PARAM_TYPE => 'user'
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&prop=deletedrevisions&titles=Main%20Page|Talk:Main%20Page&' .
				'drvprop=user|comment|content'
				=> 'apihelp-query+deletedrevisions-example-titles',
			'action=query&prop=deletedrevisions&revids=123456'
				=> 'apihelp-query+deletedrevisions-example-revids',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Deletedrevisions';
	}
}
