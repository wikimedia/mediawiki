<?php

require_once( dirname( __FILE__ ) .'/Maintenance.php' );

class MaintenanceFormatInstallDoc extends Maintenance {
	function __construct() {
		parent::__construct();
		$this->addArg( 'path', 'The file name to format', false );
		$this->addOption( 'outfile', 'The output file name', false, true );
		$this->addOption( 'html', 'Use HTML output format. By default, wikitext is used.' );
	}

	function execute() {
		if ( $this->hasArg( 0 ) ) {
			$fileName = $this->getArg( 0 );
			$inFile = fopen( $fileName, 'r' );
			if ( !$inFile ) {
				$this->error( "Unable to open input file \"$fileName\"" );
				exit( 1 );
			}
		} else {
			$inFile = STDIN;
		}

		if ( $this->hasOption( 'outfile' ) ) {
			$fileName = $this->getOption( 'outfile' );
			$outFile = fopen( $fileName, 'w' );
			if ( !$outFile ) {
				$this->error( "Unable to open output file \"$fileName\"" );
				exit( 1 );
			}
		} else {
			$outFile = STDOUT;
		}

		$inText = stream_get_contents( $inFile );
		$outText = InstallDocFormatter::format( $inText );

		if ( $this->hasOption( 'html' ) ) {
			global $wgParser;
			$opt = new ParserOptions;
			$title = Title::newFromText( 'Text file' );
			$out = $wgParser->parse( $outText, $title, $opt );
			$outText = "<html><body>\n" . $out->getText() . "\n</body></html>\n";
		}

		fwrite( $outFile, $outText );
	}
}

$maintClass = 'MaintenanceFormatInstallDoc';
require_once( RUN_MAINTENANCE_IF_MAIN );


