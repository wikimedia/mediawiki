<?php
/**
 * @group BagOStuff
 */
class RedisBagOStuffTest extends PHPUnit_Framework_TestCase {
	/** @var RedisBagOStuff */
	private $cache;

	protected function setUp() {
		parent::setUp();
		$cache = $this->getMockBuilder( 'RedisBagOStuff' )
			->disableOriginalConstructor()
			->getMock();
		$this->cache = TestingAccessWrapper::newFromObject( $cache );
	}

	/**
	 * @covers RedisBagOStuff::unserialize
	 * @dataProvider unserializeProvider
	 */
	public function testUnserialize( $expected, $input, $message ) {
		$actual = $this->cache->unserialize( $input );
		$this->assertSame( $expected, $actual, $message );
	}

	public function unserializeProvider() {
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
	 * @covers RedisBagOStuff::serialize
	 * @dataProvider serializeProvider
	 */
	public function testSerialize( $expected, $input, $message ) {
		$actual = $this->cache->serialize( $input );
		$this->assertSame( $expected, $actual, $message );
	}

	public function serializeProvider() {
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
