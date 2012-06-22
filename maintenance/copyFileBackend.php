<?php
/**
 * Copy all files in one container of one backend to another.
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

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

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
		$this->mDescription = "Copy all the files in one backend to another.";
		$this->addOption( 'src', 'Backend containing the source files', true, true );
		$this->addOption( 'dst', 'Backend where files should be copied to', true, true );
		$this->addOption( 'containers', 'Pipe separated list of containers', true, true );
		$this->addOption( 'fast', 'Skip SHA-1 checks on pre-existing files' );
	}

	public function execute() {
		$src = FileBackendGroup::singleton()->get( $this->getOption( 'src' ) );
		$dst = FileBackendGroup::singleton()->get( $this->getOption( 'dst' ) );

		$containers = explode( '|', $this->getOption( 'containers' ) );
		foreach ( $containers as $container ) {
			$this->output( "Doing container $container...\n" );

			$srcPathsRel = $src->getFileList(
				array( 'dir' => $src->getRootStoragePath() . "/$container" ) );
			if ( $srcPathsRel === null ) {
				$this->error( "Could not list files in $container.", 1 ); // die
			}
			foreach ( $srcPathsRel as $srcPathRel ) {
				$srcPath = $src->getRootStoragePath() . "/$container/$srcPathRel";
				$dstPath = $dst->getRootStoragePath() . "/$container/$srcPathRel";

				if ( $dst->fileExists( array( 'src' => $dstPath, 'latest' => 1 ) ) ) {
					if ( $this->hasOption( 'fast' ) ) {
						$this->output( "Already have $dstPath.\n" );
						continue; // assume already copied...
					}
					$srcSha1 = $src->getFileSha1Base36( array( 'src' => $srcPath ) );
					$dstSha1 = $dst->getFileSha1Base36( array( 'src' => $dstPath ) );
					if ( $srcSha1 && $srcSha1 === $dstSha1 ) {
						$this->output( "Already have $dstPath.\n" );
						continue; // already copied...
					}
				}

				$fsFile = $src->getLocalReference( array( 'src' => $srcPath, 'latest' => 1 ) );
				if ( !$fsFile ) {
					$this->error( "Could not get local copy of $srcPath.", 1 ); // die
				}

				$status = $dst->prepare( array( 'dir' => dirname( $dstPath ) ) );
				$status->merge( $dst->store(
					array( 'src' => $fsFile->getPath(), 'dst' => $dstPath ),
					array( 'nonLocking' => 1, 'nonJournaled' => 1 )
				) );
				if ( !$status->isOK() ) {
					print_r( $status->getErrorsArray() );
					$this->error( "Could not copy $srcPath to $dstPath.", 1 ); // die
				}

				$this->output( "Copied $srcPath to $dstPath.\n" );
			}
		}
	}
}

$maintClass = 'CopyFileBackend';
require_once( RUN_MAINTENANCE_IF_MAIN );
