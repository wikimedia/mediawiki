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
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\NameTableAccessException;

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

	/** @var CommentStore */
	private $commentStore;

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
	 * @return array [ tokenname => function ]
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
			'patrol' => [ self::class, 'getPatrolToken' ]
		];
		$this->getHookRunner()->onAPIQueryRecentChangesTokens( $this->tokenFunctions );

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

			if ( $cachedPatrolToken === null ) {
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
	 * @param ApiPageSet|null $resultPageSet
	 */
	public function run( $resultPageSet = null ) {
		$user = $this->getUser();
		/* Get the parameters of the request. */
		$params = $this->extractRequestParams();

		/* Build our basic query. Namely, something along the lines of:
		 * SELECT * FROM recentchanges WHERE rc_timestamp > $start
		 *   AND rc_timestamp < $end AND rc_namespace = $namespace
		 */
		$this->addTables( 'recentchanges' );
		$this->addTimestampWhereRange( 'rc_timestamp', $params['dir'], $params['start'], $params['end'] );

		if ( $params['continue'] !== null ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 2 );
			$db = $this->getDB();
			$timestamp = $db->addQuotes( $db->timestamp( $cont[0] ) );
			$id = (int)$cont[1];
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

		if ( $params['type'] !== null ) {
			try {
				$this->addWhereFld( 'rc_type', RecentChange::parseToRCType( $params['type'] ) );
			} catch ( Exception $e ) {
				ApiBase::dieDebug( __METHOD__, $e->getMessage() );
			}
		}

		$title = $params['title'];
		if ( $title !== null ) {
			$titleObj = Title::newFromText( $title );
			if ( $titleObj === null ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $title ) ] );
			}
			$this->addWhereFld( 'rc_namespace', $titleObj->getNamespace() );
			$this->addWhereFld( 'rc_title', $titleObj->getDBkey() );
		}

		if ( $params['show'] !== null ) {
			$show = array_flip( $params['show'] );

			/* Check for conflicting parameters. */
			if ( ( isset( $show['minor'] ) && isset( $show['!minor'] ) )
				|| ( isset( $show['bot'] ) && isset( $show['!bot'] ) )
				|| ( isset( $show['anon'] ) && isset( $show['!anon'] ) )
				|| ( isset( $show['redirect'] ) && isset( $show['!redirect'] ) )
				|| ( isset( $show['patrolled'] ) && isset( $show['!patrolled'] ) )
				|| ( isset( $show['patrolled'] ) && isset( $show['unpatrolled'] ) )
				|| ( isset( $show['!patrolled'] ) && isset( $show['unpatrolled'] ) )
				|| ( isset( $show['autopatrolled'] ) && isset( $show['!autopatrolled'] ) )
				|| ( isset( $show['autopatrolled'] ) && isset( $show['unpatrolled'] ) )
				|| ( isset( $show['autopatrolled'] ) && isset( $show['!patrolled'] ) )
			) {
				$this->dieWithError( 'apierror-show' );
			}

			// Check permissions
			if ( $this->includesPatrollingFlags( $show ) ) {
				if ( !$user->useRCPatrol() && !$user->useNPPatrol() ) {
					$this->dieWithError( 'apierror-permissiondenied-patrolflag', 'permissiondenied' );
				}
			}

			/* Add additional conditions to query depending upon parameters. */
			$this->addWhereIf( 'rc_minor = 0', isset( $show['!minor'] ) );
			$this->addWhereIf( 'rc_minor != 0', isset( $show['minor'] ) );
			$this->addWhereIf( 'rc_bot = 0', isset( $show['!bot'] ) );
			$this->addWhereIf( 'rc_bot != 0', isset( $show['bot'] ) );
			if ( isset( $show['anon'] ) || isset( $show['!anon'] ) ) {
				$actorMigration = ActorMigration::newMigration();
				$actorQuery = $actorMigration->getJoin( 'rc_user' );
				$this->addTables( $actorQuery['tables'] );
				$this->addJoinConds( $actorQuery['joins'] );
				$this->addWhereIf(
					$actorMigration->isAnon( $actorQuery['fields']['rc_user'] ), isset( $show['anon'] )
				);
				$this->addWhereIf(
					$actorMigration->isNotAnon( $actorQuery['fields']['rc_user'] ), isset( $show['!anon'] )
				);
			}
			$this->addWhereIf( 'rc_patrolled = 0', isset( $show['!patrolled'] ) );
			$this->addWhereIf( 'rc_patrolled != 0', isset( $show['patrolled'] ) );
			$this->addWhereIf( 'page_is_redirect = 1', isset( $show['redirect'] ) );

			if ( isset( $show['unpatrolled'] ) ) {
				// See ChangesList::isUnpatrolled
				if ( $user->useRCPatrol() ) {
					$this->addWhere( 'rc_patrolled = ' . RecentChange::PRC_UNPATROLLED );
				} elseif ( $user->useNPPatrol() ) {
					$this->addWhere( 'rc_patrolled = ' . RecentChange::PRC_UNPATROLLED );
					$this->addWhereFld( 'rc_type', RC_NEW );
				}
			}

			$this->addWhereIf(
				'rc_patrolled != ' . RecentChange::PRC_AUTOPATROLLED,
				isset( $show['!autopatrolled'] )
			);
			$this->addWhereIf(
				'rc_patrolled = ' . RecentChange::PRC_AUTOPATROLLED,
				isset( $show['autopatrolled'] )
			);

			// Don't throw log entries out the window here
			$this->addWhereIf(
				'page_is_redirect = 0 OR page_is_redirect IS NULL',
				isset( $show['!redirect'] )
			);
		}

		$this->requireMaxOneParameter( $params, 'user', 'excludeuser' );

		if ( $params['user'] !== null ) {
			// Don't query by user ID here, it might be able to use the rc_user_text index.
			$actorQuery = ActorMigration::newMigration()
				->getWhere( $this->getDB(), 'rc_user', $params['user'], false );
			$this->addTables( $actorQuery['tables'] );
			$this->addJoinConds( $actorQuery['joins'] );
			$this->addWhere( $actorQuery['conds'] );
		}

		if ( $params['excludeuser'] !== null ) {
			// Here there's no chance to use the rc_user_text index, so allow ID to be used.
			$actorQuery = ActorMigration::newMigration()
				->getWhere( $this->getDB(), 'rc_user', $params['excludeuser'] );
			$this->addTables( $actorQuery['tables'] );
			$this->addJoinConds( $actorQuery['joins'] );
			$this->addWhere( 'NOT(' . $actorQuery['conds'] . ')' );
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
		if ( $params['prop'] !== null ) {
			$prop = array_flip( $params['prop'] );

			/* Set up internal members based upon params. */
			$this->initProperties( $prop );

			if ( $this->fld_patrolled && !$user->useRCPatrol() && !$user->useNPPatrol() ) {
				$this->dieWithError( 'apierror-permissiondenied-patrolflag', 'permissiondenied' );
			}

			/* Add fields to our query if they are specified as a needed parameter. */
			$this->addFieldsIf( [ 'rc_this_oldid', 'rc_last_oldid' ], $this->fld_ids );
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
			$this->addFields( [ 'ts_tags' => ChangeTags::makeTagSummarySubquery( 'recentchanges' ) ] );
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

		if ( $params['tag'] !== null ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds( [ 'change_tag' => [ 'JOIN', [ 'rc_id=ct_rc_id' ] ] ] );
			$changeTagDefStore = MediaWikiServices::getInstance()->getChangeTagDefStore();
			try {
				$this->addWhereFld( 'ct_tag_id', $changeTagDefStore->getId( $params['tag'] ) );
			} catch ( NameTableAccessException $exception ) {
				// Return nothing.
				$this->addWhere( '1=0' );
			}
		}

		// Paranoia: avoid brute force searches (T19342)
		if ( $params['user'] !== null || $params['excludeuser'] !== null ) {
			if ( !$this->getPermissionManager()->userHasRight( $user, 'deletedhistory' ) ) {
				$bitmask = RevisionRecord::DELETED_USER;
			} elseif ( !$this->getPermissionManager()
				->userHasAnyRight( $user, 'suppressrevision', 'viewsuppressed' )
			) {
				$bitmask = RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED;
			} else {
				$bitmask = 0;
			}
			if ( $bitmask ) {
				$this->addWhere( $this->getDB()->bitAnd( 'rc_deleted', $bitmask ) . " != $bitmask" );
			}
		}
		if ( $this->getRequest()->getCheck( 'namespace' ) ) {
			// LogPage::DELETED_ACTION hides the affected page, too.
			if ( !$this->getPermissionManager()->userHasRight( $user, 'deletedhistory' ) ) {
				$bitmask = LogPage::DELETED_ACTION;
			} elseif ( !$this->getPermissionManager()
				->userHasAnyRight( $user, 'suppressrevision', 'viewsuppressed' )
			) {
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

		if ( $this->fld_comment || $this->fld_parsedcomment || $this->token ) {
			$this->commentStore = CommentStore::getStore();
			$commentQuery = $this->commentStore->getJoin( 'rc_comment' );
			$this->addTables( $commentQuery['tables'] );
			$this->addFields( $commentQuery['fields'] );
			$this->addJoinConds( $commentQuery['joins'] );
		}

		if ( $this->fld_user || $this->fld_userid || $this->token !== null ) {
			// Token needs rc_user for RecentChange::newFromRow/User::newFromAnyId (T228425)
			$actorQuery = ActorMigration::newMigration()->getJoin( 'rc_user' );
			$this->addTables( $actorQuery['tables'] );
			$this->addFields( $actorQuery['fields'] );
			$this->addJoinConds( $actorQuery['joins'] );
		}

		if ( $params['slot'] !== null ) {
			try {
				$slotId = MediaWikiServices::getInstance()->getSlotRoleStore()->getId(
					$params['slot']
				);
			} catch ( \Exception $e ) {
				$slotId = null;
			}

			$this->addTables( [
				'slot' => 'slots', 'parent_slot' => 'slots'
			] );
			$this->addJoinConds( [
				'slot' => [ 'LEFT JOIN', [
					'rc_this_oldid = slot.slot_revision_id',
					'slot.slot_role_id' => $slotId,
				] ],
				'parent_slot' => [ 'LEFT JOIN', [
					'rc_last_oldid = parent_slot.slot_revision_id',
					'parent_slot.slot_role_id' => $slotId,
				] ]
			] );
			// Detecting whether the slot has been touched as follows:
			// 1. if slot_origin=slot_revision_id then the slot has been newly created or edited
			// with this revision
			// 2. otherwise if the content of a slot is different to the content of its parent slot,
			// then the content of the slot has been changed in this revision
			// (probably by a revert)
			$this->addWhere(
				'slot.slot_origin = slot.slot_revision_id OR ' .
				'slot.slot_content_id != parent_slot.slot_content_id OR ' .
				'(slot.slot_content_id IS NULL AND parent_slot.slot_content_id IS NOT NULL) OR ' .
				'(slot.slot_content_id IS NOT NULL AND parent_slot.slot_content_id IS NULL)'
			);
			// Only include changes that touch page content (i.e. RC_NEW, RC_EDIT)
			$changeTypes = RecentChange::parseToRCType(
				array_intersect( $params['type'], [ 'new', 'edit' ] )
			);
			if ( count( $changeTypes ) ) {
				$this->addWhereFld( 'rc_type', $changeTypes );
			} else {
				// Calling $this->addWhere() with an empty array does nothing, so explicitly
				// add an unsatisfiable condition
				$this->addWhere( 'rc_type IS NULL' );
			}
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );

		$hookData = [];
		$count = 0;
		/* Perform the actual query. */
		$res = $this->select( __METHOD__, [], $hookData );

		if ( $this->fld_title && $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__, 'rc' );
		}

		$revids = [];
		$titles = [];

		$result = $this->getResult();

		/* Iterate through the rows, adding data extracted from them to our query result. */
		foreach ( $res as $row ) {
			if ( $count === 0 && $resultPageSet !== null ) {
				// Set the non-continue since the list of recentchanges is
				// prone to having entries added at the start frequently.
				$this->getContinuationManager()->addGeneratorNonContinueParam(
					$this, 'continue', "$row->rc_timestamp|$row->rc_id"
				);
			}
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', "$row->rc_timestamp|$row->rc_id" );
				break;
			}

			if ( $resultPageSet === null ) {
				/* Extract the data from a single row. */
				$vals = $this->extractRowInfo( $row );

				/* Add that row's data to our final output. */
				$fit = $this->processRow( $row, $vals, $hookData ) &&
					$result->addValue( [ 'query', $this->getModuleName() ], null, $vals );
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

		if ( $resultPageSet === null ) {
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
	 */
	public function extractRowInfo( $row ) {
		/* Determine the title of the page that has been changed. */
		$title = Title::makeTitle( $row->rc_namespace, $row->rc_title );
		$user = $this->getUser();

		/* Our output data. */
		$vals = [];

		$type = (int)$row->rc_type;
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
					$vals['pageid'] = (int)$row->rc_cur_id;
					$vals['revid'] = (int)$row->rc_this_oldid;
					$vals['old_revid'] = (int)$row->rc_last_oldid;
				}
			}
		}

		if ( $this->fld_ids ) {
			$vals['rcid'] = (int)$row->rc_id;
		}

		/* Add user data and 'anon' flag, if user is anonymous. */
		if ( $this->fld_user || $this->fld_userid ) {
			if ( $row->rc_deleted & RevisionRecord::DELETED_USER ) {
				$vals['userhidden'] = true;
				$anyHidden = true;
			}
			if ( RevisionRecord::userCanBitfield( $row->rc_deleted, RevisionRecord::DELETED_USER, $user ) ) {
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
			$vals['oldlen'] = (int)$row->rc_old_len;
			$vals['newlen'] = (int)$row->rc_new_len;
		}

		/* Add the timestamp. */
		if ( $this->fld_timestamp ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->rc_timestamp );
		}

		/* Add edit summary / log summary. */
		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			if ( $row->rc_deleted & RevisionRecord::DELETED_COMMENT ) {
				$vals['commenthidden'] = true;
				$anyHidden = true;
			}
			if ( RevisionRecord::userCanBitfield(
				$row->rc_deleted, RevisionRecord::DELETED_COMMENT, $user
			) ) {
				$comment = $this->commentStore->getComment( 'rc_comment', $row )->text;
				if ( $this->fld_comment ) {
					$vals['comment'] = $comment;
				}

				if ( $this->fld_parsedcomment ) {
					$vals['parsedcomment'] = Linker::formatComment( $comment, $title );
				}
			}
		}

		if ( $this->fld_redirect ) {
			$vals['redirect'] = (bool)$row->page_is_redirect;
		}

		/* Add the patrolled flag */
		if ( $this->fld_patrolled ) {
			$vals['patrolled'] = $row->rc_patrolled != RecentChange::PRC_UNPATROLLED;
			$vals['unpatrolled'] = ChangesList::isUnpatrolled( $row, $user );
			$vals['autopatrolled'] = $row->rc_patrolled == RecentChange::PRC_AUTOPATROLLED;
		}

		if ( $this->fld_loginfo && $row->rc_type == RC_LOG ) {
			if ( $row->rc_deleted & LogPage::DELETED_ACTION ) {
				$vals['actionhidden'] = true;
				$anyHidden = true;
			}
			if ( LogEventsList::userCanBitfield( $row->rc_deleted, LogPage::DELETED_ACTION, $user ) ) {
				$vals['logid'] = (int)$row->rc_logid;
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
			if ( $row->rev_deleted & RevisionRecord::DELETED_TEXT ) {
				$vals['sha1hidden'] = true;
				$anyHidden = true;
			}
			if ( RevisionRecord::userCanBitfield(
				$row->rev_deleted, RevisionRecord::DELETED_TEXT, $user
			) ) {
				if ( $row->rev_sha1 !== '' ) {
					$vals['sha1'] = Wikimedia\base_convert( $row->rev_sha1, 36, 16, 40 );
				} else {
					$vals['sha1'] = '';
				}
			}
		}

		if ( $this->token !== null ) {
			$tokenFunctions = $this->getTokenFunctions();
			foreach ( $this->token as $t ) {
				$val = call_user_func( $tokenFunctions[$t], $row->rc_cur_id,
					$title, RecentChange::newFromRow( $row ) );
				if ( $val === false ) {
					$this->addWarning( [ 'apiwarn-tokennotallowed', $t ] );
				} else {
					$vals[$t . 'token'] = $val;
				}
			}
		}

		if ( $anyHidden && ( $row->rc_deleted & RevisionRecord::DELETED_RESTRICTED ) ) {
			$vals['suppressed'] = true;
		}

		return $vals;
	}

	/**
	 * @param array $flagsArray flipped array (string flags are keys)
	 * @return bool
	 */
	private function includesPatrollingFlags( array $flagsArray ) {
		return isset( $flagsArray['patrolled'] ) ||
			isset( $flagsArray['!patrolled'] ) ||
			isset( $flagsArray['unpatrolled'] ) ||
			isset( $flagsArray['autopatrolled'] ) ||
			isset( $flagsArray['!autopatrolled'] );
	}

	public function getCacheMode( $params ) {
		if ( isset( $params['show'] ) &&
			$this->includesPatrollingFlags( array_flip( $params['show'] ) )
		) {
			return 'private';
		}
		if ( isset( $params['token'] ) ) {
			return 'private';
		}
		if ( $this->userCanSeeRevDel() ) {
			return 'private';
		}
		if ( $params['prop'] !== null && in_array( 'parsedcomment', $params['prop'] ) ) {
			// formatComment() calls wfMessage() among other things
			return 'anon-public-user-private';
		}

		return 'public';
	}

	public function getAllowedParams() {
		$slotRoles = MediaWikiServices::getInstance()->getSlotRoleRegistry()->getKnownRoles();
		sort( $slotRoles, SORT_STRING );

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
				ApiBase::PARAM_TYPE => 'namespace',
				ApiBase::PARAM_EXTRA_NAMESPACES => [ NS_MEDIA, NS_SPECIAL ],
			],
			'user' => [
				ApiBase::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'id', 'interwiki' ],
				UserDef::PARAM_RETURN_OBJECT => true,
			],
			'excludeuser' => [
				ApiBase::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'id', 'interwiki' ],
				UserDef::PARAM_RETURN_OBJECT => true,
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
					'unpatrolled',
					'autopatrolled',
					'!autopatrolled',
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
			'title' => null,
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'generaterevisions' => false,
			'slot' => [
				ApiBase::PARAM_TYPE => $slotRoles
			],
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
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Recentchanges';
	}
}
