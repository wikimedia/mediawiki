<?php
/**
 * Created on Sep 27, 2015
 *
 * Copyright © 2015 Brad Jorsch "bjorsch@wikimedia.org"
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
	 * @param ApiPageSet $resultPageSet
	 * @return void
	 */
	protected function run( ApiPageSet $resultPageSet = null ) {
		$db = $this->getDB();
		$params = $this->extractRequestParams( false );

		$result = $this->getResult();

		$this->requireMaxOneParameter( $params, 'user', 'excludeuser' );

		// Namespace check is likely to be desired, but can't be done
		// efficiently in SQL.
		$miser_ns = null;
		$needPageTable = false;
		if ( $params['namespace'] !== null ) {
			$params['namespace'] = array_unique( $params['namespace'] );
			sort( $params['namespace'] );
			$validNamespaces = MWNamespace::getValidNamespaces();
			sort( $validNamespaces );
			if ( $params['namespace'] != $validNamespaces ) {
				$needPageTable = true;
				if ( $this->getConfig()->get( 'MiserMode' ) ) {
					$miser_ns = $params['namespace'];
				} else {
					$this->addWhere( array( 'page_namespace' => $params['namespace'] ) );
				}
			}
		}

		$this->addTables( 'revision' );
		if ( $resultPageSet === null ) {
			$this->parseParameters( $params );
			$this->addTables( 'page' );
			$this->addJoinConds(
				array( 'page' => array( 'INNER JOIN', array( 'rev_page = page_id' ) ) )
			);
			$this->addFields( Revision::selectFields() );
			$this->addFields( Revision::selectPageFields() );

			// Review this depeneding on the outcome of T113901
			$this->addOption( 'STRAIGHT_JOIN' );
		} else {
			$this->limit = $this->getParameter( 'limit' ) ?: 10;
			$this->addFields( array( 'rev_timestamp', 'rev_id' ) );
			if ( $params['generatetitles'] ) {
				$this->addFields( array( 'rev_page' ) );
			}

			if ( $needPageTable ) {
				$this->addTables( 'page' );
				$this->addJoinConds(
					array( 'page' => array( 'INNER JOIN', array( 'rev_page = page_id' ) ) )
				);
				$this->addFieldsIf( array( 'page_namespace' ), (bool)$miser_ns );

				// Review this depeneding on the outcome of T113901
				$this->addOption( 'STRAIGHT_JOIN' );
			}
		}

		if ( $this->fld_tags ) {
			$this->addTables( 'tag_summary' );
			$this->addJoinConds(
				array( 'tag_summary' => array( 'LEFT JOIN', array( 'rev_id=ts_rev_id' ) ) )
			);
			$this->addFields( 'ts_tags' );
		}

		if ( $this->fetchContent ) {
			$this->addTables( 'text' );
			$this->addJoinConds(
				array( 'text' => array( 'INNER JOIN', array( 'rev_text_id=old_id' ) ) )
			);
			$this->addFields( 'old_id' );
			$this->addFields( Revision::selectTextFields() );
		}

		if ( $params['user'] !== null ) {
			$id = User::idFromName( $params['user'] );
			if ( $id ) {
				$this->addWhereFld( 'rev_user', $id );
			} else {
				$this->addWhereFld( 'rev_user_text', $params['user'] );
			}
		} elseif ( $params['excludeuser'] !== null ) {
			$id = User::idFromName( $params['excludeuser'] );
			if ( $id ) {
				$this->addWhere( 'rev_user != ' . $id );
			} else {
				$this->addWhere( 'rev_user_text != ' . $db->addQuotes( $params['excludeuser'] ) );
			}
		}

		if ( $params['user'] !== null || $params['excludeuser'] !== null ) {
			// Paranoia: avoid brute force searches (bug 17342)
			if ( !$this->getUser()->isAllowed( 'deletedhistory' ) ) {
				$bitmask = Revision::DELETED_USER;
			} elseif ( !$this->getUser()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
				$bitmask = Revision::DELETED_USER | Revision::DELETED_RESTRICTED;
			} else {
				$bitmask = 0;
			}
			if ( $bitmask ) {
				$this->addWhere( $db->bitAnd( 'rev_deleted', $bitmask ) . " != $bitmask" );
			}
		}

		$dir = $params['dir'];

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
		$orderby = array();
		// Targeting index rev_timestamp, user_timestamp, or usertext_timestamp
		// But 'user' is always constant for the latter two, so it doesn't matter here.
		$orderby[] = "rev_timestamp $sort";
		$orderby[] = "rev_id $sort";
		$this->addOption( 'ORDER BY', $orderby );

		$res = $this->select( __METHOD__ );
		$pageMap = array(); // Maps rev_page to array index
		$count = 0;
		$nextIndex = 0;
		$generated = array();
		foreach ( $res as $row ) {
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
				$revision = Revision::newFromRow( $row );
				$rev = $this->extractRevisionInfo( $revision, $row );

				if ( !isset( $pageMap[$row->rev_page] ) ) {
					$index = $nextIndex++;
					$pageMap[$row->rev_page] = $index;
					$title = $revision->getTitle();
					$a = array(
						'pageid' => $title->getArticleID(),
						'revisions' => array( $rev ),
					);
					ApiResult::setIndexedTagName( $a['revisions'], 'rev' );
					ApiQueryBase::addTitleInfo( $a, $title );
					$fit = $result->addValue( array( 'query', $this->getModuleName() ), $index, $a );
				} else {
					$index = $pageMap[$row->rev_page];
					$fit = $result->addValue(
						array( 'query', $this->getModuleName(), $index, 'revisions' ),
						null, $rev );
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
			$result->addIndexedTagName( array( 'query', $this->getModuleName() ), 'page' );
		}
	}

	public function getAllowedParams() {
		$ret = parent::getAllowedParams() + array(
			'user' => array(
				ApiBase::PARAM_TYPE => 'user',
			),
			'namespace' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace',
				ApiBase::PARAM_DFLT => null,
			),
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
			'excludeuser' => array(
				ApiBase::PARAM_TYPE => 'user',
			),
			'continue' => array(
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			),
			'generatetitles' => array(
				ApiBase::PARAM_DFLT => false,
			),
		);

		if ( $this->getConfig()->get( 'MiserMode' ) ) {
			$ret['namespace'][ApiBase::PARAM_HELP_MSG_APPEND] = array(
				'api-help-param-limited-in-miser-mode',
			);
		}

		return $ret;
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&list=allrevisions&arvuser=Example&arvlimit=50'
				=> 'apihelp-query+allrevisions-example-user',
			'action=query&list=allrevisions&arvdir=newer&arvlimit=50'
				=> 'apihelp-query+allrevisions-example-ns-main',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Allrevisions';
	}
}
