<?php
/**
 * Outputs page text to stdout.
 * Useful for command-line editing automation.
 * Example: php getText.php "page title" | sed -e '...' | php edit.php "page title"
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that outputs page text to stdout.
 *
 * @ingroup Maintenance
 */
class GetTextMaint extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Outputs page text to stdout' );
		$this->addOption( 'show-private', 'Show the text even if it\'s not available to the public' );
		$this->addArg( 'title', 'Page title' );
		$this->addOption( 'revision', 'Revision ID', false, true );
	}

	public function execute() {
		$titleText = $this->getArg( 0 );
		$title = Title::newFromText( $titleText );
		if ( !$title ) {
			$this->fatalError( "$titleText is not a valid title.\n" );
		}

		if ( !$title->exists() ) {
			$titleText = $title->getPrefixedText();
			$this->fatalError( "Page $titleText does not exist.\n" );
		}

		$revId = (int)$this->getOption( 'revision', $title->getLatestRevID() );

		$rev = $this->getServiceContainer()
			->getRevisionLookup()
			->getRevisionByTitle( $title, $revId );

		if ( !$rev ) {
			$titleText = $title->getPrefixedText();
			$this->fatalError( "Could not load revision $revId of $titleText.\n" );
		}

		$audience = $this->hasOption( 'show-private' ) ?
			RevisionRecord::RAW :
			RevisionRecord::FOR_PUBLIC;
		$content = $rev->getContent( SlotRecord::MAIN, $audience );

		if ( $content === null ) {
			$titleText = $title->getPrefixedText();
			$this->fatalError( "Couldn't extract the text from $titleText.\n" );
		}
		$this->output( $content->serialize() );

		if ( stream_isatty( STDOUT ) ) {
			// When writing to a TTY, add a linebreak, to keep the terminal output tidy.
			// Wikitext will generally not have a trailing newline.
			$this->output( "\n" );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = GetTextMaint::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
