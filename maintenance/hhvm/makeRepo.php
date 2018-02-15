<?php

require __DIR__ . '/../Maintenance.php';

class HHVMMakeRepo extends Maintenance {
	function __construct() {
		parent::__construct();
		$this->addDescription( 'Compile PHP sources for this MediaWiki instance, ' .
			'and generate an HHVM bytecode file to be used with HHVM\'s ' .
			'RepoAuthoritative mode. The MediaWiki core installation path and ' .
			'all registered extensions are automatically searched for the file ' .
			'extensions *.php, *.inc, *.php5 and *.phtml.' );
		$this->addOption( 'output', 'Output filename', true, true, 'o' );
		$this->addOption( 'input-dir', 'Add an input directory. ' .
			'This can be specified multiple times.', false, true, 'd', true );
		$this->addOption( 'exclude-dir', 'Directory to exclude. ' .
			'This can be specified multiple times.', false, true, false, true );
		$this->addOption( 'extension', 'Extra file extension', false, true, false, true );
		$this->addOption( 'hhvm', 'Location of HHVM binary', false, true );
		$this->addOption( 'base-dir', 'The root of all source files. ' .
			'This must match hhvm.server.source_root in the server\'s configuration file. ' .
			'By default, the MW core install path will be used.',
			false, true );
		$this->addOption( 'verbose', 'Log level 0-3', false, true, 'v' );
	}

	private static function startsWith( $subject, $search ) {
		return substr( $subject, 0, strlen( $search ) === $search );
	}

	function execute() {
		global $wgExtensionCredits, $IP;

		$dirs = [ $IP ];

		foreach ( $wgExtensionCredits as $type => $extensions ) {
			foreach ( $extensions as $extension ) {
				if ( isset( $extension['path'] )
					&& !self::startsWith( $extension['path'], $IP )
				) {
					$dirs[] = dirname( $extension['path'] );
				}
			}
		}

		$dirs = array_merge( $dirs, $this->getOption( 'input-dir', [] ) );
		$fileExts =
			[
				'php' => true,
				'inc' => true,
				'php5' => true,
				'phtml' => true
			] +
			array_flip( $this->getOption( 'extension', [] ) );

		$dirs = array_unique( $dirs );

		$baseDir = $this->getOption( 'base-dir', $IP );
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
			if ( !isset( $fileExts[$extension] ) ) {
				continue;
			}
			$canonical = realpath( $file );
			foreach ( $excludeDirs as $excluded ) {
				if ( self::startsWith( $canonical, $excluded ) ) {
					continue 2;
				}
			}
			if ( self::startsWith( $file, $baseDir ) ) {
				$file = substr( $file, strlen( $baseDir ) );
			}
			$files[] = $file;
		}

		$files = array_unique( $files );

		print "Found " . count( $files ) . " files in " .
			count( $dirs ) . " directories\n";

		$tmpDir = wfTempDir() . '/mw-make-repo' . mt_rand( 0, 1 << 31 );
		if ( !mkdir( $tmpDir ) ) {
			$this->fatalError( 'Unable to create temporary directory' );
		}
		file_put_contents( "$tmpDir/file-list", implode( "\n", $files ) );

		$hhvm = $this->getOption( 'hhvm', 'hhvm' );
		$verbose = $this->getOption( 'verbose', 3 );
		$cmd = wfEscapeShellArg(
			$hhvm,
			'--hphp',
			'--target', 'hhbc',
			'--format', 'binary',
			'--force', '1',
			'--keep-tempdir', '1',
			'--log', $verbose,
			'-v', 'AllVolatile=true',
			'--input-dir', $baseDir,
			'--input-list', "$tmpDir/file-list",
			'--output-dir', $tmpDir );
		print "$cmd\n";
		passthru( $cmd, $ret );
		if ( $ret ) {
			$this->cleanupTemp( $tmpDir );
			$this->fatalError( "Error: HHVM returned error code $ret" );
		}
		if ( !rename( "$tmpDir/hhvm.hhbc", $this->getOption( 'output' ) ) ) {
			$this->cleanupTemp( $tmpDir );
			$this->fatalError( "Error: unable to rename output file" );
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

$maintClass = HHVMMakeRepo::class;
require RUN_MAINTENANCE_IF_MAIN;
