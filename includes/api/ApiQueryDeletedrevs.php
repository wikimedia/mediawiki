<?php
/**
 *
 *
 * Created on Jul 2, 2007
 *
 * Copyright © 2007 Roan Kattouw <Firstname>.<Lastname>@gmail.com
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
 * Query module to enumerate all deleted revisions.
 *
 * @ingroup API
 */
class ApiQueryDeletedrevs extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'dr' );
	}

	public function execute() {
		$user = $this->getUser();
		// Before doing anything at all, let's check permissions
		if ( !$user->isAllowed( 'deletedhistory' ) ) {
			$this->dieUsage( 'You don\'t have permission to view deleted revision information', 'permissiondenied' );
		}

		$db = $this->getDB();
		$params = $this->extractRequestParams( false );
		$prop = array_flip( $params['prop'] );
		$fld_parentid = isset( $prop['parentid'] );
		$fld_revid = isset( $prop['revid'] );
		$fld_user = isset( $prop['user'] );
		$fld_userid = isset( $prop['userid'] );
		$fld_comment = isset( $prop['comment'] );
		$fld_parsedcomment = isset ( $prop['parsedcomment'] );
		$fld_minor = isset( $prop['minor'] );
		$fld_len = isset( $prop['len'] );
		$fld_content = isset( $prop['content'] );
		$fld_token = isset( $prop['token'] );

		$result = $this->getResult();
		$pageSet = $this->getPageSet();
		$titles = $pageSet->getTitles();

		// This module operates in three modes:
		// 'revs': List deleted revs for certain titles (1)
		// 'user': List deleted revs by a certain user (2)
		// 'all': List all deleted revs in NS (3)
		$mode = 'all';
		if ( count( $titles ) > 0 ) {
			$mode = 'revs';
		} elseif ( !is_null( $params['user'] ) ) {
			$mode = 'user';
		}

		if ( $mode == 'revs' || $mode == 'user' ) {
			// Ignore namespace and unique due to inability to know whether they were purposely set
			foreach( array( 'from', 'to', 'prefix', /*'namespace',*/ 'continue', /*'unique'*/ ) as $p ) {
				if ( !is_null( $params[$p] ) ) {
					$this->dieUsage( "The '{$p}' parameter cannot be used in modes 1 or 2", 'badparams');
				}
			}
		} else {
			foreach( array( 'start', 'end' ) as $p ) {
				if ( !is_null( $params[$p] ) ) {
					$this->dieUsage( "The {$p} parameter cannot be used in mode 3", 'badparams');
				}
			}
		}

		if ( !is_null( $params['user'] ) && !is_null( $params['excludeuser'] ) ) {
			$this->dieUsage( 'user and excludeuser cannot be used together', 'badparams' );
		}

		$this->addTables( 'archive' );
		$this->addWhere( 'ar_deleted = 0' );
		$this->addFields( array( 'ar_title', 'ar_namespace', 'ar_timestamp' ) );

		$this->addFieldsIf( 'ar_parent_id', $fld_parentid );
		$this->addFieldsIf( 'ar_rev_id', $fld_revid );
		$this->addFieldsIf( 'ar_user_text', $fld_user );
		$this->addFieldsIf( 'ar_user', $fld_userid );
		$this->addFieldsIf( 'ar_comment', $fld_comment || $fld_parsedcomment );
		$this->addFieldsIf( 'ar_minor_edit', $fld_minor );
		$this->addFieldsIf( 'ar_len', $fld_len );

		if ( $fld_content ) {
			$this->addTables( 'text' );
			$this->addFields( array( 'ar_text', 'ar_text_id', 'old_text', 'old_flags' ) );
			$this->addWhere( 'ar_text_id = old_id' );

			// This also means stricter restrictions
			if ( !$user->isAllowed( 'undelete' ) ) {
				$this->dieUsage( 'You don\'t have permission to view deleted revision content', 'permissiondenied' );
			}
		}
		// Check limits
		$userMax = $fld_content ? ApiBase::LIMIT_SML1 : ApiBase::LIMIT_BIG1;
		$botMax  = $fld_content ? ApiBase::LIMIT_SML2 : ApiBase::LIMIT_BIG2;

		$limit = $params['limit'];

		if ( $limit == 'max' ) {
			$limit = $this->getMain()->canApiHighLimits() ? $botMax : $userMax;
			$this->getResult()->setParsedLimit( $this->getModuleName(), $limit );
		}

		$this->validateLimit( 'limit', $limit, 1, $userMax, $botMax );

		if ( $fld_token ) {
			// Undelete tokens are identical for all pages, so we cache one here
			$token = $user->getEditToken( '', $this->getMain()->getRequest() );
		}

		$dir = $params['dir'];

		// We need a custom WHERE clause that matches all titles.
		if ( $mode == 'revs' ) {
			$lb = new LinkBatch( $titles );
			$where = $lb->constructSet( 'ar', $db );
			$this->addWhere( $where );
		} elseif ( $mode == 'all' ) {
			$this->addWhereFld( 'ar_namespace', $params['namespace'] );

			$from = is_null( $params['from'] ) ? null : $this->titleToKey( $params['from'] );
			$to = is_null( $params['to'] ) ? null : $this->titleToKey( $params['to'] );
			$this->addWhereRange( 'ar_title', $dir, $from, $to );

			if ( isset( $params['prefix'] ) ) {
				$this->addWhere( 'ar_title' . $db->buildLike( $this->titlePartToKey( $params['prefix'] ), $db->anyString() ) );
			}
		}

		if ( !is_null( $params['user'] ) ) {
			$this->addWhereFld( 'ar_user_text', $params['user'] );
		} elseif ( !is_null( $params['excludeuser'] ) ) {
			$this->addWhere( 'ar_user_text != ' .
				$this->getDB()->addQuotes( $params['excludeuser'] ) );
		}

		if ( !is_null( $params['continue'] ) && ( $mode == 'all' || $mode == 'revs' ) ) {
			$cont = explode( '|', $params['continue'] );
			if ( count( $cont ) != 3 ) {
				$this->dieUsage( 'Invalid continue param. You should pass the original value returned by the previous query', 'badcontinue' );
			}
			$ns = intval( $cont[0] );
			$title = $this->getDB()->strencode( $this->titleToKey( $cont[1] ) );
			$ts = $this->getDB()->strencode( $cont[2] );
			$op = ( $dir == 'newer' ? '>' : '<' );
			$this->addWhere( "ar_namespace $op $ns OR " .
					"(ar_namespace = $ns AND " .
					"(ar_title $op '$title' OR " .
					"(ar_title = '$title' AND " .
					"ar_timestamp $op= '$ts')))" );
		}

		$this->addOption( 'LIMIT', $limit + 1 );
		$this->addOption( 'USE INDEX', array( 'archive' => ( $mode == 'user' ? 'usertext_timestamp' : 'name_title_timestamp' ) ) );
		if ( $mode == 'all' ) {
			if ( $params['unique'] ) {
				$this->addOption( 'GROUP BY', 'ar_title' );
			} else {
				$this->addOption( 'ORDER BY', 'ar_title, ar_timestamp' );
			}
		} else {
			if ( $mode == 'revs' ) {
				// Sort by ns and title in the same order as timestamp for efficiency
				$this->addWhereRange( 'ar_namespace', $dir, null, null );
				$this->addWhereRange( 'ar_title', $dir, null, null );
			}
			$this->addTimestampWhereRange( 'ar_timestamp', $dir, $params['start'], $params['end'] );
		}
		$res = $this->select( __METHOD__ );
		$pageMap = array(); // Maps ns&title to (fake) pageid
		$count = 0;
		$newPageID = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $limit ) {
				// We've had enough
				if ( $mode == 'all' || $mode == 'revs' ) {
					$this->setContinueEnumParameter( 'continue', intval( $row->ar_namespace ) . '|' .
						$this->keyToTitle( $row->ar_title ) . '|' . $row->ar_timestamp );
				} else {
					$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->ar_timestamp ) );
				}
				break;
			}

			$rev = array();
			$rev['timestamp'] = wfTimestamp( TS_ISO_8601, $row->ar_timestamp );
			if ( $fld_revid ) {
				$rev['revid'] = intval( $row->ar_rev_id );
			}
			if ( $fld_parentid && !is_null( $row->ar_parent_id ) ) {
				$rev['parentid'] = intval( $row->ar_parent_id );
			}
			if ( $fld_user ) {
				$rev['user'] = $row->ar_user_text;
			}
			if ( $fld_userid ) {
				$rev['userid'] = $row->ar_user;
			}
			if ( $fld_comment ) {
				$rev['comment'] = $row->ar_comment;
			}

			$title = Title::makeTitle( $row->ar_namespace, $row->ar_title );

			if ( $fld_parsedcomment ) {
				$rev['parsedcomment'] = Linker::formatComment( $row->ar_comment, $title );
			}
			if ( $fld_minor && $row->ar_minor_edit == 1 ) {
				$rev['minor'] = '';
			}
			if ( $fld_len ) {
				$rev['len'] = $row->ar_len;
			}
			if ( $fld_content ) {
				ApiResult::setContent( $rev, Revision::getRevisionText( $row ) );
			}

			if ( !isset( $pageMap[$row->ar_namespace][$row->ar_title] ) ) {
				$pageID = $newPageID++;
				$pageMap[$row->ar_namespace][$row->ar_title] = $pageID;
				$a['revisions'] = array( $rev );
				$result->setIndexedTagName( $a['revisions'], 'rev' );
				ApiQueryBase::addTitleInfo( $a, $title );
				if ( $fld_token ) {
					$a['token'] = $token;
				}
				$fit = $result->addValue( array( 'query', $this->getModuleName() ), $pageID, $a );
			} else {
				$pageID = $pageMap[$row->ar_namespace][$row->ar_title];
				$fit = $result->addValue(
					array( 'query', $this->getModuleName(), $pageID, 'revisions' ),
					null, $rev );
			}
			if ( !$fit ) {
				if ( $mode == 'all' || $mode == 'revs' ) {
					$this->setContinueEnumParameter( 'continue', intval( $row->ar_namespace ) . '|' .
						$this->keyToTitle( $row->ar_title ) . '|' . $row->ar_timestamp );
				} else {
					$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->ar_timestamp ) );
				}
				break;
			}
		}
		$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'page' );
	}

	public function getAllowedParams() {
		return array(
			'start' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'end' => array(
				ApiBase::PARAM_TYPE => 'timestamp',
			),
			'dir' => array(
				ApiBase::PARAM_TYPE => array(
					'newer',
					'older'
				),
				ApiBase::PARAM_DFLT => 'older'
			),
			'from' => null,
			'to' => null,
			'prefix' => null,
			'continue' => null,
			'unique' => false,
			'user' => array(
				ApiBase::PARAM_TYPE => 'user'
			),
			'excludeuser' => array(
				ApiBase::PARAM_TYPE => 'user'
			),
			'namespace' => array(
				ApiBase::PARAM_TYPE => 'namespace',
				ApiBase::PARAM_DFLT => 0,
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'prop' => array(
				ApiBase::PARAM_DFLT => 'user|comment',
				ApiBase::PARAM_TYPE => array(
					'revid',
					'parentid',
					'user',
					'userid',
					'comment',
					'parsedcomment',
					'minor',
					'len',
					'content',
					'token'
				),
				ApiBase::PARAM_ISMULTI => true
			),
		);
	}

	public function getParamDescription() {
		return array(
			'start' => 'The timestamp to start enumerating from (1,2)',
			'end' => 'The timestamp to stop enumerating at (1,2)',
			'dir' => $this->getDirectionDescription( $this->getModulePrefix(), ' (1, 3)' ),
			'from' => 'Start listing at this title (3)',
			'to' => 'Stop listing at this title (3)',
			'prefix' => 'Search for all page titles that begin with this value (3)',
			'limit' => 'The maximum amount of revisions to list',
			'prop' => array(
				'Which properties to get',
				' revid          - Adds the revision ID of the deleted revision',
				' parentid       - Adds the revision ID of the previous revision to the page',
				' user           - Adds the user who made the revision',
				' userid         - Adds the user ID whom made the revision',
				' comment        - Adds the comment of the revision',
				' parsedcomment  - Adds the parsed comment of the revision',
				' minor          - Tags if the revision is minor',
				' len            - Adds the length of the revision',
				' content        - Adds the content of the revision',
				' token          - Gives the edit token',
			),
			'namespace' => 'Only list pages in this namespace (3)',
			'user' => 'Only list revisions by this user',
			'excludeuser' => 'Don\'t list revisions by this user',
			'continue' => 'When more results are available, use this to continue (3)',
			'unique' => 'List only one revision for each page (3)',
		);
	}

	public function getDescription() {
		$p = $this->getModulePrefix();
		return array(
			'List deleted revisions.',
			'Operates in three modes:',
			' 1) List deleted revisions for the given title(s), sorted by timestamp',
			' 2) List deleted contributions for the given user, sorted by timestamp (no titles specified)',
			" 3) List all deleted revisions in the given namespace, sorted by title and timestamp (no titles specified, {$p}user not set)",
			'Certain parameters only apply to some modes and are ignored in others.',
			'For instance, a parameter marked (1) only applies to mode 1 and is ignored in modes 2 and 3',
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'permissiondenied', 'info' => 'You don\'t have permission to view deleted revision information' ),
			array( 'code' => 'badparams', 'info' => 'user and excludeuser cannot be used together' ),
			array( 'code' => 'permissiondenied', 'info' => 'You don\'t have permission to view deleted revision content' ),
			array( 'code' => 'badcontinue', 'info' => 'Invalid continue param. You should pass the original value returned by the previous query' ),
			array( 'code' => 'badparams', 'info' => "The 'from' parameter cannot be used in modes 1 or 2" ),
			array( 'code' => 'badparams', 'info' => "The 'to' parameter cannot be used in modes 1 or 2" ),
			array( 'code' => 'badparams', 'info' => "The 'prefix' parameter cannot be used in modes 1 or 2" ),
			array( 'code' => 'badparams', 'info' => "The 'continue' parameter cannot be used in modes 1 or 2" ),
			array( 'code' => 'badparams', 'info' => "The 'start' parameter cannot be used in mode 3" ),
			array( 'code' => 'badparams', 'info' => "The 'end' parameter cannot be used in mode 3" ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&list=deletedrevs&titles=Main%20Page|Talk:Main%20Page&drprop=user|comment|content'
				=> 'List the last deleted revisions of Main Page and Talk:Main Page, with content (mode 1):',
			'api.php?action=query&list=deletedrevs&druser=Bob&drlimit=50'
				=> 'List the last 50 deleted contributions by Bob (mode 2):',
			'api.php?action=query&list=deletedrevs&drdir=newer&drlimit=50'
				=> 'List the first 50 deleted revisions in the main namespace (mode 3):',
			'api.php?action=query&list=deletedrevs&drdir=newer&drlimit=50&drnamespace=1&drunique='
				=> 'List the first 50 deleted pages in the Talk namespace (mode 3):',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Deletedrevs';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
