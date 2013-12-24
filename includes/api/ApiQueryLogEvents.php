<?php
/**
 *
 *
 * Created on Oct 16, 2006
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
 * Query action to List the log events, with optional filtering by various parameters.
 *
 * @ingroup API
 */
class ApiQueryLogEvents extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'le' );
	}

	private $fld_ids = false, $fld_title = false, $fld_type = false,
		$fld_action = false, $fld_user = false, $fld_userid = false,
		$fld_timestamp = false, $fld_comment = false, $fld_parsedcomment = false,
		$fld_details = false, $fld_tags = false;

	public function execute() {
		$params = $this->extractRequestParams();
		$db = $this->getDB();

		$prop = array_flip( $params['prop'] );

		$this->fld_ids = isset( $prop['ids'] );
		$this->fld_title = isset( $prop['title'] );
		$this->fld_type = isset( $prop['type'] );
		$this->fld_action = isset( $prop['action'] );
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

		// Order is significant here
		$this->addTables( array( 'logging', 'user', 'page' ) );
		$this->addJoinConds( array(
			'user' => array( 'LEFT JOIN',
				'user_id=log_user' ),
			'page' => array( 'LEFT JOIN',
				array( 'log_namespace=page_namespace',
					'log_title=page_title' ) ) ) );

		$this->addFields( array(
			'log_id',
			'log_type',
			'log_action',
			'log_timestamp',
			'log_deleted',
		) );

		$this->addFieldsIf( 'page_id', $this->fld_ids );
		$this->addFieldsIf( array( 'log_user', 'log_user_text', 'user_name' ), $this->fld_user );
		$this->addFieldsIf( 'log_user', $this->fld_userid );
		$this->addFieldsIf(
			array( 'log_namespace', 'log_title' ),
			$this->fld_title || $this->fld_parsedcomment
		);
		$this->addFieldsIf( 'log_comment', $this->fld_comment || $this->fld_parsedcomment );
		$this->addFieldsIf( 'log_params', $this->fld_details );

		if ( $this->fld_tags ) {
			$this->addTables( 'tag_summary' );
			$this->addJoinConds( array( 'tag_summary' => array( 'LEFT JOIN', 'log_id=ts_log_id' ) ) );
			$this->addFields( 'ts_tags' );
		}

		if ( !is_null( $params['tag'] ) ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds( array( 'change_tag' => array( 'INNER JOIN',
				array( 'log_id=ct_log_id' ) ) ) );
			$this->addWhereFld( 'ct_tag', $params['tag'] );
		}

		if ( !is_null( $params['action'] ) ) {
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
				$valueName = $this->encodeParamName( 'action' );
				$this->dieUsage(
					"Unrecognized value for parameter '$valueName': {$logAction}",
					"unknown_$valueName"
				);
			}

			$this->addWhereFld( 'log_type', $type );
			$this->addWhereFld( 'log_action', $action );
		} elseif ( !is_null( $params['type'] ) ) {
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

		if ( !is_null( $params['continue'] ) ) {
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
		if ( !is_null( $user ) ) {
			$userid = User::idFromName( $user );
			if ( $userid ) {
				$this->addWhereFld( 'log_user', $userid );
			} else {
				$this->addWhereFld( 'log_user_text', IP::sanitizeIP( $user ) );
			}
		}

		$title = $params['title'];
		if ( !is_null( $title ) ) {
			$titleObj = Title::newFromText( $title );
			if ( is_null( $titleObj ) ) {
				$this->dieUsage( "Bad title value '$title'", 'param_title' );
			}
			$this->addWhereFld( 'log_namespace', $titleObj->getNamespace() );
			$this->addWhereFld( 'log_title', $titleObj->getDBkey() );
		}

		$prefix = $params['prefix'];

		if ( !is_null( $prefix ) ) {
			global $wgMiserMode;
			if ( $wgMiserMode ) {
				$this->dieUsage( 'Prefix search disabled in Miser Mode', 'prefixsearchdisabled' );
			}

			$title = Title::newFromText( $prefix );
			if ( is_null( $title ) ) {
				$this->dieUsage( "Bad title value '$prefix'", 'param_prefix' );
			}
			$this->addWhereFld( 'log_namespace', $title->getNamespace() );
			$this->addWhere( 'log_title ' . $db->buildLike( $title->getDBkey(), $db->anyString() ) );
		}

		// Paranoia: avoid brute force searches (bug 17342)
		if ( !is_null( $title ) || !is_null( $user ) ) {
			if ( !$this->getUser()->isAllowed( 'deletedhistory' ) ) {
				$titleBits = LogPage::DELETED_ACTION;
				$userBits = LogPage::DELETED_USER;
			} elseif ( !$this->getUser()->isAllowed( 'suppressrevision' ) ) {
				$titleBits = LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED;
				$userBits = LogPage::DELETED_USER | LogPage::DELETED_RESTRICTED;
			} else {
				$titleBits = 0;
				$userBits = 0;
			}
			if ( !is_null( $title ) && $titleBits ) {
				$this->addWhere( $db->bitAnd( 'log_deleted', $titleBits ) . " != $titleBits" );
			}
			if ( !is_null( $user ) && $userBits ) {
				$this->addWhere( $db->bitAnd( 'log_deleted', $userBits ) . " != $userBits" );
			}
		}

		$count = 0;
		$res = $this->select( __METHOD__ );
		$result = $this->getResult();
		foreach ( $res as $row ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', "$row->log_timestamp|$row->log_id" );
				break;
			}

			$vals = $this->extractRowInfo( $row );
			if ( !$vals ) {
				continue;
			}
			$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $vals );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', "$row->log_timestamp|$row->log_id" );
				break;
			}
		}
		$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'item' );
	}

	/**
	 * @param $result ApiResult
	 * @param $vals array
	 * @param $params string
	 * @param $type string
	 * @param $action string
	 * @param $ts
	 * @param $legacy bool
	 * @return array
	 */
	public static function addLogParams( $result, &$vals, $params, $type,
		$action, $ts, $legacy = false
	) {
		switch ( $type ) {
			case 'move':
				if ( $legacy ) {
					$targetKey = 0;
					$noredirKey = 1;
				} else {
					$targetKey = '4::target';
					$noredirKey = '5::noredir';
				}

				if ( isset( $params[$targetKey] ) ) {
					$title = Title::newFromText( $params[$targetKey] );
					if ( $title ) {
						$vals2 = array();
						ApiQueryBase::addTitleInfo( $vals2, $title, 'new_' );
						$vals[$type] = $vals2;
					}
				}
				if ( isset( $params[$noredirKey] ) && $params[$noredirKey] ) {
					$vals[$type]['suppressedredirect'] = '';
				}
				$params = null;
				break;
			case 'patrol':
				if ( $legacy ) {
					$cur = 0;
					$prev = 1;
					$auto = 2;
				} else {
					$cur = '4::curid';
					$prev = '5::previd';
					$auto = '6::auto';
				}
				$vals2 = array();
				$vals2['cur'] = $params[$cur];
				$vals2['prev'] = $params[$prev];
				$vals2['auto'] = $params[$auto];
				$vals[$type] = $vals2;
				$params = null;
				break;
			case 'rights':
				$vals2 = array();
				if ( $legacy ) {
					list( $vals2['old'], $vals2['new'] ) = $params;
				} else {
					$vals2['new'] = implode( ', ', $params['5::newgroups'] );
					$vals2['old'] = implode( ', ', $params['4::oldgroups'] );
				}
				$vals[$type] = $vals2;
				$params = null;
				break;
			case 'block':
				if ( $action == 'unblock' ) {
					break;
				}
				$vals2 = array();
				list( $vals2['duration'], $vals2['flags'] ) = $params;

				// Indefinite blocks have no expiry time
				if ( SpecialBlock::parseExpiryInput( $params[0] ) !== wfGetDB( DB_SLAVE )->getInfinity() ) {
					$vals2['expiry'] = wfTimestamp( TS_ISO_8601,
						strtotime( $params[0], wfTimestamp( TS_UNIX, $ts ) ) );
				}
				$vals[$type] = $vals2;
				$params = null;
				break;
			case 'upload':
				if ( isset( $params['img_timestamp'] ) ) {
					$params['img_timestamp'] = wfTimestamp( TS_ISO_8601, $params['img_timestamp'] );
				}
				break;
		}
		if ( !is_null( $params ) ) {
			$logParams = array();
			// Keys like "4::paramname" can't be used for output so we change them to "paramname"
			foreach ( $params as $key => $value ) {
				if ( strpos( $key, ':' ) === false ) {
					$logParams[$key] = $value;
					continue;
				}
				$logParam = explode( ':', $key, 3 );
				$logParams[$logParam[2]] = $value;
			}
			$result->setIndexedTagName( $logParams, 'param' );
			$result->setIndexedTagName_recursive( $logParams, 'param' );
			$vals = array_merge( $vals, $logParams );
		}

		return $vals;
	}

	private function extractRowInfo( $row ) {
		$logEntry = DatabaseLogEntry::newFromRow( $row );
		$vals = array();
		$anyHidden = false;
		$user = $this->getUser();

		if ( $this->fld_ids ) {
			$vals['logid'] = intval( $row->log_id );
		}

		if ( $this->fld_title || $this->fld_parsedcomment ) {
			$title = Title::makeTitle( $row->log_namespace, $row->log_title );
		}

		if ( $this->fld_title || $this->fld_ids || $this->fld_details && $row->log_params !== '' ) {
			if ( LogEventsList::isDeleted( $row, LogPage::DELETED_ACTION ) ) {
				$vals['actionhidden'] = '';
				$anyHidden = true;
			}
			if ( LogEventsList::userCan( $row, LogPage::DELETED_ACTION, $user ) ) {
				if ( $this->fld_title ) {
					ApiQueryBase::addTitleInfo( $vals, $title );
				}
				if ( $this->fld_ids ) {
					$vals['pageid'] = intval( $row->page_id );
				}
				if ( $this->fld_details && $row->log_params !== '' ) {
					self::addLogParams(
						$this->getResult(),
						$vals,
						$logEntry->getParameters(),
						$logEntry->getType(),
						$logEntry->getSubtype(),
						$logEntry->getTimestamp(),
						$logEntry->isLegacy()
					);
				}
			}
		}

		if ( $this->fld_type || $this->fld_action ) {
			$vals['type'] = $row->log_type;
			$vals['action'] = $row->log_action;
		}

		if ( $this->fld_user || $this->fld_userid ) {
			if ( LogEventsList::isDeleted( $row, LogPage::DELETED_USER ) ) {
				$vals['userhidden'] = '';
				$anyHidden = true;
			}
			if ( LogEventsList::userCan( $row, LogPage::DELETED_USER, $user ) ) {
				if ( $this->fld_user ) {
					$vals['user'] = $row->user_name === null ? $row->log_user_text : $row->user_name;
				}
				if ( $this->fld_userid ) {
					$vals['userid'] = $row->log_user;
				}

				if ( !$row->log_user ) {
					$vals['anon'] = '';
				}
			}
		}
		if ( $this->fld_timestamp ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->log_timestamp );
		}

		if ( ( $this->fld_comment || $this->fld_parsedcomment ) && isset( $row->log_comment ) ) {
			if ( LogEventsList::isDeleted( $row, LogPage::DELETED_COMMENT ) ) {
				$vals['commenthidden'] = '';
				$anyHidden = true;
			}
			if ( LogEventsList::userCan( $row, LogPage::DELETED_COMMENT, $user ) ) {
				if ( $this->fld_comment ) {
					$vals['comment'] = $row->log_comment;
				}

				if ( $this->fld_parsedcomment ) {
					$vals['parsedcomment'] = Linker::formatComment( $row->log_comment, $title );
				}
			}
		}

		if ( $this->fld_tags ) {
			if ( $row->ts_tags ) {
				$tags = explode( ',', $row->ts_tags );
				$this->getResult()->setIndexedTagName( $tags, 'tag' );
				$vals['tags'] = $tags;
			} else {
				$vals['tags'] = array();
			}
		}

		if ( $anyHidden && LogEventsList::isDeleted( $row, LogPage::DELETED_RESTRICTED ) ) {
			$vals['suppressed'] = '';
		}

		return $vals;
	}

	private function getAllowedLogActions() {
		global $wgLogActions, $wgLogActionsHandlers;

		return array_keys( array_merge( $wgLogActions, $wgLogActionsHandlers ) );
	}

	public function getCacheMode( $params ) {
		if ( $this->userCanSeeRevDel() ) {
			return 'private';
		}
		if ( !is_null( $params['prop'] ) && in_array( 'parsedcomment', $params['prop'] ) ) {
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
		global $wgLogTypes;

		return array(
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'ids|title|type|user|timestamp|comment|details',
				ApiBase::PARAM_TYPE => array(
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
				)
			),
			'type' => array(
				ApiBase::PARAM_TYPE => $wgLogTypes
			),
			'action' => array(
				// validation on request is done in execute()
				ApiBase::PARAM_TYPE => ( $flags & ApiBase::GET_VALUES_FOR_HELP )
					? $this->getAllowedLogActions()
					: null
			),
			'start' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'end' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'dir' => array(
				ApiBase::PARAM_DFLT => 'older',
				ApiBase::PARAM_TYPE => array(
					'newer',
					'older'
				)
			),
			'user' => null,
			'title' => null,
			'prefix' => null,
			'tag' => null,
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'continue' => null,
		);
	}

	public function getParamDescription() {
		$p = $this->getModulePrefix();

		return array(
			'prop' => array(
				'Which properties to get',
				' ids            - Adds the ID of the log event',
				' title          - Adds the title of the page for the log event',
				' type           - Adds the type of log event',
				' user           - Adds the user responsible for the log event',
				' userid         - Adds the user ID who was responsible for the log event',
				' timestamp      - Adds the timestamp for the event',
				' comment        - Adds the comment of the event',
				' parsedcomment  - Adds the parsed comment of the event',
				' details        - Lists additional details about the event',
				' tags           - Lists tags for the event',
			),
			'type' => 'Filter log entries to only this type',
			'action' => array(
				"Filter log actions to only this action. Overrides {$p}type",
				"Wildcard actions like 'action/*' allows to specify any string for the asterisk"
			),
			'start' => 'The timestamp to start enumerating from',
			'end' => 'The timestamp to end enumerating',
			'dir' => $this->getDirectionDescription( $p ),
			'user' => 'Filter entries to those made by the given user',
			'title' => 'Filter entries to those related to a page',
			'prefix' => 'Filter entries that start with this prefix. Disabled in Miser Mode',
			'limit' => 'How many total event entries to return',
			'tag' => 'Only list event entries tagged with this tag',
			'continue' => 'When more results are available, use this to continue',
		);
	}

	public function getResultProperties() {
		global $wgLogTypes;

		return array(
			'ids' => array(
				'logid' => 'integer',
				'pageid' => 'integer'
			),
			'title' => array(
				'ns' => 'namespace',
				'title' => 'string'
			),
			'type' => array(
				'type' => array(
					ApiBase::PROP_TYPE => $wgLogTypes
				),
				'action' => 'string'
			),
			'details' => array(
				'actionhidden' => 'boolean'
			),
			'user' => array(
				'userhidden' => 'boolean',
				'user' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'anon' => 'boolean'
			),
			'userid' => array(
				'userhidden' => 'boolean',
				'userid' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				),
				'anon' => 'boolean'
			),
			'timestamp' => array(
				'timestamp' => 'timestamp'
			),
			'comment' => array(
				'commenthidden' => 'boolean',
				'comment' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'parsedcomment' => array(
				'commenthidden' => 'boolean',
				'parsedcomment' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			)
		);
	}

	public function getDescription() {
		return 'Get events from logs.';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'param_user', 'info' => 'User name $user not found' ),
			array( 'code' => 'param_title', 'info' => 'Bad title value \'title\'' ),
			array( 'code' => 'param_prefix', 'info' => 'Bad title value \'prefix\'' ),
			array( 'code' => 'prefixsearchdisabled', 'info' => 'Prefix search disabled in Miser Mode' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&list=logevents'
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Logevents';
	}
}
