<?php
/**
 * Sync one file backend to another based on the journal of later.
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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class SyncFileBackend extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Sync one file backend with another using the journal";
		$this->addOption( 'src', 'Name of backend to sync from', true, true );
		$this->addOption( 'dst', 'Name of destination backend to sync', true, true );
		$this->addOption( 'start', 'Starting journal ID', false, true );
		$this->addOption( 'posdir', 'Directory to record the journal positions', false, true );
		$this->addOption( 'verbose', 'Verbose mode', false, false, 'v' );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$src = FileBackendGroup::singleton()->get( $this->getOption( 'src' ) );
		$dst = FileBackendGroup::singleton()->get( $this->getOption( 'dst' ) );

		$posFile = $this->getOption( 'posdir' )
			? $this->getOption( 'posdir' ) . '/' . wfWikiID()
			: false;

		$start = $this->getOption( 'start', 0 );
		if ( !$start && $posFile && is_file( $posFile ) ) {
			$start = (int)trim( file_get_contents( $posFile ) );
			++$start; // we already did this ID, start with the next one
		}

		$this->output( "Synchronizing backend '{$dst->getName()}' to '{$src->getName()}'...\n" );
		$this->output( "Starting journal position is $start.\n" );

		$lastOKPos = $this->syncBackends( $src, $dst, $start ); // do the heavy lifting
		if ( $posFile && $lastOKPos >= $start ) { // successfully advanced
			file_put_contents( $posFile, $lastOKPos, LOCK_EX );
		}

		$this->output( "Stopped synchronization at journal position $lastOKPos.\n" );
	}

	/**
	 * Sync $dst backend to $src backend based on the $src logs given after $start
	 *
	 * @param $src FileBackend
	 * @param $dst FileBackend
	 * @param $start integer Starting journal position
	 * @return integer Journal entry ID this advanced to handled (inclusive)
	 */
	protected function syncBackends( FileBackend $src, FileBackend $dst, $start ) {
		$lastOKPos = 0; // failed

		do {
			$this->output( "Doing $start to " . ( $start + $this->mBatchSize ) . "...\n" );
			$entries = $src->getJournal()->getChangeEntries( $start, $this->mBatchSize, $next );
			$start = $next; // start where we left off next time
			foreach ( $entries as $entry ) {
				if ( $entry['op'] !== 'null' ) { // null ops are just for reference
					$status = $this->syncFile( $entry['path'], $src, $dst );
					if ( $status->isOK() ) {
						$lastOKPos = $entry['id'];
					} else {
						break; // no gaps; everything up to $lastPos must be OK
					}
				}
			}
		} while ( $start );

		return $lastOKPos;
	}

	/**
	 * Sync a particular file of backend $src to the corresponding $dst backend file
	 *
	 * @param $start string Starting journal position
	 * @param $src FileBackend
	 * @param $dst FileBackend
	 * @return Status
	 */
	protected function syncFile( $path, FileBackend $src, FileBackend $dst ) {
		$status = Status::newGood();

		// Source: convert internal backend names (FileBackendMultiWrite) to the public one
		$sPath = $this->replaceNamePaths( $path, $src );
		// Destination: get corresponding path name
		$dPath = $this->replaceNamePaths( $path, $dst );

		if ( $this->getOption( 'verbose' ) ) {
			$this->output( "Synchronizing '$dPath'\n" );
		}

		// Lock the live backend paths from modification
		$sLock = $src->getScopedFileLocks( array( $sPath ), LockManager::LOCK_UW, $status );

		$sExists = $src->fileExists( array( 'src' => $sPath, 'latest' => 1 ) );
		$dExists = $dst->fileExists( array( 'src' => $dPath, 'latest' => 1 ) );
		if ( $sExists ) { // exists in source
			$sSha1 = $src->getFileSha1Base36( array( 'src' => $sPath, 'latest' => 1 ) );
			$dSha1 = $dst->getFileSha1Base36( array( 'src' => $dPath, 'latest' => 1 ) );
			if ( $sSha1 !== $dSha1 ) { // source/dest don't match
				$fsFile = $src->getLocalReference( array( 'src' => $sPath, 'latest' => 1 ) );
				if ( $fsFile ) {
					$status->merge( $dst->prepare( array( 'dir' => dirname( $dPath ) ) ) );
					$status->merge( $dst->store(
						array( 'src' => $fsFile->getPath(), 'dst' => $dPath, 'overwrite' => 1 )
					) );
					if ( !$status->isOK() ) {
						$this->error( "Unable to sync '$dPath': a backend error occured." );
						print_r( $status->getErrorsArray() );
					}
				} else {
					$this->error( "Unable to sync '$dPath': could not get local copy." );
				}
			}
		} else { // does not exist on source
			if ( $dExists ) { // exists in dest
				$status->merge( $dst->delete( array( 'src' => $dPath ) ) );
				if ( !$status->isOK() ) {
					$this->error( "Unable to sync '$dPath': a backend error occured." );
					print_r( $status->getErrorsArray() );
				}
			}
		}

		return $status;
	}

	/**
	 * Substitute the backend name of storage paths with that of a given one
	 *
	 * @param $paths Array|string List of paths or single string path
	 * @return Array|string
	 */
	protected function replaceNamePaths( $paths, FileBackend $backend ) {
		return preg_replace(
			'!^mwstore://([^/]+)!',
			StringUtils::escapeRegexReplacement( "mwstore://" . $backend->getName() ),
			$paths // string or array
		);
	}

}

$maintClass = "SyncFileBackend";
require_once( RUN_MAINTENANCE_IF_MAIN );
