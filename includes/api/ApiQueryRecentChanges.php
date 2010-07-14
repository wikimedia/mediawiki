<?php

/**
 * Created on Oct 19, 2006
 *
 * API for MediaWiki 1.8+
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
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiQueryBase.php' );
}

/**
 * A query action to enumerate the recent changes that were done to the wiki.
 * Various filters are supported.
 *
 * @ingroup API
 */
class ApiQueryRecentChanges extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'rc' );
	}

	private $fld_comment = false, $fld_parsedcomment = false, $fld_user = false, $fld_flags = false,
			$fld_timestamp = false, $fld_title = false, $fld_ids = false,
			$fld_sizes = false;
	/**
	 * Get an array mapping token names to their handler functions.
	 * The prototype for a token function is func($pageid, $title, $rc)
	 * it should return a token or false (permission denied)
	 * @return array(tokenname => function)
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

	public static function getPatrolToken( $pageid, $title, $rc ) {
		global $wgUser;
		if ( !$wgUser->useRCPatrol() && ( !$wgUser->useNPPatrol() ||
				$rc->getAttribute( 'rc_type' ) != RC_NEW ) )
		{
			return false;
		}

		// The patrol token is always the same, let's exploit that
		static $cachedPatrolToken = null;
		if ( !is_null( $cachedPatrolToken ) ) {
			return $cachedPatrolToken;
		}

		$cachedPatrolToken = $wgUser->editToken();
		return $cachedPatrolToken;
	}

	/**
	 * Sets internal state to include the desired properties in the output.
	 * @param $prop associative array of properties, only keys are used here
	 */
	public function initProperties( $prop ) {
		$this->fld_comment = isset( $prop['comment'] );
		$this->fld_parsedcomment = isset( $prop['parsedcomment'] );
		$this->fld_user = isset( $prop['user'] );
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

	/**
	 * Generates and outputs the result of this query based upon the provided parameters.
	 */
	public function execute() {
		/* Get the parameters of the request. */
		$params = $this->extractRequestParams();

		/* Build our basic query. Namely, something along the lines of:
		 * SELECT * FROM recentchanges WHERE rc_timestamp > $start
		 * 		AND rc_timestamp < $end AND rc_namespace = $namespace
		 * 		AND rc_deleted = '0'
		 */
		$db = $this->getDB();
		$this->addTables( 'recentchanges' );
		$index = array( 'recentchanges' => 'rc_timestamp' ); // May change
		$this->addWhereRange( 'rc_timestamp', $params['dir'], $params['start'], $params['end'] );
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
			)
			{
				$this->dieUsageMsg( array( 'show' ) );
			}

			// Check permissions
			global $wgUser;
			if ( isset( $show['patrolled'] ) || isset( $show['!patrolled'] ) ) {
				$this->getMain()->setVaryCookie();
				if ( !$wgUser->useRCPatrol() && !$wgUser->useNPPatrol() ) {
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

		if ( !is_null( $params['user'] ) && !is_null( $param['excludeuser'] ) ) {
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
			'rc_moved_to_ns',
			'rc_moved_to_title'
		) );

		/* Determine what properties we need to display. */
		if ( !is_null( $params['prop'] ) ) {
			$prop = array_flip( $params['prop'] );

			/* Set up internal members based upon params. */
			$this->initProperties( $prop );

			global $wgUser;
			if ( $this->fld_patrolled && !$wgUser->useRCPatrol() && !$wgUser->useNPPatrol() )
			{
				$this->dieUsage( 'You need the patrol right to request the patrolled flag', 'permissiondenied' );
			}

			/* Add fields to our query if they are specified as a needed parameter. */
			$this->addFieldsIf( 'rc_id', $this->fld_ids );
			$this->addFieldsIf( 'rc_this_oldid', $this->fld_ids );
			$this->addFieldsIf( 'rc_last_oldid', $this->fld_ids );
			$this->addFieldsIf( 'rc_comment', $this->fld_comment || $this->fld_parsedcomment );
			$this->addFieldsIf( 'rc_user', $this->fld_user );
			$this->addFieldsIf( 'rc_user_text', $this->fld_user );
			$this->addFieldsIf( 'rc_minor', $this->fld_flags );
			$this->addFieldsIf( 'rc_bot', $this->fld_flags );
			$this->addFieldsIf( 'rc_new', $this->fld_flags );
			$this->addFieldsIf( 'rc_old_len', $this->fld_sizes );
			$this->addFieldsIf( 'rc_new_len', $this->fld_sizes );
			$this->addFieldsIf( 'rc_patrolled', $this->fld_patrolled );
			$this->addFieldsIf( 'rc_logid', $this->fld_loginfo );
			$this->addFieldsIf( 'rc_log_type', $this->fld_loginfo );
			$this->addFieldsIf( 'rc_log_action', $this->fld_loginfo );
			$this->addFieldsIf( 'rc_params', $this->fld_loginfo );
			if ( $this->fld_redirect || isset( $show['redirect'] ) || isset( $show['!redirect'] ) )
			{
				$this->addTables( 'page' );
				$this->addJoinConds( array( 'page' => array( 'LEFT JOIN', array( 'rc_namespace=page_namespace', 'rc_title=page_title' ) ) ) );
				$this->addFields( 'page_is_redirect' );
			}
		}

		if ( $this->fld_tags ) {
			$this->addTables( 'tag_summary' );
			$this->addJoinConds( array( 'tag_summary' => array( 'LEFT JOIN', array( 'rc_id=ts_rc_id' ) ) ) );
			$this->addFields( 'ts_tags' );
		}

		if ( !is_null( $params['tag'] ) ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds( array( 'change_tag' => array( 'INNER JOIN', array( 'rc_id=ct_rc_id' ) ) ) );
			$this->addWhereFld( 'ct_tag' , $params['tag'] );
			global $wgOldChangeTagsIndex;
			$index['change_tag'] = $wgOldChangeTagsIndex ? 'ct_tag' : 'change_tag_tag_id';
		}

		$this->token = $params['token'];
		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$this->addOption( 'USE INDEX', $index );

		$count = 0;
		/* Perform the actual query. */
		$db = $this->getDB();
		$res = $this->select( __METHOD__ );

		/* Iterate through the rows, adding data extracted from them to our query result. */
		foreach ( $res as $row ) {
			if ( ++ $count > $params['limit'] ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->rc_timestamp ) );
				break;
			}

			/* Extract the data from a single row. */
			$vals = $this->extractRowInfo( $row );

			/* Add that row's data to our final output. */
			if ( !$vals ) {
				continue;
			}
			$fit = $this->getResult()->addValue( array( 'query', $this->getModuleName() ), null, $vals );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->rc_timestamp ) );
				break;
			}
		}

		/* Format the result */
		$this->getResult()->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'rc' );
	}

	/**
	 * Extracts from a single sql row the data needed to describe one recent change.
	 *
	 * @param $row The row from which to extract the data.
	 * @return An array mapping strings (descriptors) to their respective string values.
	 * @access public
	 */
	public function extractRowInfo( $row ) {
		/* If page was moved somewhere, get the title of the move target. */
		$movedToTitle = false;
		if ( isset( $row->rc_moved_to_title ) && $row->rc_moved_to_title !== '' )
		{
			$movedToTitle = Title::makeTitle( $row->rc_moved_to_ns, $row->rc_moved_to_title );
		}

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
			case RC_MOVE_OVER_REDIRECT:
				$vals['type'] = 'move over redirect';
				break;
			default:
				$vals['type'] = $type;
		}

		/* Create a new entry in the result for the title. */
		if ( $this->fld_title ) {
			ApiQueryBase::addTitleInfo( $vals, $title );
			if ( $movedToTitle ) {
				ApiQueryBase::addTitleInfo( $vals, $movedToTitle, 'new_' );
			}
		}

		/* Add ids, such as rcid, pageid, revid, and oldid to the change's info. */
		if ( $this->fld_ids ) {
			$vals['rcid'] = intval( $row->rc_id );
			$vals['pageid'] = intval( $row->rc_cur_id );
			$vals['revid'] = intval( $row->rc_this_oldid );
			$vals['old_revid'] = intval( $row->rc_last_oldid );
		}

		/* Add user data and 'anon' flag, if use is anonymous. */
		if ( $this->fld_user ) {
			$vals['user'] = $row->rc_user_text;
			if ( !$row->rc_user ) {
				$vals['anon'] = '';
			}
		}

		/* Add flags, such as new, minor, bot. */
		if ( $this->fld_flags ) {
			if ( $row->rc_bot ) {
				$vals['bot'] = '';
			}
			if ( $row->rc_new ) {
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
			global $wgUser;
			$this->getMain()->setVaryCookie();
			$vals['parsedcomment'] = $wgUser->getSkin()->formatComment( $row->rc_comment, $title );
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
			ApiQueryLogEvents::addLogParams(
				$this->getResult(),
				$vals, $row->rc_params,
				$row->rc_log_type, $row->rc_timestamp
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
			// Don't cache tokens
			$this->getMain()->setCachePrivate();
			
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
		}
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
					'new',
					'log'
				)
			)
		);
	}

	public function getParamDescription() {
		return array(
			'start' => 'The timestamp to start enumerating from',
			'end' => 'The timestamp to end enumerating',
			'dir' => 'In which direction to enumerate',
			'namespace' => 'Filter log entries to only this namespace(s)',
			'user' => 'Only list changes by this user',
			'excludeuser' => 'Don\'t list changes by this user',
			'prop' => array(
				'Include additional pieces of information',
				' user           - Adds the user responsible for the edit and tags if they are an IP',
				' comment        - Adds the comment for the edit',
				' parsedcomment  - Adds the parsed comment for the edit',
				' flags          - Adds flags for the edit',
				' timestamp      - Adds timestamp of the edit',
				' title          - Adds the page title of the edit',
				' ids            - Adds the page id, recent changes id and the new and old revision id',
				' sizes          - Adds the new and old page length in bytes',
				' redirect       - Tags edit if page is a redirect',
				' patrolled      - Tags edits have have been patrolled',
				' loginfo        - Adds log information (logid, logtype, etc) to log entries',
				' tags           - Lists tags for the entry',
			),
			'token' => 'Which tokens to obtain for each change',
			'show' => array(
				'Show only items that meet this criteria.',
				"For example, to see only minor edits done by logged-in users, set {$this->getModulePrefix()}show=minor|!anon"
			),
			'type' => 'Which types of changes to show',
			'limit' => 'How many total changes to return',
			'tag' => 'Only list changes tagged with this tag',
		);
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

	protected function getExamples() {
		return array(
			'api.php?action=query&list=recentchanges'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
