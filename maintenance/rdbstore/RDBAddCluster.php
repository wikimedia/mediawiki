<?php
/**
 * Tool to help with addition of new clusters to an RDB store.
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
 * @author Aaron Schulz
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/../Maintenance.php' );

/**
 * Maintenance script to help with new ExternalRDBStore clusters
 *
 * @ingroup Maintenance
 */
class RDBAddCluster extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( "cluster", "Name of the DB cluster", true, true );
		$this->addOption( "dblist", "List of DBs to create", true, true );
		$this->mDescription = "Prepare a new DB cluster for RDB storage";
	}

	function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		$lb = wfGetLBFactory()->getExternalLB( $this->getOption( 'cluster' ) );

		$server = $lb->getServerInfo( $lb->getWriterIndex() );
		$dbw = DatabaseBase::factory( $server['type'], $server );
		$type = $dbw->getType(); // DB server type (mysql, postgres)

		$dbList = $this->getOption( 'dblist' );
		if ( $dbList && is_file( $dbList ) ) {
			$this->output( "Creating databases from '$dbList'.\n" );
			$dbNames = array_filter( explode( "\n", file_get_contents( $dbList ) ), 'strlen' );
			foreach ( $dbNames as $dbName ) {
				$encDbName = $dbw->addIdentifierQuotes( $dbName );
				$dbw->query( "CREATE DATABASE IF NOT EXISTS $encDbName" );
				$this->output( "Created $type database '$dbName'.\n" );
			}
		}
		$this->output( "Done.\n" );
	}
}

$maintClass = "RDBAddCluster";
require_once( RUN_MAINTENANCE_IF_MAIN );
