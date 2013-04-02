<?php
/**
 *
 *
 * Created on Oct 19, 2006
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
 * A query action to enumerate the recent changes that were done to the wiki.
 * Various filters are supported.
 *
 * @ingroup API
 */
class ApiQueryRecentChanges extends ApiQueryGeneratorBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'rc' );
	}

	private $fld_comment = false, $fld_parsedcomment = false, $fld_user = false, $fld_userid = false,
			$fld_flags = false, $fld_timestamp = false, $fld_title = false, $fld_ids = false,
			$fld_sizes = false, $fld_redirect = false, $fld_patrolled = false, $fld_loginfo = false,
			$fld_tags = false, $token = array();

	private $tokenFunctions;

	/**
	 * Get an array mapping token names to their handler functions.
	 * The prototype for a token function is func($pageid, $title, $rc)
	 * it should return a token or false (permission denied)
	 * @return array array(tokenname => function)
	 */
	protected function getTokenFunctions() {
		// Don't call the hooks twice
		if ( isset( $this->tokenFunctions ) ) {
			return $this->tokenFunctions;
		}

		// If we're in JSON callback mode, no tokens can be obtained
		if ( !is_null( $this->getMain()->getRequest()->getVal( 'callback' ) ) ) {
			return array();
		}

		$this->tokenFunctions = array(
			'patrol' => array( 'ApiQueryRecentChanges', 'getPatrolToken' )
		);
		wfRunHooks( 'APIQueryRecentChangesTokens', array( &$this->tokenFunctions ) );
		return $this->tokenFunctions;
	}

	/**
	 * @param  $pageid
	 * @param  $title
	 * @param $rc RecentChange (optional)
	 * @return bool|String
	 */
	public static function getPatrolToken( $pageid, $title, $rc = null ) {
		global $wgUser;

		$validTokenUser = false;

		if ( $rc ) {
			if ( ( $wgUser->useRCPatrol() && $rc->getAttribute( 'rc_type' ) == RC_EDIT ) ||
				( $wgUser->useNPPatrol() && $rc->getAttribute( 'rc_type' ) == RC_NEW ) )
			{
				$validTokenUser = true;
			}
		} else {
			if ( $wgUser->useRCPatrol() || $wgUser->useNPPatrol() ) {
				$validTokenUser = true;
			}
		}

		if ( $validTokenUser ) {
			// The patrol token is always the same, let's exploit that
			static $cachedPatrolToken = null;
			if ( is_null( $cachedPatrolToken ) ) {
				$cachedPatrolToken = $wgUser->getEditToken( 'patrol' );
			}
			return $cachedPatrolToken;
		} else {
			return false;
		}

	}

	/**
	 * Sets internal state to include the desired properties in the output.
	 * @param array $prop associative array of properties, only keys are used here
	 */
	public function initProperties( $prop ) {
		$this->fld_comment = isset( $prop['comment'] );
		$this->fld_parsedcomment = isset( $prop['parsedcomment'] );
		$this->fld_user = isset( $prop['user'] );
		$this->fld_userid = isset( $prop['userid'] );
		$this->fld_flags = isset( $prop['flags'] );
		$this->fld_timestamp = isset( $prop['timestamp'] );
		$this->fld_title = isset( $prop['title'] );
		$this->fld_ids = isset( $prop['ids'] );
		$this->fld_sizes = isset( $prop['sizes'] );
		$this->fld_redirect = isset( $prop['redirect'] );
		$this->fld_patrolled = isset( $prop['patrolled'] );
		$this->fld_loginfo = isset( $prop['loginfo'] );
		$this->fld_tags = isset( $prop['tags'] );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * Generates and outputs the result of this query based upon the provided parameters.
	 *
	 * @param $resultPageSet ApiPageSet
	 */
	public function run( $resultPageSet = null ) {
		$user = $this->getUser();
		/* Get the parameters of the request. */
		$params = $this->extractRequestParams();

		/* Build our basic query. Namely, something along the lines of:
		 * SELECT * FROM recentchanges WHERE rc_timestamp > $start
		 * 		AND rc_timestamp < $end AND rc_namespace = $namespace
		 * 		AND rc_deleted = 0
		 */
		$this->addTables( 'recentchanges' );
		$index = array( 'recentchanges' => 'rc_timestamp' ); // May change
		$this->addTimestampWhereRange( 'rc_timestamp', $params['dir'], $params['start'], $params['end'] );

		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			if ( count( $cont ) != 2 ) {
				$this->dieUsage( 'Invalid continue param. You should pass the ' .
								'original value returned by the previous query', '_badcontinue' );
			}

			$timestamp = $this->getDB()->addQuotes( wfTimestamp( TS_MW, $cont[0] ) );
			$id = intval( $cont[1] );
			$op = $params['dir'] === 'older' ? '<' : '>';

			$this->addWhere(
				"rc_timestamp $op $timestamp OR " .
				"(rc_timestamp = $timestamp AND " .
				"rc_id $op= $id)"
			);
		}

		$order = $params['dir'] === 'older' ? 'DESC' : 'ASC';
		$this->addOption( 'ORDER BY', array(
			"rc_timestamp $order",
			"rc_id $order",
		) );

		$this->addWhereFld( 'rc_namespace', $params['namespace'] );
		$this->addWhereFld( 'rc_deleted', 0 );

		if ( !is_null( $params['type'] ) ) {
			$this->addWhereFld( 'rc_type', $this->parseRCType( $params['type'] ) );
		}

		if ( !is_null( $params['show'] ) ) {
			$show = array_flip( $params['show'] );

			/* Check for conflicting parameters. */
			if ( ( isset( $show['minor'] ) && isset( $show['!minor'] ) )
					|| ( isset( $show['bot'] ) && isset( $show['!bot'] ) )
					|| ( isset( $show['anon'] ) && isset( $show['!anon'] ) )
					|| ( isset( $show['redirect'] ) && isset( $show['!redirect'] ) )
					|| ( isset( $show['patrolled'] ) && isset( $show['!patrolled'] ) )
			) {
				$this->dieUsageMsg( 'show' );
			}

			// Check permissions
			if ( isset( $show['patrolled'] ) || isset( $show['!patrolled'] ) ) {
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
			$this->addWhereIf( 'page_is_redirect = 1', isset( $show['redirect'] ) );

			// Don't throw log entries out the window here
			$this->addWhereIf( 'page_is_redirect = 0 OR page_is_redirect IS NULL', isset( $show['!redirect'] ) );
		}

		if ( !is_null( $params['user'] ) && !is_null( $params['excludeuser'] ) ) {
			$this->dieUsage( 'user and excludeuser cannot be used together', 'user-excludeuser' );
		}

		if ( !is_null( $params['user'] ) ) {
			$this->addWhereFld( 'rc_user_text', $params['user'] );
			$index['recentchanges'] = 'rc_user_text';
		}

		if ( !is_null( $params['excludeuser'] ) ) {
			// We don't use the rc_user_text index here because
			// * it would require us to sort by rc_user_text before rc_timestamp
			// * the != condition doesn't throw out too many rows anyway
			$this->addWhere( 'rc_user_text != ' . $this->getDB()->addQuotes( $params['excludeuser'] ) );
		}

		/* Add the fields we're concerned with to our query. */
		$this->addFields( array(
			'rc_timestamp',
			'rc_namespace',
			'rc_title',
			'rc_cur_id',
			'rc_type',
			'rc_deleted'
		) );

		$showRedirects = false;
		/* Determine what properties we need to display. */
		if ( !is_null( $params['prop'] ) ) {
			$prop = array_flip( $params['prop'] );

			/* Set up internal members based upon params. */
			$this->initProperties( $prop );

			if ( $this->fld_patrolled && !$user->useRCPatrol() && !$user->useNPPatrol() ) {
				$this->dieUsage( 'You need the patrol right to request the patrolled flag', 'permissiondenied' );
			}

			$this->addFields( 'rc_id' );
			/* Add fields to our query if they are specified as a needed parameter. */
			$this->addFieldsIf( array( 'rc_this_oldid', 'rc_last_oldid' ), $this->fld_ids );
			$this->addFieldsIf( 'rc_comment', $this->fld_comment || $this->fld_parsedcomment );
			$this->addFieldsIf( 'rc_user', $this->fld_user );
			$this->addFieldsIf( 'rc_user_text', $this->fld_user || $this->fld_userid );
			$this->addFieldsIf( array( 'rc_minor', 'rc_type', 'rc_bot' ), $this->fld_flags );
			$this->addFieldsIf( array( 'rc_old_len', 'rc_new_len' ), $this->fld_sizes );
			$this->addFieldsIf( 'rc_patrolled', $this->fld_patrolled );
			$this->addFieldsIf( array( 'rc_logid', 'rc_log_type', 'rc_log_action', 'rc_params' ), $this->fld_loginfo );
			$showRedirects = $this->fld_redirect || isset( $show['redirect'] ) || isset( $show['!redirect'] );
		}

		if ( $this->fld_tags ) {
			$this->addTables( 'tag_summary' );
			$this->addJoinConds( array( 'tag_summary' => array( 'LEFT JOIN', array( 'rc_id=ts_rc_id' ) ) ) );
			$this->addFields( 'ts_tags' );
		}

		if ( $params['toponly'] || $showRedirects ) {
			$this->addTables( 'page' );
			$this->addJoinConds( array( 'page' => array( 'LEFT JOIN', array( 'rc_namespace=page_namespace', 'rc_title=page_title' ) ) ) );
			$this->addFields( 'page_is_redirect' );

			if ( $params['toponly'] ) {
				$this->addWhere( 'rc_this_oldid = page_latest' );
			}
		}

		if ( !is_null( $params['tag'] ) ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds( array( 'change_tag' => array( 'INNER JOIN', array( 'rc_id=ct_rc_id' ) ) ) );
			$this->addWhereFld( 'ct_tag', $params['tag'] );
			global $wgOldChangeTagsIndex;
			$index['change_tag'] = $wgOldChangeTagsIndex ? 'ct_tag' : 'change_tag_tag_id';
		}

		$this->token = $params['token'];
		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$this->addOption( 'USE INDEX', $index );

		$count = 0;
		/* Perform the actual query. */
		$res = $this->select( __METHOD__ );

		$titles = array();

		$result = $this->getResult();

		/* Iterate through the rows, adding data extracted from them to our query result. */
		foreach ( $res as $row ) {
			if ( ++ $count > $params['limit'] ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', wfTimestamp( TS_ISO_8601, $row->rc_timestamp ) . '|' . $row->rc_id );
				break;
			}

			if ( is_null( $resultPageSet ) ) {
				/* Extract the data from a single row. */
				$vals = $this->extractRowInfo( $row );

				/* Add that row's data to our final output. */
				if ( !$vals ) {
					continue;
				}
				$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', wfTimestamp( TS_ISO_8601, $row->rc_timestamp ) . '|' . $row->rc_id );
					break;
				}
			} else {
				$titles[] = Title::makeTitle( $row->rc_namespace, $row->rc_title );
			}
		}

		if ( is_null( $resultPageSet ) ) {
			/* Format the result */
			$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'rc' );
		} else {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	/**
	 * Extracts from a single sql row the data needed to describe one recent change.
	 *
	 * @param mixed $row The row from which to extract the data.
	 * @return array An array mapping strings (descriptors) to their respective string values.
	 * @access public
	 */
	public function extractRowInfo( $row ) {
		/* Determine the title of the page that has been changed. */
		$title = Title::makeTitle( $row->rc_namespace, $row->rc_title );

		/* Our output data. */
		$vals = array();

		$type = intval( $row->rc_type );

		/* Determine what kind of change this was. */
		switch ( $type ) {
			case RC_EDIT:
				$vals['type'] = 'edit';
				break;
			case RC_NEW:
				$vals['type'] = 'new';
				break;
			case RC_MOVE:
				$vals['type'] = 'move';
				break;
			case RC_LOG:
				$vals['type'] = 'log';
				break;
			case RC_EXTERNAL:
				$vals['type'] = 'external';
				break;
			case RC_MOVE_OVER_REDIRECT:
				$vals['type'] = 'move over redirect';
				break;
			default:
				$vals['type'] = $type;
		}

		/* Create a new entry in the result for the title. */
		if ( $this->fld_title ) {
			ApiQueryBase::addTitleInfo( $vals, $title );
		}

		/* Add ids, such as rcid, pageid, revid, and oldid to the change's info. */
		if ( $this->fld_ids ) {
			$vals['rcid'] = intval( $row->rc_id );
			$vals['pageid'] = intval( $row->rc_cur_id );
			$vals['revid'] = intval( $row->rc_this_oldid );
			$vals['old_revid'] = intval( $row->rc_last_oldid );
		}

		/* Add user data and 'anon' flag, if use is anonymous. */
		if ( $this->fld_user || $this->fld_userid ) {

			if ( $this->fld_user ) {
				$vals['user'] = $row->rc_user_text;
			}

			if ( $this->fld_userid ) {
				$vals['userid'] = $row->rc_user;
			}

			if ( !$row->rc_user ) {
				$vals['anon'] = '';
			}
		}

		/* Add flags, such as new, minor, bot. */
		if ( $this->fld_flags ) {
			if ( $row->rc_bot ) {
				$vals['bot'] = '';
			}
			if ( $row->rc_type == RC_NEW ) {
				$vals['new'] = '';
			}
			if ( $row->rc_minor ) {
				$vals['minor'] = '';
			}
		}

		/* Add sizes of each revision. (Only available on 1.10+) */
		if ( $this->fld_sizes ) {
			$vals['oldlen'] = intval( $row->rc_old_len );
			$vals['newlen'] = intval( $row->rc_new_len );
		}

		/* Add the timestamp. */
		if ( $this->fld_timestamp ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->rc_timestamp );
		}

		/* Add edit summary / log summary. */
		if ( $this->fld_comment && isset( $row->rc_comment ) ) {
			$vals['comment'] = $row->rc_comment;
		}

		if ( $this->fld_parsedcomment && isset( $row->rc_comment ) ) {
			$vals['parsedcomment'] = Linker::formatComment( $row->rc_comment, $title );
		}

		if ( $this->fld_redirect ) {
			if ( $row->page_is_redirect ) {
				$vals['redirect'] = '';
			}
		}

		/* Add the patrolled flag */
		if ( $this->fld_patrolled && $row->rc_patrolled == 1 ) {
			$vals['patrolled'] = '';
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

		if ( $this->fld_tags ) {
			if ( $row->ts_tags ) {
				$tags = explode( ',', $row->ts_tags );
				$this->getResult()->setIndexedTagName( $tags, 'tag' );
				$vals['tags'] = $tags;
			} else {
				$vals['tags'] = array();
			}
		}

		if ( !is_null( $this->token ) ) {
			$tokenFunctions = $this->getTokenFunctions();
			foreach ( $this->token as $t ) {
				$val = call_user_func( $tokenFunctions[$t], $row->rc_cur_id,
					$title, RecentChange::newFromRow( $row ) );
				if ( $val === false ) {
					$this->setWarning( "Action '$t' is not allowed for the current user" );
				} else {
					$vals[$t . 'token'] = $val;
				}
			}
		}

		return $vals;
	}

	private function parseRCType( $type ) {
		if ( is_array( $type ) ) {
			$retval = array();
			foreach ( $type as $t ) {
				$retval[] = $this->parseRCType( $t );
			}
			return $retval;
		}
		switch( $type ) {
			case 'edit':
				return RC_EDIT;
			case 'new':
				return RC_NEW;
			case 'log':
				return RC_LOG;
			case 'external':
				return RC_EXTERNAL;
		}
	}

	public function getCacheMode( $params ) {
		if ( isset( $params['show'] ) ) {
			foreach ( $params['show'] as $show ) {
				if ( $show === 'patrolled' || $show === '!patrolled' ) {
					return 'private';
				}
			}
		}
		if ( isset( $params['token'] ) ) {
			return 'private';
		}
		if ( !is_null( $params['prop'] ) && in_array( 'parsedcomment', $params['prop'] ) ) {
			// formatComment() calls wfMessage() among other things
			return 'anon-public-user-private';
		}
		return 'public';
	}

	public function getAllowedParams() {
		return array(
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
			'namespace' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace'
			),
			'user' => array(
				ApiBase::PARAM_TYPE => 'user'
			),
			'excludeuser' => array(
				ApiBase::PARAM_TYPE => 'user'
			),
			'tag' => null,
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'title|timestamp|ids',
				ApiBase::PARAM_TYPE => array(
					'user',
					'userid',
					'comment',
					'parsedcomment',
					'flags',
					'timestamp',
					'title',
					'ids',
					'sizes',
					'redirect',
					'patrolled',
					'loginfo',
					'tags'
				)
			),
			'token' => array(
				ApiBase::PARAM_TYPE => array_keys( $this->getTokenFunctions() ),
				ApiBase::PARAM_ISMULTI => true
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
					'redirect',
					'!redirect',
					'patrolled',
					'!patrolled'
				)
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'type' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array(
					'edit',
					'external',
					'new',
					'log'
				)
			),
			'toponly' => false,
			'continue' => null,
		);
	}

	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'start' => 'The timestamp to start enumerating from',
			'end' => 'The timestamp to end enumerating',
			'dir' => $this->getDirectionDescription( $p ),
			'namespace' => 'Filter log entries to only this namespace(s)',
			'user' => 'Only list changes by this user',
			'excludeuser' => 'Don\'t list changes by this user',
			'prop' => array(
				'Include additional pieces of information',
				' user           - Adds the user responsible for the edit and tags if they are an IP',
				' userid         - Adds the user id responsible for the edit',
				' comment        - Adds the comment for the edit',
				' parsedcomment  - Adds the parsed comment for the edit',
				' flags          - Adds flags for the edit',
				' timestamp      - Adds timestamp of the edit',
				' title          - Adds the page title of the edit',
				' ids            - Adds the page ID, recent changes ID and the new and old revision ID',
				' sizes          - Adds the new and old page length in bytes',
				' redirect       - Tags edit if page is a redirect',
				' patrolled      - Tags edits that have been patrolled',
				' loginfo        - Adds log information (logid, logtype, etc) to log entries',
				' tags           - Lists tags for the entry',
			),
			'token' => 'Which tokens to obtain for each change',
			'show' => array(
				'Show only items that meet this criteria.',
				"For example, to see only minor edits done by logged-in users, set {$p}show=minor|!anon"
			),
			'type' => 'Which types of changes to show',
			'limit' => 'How many total changes to return',
			'tag' => 'Only list changes tagged with this tag',
			'toponly' => 'Only list changes which are the latest revision',
			'continue' => 'When more results are available, use this to continue',
		);
	}

	public function getResultProperties() {
		global $wgLogTypes;
		$props = array(
			'' => array(
				'type' => array(
					ApiBase::PROP_TYPE => array(
						'edit',
						'new',
						'move',
						'log',
						'move over redirect'
					)
				)
			),
			'title' => array(
				'ns' => 'namespace',
				'title' => 'string',
				'new_ns' => array(
					ApiBase::PROP_TYPE => 'namespace',
					ApiBase::PROP_NULLABLE => true
				),
				'new_title' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'ids' => array(
				'rcid' => 'integer',
				'pageid' => 'integer',
				'revid' => 'integer',
				'old_revid' => 'integer'
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
				'bot' => 'boolean',
				'new' => 'boolean',
				'minor' => 'boolean'
			),
			'sizes' => array(
				'oldlen' => 'integer',
				'newlen' => 'integer'
			),
			'timestamp' => array(
				'timestamp' => 'timestamp'
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
			'redirect' => array(
				'redirect' => 'boolean'
			),
			'patrolled' => array(
				'patrolled' => 'boolean'
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

		self::addTokenProperties( $props, $this->getTokenFunctions() );

		return $props;
	}

	public function getDescription() {
		return 'Enumerate recent changes';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'show' ),
			array( 'code' => 'permissiondenied', 'info' => 'You need the patrol right to request the patrolled flag' ),
			array( 'code' => 'user-excludeuser', 'info' => 'user and excludeuser cannot be used together' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&list=recentchanges'
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Recentchanges';
	}
}
