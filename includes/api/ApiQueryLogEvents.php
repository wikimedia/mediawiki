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
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Storage\NameTableAccessException;

/**
 * Query action to List the log events, with optional filtering by various parameters.
 *
 * @ingroup API
 */
class ApiQueryLogEvents extends ApiQueryBase {

	/** @var CommentStore */
	private $commentStore;

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'le' );
	}

	private $fld_ids = false, $fld_title = false, $fld_type = false,
		$fld_user = false, $fld_userid = false,
		$fld_timestamp = false, $fld_comment = false, $fld_parsedcomment = false,
		$fld_details = false, $fld_tags = false;

	public function execute() {
		$params = $this->extractRequestParams();
		$db = $this->getDB();
		$this->commentStore = CommentStore::getStore();
		$this->requireMaxOneParameter( $params, 'title', 'prefix', 'namespace' );

		$prop = array_flip( $params['prop'] );

		$this->fld_ids = isset( $prop['ids'] );
		$this->fld_title = isset( $prop['title'] );
		$this->fld_type = isset( $prop['type'] );
		$this->fld_user = isset( $prop['user'] );
		$this->fld_userid = isset( $prop['userid'] );
		$this->fld_timestamp = isset( $prop['timestamp'] );
		$this->fld_comment = isset( $prop['comment'] );
		$this->fld_parsedcomment = isset( $prop['parsedcomment'] );
		$this->fld_details = isset( $prop['details'] );
		$this->fld_tags = isset( $prop['tags'] );

		$hideLogs = LogEventsList::getExcludeClause( $db, 'user', $this->getUser() );
		if ( $hideLogs !== false ) {
			$this->addWhere( $hideLogs );
		}

		$actorMigration = ActorMigration::newMigration();
		$actorQuery = $actorMigration->getJoin( 'log_user' );
		$this->addTables( 'logging' );
		$this->addTables( $actorQuery['tables'] );
		$this->addTables( [ 'user', 'page' ] );
		$this->addJoinConds( $actorQuery['joins'] );
		$this->addJoinConds( [
			'user' => [ 'LEFT JOIN',
				'user_id=' . $actorQuery['fields']['log_user'] ],
			'page' => [ 'LEFT JOIN',
				[ 'log_namespace=page_namespace',
					'log_title=page_title' ] ] ] );

		$this->addFields( [
			'log_id',
			'log_type',
			'log_action',
			'log_timestamp',
			'log_deleted',
		] );

		$this->addFieldsIf( 'page_id', $this->fld_ids );
		// log_page is the page_id saved at log time, whereas page_id is from a
		// join at query time.  This leads to different results in various
		// scenarios, e.g. deletion, recreation.
		$this->addFieldsIf( 'log_page', $this->fld_ids );
		$this->addFieldsIf( $actorQuery['fields'] + [ 'user_name' ], $this->fld_user );
		$this->addFieldsIf( $actorQuery['fields'], $this->fld_userid );
		$this->addFieldsIf(
			[ 'log_namespace', 'log_title' ],
			$this->fld_title || $this->fld_parsedcomment
		);
		$this->addFieldsIf( 'log_params', $this->fld_details );

		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			$commentQuery = $this->commentStore->getJoin( 'log_comment' );
			$this->addTables( $commentQuery['tables'] );
			$this->addFields( $commentQuery['fields'] );
			$this->addJoinConds( $commentQuery['joins'] );
		}

		if ( $this->fld_tags ) {
			$this->addFields( [ 'ts_tags' => ChangeTags::makeTagSummarySubquery( 'logging' ) ] );
		}

		if ( $params['tag'] !== null ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds( [ 'change_tag' => [ 'JOIN',
				[ 'log_id=ct_log_id' ] ] ] );
			$changeTagDefStore = MediaWikiServices::getInstance()->getChangeTagDefStore();
			try {
				$this->addWhereFld( 'ct_tag_id', $changeTagDefStore->getId( $params['tag'] ) );
			} catch ( NameTableAccessException $exception ) {
				// Return nothing.
				$this->addWhere( '1=0' );
			}
		}

		if ( $params['action'] !== null ) {
			// Do validation of action param, list of allowed actions can contains wildcards
			// Allow the param, when the actions is in the list or a wildcard version is listed.
			$logAction = $params['action'];
			if ( strpos( $logAction, '/' ) === false ) {
				// all items in the list have a slash
				$valid = false;
			} else {
				$logActions = array_flip( $this->getAllowedLogActions() );
				list( $type, $action ) = explode( '/', $logAction, 2 );
				$valid = isset( $logActions[$logAction] ) || isset( $logActions[$type . '/*'] );
			}

			if ( !$valid ) {
				$encParamName = $this->encodeParamName( 'action' );
				$this->dieWithError(
					[ 'apierror-unrecognizedvalue', $encParamName, wfEscapeWikiText( $logAction ) ],
					"unknown_$encParamName"
				);
			}

			$this->addWhereFld( 'log_type', $type );
			$this->addWhereFld( 'log_action', $action );
		} elseif ( $params['type'] !== null ) {
			$this->addWhereFld( 'log_type', $params['type'] );
		}

		$this->addTimestampWhereRange(
			'log_timestamp',
			$params['dir'],
			$params['start'],
			$params['end']
		);
		// Include in ORDER BY for uniqueness
		$this->addWhereRange( 'log_id', $params['dir'], null, null );

		if ( $params['continue'] !== null ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 2 );
			$op = ( $params['dir'] === 'newer' ? '>' : '<' );
			$continueTimestamp = $db->addQuotes( $db->timestamp( $cont[0] ) );
			$continueId = (int)$cont[1];
			$this->dieContinueUsageIf( $continueId != $cont[1] );
			$this->addWhere( "log_timestamp $op $continueTimestamp OR " .
				"(log_timestamp = $continueTimestamp AND " .
				"log_id $op= $continueId)"
			);
		}

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );

		$user = $params['user'];
		if ( $user !== null ) {
			// Note the joins in $q are the same as those from ->getJoin() above
			// so we only need to add 'conds' here.
			$q = $actorMigration->getWhere( $db, 'log_user', $params['user'] );
			$this->addWhere( $q['conds'] );

			// T71222: MariaDB's optimizer, at least 10.1.37 and .38, likes to choose a wildly bad plan for
			// some reason for this code path. Tell it not to use the wrong index it wants to pick.
			// @phan-suppress-next-line PhanTypeMismatchArgument
			$this->addOption( 'IGNORE INDEX', [ 'logging' => [ 'times' ] ] );
		}

		$title = $params['title'];
		if ( $title !== null ) {
			$titleObj = Title::newFromText( $title );
			if ( $titleObj === null ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $title ) ] );
			}
			$this->addWhereFld( 'log_namespace', $titleObj->getNamespace() );
			$this->addWhereFld( 'log_title', $titleObj->getDBkey() );
		}

		if ( $params['namespace'] !== null ) {
			$this->addWhereFld( 'log_namespace', $params['namespace'] );
		}

		$prefix = $params['prefix'];

		if ( $prefix !== null ) {
			if ( $this->getConfig()->get( 'MiserMode' ) ) {
				$this->dieWithError( 'apierror-prefixsearchdisabled' );
			}

			$title = Title::newFromText( $prefix );
			if ( $title === null ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $prefix ) ] );
			}
			$this->addWhereFld( 'log_namespace', $title->getNamespace() );
			$this->addWhere( 'log_title ' . $db->buildLike( $title->getDBkey(), $db->anyString() ) );
		}

		// Paranoia: avoid brute force searches (T19342)
		if ( $params['namespace'] !== null || $title !== null || $user !== null ) {
			if ( !$this->getPermissionManager()->userHasRight( $this->getUser(), 'deletedhistory' ) ) {
				$titleBits = LogPage::DELETED_ACTION;
				$userBits = LogPage::DELETED_USER;
			} elseif ( !$this->getPermissionManager()
				->userHasAnyRight( $this->getUser(), 'suppressrevision', 'viewsuppressed' )
			) {
				$titleBits = LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED;
				$userBits = LogPage::DELETED_USER | LogPage::DELETED_RESTRICTED;
			} else {
				$titleBits = 0;
				$userBits = 0;
			}
			if ( ( $params['namespace'] !== null || $title !== null ) && $titleBits ) {
				$this->addWhere( $db->bitAnd( 'log_deleted', $titleBits ) . " != $titleBits" );
			}
			if ( $user !== null && $userBits ) {
				$this->addWhere( $db->bitAnd( 'log_deleted', $userBits ) . " != $userBits" );
			}
		}

		// T220999: MySQL/MariaDB (10.1.37) can sometimes irrationally decide that querying `actor` before
		// `logging` and filesorting is somehow better than querying $limit+1 rows from `logging`.
		// Tell it not to reorder the query. But not when `letag` was used, as it seems as likely
		// to be harmed as helped in that case.
		if ( $params['tag'] === null ) {
			$this->addOption( 'STRAIGHT_JOIN' );
		}

		$count = 0;
		$res = $this->select( __METHOD__ );

		if ( $this->fld_title ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__, 'log' );
		}

		$result = $this->getResult();
		foreach ( $res as $row ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', "$row->log_timestamp|$row->log_id" );
				break;
			}

			$vals = $this->extractRowInfo( $row );
			$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $vals );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', "$row->log_timestamp|$row->log_id" );
				break;
			}
		}
		$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'item' );
	}

	private function extractRowInfo( $row ) {
		$logEntry = DatabaseLogEntry::newFromRow( $row );
		$vals = [
			ApiResult::META_TYPE => 'assoc',
		];
		$anyHidden = false;
		$user = $this->getUser();

		if ( $this->fld_ids ) {
			$vals['logid'] = (int)$row->log_id;
		}

		if ( $this->fld_title || $this->fld_parsedcomment ) {
			$title = Title::makeTitle( $row->log_namespace, $row->log_title );
		}

		if ( $this->fld_title || $this->fld_ids || $this->fld_details && $row->log_params !== '' ) {
			if ( LogEventsList::isDeleted( $row, LogPage::DELETED_ACTION ) ) {
				$vals['actionhidden'] = true;
				$anyHidden = true;
			}
			if ( LogEventsList::userCan( $row, LogPage::DELETED_ACTION, $user ) ) {
				if ( $this->fld_title ) {
					ApiQueryBase::addTitleInfo( $vals, $title );
				}
				if ( $this->fld_ids ) {
					$vals['pageid'] = (int)$row->page_id;
					$vals['logpage'] = (int)$row->log_page;
				}
				if ( $this->fld_details ) {
					$vals['params'] = LogFormatter::newFromEntry( $logEntry )->formatParametersForApi();
				}
			}
		}

		if ( $this->fld_type ) {
			$vals['type'] = $row->log_type;
			$vals['action'] = $row->log_action;
		}

		if ( $this->fld_user || $this->fld_userid ) {
			if ( LogEventsList::isDeleted( $row, LogPage::DELETED_USER ) ) {
				$vals['userhidden'] = true;
				$anyHidden = true;
			}
			if ( LogEventsList::userCan( $row, LogPage::DELETED_USER, $user ) ) {
				if ( $this->fld_user ) {
					$vals['user'] = $row->user_name ?? $row->log_user_text;
				}
				if ( $this->fld_userid ) {
					$vals['userid'] = (int)$row->log_user;
				}

				if ( !$row->log_user ) {
					$vals['anon'] = true;
				}
			}
		}
		if ( $this->fld_timestamp ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->log_timestamp );
		}

		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			if ( LogEventsList::isDeleted( $row, LogPage::DELETED_COMMENT ) ) {
				$vals['commenthidden'] = true;
				$anyHidden = true;
			}
			if ( LogEventsList::userCan( $row, LogPage::DELETED_COMMENT, $user ) ) {
				$comment = $this->commentStore->getComment( 'log_comment', $row )->text;
				if ( $this->fld_comment ) {
					$vals['comment'] = $comment;
				}

				if ( $this->fld_parsedcomment ) {
					$vals['parsedcomment'] = Linker::formatComment( $comment, $title );
				}
			}
		}

		if ( $this->fld_tags ) {
			if ( $row->ts_tags ) {
				$tags = explode( ',', $row->ts_tags );
				ApiResult::setIndexedTagName( $tags, 'tag' );
				$vals['tags'] = $tags;
			} else {
				$vals['tags'] = [];
			}
		}

		if ( $anyHidden && LogEventsList::isDeleted( $row, LogPage::DELETED_RESTRICTED ) ) {
			$vals['suppressed'] = true;
		}

		return $vals;
	}

	/**
	 * @return array
	 */
	private function getAllowedLogActions() {
		$config = $this->getConfig();
		return array_keys( array_merge(
			$config->get( 'LogActions' ),
			$config->get( 'LogActionsHandlers' )
		) );
	}

	public function getCacheMode( $params ) {
		if ( $this->userCanSeeRevDel() ) {
			return 'private';
		}
		if ( $params['prop'] !== null && in_array( 'parsedcomment', $params['prop'] ) ) {
			// formatComment() calls wfMessage() among other things
			return 'anon-public-user-private';
		} elseif ( LogEventsList::getExcludeClause( $this->getDB(), 'user', $this->getUser() )
			=== LogEventsList::getExcludeClause( $this->getDB(), 'public' )
		) { // Output can only contain public data.
			return 'public';
		} else {
			return 'anon-public-user-private';
		}
	}

	public function getAllowedParams( $flags = 0 ) {
		$config = $this->getConfig();
		if ( $flags & ApiBase::GET_VALUES_FOR_HELP ) {
			$logActions = $this->getAllowedLogActions();
			sort( $logActions );
		} else {
			$logActions = null;
		}
		$ret = [
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'ids|title|type|user|timestamp|comment|details',
				ApiBase::PARAM_TYPE => [
					'ids',
					'title',
					'type',
					'user',
					'userid',
					'timestamp',
					'comment',
					'parsedcomment',
					'details',
					'tags'
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'type' => [
				ApiBase::PARAM_TYPE => LogPage::validTypes(),
			],
			'action' => [
				// validation on request is done in execute()
				ApiBase::PARAM_TYPE => $logActions
			],
			'start' => [
				ApiBase::PARAM_TYPE => 'timestamp'
			],
			'end' => [
				ApiBase::PARAM_TYPE => 'timestamp'
			],
			'dir' => [
				ApiBase::PARAM_DFLT => 'older',
				ApiBase::PARAM_TYPE => [
					'newer',
					'older'
				],
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
			],
			'user' => [
				ApiBase::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'id', 'interwiki' ],
				UserDef::PARAM_RETURN_OBJECT => true,
			],
			'title' => null,
			'namespace' => [
				ApiBase::PARAM_TYPE => 'namespace',
				ApiBase::PARAM_EXTRA_NAMESPACES => [ NS_MEDIA, NS_SPECIAL ],
			],
			'prefix' => [],
			'tag' => null,
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

		if ( $config->get( 'MiserMode' ) ) {
			$ret['prefix'][ApiBase::PARAM_HELP_MSG] = 'api-help-param-disabled-in-miser-mode';
		}

		return $ret;
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=logevents'
				=> 'apihelp-query+logevents-example-simple',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Logevents';
	}
}
