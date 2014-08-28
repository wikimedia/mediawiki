<?php
/**
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
 * @author Aaron Schulz
 */

require_once __DIR__ . '/Maintenance.php';

class FindMissingFiles extends Maintenance {
	function __construct() {
		parent::__construct();

		$this->mDescription = 'Find registered files with no corresponding file.';
		$this->addOption( 'start', 'Starting file name', false, true );
		$this->setBatchSize( 200 );
	}

	function execute() {
		$lastName = $this->getOption( 'start', '' );

		$repo = RepoGroup::singleton()->getLocalRepo();
		$dbr = $repo->getSlaveDB();
		$be = $repo->getBackend();

		do {
			$res = $dbr->select( 'image',
				'img_name',
				array( "img_name >= " . $dbr->addQuotes( $lastName ) ),
				__METHOD__,
				array( 'ORDER BY' => 'img_name', 'LIMIT' => $this->mBatchSize )
			);

			// Check if any of these files are missing...
			$pathsByName = array();
			foreach ( $res as $row ) {
				$file = $repo->newFile( $row->img_name );
				$pathsByName[$row->img_name] = $file->getPath();
				$lastName = $row->img_name;
			}
			$be->preloadFileStat( array( 'srcs' => $pathsByName ) );
			foreach ( $pathsByName as $path ) {
				if ( $be->fileExists( array( 'src' => $path ) ) === false ) {
					$this->output( "$path\n" );
				}
			}

			// Find all missing old versions of any of the files in this batch...
			if ( count( $pathsByName ) ) {
				$ores = $dbr->select( 'oldimage',
					array( 'oi_name', 'oi_archive_name' ),
					array( 'oi_name' => array_keys( $pathsByName ) ),
					__METHOD__
				);

				$checkPaths = array();
				foreach ( $ores as $row ) {
					if ( !strlen( $row->oi_archive_name ) ) {
						continue; // broken row
					}
					$file = $repo->newFromArchiveName( $row->oi_name, $row->oi_archive_name );
					$checkPaths[] = $file->getPath();
				}

				foreach ( array_chunk( $checkPaths, $this->mBatchSize ) as $paths ) {
					$be->preloadFileStat( array( 'srcs' => $paths ) );
					foreach ( $paths as $path ) {
						if ( $be->fileExists( array( 'src' => $path ) ) === false ) {
							$this->output( "$path\n" );
						}
					}
				}
			}
		} while ( $res->numRows() >= $this->mBatchSize );
	}
}

$maintClass = 'FindMissingFiles';
require_once RUN_MAINTENANCE_IF_MAIN;
