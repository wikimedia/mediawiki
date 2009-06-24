<?php
/**
 * CheckBadRedirects - See if pages marked as being redirects
 * really are.
 */
 
require_once( "Maintenance.php" );

class CheckBadRedirects extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Look for bad redirects";
	}

	public function execute() {
		$this->output( "Fetching redirects...\n" );
		$dbr = wfGetDB( DB_SLAVE );
		$result = $dbr->select(
			array( 'page' ),
			array( 'page_namespace','page_title', 'page_latest' ),
			array( 'page_is_redirect' => 1 ) );
	
		$count = $result->numRows();
		$this->output( "Found $count total redirects.\n" .
						"Looking for bad redirects:\n\n" );
	
		foreach( $result as $row ) {
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
			$rev = Revision::newFromId( $row->page_latest );
			if( $rev ) {
				$target = Title::newFromRedirect( $rev->getText() );
				if( !$target ) {
					$this->output( $title->getPrefixedText() . "\n" );
				}
			}
		}
		$this->output( "\ndone.\n" );
	}
}

$maintClass = "CheckBadRedirects";
require_once( DO_MAINTENANCE );
