<?php

/**
 * @group Database
 */
class SqlBagOStuffTest extends MediaWikiIntegrationTestCase {
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
	 * @covers SqlBagOStuff::makeKeyInternal
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
}
