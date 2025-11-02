<?php

namespace Wikimedia\FileBackend\FSFile;

use Wikimedia\AtEase\AtEase;

/**
 * @ingroup FileBackend
 */
class TempFSFileFactory {
	/** @var string|null */
	private $tmpDirectory;

	/**
	 * @param string|null $tmpDirectory A directory to put the temporary files in, e.g.,
	 *   $wgTmpDirectory. If null, we'll try to find one ourselves.
	 */
	public function __construct( $tmpDirectory = null ) {
		$this->tmpDirectory = $tmpDirectory;
	}

	/**
	 * Make a new temporary file on the file system.
	 * Temporary files may be purged when the file object falls out of scope.
	 *
	 * @param string $prefix
	 * @param string $extension Optional file extension
	 * @return TempFSFile|null
	 */
	public function newTempFSFile( $prefix, $extension = '' ) {
		$ext = ( $extension != '' ) ? ".{$extension}" : '';
		$tmpDirectory = $this->tmpDirectory;
		if ( !is_string( $tmpDirectory ) ) {
			$tmpDirectory = TempFSFile::getUsableTempDirectory();
		}

		$attempts = 5;
		while ( $attempts-- ) {
			$hex = sprintf( '%06x%06x', mt_rand( 0, 0xffffff ), mt_rand( 0, 0xffffff ) );
			$path = "$tmpDirectory/$prefix$hex$ext";
			AtEase::suppressWarnings();
			$newFileHandle = fopen( $path, 'x' );
			AtEase::restoreWarnings();
			if ( $newFileHandle ) {
				fclose( $newFileHandle );
				$tmpFile = new TempFSFile( $path );
				$tmpFile->autocollect();
				// Safely instantiated, end loop.
				return $tmpFile;
			}
		}

		// Give up
		return null; // @codeCoverageIgnore
	}
}

/** @deprecated class alias since 1.44 */
class_alias( TempFSFileFactory::class, 'MediaWiki\FileBackend\FSFile\TempFSFileFactory' );
