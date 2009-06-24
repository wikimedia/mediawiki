<?php
/**
 * Delete one or more revisions by moving them to the archive table.
 *
 * @file
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class DeleteRevision extends Maintenance {
	
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Delete one or more revisions by moving them to the archive table";
	}
	
	public function execute() {
		if( count( $this->mArgs ) == 0 ) {
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
					'ar_comment'    => 'rev_comment',
					'ar_user'       => 'rev_user',
					'ar_user_text'  => 'rev_user_text',
					'ar_timestamp'  => 'rev_timestamp',
					'ar_minor_edit' => 'rev_minor_edit',
					'ar_rev_id'     => 'rev_id',
					'ar_text_id'    => 'rev_text_id',
				), array(
					'rev_id' => $revID,
					'page_id = rev_page'
				), __METHOD__
			);
			if ( !$dbw->affectedRows() ) {
				$this->output( "Revision $revID not found\n" );
			} else {
				$affected += $dbw->affectedRows();
				$dbw->delete( 'revision', array( 'rev_id' => $revID ) );
			}
		}
		$this->output( "Deleted $affected revisions\n" );
	}
}

$maintClass = "DeleteRevision";
require_once( DO_MAINTENANCE );
