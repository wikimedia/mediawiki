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
		$this->mDescription = "Copy files in one backend to another.";
		$this->setBatchSize( 50 );
	}

	public function execute() {
		$repo = RepoGroup::singleton()->getLocalRepo();
		$be = $repo->getBackend();
		$dbw = $repo->getMasterDB();
		$origBase = $be->getContainerStoragePath( "{$repo->getName()}-original" );

		$res = $dbw->select( 'image', array( 'img_name', 'img_sha1' ), array(), __METHOD__ );

		foreach ( $res as $row ) {
			$file = wfLocalFile( $row->img_name );
			$sha1 = $row->img_sha1;
			$ext = FileBackend::extensionFromPath( $row->img_name );
			if ( !strlen( $sha1 ) ) {
				continue;
			}
			$dpath = "{$origBase}/{$sha1[0]}/{$sha1[1]}/{$sha1[2]}/{$sha1}.{$ext}";

			$status = $be->prepare( array( 'dir' => dirname( $dpath ) ) );
			$status->merge( $be->copy( array(
				'src' => $file->getPath(), 'dst' => $dpath, 'overwriteSame' => true
			) ) );
			if ( !$status->isOK() ) {
				$this->output( print_r( $status->getErrorsArray(), true ) );
			} else {
				$this->output( "Migrated '{$row->img_name}' ($dpath)\n" );
			}
		}

		$this->output( "Done\n" );
	}
}

$maintClass = 'MigrateFileRepo';
require_once RUN_MAINTENANCE_IF_MAIN;
