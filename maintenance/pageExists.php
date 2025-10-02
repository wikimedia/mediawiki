<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Title\Title;

/**
 * @ingroup Maintenance
 */
class PageExists extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Report whether a specific page exists' );
		$this->addArg( 'title', 'Page title to check whether it exists' );
	}

	/** @inheritDoc */
	public function execute() {
		$titleArg = $this->getArg( 0 );
		$title = Title::newFromText( $titleArg );
		$pageExists = $title && $title->exists();

		if ( $pageExists ) {
			$text = "{$title} exists.\n";
		} else {
			$text = "{$titleArg} doesn't exist.\n";
		}
		$this->output( $text );
		return $pageExists;
	}
}

// @codeCoverageIgnoreStart
$maintClass = PageExists::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
