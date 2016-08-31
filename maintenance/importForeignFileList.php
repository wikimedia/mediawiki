<?php
/**
 * Import a list of files from a foreigh api repo
 *
 * @file
 * @ingroup Maintenance
 * @author Mark A. Hershberger
 */

require_once __DIR__ . '/Maintenance.php';
class ImportForeignFileList extends Maintenance {
	protected $repo;
	protected $forceOverwrite;
	protected $onlyShowWanted = false;

	static protected $apiURL = 'https://commons.wikimedia.org/w/api.php';

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Import foreign file list";

		$this->addOption( 'apiUrl', 'URL to retrieve from (' .self::$apiURL. ')',
						  false, true, 'u' );
		$this->addOption( 'wantedFiles', 'Only print out wanted files', false, false, 'w' );
		$this->addOption( 'downloadDir', 'If provided, the files will only be downloaded to ' .
						  'this directory and not imported.', false, true, 'd' );
		$this->addOption( 'force', 'Force overwriting existing files.', false, false, 'f' );

		$this->addArg( 'list', 'The file to read the wanted list from.  If ' .
					   'not specified, uses the list from ' .
					   '[[Special:WantedFiles]].', false, true );
	}

	public function execute() {
		$this->init();
		foreach ( $this->getFileList() as $item ) {
			$file = $this->getFileName( $item );
			if ( $this->onlyShowWanted ) {
				$this->output( "$file\n" );
			} else if ( $this->fileAlreadyExists( $file ) ) {
				$this->output( "File exists, not overwriting without --force: $file\n" );
			} else if ( $file !== false ) {
				$this->output( "Trying to retrieve: $file ... " );
				$info = $this->getImageInfo( $file );
				$fileName = $this->fetchFile( $file, $info );
				if ( $fileName ) {
					$this->output( "success. " );
					if ( $this->importFile( $file, $fileName ) ) {
						$this->output( "Successfully imported." );
					}
					$this->output( "\n" );
				} else {
					$this->output( "Not found on remote, skipping.\n" );
				}
			} else {
				$this->output( "Problem turning $item into proper filename" );
			}
		}
	}

	public function init() {
		global $wgShowExceptionDetails, $wgForeignFileRepos, $wgUploadDirectory;
		$wgShowExceptionDetails = true;

		$this->failed = 0;
		$this->dir = $this->getOption( 'downloadDir' );
		$this->onlyShowWanted = $this->getOption( 'wantedFiles' );
		$this->forceOverwrite = $this->getOption( 'force' ) ? true : false;
		if ( $this->dir && ! ( is_dir( $this->dir ) && is_readable( $this->dir ) ) ) {
			$this->error( 'Directory (' . $this->dir . ') cannot be used. ' .
						  'Make sure it exists and is writable.', 1 );
		} else if ( !$this->dir ) {
			$this->dir = false; // php would eval null as false, so
								// make sure we really have false.
		}
		$wgForeignFileRepos[] = [
			'class' => 'ForeignAPIRepo',
			'name' => '_thisrepo',
			'backend' => '_thisrepo-backend',
			'hashLevels' => 2,
			'directory' => $wgUploadDirectory, // b/c (copied from Setup.php)
		];
		$this->repo = new \ForeignAPIRepo(
			[ 'name' => 'iter',
			  'apibase' => $this->getOption( 'apiUrl', self::$apiURL ),
			  'backend' => '_thisrepo-backend'
			]
		);
	}

	public function getFileList() {
		$file = $this->getArg( 0 );
		if ( $file && is_readable( $file ) ) {
			return explode("\n", file_get_contents( $file ) );
		}

		$files = new \WantedFilesPage();
		return $files->reallyDoQuery( false );
	}


	public function getImageInfo( $name ) {
		$data = $this->repo->getImageData( $name );
		return $this->repo->getImageInfo( $data );
	}

	public function fetchFile( $name, $info ) {
		if ( isset( $info['url'] ) ) {
			$content = Http::get( $info['url'], [ 'userAgent' => 'MW importImages cli' ] );
			if ( $content !== false ) {
				$file = $this->dir . "/$name";
				if ( $this->dir === false ) {
					$file = tempnam( wfTempDir(), "import" );
				}
				$fh = fopen( $file, "w" );
				fwrite( $fh, $content );
				fclose( $fh );
				return $file;
			}
		}
		return false;
	}

	public function importFile( $file, $fileName ) {
		if ( file_exists( $fileName ) && $this->dir === false ) {
			// Copied from importImages.php
			$props = FSFile::getPropsFromPath( $fileName );
			$flags = 0;
			$publishOptions = [];
			$handler = MediaHandler::getHandler( $props['mime'] );
			if ( $handler ) {
				$publishOptions['headers'] = $handler->getStreamHeaders( $props['metadata'] );
			} else {
				$publishOptions['headers'] = [];
			}
			$image = wfLocalFile( $file );
			$archive = $image->publish( $fileName, $flags, $publishOptions );
			if ( !$archive->isGood() ) {
				$this->error( "failed. (" . $archive->getWikiText( false, false, 'en' ) . ")" );
				$this->failed++;
			} else {
				$summary = $commentText = "Imported via MW importImages CLI";
				$timestamp = false;
				$image->recordUpload2(
					$archive->value,
					$summary,
					$commentText,
					$props,
					$timestamp
				);
			}
			unlink( $fileName );
		}
		return false;
	}

	public function getFileName( $file ) {
		if ( is_object( $file ) ) {
			$file = $file->title;
		}
		if ( substr( $file, 0, 5 ) === "File:" ) {
			$file = substr( $file, 5 );
		}

		return $file;
	}

	public function fileAlreadyExists( $file ) {
		if ( $this->dir && file_exists( $this->dir . "/$file" ) && !$this->forceOverwrite ) {
			return true;
		} else if ( !$this->dir ) {
			$image = wfLocalFile( $file );
			return $image->exists();
		}
		return false;
	}

}

$maintClass = "ImportForeignFileList";
require_once RUN_MAINTENANCE_IF_MAIN;
