<?php

/**
 * @covers \SqlBagOStuff
 * @group BagOStuff
 */
class SqlBagOStuffTest extends MediaWikiUnitTestCase {
	public static function provideMakeKey() {
		yield 'simple' => [ 'local', 'first', [ 'second', 'third' ],
			'local:first:second:third' ];
		yield 'keyspace with spaces' => [ 'local with spaces', 'first:first', [ 'second:second' ],
			'local_with_spaces:first%3Afirst:second%3Asecond' ];
		$longA = str_repeat( 'a', 128 );
		$longB = str_repeat( 'b', 128 );
		$longC = str_repeat( 'c', 128 );
		yield 'long components' => [ 'long example', 'first', [ $longA, $longB ],
			'long_example:first:#c0045d80095a957b82531b50d813ff4549c9029cafe455ff4fb9c56750eeec62' ];
		yield 'long first component (keygroup)' => [ 'long example', $longA, [ $longB, $longC ],
			'long_example:BagOStuff-long-key:##8b1678c21d099b6a7e0587f4ff13fc2c0d562a2095158acb997f66903610a419' ];
	}

	/**
	 * @param string $keyspace
	 * @param string $class
	 * @param array $components
	 * @param string $expected
	 * @dataProvider SqlBagOStuffTest::provideMakeKey
	 */
	public function testMakeKey(
		string $keyspace,
		string $class,
		array $components,
		string $expected
	) {
		$cache = new SqlBagOStuff( [
			'keyspace' => $keyspace,
			'servers' => []
		] );
		$this->assertSame( $expected, $cache->makeKey( $class, ...$components ) );
	}

	public function testSisterKeys() {
		$cache = new SqlBagOStuff( [
			'keyspace' => 'test',
			'servers' => [ 'pc1' => [], 'pc2' => [], 'pc3' => [], 'pc4' => [], 'pc5' => [], 'pc6' => [] ],
			'shards' => 30
		] );
		$cacheObj = \Wikimedia\TestingAccessWrapper::newFromObject( $cache );

		$indexFirstKey = $cacheObj->getShardIndexesForKey( 'Test123' );
		$tableNameFirstKey = $cacheObj->getTableNameForKey( 'Test123' );

		$indexSecondKey = $cacheObj->getShardIndexesForKey( 'Test133' );
		$tableNameSecondKey = $cacheObj->getTableNameForKey( 'Test133' );

		$this->assertNotEquals( $indexFirstKey, $indexSecondKey );
		$this->assertNotEquals( $tableNameFirstKey, $tableNameSecondKey );

		$indexFirstKey = $cacheObj->getShardIndexesForKey( 'Test123|#|12345' );
		$tableNameFirstKey = $cacheObj->getTableNameForKey( 'Test123|#|12345' );

		$indexSecondKey = $cacheObj->getShardIndexesForKey( 'Test123|#|54321' );
		$tableNameSecondKey = $cacheObj->getTableNameForKey( 'Test123|#|54321' );

		$this->assertSame( $indexFirstKey, $indexSecondKey );
		$this->assertSame( $tableNameFirstKey, $tableNameSecondKey );

		$firstKey = $cache->makeKey( 'Test123', '|#|', '12345' );
		$indexFirstKey = $cacheObj->getShardIndexesForKey( $firstKey );
		$tableNameFirstKey = $cacheObj->getTableNameForKey( $firstKey );

		$secondKey = $cache->makeKey( 'Test123', '|#|', '54321' );
		$indexSecondKey = $cacheObj->getShardIndexesForKey( $secondKey );
		$tableNameSecondKey = $cacheObj->getTableNameForKey( $secondKey );

		$this->assertSame( $indexFirstKey, $indexSecondKey );
		$this->assertSame( $tableNameFirstKey, $tableNameSecondKey );
	}
}
