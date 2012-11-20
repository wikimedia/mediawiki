<?php

/**
 * This tool is a part of MediaWiki4Intranet Import-Export patch.
 * http://wiki.4intra.net/MW_Import&Export
 * Copyright (c) 2010+, Vitaliy Filippov
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
 */

/**
 * Maintenance tool which updates archived image revision filenames
 * to match the revision date, not the NEXT revision date as in the
 * original MediaWiki.
 *
 * DO NOT run this if you don't plan to use MW4Intranet Import-Export patch.
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class OldImageRenamer extends Maintenance {

	var $quiet;
	var $remove_unexisting;
	var $bak;
	var $mDescription = 'By default, old files in MediaWiki are stored containing timestamp of **next**
revision in file names. This script renames them to contain **their own** timestamp
in file names (plus a \'T\' letter in the beginning to avoid collisions with old scheme).
This is needed to run MW4Intranet Import&Export with conflict detection patch.
';

	function __construct() {
		$this->addOption( 'quiet', 'Silent mode', false, false );
		$this->addOption( 'delunexisting', 'Remove archive images non-existing on FS anymore', false, false );
		$this->addOption( 'backup', 'Make backups', false, false );
	}

	function out($s) {
		if ( !$this->quiet ) {
			print $s;
		}
	}

	function execute() {
		$this->quiet = $this->getOption( 'quiet', false );
		$this->remove_unexisting = $this->getOption( 'delunexisting', false );
		$this->bak = $this->getOption( 'backup', false );
		$dbr = wfGetDB( DB_MASTER );
		$res = $dbr->select( 'oldimage', '*', '1', __METHOD__, array( 'FOR UPDATE', 'ORDER BY' => 'oi_name, oi_timestamp' ) );
		$file = NULL;
		$lastfilename = NULL;
		while ( $oi = $dbr->fetchRow( $res ) ) {
			$row = array();
			foreach ( $oi as $k => $v ) {
				if ( !is_numeric( $k ) ) {
					$row[$k] = $v;
				}
			}
			$ts = wfTimestamp( TS_MW, $oi[ 'oi_timestamp' ] );
			$fn = $oi[ 'oi_archive_name' ];
			if ( ( $p = strpos( $fn, '!' ) ) !== false ) {
				if ( !$file || $lastfilename != $oi['oi_name'] ) {
					$lastfilename = $oi['oi_name'];
					$file = wfLocalFile( $oi['oi_name'] );
					$path = $file->repo->getZonePath( 'public' ) . '/archive/' . $file->getHashPath();
				}
				$nfn = 'T'.$ts.'!'.$file->getName();
				if ( $fn != $nfn ) {
					if ( $this->remove_unexisting && !file_exists( $path . $fn ) ) {
						$dbr->delete( 'oldimage', $row, __METHOD__ );
						print "Removed $fn from oldimage table\n";
						continue;
					}
					if ( file_exists( $path . $nfn ) ) {
						if ( $this->bak ) {
							$i = 0;
							while ( file_exists( $path.$nfn.'.'.$i ) ) {
								$i++;
							}
							rename( $path.$nfn, $path.$nfn.'.'.$i );
							print "WARNING: moved $path$nfn into $path$nfn.$i\n";
						} else {
							print "Error moving $path$fn to $path$nfn: $path$nfn already exists\n";
							break;
						}
					}
					if ( rename( $path . $fn, $path . $nfn ) ) {
						if ( $dbr->update( 'oldimage', array( 'oi_archive_name' => $nfn ), $row, __METHOD__ ) ) {
							$this->out( "Moved $path$fn to $path$nfn\n" );
						} else {
							rename( $path . $nfn, $path . $fn );
							print "Error moving $path$fn to $path$nfn: can't update $fn to $nfn in the database\n";
							break;
						}
					} else {
						print "Error moving file :-(\n";
						break;
					}
				}
			}
		}
		$dbr->commit();
	}
}

$maintClass = "OldImageRenamer";
require_once( RUN_MAINTENANCE_IF_MAIN );
