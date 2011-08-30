<?php
/**
 * Check images to see if they exist, are readable, etc etc
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

class CheckImages extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Check images to see if they exist, are readable, etc";
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$start = '';
		$dbr = wfGetDB( DB_SLAVE );

		$numImages = 0;
		$numGood = 0;

		do {
			$res = $dbr->select( 'image', '*', array( 'img_name > ' . $dbr->addQuotes( $start ) ),
				__METHOD__, array( 'LIMIT' => $this->mBatchSize ) );
			foreach ( $res as $row ) {
				$numImages++;
				$start = $row->img_name;
				$file = RepoGroup::singleton()->getLocalRepo()->newFileFromRow( $row );
				$path = $file->getPath();
				if ( !$path ) {
					$this->output( "{$row->img_name}: not locally accessible\n" );
					continue;
				}
				wfSuppressWarnings();
				$stat = stat( $file->getPath() );
				wfRestoreWarnings();
				if ( !$stat ) {
					$this->output( "{$row->img_name}: missing\n" );
					continue;
				}

				if ( $stat['mode'] & 040000 ) {
					$this->output( "{$row->img_name}: is a directory\n" );
					continue;
				}

				if ( $stat['size'] == 0 && $row->img_size != 0 ) {
					$this->output( "{$row->img_name}: truncated, was {$row->img_size}\n" );
					continue;
				}

				if ( $stat['size'] != $row->img_size ) {
					$this->output( "{$row->img_name}: size mismatch DB={$row->img_size}, actual={$stat['size']}\n" );
					continue;
				}

				$numGood++;
			}

		} while ( $res->numRows() );

		$this->output( "Good images: $numGood/$numImages\n" );
	}
}

$maintClass = "CheckImages";
require_once( RUN_MAINTENANCE_IF_MAIN );
