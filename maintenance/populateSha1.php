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
 * @ingroup Maintenance
 */

require_once( dirname(__FILE__).'/Maintenance.php' );

class PopulateSha1 extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Populate the img_sha1 field";
		$this->addOption( 'method', "Use 'pipe' to pipe to mysql command line,\n" .
			"\t\tdefault uses Database class", false, true );
		$this->addOption( 'file', 'Fix for a specific file, without File: namespace prefixed', false, true );
	}

	public function execute() {
		$method = $this->getOption( 'method', 'normal' );
		$file = $this->getOption( 'file' );

		$t = -microtime( true );
		$dbw = wfGetDB( DB_MASTER );
		if( $file ) {
			$res = $dbw->selectRow( 
				'image', 
				array( 'img_name' ), 
				array( 'img_name' => $dbw->addQuotes( $file ) ),
				__METHOD__
			);
			if( !$res ) {
				$this->error( "No such file: $file", true );
				return;
			}
		} else {
			$res = $dbw->select( 'image', array( 'img_name' ), array( 'img_sha1' => '' ), __METHOD__ );
		}
		$imageTable = $dbw->tableName( 'image' );
		$oldimageTable = $dbw->tableName( 'oldimage' );
		$batch = array();
	
		if ( $method == 'pipe' ) {
			// @fixme kill this and replace with a second unbuffered DB connection.
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
			if ( $i % 100 == 0 ) {
				$this->output( sprintf( "Done %d of %d, %5.3f%%  \r", $i, $numRows, $i / $numRows * 100 ) );
				wfWaitForSlaves( 5 );
			}
			$file = wfLocalFile( $row->img_name );
			if ( !$file ) {
				continue;
			}
			$sha1 = File::sha1Base36( $file->getPath() );
			if ( strval( $sha1 ) !== '' ) {
				$sql = "UPDATE $imageTable SET img_sha1=" . $dbw->addQuotes( $sha1 ) .
					" WHERE img_name=" . $dbw->addQuotes( $row->img_name );
				if ( $method == 'pipe' ) {
					fwrite( $pipe, "$sql;\n" );
				} else {
					$dbw->query( $sql, __METHOD__ );
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
	}
}

$maintClass = "PopulateSha1";
require_once( DO_MAINTENANCE );
