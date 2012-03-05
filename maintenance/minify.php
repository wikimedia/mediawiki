<?php
/**
 * Minify a file or set of files
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class MinifyScript extends Maintenance {
	var $outDir;

	public function __construct() {
		parent::__construct();
		$this->addOption( 'outfile',
			'File for output. Only a single file may be specified for input.',
			false, true );
		$this->addOption( 'outdir',
			"Directory for output. If this is not specified, and neither is --outfile, then the\n" .
			"output files will be sent to the same directories as the input files.",
			false, true );
		$this->mDescription = "Minify a file or set of files.\n\n" .
			"If --outfile is not specified, then the output file names will have a .min extension\n" .
			"added, e.g. jquery.js -> jquery.min.js.";

	}

	public function execute() {
		if ( !count( $this->mArgs ) ) {
			$this->error( "minify.php: At least one input file must be specified." );
			exit( 1 );
		}

		if ( $this->hasOption( 'outfile' ) ) {
			if ( count( $this->mArgs ) > 1 ) {
				$this->error( '--outfile may only be used with a single input file.' );
				exit( 1 );
			}

			// Minify one file
			$this->minify( $this->getArg( 0 ), $this->getOption( 'outfile' ) );
			return;
		}

		$outDir = $this->getOption( 'outdir', false );

		foreach ( $this->mArgs as $arg ) {
			$inPath = realpath( $arg );
			$inName = basename( $inPath );
			$inDir = dirname( $inPath );

			if ( strpos( $inName, '.min.' ) !== false ) {
				$this->error( "Skipping $inName\n" );
				continue;
			}

			if ( !file_exists( $inPath ) ) {
				$this->error( "File does not exist: $arg", true );
			}

			$extension = $this->getExtension( $inName );
			$outName = substr( $inName, 0, -strlen( $extension ) ) . 'min.' . $extension;
			if ( $outDir === false ) {
				$outPath = $inDir . '/' . $outName;
			} else {
				$outPath = $outDir . '/' . $outName;
			}

			$this->minify( $inPath, $outPath );
		}
	}

	public function getExtension( $fileName ) {
		$dotPos = strrpos( $fileName, '.' );
		if ( $dotPos === false ) {
			$this->error( "No file extension, cannot determine type: $arg" );
			exit( 1 );
		}
		return substr( $fileName, $dotPos + 1 );
	}

	public function minify( $inPath, $outPath ) {
		$extension = $this->getExtension( $inPath );
		$this->output( basename( $inPath ) . ' -> ' . basename( $outPath ) . '...' );

		$inText = file_get_contents( $inPath );
		if ( $inText === false ) {
			$this->error( "Unable to open file $inPath for reading." );
			exit( 1 );
		}
		$outFile = fopen( $outPath, 'w' );
		if ( !$outFile ) {
			$this->error( "Unable to open file $outPath for writing." );
			exit( 1 );
		}

		switch ( $extension ) {
			case 'js':
				$outText = JSMin::minify( $inText );
				break;
			case 'css':
				$outText = CSSMin::minify( $inText );
				break;
			default:
				$this->error( "No minifier defined for extension \"$extension\"" );
		}

		fwrite( $outFile, $outText );
		fclose( $outFile );
		$this->output( " ok\n" );
	}
}

$maintClass = 'MinifyScript';
require_once( DO_MAINTENANCE );
