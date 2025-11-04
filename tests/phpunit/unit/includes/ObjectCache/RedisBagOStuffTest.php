<?php

use Wikimedia\ObjectCache\RedisBagOStuff;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Wikimedia\ObjectCache\RedisBagOStuff
 * @group BagOStuff
 */
class RedisBagOStuffTest extends MediaWikiUnitTestCase {

	/** @var RedisBagOStuff */
	private $cache;

	protected function setUp(): void {
		parent::setUp();

		$cache = $this->createMock( RedisBagOStuff::class );
		$this->cache = TestingAccessWrapper::newFromObject( $cache );
	}

	/**
	 * @dataProvider unserializeProvider
	 */
	public function testUnserialize( $expected, $input, $message ) {
		$actual = $this->cache->unserialize( $input );
		$this->assertSame( $expected, $actual, $message );
	}

	public static function unserializeProvider() {
		return [
			[
				-1,
				'-1',
				'String representation of \'-1\'',
			],
			[
				0,
				'0',
				'String representation of \'0\'',
			],
			[
				1,
				'1',
				'String representation of \'1\'',
			],
			[
				-1.0,
				'd:-1;',
				'Serialized negative double',
			],
			[
				'foo',
				's:3:"foo";',
				'Serialized string',
			]
		];
	}

	/**
	 * @dataProvider serializeProvider
	 */
	public function testSerialize( $expected, $input, $message ) {
		$actual = $this->cache->serialize( $input );
		$this->assertSame( $expected, $actual, $message );
	}

	public static function serializeProvider() {
		return [
			[
				-1,
				-1,
				'-1 as integer',
			],
			[
				0,
				0,
				'0 as integer',
			],
			[
				1,
				1,
				'1 as integer',
			],
			[
				'd:-1;',
				-1.0,
				'Negative double',
			],
			[
				's:3:"2.1";',
				'2.1',
				'Decimal string',
			],
			[
				's:1:"1";',
				'1',
				'String representation of 1',
			],
			[
				's:3:"foo";',
				'foo',
				'String',
			],
		];
	}
}
