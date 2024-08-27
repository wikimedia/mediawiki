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

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that reports the hostname of a replica DB server.
 *
 * @ingroup Maintenance
 */
class GetReplicaServer extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( "group", "Query group to check specifically" );
		$this->addOption( 'cluster', 'Use an external cluster by name', false, true );
		$this->addDescription( 'Report the hostname of a replica DB server' );
	}

	public function execute() {
		$lbf = $this->getServiceContainer()->getDBLoadBalancerFactory();
		if ( $this->hasOption( 'cluster' ) ) {
			try {
				$lb = $lbf->getExternalLB( $this->getOption( 'cluster' ) );
			} catch ( InvalidArgumentException $e ) {
				$this->fatalError( 'Error: ' . $e->getMessage() );
			}
		} else {
			$lb = $lbf->getMainLB();
		}

		$group = $this->getOption( 'group', false );
		$index = $lb->getReaderIndex( $group );
		if ( $index === false && $group ) {
			// retry without the group; it may not exist
			$index = $lb->getReaderIndex( false );
		}
		if ( $index === false ) {
			$this->fatalError( 'Error: unable to get reader index' );
		}

		$this->output( $lb->getServerName( $index ) . "\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = GetReplicaServer::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
