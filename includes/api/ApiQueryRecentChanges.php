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

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'rc' );
	}

	private $fld_comment = false, $fld_parsedcomment = false, $fld_user = false, $fld_userid = false,
		$fld_flags = false, $fld_timestamp = false, $fld_title = false, $fld_ids = false,
		$fld_sizes = false, $fld_redirect = false, $fld_patrolled = false, $fld_loginfo = false,
		$fld_tags = false, $fld_sha1 = false, $token = [];

	private $tokenFunctions;

	/**
	 * Get an array mapping token names to their handler functions.
	 * The prototype for a token function is func($pageid, $title, $rc)
	 * it should return a token or false (permission denied)
	 * @deprecated since 1.24
	 * @return array Array(tokenname => function)
	 */
	protected function getTokenFunctions() {
		// Don't call the hooks twice
		if ( isset( $this->tokenFunctions ) ) {
			return $this->tokenFunctions;
		}

		// If we're in a mode that breaks the same-origin policy, no tokens can
		// be obtained
		if ( $this->lacksSameOriginSecurity() ) {
			return [];
		}

		$this->tokenFunctions = [
			'patrol' => [ 'ApiQueryRecentChanges', 'getPatrolToken' ]
		];
		Hooks::run( 'APIQueryRecentChangesTokens', [ &$this->tokenFunctions ] );

		return $this->tokenFunctions;
	}

	/**
	 * @deprecated since 1.24
	 * @param int $pageid
	 * @param Title $title
	 * @param RecentChange|null $rc
	 * @return bool|string
	 */
	public static function getPatrolToken( $pageid, $title, $rc = null ) {
		global $wgUser;

		$validTokenUser = false;

		if ( $rc ) {
			if ( ( $wgUser->useRCPatrol() && $rc->getAttribute( 'rc_type' ) == RC_EDIT ) ||
				( $wgUser->useNPPatrol() && $rc->getAttribute( 'rc_type' ) == RC_NEW )
			) {
				$validTokenUser = true;
			}
		} elseif ( $wgUser->useRCPatrol() || $wgUser->useNPPatrol() ) {
			$validTokenUser = true;
		}

		if ( $validTokenUser ) {
			// The patrol token is always the same, let's exploit that
			static $cachedPatrolToken = null;

			if ( is_null( $cachedPatrolToken ) ) {
				$cachedPatrolToken = $wgUser->getEditToken( 'patrol' );
			}

			return $cachedPatrolToken;
		}

		return false;
	}

	/**
	 * Sets internal state to include the desired properties in the output.
	 * @param array $prop Associative array of properties, only keys are used here
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
		$this->fld_sha1 = isset( $prop['sha1'] );
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
	 * @param ApiPageSet $resultPageSet
	 */
	public function run( $resultPageSet = null ) {
		$user = $this->getUser();
		/* Get the parameters of the request. */
		$params = $this->extractRequestParams();

		/* Build our basic query. Namely, something along the lines of:
		 * SELECT * FROM recentchanges WHERE rc_timestamp > $start
		 * 		AND rc_timestamp < $end AND rc_namespace = $namespace
		 */
		$this->addTables( 'recentchanges' );
		$index = [ 'recentchanges' => 'rc_timestamp' ]; // May change
		$this->addTimestampWhereRange( 'rc_timestamp', $params['dir'], $params['start'], $params['end'] );

		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 2 );
			$db = $this->getDB();
			$timestamp = $db->addQuotes( $db->timestamp( $cont[0] ) );
			$id = intval( $cont[1] );
			$this->dieContinueUsageIf( $id != $cont[1] );
			$op = $params['dir'] === 'older' ? '<' : '>';
			$this->addWhere(
				"rc_timestamp $op $timestamp OR " .
				"(rc_timestamp = $timestamp AND " .
				"rc_id $op= $id)"
			);
		}

		$order = $params['dir'] === 'older' ? 'DESC' : 'ASC';
		$this->addOption( 'ORDER BY', [
			"rc_timestamp $order",
			"rc_id $order",
		] );

		$this->addWhereFld( 'rc_namespace', $params['namespace'] );

		if ( !is_null( $params['type'] ) ) {
			try {
				$this->addWhereFld( 'rc_type', RecentChange::parseToRCType( $params['type'] ) );
			} catch ( Exception $e ) {
				ApiBase::dieDebug( __METHOD__, $e->getMessage() );
			}
		}

		if ( !is_null( $params['show'] ) ) {
			$show = array_flip( $params['show'] );

			/* Check for conflicting parameters. */
			if ( ( isset( $show['minor'] ) && isset( $show['!minor'] ) )
				|| ( isset( $show['bot'] ) && isset( $show['!bot'] ) )
				|| ( isset( $show['anon'] ) && isset( $show['!anon'] ) )
				|| ( isset( $show['redirect'] ) && isset( $show['!redirect'] ) )
				|| ( isset( $show['patrolled'] ) && isset( $show['!patrolled'] ) )
				|| ( isset( $show['patrolled'] ) && isset( $show['unpatrolled'] ) )
				|| ( isset( $show['!patrolled'] ) && isset( $show['unpatrolled'] ) )
			) {
				$this->dieUsageMsg( 'show' );
			}

			// Check permissions
			if ( isset( $show['patrolled'] )
				|| isset( $show['!patrolled'] )
				|| isset( $show['unpatrolled'] )
			) {
				if ( !$user->useRCPatrol() && !$user->useNPPatrol() ) {
					$this->dieUsage(
						'You need patrol or patrolmarks permission to request the patrolled flag',
						'permissiondenied'
					);
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

			if ( isset( $show['unpatrolled'] ) ) {
				// See ChangesList::isUnpatrolled
				if ( $user->useRCPatrol() ) {
					$this->addWhere( 'rc_patrolled = 0' );
				} elseif ( $user->useNPPatrol() ) {
					$this->addWhere( 'rc_patrolled = 0' );
					$this->addWhereFld( 'rc_type', RC_NEW );
				}
			}

			// Don't throw log entries out the window here
			$this->addWhereIf(
				'page_is_redirect = 0 OR page_is_redirect IS NULL',
				isset( $show['!redirect'] )
			);
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
		$this->addFields( [
			'rc_id',
			'rc_timestamp',
			'rc_namespace',
			'rc_title',
			'rc_cur_id',
			'rc_type',
			'rc_deleted'
		] );

		$showRedirects = false;
		/* Determine what properties we need to display. */
		if ( !is_null( $params['prop'] ) ) {
			$prop = array_flip( $params['prop'] );

			/* Set up internal members based upon params. */
			$this->initProperties( $prop );

			if ( $this->fld_patrolled && !$user->useRCPatrol() && !$user->useNPPatrol() ) {
				$this->dieUsage(
					'You need patrol or patrolmarks permission to request the patrolled flag',
					'permissiondenied'
				);
			}

			/* Add fields to our query if they are specified as a needed parameter. */
			$this->addFieldsIf( [ 'rc_this_oldid', 'rc_last_oldid' ], $this->fld_ids );
			$this->addFieldsIf( 'rc_comment', $this->fld_comment || $this->fld_parsedcomment );
			$this->addFieldsIf( 'rc_user', $this->fld_user || $this->fld_userid );
			$this->addFieldsIf( 'rc_user_text', $this->fld_user );
			$this->addFieldsIf( [ 'rc_minor', 'rc_type', 'rc_bot' ], $this->fld_flags );
			$this->addFieldsIf( [ 'rc_old_len', 'rc_new_len' ], $this->fld_sizes );
			$this->addFieldsIf( [ 'rc_patrolled', 'rc_log_type' ], $this->fld_patrolled );
			$this->addFieldsIf(
				[ 'rc_logid', 'rc_log_type', 'rc_log_action', 'rc_params' ],
				$this->fld_loginfo
			);
			$showRedirects = $this->fld_redirect || isset( $show['redirect'] )
				|| isset( $show['!redirect'] );
		}
		$this->addFieldsIf( [ 'rc_this_oldid' ],
			$resultPageSet && $params['generaterevisions'] );

		if ( $this->fld_tags ) {
			$this->addTables( 'tag_summary' );
			$this->addJoinConds( [ 'tag_summary' => [ 'LEFT JOIN', [ 'rc_id=ts_rc_id' ] ] ] );
			$this->addFields( 'ts_tags' );
		}

		if ( $this->fld_sha1 ) {
			$this->addTables( 'revision' );
			$this->addJoinConds( [ 'revision' => [ 'LEFT JOIN',
				[ 'rc_this_oldid=rev_id' ] ] ] );
			$this->addFields( [ 'rev_sha1', 'rev_deleted' ] );
		}

		if ( $params['toponly'] || $showRedirects ) {
			$this->addTables( 'page' );
			$this->addJoinConds( [ 'page' => [ 'LEFT JOIN',
				[ 'rc_namespace=page_namespace', 'rc_title=page_title' ] ] ] );
			$this->addFields( 'page_is_redirect' );

			if ( $params['toponly'] ) {
				$this->addWhere( 'rc_this_oldid = page_latest' );
			}
		}

		if ( !is_null( $params['tag'] ) ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds( [ 'change_tag' => [ 'INNER JOIN', [ 'rc_id=ct_rc_id' ] ] ] );
			$this->addWhereFld( 'ct_tag', $params['tag'] );
		}

		// Paranoia: avoid brute force searches (bug 17342)
		if ( !is_null( $params['user'] ) || !is_null( $params['excludeuser'] ) ) {
			if ( !$user->isAllowed( 'deletedhistory' ) ) {
				$bitmask = Revision::DELETED_USER;
			} elseif ( !$user->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
				$bitmask = Revision::DELETED_USER | Revision::DELETED_RESTRICTED;
			} else {
				$bitmask = 0;
			}
			if ( $bitmask ) {
				$this->addWhere( $this->getDB()->bitAnd( 'rc_deleted', $bitmask ) . " != $bitmask" );
			}
		}
		if ( $this->getRequest()->getCheck( 'namespace' ) ) {
			// LogPage::DELETED_ACTION hides the affected page, too.
			if ( !$user->isAllowed( 'deletedhistory' ) ) {
				$bitmask = LogPage::DELETED_ACTION;
			} elseif ( !$user->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
				$bitmask = LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED;
			} else {
				$bitmask = 0;
			}
			if ( $bitmask ) {
				$this->addWhere( $this->getDB()->makeList( [
					'rc_type != ' . RC_LOG,
					$this->getDB()->bitAnd( 'rc_deleted', $bitmask ) . " != $bitmask",
				], LIST_OR ) );
			}
		}

		$this->token = $params['token'];
		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$this->addOption( 'USE INDEX', $index );

		$count = 0;
		/* Perform the actual query. */
		$res = $this->select( __METHOD__ );

		$revids = [];
		$titles = [];

		$result = $this->getResult();

		/* Iterate through the rows, adding data extracted from them to our query result. */
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', "$row->rc_timestamp|$row->rc_id" );
				break;
			}

			if ( is_null( $resultPageSet ) ) {
				/* Extract the data from a single row. */
				$vals = $this->extractRowInfo( $row );

				/* Add that row's data to our final output. */
				$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', "$row->rc_timestamp|$row->rc_id" );
					break;
				}
			} elseif ( $params['generaterevisions'] ) {
				$revid = (int)$row->rc_this_oldid;
				if ( $revid > 0 ) {
					$revids[] = $revid;
				}
			} else {
				$titles[] = Title::makeTitle( $row->rc_namespace, $row->rc_title );
			}
		}

		if ( is_null( $resultPageSet ) ) {
			/* Format the result */
			$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'rc' );
		} elseif ( $params['generaterevisions'] ) {
			$resultPageSet->populateFromRevisionIDs( $revids );
		} else {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	/**
	 * Extracts from a single sql row the data needed to describe one recent change.
	 *
	 * @param stdClass $row The row from which to extract the data.
	 * @return array An array mapping strings (descriptors) to their respective string values.
	 * @access public
	 */
	public function extractRowInfo( $row ) {
		/* Determine the title of the page that has been changed. */
		$title = Title::makeTitle( $row->rc_namespace, $row->rc_title );
		$user = $this->getUser();

		/* Our output data. */
		$vals = [];

		$type = intval( $row->rc_type );
		$vals['type'] = RecentChange::parseFromRCType( $type );

		$anyHidden = false;

		/* Create a new entry in the result for the title. */
		if ( $this->fld_title || $this->fld_ids ) {
			if ( $type === RC_LOG && ( $row->rc_deleted & LogPage::DELETED_ACTION ) ) {
				$vals['actionhidden'] = true;
				$anyHidden = true;
			}
			if ( $type !== RC_LOG ||
				LogEventsList::userCanBitfield( $row->rc_deleted, LogPage::DELETED_ACTION, $user )
			) {
				if ( $this->fld_title ) {
					ApiQueryBase::addTitleInfo( $vals, $title );
				}
				if ( $this->fld_ids ) {
					$vals['pageid'] = intval( $row->rc_cur_id );
					$vals['revid'] = intval( $row->rc_this_oldid );
					$vals['old_revid'] = intval( $row->rc_last_oldid );
				}
			}
		}

		if ( $this->fld_ids ) {
			$vals['rcid'] = intval( $row->rc_id );
		}

		/* Add user data and 'anon' flag, if user is anonymous. */
		if ( $this->fld_user || $this->fld_userid ) {
			if ( $row->rc_deleted & Revision::DELETED_USER ) {
				$vals['userhidden'] = true;
				$anyHidden = true;
			}
			if ( Revision::userCanBitfield( $row->rc_deleted, Revision::DELETED_USER, $user ) ) {
				if ( $this->fld_user ) {
					$vals['user'] = $row->rc_user_text;
				}

				if ( $this->fld_userid ) {
					$vals['userid'] = (int)$row->rc_user;
				}

				if ( !$row->rc_user ) {
					$vals['anon'] = true;
				}
			}
		}

		/* Add flags, such as new, minor, bot. */
		if ( $this->fld_flags ) {
			$vals['bot'] = (bool)$row->rc_bot;
			$vals['new'] = $row->rc_type == RC_NEW;
			$vals['minor'] = (bool)$row->rc_minor;
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
		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			if ( $row->rc_deleted & Revision::DELETED_COMMENT ) {
				$vals['commenthidden'] = true;
				$anyHidden = true;
			}
			if ( Revision::userCanBitfield( $row->rc_deleted, Revision::DELETED_COMMENT, $user ) ) {
				if ( $this->fld_comment && isset( $row->rc_comment ) ) {
					$vals['comment'] = $row->rc_comment;
				}

				if ( $this->fld_parsedcomment && isset( $row->rc_comment ) ) {
					$vals['parsedcomment'] = Linker::formatComment( $row->rc_comment, $title );
				}
			}
		}

		if ( $this->fld_redirect ) {
			$vals['redirect'] = (bool)$row->page_is_redirect;
		}

		/* Add the patrolled flag */
		if ( $this->fld_patrolled ) {
			$vals['patrolled'] = $row->rc_patrolled == 1;
			$vals['unpatrolled'] = ChangesList::isUnpatrolled( $row, $user );
		}

		if ( $this->fld_loginfo && $row->rc_type == RC_LOG ) {
			if ( $row->rc_deleted & LogPage::DELETED_ACTION ) {
				$vals['actionhidden'] = true;
				$anyHidden = true;
			}
			if ( LogEventsList::userCanBitfield( $row->rc_deleted, LogPage::DELETED_ACTION, $user ) ) {
				$vals['logid'] = intval( $row->rc_logid );
				$vals['logtype'] = $row->rc_log_type;
				$vals['logaction'] = $row->rc_log_action;
				$vals['logparams'] = LogFormatter::newFromRow( $row )->formatParametersForApi();
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

		if ( $this->fld_sha1 && $row->rev_sha1 !== null ) {
			if ( $row->rev_deleted & Revision::DELETED_TEXT ) {
				$vals['sha1hidden'] = true;
				$anyHidden = true;
			}
			if ( Revision::userCanBitfield( $row->rev_deleted, Revision::DELETED_TEXT, $user ) ) {
				if ( $row->rev_sha1 !== '' ) {
					$vals['sha1'] = Wikimedia\base_convert( $row->rev_sha1, 36, 16, 40 );
				} else {
					$vals['sha1'] = '';
				}
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

		if ( $anyHidden && ( $row->rc_deleted & Revision::DELETED_RESTRICTED ) ) {
			$vals['suppressed'] = true;
		}

		return $vals;
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
		if ( $this->userCanSeeRevDel() ) {
			return 'private';
		}
		if ( !is_null( $params['prop'] ) && in_array( 'parsedcomment', $params['prop'] ) ) {
			// formatComment() calls wfMessage() among other things
			return 'anon-public-user-private';
		}

		return 'public';
	}

	public function getAllowedParams() {
		return [
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
			'namespace' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace'
			],
			'user' => [
				ApiBase::PARAM_TYPE => 'user'
			],
			'excludeuser' => [
				ApiBase::PARAM_TYPE => 'user'
			],
			'tag' => null,
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'title|timestamp|ids',
				ApiBase::PARAM_TYPE => [
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
					'tags',
					'sha1',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'token' => [
				ApiBase::PARAM_DEPRECATED => true,
				ApiBase::PARAM_TYPE => array_keys( $this->getTokenFunctions() ),
				ApiBase::PARAM_ISMULTI => true
			],
			'show' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					'minor',
					'!minor',
					'bot',
					'!bot',
					'anon',
					'!anon',
					'redirect',
					'!redirect',
					'patrolled',
					'!patrolled',
					'unpatrolled'
				]
			],
			'limit' => [
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'type' => [
				ApiBase::PARAM_DFLT => 'edit|new|log|categorize',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => RecentChange::getChangeTypes()
			],
			'toponly' => false,
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'generaterevisions' => false,
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=recentchanges'
				=> 'apihelp-query+recentchanges-example-simple',
			'action=query&generator=recentchanges&grcshow=!patrolled&prop=info'
				=> 'apihelp-query+recentchanges-example-generator',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Recentchanges';
	}
}
