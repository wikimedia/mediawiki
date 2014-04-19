<?php
/**
 * Copy all files in FileRepo to an originals container using SHA1 paths.
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

require_once __DIR__ . '/Maintenance.php';

/**
 * Copy all files in FileRepo to an originals container using SHA1 paths.
 *
 * @ingroup Maintenance
 */
class MigrateFileRepo extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Copy files in repo to a different layout.";
		$this->addOption( 'layout', "New layout; one of 'name' or 'sha1'", true, true );
		$this->setBatchSize( 50 );
	}

	public function execute() {
		$layout = $this->getOption( 'layout' );
		if ( !in_array( $layout, array( 'name', 'sha1' ) ) ) {
			$this->error( "Invalid layout.", 1 );
		}

		$repo = RepoGroup::singleton()->getLocalRepo();
		$be = $repo->getBackend();
		$dbw = $repo->getMasterDB();
		$origBase = $be->getContainerStoragePath( "{$repo->getName()}-original" );

		$res = $dbw->select( 'image', array( 'img_name', 'img_sha1' ), array(), __METHOD__ );

		$batch = array();
		foreach ( $res as $row ) {
			$file = wfLocalFile( $row->img_name );
			$sha1 = $row->img_sha1;
			$ext = FileBackend::extensionFromPath( $row->img_name );
			if ( !strlen( $sha1 ) ) {
				continue;
			}

			$spath = $file->getPath(); // source paths may be silently translated
			if ( $layout === 'sha1' ) {
				$dpath = "{$origBase}/{$sha1[0]}/{$sha1[1]}/{$sha1[2]}/{$sha1}.{$ext}";
			} else {
				$dpath = $file->getPath(); // destination paths are used literally
			}

			$status = $be->prepare( array( 'dir' => dirname( $dpath ) ) );
			if ( !$status->isOK() ) {
				$this->output( print_r( $status->getErrorsArray(), true ) );
			}

			$batch[] = array( 'op' => 'copy', 'src' => $spath, 'dst' => $dpath,
				'overwriteSame' => true, 'img' => $row->img_name );
			if ( count( $batch ) >= $this->mBatchSize ) {
				$this->runBatch( $batch, $be );
				$batch = array();
			}
		}
		if ( count( $batch ) ) {
			$this->runBatch( $batch, $be );
		}

		$this->output( "Done\n" );
	}

	protected function runBatch( array $ops, FileBackend $be ) {
		$this->output( "Migrating file batch:\n" );
		foreach ( $ops as $op ) {
			$this->output( "\"{$op['img']}\" ({$op['dst']})\n" );
		}

		$status = $be->doOperations( $ops );
		if ( !$status->isOK() ) {
			$this->output( print_r( $status->getErrorsArray(), true ) );
		}

		$this->output( "Batch done\n\n" );
	}
}

$maintClass = 'MigrateFileRepo';
require_once RUN_MAINTENANCE_IF_MAIN;
