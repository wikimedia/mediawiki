<?php
/**
 * Copy all files in some containers of one backend to another.
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

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Copy all files in one container of one backend to another.
 *
 * This can also be used to re-shard the files for one backend using the
 * config of second backend. The second backend should have the same config
 * as the first, except for it having a different name and different sharding
 * configuration. The backend should be made read-only while this runs.
 * After this script finishes, the old files in the containers can be deleted.
 *
 * @ingroup Maintenance
 */
class CopyFileBackend extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Copy files in one backend to another.";
		$this->addOption( 'src', 'Backend containing the source files', true, true );
		$this->addOption( 'dst', 'Backend where files should be copied to', true, true );
		$this->addOption( 'containers', 'Pipe separated list of containers', true, true );
		$this->addOption( 'subdir', 'Only do items in this child directory', false, true );
		$this->addOption( 'ratefile', 'File to check periodically for batch size', false, true );
		$this->addOption( 'skiphash', 'Skip SHA-1 sync checks for files' );
		$this->addOption( 'missingonly', 'Only copy files missing from destination listing' );
		$this->addOption( 'utf8only', 'Skip source files that do not have valid UTF-8 names' );
		$this->setBatchSize( 50 );
	}

	public function execute() {
		$src = FileBackendGroup::singleton()->get( $this->getOption( 'src' ) );
		$dst = FileBackendGroup::singleton()->get( $this->getOption( 'dst' ) );
		$containers = explode( '|', $this->getOption( 'containers' ) );
		$subDir = $this->getOption( rtrim( 'subdir', '/' ), '' );

		$rateFile = $this->getOption( 'ratefile' );

		if ( $this->hasOption( 'utf8only' ) && !extension_loaded( 'mbstring' ) ) {
			$this->error( "Cannot check for UTF-8, mbstring extension missing.", 1 ); // die
		}

		$count = 0;
		foreach ( $containers as $container ) {
			if ( $subDir != '' ) {
				$backendRel = "$container/$subDir";
				$this->output( "Doing container '$container', directory '$subDir'...\n" );
			} else {
				$backendRel = $container;
				$this->output( "Doing container '$container'...\n" );
			}

			$srcPathsRel = $src->getFileList( array(
				'dir' => $src->getRootStoragePath() . "/$backendRel" ) );
			if ( $srcPathsRel === null ) {
				$this->error( "Could not list files in $container.", 1 ); // die
			}

			// Do a listing comparison if specified
			if ( $this->hasOption( 'missingonly' ) ) {
				$relFilesSrc = array();
				$relFilesDst = array();
				foreach ( $srcPathsRel as $srcPathRel ) {
					$relFilesSrc[] = $srcPathRel;
				}
				$dstPathsRel = $dst->getFileList( array(
					'dir' => $dst->getRootStoragePath() . "/$backendRel" ) );
				if ( $dstPathsRel === null ) {
					$this->error( "Could not list files in $container.", 1 ); // die
				}
				foreach ( $dstPathsRel as $dstPathRel ) {
					$relFilesDst[] = $dstPathRel;
				}
				// Only copy the missing files over in the next loop
				$srcPathsRel = array_diff( $relFilesSrc, $relFilesDst );
				$this->output( count( $srcPathsRel ) . " file(s) need to be copied.\n" );
				unset( $relFilesSrc );
				unset( $relFilesDst );
			}

			$batchPaths = array();
			foreach ( $srcPathsRel as $srcPathRel ) {
				// Check up on the rate file periodically to adjust the concurrency
				if ( $rateFile && ( !$count || ( $count % 500 ) == 0 ) ) {
					$this->mBatchSize = max( 1, (int)file_get_contents( $rateFile ) );
					$this->output( "Batch size is now {$this->mBatchSize}.\n" );
				}
				$batchPaths[$srcPathRel] = 1; // remove duplicates
				if ( count( $batchPaths ) >= $this->mBatchSize ) {
					$this->copyFileBatch( array_keys( $batchPaths ), $backendRel, $src, $dst );
					$batchPaths = array(); // done
				}
				++$count;
			}
			if ( count( $batchPaths ) ) { // left-overs
				$this->copyFileBatch( array_keys( $batchPaths ), $backendRel, $src, $dst );
				$batchPaths = array(); // done
			}

			if ( $subDir != '' ) {
				$this->output( "Finished container '$container', directory '$subDir'.\n" );
			} else {
				$this->output( "Finished container '$container'.\n" );
			}
		}

		$this->output( "Done [$count file(s)].\n" );
	}

	protected function copyFileBatch(
		array $srcPathsRel, $backendRel, FileBackend $src, FileBackend $dst
	) {
		$ops = array();
		$fsFiles = array();
		$copiedRel = array(); // for output message
		foreach ( $srcPathsRel as $srcPathRel ) {
			$srcPath = $src->getRootStoragePath() . "/$backendRel/$srcPathRel";
			$dstPath = $dst->getRootStoragePath() . "/$backendRel/$srcPathRel";
			if ( $this->hasOption( 'utf8only' ) && !mb_check_encoding( $srcPath, 'UTF-8' ) ) {
				$this->error( "Detected illegal (non-UTF8) path for $srcPath." );
				continue;
			} elseif ( $this->filesAreSame( $src, $dst, $srcPath, $dstPath ) ) {
				$this->output( "Already have $srcPathRel.\n" );
				continue; // assume already copied...
			}
			// Note: getLocalReference() is fast for FS backends
			$fsFile = $src->getLocalReference( array( 'src' => $srcPath, 'latest' => 1 ) );
			if ( !$fsFile ) {
				$this->error( "Could not get local copy of $srcPath.", 1 ); // die
			} elseif ( !$fsFile->exists() ) {
				// FSFileBackends just return the path for getLocalReference() and paths with
				// illegal slashes may get normalized to a different path. This can cause the
				// local reference to not exist...skip these broken files.
				$this->error( "Detected possible illegal path for $srcPath." );
				continue;
			}
			$fsFiles[] = $fsFile; // keep TempFSFile objects alive as needed
			// Note: prepare() is usually fast for key/value backends
			$status = $dst->prepare( array( 'dir' => dirname( $dstPath ), 'bypassReadOnly' => 1 ) );
			if ( !$status->isOK() ) {
				$this->error( print_r( $status->getErrorsArray(), true ) );
				$this->error( "Could not copy $srcPath to $dstPath.", 1 ); // die
			}
			$ops[] = array( 'op' => 'store',
				'src' => $fsFile->getPath(), 'dst' => $dstPath, 'overwrite' => 1 );
			$copiedRel[] = $srcPathRel;
		}

		$t_start = microtime( true );
		$status = $dst->doQuickOperations( $ops, array( 'bypassReadOnly' => 1 ) );
		if ( !$status->isOK() ) {
			sleep( 10 ); // wait and retry copy again
			$status = $dst->doQuickOperations( $ops, array( 'bypassReadOnly' => 1 ) );
		}
		$ellapsed_ms = floor( ( microtime( true ) - $t_start ) * 1000 );
		if ( !$status->isOK() ) {
			$this->error( print_r( $status->getErrorsArray(), true ) );
			$this->error( "Could not copy file batch.", 1 ); // die
		} elseif ( count( $copiedRel ) ) {
			$this->output( "\nCopied these file(s) [{$ellapsed_ms}ms]:\n" .
				implode( "\n", $copiedRel ) . "\n\n" );
		}
	}

	protected function filesAreSame( FileBackend $src, FileBackend $dst, $sPath, $dPath ) {
		$skipHash = $this->hasOption( 'skiphash' );
		return (
			( $src->fileExists( array( 'src' => $sPath, 'latest' => 1 ) )
				=== $dst->fileExists( array( 'src' => $dPath, 'latest' => 1 ) ) // short-circuit
			) && ( $src->getFileSize( array( 'src' => $sPath, 'latest' => 1 ) )
				=== $dst->getFileSize( array( 'src' => $dPath, 'latest' => 1 ) ) // short-circuit
			) && ( $skipHash || ( $src->getFileSha1Base36( array( 'src' => $sPath, 'latest' => 1 ) )
				=== $dst->getFileSha1Base36( array( 'src' => $dPath, 'latest' => 1 ) )
			) )
		);
	}
}

$maintClass = 'CopyFileBackend';
require_once( RUN_MAINTENANCE_IF_MAIN );
