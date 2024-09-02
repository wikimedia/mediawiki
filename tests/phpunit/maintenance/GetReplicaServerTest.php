<?php

namespace MediaWiki\Tests\Maintenance;

use GetReplicaServer;

/**
 * @covers GetReplicaServer
 * @group Database
 * @author Dreamy Jazz
 */
class GetReplicaServerTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return GetReplicaServer::class;
	}

	public function testExecuteForUnknownCluster() {
		$this->maintenance->setOption( 'cluster', 'invalid-cluster' );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( "/Error: Unknown cluster 'invalid-cluster'/" );
		$this->maintenance->execute();
	}

	public function testExecute() {
		$this->maintenance->execute();
		$expectedServerName = $this->getDb()->getServerName();
		$this->expectOutputString( "$expectedServerName\n" );
	}
}
