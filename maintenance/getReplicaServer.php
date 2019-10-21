<?php
/**
 * Reports the hostname of a replica DB server.
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

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\MediaWikiServices;

/**
 * Maintenance script that reports the hostname of a replica DB server.
 *
 * @ingroup Maintenance
 */
class GetReplicaServer extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( "group", "Query group to check specifically" );
		$this->addDescription( 'Report the hostname of a replica DB server' );
	}

	public function execute() {
		if ( $this->hasOption( 'group' ) ) {
			$db = $this->getDB( DB_REPLICA, $this->getOption( 'group' ) );
			$host = $db->getServer();
		} else {
			$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
			$i = $lb->getReaderIndex();
			$host = $lb->getServerName( $i );
		}
		$this->output( "$host\n" );
	}
}

$maintClass = GetReplicaServer::class;
require_once RUN_MAINTENANCE_IF_MAIN;
