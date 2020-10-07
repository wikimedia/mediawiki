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

use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

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
	/** @var array|null (path sha1 => stat) Pre-computed dst stat entries from listings */
	protected $statCache = null;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Copy files in one backend to another.' );
		$this->addOption( 'src', 'Backend containing the source files', true, true );
		$this->addOption( 'dst', 'Backend where files should be copied to', true, true );
		$this->addOption( 'containers', 'Pipe separated list of containers', true, true );
		$this->addOption( 'subdir', 'Only do items in this child directory', false, true );
		$this->addOption( 'ratefile', 'File to check periodically for batch size', false, true );
		$this->addOption( 'prestat', 'Stat the destination files first (try to use listings)' );
		$this->addOption( 'skiphash', 'Skip SHA-1 sync checks for files' );
		$this->addOption( 'missingonly', 'Only copy files missing from destination listing' );
		$this->addOption( 'syncviadelete', 'Delete destination files missing from source listing' );
		$this->addOption( 'utf8only', 'Skip source files that do not have valid UTF-8 names' );
		$this->setBatchSize( 50 );
	}

	public function execute() {
		$backendGroup = MediaWikiServices::getInstance()->getFileBackendGroup();
		$src = $backendGroup->get( $this->getOption( 'src' ) );
		$dst = $backendGroup->get( $this->getOption( 'dst' ) );
		$containers = explode( '|', $this->getOption( 'containers' ) );
		$subDir = rtrim( $this->getOption( 'subdir', '' ), '/' );

		$rateFile = $this->getOption( 'ratefile' );

		foreach ( $containers as $container ) {
			if ( $subDir != '' ) {
				$backendRel = "$container/$subDir";
				$this->output( "Doing container '$container', directory '$subDir'...\n" );
			} else {
				$backendRel = $container;
				$this->output( "Doing container '$container'...\n" );
			}

			if ( $this->hasOption( 'missingonly' ) ) {
				$this->output( "\tBuilding list of missing files..." );
				$srcPathsRel = $this->getListingDiffRel( $src, $dst, $backendRel );
				$this->output( count( $srcPathsRel ) . " file(s) need to be copied.\n" );
			} else {
				$srcPathsRel = $src->getFileList( [
					'dir' => $src->getRootStoragePath() . "/$backendRel",
					'adviseStat' => true // avoid HEADs
				] );
				if ( $srcPathsRel === null ) {
					$this->fatalError( "Could not list files in $container." );
				}
			}

			if ( $this->getOption( 'prestat' ) && !$this->hasOption( 'missingonly' ) ) {
				// Build the stat cache for the destination files
				$this->output( "\tBuilding destination stat cache..." );
				$dstPathsRel = $dst->getFileList( [
					'dir' => $dst->getRootStoragePath() . "/$backendRel",
					'adviseStat' => true // avoid HEADs
				] );
				if ( $dstPathsRel === null ) {
					$this->fatalError( "Could not list files in $container." );
				}
				$this->statCache = [];
				foreach ( $dstPathsRel as $dstPathRel ) {
					$path = $dst->getRootStoragePath() . "/$backendRel/$dstPathRel";
					$this->statCache[sha1( $path )] = $dst->getFileStat( [ 'src' => $path ] );
				}
				$this->output( "done [" . count( $this->statCache ) . " file(s)]\n" );
			}

			$this->output( "\tCopying file(s)...\n" );
			$count = 0;
			$batchPaths = [];
			foreach ( $srcPathsRel as $srcPathRel ) {
				// Check up on the rate file periodically to adjust the concurrency
				if ( $rateFile && ( !$count || ( $count % 500 ) == 0 ) ) {
					$this->setBatchSize( max( 1, (int)file_get_contents( $rateFile ) ) );
					$this->output( "\tBatch size is now {$this->getBatchSize()}.\n" );
				}
				$batchPaths[$srcPathRel] = 1; // remove duplicates
				if ( count( $batchPaths ) >= $this->getBatchSize() ) {
					$this->copyFileBatch( array_keys( $batchPaths ), $backendRel, $src, $dst );
					$batchPaths = []; // done
				}
				++$count;
			}
			if ( count( $batchPaths ) ) { // left-overs
				$this->copyFileBatch( array_keys( $batchPaths ), $backendRel, $src, $dst );
			}
			$this->output( "\tCopied $count file(s).\n" );

			if ( $this->hasOption( 'syncviadelete' ) ) {
				$this->output( "\tBuilding list of excess destination files..." );
				$delPathsRel = $this->getListingDiffRel( $dst, $src, $backendRel );
				$this->output( count( $delPathsRel ) . " file(s) need to be deleted.\n" );

				$this->output( "\tDeleting file(s)...\n" );
				$count = 0;
				$batchPaths = [];
				foreach ( $delPathsRel as $delPathRel ) {
					// Check up on the rate file periodically to adjust the concurrency
					if ( $rateFile && ( !$count || ( $count % 500 ) == 0 ) ) {
						$this->setBatchSize( max( 1, (int)file_get_contents( $rateFile ) ) );
						$this->output( "\tBatch size is now {$this->getBatchSize()}.\n" );
					}
					$batchPaths[$delPathRel] = 1; // remove duplicates
					if ( count( $batchPaths ) >= $this->getBatchSize() ) {
						$this->delFileBatch( array_keys( $batchPaths ), $backendRel, $dst );
						$batchPaths = []; // done
					}
					++$count;
				}
				if ( count( $batchPaths ) ) { // left-overs
					$this->delFileBatch( array_keys( $batchPaths ), $backendRel, $dst );
				}

				$this->output( "\tDeleted $count file(s).\n" );
			}

			if ( $subDir != '' ) {
				$this->output( "Finished container '$container', directory '$subDir'.\n" );
			} else {
				$this->output( "Finished container '$container'.\n" );
			}
		}

		$this->output( "Done.\n" );
	}

	/**
	 * @param FileBackend $src
	 * @param FileBackend $dst
	 * @param string $backendRel
	 * @return string[] (rel paths in $src minus those in $dst)
	 */
	protected function getListingDiffRel( FileBackend $src, FileBackend $dst, $backendRel ) {
		$srcPathsRel = $src->getFileList( [
			'dir' => $src->getRootStoragePath() . "/$backendRel" ] );
		if ( $srcPathsRel === null ) {
			$this->fatalError( "Could not list files in source container." );
		}
		$dstPathsRel = $dst->getFileList( [
			'dir' => $dst->getRootStoragePath() . "/$backendRel" ] );
		if ( $dstPathsRel === null ) {
			$this->fatalError( "Could not list files in destination container." );
		}
		// Get the list of destination files
		$relFilesDstSha1 = [];
		foreach ( $dstPathsRel as $dstPathRel ) {
			$relFilesDstSha1[sha1( $dstPathRel )] = 1;
		}
		unset( $dstPathsRel ); // free
		// Get the list of missing files
		$missingPathsRel = [];
		foreach ( $srcPathsRel as $srcPathRel ) {
			if ( !isset( $relFilesDstSha1[sha1( $srcPathRel )] ) ) {
				$missingPathsRel[] = $srcPathRel;
			}
		}
		unset( $srcPathsRel ); // free

		return $missingPathsRel;
	}

	/**
	 * @param string[] $srcPathsRel
	 * @param string $backendRel
	 * @param FileBackend $src
	 * @param FileBackend $dst
	 * @return void
	 */
	protected function copyFileBatch(
		array $srcPathsRel, $backendRel, FileBackend $src, FileBackend $dst
	) {
		$ops = [];
		$fsFiles = [];
		$copiedRel = []; // for output message
		$domainId = $src->getDomainId();

		// Download the batch of source files into backend cache...
		if ( $this->hasOption( 'missingonly' ) ) {
			$srcPaths = [];
			foreach ( $srcPathsRel as $srcPathRel ) {
				$srcPaths[] = $src->getRootStoragePath() . "/$backendRel/$srcPathRel";
			}
			$t_start = microtime( true );
			$fsFiles = $src->getLocalReferenceMulti( [ 'srcs' => $srcPaths, 'latest' => 1 ] );
			$elapsed_ms = floor( ( microtime( true ) - $t_start ) * 1000 );
			$this->output( "\n\tDownloaded these file(s) [{$elapsed_ms}ms]:\n\t" .
				implode( "\n\t", $srcPaths ) . "\n\n" );
		}

		// Determine what files need to be copied over...
		foreach ( $srcPathsRel as $srcPathRel ) {
			$srcPath = $src->getRootStoragePath() . "/$backendRel/$srcPathRel";
			$dstPath = $dst->getRootStoragePath() . "/$backendRel/$srcPathRel";
			if ( $this->hasOption( 'utf8only' ) && !mb_check_encoding( $srcPath, 'UTF-8' ) ) {
				$this->error( "$domainId: Detected illegal (non-UTF8) path for $srcPath." );
				continue;
			} elseif ( !$this->hasOption( 'missingonly' )
				&& $this->filesAreSame( $src, $dst, $srcPath, $dstPath )
			) {
				$this->output( "\tAlready have $srcPathRel.\n" );
				continue; // assume already copied...
			}
			$fsFile = array_key_exists( $srcPath, $fsFiles )
				? $fsFiles[$srcPath]
				: $src->getLocalReference( [ 'src' => $srcPath, 'latest' => 1 ] );
			if ( !$fsFile ) {
				$src->clearCache( [ $srcPath ] );
				if ( $src->fileExists( [ 'src' => $srcPath, 'latest' => 1 ] ) === false ) {
					$this->error( "$domainId: File '$srcPath' was listed but does not exist." );
				} else {
					$this->error( "$domainId: Could not get local copy of $srcPath." );
				}
				continue;
			} elseif ( !$fsFile->exists() ) {
				// FSFileBackends just return the path for getLocalReference() and paths with
				// illegal slashes may get normalized to a different path. This can cause the
				// local reference to not exist...skip these broken files.
				$this->error( "$domainId: Detected possible illegal path for $srcPath." );
				continue;
			}
			$fsFiles[] = $fsFile; // keep TempFSFile objects alive as needed
			// Note: prepare() is usually fast for key/value backends
			$status = $dst->prepare( [ 'dir' => dirname( $dstPath ), 'bypassReadOnly' => 1 ] );
			if ( !$status->isOK() ) {
				$this->error( Status::wrap( $status )->getMessage( false, false, 'en' )->text() );
				$this->fatalError( "$domainId: Could not copy $srcPath to $dstPath." );
			}
			$ops[] = [ 'op' => 'store',
				'src' => $fsFile->getPath(), 'dst' => $dstPath, 'overwrite' => 1 ];
			$copiedRel[] = $srcPathRel;
		}

		// Copy in the batch of source files...
		$t_start = microtime( true );
		$status = $dst->doQuickOperations( $ops, [ 'bypassReadOnly' => 1 ] );
		if ( !$status->isOK() ) {
			sleep( 10 ); // wait and retry copy again
			$status = $dst->doQuickOperations( $ops, [ 'bypassReadOnly' => 1 ] );
		}
		$elapsed_ms = floor( ( microtime( true ) - $t_start ) * 1000 );
		if ( !$status->isOK() ) {
			$this->error( Status::wrap( $status )->getMessage( false, false, 'en' )->text() );
			$this->fatalError( "$domainId: Could not copy file batch." );
		} elseif ( count( $copiedRel ) ) {
			$this->output( "\n\tCopied these file(s) [{$elapsed_ms}ms]:\n\t" .
				implode( "\n\t", $copiedRel ) . "\n\n" );
		}
	}

	/**
	 * @param string[] $dstPathsRel
	 * @param string $backendRel
	 * @param FileBackend $dst
	 * @return void
	 */
	protected function delFileBatch(
		array $dstPathsRel, $backendRel, FileBackend $dst
	) {
		$ops = [];
		$deletedRel = []; // for output message
		$domainId = $dst->getDomainId();

		// Determine what files need to be copied over...
		foreach ( $dstPathsRel as $dstPathRel ) {
			$dstPath = $dst->getRootStoragePath() . "/$backendRel/$dstPathRel";
			$ops[] = [ 'op' => 'delete', 'src' => $dstPath ];
			$deletedRel[] = $dstPathRel;
		}

		// Delete the batch of source files...
		$t_start = microtime( true );
		$status = $dst->doQuickOperations( $ops, [ 'bypassReadOnly' => 1 ] );
		if ( !$status->isOK() ) {
			sleep( 10 ); // wait and retry copy again
			$status = $dst->doQuickOperations( $ops, [ 'bypassReadOnly' => 1 ] );
		}
		$elapsed_ms = floor( ( microtime( true ) - $t_start ) * 1000 );
		if ( !$status->isOK() ) {
			$this->error( Status::wrap( $status )->getMessage( false, false, 'en' )->text() );
			$this->fatalError( "$domainId: Could not delete file batch." );
		} elseif ( count( $deletedRel ) ) {
			$this->output( "\n\tDeleted these file(s) [{$elapsed_ms}ms]:\n\t" .
				implode( "\n\t", $deletedRel ) . "\n\n" );
		}
	}

	/**
	 * @param FileBackend $src
	 * @param FileBackend $dst
	 * @param string $sPath
	 * @param string $dPath
	 * @return bool
	 */
	protected function filesAreSame( FileBackend $src, FileBackend $dst, $sPath, $dPath ) {
		$skipHash = $this->hasOption( 'skiphash' );
		$srcStat = $src->getFileStat( [ 'src' => $sPath ] );
		$dPathSha1 = sha1( $dPath );
		if ( $this->statCache !== null ) {
			// All dst files are already in stat cache
			$dstStat = $this->statCache[$dPathSha1] ?? false;
		} else {
			$dstStat = $dst->getFileStat( [ 'src' => $dPath ] );
		}
		// Initial fast checks to see if files are obviously different
		$sameFast = (
			is_array( $srcStat ) // sanity check that source exists
			&& is_array( $dstStat ) // dest exists
			&& $srcStat['size'] === $dstStat['size']
		);
		// More thorough checks against files
		if ( !$sameFast ) {
			$same = false; // no need to look farther
		} elseif ( isset( $srcStat['md5'] ) && isset( $dstStat['md5'] ) ) {
			// If MD5 was already in the stat info, just use it.
			// This is useful as many objects stores can return this in object listing,
			// so we can use it to avoid slow per-file HEADs.
			$same = ( $srcStat['md5'] === $dstStat['md5'] );
		} elseif ( $skipHash ) {
			// This mode is good for copying to a backup location or resyncing clone
			// backends in FileBackendMultiWrite (since they get writes second, they have
			// higher timestamps). However, when copying the other way, this hits loads of
			// false positives (possibly 100%) and wastes a bunch of time on GETs/PUTs.
			// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
			$same = ( $srcStat['mtime'] <= $dstStat['mtime'] );
		} else {
			// This is the slowest method which does many per-file HEADs (unless an object
			// store tracks SHA-1 in listings).
			$same = ( $src->getFileSha1Base36( [ 'src' => $sPath, 'latest' => 1 ] )
				=== $dst->getFileSha1Base36( [ 'src' => $dPath, 'latest' => 1 ] ) );
		}

		return $same;
	}
}

$maintClass = CopyFileBackend::class;
require_once RUN_MAINTENANCE_IF_MAIN;
