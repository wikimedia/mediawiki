<?php
/**
 * Take page text out of an XML dump file and render basic HTML out to files.
 * This is *NOT* suitable for publishing or offline use; it's intended for
 * running comparative tests of parsing behavior using real-world data.
 *
 * Templates etc are pulled from the local wiki database, not from the dump.
 *
 * Copyright (C) 2006 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\User\User;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that takes page text out of an XML dump file
 * and render basic HTML out to files.
 *
 * @ingroup Maintenance
 */
class DumpRenderer extends Maintenance {

	/** @var int */
	private $count = 0;
	private string $outputDirectory;
	private float $startTime;
	/** @var string */
	private $prefix;

	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Take page text out of an XML dump file and render basic HTML out to files' );
		$this->addOption( 'output-dir', 'The directory to output the HTML files to', true, true );
		$this->addOption( 'prefix', 'Prefix for the rendered files (defaults to wiki)', false, true );
		$this->addOption( 'parser', 'Use an alternative parser class', false, true );
	}

	public function execute() {
		$this->outputDirectory = $this->getOption( 'output-dir' );
		$this->prefix = $this->getOption( 'prefix', 'wiki' );
		$this->startTime = microtime( true );

		if ( $this->hasOption( 'parser' ) ) {
			$this->prefix .= '-' . $this->getOption( 'parser' );
			// T236809: We'll need to provide an alternate ParserFactory
			// service to make this work.
			$this->fatalError( 'Parser class configuration temporarily disabled.' );
		}

		$user = User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] );

		$source = new ImportStreamSource( $this->getStdin() );
		$importer = $this->getServiceContainer()
			->getWikiImporterFactory()
			->getWikiImporter( $source, new UltimateAuthority( $user ) );

		$importer->setRevisionCallback(
			$this->handleRevision( ... ) );
		$importer->setNoticeCallback( static function ( $msg, $params ) {
			echo wfMessage( $msg, $params )->text() . "\n";
		} );

		$importer->doImport();

		$delta = microtime( true ) - $this->startTime;
		$this->error( "Rendered {$this->count} pages in " . round( $delta, 2 ) . " seconds " );
		if ( $delta > 0 ) {
			$this->error( round( $this->count / $delta, 2 ) . " pages/sec" );
		}
		$this->error( "\n" );
	}

	/**
	 * Callback function for each revision, turn into HTML and save
	 */
	public function handleRevision( WikiRevision $rev ) {
		$title = $rev->getTitle();
		if ( !$title ) {
			$this->error( "Got bogus revision with null title!" );

			return;
		}
		$display = $title->getPrefixedText();

		$this->count++;

		$sanitized = rawurlencode( $display );
		$filename = sprintf( "%s/%s-%07d-%s.html",
			$this->outputDirectory,
			$this->prefix,
			$this->count,
			$sanitized );
		$this->output( sprintf( "%s\t%s\n", $filename, $display ) );

		$user = new User();
		$options = ParserOptions::newFromUser( $user );

		$content = $rev->getContent();
		$contentRenderer = $this->getServiceContainer()->getContentRenderer();
		// ContentRenderer expects a RevisionRecord, and all we have is a
		// WikiRevision from the dump.  Make a fake MutableRevisionRecord to
		// satisfy it -- the only thing ::getParserOutput actually needs is
		// the revision ID and revision timestamp.
		$mutableRev = new MutableRevisionRecord( $rev->getTitle() );
		$mutableRev->setId( $rev->getID() );
		$mutableRev->setTimestamp( $rev->getTimestamp() );
		$output = $contentRenderer->getParserOutput(
			$content, $title, $mutableRev, $options
		);

		file_put_contents( $filename,
			"<!DOCTYPE html>\n" .
			"<html lang=\"en\" dir=\"ltr\">\n" .
			"<head>\n" .
			"<meta charset=\"UTF-8\" />\n" .
			"<meta name=\"color-scheme\" content=\"light dark\">" .
			"<title>" . htmlspecialchars( $display, ENT_COMPAT ) . "</title>\n" .
			"</head>\n" .
			"<body>\n" .
			// TODO T371004 move runOutputPipeline out of $parserOutput
			$output->runOutputPipeline( $options, [] )->getContentHolderText() .
			"</body>\n" .
			"</html>" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = DumpRenderer::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
