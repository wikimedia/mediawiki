<?php
/**
 * Format RELEASE-NOTE file to wiki text or HTML markup.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Installer\InstallDocFormatter;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Title\Title;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that formats RELEASE-NOTE file to wiki text or HTML markup.
 *
 * @ingroup Maintenance
 */
class FormatInstallDoc extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addArg( 'path', 'The file name to format', false );
		$this->addOption( 'outfile', 'The output file name', false, true );
		$this->addOption( 'html', 'Use HTML output format. By default, wikitext is used.' );
	}

	public function execute() {
		if ( $this->hasArg( 0 ) ) {
			$fileName = $this->getArg( 0 );
			$inFile = fopen( $fileName, 'r' );
			if ( !$inFile ) {
				$this->fatalError( "Unable to open input file \"$fileName\"" );
			}
		} else {
			$inFile = STDIN;
		}

		if ( $this->hasOption( 'outfile' ) ) {
			$fileName = $this->getOption( 'outfile' );
			$outFile = fopen( $fileName, 'w' );
			if ( !$outFile ) {
				$this->fatalError( "Unable to open output file \"$fileName\"" );
			}
		} else {
			$outFile = STDOUT;
		}

		$inText = stream_get_contents( $inFile );
		$outText = InstallDocFormatter::format( $inText );

		if ( $this->hasOption( 'html' ) ) {
			$parser = $this->getServiceContainer()->getParser();
			$opt = ParserOptions::newFromAnon();
			$title = Title::newFromText( 'Text file' );
			$out = $parser->parse( $outText, $title, $opt );
			$outText = "<html><body>\n" .
				// TODO T371008 consider if using the Content framework makes sense instead of creating the pipeline
				$this->getServiceContainer()->getDefaultOutputPipeline()
					->run( $out, $opt, [] )
					->getContentHolderText()
				. "\n</body></html>\n";
		}

		fwrite( $outFile, $outText );
	}
}

// @codeCoverageIgnoreStart
$maintClass = FormatInstallDoc::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
