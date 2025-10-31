<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\MainConfigNames;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Title\Title;
use MediaWiki\User\ActorMigration;
use MediaWiki\User\ExternalUserNames;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserNameUtils;
use stdClass;
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

	private CommentStore $commentStore;
	private UserIdentityLookup $userIdentityLookup;
	private UserNameUtils $userNameUtils;
	private RevisionStore $revisionStore;
	private NameTableStore $changeTagDefStore;
	private ChangeTagsStore $changeTagsStore;
	private ActorMigration $actorMigration;
	private CommentFormatter $commentFormatter;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		CommentStore $commentStore,
		UserIdentityLookup $userIdentityLookup,
		UserNameUtils $userNameUtils,
		RevisionStore $revisionStore,
		NameTableStore $changeTagDefStore,
		ChangeTagsStore $changeTagsStore,
		ActorMigration $actorMigration,
		CommentFormatter $commentFormatter
	) {
		parent::__construct( $query, $moduleName, 'uc' );
		$this->commentStore = $commentStore;
		$this->userIdentityLookup = $userIdentityLookup;
		$this->userNameUtils = $userNameUtils;
		$this->revisionStore = $revisionStore;
		$this->changeTagDefStore = $changeTagDefStore;
		$this->changeTagsStore = $changeTagsStore;
		$this->actorMigration = $actorMigration;
		$this->commentFormatter = $commentFormatter;
	}

	private array $params;
	private bool $multiUserMode;
	private string $orderBy;
	private array $parentLens;

	private bool $fld_ids = false;
	private bool $fld_title = false;
	private bool $fld_timestamp = false;
	private bool $fld_comment = false;
	private bool $fld_parsedcomment = false;
	private bool $fld_flags = false;
	private bool $fld_patrolled = false;
	private bool $fld_tags = false;
	private bool $fld_size = false;
	private bool $fld_sizediff = false;

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

		$dbSecondary = $this->getDB(); // any random replica DB

		$sort = ( $this->params['dir'] == 'newer' ?
			SelectQueryBuilder::SORT_ASC : SelectQueryBuilder::SORT_DESC );
		$op = ( $this->params['dir'] == 'older' ? '<=' : '>=' );

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
					$continue = $this->parseContinueParamOrDie( $this->params['continue'],
						[ 'string', 'string', 'string', 'int' ] );
					$this->dieContinueUsageIf( $continue[0] !== 'name' );
					$fromName = $continue[1];
				}

				$limit = 501;
				do {
					$usersBatch = $this->userIdentityLookup
						->newSelectQueryBuilder()
						->caller( $fname )
						->limit( $limit )
						->whereUserNamePrefix( $this->params['userprefix'] )
						->where( $fromName !== false
							? $dbSecondary->buildComparison( $op, [ 'actor_name' => $fromName ] )
							: [] )
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

			$this->orderBy = 'actor';
			$this->multiUserMode = count( $ids ) > 1;

			$fromId = false;
			if ( $this->multiUserMode && $this->params['continue'] !== null ) {
				$continue = $this->parseContinueParamOrDie( $this->params['continue'],
					[ 'string', 'int', 'string', 'int' ] );
				$this->dieContinueUsageIf( $continue[0] !== 'actor' );
				$fromId = $continue[1];
			}

			$userIter = $this->userIdentityLookup
				->newSelectQueryBuilder()
				->caller( __METHOD__ )
				->whereUserIds( $ids )
				->orderByUserId( $sort )
				->where( $fromId ? $dbSecondary->buildComparison( $op, [ 'actor_id' => $fromId ] ) : [] )
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
				[ $start, $end ] = IPUtils::parseRange( $ipRange );
				if ( $this->params['continue'] !== null ) {
					$continue = $this->parseContinueParamOrDie( $this->params['continue'],
						[ 'string', 'string', 'string', 'int' ] );
					$this->dieContinueUsageIf( $continue[0] !== 'name' );
					$fromName = $continue[1];
					$fromIPHex = IPUtils::toHex( $fromName );
					$this->dieContinueUsageIf( $fromIPHex === false );
					if ( $op == '<=' ) {
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
						->where( $dbSecondary->expr( 'ipc_hex', '>=', $start )->and( 'ipc_hex', '<=', $end ) )
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
						yield UserIdentityValue::newAnonymous( $ipAddr );
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

			$this->orderBy = 'actor';
			$this->multiUserMode = count( $names ) > 1;

			$fromId = false;
			if ( $this->multiUserMode && $this->params['continue'] !== null ) {
				$continue = $this->parseContinueParamOrDie( $this->params['continue'],
					[ 'string', 'int', 'string', 'int' ] );
				$this->dieContinueUsageIf( $continue[0] !== 'actor' );
				$fromId = $continue[1];
			}

			$userIter = $this->userIdentityLookup
				->newSelectQueryBuilder()
				->caller( __METHOD__ )
				->whereUserNames( array_keys( $names ) )
				->orderByName( $sort )
				->where( $fromId ? $dbSecondary->buildComparison( $op, [ 'actor_id' => $fromId ] ) : [] )
				->fetchUserIdentities();
			$batchSize = count( $names );
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

		$queryBuilder = $this->revisionStore->newSelectQueryBuilder( $db )->joinComment()->joinPage();
		$revWhere = $this->actorMigration->getWhere( $db, 'rev_user', $users );

		$orderUserField = 'rev_actor';
		$userField = $this->orderBy === 'actor' ? 'rev_actor' : 'actor_name';
		$tsField = 'rev_timestamp';
		$idField = 'rev_id';

		$this->getQueryBuilder()->merge( $queryBuilder );
		$this->addWhere( $revWhere['conds'] );
		// Force the appropriate index to avoid bad query plans (T307815 and T307295)
		if ( isset( $revWhere['orconds']['newactor'] ) ) {
			$this->addOption( 'USE INDEX', [ 'revision' => 'rev_actor_timestamp' ] );
		}

		// Handle continue parameter
		if ( $this->params['continue'] !== null ) {
			if ( $this->multiUserMode ) {
				$continue = $this->parseContinueParamOrDie( $this->params['continue'],
					[ 'string', 'string', 'timestamp', 'int' ] );
				$modeFlag = array_shift( $continue );
				$this->dieContinueUsageIf( $modeFlag !== $this->orderBy );
				$encUser = array_shift( $continue );
			} else {
				$continue = $this->parseContinueParamOrDie( $this->params['continue'],
					[ 'timestamp', 'int' ] );
			}
			$op = ( $this->params['dir'] == 'older' ? '<=' : '>=' );
			if ( $this->multiUserMode ) {
				$this->addWhere( $db->buildComparison( $op, [
					// @phan-suppress-next-line PhanPossiblyUndeclaredVariable encUser is set when used
					$userField => $encUser,
					$tsField => $db->timestamp( $continue[0] ),
					$idField => $continue[1],
				] ) );
			} else {
				$this->addWhere( $db->buildComparison( $op, [
					$tsField => $db->timestamp( $continue[0] ),
					$idField => $continue[1],
				] ) );
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

			$this->addWhereIf( [ 'rev_minor_edit' => 0 ], isset( $show['!minor'] ) );
			$this->addWhereIf( $db->expr( 'rev_minor_edit', '!=', 0 ), isset( $show['minor'] ) );
			$this->addWhereIf(
				[ 'rc_patrolled' => RecentChange::PRC_UNPATROLLED ],
				isset( $show['!patrolled'] )
			);
			$this->addWhereIf(
				$db->expr( 'rc_patrolled', '!=', RecentChange::PRC_UNPATROLLED ),
				isset( $show['patrolled'] )
			);
			$this->addWhereIf(
				$db->expr( 'rc_patrolled', '!=', RecentChange::PRC_AUTOPATROLLED ),
				isset( $show['!autopatrolled'] )
			);
			$this->addWhereIf(
				[ 'rc_patrolled' => RecentChange::PRC_AUTOPATROLLED ],
				isset( $show['autopatrolled'] )
			);
			$this->addWhereIf( $idField . ' != page_latest', isset( $show['!top'] ) );
			$this->addWhereIf( $idField . ' = page_latest', isset( $show['top'] ) );
			$this->addWhereIf( $db->expr( 'rev_parent_id', '!=', 0 ), isset( $show['!new'] ) );
			$this->addWhereIf( [ 'rev_parent_id' => 0 ], isset( $show['new'] ) );
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
			$this->addFields( [
				'ts_tags' => $this->changeTagsStore->makeTagSummarySubquery( 'revision' )
			] );
		}

		if ( isset( $this->params['tag'] ) ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds(
				[ 'change_tag' => [ 'JOIN', [ $idField . ' = ct_rev_id' ] ] ]
			);
			try {
				$this->addWhereFld( 'ct_tag_id', $this->changeTagDefStore->getId( $this->params['tag'] ) );
			} catch ( NameTableAccessException ) {
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
					$vals['parsedcomment'] = $this->commentFormatter->format( $comment, $title );
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

	private function continueStr( \stdClass $row ): string {
		if ( $this->multiUserMode ) {
			switch ( $this->orderBy ) {
				case 'name':
					return "name|$row->rev_user_text|$row->rev_timestamp|$row->rev_id";
				case 'actor':
					return "actor|$row->rev_actor|$row->rev_timestamp|$row->rev_id";
			}
		} else {
			return "$row->rev_timestamp|$row->rev_id";
		}
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		// This module provides access to deleted revisions and patrol flags if
		// the requester is logged in
		return 'anon-public-user-private';
	}

	/** @inheritDoc */
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
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'interwiki' ],
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
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'newer' => 'api-help-paramvalue-direction-newer',
					'older' => 'api-help-paramvalue-direction-older',
				],
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

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=usercontribs&ucuser=Example'
				=> 'apihelp-query+usercontribs-example-user',
			'action=query&list=usercontribs&ucuserprefix=192.0.2.'
				=> 'apihelp-query+usercontribs-example-ipprefix',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Usercontribs';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryUserContribs::class, 'ApiQueryUserContribs' );
