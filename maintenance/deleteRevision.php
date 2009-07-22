<?php
/**
 * Delete one or more revisions by moving them to the archive table.
 *
 * @file
 * @ingroup Maintenance
 */

require_once( 'commandLine.inc' );

$dbw = wfGetDB( DB_MASTER );

if ( count( $args ) == 0 ) {
	echo "Usage: php deleteRevision.php <revid> [<revid> ...]\n";
	exit(1);
}

echo "Deleting revision(s) " . implode( ',', $args ) . " from ".wfWikiID()."...\n";

$affected = 0;
foreach ( $args as $revID ) {
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
		), $fname
	);
	if ( !$dbw->affectedRows() ) {
		echo "Revision $revID not found\n";
	} else {
		$affected += $dbw->affectedRows();
		$dbw->delete( 'revision', array( 'rev_id' => $revID ) );

		// Database integrity
		$pageID = $dbw->selectField( 'page', 'page_id', array( 'page_latest' => $revID ), __METHOD__ );
		if ( $pageID ) {
			$newLatest = $dbw->selectField( 'revision', 'rev_id', array( 'rev_page' => $pageID ), __METHOD__, array( 'ORDER BY' => 'rev_timestamp DESC' ) );
			$dbw->update( 'page', array( 'page_latest' => $newLatest ), array( 'page_id' => $pageID ), __METHOD__ );
		}
	}
}

print "Deleted $affected revisions\n";

