<?php
/**
 * Show page contents.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Content\TextContent;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to show page contents.
 *
 * @ingroup Maintenance
 */
class ViewCLI extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Show article contents on the command line' );
		$this->addArg( 'title', 'Title of article to view' );
	}

	public function execute() {
		$title = Title::newFromText( $this->getArg( 0 ) );
		if ( !$title ) {
			$this->fatalError( "Invalid title" );
		} elseif ( $title->isSpecialPage() ) {
			$this->fatalError( "Special Pages not supported" );
		} elseif ( !$title->exists() ) {
			$this->fatalError( "Page does not exist" );
		}

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$content = $page->getContent( RevisionRecord::RAW );
		if ( !$content ) {
			$this->fatalError( "Page has no content" );
		}
		if ( !$content instanceof TextContent ) {
			$this->fatalError( "Non-text content models not supported" );
		}

		$this->output( $content->getText() );
	}
}

// @codeCoverageIgnoreStart
$maintClass = ViewCLI::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
