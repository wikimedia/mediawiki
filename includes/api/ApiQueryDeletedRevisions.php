<?php
/**
 * Created on Oct 3, 2014
 *
 * Copyright © 2014 Brad Jorsch "bjorsch@wikimedia.org"
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
		if ( !$user->isAllowed( 'deletedhistory' ) ) {
			$this->dieUsage(
				'You don\'t have permission to view deleted revision information',
				'permissiondenied'
			);
		}

		$result = $this->getResult();
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

		if ( !is_null( $params['user'] ) && !is_null( $params['excludeuser'] ) ) {
			$this->dieUsage( 'user and excludeuser cannot be used together', 'badparams' );
		}

		$this->addTables( 'archive' );
		if ( $resultPageSet === null ) {
			$this->parseParameters( $params );
			$this->addFields( Revision::selectArchiveFields() );
			$this->addFields( array( 'ar_title', 'ar_namespace' ) );
		} else {
			$this->limit = $this->getParameter( 'limit' ) ?: 10;
			$this->addFields( array( 'ar_title', 'ar_namespace', 'ar_timestamp', 'ar_rev_id', 'ar_id' ) );
		}

		if ( $this->fld_tags ) {
			$this->addTables( 'tag_summary' );
			$this->addJoinConds(
				array( 'tag_summary' => array( 'LEFT JOIN', array( 'ar_rev_id=ts_rev_id' ) ) )
			);
			$this->addFields( 'ts_tags' );
		}

		if ( !is_null( $params['tag'] ) ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds(
				array( 'change_tag' => array( 'INNER JOIN', array( 'ar_rev_id=ct_rev_id' ) ) )
			);
			$this->addWhereFld( 'ct_tag', $params['tag'] );
		}

		if ( $this->fetchContent ) {
			// Modern MediaWiki has the content for deleted revs in the 'text'
			// table using fields old_text and old_flags. But revisions deleted
			// pre-1.5 store the content in the 'archive' table directly using
			// fields ar_text and ar_flags, and no corresponding 'text' row. So
			// we have to LEFT JOIN and fetch all four fields.
			$this->addTables( 'text' );
			$this->addJoinConds(
				array( 'text' => array( 'LEFT JOIN', array( 'ar_text_id=old_id' ) ) )
			);
			$this->addFields( array( 'ar_text', 'ar_flags', 'old_text', 'old_flags' ) );

			// This also means stricter restrictions
			if ( !$user->isAllowedAny( 'undelete', 'deletedtext' ) ) {
				$this->dieUsage(
					'You don\'t have permission to view deleted revision content',
					'permissiondenied'
				);
			}
		}

		$dir = $params['dir'];

		if ( $revCount !== 0 ) {
			$this->addWhere( array(
				'ar_rev_id' => array_keys( $pageSet->getDeletedRevisionIDs() )
			) );
		} else {
			// We need a custom WHERE clause that matches all titles.
			$lb = new LinkBatch( $pageSet->getGoodAndMissingTitles() );
			$where = $lb->constructSet( 'ar', $db );
			$this->addWhere( $where );
		}

		if ( !is_null( $params['user'] ) ) {
			$this->addWhereFld( 'ar_user_text', $params['user'] );
		} elseif ( !is_null( $params['excludeuser'] ) ) {
			$this->addWhere( 'ar_user_text != ' .
				$db->addQuotes( $params['excludeuser'] ) );
		}

		if ( !is_null( $params['user'] ) || !is_null( $params['excludeuser'] ) ) {
			// Paranoia: avoid brute force searches (bug 17342)
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
		$generated = array();
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
					ApiBase::dieDebug( "Found row in archive (ar_id={$row->ar_id}) that didn't " .
						"get processed by ApiPageSet" );
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
		return parent::getAllowedParams() + array(
			'start' => array(
				ApiBase::PARAM_TYPE => 'timestamp',
			),
			'end' => array(
				ApiBase::PARAM_TYPE => 'timestamp',
			),
			'dir' => array(
				ApiBase::PARAM_TYPE => array(
					'newer',
					'older'
				),
				ApiBase::PARAM_DFLT => 'older',
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
			),
			'tag' => null,
			'user' => array(
				ApiBase::PARAM_TYPE => 'user'
			),
			'excludeuser' => array(
				ApiBase::PARAM_TYPE => 'user'
			),
			'continue' => array(
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			),
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&prop=deletedrevisions&titles=Main%20Page|Talk:Main%20Page&' .
				'drvprop=user|comment|content'
				=> 'apihelp-query+deletedrevisions-example-titles',
			'action=query&prop=deletedrevisions&revids=123456'
				=> 'apihelp-query+deletedrevisions-example-revids',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Properties#deletedrevisions_.2F_drv';
	}
}
