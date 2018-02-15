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
 */

require_once __DIR__ . '/Maintenance.php';

class FindOrphanedFiles extends Maintenance {
	function __construct() {
		parent::__construct();

		$this->addDescription( "Find unregistered files in the 'public' repo zone." );
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
			$this->fatalError( "Local repo uses SHA-1 file storage names; aborting." );
		}

		$directory = $repo->getZonePath( 'public' );
		if ( $subdir != '' ) {
			$directory .= "/$subdir/";
		}

		if ( $verbose ) {
			$this->output( "Scanning files under $directory:\n" );
		}

		$list = $repo->getBackend()->getFileList( [ 'dir' => $directory ] );
		if ( $list === null ) {
			$this->fatalError( "Could not get file listing." );
		}

		$pathBatch = [];
		foreach ( $list as $path ) {
			if ( preg_match( '#^(thumb|deleted)/#', $path ) ) {
				continue; // handle ugly nested containers on stock installs
			}

			$pathBatch[] = $path;
			if ( count( $pathBatch ) >= $this->getBatchSize() ) {
				$this->checkFiles( $repo, $pathBatch, $verbose );
				$pathBatch = [];
			}
		}
		$this->checkFiles( $repo, $pathBatch, $verbose );
	}

	protected function checkFiles( LocalRepo $repo, array $paths, $verbose ) {
		if ( !count( $paths ) ) {
			return;
		}

		$dbr = $repo->getReplicaDB();

		$curNames = [];
		$oldNames = [];
		$imgIN = [];
		$oiWheres = [];
		foreach ( $paths as $path ) {
			$name = basename( $path );
			if ( preg_match( '#^archive/#', $path ) ) {
				if ( $verbose ) {
					$this->output( "Checking old file $name\n" );
				}

				$oldNames[] = $name;
				list( , $base ) = explode( '!', $name, 2 ); // <TS_MW>!<img_name>
				$oiWheres[] = $dbr->makeList(
					[ 'oi_name' => $base, 'oi_archive_name' => $name ],
					LIST_AND
				);
			} else {
				if ( $verbose ) {
					$this->output( "Checking current file $name\n" );
				}

				$curNames[] = $name;
				$imgIN[] = $name;
			}
		}

		$res = $dbr->query(
			$dbr->unionQueries(
				[
					$dbr->selectSQLText(
						'image',
						[ 'name' => 'img_name', 'old' => 0 ],
						$imgIN ? [ 'img_name' => $imgIN ] : '1=0'
					),
					$dbr->selectSQLText(
						'oldimage',
						[ 'name' => 'oi_archive_name', 'old' => 1 ],
						$oiWheres ? $dbr->makeList( $oiWheres, LIST_OR ) : '1=0'
					)
				],
				true // UNION ALL (performance)
			),
			__METHOD__
		);

		$curNamesFound = [];
		$oldNamesFound = [];
		foreach ( $res as $row ) {
			if ( $row->old ) {
				$oldNamesFound[] = $row->name;
			} else {
				$curNamesFound[] = $row->name;
			}
		}

		foreach ( array_diff( $curNames, $curNamesFound ) as $name ) {
			$file = $repo->newFile( $name );
			// Print name and public URL to ease recovery
			if ( $file ) {
				$this->output( $name . "\n" . $file->getCanonicalUrl() . "\n\n" );
			} else {
				$this->error( "Cannot get URL for bad file title '$name'" );
			}
		}

		foreach ( array_diff( $oldNames, $oldNamesFound ) as $name ) {
			list( , $base ) = explode( '!', $name, 2 ); // <TS_MW>!<img_name>
			$file = $repo->newFromArchiveName( Title::makeTitle( NS_FILE, $base ), $name );
			// Print name and public URL to ease recovery
			$this->output( $name . "\n" . $file->getCanonicalUrl() . "\n\n" );
		}
	}
}

$maintClass = FindOrphanedFiles::class;
require_once RUN_MAINTENANCE_IF_MAIN;
