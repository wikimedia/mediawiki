<?php
/**
 * Deletes a batch of pages
 * Usage: php deleteBatch.php [-u <user>] [-r <reason>] [-i <interval>] [listfile]
 * where
 *	[listfile] is a file where each line contains the title of a page to be
 *             deleted, standard input is used if listfile is not given.
 *	<user> is the username
 *	<reason> is the delete reason
 *	<interval> is the number of seconds to sleep for after each delete
 *
 * @file
 * @ingroup Maintenance
 */
 
require_once( "Maintenance.php" );

class DeleteBatch extends Maintenance {
	
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Deletes a batch of pages";
		$this->addParam( 'u', "User to perform deletion", false, true );
		$this->addParam( 'r', "Reason to delete page", false, true );
		$this->addParam( 'i', "Interval to sleep between deletions" );
		$this->addArgs( array( 'listfile' ) );
	}
	
	public function execute() {
		global $wgUser;

		# Change to current working directory
		$oldCwd = getcwd();
		chdir( $oldCwd );
	
		# Options processing
		$user = $this->getOption( 'u', 'Delete page script' );
		$reason = $this->getOption( 'r', '' );
		$interval = $this->getOption( 'i', 0 );
		if( $this->hasArg() ) {
			$file = fopen( $this->getArg(), 'r' );
		} else {
			$file = $this->getStdin();
		}

		# Setup
		if( !$file ) {
			$this->error( "Unable to read file, exiting\n", true );
		}
		$wgUser = User::newFromName( $user );
		$dbw = wfGetDB( DB_MASTER );

		# Handle each entry
		for ( $linenum = 1; !feof( $file ); $linenum++ ) {
			$line = trim( fgets( $file ) );
			if ( $line == '' ) {
				continue;
			}
			$page = Title::newFromText( $line );
			if ( is_null( $page ) ) {
				$this->output( "Invalid title '$line' on line $linenum\n" );
				continue;
			}
			if( !$page->exists() ) {
				$this->output( "Skipping nonexistent page '$line'\n" );
				continue;
			}
	
	
			$this->output( $page->getPrefixedText() );
			$dbw->begin();
			if( $page->getNamespace() == NS_FILE ) {
				$art = new ImagePage( $page );
				$img = wfFindFile( $art->mTitle );
				if( !$img || !$img->delete( $reason ) ) {
					$this->output( "FAILED to delete image file... " );
				}
			} else {
				$art = new Article( $page );
			}
			$success = $art->doDeleteArticle( $reason );
			$dbw->immediateCommit();
			if ( $success ) {
				$this->output( "\n" );
			} else {
				$this->output( " FAILED to delete article\n" );
			}
	
			if ( $interval ) {
				sleep( $interval );
			}
			wfWaitForSlaves( 5 );
}
	}
}

$maintClass = "DeleteBatch";
require_once( DO_MAINTENANCE );
