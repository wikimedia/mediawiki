<?php
/**
 *
 *
 * Created on Sep 25, 2006
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
 * This query action allows clients to retrieve a list of recently modified pages
 * that are part of the logged-in user's watchlist.
 *
 * @ingroup API
 */
class ApiQueryWatchlist extends ApiQueryGeneratorBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'wl' );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	private $fld_ids = false, $fld_title = false, $fld_patrol = false, $fld_flags = false,
			$fld_timestamp = false, $fld_user = false, $fld_comment = false, $fld_parsedcomment = false, $fld_sizes = false,
			$fld_notificationtimestamp = false, $fld_userid = false, $fld_loginfo = false;

	/**
	 * @param $resultPageSet ApiPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$this->selectNamedDB( 'watchlist', DB_SLAVE, 'watchlist' );

		$params = $this->extractRequestParams();

		$user = $this->getWatchlistUser( $params );

		if ( !is_null( $params['prop'] ) && is_null( $resultPageSet ) ) {
			$prop = array_flip( $params['prop'] );

			$this->fld_ids = isset( $prop['ids'] );
			$this->fld_title = isset( $prop['title'] );
			$this->fld_flags = isset( $prop['flags'] );
			$this->fld_user = isset( $prop['user'] );
			$this->fld_userid = isset( $prop['userid'] );
			$this->fld_comment = isset( $prop['comment'] );
			$this->fld_parsedcomment = isset ( $prop['parsedcomment'] );
			$this->fld_timestamp = isset( $prop['timestamp'] );
			$this->fld_sizes = isset( $prop['sizes'] );
			$this->fld_patrol = isset( $prop['patrol'] );
			$this->fld_notificationtimestamp = isset( $prop['notificationtimestamp'] );
			$this->fld_loginfo = isset( $prop['loginfo'] );

			if ( $this->fld_patrol ) {
				if ( !$user->useRCPatrol() && !$user->useNPPatrol() ) {
					$this->dieUsage( 'patrol property is not available', 'patrol' );
				}
			}
		}

		$this->addFields( array(
			'rc_namespace',
			'rc_title',
			'rc_timestamp',
			'rc_type',
		) );

		if ( is_null( $resultPageSet ) ) {
			$this->addFields( array(
				'rc_cur_id',
				'rc_this_oldid',
				'rc_last_oldid',
			) );

			$this->addFieldsIf( array( 'rc_type', 'rc_minor', 'rc_bot' ), $this->fld_flags );
			$this->addFieldsIf( 'rc_user', $this->fld_user || $this->fld_userid );
			$this->addFieldsIf( 'rc_user_text', $this->fld_user );
			$this->addFieldsIf( 'rc_comment', $this->fld_comment || $this->fld_parsedcomment );
			$this->addFieldsIf( 'rc_patrolled', $this->fld_patrol );
			$this->addFieldsIf( array( 'rc_old_len', 'rc_new_len' ), $this->fld_sizes );
			$this->addFieldsIf( 'wl_notificationtimestamp', $this->fld_notificationtimestamp );
			$this->addFieldsIf( array( 'rc_logid', 'rc_log_type', 'rc_log_action', 'rc_params' ), $this->fld_loginfo );
		} elseif ( $params['allrev'] ) {
			$this->addFields( 'rc_this_oldid' );
		} else {
			$this->addFields( 'rc_cur_id' );
		}

		$this->addTables( array(
			'recentchanges',
			'watchlist',
		) );

		$userId = $user->getId();
		$this->addJoinConds( array( 'watchlist' => array('INNER JOIN',
			array(
				'wl_user' => $userId,
				'wl_namespace=rc_namespace',
				'wl_title=rc_title'
		) ) ) );

		$this->addWhere( array(
			'rc_deleted' => 0,
		) );

		$db = $this->getDB();

		$this->addTimestampWhereRange( 'rc_timestamp', $params['dir'],
			$params['start'], $params['end'] );
		$this->addWhereFld( 'wl_namespace', $params['namespace'] );

		if ( !$params['allrev'] ) {
			$this->addTables( 'page' );
			$this->addJoinConds( array( 'page' => array( 'LEFT JOIN','rc_cur_id=page_id' ) ) );
			$this->addWhere( 'rc_this_oldid=page_latest OR rc_type=' . RC_LOG );
		}

		if ( !is_null( $params['show'] ) ) {
			$show = array_flip( $params['show'] );

			/* Check for conflicting parameters. */
			if ( ( isset ( $show['minor'] ) && isset ( $show['!minor'] ) )
					|| ( isset ( $show['bot'] ) && isset ( $show['!bot'] ) )
					|| ( isset ( $show['anon'] ) && isset ( $show['!anon'] ) )
					|| ( isset ( $show['patrolled'] ) && isset ( $show['!patrolled'] ) )
			)
			{
				$this->dieUsageMsg( 'show' );
			}

			// Check permissions.
			if ( isset( $show['patrolled'] ) || isset( $show['!patrolled'] ) ) {
				$user = $this->getUser();
				if ( !$user->useRCPatrol() && !$user->useNPPatrol() ) {
					$this->dieUsage( 'You need the patrol right to request the patrolled flag', 'permissiondenied' );
				}
			}

			/* Add additional conditions to query depending upon parameters. */
			$this->addWhereIf( 'rc_minor = 0', isset( $show['!minor'] ) );
			$this->addWhereIf( 'rc_minor != 0', isset( $show['minor'] ) );
			$this->addWhereIf( 'rc_bot = 0', isset( $show['!bot'] ) );
			$this->addWhereIf( 'rc_bot != 0', isset( $show['bot'] ) );
			$this->addWhereIf( 'rc_user = 0', isset( $show['anon'] ) );
			$this->addWhereIf( 'rc_user != 0', isset( $show['!anon'] ) );
			$this->addWhereIf( 'rc_patrolled = 0', isset( $show['!patrolled'] ) );
			$this->addWhereIf( 'rc_patrolled != 0', isset( $show['patrolled'] ) );
		}

		if ( !is_null( $params['user'] ) && !is_null( $params['excludeuser'] ) ) {
			$this->dieUsage( 'user and excludeuser cannot be used together', 'user-excludeuser' );
		}
		if ( !is_null( $params['user'] ) ) {
			$this->addWhereFld( 'rc_user_text', $params['user'] );
		}
		if ( !is_null( $params['excludeuser'] ) ) {
			$this->addWhere( 'rc_user_text != ' . $db->addQuotes( $params['excludeuser'] ) );
		}

		// This is an index optimization for mysql, as done in the Special:Watchlist page
		$this->addWhereIf( "rc_timestamp > ''", !isset( $params['start'] ) && !isset( $params['end'] ) && $db->getType() == 'mysql' );

		$this->addOption( 'LIMIT', $params['limit'] + 1 );

		$ids = array();
		$count = 0;
		$res = $this->select( __METHOD__ );

		foreach ( $res as $row ) {
			if ( ++ $count > $params['limit'] ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->rc_timestamp ) );
				break;
			}

			if ( is_null( $resultPageSet ) ) {
				$vals = $this->extractRowInfo( $row );
				$fit = $this->getResult()->addValue( array( 'query', $this->getModuleName() ), null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'start',
							wfTimestamp( TS_ISO_8601, $row->rc_timestamp ) );
					break;
				}
			} else {
				if ( $params['allrev'] ) {
					$ids[] = intval( $row->rc_this_oldid );
				} else {
					$ids[] = intval( $row->rc_cur_id );
				}
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$this->getResult()->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'item' );
		} elseif ( $params['allrev'] ) {
			$resultPageSet->populateFromRevisionIDs( $ids );
		} else {
			$resultPageSet->populateFromPageIDs( $ids );
		}
	}

	private function extractRowInfo( $row ) {
		$vals = array();

		if ( $this->fld_ids ) {
			$vals['pageid'] = intval( $row->rc_cur_id );
			$vals['revid'] = intval( $row->rc_this_oldid );
			$vals['old_revid'] = intval( $row->rc_last_oldid );
		}

		$title = Title::makeTitle( $row->rc_namespace, $row->rc_title );

		if ( $this->fld_title ) {
			ApiQueryBase::addTitleInfo( $vals, $title );
		}

		if ( $this->fld_user || $this->fld_userid ) {

			if ( $this->fld_user ) {
				$vals['user'] = $row->rc_user_text;
			}

			if ( $this->fld_userid ) {
				$vals['user'] = $row->rc_user;
			}

			if ( !$row->rc_user ) {
				$vals['anon'] = '';
			}
		}

		if ( $this->fld_flags ) {
			if ( $row->rc_type == RC_NEW ) {
				$vals['new'] = '';
			}
			if ( $row->rc_minor ) {
				$vals['minor'] = '';
			}
			if ( $row->rc_bot ) {
				$vals['bot'] = '';
			}
		}

		if ( $this->fld_patrol && isset( $row->rc_patrolled ) ) {
			$vals['patrolled'] = '';
		}

		if ( $this->fld_timestamp ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->rc_timestamp );
		}

		if ( $this->fld_sizes ) {
			$vals['oldlen'] = intval( $row->rc_old_len );
			$vals['newlen'] = intval( $row->rc_new_len );
		}

		if ( $this->fld_notificationtimestamp ) {
			$vals['notificationtimestamp'] = ( $row->wl_notificationtimestamp == null )
				? ''
				: wfTimestamp( TS_ISO_8601, $row->wl_notificationtimestamp );
		}

		if ( $this->fld_comment && isset( $row->rc_comment ) ) {
			$vals['comment'] = $row->rc_comment;
		}

		if ( $this->fld_parsedcomment && isset( $row->rc_comment ) ) {
			$vals['parsedcomment'] = Linker::formatComment( $row->rc_comment, $title );
		}

		if ( $this->fld_loginfo && $row->rc_type == RC_LOG ) {
			$vals['logid'] = intval( $row->rc_logid );
			$vals['logtype'] = $row->rc_log_type;
			$vals['logaction'] = $row->rc_log_action;
			$logEntry = DatabaseLogEntry::newFromRow( (array)$row );
			ApiQueryLogEvents::addLogParams(
				$this->getResult(),
				$vals,
				$logEntry->getParameters(),
				$logEntry->getType(),
				$logEntry->getSubtype(),
				$logEntry->getTimestamp()
			);
		}

		return $vals;
	}

	public function getAllowedParams() {
		return array(
			'allrev' => false,
			'start' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'end' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'namespace' => array (
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace'
			),
			'user' => array(
				ApiBase::PARAM_TYPE => 'user',
			),
			'excludeuser' => array(
				ApiBase::PARAM_TYPE => 'user',
			),
			'dir' => array(
				ApiBase::PARAM_DFLT => 'older',
				ApiBase::PARAM_TYPE => array(
					'newer',
					'older'
				)
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'ids|title|flags',
				ApiBase::PARAM_TYPE => array(
					'ids',
					'title',
					'flags',
					'user',
					'userid',
					'comment',
					'parsedcomment',
					'timestamp',
					'patrol',
					'sizes',
					'notificationtimestamp',
					'loginfo',
				)
			),
			'show' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array(
					'minor',
					'!minor',
					'bot',
					'!bot',
					'anon',
					'!anon',
					'patrolled',
					'!patrolled',
				)
			),
			'owner' => array(
				ApiBase::PARAM_TYPE => 'user'
			),
			'token' => array(
				ApiBase::PARAM_TYPE => 'string'
			)
		);
	}

	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'allrev' => 'Include multiple revisions of the same page within given timeframe',
			'start' => 'The timestamp to start enumerating from',
			'end' => 'The timestamp to end enumerating',
			'namespace' => 'Filter changes to only the given namespace(s)',
			'user' => 'Only list changes by this user',
			'excludeuser' => 'Don\'t list changes by this user',
			'dir' => $this->getDirectionDescription( $p ),
			'limit' => 'How many total results to return per request',
			'prop' => array(
				'Which additional items to get (non-generator mode only).',
				' ids                    - Adds revision ids and page ids',
				' title                  - Adds title of the page',
				' flags                  - Adds flags for the edit',
				' user                   - Adds the user who made the edit',
				' userid                 - Adds user id of whom made the edit',
				' comment                - Adds comment of the edit',
				' parsedcomment          - Adds parsed comment of the edit',
				' timestamp              - Adds timestamp of the edit',
				' patrol                 - Tags edits that are patrolled',
				' sizes                  - Adds the old and new lengths of the page',
				' notificationtimestamp  - Adds timestamp of when the user was last notified about the edit',
				' loginfo                - Adds log information where appropriate',
			),
			'show' => array(
				'Show only items that meet this criteria.',
				"For example, to see only minor edits done by logged-in users, set {$p}show=minor|!anon"
			),
			'owner' => 'The name of the user whose watchlist you\'d like to access',
			'token' => 'Give a security token (settable in preferences) to allow access to another user\'s watchlist'
		);
	}

	public function getResultProperties() {
		global $wgLogTypes;
		return array(
			'ids' => array(
				'pageid' => 'integer',
				'revid' => 'integer',
				'old_revid' => 'integer'
			),
			'title' => array(
				'ns' => 'namespace',
				'title' => 'string'
			),
			'user' => array(
				'user' => 'string',
				'anon' => 'boolean'
			),
			'userid' => array(
				'userid' => 'integer',
				'anon' => 'boolean'
			),
			'flags' => array(
				'new' => 'boolean',
				'minor' => 'boolean',
				'bot' => 'boolean'
			),
			'patrol' => array(
				'patrolled' => 'boolean'
			),
			'timestamp' => array(
				'timestamp' => 'timestamp'
			),
			'sizes' => array(
				'oldlen' => 'integer',
				'newlen' => 'integer'
			),
			'notificationtimestamp' => array(
				'notificationtimestamp' => array(
					ApiBase::PROP_TYPE => 'timestamp',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'comment' => array(
				'comment' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'parsedcomment' => array(
				'parsedcomment' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'loginfo' => array(
				'logid' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				),
				'logtype' => array(
					ApiBase::PROP_TYPE => $wgLogTypes,
					ApiBase::PROP_NULLABLE => true
				),
				'logaction' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			)
		);
	}

	public function getDescription() {
		return "Get all recent changes to pages in the logged in user's watchlist";
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'bad_wlowner', 'info' => 'Specified user does not exist' ),
			array( 'code' => 'bad_wltoken', 'info' => 'Incorrect watchlist token provided -- please set a correct token in Special:Preferences' ),
			array( 'code' => 'notloggedin', 'info' => 'You must be logged-in to have a watchlist' ),
			array( 'code' => 'patrol', 'info' => 'patrol property is not available' ),
			array( 'show' ),
			array( 'code' => 'permissiondenied', 'info' => 'You need the patrol right to request the patrolled flag' ),
			array( 'code' => 'user-excludeuser', 'info' => 'user and excludeuser cannot be used together' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&list=watchlist',
			'api.php?action=query&list=watchlist&wlprop=ids|title|timestamp|user|comment',
			'api.php?action=query&list=watchlist&wlallrev=&wlprop=ids|title|timestamp|user|comment',
			'api.php?action=query&generator=watchlist&prop=info',
			'api.php?action=query&generator=watchlist&gwlallrev=&prop=revisions&rvprop=timestamp|user',
			'api.php?action=query&list=watchlist&wlowner=Bob_Smith&wltoken=d8d562e9725ea1512894cdab28e5ceebc7f20237'
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Watchlist';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
