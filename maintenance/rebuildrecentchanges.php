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

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Maintenance script that rebuilds recent changes from scratch.
 *
 * @ingroup Maintenance
 */
class RebuildRecentchanges extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Rebuild recent changes";
	}

	public function execute() {
		$this->rebuildRecentChangesTablePass1();
		$this->rebuildRecentChangesTablePass2();
		$this->rebuildRecentChangesTablePass3();
		$this->rebuildRecentChangesTablePass4();
		$this->purgeFeeds();
		$this->output( "Done.\n" );
	}

	/**
	 * Rebuild pass 1
	 * DOCUMENT ME!
	 */
	private function rebuildRecentChangesTablePass1() {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->delete( 'recentchanges', '*' );

		$this->output( "Loading from page and revision tables...\n" );

		global $wgRCMaxAge;

		$this->output( '$wgRCMaxAge=' . $wgRCMaxAge );
		$days = $wgRCMaxAge / 24 / 3600;
		if ( intval( $days ) == $days ) {
				$this->output( " (" . $days . " days)\n" );
		} else {
				$this->output( " (approx. " .  intval( $days ) . " days)\n" );
		}

		$cutoff = time() - $wgRCMaxAge;
		$dbw->insertSelect( 'recentchanges', array( 'page', 'revision' ),
			array(
				'rc_timestamp'  => 'rev_timestamp',
				'rc_cur_time'   => 'rev_timestamp',
				'rc_user'       => 'rev_user',
				'rc_user_text'  => 'rev_user_text',
				'rc_namespace'  => 'page_namespace',
				'rc_title'      => 'page_title',
				'rc_comment'    => 'rev_comment',
				'rc_minor'      => 'rev_minor_edit',
				'rc_bot'        => 0,
				'rc_new'        => 'page_is_new',
				'rc_cur_id'     => 'page_id',
				'rc_this_oldid' => 'rev_id',
				'rc_last_oldid' => 0, // is this ok?
				'rc_type'       => $dbw->conditional( 'page_is_new != 0', RC_NEW, RC_EDIT ),
				'rc_deleted'    => 'rev_deleted'
			), array(
				'rev_timestamp > ' . $dbw->addQuotes( $dbw->timestamp( $cutoff ) ),
				'rev_page=page_id'
			), __METHOD__,
			array(), // INSERT options
			array( 'ORDER BY' => 'rev_timestamp DESC', 'LIMIT' => 5000 ) // SELECT options
		);
	}

	/**
	 * Rebuild pass 2
	 * DOCUMENT ME!
	 */
	private function rebuildRecentChangesTablePass2() {
		$dbw = wfGetDB( DB_MASTER );
		list ( $recentchanges, $revision ) = $dbw->tableNamesN( 'recentchanges', 'revision' );

		$this->output( "Updating links and size differences...\n" );

		# Fill in the rc_last_oldid field, which points to the previous edit
		$sql = "SELECT rc_cur_id,rc_this_oldid,rc_timestamp FROM $recentchanges " .
		  "ORDER BY rc_cur_id,rc_timestamp";
		$res = $dbw->query( $sql, DB_MASTER );

		$lastCurId = 0;
		$lastOldId = 0;
		foreach ( $res as $obj ) {
			$new = 0;
			if ( $obj->rc_cur_id != $lastCurId ) {
				# Switch! Look up the previous last edit, if any
				$lastCurId = intval( $obj->rc_cur_id );
				$emit = $obj->rc_timestamp;
				$sql2 = "SELECT rev_id,rev_len FROM $revision " .
					"WHERE rev_page={$lastCurId} " .
					"AND rev_timestamp<'{$emit}' ORDER BY rev_timestamp DESC";
				$sql2 = $dbw->limitResult( $sql2, 1, false );
				$res2 = $dbw->query( $sql2 );
				$row = $dbw->fetchObject( $res2 );
				if ( $row ) {
					$lastOldId = intval( $row->rev_id );
					# Grab the last text size if available
					$lastSize = !is_null( $row->rev_len ) ? intval( $row->rev_len ) : null;
				} else {
					# No previous edit
					$lastOldId = 0;
					$lastSize = null;
					$new = 1; // probably true
				}
			}
			if ( $lastCurId == 0 ) {
				$this->output( "Uhhh, something wrong? No curid\n" );
			} else {
				# Grab the entry's text size
				$size = $dbw->selectField( 'revision', 'rev_len', array( 'rev_id' => $obj->rc_this_oldid ) );

				$dbw->update( 'recentchanges',
					array(
						'rc_last_oldid' => $lastOldId,
						'rc_new'        => $new,
						'rc_type'       => $new,
						'rc_old_len'    => $lastSize,
						'rc_new_len'    => $size,
					), array(
						'rc_cur_id'     => $lastCurId,
						'rc_this_oldid' => $obj->rc_this_oldid,
					),
					__METHOD__
				);

				$lastOldId = intval( $obj->rc_this_oldid );
				$lastSize = $size;
			}
		}
	}

	/**
	 * Rebuild pass 3
	 * DOCUMENT ME!
	 */
	private function rebuildRecentChangesTablePass3() {
		$dbw = wfGetDB( DB_MASTER );

		$this->output( "Loading from user, page, and logging tables...\n" );

		global $wgRCMaxAge, $wgLogTypes, $wgLogRestrictions;
		// Some logs don't go in RC. This should check for that
		$basicRCLogs = array_diff( $wgLogTypes, array_keys( $wgLogRestrictions ) );

		// Escape...blah blah
		$selectLogs = array();
		foreach ( $basicRCLogs as $logtype ) {
			$safetype = $dbw->strencode( $logtype );
			$selectLogs[] = "'$safetype'";
		}

		$cutoff = time() - $wgRCMaxAge;
		list( $logging, $page ) = $dbw->tableNamesN( 'logging', 'page' );
		$dbw->insertSelect( 'recentchanges', array( 'user', "$logging LEFT JOIN $page ON (log_namespace=page_namespace AND log_title=page_title)" ),
			array(
				'rc_timestamp'  => 'log_timestamp',
				'rc_cur_time'   => 'log_timestamp',
				'rc_user'       => 'log_user',
				'rc_user_text'  => 'user_name',
				'rc_namespace'  => 'log_namespace',
				'rc_title'      => 'log_title',
				'rc_comment'    => 'log_comment',
				'rc_minor'      => 0,
				'rc_bot'        => 0,
				'rc_patrolled'  => 1,
				'rc_new'        => 0,
				'rc_this_oldid' => 0,
				'rc_last_oldid' => 0,
				'rc_type'       => RC_LOG,
				'rc_cur_id'     => $dbw->cascadingDeletes() ? 'page_id' : 'COALESCE(page_id, 0)',
				'rc_log_type'   => 'log_type',
				'rc_log_action' => 'log_action',
				'rc_logid'      => 'log_id',
				'rc_params'     => 'log_params',
				'rc_deleted'    => 'log_deleted'
			), array(
				'log_timestamp > ' . $dbw->addQuotes( $dbw->timestamp( $cutoff ) ),
				'log_user=user_id',
				'log_type IN(' . implode( ',', $selectLogs ) . ')'
			), __METHOD__,
			array(), // INSERT options
			array( 'ORDER BY' => 'log_timestamp DESC', 'LIMIT' => 5000 ) // SELECT options
		);
	}

	/**
	 * Rebuild pass 4
	 * DOCUMENT ME!
	 */
	private function rebuildRecentChangesTablePass4() {
		global $wgGroupPermissions, $wgUseRCPatrol;

		$dbw = wfGetDB( DB_MASTER );

		list( $recentchanges, $usergroups, $user ) = $dbw->tableNamesN( 'recentchanges', 'user_groups', 'user' );

		$botgroups = $autopatrolgroups = array();
		foreach ( $wgGroupPermissions as $group => $rights ) {
			if ( isset( $rights['bot'] ) && $rights['bot'] ) {
				$botgroups[] = $dbw->addQuotes( $group );
			}
			if ( $wgUseRCPatrol && isset( $rights['autopatrol'] ) && $rights['autopatrol'] ) {
				$autopatrolgroups[] = $dbw->addQuotes( $group );
			}
		}
		# Flag our recent bot edits
		if ( !empty( $botgroups ) ) {
			$botwhere = implode( ',', $botgroups );
			$botusers = array();

			$this->output( "Flagging bot account edits...\n" );

			# Find all users that are bots
			$sql = "SELECT DISTINCT user_name FROM $usergroups, $user " .
				"WHERE ug_group IN($botwhere) AND user_id = ug_user";
			$res = $dbw->query( $sql, DB_MASTER );

			foreach ( $res as $obj ) {
				$botusers[] = $dbw->addQuotes( $obj->user_name );
			}
			# Fill in the rc_bot field
			if ( !empty( $botusers ) ) {
				$botwhere = implode( ',', $botusers );
				$sql2 = "UPDATE $recentchanges SET rc_bot=1 " .
					"WHERE rc_user_text IN($botwhere)";
				$dbw->query( $sql2 );
			}
		}
		global $wgMiserMode;
		# Flag our recent autopatrolled edits
		if ( !$wgMiserMode && !empty( $autopatrolgroups ) ) {
			$patrolwhere = implode( ',', $autopatrolgroups );
			$patrolusers = array();

			$this->output( "Flagging auto-patrolled edits...\n" );

			# Find all users in RC with autopatrol rights
			$sql = "SELECT DISTINCT user_name FROM $usergroups, $user " .
				"WHERE ug_group IN($patrolwhere) AND user_id = ug_user";
			$res = $dbw->query( $sql, DB_MASTER );

			foreach ( $res as $obj ) {
				$patrolusers[] = $dbw->addQuotes( $obj->user_name );
			}

			# Fill in the rc_patrolled field
			if ( !empty( $patrolusers ) ) {
				$patrolwhere = implode( ',', $patrolusers );
				$sql2 = "UPDATE $recentchanges SET rc_patrolled=1 " .
					"WHERE rc_user_text IN($patrolwhere)";
				$dbw->query( $sql2 );
			}
		}
	}

	/**
	 * Purge cached feeds in $messageMemc
	 */
	private function purgeFeeds() {
		global $wgFeedClasses, $messageMemc;

		$this->output( "Deleting feed timestamps.\n" );

		foreach ( $wgFeedClasses as $feed => $className ) {
			$messageMemc->delete( wfMemcKey( 'rcfeed', $feed, 'timestamp' ) ); # Good enough for now.
		}
	}

}

$maintClass = "RebuildRecentchanges";
require_once( RUN_MAINTENANCE_IF_MAIN );
