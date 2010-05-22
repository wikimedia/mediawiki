<?php
/**
 * Why yes, this *is* another special-purpose Wikimedia maintenance script!
 * Should be fixed up and generalized.
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
 * @ingroup Wikimedia
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class RenameWiki extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Rename external storage dbs and leave a new one";
		$this->addArg( 'olddb', 'Old DB name' );
		$this->addArg( 'newdb', 'New DB name' );
	}
	
	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		global $wgDefaultExternalStore;

		# Setup
		$from = $this->getArg( 0 );
		$to = $this->getArg( 1 );
		$this->output( "Renaming blob tables in ES from $from to $to...\n" );
		$this->output( "Sleeping 5 seconds...\n" );
		sleep( 5 );

		# Initialise external storage
		if ( is_array( $wgDefaultExternalStore ) ) {
			$stores = $wgDefaultExternalStore;
		} elseif ( $wgDefaultExternalStore ) {
			$stores = array( $wgDefaultExternalStore );
		} else {
			$stores = array();
		}

		if ( count( $stores ) ) {
			$this->output( "Initialising external storage $store...\n" );
			global $wgDBuser, $wgDBpassword, $wgExternalServers;
			foreach ( $stores as $storeURL ) {
				$m = array();
				if ( !preg_match( '!^DB://(.*)$!', $storeURL, $m ) ) {
					continue;
				}
	
				$cluster = $m[1];
	
				# Hack
				$wgExternalServers[$cluster][0]['user'] = $wgDBuser;
				$wgExternalServers[$cluster][0]['password'] = $wgDBpassword;
	
				$store = new ExternalStoreDB;
				$extdb =& $store->getMaster( $cluster );
				$extdb->query( "SET table_type=InnoDB" );
				$extdb->query( "CREATE DATABASE {$to}" );
				$extdb->query( "ALTER TABLE {$from}.blobs RENAME TO {$to}.blobs" );
				$extdb->selectDB( $from );
				$extdb->sourceFile( $this->getDir() . '/storage/blobs.sql' );
				$extdb->commit();
			}
		}
		$this->output( "done.\n" );
	}
}

$maintClass = "RenameWiki";
require_once( DO_MAINTENANCE );
