<?php
/**
 *
 *
 * Created on Oct 16, 2006
 *
 * Copyright © 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiQueryBase.php' );
}

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
		$this->fld_action = isset ( $prop['action'] );
		$this->fld_user = isset( $prop['user'] );
		$this->fld_userid = isset( $prop['userid'] );
		$this->fld_timestamp = isset( $prop['timestamp'] );
		$this->fld_comment = isset( $prop['comment'] );
		$this->fld_parsedcomment = isset ( $prop['parsedcomment'] );
		$this->fld_details = isset( $prop['details'] );
		$this->fld_tags = isset( $prop['tags'] );

		$hideLogs = LogEventsList::getExcludeClause( $db );
		if ( $hideLogs !== false ) {
			$this->addWhere( $hideLogs );
		}

		// Order is significant here
		$this->addTables( array( 'logging', 'user', 'page' ) );
		$this->addOption( 'STRAIGHT_JOIN' );
		$this->addJoinConds( array(
			'user' => array( 'JOIN',
				'user_id=log_user' ),
			'page' => array( 'LEFT JOIN',
				array(	'log_namespace=page_namespace',
					'log_title=page_title' ) ) ) );
		$index = array( 'logging' => 'times' ); // default, may change

		$this->addFields( array(
			'log_type',
			'log_action',
			'log_timestamp',
			'log_deleted',
		) );

		$this->addFieldsIf( 'log_id', $this->fld_ids );
		$this->addFieldsIf( 'page_id', $this->fld_ids );
		$this->addFieldsIf( 'log_user', $this->fld_user );
		$this->addFieldsIf( 'user_name', $this->fld_user );
		$this->addFieldsIf( 'user_id', $this->fld_userid );
		$this->addFieldsIf( 'log_namespace', $this->fld_title || $this->fld_parsedcomment );
		$this->addFieldsIf( 'log_title', $this->fld_title || $this->fld_parsedcomment );
		$this->addFieldsIf( 'log_comment', $this->fld_comment || $this->fld_parsedcomment );
		$this->addFieldsIf( 'log_params', $this->fld_details );

		if ( $this->fld_tags ) {
			$this->addTables( 'tag_summary' );
			$this->addJoinConds( array( 'tag_summary' => array( 'LEFT JOIN', 'log_id=ts_log_id' ) ) );
			$this->addFields( 'ts_tags' );
		}

		if ( !is_null( $params['tag'] ) ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds( array( 'change_tag' => array( 'INNER JOIN', array( 'log_id=ct_log_id' ) ) ) );
			$this->addWhereFld( 'ct_tag', $params['tag'] );
			global $wgOldChangeTagsIndex;
			$index['change_tag'] = $wgOldChangeTagsIndex ? 'ct_tag' : 'change_tag_tag_id';
		}

		if ( !is_null( $params['action'] ) ) {
			list( $type, $action ) = explode( '/', $params['action'] );
			$this->addWhereFld( 'log_type', $type );
			$this->addWhereFld( 'log_action', $action );
		}
		else if ( !is_null( $params['type'] ) ) {
			$this->addWhereFld( 'log_type', $params['type'] );
			$index['logging'] = 'type_time';
		}

		$this->addWhereRange( 'log_timestamp', $params['dir'], $params['start'], $params['end'] );

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );

		$user = $params['user'];
		if ( !is_null( $user ) ) {
			$userid = User::idFromName( $user );
			if ( !$userid ) {
				$this->dieUsage( "User name $user not found", 'param_user' );
			}
			$this->addWhereFld( 'log_user', $userid );
			$index['logging'] = 'user_time';
		}

		$title = $params['title'];
		if ( !is_null( $title ) ) {
			$titleObj = Title::newFromText( $title );
			if ( is_null( $titleObj ) ) {
				$this->dieUsage( "Bad title value '$title'", 'param_title' );
			}
			$this->addWhereFld( 'log_namespace', $titleObj->getNamespace() );
			$this->addWhereFld( 'log_title', $titleObj->getDBkey() );

			// Use the title index in preference to the user index if there is a conflict
			$index['logging'] = is_null( $user ) ? 'page_time' : array( 'page_time', 'user_time' );
		}

		$this->addOption( 'USE INDEX', $index );

		// Paranoia: avoid brute force searches (bug 17342)
		if ( !is_null( $title ) ) {
			$this->addWhere( $db->bitAnd( 'log_deleted', LogPage::DELETED_ACTION ) . ' = 0' );
		}
		if ( !is_null( $user ) ) {
			$this->addWhere( $db->bitAnd( 'log_deleted', LogPage::DELETED_USER ) . ' = 0' );
		}

		$count = 0;
		$res = $this->select( __METHOD__ );
		foreach ( $res as $row ) {
			if ( ++ $count > $limit ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->log_timestamp ) );
				break;
			}

			$vals = $this->extractRowInfo( $row );
			if ( !$vals ) {
				continue;
			}
			$fit = $this->getResult()->addValue( array( 'query', $this->getModuleName() ), null, $vals );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->log_timestamp ) );
				break;
			}
		}
		$this->getResult()->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'item' );
	}

	/**
	 * @static
	 * @param $result ApiResult
	 * @param $vals
	 * @param $params
	 * @param $type
	 * @param $ts
	 * @return array
	 */
	public static function addLogParams( $result, &$vals, $params, $type, $ts ) {
		$params = explode( "\n", $params );
		switch ( $type ) {
			case 'move':
				if ( isset( $params[0] ) ) {
					$title = Title::newFromText( $params[0] );
					if ( $title ) {
						$vals2 = array();
						ApiQueryBase::addTitleInfo( $vals2, $title, 'new_' );
						$vals[$type] = $vals2;
					}
				}
				if ( isset( $params[1] ) && $params[1] ) {
					$vals[$type]['suppressedredirect'] = '';
				}
				$params = null;
				break;
			case 'patrol':
				$vals2 = array();
				list( $vals2['cur'], $vals2['prev'], $vals2['auto'] ) = $params;
				$vals[$type] = $vals2;
				$params = null;
				break;
			case 'rights':
				$vals2 = array();
				list( $vals2['old'], $vals2['new'] ) = $params;
				$vals[$type] = $vals2;
				$params = null;
				break;
			case 'block':
				$vals2 = array();
				list( $vals2['duration'], $vals2['flags'] ) = $params;

				// Indefinite blocks have no expiry time
				if ( Block::parseExpiryInput( $params[0] ) !== Block::infinity() ) {
					$vals2['expiry'] = wfTimestamp( TS_ISO_8601,
						strtotime( $params[0], wfTimestamp( TS_UNIX, $ts ) ) );
				}
				$vals[$type] = $vals2;
				$params = null;
				break;
		}
		if ( !is_null( $params ) ) {
			$result->setIndexedTagName( $params, 'param' );
			$vals = array_merge( $vals, $params );
		}
		return $vals;
	}

	private function extractRowInfo( $row ) {
		$vals = array();

		if ( $this->fld_ids ) {
			$vals['logid'] = intval( $row->log_id );
			$vals['pageid'] = intval( $row->page_id );
		}

		if ( $this->fld_title || $this->fld_parsedcomment ) {
			$title = Title::makeTitle( $row->log_namespace, $row->log_title );
		}

		if ( $this->fld_title ) {
			if ( LogEventsList::isDeleted( $row, LogPage::DELETED_ACTION ) ) {
				$vals['actionhidden'] = '';
			} else {
				ApiQueryBase::addTitleInfo( $vals, $title );
			}
		}

		if ( $this->fld_type || $this->fld_action ) {
			$vals['type'] = $row->log_type;
			$vals['action'] = $row->log_action;
		}

		if ( $this->fld_details && $row->log_params !== '' ) {
			if ( LogEventsList::isDeleted( $row, LogPage::DELETED_ACTION ) ) {
				$vals['actionhidden'] = '';
			} else {
				self::addLogParams(
					$this->getResult(), $vals,
					$row->log_params, $row->log_type,
					$row->log_timestamp
				);
			}
		}

		if ( $this->fld_user || $this->fld_userid ) {
			if ( LogEventsList::isDeleted( $row, LogPage::DELETED_USER ) ) {
				$vals['userhidden'] = '';
			} else {
				if ( $this->fld_user ) {
					$vals['user'] = $row->user_name;
				}
				if ( $this->fld_userid ) {
					$vals['userid'] = $row->user_id;
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
			} else {
				if ( $this->fld_comment ) {
					$vals['comment'] = $row->log_comment;
				}

				if ( $this->fld_parsedcomment ) {
					global $wgUser;
					$vals['parsedcomment'] = $wgUser->getSkin()->formatComment( $row->log_comment, $title );
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

		return $vals;
	}

	public function getCacheMode( $params ) {
		if ( !is_null( $params['prop'] ) && in_array( 'parsedcomment', $params['prop'] ) ) {
			// formatComment() calls wfMsg() among other things
			return 'anon-public-user-private';
		} else {
			return 'public';
		}
	}

	public function getAllowedParams() {
		global $wgLogTypes, $wgLogActions;
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
				ApiBase::PARAM_TYPE => array_keys( $wgLogActions )
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
			'tag' => null,
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			)
		);
	}

	public function getParamDescription() {
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
				' details        - Lists addtional details about the event',
				' tags           - Lists tags for the event',
			),
			'type' => 'Filter log entries to only this type(s)',
			'action' => "Filter log actions to only this type. Overrides {$this->getModulePrefix()}type",
			'start' => 'The timestamp to start enumerating from',
			'end' => 'The timestamp to end enumerating',
			'dir' => 'In which direction to enumerate',
			'user' => 'Filter entries to those made by the given user',
			'title' => 'Filter entries to those related to a page',
			'limit' => 'How many total event entries to return',
			'tag' => 'Only list event entries tagged with this tag',
		);
	}

	public function getDescription() {
		return 'Get events from logs';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'param_user', 'info' => 'User name $user not found' ),
			array( 'code' => 'param_title', 'info' => 'Bad title value \'title\'' ),
		) );
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&list=logevents'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
