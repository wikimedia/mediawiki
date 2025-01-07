<?php

/**
 * @group BagOStuff
 * @group Database
 * @covers \SqlBagOStuff
 */
class SqlBagOStuffIntegrationTest extends BagOStuffTestBase {
	protected function newCacheInstance() {
		return $this->getServiceContainer()->getObjectCacheFactory()->getInstance( CACHE_DB );
	}

	public function testFallback() {
		if ( $this->getDb()->getType() !== 'mysql' ) {
			$this->markTestSkipped( "Does not work with sqlite and postgres" );
		}
		global $wgDBserver, $wgDBport, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype;
		$cache = new SqlBagOStuff( [
			'keyspace' => 'test',
			'servers' => [ 'pc1' => [
				'serverName' => 'db0',
				'host' => $wgDBserver,
				'port' => $wgDBport,
				'dbname' => $wgDBname,
				'user' => $wgDBuser,
				'password' => $wgDBpassword,
				'type' => $wgDBtype,
			], 'pc2' => [
				'serverName' => 'db1',
				'host' => $wgDBserver . 'nocoonection',
				'port' => $wgDBport,
				'dbname' => $wgDBname . 'nocoonection',
				'user' => $wgDBuser . 'nocoonection',
				'password' => $wgDBpassword . 'nocoonection',
				'type' => $wgDBtype,
			] ],
			'shards' => 1
		] );

		// around half of these must fallback. Test shows keyname3 does.
		for ( $i = 0; $i < 10; $i++ ) {
			$cache->set( 'keyname' . (string)$i, 'value' . (string)$i );
			$res = $cache->get( 'keyname' . (string)$i );

			$this->assertSame( 'value' . (string)$i, $res );
		}

		// Testing getMulti
		$keys = [];
		for ( $i = 0; $i < 10; $i++ ) {
			$cache->set( 'keyname' . (string)$i, 'value' . (string)$i );
			$keys[] = 'keyname' . (string)$i;
		}
		$res = $cache->getMulti( $keys );
		for ( $i = 0; $i < 10; $i++ ) {
			$this->assertSame( 'value' . (string)$i, $res['keyname' . (string)$i] );
		}
	}
}
