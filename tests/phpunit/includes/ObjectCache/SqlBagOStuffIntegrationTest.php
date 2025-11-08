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

	public function testDataRedundancy() {
		$dbFileName1 = $this->getNewTempFile();
		$dbFileName2 = $this->getNewTempFile();

		$cache = new SqlBagOStuff( [
			'keyspace' => 'test',
			'servers' => [ 'ms1' => [
				'serverName' => 'db0',
				'dbname' => 'unittest_ms1',
				'type' => 'sqlite',
				'dbFilePath' => $dbFileName1,
			], 'ms2' => [
				'serverName' => 'db1',
				'dbname' => 'unittest_ms2',
				'type' => 'sqlite',
				'dbFilePath' => $dbFileName2,
			] ],
			'shards' => 1,
			'dataRedundancy' => 2,
		] );

		for ( $i = 0; $i < 10; $i++ ) {
			$cache->set( 'keyname' . (string)$i, 'value' . (string)$i, 60 );
			$res = $cache->get( 'keyname' . (string)$i );

			$this->assertSame( 'value' . (string)$i, $res );
		}

		// Testing getMulti
		$keys = [];
		for ( $i = 0; $i < 10; $i++ ) {
			$cache->set( 'keyname' . (string)$i, 'value' . (string)$i, 60 );
			$keys[] = 'keyname' . (string)$i;
		}
		$res = $cache->getMulti( $keys );
		for ( $i = 0; $i < 10; $i++ ) {
			$this->assertSame( 'value' . (string)$i, $res['keyname' . (string)$i] );
		}

		// Now let's depool ms2
		$cacheDepooled = new SqlBagOStuff( [
			'keyspace' => 'test',
			'servers' => [ 'ms1' => [
				'serverName' => 'db0',
				'dbname' => 'unittest_ms1',
				'type' => 'sqlite',
				'dbFilePath' => $dbFileName1,
			] ],
			'shards' => 1
		] );

		$keys = [];
		for ( $i = 0; $i < 10; $i++ ) {
			// Picking slightly higher TTL so the exptime ends up higher. In reality it doesn't matter
			// but unittest runs all of this under 1 second so the exptime ends up being the same.
			$cacheDepooled->set( 'keyname' . (string)$i, 'valueNewer' . (string)$i, 61 );
			$keys[] = 'keyname' . (string)$i;
		}
		$res = $cache->getMulti( $keys );
		for ( $i = 0; $i < 10; $i++ ) {
			// Must give the newer value only!
			$this->assertSame( 'valueNewer' . (string)$i, $res['keyname' . (string)$i] );
		}

		// Also checking when the value is only set in one cache
		$cacheDepooled->set( 'keyname2025', 'value2025', 60 );
		$this->assertSame( 'value2025', $cache->get( 'keyname2025' ) );
	}
}
