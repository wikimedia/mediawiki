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
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\NameTableAccessException;

/**
 * This query action adds a list of a specified user's contributions to the output.
 *
 * @ingroup API
 */
class ApiQueryUserContribs extends ApiQueryBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'uc' );
	}

	private $params, $multiUserMode, $orderBy, $parentLens, $commentStore;

	private $fld_ids = false, $fld_title = false, $fld_timestamp = false,
		$fld_comment = false, $fld_parsedcomment = false, $fld_flags = false,
		$fld_patrolled = false, $fld_tags = false, $fld_size = false, $fld_sizediff = false;

	public function execute() {
		global $wgActorTableSchemaMigrationStage;

		// Parse some parameters
		$this->params = $this->extractRequestParams();

		$this->commentStore = CommentStore::getStore();

		$prop = array_flip( $this->params['prop'] );
		$this->fld_ids = isset( $prop['ids'] );
		$this->fld_title = isset( $prop['title'] );
		$this->fld_comment = isset( $prop['comment'] );
		$this->fld_parsedcomment = isset( $prop['parsedcomment'] );
		$this->fld_size = isset( $prop['size'] );
		$this->fld_sizediff = isset( $prop['sizediff'] );
		$this->fld_flags = isset( $prop['flags'] );
		$this->fld_timestamp = isset( $prop['timestamp'] );
		$this->fld_patrolled = isset( $prop['patrolled'] );
		$this->fld_tags = isset( $prop['tags'] );

		// The main query may use the 'contributions' group DB, which can map to replica DBs
		// with extra user based indexes or partioning by user. The additional metadata
		// queries should use a regular replica DB since the lookup pattern is not all by user.
		$dbSecondary = $this->getDB(); // any random replica DB

		$sort = ( $this->params['dir'] == 'newer' ? '' : ' DESC' );
		$op = ( $this->params['dir'] == 'older' ? '<' : '>' );

		// Create an Iterator that produces the UserIdentity objects we need, depending
		// on which of the 'userprefix', 'userids', or 'user' params was
		// specified.
		$this->requireOnlyOneParameter( $this->params, 'userprefix', 'userids', 'user' );
		if ( isset( $this->params['userprefix'] ) ) {
			$this->multiUserMode = true;
			$this->orderBy = 'name';
			$fname = __METHOD__;

			// Because 'userprefix' might produce a huge number of users (e.g.
			// a wiki with users "Test00000001" to "Test99999999"), use a
			// generator with batched lookup and continuation.
			$userIter = call_user_func( function () use ( $dbSecondary, $sort, $op, $fname ) {
				global $wgActorTableSchemaMigrationStage;

				$fromName = false;
				if ( !is_null( $this->params['continue'] ) ) {
					$continue = explode( '|', $this->params['continue'] );
					$this->dieContinueUsageIf( count( $continue ) != 4 );
					$this->dieContinueUsageIf( $continue[0] !== 'name' );
					$fromName = $continue[1];
				}
				$like = $dbSecondary->buildLike( $this->params['userprefix'], $dbSecondary->anyString() );

				$limit = 501;

				do {
					$from = $fromName ? "$op= " . $dbSecondary->addQuotes( $fromName ) : false;

					// For the new schema, pull from the actor table. For the
					// old, pull from rev_user.
					if ( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_READ_NEW ) {
						$res = $dbSecondary->select(
							'actor',
							[ 'actor_id', 'user_id' => 'COALESCE(actor_user,0)', 'user_name' => 'actor_name' ],
							array_merge( [ "actor_name$like" ], $from ? [ "actor_name $from" ] : [] ),
							$fname,
							[ 'ORDER BY' => [ "user_name $sort" ], 'LIMIT' => $limit ]
						);
					} else {
						$res = $dbSecondary->select(
							'revision',
							[ 'actor_id' => 'NULL', 'user_id' => 'rev_user', 'user_name' => 'rev_user_text' ],
							array_merge( [ "rev_user_text$like" ], $from ? [ "rev_user_text $from" ] : [] ),
							$fname,
							[ 'DISTINCT', 'ORDER BY' => [ "rev_user_text $sort" ], 'LIMIT' => $limit ]
						);
					}

					$count = 0;
					$fromName = false;
					foreach ( $res as $row ) {
						if ( ++$count >= $limit ) {
							$fromName = $row->user_name;
							break;
						}
						yield User::newFromRow( $row );
					}
				} while ( $fromName !== false );
			} );
			// Do the actual sorting client-side, because otherwise
			// prepareQuery might try to sort by actor and confuse everything.
			$batchSize = 1;
		} elseif ( isset( $this->params['userids'] ) ) {
			if ( $this->params['userids'] === [] ) {
				$encParamName = $this->encodeParamName( 'userids' );
				$this->dieWithError( [ 'apierror-paramempty', $encParamName ], "paramempty_$encParamName" );
			}

			$ids = [];
			foreach ( $this->params['userids'] as $uid ) {
				if ( $uid <= 0 ) {
					$this->dieWithError( [ 'apierror-invaliduserid', $uid ], 'invaliduserid' );
				}
				$ids[] = $uid;
			}

			$this->orderBy = 'id';
			$this->multiUserMode = count( $ids ) > 1;

			$from = $fromId = false;
			if ( $this->multiUserMode && !is_null( $this->params['continue'] ) ) {
				$continue = explode( '|', $this->params['continue'] );
				$this->dieContinueUsageIf( count( $continue ) != 4 );
				$this->dieContinueUsageIf( $continue[0] !== 'id' && $continue[0] !== 'actor' );
				$fromId = (int)$continue[1];
				$this->dieContinueUsageIf( $continue[1] !== (string)$fromId );
				$from = "$op= $fromId";
			}

			// For the new schema, just select from the actor table. For the
			// old, select from user.
			if ( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_READ_NEW ) {
				$res = $dbSecondary->select(
					'actor',
					[ 'actor_id', 'user_id' => 'actor_user', 'user_name' => 'actor_name' ],
					array_merge( [ 'actor_user' => $ids ], $from ? [ "actor_id $from" ] : [] ),
					__METHOD__,
					[ 'ORDER BY' => "user_id $sort" ]
				);
			} else {
				$res = $dbSecondary->select(
					'user',
					[ 'actor_id' => 'NULL', 'user_id' => 'user_id', 'user_name' => 'user_name' ],
					array_merge( [ 'user_id' => $ids ], $from ? [ "user_id $from" ] : [] ),
					__METHOD__,
					[ 'ORDER BY' => "user_id $sort" ]
				);
			}
			$userIter = UserArray::newFromResult( $res );
			$batchSize = count( $ids );
		} else {
			$names = [];
			if ( !count( $this->params['user'] ) ) {
				$encParamName = $this->encodeParamName( 'user' );
				$this->dieWithError(
					[ 'apierror-paramempty', $encParamName ], "paramempty_$encParamName"
				);
			}
			foreach ( $this->params['user'] as $u ) {
				if ( $u === '' ) {
					$encParamName = $this->encodeParamName( 'user' );
					$this->dieWithError(
						[ 'apierror-paramempty', $encParamName ], "paramempty_$encParamName"
					);
				}

				if ( User::isIP( $u ) || ExternalUserNames::isExternal( $u ) ) {
					$names[$u] = null;
				} else {
					$name = User::getCanonicalName( $u, 'valid' );
					if ( $name === false ) {
						$encParamName = $this->encodeParamName( 'user' );
						$this->dieWithError(
							[ 'apierror-baduser', $encParamName, wfEscapeWikiText( $u ) ], "baduser_$encParamName"
						);
					}
					$names[$name] = null;
				}
			}

			$this->orderBy = 'name';
			$this->multiUserMode = count( $names ) > 1;

			$from = $fromName = false;
			if ( $this->multiUserMode && !is_null( $this->params['continue'] ) ) {
				$continue = explode( '|', $this->params['continue'] );
				$this->dieContinueUsageIf( count( $continue ) != 4 );
				$this->dieContinueUsageIf( $continue[0] !== 'name' && $continue[0] !== 'actor' );
				$fromName = $continue[1];
				$from = "$op= " . $dbSecondary->addQuotes( $fromName );
			}

			// For the new schema, just select from the actor table. For the
			// old, select from user then merge in any unknown users (IPs and imports).
			if ( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_READ_NEW ) {
				$res = $dbSecondary->select(
					'actor',
					[ 'actor_id', 'user_id' => 'actor_user', 'user_name' => 'actor_name' ],
					array_merge( [ 'actor_name' => array_keys( $names ) ], $from ? [ "actor_id $from" ] : [] ),
					__METHOD__,
					[ 'ORDER BY' => "actor_name $sort" ]
				);
				$userIter = UserArray::newFromResult( $res );
			} else {
				$res = $dbSecondary->select(
					'user',
					[ 'actor_id' => 'NULL', 'user_id', 'user_name' ],
					array_merge( [ 'user_name' => array_keys( $names ) ], $from ? [ "user_name $from" ] : [] ),
					__METHOD__
				);
				foreach ( $res as $row ) {
					$names[$row->user_name] = $row;
				}
				if ( $this->params['dir'] == 'newer' ) {
					ksort( $names, SORT_STRING );
				} else {
					krsort( $names, SORT_STRING );
				}
				$neg = $op === '>' ? -1 : 1;
				$userIter = call_user_func( function () use ( $names, $fromName, $neg ) {
					foreach ( $names as $name => $row ) {
						if ( $fromName === false || $neg * strcmp( $name, $fromName ) <= 0 ) {
							$user = $row ? User::newFromRow( $row ) : User::newFromName( $name, false );
							yield $user;
						}
					}
				} );
			}
			$batchSize = count( $names );
		}

		// With the new schema, the DB query will order by actor so update $this->orderBy to match.
		if ( $batchSize > 1 && ( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_READ_NEW ) ) {
			$this->orderBy = 'actor';
		}

		// Use the 'contributions' replica, but only if we're querying by user ID (T216656).
		if ( $this->orderBy === 'id' &&
			!( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_READ_NEW )
		) {
			$this->selectNamedDB( 'contributions', DB_REPLICA, 'contributions' );
		}

		$count = 0;
		$limit = $this->params['limit'];
		$userIter->rewind();
		while ( $userIter->valid() ) {
			$users = [];
			while ( count( $users ) < $batchSize && $userIter->valid() ) {
				$users[] = $userIter->current();
				$userIter->next();
			}

			$hookData = [];
			$this->prepareQuery( $users, $limit - $count );
			$res = $this->select( __METHOD__, [], $hookData );

			if ( $this->fld_sizediff ) {
				$revIds = [];
				foreach ( $res as $row ) {
					if ( $row->rev_parent_id ) {
						$revIds[] = $row->rev_parent_id;
					}
				}
				$this->parentLens = MediaWikiServices::getInstance()->getRevisionStore()
					->listRevisionSizes( $dbSecondary, $revIds );
			}

			foreach ( $res as $row ) {
				if ( ++$count > $limit ) {
					// We've reached the one extra which shows that there are
					// additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue', $this->continueStr( $row ) );
					break 2;
				}

				$vals = $this->extractRowInfo( $row );
				$fit = $this->processRow( $row, $vals, $hookData ) &&
					$this->getResult()->addValue( [ 'query', $this->getModuleName() ], null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', $this->continueStr( $row ) );
					break 2;
				}
			}
		}

		$this->getResult()->addIndexedTagName( [ 'query', $this->getModuleName() ], 'item' );
	}

	/**
	 * Prepares the query and returns the limit of rows requested
	 * @param User[] $users
	 * @param int $limit
	 */
	private function prepareQuery( array $users, $limit ) {
		global $wgActorTableSchemaMigrationStage;

		$this->resetQueryParams();
		$db = $this->getDB();

		$revQuery = MediaWikiServices::getInstance()->getRevisionStore()->getQueryInfo( [ 'page' ] );
		$this->addTables( $revQuery['tables'] );
		$this->addJoinConds( $revQuery['joins'] );
		$this->addFields( $revQuery['fields'] );

		if ( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_READ_NEW ) {
			$revWhere = ActorMigration::newMigration()->getWhere( $db, 'rev_user', $users );
			$orderUserField = 'rev_actor';
			$userField = $this->orderBy === 'actor' ? 'revactor_actor' : 'actor_name';
			$tsField = 'revactor_timestamp';
			$idField = 'revactor_rev';
		} else {
			// If we're dealing with user names (rather than IDs) in read-old mode,
			// pass false for ActorMigration::getWhere()'s $useId parameter so
			// $revWhere['conds'] isn't an OR.
			$revWhere = ActorMigration::newMigration()
				->getWhere( $db, 'rev_user', $users, $this->orderBy === 'id' );
			$orderUserField = $this->orderBy === 'id' ? 'rev_user' : 'rev_user_text';
			$userField = $revQuery['fields'][$orderUserField];
			$tsField = 'rev_timestamp';
			$idField = 'rev_id';
		}

		$this->addWhere( $revWhere['conds'] );

		// Handle continue parameter
		if ( !is_null( $this->params['continue'] ) ) {
			$continue = explode( '|', $this->params['continue'] );
			if ( $this->multiUserMode ) {
				$this->dieContinueUsageIf( count( $continue ) != 4 );
				$modeFlag = array_shift( $continue );
				$this->dieContinueUsageIf( $modeFlag !== $this->orderBy );
				$encUser = $db->addQuotes( array_shift( $continue ) );
			} else {
				$this->dieContinueUsageIf( count( $continue ) != 2 );
			}
			$encTS = $db->addQuotes( $db->timestamp( $continue[0] ) );
			$encId = (int)$continue[1];
			$this->dieContinueUsageIf( $encId != $continue[1] );
			$op = ( $this->params['dir'] == 'older' ? '<' : '>' );
			if ( $this->multiUserMode ) {
				$this->addWhere(
					"$userField $op $encUser OR " .
					"($userField = $encUser AND " .
					"($tsField $op $encTS OR " .
					"($tsField = $encTS AND " .
					"$idField $op= $encId)))"
				);
			} else {
				$this->addWhere(
					"$tsField $op $encTS OR " .
					"($tsField = $encTS AND " .
					"$idField $op= $encId)"
				);
			}
		}

		// Don't include any revisions where we're not supposed to be able to
		// see the username.
		$user = $this->getUser();
		if ( !$user->isAllowed( 'deletedhistory' ) ) {
			$bitmask = RevisionRecord::DELETED_USER;
		} elseif ( !$user->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$bitmask = RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED;
		} else {
			$bitmask = 0;
		}
		if ( $bitmask ) {
			$this->addWhere( $db->bitAnd( 'rev_deleted', $bitmask ) . " != $bitmask" );
		}

		// Add the user field to ORDER BY if there are multiple users
		if ( count( $users ) > 1 ) {
			$this->addWhereRange( $orderUserField, $this->params['dir'], null, null );
		}

		// Then timestamp
		$this->addTimestampWhereRange( $tsField,
			$this->params['dir'], $this->params['start'], $this->params['end'] );

		// Then rev_id for a total ordering
		$this->addWhereRange( $idField, $this->params['dir'], null, null );

		$this->addWhereFld( 'page_namespace', $this->params['namespace'] );

		$show = $this->params['show'];
		if ( $this->params['toponly'] ) { // deprecated/old param
			$show[] = 'top';
		}
		if ( !is_null( $show ) ) {
			$show = array_flip( $show );

			if ( ( isset( $show['minor'] ) && isset( $show['!minor'] ) )
				|| ( isset( $show['patrolled'] ) && isset( $show['!patrolled'] ) )
				|| ( isset( $show['autopatrolled'] ) && isset( $show['!autopatrolled'] ) )
				|| ( isset( $show['autopatrolled'] ) && isset( $show['!patrolled'] ) )
				|| ( isset( $show['top'] ) && isset( $show['!top'] ) )
				|| ( isset( $show['new'] ) && isset( $show['!new'] ) )
			) {
				$this->dieWithError( 'apierror-show' );
			}

			$this->addWhereIf( 'rev_minor_edit = 0', isset( $show['!minor'] ) );
			$this->addWhereIf( 'rev_minor_edit != 0', isset( $show['minor'] ) );
			$this->addWhereIf(
				'rc_patrolled = ' . RecentChange::PRC_UNPATROLLED,
				isset( $show['!patrolled'] )
			);
			$this->addWhereIf(
				'rc_patrolled != ' . RecentChange::PRC_UNPATROLLED,
				isset( $show['patrolled'] )
			);
			$this->addWhereIf(
				'rc_patrolled != ' . RecentChange::PRC_AUTOPATROLLED,
				isset( $show['!autopatrolled'] )
			);
			$this->addWhereIf(
				'rc_patrolled = ' . RecentChange::PRC_AUTOPATROLLED,
				isset( $show['autopatrolled'] )
			);
			$this->addWhereIf( $idField . ' != page_latest', isset( $show['!top'] ) );
			$this->addWhereIf( $idField . ' = page_latest', isset( $show['top'] ) );
			$this->addWhereIf( 'rev_parent_id != 0', isset( $show['!new'] ) );
			$this->addWhereIf( 'rev_parent_id = 0', isset( $show['new'] ) );
		}
		$this->addOption( 'LIMIT', $limit + 1 );

		if ( isset( $show['patrolled'] ) || isset( $show['!patrolled'] ) ||
			isset( $show['autopatrolled'] ) || isset( $show['!autopatrolled'] ) || $this->fld_patrolled
		) {
			if ( !$user->useRCPatrol() && !$user->useNPPatrol() ) {
				$this->dieWithError( 'apierror-permissiondenied-patrolflag', 'permissiondenied' );
			}

			$isFilterset = isset( $show['patrolled'] ) || isset( $show['!patrolled'] ) ||
				isset( $show['autopatrolled'] ) || isset( $show['!autopatrolled'] );
			$this->addTables( 'recentchanges' );
			$this->addJoinConds( [ 'recentchanges' => [
				$isFilterset ? 'JOIN' : 'LEFT JOIN',
				[
					// This is a crazy hack. recentchanges has no index on rc_this_oldid, so instead of adding
					// one T19237 did a join using rc_user_text and rc_timestamp instead. Now rc_user_text is
					// probably unavailable, so just do rc_timestamp.
					'rc_timestamp = ' . $tsField,
					'rc_this_oldid = ' . $idField,
				]
			] ] );
		}

		$this->addFieldsIf( 'rc_patrolled', $this->fld_patrolled );

		if ( $this->fld_tags ) {
			$this->addFields( [ 'ts_tags' => ChangeTags::makeTagSummarySubquery( 'revision' ) ] );
		}

		if ( isset( $this->params['tag'] ) ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds(
				[ 'change_tag' => [ 'JOIN', [ $idField . ' = ct_rev_id' ] ] ]
			);
			$changeTagDefStore = MediaWikiServices::getInstance()->getChangeTagDefStore();
			try {
				$this->addWhereFld( 'ct_tag_id', $changeTagDefStore->getId( $this->params['tag'] ) );
			} catch ( NameTableAccessException $exception ) {
				// Return nothing.
				$this->addWhere( '1=0' );
			}
		}
	}

	/**
	 * Extract fields from the database row and append them to a result array
	 *
	 * @param stdClass $row
	 * @return array
	 */
	private function extractRowInfo( $row ) {
		$vals = [];
		$anyHidden = false;

		if ( $row->rev_deleted & RevisionRecord::DELETED_TEXT ) {
			$vals['texthidden'] = true;
			$anyHidden = true;
		}

		// Any rows where we can't view the user were filtered out in the query.
		$vals['userid'] = (int)$row->rev_user;
		$vals['user'] = $row->rev_user_text;
		if ( $row->rev_deleted & RevisionRecord::DELETED_USER ) {
			$vals['userhidden'] = true;
			$anyHidden = true;
		}
		if ( $this->fld_ids ) {
			$vals['pageid'] = (int)$row->rev_page;
			$vals['revid'] = (int)$row->rev_id;

			if ( !is_null( $row->rev_parent_id ) ) {
				$vals['parentid'] = (int)$row->rev_parent_id;
			}
		}

		$title = Title::makeTitle( $row->page_namespace, $row->page_title );

		if ( $this->fld_title ) {
			ApiQueryBase::addTitleInfo( $vals, $title );
		}

		if ( $this->fld_timestamp ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->rev_timestamp );
		}

		if ( $this->fld_flags ) {
			$vals['new'] = $row->rev_parent_id == 0 && !is_null( $row->rev_parent_id );
			$vals['minor'] = (bool)$row->rev_minor_edit;
			$vals['top'] = $row->page_latest == $row->rev_id;
		}

		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			if ( $row->rev_deleted & RevisionRecord::DELETED_COMMENT ) {
				$vals['commenthidden'] = true;
				$anyHidden = true;
			}

			$userCanView = RevisionRecord::userCanBitfield(
				$row->rev_deleted,
				RevisionRecord::DELETED_COMMENT, $this->getUser()
			);

			if ( $userCanView ) {
				$comment = $this->commentStore->getComment( 'rev_comment', $row )->text;
				if ( $this->fld_comment ) {
					$vals['comment'] = $comment;
				}

				if ( $this->fld_parsedcomment ) {
					$vals['parsedcomment'] = Linker::formatComment( $comment, $title );
				}
			}
		}

		if ( $this->fld_patrolled ) {
			$vals['patrolled'] = $row->rc_patrolled != RecentChange::PRC_UNPATROLLED;
			$vals['autopatrolled'] = $row->rc_patrolled == RecentChange::PRC_AUTOPATROLLED;
		}

		if ( $this->fld_size && !is_null( $row->rev_len ) ) {
			$vals['size'] = (int)$row->rev_len;
		}

		if ( $this->fld_sizediff
			&& !is_null( $row->rev_len )
			&& !is_null( $row->rev_parent_id )
		) {
			$parentLen = $this->parentLens[$row->rev_parent_id] ?? 0;
			$vals['sizediff'] = (int)$row->rev_len - $parentLen;
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

		if ( $anyHidden && ( $row->rev_deleted & RevisionRecord::DELETED_RESTRICTED ) ) {
			$vals['suppressed'] = true;
		}

		return $vals;
	}

	private function continueStr( $row ) {
		if ( $this->multiUserMode ) {
			switch ( $this->orderBy ) {
				case 'id':
					return "id|$row->rev_user|$row->rev_timestamp|$row->rev_id";
				case 'name':
					return "name|$row->rev_user_text|$row->rev_timestamp|$row->rev_id";
				case 'actor':
					return "actor|$row->rev_actor|$row->rev_timestamp|$row->rev_id";
			}
		} else {
			return "$row->rev_timestamp|$row->rev_id";
		}
	}

	public function getCacheMode( $params ) {
		// This module provides access to deleted revisions and patrol flags if
		// the requester is logged in
		return 'anon-public-user-private';
	}

	public function getAllowedParams() {
		return [
			'limit' => [
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'start' => [
				ApiBase::PARAM_TYPE => 'timestamp'
			],
			'end' => [
				ApiBase::PARAM_TYPE => 'timestamp'
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'user' => [
				ApiBase::PARAM_TYPE => 'user',
				ApiBase::PARAM_ISMULTI => true
			],
			'userids' => [
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_ISMULTI => true
			],
			'userprefix' => null,
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
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'ids|title|timestamp|comment|size|flags',
				ApiBase::PARAM_TYPE => [
					'ids',
					'title',
					'timestamp',
					'comment',
					'parsedcomment',
					'size',
					'sizediff',
					'flags',
					'patrolled',
					'tags'
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'show' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					'minor',
					'!minor',
					'patrolled',
					'!patrolled',
					'autopatrolled',
					'!autopatrolled',
					'top',
					'!top',
					'new',
					'!new',
				],
				ApiBase::PARAM_HELP_MSG => [
					'apihelp-query+usercontribs-param-show',
					$this->getConfig()->get( 'RCMaxAge' )
				],
			],
			'tag' => null,
			'toponly' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=usercontribs&ucuser=Example'
				=> 'apihelp-query+usercontribs-example-user',
			'action=query&list=usercontribs&ucuserprefix=192.0.2.'
				=> 'apihelp-query+usercontribs-example-ipprefix',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Usercontribs';
	}
}

/**
 * @since 1.9
 * @deprecated since 1.32
 */
class_alias( ApiQueryUserContribs::class, 'ApiQueryContributions' );
