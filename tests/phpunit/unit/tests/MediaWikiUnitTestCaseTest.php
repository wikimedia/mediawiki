<?php

use MediaWiki\Config\HashConfig;
use PHPUnit\Framework\ExpectationFailedException;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Services\NoSuchServiceException;

/**
 * Test functionality implemented in the MediaWikiUnitTestCase base class.
 *
 * @covers \MediaWikiUnitTestCase
 * @covers \MediaWikiTestCaseTrait
 */
class MediaWikiUnitTestCaseTest extends MediaWikiUnitTestCase {

	public function testServiceContainer() {
		$config = new HashConfig();
		$cache = new HashBagOStuff();

		$this->setService( 'MainConfig', $config );
		$services = $this->getServiceContainer();

		$this->setService(
			'LocalServerObjectCache',
			static function () use ( $cache ) {
				return $cache;
			}
		);

		$this->assertSame( $config, $services->get( 'MainConfig' ) );
		$this->assertSame( $config, $services->getService( 'MainConfig' ) );
		$this->assertSame( $config, $services->getMainConfig() );

		$this->assertSame( $cache, $services->get( 'LocalServerObjectCache' ) );
		$this->assertSame( $cache, $services->getService( 'LocalServerObjectCache' ) );
		$this->assertSame( $cache, $services->getLocalServerObjectCache() );

		$this->expectException( NoSuchServiceException::class );
		$services->getMessageCache();
	}

	public function testArrayContains() {
		$this->assertArrayContains( [], [], 'empty has empty' );
		$this->assertArrayContains( [], [ 'a', 'b', 'c' ], 'list has empty' );
		$this->assertArrayContains(
			[ 'b' ],
			[ 'a', 'b', 'c' ],
			'list contains a value'
		);
		$this->assertArrayContains(
			[ 'c', 'a' ],
			[ 'a', 'b', 'c' ],
			'list contains subset and ignores order'
		);
		$this->assertArrayContains(
			[ 'b', 'b' ],
			[ 'a', 'b', 'b' ],
			'list contains duplicate value'
		);
		$this->assertArrayContains(
			[ 'a', 'b', 'c', 'b' ],
			[ 'a', 'b', 'c', 'b' ],
			'list is the same'
		);

		$this->assertArrayContains( [], [ 'x' => 100, 'y' => 200 ], 'assoc has empty' );
		$this->assertArrayContains(
			[ 'y' => 200 ],
			[ 'x' => 100, 'y' => 200, 'z' => 300 ],
			'assoc contains pair'
		);
		$this->assertArrayContains(
			[ 'z' => 300, 'x' => 100 ],
			[ 'x' => 100, 'y' => 200, 'z' => 300 ],
			'assoc contains subset and ignores key order'
		);
		$this->assertArrayContains(
			[ 'x' => 100, 'y' => 200, 'z' => 300 ],
			[ 'x' => 100, 'y' => 200, 'z' => 300 ],
			'assoc is the same'
		);
		$this->assertArrayContains(
			[
				'z' => 300,
				'x' => [ 'x2' => 2 ]
			],
			[
				'x' => [ 'x1' => 1, 'x2' => 2 ],
				'y' => 200,
				'z' => 300
			],
			'assoc contains subset of nested assoc array and ignores key order'
		);
		$this->assertArrayContains(
			[
				[
					'z' => 300,
					'x' => [ 'a', 'b' ],
				],
			],
			[
				[
					'x' => [ 'a', 'b', 'c' ],
					'y' => 200,
					'z' => 300,
				],
				[
					'x' => [ 'a', 'b', 'c' ],
					'y' => 200,
					'z' => 300,
				],
			],
			'assoc partially contains nested list and ignores key order'
		);
		$this->assertArrayContains(
			[
				'foo',
				'bar'
			],
			[
				[
					'x' => [ 'a', 'b', 'c' ],
					'y' => 200,
					'z' => 300,
				],
				'bar',
				[
					'x' => [ 'a', 'b', 'c' ],
					'y' => 200,
					'z' => 300,
				],
				'quux',
				'foo',
			],
			'nested array contains expected items from a flat list'
		);
	}

	public static function provideArrayContainsFail() {
		yield 'list empty missing the only value' => [
			[ 'k' ],
			[],
		];
		yield 'list non-empty missing the only value' => [
			[ 'k' ],
			[ 'a', 'b', 'c' ],
		];
		yield 'list missing the middle value' => [
			[ 'c', 'k', 'a' ],
			[ 'a', 'b', 'c' ],
		];
		yield 'list missing the last value' => [
			[ 'a', 'b', 'c', 'k' ],
			[ 'a', 'b', 'c' ],
		];
		yield 'list missing a third duplicate' => [
			[ 'b', 'b', 'b' ],
			[ 'a', 'b', 'b' ],
		];

		yield 'assoc missing "a" key' => [
			[ 'a' => 200 ],
			[ 'x' => 100, 'y' => 200, 'z' => 300 ],
		];
		yield 'assoc mismatches "y" value' => [
			[ 'y' => 900 ],
			[ 'x' => 100, 'y' => 200, 'z' => 300 ],
		];
		$actualAssoc = [
			[
				'x' => [ 'a', 'b', 'c' ],
				'y' => 200,
				'z' => 300,
			],
			[
				'x' => [ 'd', 'e', 'f' ],
				'y' => 444,
				'z' => 555,
			],
		];
		yield 'assoc missing "a" key of nested array' => [
			[
				[
					'a' => 200,
					'y' => 200,
					'z' => 300,
				],
			],
			$actualAssoc,
		];
		yield 'assoc mismatches "y" value of nested array' => [
			[
				[
					'y' => 900,
					'z' => 300,
				],
			],
			$actualAssoc,
		];
		yield 'assoc mismatches order of nested list' => [
			[
				[
					'x' => [ 'a', 'c', 'b' ],
				],
			],
			$actualAssoc,
		];
	}

	/**
	 * @dataProvider provideArrayContainsFail
	 */
	public function testArrayContainsFail( $expected, $actual ) {
		$this->expectException( ExpectationFailedException::class );
		$this->assertArrayContains( $expected, $actual );
	}
}
