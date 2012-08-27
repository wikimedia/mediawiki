<?php
/**
 * Optional upgrade script to populate the img_sha1 field
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

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Maintenance script to populate the img_sha1 field.
 *
 * @ingroup Maintenance
 */
class PopulateImageSha1 extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Populate the img_sha1 field";
		$this->addOption( 'force', "Recalculate sha1 for rows that already have a value" );
		$this->addOption( 'method', "Use 'pipe' to pipe to mysql command line,\n" .
			"\t\tdefault uses Database class", false, true );
		$this->addOption( 'file', 'Fix for a specific file, without File: namespace prefixed', false, true );
	}

	protected function getUpdateKey() {
		return 'populate img_sha1';
	}

	protected function updateSkippedMessage() {
		return 'img_sha1 column of image table already populated.';
	}

	public function execute() {
		if ( $this->getOption( 'file' ) ) {
			$this->doDBUpdates(); // skip update log checks/saves
		} else {
			parent::execute();
		}
	}

	public function doDBUpdates() {
		$method = $this->getOption( 'method', 'normal' );
		$file = $this->getOption( 'file', '' );
		$force = $this->getOption( 'force' );
		$isRegen = ( $force || $file != '' ); // forced recalculation?

		$t = -microtime( true );
		$dbw = wfGetDB( DB_MASTER );
		if ( $file != '' ) {
			$res = $dbw->select(
				'image',
				array( 'img_name' ),
				array( 'img_name' => $file ),
				__METHOD__
			);
			if ( !$res ) {
				$this->error( "No such file: $file", true );
				return false;
			}
			$this->output( "Populating img_sha1 field for specified files\n" );
		} else {
			if ( $force ) {
				$conds = array();
				$this->output( "Populating and recalculating img_sha1 field\n" );
			} else {
				$conds = array( 'img_sha1' => '' );
				$this->output( "Populating img_sha1 field\n" );
			}
			$res = $dbw->select( 'image', array( 'img_name' ), $conds, __METHOD__ );
		}

		$imageTable = $dbw->tableName( 'image' );
		$oldImageTable = $dbw->tableName( 'oldimage' );

		if ( $method == 'pipe' ) {
			// Opening a pipe allows the SHA-1 operation to be done in parallel
			// with the database write operation, because the writes are queued
			// in the pipe buffer. This can improve performance by up to a
			// factor of 2.
			global $wgDBuser, $wgDBserver, $wgDBpassword, $wgDBname;
			$cmd = 'mysql -u' . wfEscapeShellArg( $wgDBuser ) .
				' -h' . wfEscapeShellArg( $wgDBserver ) .
				' -p' . wfEscapeShellArg( $wgDBpassword, $wgDBname );
			$this->output( "Using pipe method\n" );
			$pipe = popen( $cmd, 'w' );
		}

		$numRows = $res->numRows();
		$i = 0;
		foreach ( $res as $row ) {
			if ( $i % $this->mBatchSize == 0 ) {
				$this->output( sprintf(
					"Done %d of %d, %5.3f%%  \r", $i, $numRows, $i / $numRows * 100 ) );
				wfWaitForSlaves();
			}
			$file = wfLocalFile( $row->img_name );
			if ( !$file ) {
				continue;
			}
			// Upgrade the current file version...
			$sha1 = $file->getRepo()->getFileSha1( $file->getPath() );
			if ( strval( $sha1 ) !== '' ) { // file on disk and hashed properly
				if ( $isRegen && $file->getSha1() !== $sha1 ) {
					// The population was probably done already. If the old SHA1
					// does not match, then both fix the SHA1 and the metadata.
					$file->upgradeRow();
				} else {
					$sql = "UPDATE $imageTable SET img_sha1=" . $dbw->addQuotes( $sha1 ) .
						" WHERE img_name=" . $dbw->addQuotes( $file->getName() );
					if ( $method == 'pipe' ) {
						fwrite( $pipe, "$sql;\n" );
					} else {
						$dbw->query( $sql, __METHOD__ );
					}
				}
			}
			// Upgrade the old file versions...
			foreach ( $file->getHistory() as $oldFile ) {
				$sha1 = $oldFile->getRepo()->getFileSha1( $oldFile->getPath() );
				if ( strval( $sha1 ) !== '' ) { // file on disk and hashed properly
					if ( $isRegen && $oldFile->getSha1() !== $sha1 ) {
						// The population was probably done already. If the old SHA1
						// does not match, then both fix the SHA1 and the metadata.
						$oldFile->upgradeRow();
					} else {
						$sql = "UPDATE $oldImageTable SET oi_sha1=" . $dbw->addQuotes( $sha1 ) .
							" WHERE (oi_name=" . $dbw->addQuotes( $oldFile->getName() ) . " AND" .
							" oi_archive_name=" . $dbw->addQuotes( $oldFile->getArchiveName() ) . ")";
						if ( $method == 'pipe' ) {
							fwrite( $pipe, "$sql;\n" );
						} else {
							$dbw->query( $sql, __METHOD__ );
						}
					}
				}
			}
			$i++;
		}
		if ( $method == 'pipe' ) {
			fflush( $pipe );
			pclose( $pipe );
		}
		$t += microtime( true );
		$this->output( sprintf( "\nDone %d files in %.1f seconds\n", $numRows, $t ) );

		return !$file; // we only updated *some* files, don't log
	}
}

$maintClass = "PopulateImageSha1";
require_once( RUN_MAINTENANCE_IF_MAIN );
