<?php
/**
 * Check that pages marked as being redirects really are.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to check that pages marked as being redirects really are.
 *
 * @ingroup Maintenance
 */
class CheckBadRedirects extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Check for bad redirects' );
	}

	public function execute() {
		$this->output( "Fetching redirects...\n" );
		$dbr = $this->getReplicaDB();
		$result = $dbr->newSelectQueryBuilder()
			->select( [ 'page_namespace', 'page_title', 'page_latest' ] )
			->from( 'page' )
			->where( [ 'page_is_redirect' => 1 ] )
			->caller( __METHOD__ )
			->fetchResultSet();

		$count = $result->numRows();
		$this->output( "Found $count redirects.\n" .
			"Checking for bad redirects:\n\n" );

		$revLookup = $this->getServiceContainer()->getRevisionLookup();
		foreach ( $result as $row ) {
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
			$revRecord = $revLookup->getRevisionById( $row->page_latest );
			if ( $revRecord ) {
				$target = $revRecord->getContent( SlotRecord::MAIN )->getRedirectTarget();
				if ( !$target ) {
					$this->output( $title->getPrefixedText() . "\n" );
				}
			}
		}
		$this->output( "\nDone.\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = CheckBadRedirects::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
