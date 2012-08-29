<?php
/**
 * Makes the required database updates for Special:ProtectedPages
 * to show all protected pages, even ones before the page restrictions
 * schema change. All remaining page_restriction column values are moved
 * to the new table.
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
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class UpdateRestrictions extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Updates page_restrictions table from old page_restriction column";
		$this->setBatchSize( 100 );
	}

	public function execute() {
		$db = wfGetDB( DB_MASTER );
		if ( !$db->tableExists( 'page_restrictions' ) ) {
			$this->error( "page_restrictions table does not exist", true );
		}

		$start = $db->selectField( 'page', 'MIN(page_id)', false, __METHOD__ );
		if ( !$start ) {
			$this->error( "Nothing to do.", true );
		}
		$end = $db->selectField( 'page', 'MAX(page_id)', false, __METHOD__ );

		# Do remaining chunk
		$end += $this->mBatchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $this->mBatchSize - 1;
		$encodedExpiry = 'infinity';
		while ( $blockEnd <= $end ) {
			$this->output( "...doing page_id from $blockStart to $blockEnd\n" );
			$cond = "page_id BETWEEN $blockStart AND $blockEnd AND page_restrictions !=''";
			$res = $db->select( 'page', array( 'page_id', 'page_namespace', 'page_restrictions' ), $cond, __METHOD__ );
			$batch = array();
			foreach ( $res as $row ) {
				$oldRestrictions = array();
				foreach ( explode( ':', trim( $row->page_restrictions ) ) as $restrict ) {
					$temp = explode( '=', trim( $restrict ) );
					// Make sure we are not settings restrictions to ""
					if ( count( $temp ) == 1 && $temp[0] ) {
						// old old format should be treated as edit/move restriction
						$oldRestrictions["edit"] = trim( $temp[0] );
						$oldRestrictions["move"] = trim( $temp[0] );
					} elseif ( $temp[1] ) {
						$oldRestrictions[$temp[0]] = trim( $temp[1] );
					}
				}
				# Clear invalid columns
				if ( $row->page_namespace == NS_MEDIAWIKI ) {
					$db->update( 'page', array( 'page_restrictions' => '' ),
						array( 'page_id' => $row->page_id ), __FUNCTION__ );
					$this->output( "...removed dead page_restrictions column for page {$row->page_id}\n" );
				}
				# Update restrictions table
				foreach ( $oldRestrictions as $action => $restrictions ) {
					$batch[] = array(
						'pr_page' => $row->page_id,
						'pr_type' => $action,
						'pr_level' => $restrictions,
						'pr_cascade' => 0,
						'pr_expiry' => $encodedExpiry
					);
				}
			}
			# We use insert() and not replace() as Article.php replaces
			# page_restrictions with '' when protected in the restrictions table
			if ( count( $batch ) ) {
				$ok = $db->deadlockLoop( array( $db, 'insert' ), 'page_restrictions',
					$batch, __FUNCTION__, array( 'IGNORE' ) );
				if ( !$ok ) {
					throw new MWException( "Deadlock loop failed wtf :(" );
				}
			}
			$blockStart += $this->mBatchSize - 1;
			$blockEnd += $this->mBatchSize - 1;
			wfWaitForSlaves();
		}
		$this->output( "...removing dead rows from page_restrictions\n" );
		// Kill any broken rows from previous imports
		$db->delete( 'page_restrictions', array( 'pr_level' => '' ) );
		// Kill other invalid rows
		$db->deleteJoin( 'page_restrictions', 'page', 'pr_page', 'page_id', array( 'page_namespace' => NS_MEDIAWIKI ) );
		$this->output( "...Done!\n" );
	}
}

$maintClass = "UpdateRestrictions";
require_once( RUN_MAINTENANCE_IF_MAIN );
