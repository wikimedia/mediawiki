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
		$this->addOption( "dblist", "List of DBs to create", false, true );
		$this->mDescription = "Prepare a new DB cluster for RDB storage";
	}

	public function execute() {
		$lb = wfGetLBFactory()->getExternalLB( $this->getOption( 'cluster' ) );

		$server = $lb->getServerInfo( $lb->getWriterIndex() );
		$dbw = DatabaseBase::factory( $server['type'], $server );

		if ( $dbw->getType() === 'mysql' ) {
			$dbw->query( "SET table_type=Innodb" );
			$this->output( "Creating rdb_metadata database.\n" );
			$dbw->query( "CREATE DATABASE IF NOT EXISTS rdb_metadata" );
			$dbw->selectDB( 'rdb_metadata' );
			$this->output( "Creating rdb_trx_journal table in rdb_metadata DB.\n" );
			$dbw->sourceFile( dirname( __FILE__ ) . '/rdb_trx_journal.sql' );

			$dbList = $this->getOption( 'dblist' );
			if ( $dbList && is_file( $dbList ) ) {
				$this->output( "Creating databases from '$dbList'.\n" );
				$dbNames = array_filter( explode( "\n", file_get_contents( $dbList ) ), 'strlen' );
				foreach ( $dbNames as $dbName ) {
					$dbw->query( "CREATE DATABASE IF NOT EXISTS $dbName" );
				}
			}
			$this->output( "Done.\n" );
		} else {
			throw new MWException( "Unsupported DB type; must be MySQL." );
		}
	}
}

$maintClass = "RDBAddCluster";
require_once( RUN_MAINTENANCE_IF_MAIN );
