<?php
/**
 * Delete one or more revisions by moving them to the archive table.
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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class DeleteRevision extends Maintenance {
	
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Delete one or more revisions by moving them to the archive table";
	}
	
	public function execute() {
		if ( count( $this->mArgs ) == 0 ) {
			$this->error( "No revisions specified", true );
		}

		$this->output( "Deleting revision(s) " . implode( ',', $this->mArgs ) .
						" from " . wfWikiID() . "...\n" );
		$dbw = wfGetDB( DB_MASTER );
		
		$affected = 0;
		foreach ( $this->mArgs as $revID ) {
			$dbw->insertSelect( 'archive', array( 'page', 'revision' ),
				array(
					'ar_namespace'  => 'page_namespace',
					'ar_title'      => 'page_title',
					'ar_page_id'    => 'page_id',
					'ar_comment'    => 'rev_comment',
					'ar_user'       => 'rev_user',
					'ar_user_text'  => 'rev_user_text',
					'ar_timestamp'  => 'rev_timestamp',
					'ar_minor_edit' => 'rev_minor_edit',
					'ar_rev_id'     => 'rev_id',
					'ar_text_id'    => 'rev_text_id',
					'ar_deleted'    => 'rev_deleted',
					'ar_len'        => 'rev_len',
				), array(
					'rev_id' => $revID,
					'page_id = rev_page'
				), __METHOD__
			);
			if ( !$dbw->affectedRows() ) {
				$this->output( "Revision $revID not found\n" );
			} else {
				$affected += $dbw->affectedRows();
				$pageID = $dbw->selectField( 'revision', 'rev_page', array( 'rev_id' => $revID ), __METHOD__ );
				$pageLatest = $dbw->selectField( 'page', 'page_latest', array( 'page_id' => $pageID ), __METHOD__ );
				$dbw->delete( 'revision', array( 'rev_id' => $revID ) );
				if ( $pageLatest == $revID ) {
					// Database integrity
					$newLatest = $dbw->selectField( 'revision', 'rev_id', array( 'rev_page' => $pageID ), __METHOD__, array( 'ORDER BY' => 'rev_timestamp DESC' ) );
					$dbw->update( 'page', array( 'page_latest' => $newLatest ), array( 'page_id' => $pageID ), __METHOD__ );
				}
			}
		}
		$this->output( "Deleted $affected revisions\n" );
	}
}

$maintClass = "DeleteRevision";
require_once( DO_MAINTENANCE );
