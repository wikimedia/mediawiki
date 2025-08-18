<?php
/**
 * Rebuild recent changes from scratch.  This takes several hours,
 * depending on the database size and server configuration.
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
 * @ingroup Maintenance
 * @todo Document
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Logging\LogPage;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\RecentChanges\RecentChange;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Maintenance script that rebuilds recent changes from scratch.
 *
 * @ingroup Maintenance
 */
class RebuildRecentchanges extends Maintenance {
	/** @var int UNIX timestamp */
	private $cutoffFrom;
	/** @var int UNIX timestamp */
	private $cutoffTo;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Rebuild recent changes' );

		$this->addOption(
			'from',
			"Only rebuild rows in requested time range (in YYYYMMDDHHMMSS format)",
			false,
			true
		);
		$this->addOption(
			'to',
			"Only rebuild rows in requested time range (in YYYYMMDDHHMMSS format)",
			false,
			true
		);
		$this->setBatchSize( 200 );
	}

	public function execute() {
		if (
			( $this->hasOption( 'from' ) && !$this->hasOption( 'to' ) ) ||
			( !$this->hasOption( 'from' ) && $this->hasOption( 'to' ) )
		) {
			$this->fatalError( "Both 'from' and 'to' must be given, or neither" );
		}

		$this->rebuildRecentChangesTablePass1();
		$this->rebuildRecentChangesTablePass2();
		$this->rebuildRecentChangesTablePass3();
		$this->rebuildRecentChangesTablePass4();
		$this->rebuildRecentChangesTablePass5();
		if ( !( $this->hasOption( 'from' ) && $this->hasOption( 'to' ) ) ) {
			$this->purgeFeeds();
		}
		$this->output( "Done.\n" );
	}

	/**
	 * Rebuild pass 1: Insert `recentchanges` entries for page revisions.
	 */
	private function rebuildRecentChangesTablePass1() {
		$dbw = $this->getPrimaryDB();
		$commentStore = $this->getServiceContainer()->getCommentStore();

		if ( $this->hasOption( 'from' ) && $this->hasOption( 'to' ) ) {
			$this->cutoffFrom = (int)wfTimestamp( TS_UNIX, $this->getOption( 'from' ) );
			$this->cutoffTo = (int)wfTimestamp( TS_UNIX, $this->getOption( 'to' ) );

			$sec = $this->cutoffTo - $this->cutoffFrom;
			$days = $sec / 24 / 3600;
			$this->output( "Rebuilding range of $sec seconds ($days days)\n" );
		} else {
			global $wgRCMaxAge;

			$days = $wgRCMaxAge / 24 / 3600;
			$this->output( "Rebuilding \$wgRCMaxAge=$wgRCMaxAge seconds ($days days)\n" );

			$this->cutoffFrom = time() - $wgRCMaxAge;
			$this->cutoffTo = time();
		}

		$this->output( "Clearing recentchanges table for time range...\n" );
		$rcids = $dbw->newSelectQueryBuilder()
			->select( 'rc_id' )
			->from( 'recentchanges' )
			->where( $dbw->expr( 'rc_timestamp', '>', $dbw->timestamp( $this->cutoffFrom ) ) )
			->andWhere( $dbw->expr( 'rc_timestamp', '<', $dbw->timestamp( $this->cutoffTo ) ) )
			->caller( __METHOD__ )->fetchFieldValues();
		foreach ( array_chunk( $rcids, $this->getBatchSize() ) as $rcidBatch ) {
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'recentchanges' )
				->where( [ 'rc_id' => $rcidBatch ] )
				->caller( __METHOD__ )->execute();
			$this->waitForReplication();
		}

		$this->output( "Loading from page and revision tables...\n" );

		$res = $dbw->newSelectQueryBuilder()
			->select(
				[
					'rev_timestamp',
					'rev_minor_edit',
					'rev_id',
					'rev_deleted',
					'page_namespace',
					'page_title',
					'page_is_new',
					'page_id',
					'rev_comment_text' => 'comment_rev_comment.comment_text',
					'rev_comment_data' => 'comment_rev_comment.comment_data',
					'rev_comment_cid' => 'comment_rev_comment.comment_id',
					'rev_user' => 'actor_rev_user.actor_user',
					'rev_user_text' => 'actor_rev_user.actor_name',
					'rev_actor' => 'rev_actor',
				]
			)
			->from( 'revision' )
			->join( 'page', null, 'rev_page=page_id' )
			->join( 'comment', 'comment_rev_comment', 'comment_rev_comment.comment_id = rev_comment_id' )
			->join( 'actor', 'actor_rev_user', 'actor_rev_user.actor_id = rev_actor' )
			->where(
				[
					$dbw->expr( 'rev_timestamp', '>', $dbw->timestamp( $this->cutoffFrom ) ),
					$dbw->expr( 'rev_timestamp', '<', $dbw->timestamp( $this->cutoffTo ) )
				]
			)
			->orderBy( 'rev_timestamp', SelectQueryBuilder::SORT_DESC )
			->caller( __METHOD__ )->fetchResultSet();

		$this->output( "Inserting from page and revision tables...\n" );
		$inserted = 0;
		foreach ( $res as $row ) {
			$comment = $commentStore->getComment( 'rev_comment', $row );
			$dbw->newInsertQueryBuilder()
				->insertInto( 'recentchanges' )
				->row( [
					'rc_timestamp' => $row->rev_timestamp,
					'rc_actor' => $row->rev_actor,
					'rc_namespace' => $row->page_namespace,
					'rc_title' => $row->page_title,
					'rc_minor' => $row->rev_minor_edit,
					'rc_bot' => 0,
					'rc_cur_id' => $row->page_id,
					'rc_this_oldid' => $row->rev_id,
					'rc_last_oldid' => 0, // is this ok?
					'rc_type' => $row->page_is_new ? RC_NEW : RC_EDIT,
					'rc_source' => $row->page_is_new ? RecentChange::SRC_NEW : RecentChange::SRC_EDIT,
					'rc_deleted' => $row->rev_deleted
					] + $commentStore->insert( $dbw, 'rc_comment', $comment ) )
				->caller( __METHOD__ )->execute();

			$rcid = $dbw->insertId();
			$dbw->newUpdateQueryBuilder()
				->update( 'change_tag' )
				->set( [ 'ct_rc_id' => $rcid ] )
				->where( [ 'ct_rev_id' => $row->rev_id ] )
				->caller( __METHOD__ )->execute();

			if ( ( ++$inserted % $this->getBatchSize() ) == 0 ) {
				$this->waitForReplication();
			}
		}
	}

	/**
	 * Rebuild pass 2: Enhance entries for page revisions with references to the previous revision
	 * (rc_last_oldid etc.) and size differences (rc_old_len, rc_new_len).
	 */
	private function rebuildRecentChangesTablePass2() {
		$dbw = $this->getPrimaryDB();

		$this->output( "Updating links and size differences...\n" );

		# Fill in the rc_last_oldid field, which points to the previous edit
		$res = $dbw->newSelectQueryBuilder()
			->select( [ 'rc_cur_id', 'rc_this_oldid', 'rc_timestamp' ] )
			->from( 'recentchanges' )
			->where( $dbw->expr( 'rc_timestamp', '>', $dbw->timestamp( $this->cutoffFrom ) ) )
			->andWhere( $dbw->expr( 'rc_timestamp', '<', $dbw->timestamp( $this->cutoffTo ) ) )
			->orderBy( [ 'rc_cur_id', 'rc_timestamp' ] )
			->caller( __METHOD__ )->fetchResultSet();

		$lastCurId = 0;
		$lastOldId = 0;
		$lastSize = null;
		$updated = 0;
		foreach ( $res as $row ) {
			$new = 0;

			if ( $row->rc_cur_id != $lastCurId ) {
				# Switch! Look up the previous last edit, if any
				$lastCurId = intval( $row->rc_cur_id );
				$emit = $row->rc_timestamp;

				$revRow = $dbw->newSelectQueryBuilder()
					->select( [ 'rev_id', 'rev_len' ] )
					->from( 'revision' )
					->where( [ 'rev_page' => $lastCurId, $dbw->expr( 'rev_timestamp', '<', $emit ) ] )
					->orderBy( 'rev_timestamp DESC' )
					->caller( __METHOD__ )->fetchRow();
				if ( $revRow ) {
					$lastOldId = intval( $revRow->rev_id );
					# Grab the last text size if available
					$lastSize = $revRow->rev_len !== null ? intval( $revRow->rev_len ) : null;
				} else {
					# No previous edit
					$lastOldId = 0;
					$lastSize = 0;
					$new = 1; // probably true
				}
			}

			if ( $lastCurId == 0 ) {
				$this->output( "Uhhh, something wrong? No curid\n" );
			} else {
				# Grab the entry's text size
				$size = (int)$dbw->newSelectQueryBuilder()
					->select( 'rev_len' )
					->from( 'revision' )
					->where( [ 'rev_id' => $row->rc_this_oldid ] )
					->caller( __METHOD__ )->fetchField();

				$dbw->newUpdateQueryBuilder()
					->update( 'recentchanges' )
					->set( [
						'rc_last_oldid' => $lastOldId,
						'rc_type' => $new ? RC_NEW : RC_EDIT,
						'rc_source' => $new === 1 ? RecentChange::SRC_NEW : RecentChange::SRC_EDIT,
						'rc_old_len' => $lastSize,
						'rc_new_len' => $size,
					] )
					->where( [
						'rc_cur_id' => $lastCurId,
						'rc_this_oldid' => $row->rc_this_oldid,
						// index usage
						'rc_timestamp' => $row->rc_timestamp,
					] )
					->caller( __METHOD__ )->execute();

				$lastOldId = intval( $row->rc_this_oldid );
				$lastSize = $size;

				if ( ( ++$updated % $this->getBatchSize() ) == 0 ) {
					$this->waitForReplication();
				}
			}
		}
	}

	/**
	 * Rebuild pass 3: Insert `recentchanges` entries for action logs.
	 */
	private function rebuildRecentChangesTablePass3() {
		global $wgLogRestrictions, $wgFilterLogTypes;

		$dbw = $this->getDB( DB_PRIMARY );
		$commentStore = $this->getServiceContainer()->getCommentStore();
		$nonRCLogs = array_merge(
			array_keys( $wgLogRestrictions ),
			array_keys( $wgFilterLogTypes ),
			[ 'create' ]
		);

		$this->output( "Loading from user and logging tables...\n" );

		$res = $dbw->newSelectQueryBuilder()
			->select(
				[
					'log_timestamp',
					'log_actor',
					'log_namespace',
					'log_title',
					'log_page',
					'log_type',
					'log_action',
					'log_id',
					'log_params',
					'log_deleted',
					'log_comment_text' => 'comment_log_comment.comment_text',
					'log_comment_data' => 'comment_log_comment.comment_data',
					'log_comment_cid' => 'comment_log_comment.comment_id',
				]
			)
			->from( 'logging' )
			->join( 'comment', 'comment_log_comment', 'comment_log_comment.comment_id = log_comment_id' )
			->where(
				[
					$dbw->expr( 'log_timestamp', '>', $dbw->timestamp( $this->cutoffFrom ) ),
					$dbw->expr( 'log_timestamp', '<', $dbw->timestamp( $this->cutoffTo ) ),
					// Some logs don't go in RC since they are private, or are included in the filterable log types.
					'log_type' => array_diff( LogPage::validTypes(), $nonRCLogs ),
				]
			)
			->orderBy( [ 'log_timestamp DESC', 'log_id DESC' ] )
			->caller( __METHOD__ )->fetchResultSet();

		$field = $dbw->fieldInfo( 'recentchanges', 'rc_cur_id' );

		$inserted = 0;
		foreach ( $res as $row ) {
			$comment = $commentStore->getComment( 'log_comment', $row );
			$dbw->newInsertQueryBuilder()
				->insertInto( 'recentchanges' )
				->row( [
					'rc_timestamp' => $row->log_timestamp,
					'rc_actor' => $row->log_actor,
					'rc_namespace' => $row->log_namespace,
					'rc_title' => $row->log_title,
					'rc_minor' => 0,
					'rc_bot' => 0,
					'rc_patrolled' => $row->log_type == 'upload' ? 0 : 2,
					'rc_this_oldid' => 0,
					'rc_last_oldid' => 0,
					'rc_type' => RC_LOG,
					'rc_source' => RecentChange::SRC_LOG,
					'rc_cur_id' => $field->isNullable()
						? $row->log_page
						: (int)$row->log_page, // NULL => 0,
					'rc_log_type' => $row->log_type,
					'rc_log_action' => $row->log_action,
					'rc_logid' => $row->log_id,
					'rc_params' => $row->log_params,
					'rc_deleted' => $row->log_deleted
					] + $commentStore->insert( $dbw, 'rc_comment', $comment ) )
				->caller( __METHOD__ )->execute();

			$rcid = $dbw->insertId();
			$dbw->newUpdateQueryBuilder()
				->update( 'change_tag' )
				->set( [ 'ct_rc_id' => $rcid ] )
				->where( [ 'ct_log_id' => $row->log_id ] )
				->caller( __METHOD__ )->execute();

			if ( ( ++$inserted % $this->getBatchSize() ) == 0 ) {
				$this->waitForReplication();
			}
		}
	}

	/**
	 * Find rc_id values that have a user with one of the specified groups
	 *
	 * @param IReadableDatabase $db
	 * @param string[] $groups
	 * @param array $conds Extra query conditions
	 * @return int[]
	 */
	private function findRcIdsWithGroups( $db, $groups, $conds = [] ) {
		if ( !count( $groups ) ) {
			return [];
		}
		return $db->newSelectQueryBuilder()
			->select( 'rc_id' )
			->distinct()
			->from( 'recentchanges' )
			->join( 'actor', null, 'actor_id=rc_actor' )
			->join( 'user_groups', null, 'ug_user=actor_user' )
			->where( $conds )
			->andWhere( [
				$db->expr( 'rc_timestamp', '>', $db->timestamp( $this->cutoffFrom ) ),
				$db->expr( 'rc_timestamp', '<', $db->timestamp( $this->cutoffTo ) ),
				'ug_group' => $groups
			] )
			->caller( __METHOD__ )->fetchFieldValues();
	}

	/**
	 * Rebuild pass 4: Mark bot and autopatrolled entries.
	 */
	private function rebuildRecentChangesTablePass4() {
		global $wgUseRCPatrol, $wgUseNPPatrol, $wgUseFilePatrol, $wgMiserMode;

		$dbw = $this->getPrimaryDB();

		# @FIXME: recognize other bot account groups (not the same as users with 'bot' rights)
		# @NOTE: users with 'bot' rights choose when edits are bot edits or not. That information
		# may be lost at this point (aside from joining on the patrol log table entries).
		$botgroups = [ 'bot' ];
		$autopatrolgroups = ( $wgUseRCPatrol || $wgUseNPPatrol || $wgUseFilePatrol ) ?
			$this->getServiceContainer()->getGroupPermissionsLookup()
			->getGroupsWithPermission( 'autopatrol' ) : [];

		# Flag our recent bot edits
		// @phan-suppress-next-line PhanRedundantCondition
		if ( $botgroups ) {
			$this->output( "Flagging bot account edits...\n" );

			# Fill in the rc_bot field
			$rcids = $this->findRcIdsWithGroups( $dbw, $botgroups );

			foreach ( array_chunk( $rcids, $this->getBatchSize() ) as $rcidBatch ) {
				$dbw->newUpdateQueryBuilder()
					->update( 'recentchanges' )
					->set( [ 'rc_bot' => 1 ] )
					->where( [ 'rc_id' => $rcidBatch ] )
					->caller( __METHOD__ )->execute();
				$this->waitForReplication();
			}
		}

		# Flag our recent autopatrolled edits
		if ( !$wgMiserMode && $autopatrolgroups ) {
			$this->output( "Flagging auto-patrolled edits...\n" );

			$conds = [ 'rc_patrolled' => 0 ];
			if ( !$wgUseRCPatrol ) {
				$subConds = [];
				if ( $wgUseNPPatrol ) {
					$subConds[] = $dbw->expr( 'rc_source', '=', RecentChange::SRC_NEW );
				}
				if ( $wgUseFilePatrol ) {
					$subConds[] = $dbw->expr( 'rc_log_type', '=', 'upload' );
				}
				$conds[] = $dbw->makeList( $subConds, IDatabase::LIST_OR );
			}

			$rcids = $this->findRcIdsWithGroups( $dbw, $autopatrolgroups, $conds );
			foreach ( array_chunk( $rcids, $this->getBatchSize() ) as $rcidBatch ) {
				$dbw->newUpdateQueryBuilder()
					->update( 'recentchanges' )
					->set( [ 'rc_patrolled' => 2 ] )
					->where( [ 'rc_id' => $rcidBatch ] )
					->caller( __METHOD__ )->execute();
				$this->waitForReplication();
			}
		}
	}

	/**
	 * Rebuild pass 5: Delete duplicate entries where we generate both a page revision and a log
	 * entry for a single action (upload, move, protect, import, etc.).
	 */
	private function rebuildRecentChangesTablePass5() {
		$dbw = $this->getPrimaryDB();

		$this->output( "Removing duplicate revision and logging entries...\n" );

		$res = $dbw->newSelectQueryBuilder()
			->select( [ 'ls_value', 'ls_log_id' ] )
			->from( 'logging' )
			->join( 'log_search', null, 'ls_log_id = log_id' )
			->where( [
				'ls_field' => 'associated_rev_id',
				$dbw->expr( 'log_type', '!=', 'create' ),
				$dbw->expr( 'log_timestamp', '>', $dbw->timestamp( $this->cutoffFrom ) ),
				$dbw->expr( 'log_timestamp', '<', $dbw->timestamp( $this->cutoffTo ) ),
			] )
			->caller( __METHOD__ )->fetchResultSet();

		$updates = 0;
		foreach ( $res as $row ) {
			$rev_id = $row->ls_value;
			$log_id = $row->ls_log_id;

			// Mark the logging row as having an associated rev id
			$dbw->newUpdateQueryBuilder()
				->update( 'recentchanges' )
				->set( [ 'rc_this_oldid' => $rev_id ] )
				->where( [ 'rc_logid' => $log_id ] )
				->caller( __METHOD__ )->execute();

			// Delete the revision row
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'recentchanges' )
				->where( [ 'rc_this_oldid' => $rev_id, 'rc_logid' => 0 ] )
				->caller( __METHOD__ )->execute();

			if ( ( ++$updates % $this->getBatchSize() ) == 0 ) {
				$this->waitForReplication();
			}
		}
	}

	/**
	 * Purge cached feeds in $wanCache
	 */
	private function purgeFeeds() {
		global $wgFeedClasses;

		$this->output( "Deleting feed timestamps.\n" );

		$wanCache = $this->getServiceContainer()->getMainWANObjectCache();
		foreach ( $wgFeedClasses as $feed => $className ) {
			$wanCache->delete( $wanCache->makeKey( 'rcfeed', $feed, 'timestamp' ) ); # Good enough for now.
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = RebuildRecentchanges::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
