<?php
/**
 * Check images to see if they exist, are readable, etc.
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

use MediaWiki\FileRepo\File\FileSelectQueryBuilder;
use MediaWiki\Maintenance\Maintenance;
use Wikimedia\Rdbms\SelectQueryBuilder;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to check images to see if they exist, are readable, etc.
 *
 * @ingroup Maintenance
 */
class CheckImages extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Check images to see if they exist, are readable, etc' );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$start = '';
		$dbr = $this->getReplicaDB();

		$numImages = 0;
		$numGood = 0;

		$repo = $this->getServiceContainer()->getRepoGroup()->getLocalRepo();
		do {
			$res = FileSelectQueryBuilder::newForFile( $dbr )
				->where( $dbr->expr( 'img_name', '>', $start ) )
				->limit( $this->getBatchSize() )
				->orderBy( 'img_name', SelectQueryBuilder::SORT_ASC )
				->caller( __METHOD__ )
				->fetchResultSet();
			foreach ( $res as $row ) {
				$numImages++;
				$start = $row->img_name;
				$file = $repo->newFileFromRow( $row );
				$path = $file->getPath();
				if ( !$path ) {
					$this->output( "{$row->img_name}: not locally accessible\n" );
					continue;
				}
				$size = $repo->getFileSize( $file->getPath() );
				if ( $size === false ) {
					$this->output( "{$row->img_name}: missing\n" );
					continue;
				}

				if ( $size == 0 && $row->img_size != 0 ) {
					$this->output( "{$row->img_name}: truncated, was {$row->img_size}\n" );
					continue;
				}

				if ( $size != $row->img_size ) {
					$this->output( "{$row->img_name}: size mismatch DB={$row->img_size}, "
						. "actual={$size}\n" );
					continue;
				}

				$numGood++;
			}
		} while ( $res->numRows() );

		$this->output( "Good images: $numGood/$numImages\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = CheckImages::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
