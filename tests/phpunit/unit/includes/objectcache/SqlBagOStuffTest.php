<?php

/**
 * @covers \SqlBagOStuff
 * @group BagOStuff
 */
class SqlBagOStuffTest extends MediaWikiUnitTestCase {
	public static function provideMakeKey() {
		yield [ 'local', 'first', [ 'second', 'third' ],
			'local:first:second:third' ];
		yield [ 'local with spaces', 'first:first', [ 'second:second' ],
			'local_with_spaces:first%3Afirst:second%3Asecond' ];
		$longA = str_repeat( 'a', 128 );
		$longB = str_repeat( 'b', 128 );
		$longC = str_repeat( 'c', 128 );
		yield [ 'global fairly long', 'first', [ $longA, $longB ],
			'global_fairly_long:first:' . $longA . ':#73045f89f89b1604b62a6ae1ab4d4133' ];
		yield [ 'global really long', 'first', [ $longA, $longB, $longC ],
			'global_really_long:BagOStuff-long-key:##99f6adc828cfb6c892501f20153bd028' ];
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
