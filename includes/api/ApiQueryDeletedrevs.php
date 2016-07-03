<?php
/**
 *
 *
 * Created on Jul 2, 2007
 *
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
 * Query module to enumerate all deleted revisions.
 *
 * @ingroup API
 * @deprecated since 1.25
 */
class ApiQueryDeletedrevs extends ApiQueryBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'dr' );
	}

	public function execute() {
		$user = $this->getUser();
		// Before doing anything at all, let's check permissions
		if ( !$user->isAllowed( 'deletedhistory' ) ) {
			$this->dieUsage(
				'You don\'t have permission to view deleted revision information',
				'permissiondenied'
			);
		}

		$this->setWarning(
			'list=deletedrevs has been deprecated. Please use prop=deletedrevisions or ' .
			'list=alldeletedrevisions instead.'
		);
		$this->logFeatureUsage( 'action=query&list=deletedrevs' );

		$db = $this->getDB();
		$params = $this->extractRequestParams( false );
		$prop = array_flip( $params['prop'] );
		$fld_parentid = isset( $prop['parentid'] );
		$fld_revid = isset( $prop['revid'] );
		$fld_user = isset( $prop['user'] );
		$fld_userid = isset( $prop['userid'] );
		$fld_comment = isset( $prop['comment'] );
		$fld_parsedcomment = isset( $prop['parsedcomment'] );
		$fld_minor = isset( $prop['minor'] );
		$fld_len = isset( $prop['len'] );
		$fld_sha1 = isset( $prop['sha1'] );
		$fld_content = isset( $prop['content'] );
		$fld_token = isset( $prop['token'] );
		$fld_tags = isset( $prop['tags'] );

		if ( isset( $prop['token'] ) ) {
			$p = $this->getModulePrefix();
			$this->setWarning(
				"{$p}prop=token has been deprecated. Please use action=query&meta=tokens instead."
			);
		}

		// If we're in a mode that breaks the same-origin policy, no tokens can
		// be obtained
		if ( $this->lacksSameOriginSecurity() ) {
			$fld_token = false;
		}

		// If user can't undelete, no tokens
		if ( !$user->isAllowed( 'undelete' ) ) {
			$fld_token = false;
		}

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
			foreach ( [ 'from', 'to', 'prefix', /*'namespace', 'unique'*/ ] as $p ) {
				if ( !is_null( $params[$p] ) ) {
					$this->dieUsage( "The '{$p}' parameter cannot be used in modes 1 or 2", 'badparams' );
				}
			}
		} else {
			foreach ( [ 'start', 'end' ] as $p ) {
				if ( !is_null( $params[$p] ) ) {
					$this->dieUsage( "The {$p} parameter cannot be used in mode 3", 'badparams' );
				}
			}
		}

		if ( !is_null( $params['user'] ) && !is_null( $params['excludeuser'] ) ) {
			$this->dieUsage( 'user and excludeuser cannot be used together', 'badparams' );
		}

		$this->addTables( 'archive' );
		$this->addFields( [ 'ar_title', 'ar_namespace', 'ar_timestamp', 'ar_deleted', 'ar_id' ] );

		$this->addFieldsIf( 'ar_parent_id', $fld_parentid );
		$this->addFieldsIf( 'ar_rev_id', $fld_revid );
		$this->addFieldsIf( 'ar_user_text', $fld_user );
		$this->addFieldsIf( 'ar_user', $fld_userid );
		$this->addFieldsIf( 'ar_comment', $fld_comment || $fld_parsedcomment );
		$this->addFieldsIf( 'ar_minor_edit', $fld_minor );
		$this->addFieldsIf( 'ar_len', $fld_len );
		$this->addFieldsIf( 'ar_sha1', $fld_sha1 );

		if ( $fld_tags ) {
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

		if ( $fld_content ) {
			// Modern MediaWiki has the content for deleted revs in the 'text'
			// table using fields old_text and old_flags. But revisions deleted
			// pre-1.5 store the content in the 'archive' table directly using
			// fields ar_text and ar_flags, and no corresponding 'text' row. So
			// we have to LEFT JOIN and fetch all four fields, plus ar_text_id
			// to be able to tell the difference.
			$this->addTables( 'text' );
			$this->addJoinConds(
				[ 'text' => [ 'LEFT JOIN', [ 'ar_text_id=old_id' ] ] ]
			);
			$this->addFields( [ 'ar_text', 'ar_flags', 'ar_text_id', 'old_text', 'old_flags' ] );

			// This also means stricter restrictions
			if ( !$user->isAllowedAny( 'undelete', 'deletedtext' ) ) {
				$this->dieUsage(
					'You don\'t have permission to view deleted revision content',
					'permissiondenied'
				);
			}
		}
		// Check limits
		$userMax = $fld_content ? ApiBase::LIMIT_SML1 : ApiBase::LIMIT_BIG1;
		$botMax = $fld_content ? ApiBase::LIMIT_SML2 : ApiBase::LIMIT_BIG2;

		$limit = $params['limit'];

		if ( $limit == 'max' ) {
			$limit = $this->getMain()->canApiHighLimits() ? $botMax : $userMax;
			$this->getResult()->addParsedLimit( $this->getModuleName(), $limit );
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

			$from = $params['from'] === null
				? null
				: $this->titlePartToKey( $params['from'], $params['namespace'] );
			$to = $params['to'] === null
				? null
				: $this->titlePartToKey( $params['to'], $params['namespace'] );
			$this->addWhereRange( 'ar_title', $dir, $from, $to );

			if ( isset( $params['prefix'] ) ) {
				$this->addWhere( 'ar_title' . $db->buildLike(
					$this->titlePartToKey( $params['prefix'], $params['namespace'] ),
					$db->anyString() ) );
			}
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
			if ( $mode == 'all' || $mode == 'revs' ) {
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

		$this->addOption( 'LIMIT', $limit + 1 );
		$this->addOption(
			'USE INDEX',
			[ 'archive' => ( $mode == 'user' ? 'usertext_timestamp' : 'name_title_timestamp' ) ]
		);
		if ( $mode == 'all' ) {
			if ( $params['unique'] ) {
				// @todo Does this work on non-MySQL?
				$this->addOption( 'GROUP BY', 'ar_title' );
			} else {
				$sort = ( $dir == 'newer' ? '' : ' DESC' );
				$this->addOption( 'ORDER BY', [
					'ar_title' . $sort,
					'ar_timestamp' . $sort,
					'ar_id' . $sort,
				] );
			}
		} else {
			if ( $mode == 'revs' ) {
				// Sort by ns and title in the same order as timestamp for efficiency
				$this->addWhereRange( 'ar_namespace', $dir, null, null );
				$this->addWhereRange( 'ar_title', $dir, null, null );
			}
			$this->addTimestampWhereRange( 'ar_timestamp', $dir, $params['start'], $params['end'] );
			// Include in ORDER BY for uniqueness
			$this->addWhereRange( 'ar_id', $dir, null, null );
		}
		$res = $this->select( __METHOD__ );
		$pageMap = []; // Maps ns&title to (fake) pageid
		$count = 0;
		$newPageID = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $limit ) {
				// We've had enough
				if ( $mode == 'all' || $mode == 'revs' ) {
					$this->setContinueEnumParameter( 'continue',
						"$row->ar_namespace|$row->ar_title|$row->ar_timestamp|$row->ar_id"
					);
				} else {
					$this->setContinueEnumParameter( 'continue', "$row->ar_timestamp|$row->ar_id" );
				}
				break;
			}

			$rev = [];
			$anyHidden = false;

			$rev['timestamp'] = wfTimestamp( TS_ISO_8601, $row->ar_timestamp );
			if ( $fld_revid ) {
				$rev['revid'] = intval( $row->ar_rev_id );
			}
			if ( $fld_parentid && !is_null( $row->ar_parent_id ) ) {
				$rev['parentid'] = intval( $row->ar_parent_id );
			}
			if ( $fld_user || $fld_userid ) {
				if ( $row->ar_deleted & Revision::DELETED_USER ) {
					$rev['userhidden'] = true;
					$anyHidden = true;
				}
				if ( Revision::userCanBitfield( $row->ar_deleted, Revision::DELETED_USER, $user ) ) {
					if ( $fld_user ) {
						$rev['user'] = $row->ar_user_text;
					}
					if ( $fld_userid ) {
						$rev['userid'] = (int)$row->ar_user;
					}
				}
			}

			if ( $fld_comment || $fld_parsedcomment ) {
				if ( $row->ar_deleted & Revision::DELETED_COMMENT ) {
					$rev['commenthidden'] = true;
					$anyHidden = true;
				}
				if ( Revision::userCanBitfield( $row->ar_deleted, Revision::DELETED_COMMENT, $user ) ) {
					if ( $fld_comment ) {
						$rev['comment'] = $row->ar_comment;
					}
					if ( $fld_parsedcomment ) {
						$title = Title::makeTitle( $row->ar_namespace, $row->ar_title );
						$rev['parsedcomment'] = Linker::formatComment( $row->ar_comment, $title );
					}
				}
			}

			if ( $fld_minor ) {
				$rev['minor'] = $row->ar_minor_edit == 1;
			}
			if ( $fld_len ) {
				$rev['len'] = $row->ar_len;
			}
			if ( $fld_sha1 ) {
				if ( $row->ar_deleted & Revision::DELETED_TEXT ) {
					$rev['sha1hidden'] = true;
					$anyHidden = true;
				}
				if ( Revision::userCanBitfield( $row->ar_deleted, Revision::DELETED_TEXT, $user ) ) {
					if ( $row->ar_sha1 != '' ) {
						$rev['sha1'] = Wikimedia\base_convert( $row->ar_sha1, 36, 16, 40 );
					} else {
						$rev['sha1'] = '';
					}
				}
			}
			if ( $fld_content ) {
				if ( $row->ar_deleted & Revision::DELETED_TEXT ) {
					$rev['texthidden'] = true;
					$anyHidden = true;
				}
				if ( Revision::userCanBitfield( $row->ar_deleted, Revision::DELETED_TEXT, $user ) ) {
					if ( isset( $row->ar_text ) && !$row->ar_text_id ) {
						// Pre-1.5 ar_text row (if condition from Revision::newFromArchiveRow)
						ApiResult::setContentValue( $rev, 'text', Revision::getRevisionText( $row, 'ar_' ) );
					} else {
						ApiResult::setContentValue( $rev, 'text', Revision::getRevisionText( $row ) );
					}
				}
			}

			if ( $fld_tags ) {
				if ( $row->ts_tags ) {
					$tags = explode( ',', $row->ts_tags );
					ApiResult::setIndexedTagName( $tags, 'tag' );
					$rev['tags'] = $tags;
				} else {
					$rev['tags'] = [];
				}
			}

			if ( $anyHidden && ( $row->ar_deleted & Revision::DELETED_RESTRICTED ) ) {
				$rev['suppressed'] = true;
			}

			if ( !isset( $pageMap[$row->ar_namespace][$row->ar_title] ) ) {
				$pageID = $newPageID++;
				$pageMap[$row->ar_namespace][$row->ar_title] = $pageID;
				$a['revisions'] = [ $rev ];
				ApiResult::setIndexedTagName( $a['revisions'], 'rev' );
				$title = Title::makeTitle( $row->ar_namespace, $row->ar_title );
				ApiQueryBase::addTitleInfo( $a, $title );
				if ( $fld_token ) {
					$a['token'] = $token;
				}
				$fit = $result->addValue( [ 'query', $this->getModuleName() ], $pageID, $a );
			} else {
				$pageID = $pageMap[$row->ar_namespace][$row->ar_title];
				$fit = $result->addValue(
					[ 'query', $this->getModuleName(), $pageID, 'revisions' ],
					null, $rev );
			}
			if ( !$fit ) {
				if ( $mode == 'all' || $mode == 'revs' ) {
					$this->setContinueEnumParameter( 'continue',
						"$row->ar_namespace|$row->ar_title|$row->ar_timestamp|$row->ar_id"
					);
				} else {
					$this->setContinueEnumParameter( 'continue', "$row->ar_timestamp|$row->ar_id" );
				}
				break;
			}
		}
		$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'page' );
	}

	public function isDeprecated() {
		return true;
	}

	public function getAllowedParams() {
		return [
			'start' => [
				ApiBase::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 1, 2 ] ],
			],
			'end' => [
				ApiBase::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 1, 2 ] ],
			],
			'dir' => [
				ApiBase::PARAM_TYPE => [
					'newer',
					'older'
				],
				ApiBase::PARAM_DFLT => 'older',
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 1, 3 ] ],
			],
			'from' => [
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 3 ] ],
			],
			'to' => [
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 3 ] ],
			],
			'prefix' => [
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 3 ] ],
			],
			'unique' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 3 ] ],
			],
			'namespace' => [
				ApiBase::PARAM_TYPE => 'namespace',
				ApiBase::PARAM_DFLT => NS_MAIN,
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 3 ] ],
			],
			'tag' => null,
			'user' => [
				ApiBase::PARAM_TYPE => 'user'
			],
			'excludeuser' => [
				ApiBase::PARAM_TYPE => 'user'
			],
			'prop' => [
				ApiBase::PARAM_DFLT => 'user|comment',
				ApiBase::PARAM_TYPE => [
					'revid',
					'parentid',
					'user',
					'userid',
					'comment',
					'parsedcomment',
					'minor',
					'len',
					'sha1',
					'content',
					'token',
					'tags'
				],
				ApiBase::PARAM_ISMULTI => true
			],
			'limit' => [
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=deletedrevs&titles=Main%20Page|Talk:Main%20Page&' .
				'drprop=user|comment|content'
				=> 'apihelp-query+deletedrevs-example-mode1',
			'action=query&list=deletedrevs&druser=Bob&drlimit=50'
				=> 'apihelp-query+deletedrevs-example-mode2',
			'action=query&list=deletedrevs&drdir=newer&drlimit=50'
				=> 'apihelp-query+deletedrevs-example-mode3-main',
			'action=query&list=deletedrevs&drdir=newer&drlimit=50&drnamespace=1&drunique='
				=> 'apihelp-query+deletedrevs-example-mode3-talk',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Deletedrevs';
	}
}
