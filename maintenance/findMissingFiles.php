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

use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

class FindMissingFiles extends Maintenance {
	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Find registered files with no corresponding file.' );
		$this->addOption( 'start', 'Start after this file name', false, true );
		$this->addOption( 'mtimeafter', 'Only include files changed since this time', false, true );
		$this->addOption( 'mtimebefore', 'Only includes files changed before this time', false, true );
		$this->setBatchSize( 300 );
	}

	public function execute() {
		$lastName = $this->getOption( 'start', '' );

		$repo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();
		$dbr = $repo->getReplicaDB();
		$be = $repo->getBackend();
		$batchSize = $this->getBatchSize();

		$mtime1 = $dbr->timestampOrNull( $this->getOption( 'mtimeafter', null ) );
		$mtime2 = $dbr->timestampOrNull( $this->getOption( 'mtimebefore', null ) );

		$joinTables = [];
		$joinConds = [];
		if ( $mtime1 || $mtime2 ) {
			$joinTables[] = 'page';
			$joinConds['page'] = [ 'JOIN',
				[ 'page_title = img_name', 'page_namespace' => NS_FILE ] ];
			$joinTables[] = 'logging';
			$on = [ 'log_page = page_id', 'log_type' => [ 'upload', 'move', 'delete' ] ];
			if ( $mtime1 ) {
				$on[] = "log_timestamp > {$dbr->addQuotes($mtime1)}";
			}
			if ( $mtime2 ) {
				$on[] = "log_timestamp < {$dbr->addQuotes($mtime2)}";
			}
			$joinConds['logging'] = [ 'JOIN', $on ];
		}

		do {
			$res = $dbr->select(
				array_merge( [ 'image' ], $joinTables ),
				[ 'name' => 'img_name' ],
				[ "img_name > " . $dbr->addQuotes( $lastName ) ],
				__METHOD__,
				// DISTINCT causes a pointless filesort
				[ 'ORDER BY' => 'name', 'GROUP BY' => 'name',
					'LIMIT' => $batchSize ],
				$joinConds
			);

			// Check if any of these files are missing...
			$pathsByName = [];
			foreach ( $res as $row ) {
				$file = $repo->newFile( $row->name );
				$pathsByName[$row->name] = $file->getPath();
				$lastName = $row->name;
			}
			$be->preloadFileStat( [ 'srcs' => $pathsByName ] );
			foreach ( $pathsByName as $path ) {
				if ( $be->fileExists( [ 'src' => $path ] ) === false ) {
					$this->output( "$path\n" );
				}
			}

			// Find all missing old versions of any of the files in this batch...
			if ( count( $pathsByName ) ) {
				$ores = $dbr->select( 'oldimage',
					[ 'oi_name', 'oi_archive_name' ],
					[ 'oi_name' => array_map( 'strval', array_keys( $pathsByName ) ) ],
					__METHOD__
				);

				$checkPaths = [];
				foreach ( $ores as $row ) {
					if ( !strlen( $row->oi_archive_name ) ) {
						// broken row
						continue;
					}
					$file = $repo->newFromArchiveName( $row->oi_name, $row->oi_archive_name );
					$checkPaths[] = $file->getPath();
				}

				foreach ( array_chunk( $checkPaths, $batchSize ) as $paths ) {
					$be->preloadFileStat( [ 'srcs' => $paths ] );
					foreach ( $paths as $path ) {
						if ( $be->fileExists( [ 'src' => $path ] ) === false ) {
							$this->output( "$path\n" );
						}
					}
				}
			}
		} while ( $res->numRows() >= $batchSize );
	}
}

$maintClass = FindMissingFiles::class;
require_once RUN_MAINTENANCE_IF_MAIN;
