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

class FindOrphanedFiles extends Maintenance {
	function __construct() {
		parent::__construct();

		$this->mDescription = "Find unregistered files in the 'public' repo zone.";
		$this->addOption( 'subdir',
			'Only scan files in this subdirectory (e.g. "a/a0")', false, true );
		$this->addOption( 'verbose', "Mention file paths checked" );
		$this->setBatchSize( 500 );
	}

	function execute() {
		$subdir = $this->getOption( 'subdir', '' );
		$verbose = $this->hasOption( 'verbose' );

		$repo = RepoGroup::singleton()->getLocalRepo();
		if ( $repo->hasSha1Storage() ) {
			$this->error( "Local repo uses SHA-1 file storage names; aborting.", 1 );
		}

		$directory = $repo->getZonePath( 'public' );
		if ( $subdir != '' ) {
			$directory .= "/$subdir/";
		}

		if ( $verbose ) {
			$this->output( "Scanning files under $directory:\n" );
		}

		$list = $repo->getBackend()->getFileList( array( 'dir' => $directory ) );
		if ( $list === null ) {
			$this->error( "Could not get file listing.", 1 );
		}

		$nameBatch = array();
		foreach ( $list as $path ) {
			if ( preg_match( '#^(thumb|deleted)/#', $path ) ) {
				continue; // handle ugly nested containers on stock installs
			}

			$nameBatch[] = basename( $path );
			if ( count( $nameBatch ) >= $this->mBatchSize ) {
				$this->checkFiles( $repo, $nameBatch, $verbose );
				$nameBatch = array();
			}
		}
		$this->checkFiles( $repo, $nameBatch, $verbose );
	}

	protected function checkFiles( LocalRepo $repo, array $names, $verbose ) {
		if ( !count( $names ) ) {
			return;
		}

		$dbr = $repo->getSlaveDB();

		$imgIN = array();
		$oiWheres = array();
		foreach ( $names as $name ) {
			if ( strpos( $name, '!' ) !== false ) {
				if ( $verbose ) {
					$this->output( "Checking old file $name\n" );
				}

				list( , $base ) = explode( '!', $name ); // <TS_MW>!<img_name>
				$oiWheres[] = $dbr->makeList(
					array( 'oi_name' => $base, 'oi_archive_name' => $name ),
					LIST_AND
				);
			} else {
				if ( $verbose ) {
					$this->output( "Checking current file $name\n" );
				}

				$imgIN[] = $name;
			}
		}

		$res = $dbr->query(
			$dbr->unionQueries(
				array(
					$dbr->selectSQLText(
						'image',
						array( 'name' => 'img_name' ),
						$imgIN ? array( 'img_name' => $imgIN ) : '1=0'
					),
					$dbr->selectSQLText(
						'oldimage',
						array( 'name' => 'oi_archive_name' ),
						$oiWheres ? $dbr->makeList( $oiWheres, LIST_OR ) : '1=0'
					)
				),
				true // UNION ALL (performance)
			),
			__METHOD__
		);

		$namesFound = array();
		foreach ( $res as $row ) {
			$namesFound[] = $row->name;
		}

		$namesOrphans = array_diff( $names, $namesFound );
		foreach ( $namesOrphans as $name ) {
			// Print name and public URL to ease recovery
			if ( strpos( $name, '!' ) !== false ) {
				list( , $base ) = explode( '!', $name ); // <TS_MW>!<img_name>
				$file = $repo->newFromArchiveName( Title::makeTitle( NS_FILE, $base ), $name );
			} else {
				$file = $repo->newFile( $name );
			}
			$this->output( $name . "\n" . $file->getCanonicalUrl() . "\n\n" );
		}
	}
}

$maintClass = 'FindOrphanedFiles';
require_once RUN_MAINTENANCE_IF_MAIN;
