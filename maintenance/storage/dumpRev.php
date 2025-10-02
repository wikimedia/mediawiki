<?php
/**
 * Get the text of a revision, resolving external storage if needed.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance ExternalStorage
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Revision\SlotRecord;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that gets the text of a revision,
 * resolving external storage if needed.
 *
 * @ingroup Maintenance ExternalStorage
 */
class DumpRev extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addArg( 'rev-id', 'Revision ID', true );
	}

	public function execute() {
		$id = (int)$this->getArg( 0 );

		$lookup = $this->getServiceContainer()->getRevisionLookup();
		$rev = $lookup->getRevisionById( $id );
		if ( !$rev ) {
			$this->fatalError( "Row not found" );
		}

		$content = $rev->getContent( SlotRecord::MAIN );
		if ( !$content ) {
			$this->fatalError( "Text not found" );
		}

		$blobStore = $this->getServiceContainer()->getBlobStore();
		$slot = $rev->getSlot( SlotRecord::MAIN );
		$text = $blobStore->getBlob( $slot->getAddress() );

		$this->output( "Text length: " . strlen( $text ) . "\n" );
		$this->output( substr( $text, 0, 100 ) . "\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = DumpRev::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
