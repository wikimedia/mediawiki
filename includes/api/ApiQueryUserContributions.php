<?php
/**
 * API for MediaWiki 1.8+
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
 * This query action adds a list of a specified user's contributions to the output.
 *
 * @ingroup API
 */
class ApiQueryContributions extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'uc' );
	}

	private $params, $prefixMode, $userprefix, $multiUserMode, $usernames;
	private $fld_ids = false, $fld_title = false, $fld_timestamp = false,
			$fld_comment = false, $fld_parsedcomment = false, $fld_flags = false,
			$fld_patrolled = false, $fld_tags = false, $fld_size = false;

	public function execute() {
		// Parse some parameters
		$this->params = $this->extractRequestParams();

		$prop = array_flip( $this->params['prop'] );
		$this->fld_ids = isset( $prop['ids'] );
		$this->fld_title = isset( $prop['title'] );
		$this->fld_comment = isset( $prop['comment'] );
		$this->fld_parsedcomment = isset ( $prop['parsedcomment'] );
		$this->fld_size = isset( $prop['size'] );
		$this->fld_flags = isset( $prop['flags'] );
		$this->fld_timestamp = isset( $prop['timestamp'] );
		$this->fld_patrolled = isset( $prop['patrolled'] );
		$this->fld_tags = isset( $prop['tags'] );

		// TODO: if the query is going only against the revision table, should this be done?
		$this->selectNamedDB( 'contributions', DB_SLAVE, 'contributions' );

		if ( isset( $this->params['userprefix'] ) ) {
			$this->prefixMode = true;
			$this->multiUserMode = true;
			$this->userprefix = $this->params['userprefix'];
		} else {
			$this->usernames = array();
			if ( !is_array( $this->params['user'] ) ) {
				$this->params['user'] = array( $this->params['user'] );
			}
			if ( !count( $this->params['user'] ) ) {
				$this->dieUsage( 'User parameter may not be empty.', 'param_user' );
			}
			foreach ( $this->params['user'] as $u ) {
				$this->prepareUsername( $u );
			}
			$this->prefixMode = false;
			$this->multiUserMode = ( count( $this->params['user'] ) > 1 );
		}
		$this->prepareQuery();

		// Do the actual query.
		$res = $this->select( __METHOD__ );

		// Initialise some variables
		$count = 0;
		$limit = $this->params['limit'];

		// Fetch each row
		foreach ( $res as $row ) {
			if ( ++ $count > $limit ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				if ( $this->multiUserMode ) {
					$this->setContinueEnumParameter( 'continue', $this->continueStr( $row ) );
				} else {
					$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->rev_timestamp ) );
				}
				break;
			}

			$vals = $this->extractRowInfo( $row );
			$fit = $this->getResult()->addValue( array( 'query', $this->getModuleName() ), null, $vals );
			if ( !$fit ) {
				if ( $this->multiUserMode ) {
					$this->setContinueEnumParameter( 'continue', $this->continueStr( $row ) );
				} else {
					$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->rev_timestamp ) );
				}
				break;
			}
		}

		$this->getResult()->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'item' );
	}

	/**
	 * Validate the 'user' parameter and set the value to compare
	 * against `revision`.`rev_user_text`
	 */
	private function prepareUsername( $user ) {
		if ( !is_null( $user ) && $user !== '' ) {
			$name = User::isIP( $user )
				? $user
				: User::getCanonicalName( $user, 'valid' );
			if ( $name === false ) {
				$this->dieUsage( "User name {$user} is not valid", 'param_user' );
			} else {
				$this->usernames[] = $name;
			}
		} else {
			$this->dieUsage( 'User parameter may not be empty', 'param_user' );
		}
	}

	/**
	 * Prepares the query and returns the limit of rows requested
	 */
	private function prepareQuery() {
		// We're after the revision table, and the corresponding page
		// row for anything we retrieve. We may also need the
		// recentchanges row and/or tag summary row.
		global $wgUser;
		$tables = array( 'page', 'revision' ); // Order may change
		$this->addWhere( 'page_id=rev_page' );

		// Handle continue parameter
		if ( $this->multiUserMode && !is_null( $this->params['continue'] ) ) {
			$continue = explode( '|', $this->params['continue'] );
			if ( count( $continue ) != 2 ) {
				$this->dieUsage( 'Invalid continue param. You should pass the original ' .
					'value returned by the previous query', '_badcontinue' );
			}
			$encUser = $this->getDB()->strencode( $continue[0] );
			$encTS = wfTimestamp( TS_MW, $continue[1] );
			$op = ( $this->params['dir'] == 'older' ? '<' : '>' );
			$this->addWhere(
				"rev_user_text $op '$encUser' OR " .
				"(rev_user_text = '$encUser' AND " .
				"rev_timestamp $op= '$encTS')"
			);
		}

		if ( !$wgUser->isAllowed( 'hideuser' ) ) {
			$this->addWhere( $this->getDB()->bitAnd( 'rev_deleted', Revision::DELETED_USER ) . ' = 0' );
		}
		// We only want pages by the specified users.
		if ( $this->prefixMode ) {
			$this->addWhere( 'rev_user_text' . $this->getDB()->buildLike( $this->userprefix, $this->getDB()->anyString() ) );
		} else {
			$this->addWhereFld( 'rev_user_text', $this->usernames );
		}
		// ... and in the specified timeframe.
		// Ensure the same sort order for rev_user_text and rev_timestamp
		// so our query is indexed
		if ( $this->multiUserMode ) {
			$this->addWhereRange( 'rev_user_text', $this->params['dir'], null, null );
		}
		$this->addWhereRange( 'rev_timestamp',
			$this->params['dir'], $this->params['start'], $this->params['end'] );
		$this->addWhereFld( 'page_namespace', $this->params['namespace'] );

		$show = $this->params['show'];
		if ( !is_null( $show ) ) {
			$show = array_flip( $show );
			if ( ( isset( $show['minor'] ) && isset( $show['!minor'] ) )
			   		|| ( isset( $show['patrolled'] ) && isset( $show['!patrolled'] ) ) ) {
				$this->dieUsageMsg( array( 'show' ) );
			}

			$this->addWhereIf( 'rev_minor_edit = 0', isset( $show['!minor'] ) );
			$this->addWhereIf( 'rev_minor_edit != 0', isset( $show['minor'] ) );
			$this->addWhereIf( 'rc_patrolled = 0', isset( $show['!patrolled'] ) );
			$this->addWhereIf( 'rc_patrolled != 0', isset( $show['patrolled'] ) );
		}
		$this->addOption( 'LIMIT', $this->params['limit'] + 1 );
		$index = array( 'revision' => 'usertext_timestamp' );

		// Mandatory fields: timestamp allows request continuation
		// ns+title checks if the user has access rights for this page
		// user_text is necessary if multiple users were specified
		$this->addFields( array(
			'rev_timestamp',
			'page_namespace',
			'page_title',
			'rev_user',
			'rev_user_text',
			'rev_deleted'
		) );

		if ( isset( $show['patrolled'] ) || isset( $show['!patrolled'] ) ||
				 $this->fld_patrolled ) {
			if ( !$wgUser->useRCPatrol() && !$wgUser->useNPPatrol() ) {
				$this->dieUsage( 'You need the patrol right to request the patrolled flag', 'permissiondenied' );
			}

			// Use a redundant join condition on both
			// timestamp and ID so we can use the timestamp
			// index
			$index['recentchanges'] = 'rc_user_text';
			if ( isset( $show['patrolled'] ) || isset( $show['!patrolled'] ) ) {
				// Put the tables in the right order for
				// STRAIGHT_JOIN
				$tables = array( 'revision', 'recentchanges', 'page' );
				$this->addOption( 'STRAIGHT_JOIN' );
				$this->addWhere( 'rc_user_text=rev_user_text' );
				$this->addWhere( 'rc_timestamp=rev_timestamp' );
				$this->addWhere( 'rc_this_oldid=rev_id' );
			} else {
				$tables[] = 'recentchanges';
				$this->addJoinConds( array( 'recentchanges' => array(
					'LEFT JOIN', array(
						'rc_user_text=rev_user_text',
						'rc_timestamp=rev_timestamp',
						'rc_this_oldid=rev_id' ) ) ) );
			}
		}

		$this->addTables( $tables );
		$this->addFieldsIf( 'rev_page', $this->fld_ids );
		$this->addFieldsIf( 'rev_id', $this->fld_ids || $this->fld_flags );
		$this->addFieldsIf( 'page_latest', $this->fld_flags );
		// $this->addFieldsIf( 'rev_text_id', $this->fld_ids ); // Should this field be exposed?
		$this->addFieldsIf( 'rev_comment', $this->fld_comment || $this->fld_parsedcomment );
		$this->addFieldsIf( 'rev_len', $this->fld_size );
		$this->addFieldsIf( 'rev_minor_edit', $this->fld_flags );
		$this->addFieldsIf( 'rev_parent_id', $this->fld_flags );
		$this->addFieldsIf( 'rc_patrolled', $this->fld_patrolled );

		if ( $this->fld_tags ) {
			$this->addTables( 'tag_summary' );
			$this->addJoinConds( array( 'tag_summary' => array( 'LEFT JOIN', array( 'rev_id=ts_rev_id' ) ) ) );
			$this->addFields( 'ts_tags' );
		}

		if ( isset( $this->params['tag'] ) ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds( array( 'change_tag' => array( 'INNER JOIN', array( 'rev_id=ct_rev_id' ) ) ) );
			$this->addWhereFld( 'ct_tag', $this->params['tag'] );
			global $wgOldChangeTagsIndex;
			$index['change_tag'] = $wgOldChangeTagsIndex ? 'ct_tag' : 'change_tag_tag_id';
		}

		$this->addOption( 'USE INDEX', $index );
	}

	/**
	 * Extract fields from the database row and append them to a result array
	 */
	private function extractRowInfo( $row ) {
		$vals = array();

		$vals['userid'] = $row->rev_user;
		$vals['user'] = $row->rev_user_text;
		if ( $row->rev_deleted & Revision::DELETED_USER ) {
			$vals['userhidden'] = '';
		}
		if ( $this->fld_ids ) {
			$vals['pageid'] = intval( $row->rev_page );
			$vals['revid'] = intval( $row->rev_id );
			// $vals['textid'] = intval( $row->rev_text_id ); // todo: Should this field be exposed?
		}

		$title = Title::makeTitle( $row->page_namespace, $row->page_title );

		if ( $this->fld_title ) {
			ApiQueryBase::addTitleInfo( $vals, $title );
		}

		if ( $this->fld_timestamp ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->rev_timestamp );
		}

		if ( $this->fld_flags ) {
			if ( $row->rev_parent_id == 0 && !is_null( $row->rev_parent_id ) ) {
				$vals['new'] = '';
			}
			if ( $row->rev_minor_edit ) {
				$vals['minor'] = '';
			}
			if ( $row->page_latest == $row->rev_id ) {
				$vals['top'] = '';
			}
		}

		if ( ( $this->fld_comment || $this->fld_parsedcomment ) && isset( $row->rev_comment ) ) {
			if ( $row->rev_deleted & Revision::DELETED_COMMENT ) {
				$vals['commenthidden'] = '';
			} else {
				if ( $this->fld_comment ) {
					$vals['comment'] = $row->rev_comment;
				}

				if ( $this->fld_parsedcomment ) {
					global $wgUser;
					$vals['parsedcomment'] = $wgUser->getSkin()->formatComment( $row->rev_comment, $title );
				}
			}
		}

		if ( $this->fld_patrolled && $row->rc_patrolled ) {
			$vals['patrolled'] = '';
		}

		if ( $this->fld_size && !is_null( $row->rev_len ) ) {
			$vals['size'] = intval( $row->rev_len );
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

	private function continueStr( $row ) {
		return $row->rev_user_text . '|' .
			wfTimestamp( TS_ISO_8601, $row->rev_timestamp );
	}

	public function getCacheMode( $params ) {
		// This module provides access to deleted revisions and patrol flags if
		// the requester is logged in
		return 'anon-public-user-private';
	}

	public function getAllowedParams() {
		return array(
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'start' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'end' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'continue' => null,
			'user' => array(
				ApiBase::PARAM_ISMULTI => true
			),
			'userprefix' => null,
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
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'ids|title|timestamp|comment|size|flags',
				ApiBase::PARAM_TYPE => array(
					'ids',
					'title',
					'timestamp',
					'comment',
					'parsedcomment',
					'size',
					'flags',
					'patrolled',
					'tags'
				)
			),
			'show' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array(
					'minor',
					'!minor',
					'patrolled',
					'!patrolled',
				)
			),
			'tag' => null,
		);
	}

	public function getParamDescription() {
		global $wgRCMaxAge;
		$p = $this->getModulePrefix();
		return array(
			'limit' => 'The maximum number of contributions to return',
			'start' => 'The start timestamp to return from',
			'end' => 'The end timestamp to return to',
			'continue' => 'When more results are available, use this to continue',
			'user' => 'The users to retrieve contributions for',
			'userprefix' => "Retrieve contibutions for all users whose names begin with this value. Overrides {$p}user",
			'dir' => 'The direction to search (older or newer)',
			'namespace' => 'Only list contributions in these namespaces',
			'prop' => array(
				'Include additional pieces of information',
				' ids            - Adds the page id and revision id',
				' title          - Adds the title and namespace id of the page',
				' timestamp      - Adds the timestamp of the edit',
				' comment        - Adds the comment of the edit',
				' parsedcomment  - Adds the parsed comment of the edit',
				' size           - Adds the size of the page',
				' flags          - Adds flags of the edit',
				' patrolled      - Tags patrolled edits',
				' tags           - Lists tags for the edit',
			),
			'show' => array( "Show only items that meet this criteria, e.g. non minor edits only: {$p}show=!minor",
					"NOTE: if {$p}show=patrolled or {$p}show=!patrolled is set, revisions older than $wgRCMaxAge won\'t be shown", ),
			'tag' => 'Only list revisions tagged with this tag',
		);
	}

	public function getDescription() {
		return 'Get all edits by a user';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'param_user', 'info' => 'User parameter may not be empty.' ),
			array( 'code' => 'param_user', 'info' => 'User name user is not valid' ),
			array( 'show' ),
			array( 'code' => 'permissiondenied', 'info' => 'You need the patrol right to request the patrolled flag' ),
		) );
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&list=usercontribs&ucuser=YurikBot',
			'api.php?action=query&list=usercontribs&ucuserprefix=217.121.114.',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
