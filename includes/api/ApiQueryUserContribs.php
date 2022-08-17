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

use MediaWiki\MainConfigNames;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserNameUtils;
use Wikimedia\IPUtils;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * This query action adds a list of a specified user's contributions to the output.
 *
 * @ingroup API
 */
class ApiQueryUserContribs extends ApiQueryBase {

	/** @var CommentStore */
	private $commentStore;

	/** @var UserIdentityLookup */
	private $userIdentityLookup;

	/** @var UserNameUtils */
	private $userNameUtils;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var NameTableStore */
	private $changeTagDefStore;

	/** @var ActorMigration */
	private $actorMigration;

	/**
	 * @param ApiQuery $query
	 * @param string $moduleName
	 * @param CommentStore $commentStore
	 * @param UserIdentityLookup $userIdentityLookup
	 * @param UserNameUtils $userNameUtils
	 * @param RevisionStore $revisionStore
	 * @param NameTableStore $changeTagDefStore
	 * @param ActorMigration $actorMigration
	 */
	public function __construct(
		ApiQuery $query,
		$moduleName,
		CommentStore $commentStore,
		UserIdentityLookup $userIdentityLookup,
		UserNameUtils $userNameUtils,
		RevisionStore $revisionStore,
		NameTableStore $changeTagDefStore,
		ActorMigration $actorMigration
	) {
		parent::__construct( $query, $moduleName, 'uc' );
		$this->commentStore = $commentStore;
		$this->userIdentityLookup = $userIdentityLookup;
		$this->userNameUtils = $userNameUtils;
		$this->revisionStore = $revisionStore;
		$this->changeTagDefStore = $changeTagDefStore;
		$this->actorMigration = $actorMigration;
	}

	private $params, $multiUserMode, $orderBy, $parentLens;

	private $fld_ids = false, $fld_title = false, $fld_timestamp = false,
		$fld_comment = false, $fld_parsedcomment = false, $fld_flags = false,
		$fld_patrolled = false, $fld_tags = false, $fld_size = false, $fld_sizediff = false;

	public function execute() {
		// Parse some parameters
		$this->params = $this->extractRequestParams();

		$prop = array_fill_keys( $this->params['prop'], true );
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
		// with extra user based indexes or partitioning by user. The additional metadata
		// queries should use a regular replica DB since the lookup pattern is not all by user.
		$dbSecondary = $this->getDB(); // any random replica DB

		$sort = ( $this->params['dir'] == 'newer' ?
			SelectQueryBuilder::SORT_ASC : SelectQueryBuilder::SORT_DESC );
		$op = ( $this->params['dir'] == 'older' ? '<' : '>' );

		// Create an Iterator that produces the UserIdentity objects we need, depending
		// on which of the 'userprefix', 'userids', 'iprange', or 'user' params
		// was specified.
		$this->requireOnlyOneParameter( $this->params, 'userprefix', 'userids', 'iprange', 'user' );
		if ( isset( $this->params['userprefix'] ) ) {
			$this->multiUserMode = true;
			$this->orderBy = 'name';
			$fname = __METHOD__;

			// Because 'userprefix' might produce a huge number of users (e.g.
			// a wiki with users "Test00000001" to "Test99999999"), use a
			// generator with batched lookup and continuation.
			$userIter = call_user_func( function () use ( $dbSecondary, $sort, $op, $fname ) {
				$fromName = false;
				if ( $this->params['continue'] !== null ) {
					$continue = explode( '|', $this->params['continue'] );
					$this->dieContinueUsageIf( count( $continue ) != 4 );
					$this->dieContinueUsageIf( $continue[0] !== 'name' );
					$fromName = $continue[1];
				}

				$limit = 501;
				do {
					$from = $fromName ? "$op= " . $dbSecondary->addQuotes( $fromName ) : false;
					$usersBatch = $this->userIdentityLookup
						->newSelectQueryBuilder()
						->caller( $fname )
						->limit( $limit )
						->whereUserNamePrefix( $this->params['userprefix'] )
						->where( $from ? [ "actor_name $from" ] : [] )
						->orderByName( $sort )
						->fetchUserIdentities();

					$count = 0;
					$fromName = false;
					foreach ( $usersBatch as $user ) {
						if ( ++$count >= $limit ) {
							$fromName = $user->getName();
							break;
						}
						yield $user;
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
			if ( $this->multiUserMode && $this->params['continue'] !== null ) {
				$continue = explode( '|', $this->params['continue'] );
				$this->dieContinueUsageIf( count( $continue ) != 4 );
				$this->dieContinueUsageIf( $continue[0] !== 'id' && $continue[0] !== 'actor' );
				$fromId = (int)$continue[1];
				$this->dieContinueUsageIf( $continue[1] !== (string)$fromId );
				$from = "$op= $fromId";
			}

			$userIter = $this->userIdentityLookup
				->newSelectQueryBuilder()
				->caller( __METHOD__ )
				->whereUserIds( $ids )
				->orderByUserId( $sort )
				->where( $from ? [ "actor_id $from" ] : [] )
				->fetchUserIdentities();
			$batchSize = count( $ids );
		} elseif ( isset( $this->params['iprange'] ) ) {
			// Make sure it is a valid range and within the CIDR limit
			$ipRange = $this->params['iprange'];
			$contribsCIDRLimit = $this->getConfig()->get( MainConfigNames::RangeContributionsCIDRLimit );
			if ( IPUtils::isIPv4( $ipRange ) ) {
				$type = 'IPv4';
				$cidrLimit = $contribsCIDRLimit['IPv4'];
			} elseif ( IPUtils::isIPv6( $ipRange ) ) {
				$type = 'IPv6';
				$cidrLimit = $contribsCIDRLimit['IPv6'];
			} else {
				$this->dieWithError( [ 'apierror-invalidiprange', $ipRange ], 'invalidiprange' );
			}
			$range = IPUtils::parseCIDR( $ipRange )[1];
			if ( $range === false ) {
				$this->dieWithError( [ 'apierror-invalidiprange', $ipRange ], 'invalidiprange' );
			} elseif ( $range < $cidrLimit ) {
				$this->dieWithError( [ 'apierror-cidrtoobroad', $type, $cidrLimit ] );
			}

			$this->multiUserMode = true;
			$this->orderBy = 'name';
			$fname = __METHOD__;

			// Because 'iprange' might produce a huge number of ips, use a
			// generator with batched lookup and continuation.
			$userIter = call_user_func( function () use ( $dbSecondary, $sort, $op, $fname, $ipRange ) {
				$fromName = false;
				list( $start, $end ) = IPUtils::parseRange( $ipRange );
				if ( $this->params['continue'] !== null ) {
					$continue = explode( '|', $this->params['continue'] );
					$this->dieContinueUsageIf( count( $continue ) != 4 );
					$this->dieContinueUsageIf( $continue[0] !== 'name' );
					$fromName = $continue[1];
					$fromIPHex = IPUtils::toHex( $fromName );
					$this->dieContinueUsageIf( $fromIPHex === false );
					if ( $op == '<' ) {
						$end = $fromIPHex;
					} else {
						$start = $fromIPHex;
					}
				}

				$limit = 501;

				do {
					$res = $dbSecondary->newSelectQueryBuilder()
						->select( 'ipc_hex' )
						->from( 'ip_changes' )
						->where( [ 'ipc_hex BETWEEN ' . $dbSecondary->addQuotes( $start ) .
							' AND ' . $dbSecondary->addQuotes( $end )
						] )
						->groupBy( 'ipc_hex' )
						->orderBy( 'ipc_hex', $sort )
						->limit( $limit )
						->caller( $fname )
						->fetchResultSet();

					$count = 0;
					$fromName = false;
					foreach ( $res as $row ) {
						$ipAddr = IPUtils::formatHex( $row->ipc_hex );
						if ( ++$count >= $limit ) {
							$fromName = $ipAddr;
							break;
						}
						yield User::newFromName( $ipAddr, false );
					}
				} while ( $fromName !== false );
			} );
			// Do the actual sorting client-side, because otherwise
			// prepareQuery might try to sort by actor and confuse everything.
			$batchSize = 1;
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

				if ( $this->userNameUtils->isIP( $u ) || ExternalUserNames::isExternal( $u ) ) {
					$names[$u] = null;
				} else {
					$name = $this->userNameUtils->getCanonical( $u );
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
			if ( $this->multiUserMode && $this->params['continue'] !== null ) {
				$continue = explode( '|', $this->params['continue'] );
				$this->dieContinueUsageIf( count( $continue ) != 4 );
				$this->dieContinueUsageIf( $continue[0] !== 'name' && $continue[0] !== 'actor' );
				$fromName = $continue[1];
				$from = "$op= " . $dbSecondary->addQuotes( $fromName );
			}

			$userIter = $this->userIdentityLookup
				->newSelectQueryBuilder()
				->caller( __METHOD__ )
				->whereUserNames( array_keys( $names ) )
				->where( $from ? [ "actor_id $from" ] : [] )
				->orderByName( $sort )
				->fetchUserIdentities();
			$batchSize = count( $names );
		}

		// The DB query will order by actor so update $this->orderBy to match.
		if ( $batchSize > 1 ) {
			$this->orderBy = 'actor';
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

			if ( $this->fld_title ) {
				$this->executeGenderCacheFromResultWrapper( $res, __METHOD__ );
			}

			if ( $this->fld_sizediff ) {
				$revIds = [];
				foreach ( $res as $row ) {
					if ( $row->rev_parent_id ) {
						$revIds[] = (int)$row->rev_parent_id;
					}
				}
				$this->parentLens = $this->revisionStore->getRevisionSizes( $revIds );
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
	 * @param UserIdentity[] $users
	 * @param int $limit
	 */
	private function prepareQuery( array $users, $limit ) {
		$this->resetQueryParams();
		$db = $this->getDB();

		$revQuery = $this->revisionStore->getQueryInfo( [ 'page' ] );
		$revWhere = $this->actorMigration->getWhere( $db, 'rev_user', $users );

		$orderUserField = 'rev_actor';
		$userField = $this->orderBy === 'actor' ? 'rev_actor' : 'actor_name';
		$tsField = 'rev_timestamp';
		$idField = 'rev_id';

		$this->addTables( $revQuery['tables'] );
		$this->addJoinConds( $revQuery['joins'] );
		$this->addFields( $revQuery['fields'] );
		$this->addWhere( $revWhere['conds'] );

		// Handle continue parameter
		if ( $this->params['continue'] !== null ) {
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
					// @phan-suppress-next-line PhanPossiblyUndeclaredVariable encUser is set when used
					"$userField $op $encUser OR " .
					// @phan-suppress-next-line PhanPossiblyUndeclaredVariable encUser is set when used
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
		if ( !$this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
			$bitmask = RevisionRecord::DELETED_USER;
		} elseif ( !$this->getAuthority()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
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
		if ( $show !== null ) {
			$show = array_fill_keys( $show, true );

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
			$user = $this->getUser();
			if ( !$user->useRCPatrol() && !$user->useNPPatrol() ) {
				$this->dieWithError( 'apierror-permissiondenied-patrolflag', 'permissiondenied' );
			}

			$isFilterset = isset( $show['patrolled'] ) || isset( $show['!patrolled'] ) ||
				isset( $show['autopatrolled'] ) || isset( $show['!autopatrolled'] );
			$this->addTables( 'recentchanges' );
			$this->addJoinConds( [ 'recentchanges' => [
				$isFilterset ? 'JOIN' : 'LEFT JOIN',
				[ 'rc_this_oldid = ' . $idField ]
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
			try {
				$this->addWhereFld( 'ct_tag_id', $this->changeTagDefStore->getId( $this->params['tag'] ) );
			} catch ( NameTableAccessException $exception ) {
				// Return nothing.
				$this->addWhere( '1=0' );
			}
		}
		$this->addOption(
			'MAX_EXECUTION_TIME',
			$this->getConfig()->get( MainConfigNames::MaxExecutionTimeForExpensiveQueries )
		);
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

			if ( $row->rev_parent_id !== null ) {
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
			$vals['new'] = $row->rev_parent_id == 0 && $row->rev_parent_id !== null;
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
				RevisionRecord::DELETED_COMMENT, $this->getAuthority()
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

		if ( $this->fld_size && $row->rev_len !== null ) {
			$vals['size'] = (int)$row->rev_len;
		}

		if ( $this->fld_sizediff
			&& $row->rev_len !== null
			&& $row->rev_parent_id !== null
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
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'start' => [
				ParamValidator::PARAM_TYPE => 'timestamp'
			],
			'end' => [
				ParamValidator::PARAM_TYPE => 'timestamp'
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'interwiki' ],
				ParamValidator::PARAM_ISMULTI => true
			],
			'userids' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_ISMULTI => true
			],
			'userprefix' => null,
			'iprange' => null,
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'older',
				ParamValidator::PARAM_TYPE => [
					'newer',
					'older'
				],
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
			],
			'namespace' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'namespace'
			],
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => 'ids|title|timestamp|comment|size|flags',
				ParamValidator::PARAM_TYPE => [
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
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => [
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
					$this->getConfig()->get( MainConfigNames::RCMaxAge )
				],
			],
			'tag' => null,
			'toponly' => [
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_DEPRECATED => true,
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
