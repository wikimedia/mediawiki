<?php

require __DIR__ . '/../Maintenance.php';

class HHVMMakeRepo extends Maintenance {
	function __construct() {
		parent::__construct();
		$this->addOption( 'output', 'Output filename', true, true, 'o' );
		$this->addOption( 'input-dir', 'Extra input directory', false, true, 'd', true );
		$this->addOption( 'exclude-dir', 'Directory to exclude', false, true, false, true );
		$this->addOption( 'extension', 'Extra file extension', false, true, false, true );
		$this->addOption( 'hhvm', 'Location of HHVM binary', false, true );
		$this->addOption( 'base-dir', 'The root of all source files. ' .
			'This must match hhvm.server.source_root in the server\'s configuration file.',
			true, true );
		$this->addOption( 'verbose', 'Log level 0-3', false, true, 'v' );
	}

	function execute() {
		global $wgExtensionCredits, $IP;

		$dirs = [ $IP ];
		foreach ( $wgExtensionCredits as $type => $extensions ) {
			foreach ( $extensions as $extension ) {
				if ( isset( $extension['path'] ) ) {
					$dirs[] = dirname( $extension['path'] );
				}
			}
		}

		$dirs = array_merge( $dirs, $this->getOption( 'input-dir', [] ) );
		$extensions =  [ 'php' => true, 'inc' => true ] +
			array_flip( $this->getOption( 'extension', [] ) );

		$dirs = array_unique( $dirs );

		$baseDir = $this->getOption( 'base-dir' );
		$excludeDirs = array_map( 'realpath', $this->getOption( 'exclude-dir', [] ) );

		if ( $baseDir !== '' && substr( $baseDir, -1 ) !== '/' ) {
			$baseDir .= '/';
		}

		$unfilteredFiles = [ "$IP/LocalSettings.php" ];
		foreach ( $dirs as $dir ) {
			$this->appendDir( $unfilteredFiles, $dir );
		}

		$files = [];
		foreach ( $unfilteredFiles as $file ) {
			$dotPos = strrpos( $file, '.' );
			$slashPos = strrpos( $file, '/' );
			if ( $dotPos === false || $slashPos === false || $dotPos < $slashPos ) {
				continue;
			}
			$extension = substr( $file, $dotPos + 1 );
			if ( !isset( $extensions[$extension] ) ) {
				continue;
			}
			$canonical = realpath( $file );
			foreach ( $excludeDirs as $excluded ) {
				if ( substr( $canonical, 0, strlen( $excluded ) ) === $excluded ) {
					continue 2;
				}
			}
			if ( substr( $file, 0, strlen( $baseDir ) ) === $baseDir ) {
				$file = substr( $file, strlen( $baseDir ) );
			}
			$files[] = $file;
		}

		$files = array_unique( $files );

		print "Found " . count( $files ) . " files in " .
			count( $dirs ) . " directories\n";

		$tmpDir = wfTempDir() . '/mw-make-repo' . mt_rand( 0, 1<<31 );
		if ( !mkdir( $tmpDir ) ) {
			$this->error( 'Unable to create temporary directory', 1 );
		}
		file_put_contents( "$tmpDir/file-list", implode( "\n", $files ) );

		$hhvm = $this->getOption( 'hhvm', 'hhvm' );
		$verbose = $this->getOption( 'verbose', 3 );
		$cmd = wfEscapeShellArg(
			'hhvm',
			'--hphp',
		    '--target', 'hhbc',
			'--format', 'binary',
			'--force', '1',
			'--keep-tempdir', '1',
			'--log', $verbose,
			'--input-dir', $baseDir,
			'--input-list', "$tmpDir/file-list",
			'--output-dir', $tmpDir );
		print "$cmd\n";
		passthru( $cmd, $ret );
		if ( $ret ) {
			$this->cleanupTemp( $tmpDir );
			$this->error( "Error: HHVM returned error code $ret", 1 );
		}
		if ( !rename( "$tmpDir/hhvm.hhbc", $this->getOption( 'output' ) ) ) {
			$this->cleanupTemp( $tmpDir );
			$this->error( "Error: unable to rename output file", 1 );
		}
		$this->cleanupTemp( $tmpDir );
		return 0;
	}

	private function cleanupTemp( $tmpDir ) {
		if ( file_exists( "$tmpDir/hhvm.hhbc" ) ) {
			unlink( "$tmpDir/hhvm.hhbc" );
		}
		if ( file_exists( "$tmpDir/Stats.js" ) ) {
			unlink( "$tmpDir/Stats.js" );
		}

		unlink( "$tmpDir/file-list" );
		rmdir( $tmpDir );
	}

	private function appendDir( &$files, $dir ) {
		$iter = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator(
				$dir,
				FilesystemIterator::UNIX_PATHS
			),
			RecursiveIteratorIterator::LEAVES_ONLY
		);
		foreach ( $iter as $file => $fileInfo ) {
			if ( $fileInfo->isFile() ) {
				$files[] = $file;
			}
		}
	}
}

$maintClass = 'HHVMMakeRepo';
require RUN_MAINTENANCE_IF_MAIN;
