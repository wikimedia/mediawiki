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

namespace MediaWiki\Api;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Logging\DatabaseLogEntry;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\Logging\LogPage;
use MediaWiki\MainConfigNames;
use MediaWiki\ParamValidator\TypeDef\NamespaceDef;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Title\Title;
use MediaWiki\User\UserNameUtils;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

/**
 * Query action to List the log events, with optional filtering by various parameters.
 *
 * @ingroup API
 */
class ApiQueryLogEvents extends ApiQueryBase {

	private CommentStore $commentStore;
	private CommentFormatter $commentFormatter;
	private NameTableStore $changeTagDefStore;
	private ChangeTagsStore $changeTagsStore;
	private UserNameUtils $userNameUtils;
	private LogFormatterFactory $logFormatterFactory;

	/** @var string[]|null */
	private $formattedComments;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		CommentStore $commentStore,
		RowCommentFormatter $commentFormatter,
		NameTableStore $changeTagDefStore,
		ChangeTagsStore $changeTagsStore,
		UserNameUtils $userNameUtils,
		LogFormatterFactory $logFormatterFactory
	) {
		parent::__construct( $query, $moduleName, 'le' );
		$this->commentStore = $commentStore;
		$this->commentFormatter = $commentFormatter;
		$this->changeTagDefStore = $changeTagDefStore;
		$this->changeTagsStore = $changeTagsStore;
		$this->userNameUtils = $userNameUtils;
		$this->logFormatterFactory = $logFormatterFactory;
	}

	private bool $fld_ids = false;
	private bool $fld_title = false;
	private bool $fld_type = false;
	private bool $fld_user = false;
	private bool $fld_userid = false;
	private bool $fld_timestamp = false;
	private bool $fld_comment = false;
	private bool $fld_parsedcomment = false;
	private bool $fld_details = false;
	private bool $fld_tags = false;

	public function execute() {
		$params = $this->extractRequestParams();
		$db = $this->getDB();
		$this->requireMaxOneParameter( $params, 'title', 'prefix', 'namespace' );

		$prop = array_fill_keys( $params['prop'], true );

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

		$hideLogs = LogEventsList::getExcludeClause( $db, 'user', $this->getAuthority() );
		if ( $hideLogs !== false ) {
			$this->addWhere( $hideLogs );
		}

		$this->addTables( 'logging' );

		$this->addFields( [
			'log_id',
			'log_type',
			'log_action',
			'log_timestamp',
			'log_deleted',
		] );

		$user = $params['user'];
		if ( $this->fld_user || $this->fld_userid || $user !== null ) {
			$this->addTables( 'actor' );
			$this->addJoinConds( [
				'actor' => [ 'JOIN', 'actor_id=log_actor' ],
			] );
			$this->addFieldsIf( [ 'actor_name', 'actor_user' ], $this->fld_user );
			$this->addFieldsIf( 'actor_user', $this->fld_userid );
			if ( $user !== null ) {
				$this->addWhereFld( 'actor_name', $user );
			}
		}

		if ( $this->fld_ids ) {
			$this->addTables( 'page' );
			$this->addJoinConds( [
				'page' => [ 'LEFT JOIN',
					[ 'log_namespace=page_namespace',
						'log_title=page_title' ] ]
			] );
			// log_page is the page_id saved at log time, whereas page_id is from a
			// join at query time.  This leads to different results in various
			// scenarios, e.g. deletion, recreation.
			$this->addFields( [ 'page_id', 'log_page' ] );
		}
		$this->addFieldsIf(
			[ 'log_namespace', 'log_title' ],
			$this->fld_title || $this->fld_parsedcomment
		);
		$this->addFieldsIf( 'log_params', $this->fld_details || $this->fld_ids );

		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			$commentQuery = $this->commentStore->getJoin( 'log_comment' );
			$this->addTables( $commentQuery['tables'] );
			$this->addFields( $commentQuery['fields'] );
			$this->addJoinConds( $commentQuery['joins'] );
		}

		if ( $this->fld_tags ) {
			$this->addFields( [
				'ts_tags' => $this->changeTagsStore->makeTagSummarySubquery( 'logging' )
			] );
		}

		if ( $params['tag'] !== null ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds( [ 'change_tag' => [ 'JOIN',
				[ 'log_id=ct_log_id' ] ] ] );
			try {
				$this->addWhereFld( 'ct_tag_id', $this->changeTagDefStore->getId( $params['tag'] ) );
			} catch ( NameTableAccessException ) {
				// Return nothing.
				$this->addWhere( '1=0' );
			}
		}

		if ( $params['action'] !== null ) {
			// Do validation of action param, list of allowed actions can contains wildcards
			// Allow the param, when the actions is in the list or a wildcard version is listed.
			$logAction = $params['action'];
			if ( !str_contains( $logAction, '/' ) ) {
				// all items in the list have a slash
				$valid = false;
			} else {
				$logActions = array_fill_keys( $this->getAllowedLogActions(), true );
				[ $type, $action ] = explode( '/', $logAction, 2 );
				$valid = isset( $logActions[$logAction] ) || isset( $logActions[$type . '/*'] );
			}

			if ( !$valid ) {
				$encParamName = $this->encodeParamName( 'action' );
				$this->dieWithError(
					[ 'apierror-unrecognizedvalue', $encParamName, wfEscapeWikiText( $logAction ) ],
					"unknown_$encParamName"
				);
			}

			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable,PhanPossiblyUndeclaredVariable T240141
			$this->addWhereFld( 'log_type', $type );
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable,PhanPossiblyUndeclaredVariable T240141
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
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'timestamp', 'int' ] );
			$op = ( $params['dir'] === 'newer' ? '>=' : '<=' );
			$this->addWhere( $db->buildComparison( $op, [
				'log_timestamp' => $db->timestamp( $cont[0] ),
				'log_id' => $cont[1],
			] ) );
		}

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );

		$title = $params['title'];
		if ( $title !== null ) {
			$titleObj = Title::newFromText( $title );
			if ( $titleObj === null || $titleObj->isExternal() ) {
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
			if ( $this->getConfig()->get( MainConfigNames::MiserMode ) ) {
				$this->dieWithError( 'apierror-prefixsearchdisabled' );
			}

			$title = Title::newFromText( $prefix );
			if ( $title === null || $title->isExternal() ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $prefix ) ] );
			}
			$this->addWhereFld( 'log_namespace', $title->getNamespace() );
			$this->addWhere(
				$db->expr( 'log_title', IExpression::LIKE, new LikeValue( $title->getDBkey(), $db->anyString() ) )
			);
		}

		// Paranoia: avoid brute force searches (T19342)
		if ( $params['namespace'] !== null || $title !== null || $user !== null ) {
			if ( !$this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
				$titleBits = LogPage::DELETED_ACTION;
				$userBits = LogPage::DELETED_USER;
			} elseif ( !$this->getAuthority()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
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
		// If "user" was specified, it's obviously correct to query actor first (T282122)
		if ( $params['tag'] === null && $user === null ) {
			$this->addOption( 'STRAIGHT_JOIN' );
		}

		$this->addOption(
			'MAX_EXECUTION_TIME',
			$this->getConfig()->get( MainConfigNames::MaxExecutionTimeForExpensiveQueries )
		);

		$count = 0;
		$res = $this->select( __METHOD__ );

		if ( $this->fld_title ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__, 'log' );
		}
		if ( $this->fld_parsedcomment ) {
			$this->formattedComments = $this->commentFormatter->formatItems(
				$this->commentFormatter->rows( $res )
					->commentKey( 'log_comment' )
					->indexField( 'log_id' )
					->namespaceField( 'log_namespace' )
					->titleField( 'log_title' )
			);
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

	private function extractRowInfo( \stdClass $row ): array {
		$logEntry = DatabaseLogEntry::newFromRow( $row );
		$vals = [
			ApiResult::META_TYPE => 'assoc',
		];
		$anyHidden = false;

		if ( $this->fld_ids ) {
			$vals['logid'] = (int)$row->log_id;
		}

		if ( $this->fld_title ) {
			$title = Title::makeTitle( $row->log_namespace, $row->log_title );
		}

		$authority = $this->getAuthority();
		if ( $this->fld_title || $this->fld_ids || ( $this->fld_details && $row->log_params !== '' ) ) {
			if ( LogEventsList::isDeleted( $row, LogPage::DELETED_ACTION ) ) {
				$vals['actionhidden'] = true;
				$anyHidden = true;
			}
			if ( LogEventsList::userCan( $row, LogPage::DELETED_ACTION, $authority ) ) {
				if ( $this->fld_title ) {
					// @phan-suppress-next-next-line PhanTypeMismatchArgumentNullable,PhanPossiblyUndeclaredVariable
					// title is set when used
					ApiQueryBase::addTitleInfo( $vals, $title );
				}
				if ( $this->fld_ids ) {
					$vals['pageid'] = (int)$row->page_id;
					$vals['logpage'] = (int)$row->log_page;
					$revId = $logEntry->getAssociatedRevId();
					if ( $revId ) {
						$vals['revid'] = (int)$revId;
					}
				}
				if ( $this->fld_details ) {
					$vals['params'] = $this->logFormatterFactory->newFromEntry( $logEntry )->formatParametersForApi();
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
			if ( LogEventsList::userCan( $row, LogPage::DELETED_USER, $authority ) ) {
				if ( $this->fld_user ) {
					$vals['user'] = $row->actor_name;
				}
				if ( $this->fld_userid ) {
					$vals['userid'] = (int)$row->actor_user;
				}

				if ( isset( $vals['user'] ) && $this->userNameUtils->isTemp( $vals['user'] ) ) {
					$vals['temp'] = true;
				}

				if ( !$row->actor_user ) {
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
			if ( LogEventsList::userCan( $row, LogPage::DELETED_COMMENT, $authority ) ) {
				if ( $this->fld_comment ) {
					$vals['comment'] = $this->commentStore->getComment( 'log_comment', $row )->text;
				}

				if ( $this->fld_parsedcomment ) {
					// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
					$vals['parsedcomment'] = $this->formattedComments[$row->log_id];
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
			$config->get( MainConfigNames::LogActions ),
			$config->get( MainConfigNames::LogActionsHandlers )
		) );
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		if ( $this->userCanSeeRevDel() ) {
			return 'private';
		}
		if ( $params['prop'] !== null && in_array( 'parsedcomment', $params['prop'] ) ) {
			// MediaWiki\CommentFormatter\CommentFormatter::formatItems() calls wfMessage() among other things
			return 'anon-public-user-private';
		} elseif ( LogEventsList::getExcludeClause( $this->getDB(), 'user', $this->getAuthority() )
			=== LogEventsList::getExcludeClause( $this->getDB(), 'public' )
		) { // Output can only contain public data.
			return 'public';
		} else {
			return 'anon-public-user-private';
		}
	}

	/** @inheritDoc */
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
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => 'ids|title|type|user|timestamp|comment|details',
				ParamValidator::PARAM_TYPE => [
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
				ParamValidator::PARAM_TYPE => LogPage::validTypes(),
			],
			'action' => [
				// validation on request is done in execute()
				ParamValidator::PARAM_TYPE => $logActions
			],
			'start' => [
				ParamValidator::PARAM_TYPE => 'timestamp'
			],
			'end' => [
				ParamValidator::PARAM_TYPE => 'timestamp'
			],
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'older',
				ParamValidator::PARAM_TYPE => [
					'newer',
					'older'
				],
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'newer' => 'api-help-paramvalue-direction-newer',
					'older' => 'api-help-paramvalue-direction-older',
				],
			],
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
			],
			'title' => null,
			'namespace' => [
				ParamValidator::PARAM_TYPE => 'namespace',
				NamespaceDef::PARAM_EXTRA_NAMESPACES => [ NS_MEDIA, NS_SPECIAL ],
			],
			'prefix' => [],
			'tag' => null,
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];

		if ( $config->get( MainConfigNames::MiserMode ) ) {
			$ret['prefix'][ApiBase::PARAM_HELP_MSG] = 'api-help-param-disabled-in-miser-mode';
		}

		return $ret;
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=logevents'
				=> 'apihelp-query+logevents-example-simple',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Logevents';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryLogEvents::class, 'ApiQueryLogEvents' );
