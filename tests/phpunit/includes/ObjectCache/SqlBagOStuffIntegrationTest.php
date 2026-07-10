<?php

use MediaWiki\ObjectCache\SqlBagOStuff;
use Wikimedia\ObjectCache\BagOStuff;

/**
 * @group BagOStuff
 * @group Database
 * @covers \MediaWiki\ObjectCache\SqlBagOStuff
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

	/**
	 * Build an isolated single-server SqlBagOStuff backed by a fresh SQLite file.
	 *
	 * A dedicated temp file (rather than the shared CACHE_DB test database) gives
	 * exact, uncontaminated key-group counts and lets us assert on an empty stash.
	 * SqlBagOStuff auto-creates the objectcache table for SQLite on first connect.
	 */
	private function newSqliteStatsCache(): SqlBagOStuff {
		return new SqlBagOStuff( [
			'keyspace' => 'test',
			'servers' => [ 'ms1' => [
				'serverName' => 'db0',
				'dbname' => 'unittest_ms1',
				'type' => 'sqlite',
				'dbFilePath' => $this->getNewTempFile(),
			] ],
			'shards' => 1,
		] );
	}

	/**
	 * @covers \MediaWiki\ObjectCache\SqlBagOStuff::getKeyGroupStats
	 */
	public function testGetKeyGroupStatsCountsAndBytesByGroup() {
		$cache = $this->newSqliteStatsCache();

		$cache->set( $cache->makeKey( 'groupa', 'k1' ), 'v', 60 );
		$cache->set( $cache->makeKey( 'groupa', 'k2' ), 'v', 60 );
		$cache->set( $cache->makeKey( 'groupb', 'k1' ), 'v', 60 );
		// A single much larger value in its own group, for the byte comparison below.
		$cache->set(
			$cache->makeKey( 'grouplarge', 'k1' ),
			str_repeat( wfRandomString( 32 ), 200 ),
			60
		);

		$stats = $cache->getKeyGroupStats();

		$this->assertArrayHasKey( 'ms1', $stats, 'Result is keyed by server tag' );
		$server = $stats['ms1'];

		$this->assertSame( 2, $server['groupa']['keys'], 'groupa has two keys' );
		$this->assertSame( 1, $server['groupb']['keys'], 'groupb has one key' );
		$this->assertGreaterThan( 0, $server['groupa']['bytes'], 'groupa reports on-disk bytes' );

		// The exact on-disk byte length depends on PHP serialization and optional zlib
		// compression, so we assert relative sizes rather than a brittle literal: a group
		// holding one large value must weigh more than a group holding one tiny value.
		$this->assertGreaterThan(
			$server['groupb']['bytes'],
			$server['grouplarge']['bytes'],
			'A larger stored value yields more bytes'
		);
	}

	/**
	 * @covers \MediaWiki\ObjectCache\SqlBagOStuff::getKeyGroupStats
	 */
	public function testGetKeyGroupStatsExcludesExpiredButCountsIndefinite() {
		$cache = $this->newSqliteStatsCache();

		$mockTime = (float)self::TEST_TIME;
		$cache->setMockTime( $mockTime );

		$cache->set( $cache->makeKey( 'groupexpired', 'k1' ), 'v', 5 );
		$cache->set( $cache->makeKey( 'groupforever', 'k1' ), 'v', BagOStuff::TTL_INDEFINITE );

		// Advance the census clock past the short TTL so its row is logically expired.
		$mockTime += 100;

		$stats = $cache->getKeyGroupStats();
		$server = $stats['ms1'];

		$this->assertArrayNotHasKey( 'groupexpired', $server, 'Expired rows are excluded' );
		$this->assertArrayHasKey( 'groupforever', $server, 'Indefinite rows are counted' );
		$this->assertSame( 1, $server['groupforever']['keys'] );
	}

	/**
	 * @covers \MediaWiki\ObjectCache\SqlBagOStuff::getKeyGroupStats
	 */
	public function testGetKeyGroupStatsThrowsForUnknownTag() {
		$cache = $this->newSqliteStatsCache();

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Unknown server tag: ms3' );
		$cache->getKeyGroupStats( 'ms3' );
	}

	/**
	 * @covers \MediaWiki\ObjectCache\SqlBagOStuff::getKeyGroupStats
	 */
	public function testGetKeyGroupStatsThrowsWhenNoServerTagsConfigured() {
		// In load-balancer mode there are no server tags. The tag check short-circuits
		// before any connection, so the callback here is never invoked.
		$cache = new SqlBagOStuff( [
			'keyspace' => 'test',
			'loadBalancerCallback' => static fn () => null,
			'dbDomain' => false,
		] );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Given a tag but no tags are configured' );
		$cache->getKeyGroupStats( 'anything' );
	}

	/**
	 * @covers \MediaWiki\ObjectCache\SqlBagOStuff::getKeyGroupStats
	 */
	public function testGetKeyGroupStatsRestrictsToRequestedTag() {
		$cache = new SqlBagOStuff( [
			'keyspace' => 'test',
			'servers' => [ 'ms1' => [
				'serverName' => 'db0',
				'dbname' => 'unittest_ms1',
				'type' => 'sqlite',
				'dbFilePath' => $this->getNewTempFile(),
			], 'ms2' => [
				'serverName' => 'db1',
				'dbname' => 'unittest_ms2',
				'type' => 'sqlite',
				'dbFilePath' => $this->getNewTempFile(),
			] ],
			'shards' => 1,
			// Mirror every write to both servers so ms1 deterministically holds all keys,
			// letting us assert on its counts rather than relying on the striping hash.
			'dataRedundancy' => 2,
		] );

		for ( $i = 0; $i < 10; $i++ ) {
			$cache->set( $cache->makeKey( 'groupa', 'k' . $i ), 'v', 60 );
		}

		$stats = $cache->getKeyGroupStats( 'ms1' );

		$this->assertArrayNotHasKey( 'ms2', $stats, 'Other server tags are excluded' );
		$this->assertSame( 10, $stats['ms1']['groupa']['keys'], 'Requested tag reports its own rows' );
	}

	/**
	 * @covers \MediaWiki\ObjectCache\SqlBagOStuff::getKeyGroupStats
	 */
	public function testGetKeyGroupStatsPaginatesAcrossBatches() {
		$cache = $this->newSqliteStatsCache();

		for ( $i = 0; $i < 5; $i++ ) {
			$cache->set( $cache->makeKey( 'groupbatch', 'k' . $i ), 'v', 60 );
		}

		$batches = 0;
		// batchSize below the row count forces the keyset-pagination loop to iterate.
		$stats = $cache->getKeyGroupStats( null, 2, static function () use ( &$batches ) {
			$batches++;
		} );

		$this->assertSame( 5, $stats['ms1']['groupbatch']['keys'], 'All rows counted across batches' );
		$this->assertGreaterThan( 1, $batches, 'Progress callback fired once per batch' );
	}

	/**
	 * @covers \MediaWiki\ObjectCache\SqlBagOStuff::getKeyGroupStats
	 */
	public function testGetKeyGroupStatsOnEmptyStash() {
		$cache = $this->newSqliteStatsCache();

		$stats = $cache->getKeyGroupStats();

		$this->assertSame( [ 'ms1' => [] ], $stats, 'Empty table yields a per-server-empty result' );
	}

	// @todo Cover graceful degradation when a shard raises a DBError mid-scan. The
	// existing harness has no clean way to inject a bad connection into a live scan
	// (testFallback only exercises connect-time failures), so it is left uncovered
	// rather than building bespoke mocking scaffolding.
}
