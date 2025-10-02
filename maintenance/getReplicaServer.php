<?php
/**
 * Reports the hostname of a replica DB server.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;

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
