<?php

namespace MediaWiki\Tests\User\TempUser;

use MediaWiki\User\TempUser\FilteredRadixSerialMapping;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\User\TempUser\FilteredRadixSerialMapping
 */
class FilteredRadixSerialMappingTest extends MediaWikiUnitTestCase {

	public static function provideGetSerialIdForIndex() {
		return [
			[
				[ 'radix' => 10 ],
				16,
				'16',
			],
			[
				[ 'radix' => 16 ],
				10,
				'a',
			],
			[
				[ 'radix' => 16, 'uppercase' => true ],
				10,
				'A',
			],
			[
				[ 'radix' => 10, 'badIndexes' => [ 2 ] ],
				1,
				'1'
			],
			[
				[ 'radix' => 10, 'badIndexes' => [ 2 ] ],
				1,
				'1'
			],
			[
				[ 'radix' => 10, 'badIndexes' => [ 2 ] ],
				2,
				'3'
			],
			[
				[ 'radix' => 10, 'badIndexes' => [ 2 ] ],
				3,
				'4'
			],
		];
	}

	/**
	 * @dataProvider provideGetSerialIdForIndex
	 * @param array $config
	 * @param int $id
	 * @param string $expected
	 */
	public function testGetSerialIdForIndex( $config, $id, $expected ) {
		$map = new FilteredRadixSerialMapping( $config );
		$this->assertSame( $expected, $map->getSerialIdForIndex( $id ) );
	}
}
