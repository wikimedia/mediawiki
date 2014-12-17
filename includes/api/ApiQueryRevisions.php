<?php
/**
 *
 *
 * Created on Sep 7, 2006
 *
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
			return array();
		}

		$this->tokenFunctions = array(
			'rollback' => array( 'ApiQueryRevisions', 'getRollbackToken' )
		);
		Hooks::run( 'APIQueryRevisionsTokens', array( &$this->tokenFunctions ) );

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

		return $wgUser->getEditToken(
			array( $title->getPrefixedText(), $rev->getUserText() ) );
	}

	protected function run( ApiPageSet $resultPageSet = null ) {
		$params = $this->extractRequestParams( false );

		// If any of those parameters are used, work in 'enumeration' mode.
		// Enum mode can only be used when exactly one page is provided.
		// Enumerating revisions on multiple pages make it extremely
		// difficult to manage continuations and require additional SQL indexes
		$enumRevMode = ( !is_null( $params['user'] ) || !is_null( $params['excludeuser'] ) ||
			!is_null( $params['limit'] ) || !is_null( $params['startid'] ) ||
			!is_null( $params['endid'] ) || $params['dir'] === 'newer' ||
			!is_null( $params['start'] ) || !is_null( $params['end'] ) );

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
			$this->dieUsage(
				'The revids= parameter may not be used with the list options ' .
					'(limit, startid, endid, dirNewer, start, end).',
				'revids'
			);
		}

		if ( $pageCount > 1 && $enumRevMode ) {
			$this->dieUsage(
				'titles, pageids or a generator was used to supply multiple pages, ' .
					'but the limit, startid, endid, dirNewer, user, excludeuser, start ' .
					'and end parameters may only be used on a single page.',
				'multpages'
			);
		}

		// In non-enum mode, rvlimit can't be directly used. Use the maximum
		// allowed value.
		if ( !$enumRevMode ) {
			$this->setParsedLimit = false;
			$params['limit'] = 'max';
		}

		$db = $this->getDB();
		$this->addTables( array( 'revision', 'page' ) );
		$this->addJoinConds(
			array( 'page' => array( 'INNER JOIN', array( 'page_id = rev_page' ) ) )
		);

		if ( $resultPageSet === null ) {
			$this->parseParameters( $params );
			$this->token = $params['token'];
			$this->addFields( Revision::selectFields() );
			if ( $this->token !== null || $pageCount > 0 ) {
				$this->addFields( Revision::selectPageFields() );
			}
		} else {
			$this->limit = $this->getParameter( 'limit' ) ?: 10;
			$this->addFields( array( 'rev_id', 'rev_page' ) );
		}

		if ( $this->fld_tags ) {
			$this->addTables( 'tag_summary' );
			$this->addJoinConds(
				array( 'tag_summary' => array( 'LEFT JOIN', array( 'rev_id=ts_rev_id' ) ) )
			);
			$this->addFields( 'ts_tags' );
		}

		if ( !is_null( $params['tag'] ) ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds(
				array( 'change_tag' => array( 'INNER JOIN', array( 'rev_id=ct_rev_id' ) ) )
			);
			$this->addWhereFld( 'ct_tag', $params['tag'] );
		}

		if ( $this->fetchContent ) {
			// For each page we will request, the user must have read rights for that page
			$user = $this->getUser();
			/** @var $title Title */
			foreach ( $pageSet->getGoodTitles() as $title ) {
				if ( !$title->userCan( 'read', $user ) ) {
					$this->dieUsage(
						'The current user is not allowed to read ' . $title->getPrefixedText(),
						'accessdenied' );
				}
			}

			$this->addTables( 'text' );
			$this->addJoinConds(
				array( 'text' => array( 'INNER JOIN', array( 'rev_text_id=old_id' ) ) )
			);
			$this->addFields( 'old_id' );
			$this->addFields( Revision::selectTextFields() );
		}

		// add user name, if needed
		if ( $this->fld_user ) {
			$this->addTables( 'user' );
			$this->addJoinConds( array( 'user' => Revision::userJoinCond() ) );
			$this->addFields( Revision::selectUserFields() );
		}

		if ( $enumRevMode ) {
			// This is mostly to prevent parameter errors (and optimize SQL?)
			if ( !is_null( $params['startid'] ) && !is_null( $params['start'] ) ) {
				$this->dieUsage( 'start and startid cannot be used together', 'badparams' );
			}

			if ( !is_null( $params['endid'] ) && !is_null( $params['end'] ) ) {
				$this->dieUsage( 'end and endid cannot be used together', 'badparams' );
			}

			if ( !is_null( $params['user'] ) && !is_null( $params['excludeuser'] ) ) {
				$this->dieUsage( 'user and excludeuser cannot be used together', 'badparams' );
			}

			// Continuing effectively uses startid. But we can't use rvstartid
			// directly, because there is no way to tell the client to ''not''
			// send rvstart if it sent it in the original query. So instead we
			// send the continuation startid as rvcontinue, and ignore both
			// rvstart and rvstartid when that is supplied.
			if ( !is_null( $params['continue'] ) ) {
				$params['startid'] = $params['continue'];
				$params['start'] = null;
			}

			// This code makes an assumption that sorting by rev_id and rev_timestamp produces
			// the same result. This way users may request revisions starting at a given time,
			// but to page through results use the rev_id returned after each page.
			// Switching to rev_id removes the potential problem of having more than
			// one row with the same timestamp for the same page.
			// The order needs to be the same as start parameter to avoid SQL filesort.
			if ( is_null( $params['startid'] ) && is_null( $params['endid'] ) ) {
				$this->addTimestampWhereRange( 'rev_timestamp', $params['dir'],
					$params['start'], $params['end'] );
			} else {
				$this->addWhereRange( 'rev_id', $params['dir'],
					$params['startid'], $params['endid'] );
				// One of start and end can be set
				// If neither is set, this does nothing
				$this->addTimestampWhereRange( 'rev_timestamp', $params['dir'],
					$params['start'], $params['end'], false );
			}

			// There is only one ID, use it
			$ids = array_keys( $pageSet->getGoodTitles() );
			$this->addWhereFld( 'rev_page', reset( $ids ) );

			if ( !is_null( $params['user'] ) ) {
				$this->addWhereFld( 'rev_user_text', $params['user'] );
			} elseif ( !is_null( $params['excludeuser'] ) ) {
				$this->addWhere( 'rev_user_text != ' .
					$db->addQuotes( $params['excludeuser'] ) );
			}
			if ( !is_null( $params['user'] ) || !is_null( $params['excludeuser'] ) ) {
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
		} elseif ( $revCount > 0 ) {
			$revs = $pageSet->getLiveRevisionIDs();

			// Get all revision IDs
			$this->addWhereFld( 'rev_id', array_keys( $revs ) );

			if ( !is_null( $params['continue'] ) ) {
				$this->addWhere( 'rev_id >= ' . intval( $params['continue'] ) );
			}
			$this->addOption( 'ORDER BY', 'rev_id' );
		} elseif ( $pageCount > 0 ) {
			$titles = $pageSet->getGoodTitles();

			// When working in multi-page non-enumeration mode,
			// limit to the latest revision only
			$this->addWhere( 'page_latest=rev_id' );

			// Get all page IDs
			$this->addWhereFld( 'page_id', array_keys( $titles ) );
			// Every time someone relies on equality propagation, god kills a kitten :)
			$this->addWhereFld( 'rev_page', array_keys( $titles ) );

			if ( !is_null( $params['continue'] ) ) {
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
			$this->addOption( 'ORDER BY', array(
				'rev_page',
				'rev_id'
			) );
		} else {
			ApiBase::dieDebug( __METHOD__, 'param validation?' );
		}

		$this->addOption( 'LIMIT', $this->limit + 1 );

		$count = 0;
		$generated = array();
		$res = $this->select( __METHOD__ );

		foreach ( $res as $row ) {
			if ( ++$count > $this->limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				if ( $enumRevMode ) {
					$this->setContinueEnumParameter( 'continue', intval( $row->rev_id ) );
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
				$revision = new Revision( $row );
				$rev = $this->extractRevisionInfo( $revision, $row );

				if ( $this->token !== null ) {
					$title = $revision->getTitle();
					$tokenFunctions = $this->getTokenFunctions();
					foreach ( $this->token as $t ) {
						$val = call_user_func( $tokenFunctions[$t], $title->getArticleID(), $title, $revision );
						if ( $val === false ) {
							$this->setWarning( "Action '$t' is not allowed for the current user" );
						} else {
							$rev[$t . 'token'] = $val;
						}
					}
				}

				$fit = $this->addPageSubItem( $row->rev_page, $rev, 'rev' );
				if ( !$fit ) {
					if ( $enumRevMode ) {
						$this->setContinueEnumParameter( 'continue', intval( $row->rev_id ) );
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
		$ret = parent::getAllowedParams() + array(
			'startid' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_HELP_MSG_INFO => array( array( 'singlepageonly' ) ),
			),
			'endid' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_HELP_MSG_INFO => array( array( 'singlepageonly' ) ),
			),
			'start' => array(
				ApiBase::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_HELP_MSG_INFO => array( array( 'singlepageonly' ) ),
			),
			'end' => array(
				ApiBase::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_HELP_MSG_INFO => array( array( 'singlepageonly' ) ),
			),
			'dir' => array(
				ApiBase::PARAM_DFLT => 'older',
				ApiBase::PARAM_TYPE => array(
					'newer',
					'older'
				),
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
				ApiBase::PARAM_HELP_MSG_INFO => array( array( 'singlepageonly' ) ),
			),
			'user' => array(
				ApiBase::PARAM_TYPE => 'user',
				ApiBase::PARAM_HELP_MSG_INFO => array( array( 'singlepageonly' ) ),
			),
			'excludeuser' => array(
				ApiBase::PARAM_TYPE => 'user',
				ApiBase::PARAM_HELP_MSG_INFO => array( array( 'singlepageonly' ) ),
			),
			'tag' => null,
			'token' => array(
				ApiBase::PARAM_DEPRECATED => true,
				ApiBase::PARAM_TYPE => array_keys( $this->getTokenFunctions() ),
				ApiBase::PARAM_ISMULTI => true
			),
			'continue' => array(
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			),
		);

		$ret['limit'][ApiBase::PARAM_HELP_MSG_INFO] = array( array( 'singlepageonly' ) );

		return $ret;
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&prop=revisions&titles=API|Main%20Page&' .
				'rvprop=timestamp|user|comment|content'
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
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Properties#revisions_.2F_rv';
	}
}
