<?php
/**
 * Purges a specific page.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Title\Title;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that purges a list of pages passed through stdin
 *
 * @ingroup Maintenance
 */
class PurgePage extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Purge page.' );
		$this->addOption( 'skip-exists-check', 'Skip page existence check', false, false );
	}

	public function execute() {
		$stdin = $this->getStdin();

		while ( !feof( $stdin ) ) {
			$title = trim( fgets( $stdin ) );
			if ( $title != '' ) {
				$this->purge( $title );
			}
		}
	}

	private function purge( string $titleText ) {
		$title = Title::newFromText( $titleText );

		if ( $title === null ) {
			$this->error( 'Invalid page title' );
			return;
		}

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		if ( !$this->getOption( 'skip-exists-check' ) && !$page->exists() ) {
			$this->error( "Page doesn't exist" );
			return;
		}

		if ( $page->doPurge() ) {
			$this->output( "Purged {$titleText}\n" );
		} else {
			$this->error( "Purge failed for {$titleText}" );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = PurgePage::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
