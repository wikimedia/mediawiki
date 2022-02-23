<?php

namespace MediaWiki\Tests\Unit\Settings\Config;

use MediaWiki\Settings\Config\ConfigBuilder;
use MediaWiki\Settings\Config\MergeStrategy;

trait ConfigSinkTestTrait {

	abstract protected function getConfigSink(): ConfigBuilder;

	abstract protected function assertKeyHasValue( string $key, $value );

	public function testSet() {
		$this->getConfigSink()
			->set( 'TestKey1', 'foo' )
			->set( 'TestKey2', 'bar' );
		$this->assertKeyHasValue( 'TestKey1', 'foo' );
		$this->assertKeyHasValue( 'TestKey2', 'bar' );
	}

	public function testSetDefault() {
		$this->getConfigSink()
			->setDefault( 'TestKey1', 'foo' )
			->setDefault( 'TestKey2', 'bar' );
		$this->assertKeyHasValue( 'TestKey1', 'foo' );
		$this->assertKeyHasValue( 'TestKey2', 'bar' );
	}

	public function provideSetNewValue() {
		yield 'replace 1 with 2' => [ 1, 2, null, 2 ];
		yield 'replace 1 with 0' => [ 1, 0, null, 0 ];
		yield 'replace 1 with null' => [ 1, null, null, null ];

		yield 'merge two arrays' => [
			[ 'a' ], [ 'b' ], MergeStrategy::ARRAY_MERGE, [ 'a', 'b' ]
		];
		yield 'merge two maps' => [
			[ 'a' => 1 ], [ 'a' => 2 ], MergeStrategy::ARRAY_MERGE, [ 'a' => 2 ]
		];

		yield 'empty array replaces 1' => [ 1, [], MergeStrategy::ARRAY_MERGE, [] ];
		yield '1 replaces non-empty array' => [ [ 'x' ], 1, MergeStrategy::ARRAY_MERGE, 1 ];
		yield 'null replaces non-empty array' => [ [ 'x' ], null, MergeStrategy::ARRAY_MERGE, null ];

		yield 'empty array replaces non-empty array' => [ [ 'x' ], [], MergeStrategy::REPLACE, [] ];
	}

	/**
	 * @dataProvider provideSetNewValue
	 *
	 * @param mixed $first
	 * @param mixed $second
	 * @param string $strategy
	 * @param mixed $expected
	 */
	public function testSetNewValue( $first, $second, $strategy, $expected ) {
		$this->getConfigSink()
			->set( 'TestKey', $first )
			->set(
				'TestKey',
				$second, $strategy ? MergeStrategy::newFromName( $strategy ) : null
			);
		$this->assertKeyHasValue( 'TestKey', $expected );
	}

	public function provideSetDefaultValue() {
		yield 'do not replace 1 with 2' => [ 1, 2, null, 1 ];
		yield 'do not replace 0 with 2' => [ 0, 2, null, 0 ];
		yield 'do not replace null with 2' => [ false, 2, null, null ];
		yield 'do not replace false with 2' => [ null, 2, null, false ];
		yield 'do not replace an empty array with 2' => [ [], 2, null, [] ];
		yield 'do not replace an empty array with a non-empty one' => [ [], [ 2 ], null, [] ];

		yield 'merge two arrays' => [
			[ 'a' ], [ 'b' ], MergeStrategy::ARRAY_MERGE, [ 'b', 'a' ]
		];
		yield 'merge two maps' => [
			[ 'a' => 1 ], [ 'a' => 2 ], MergeStrategy::ARRAY_MERGE, [ 'a' => 1 ]
		];

		yield 'non-empty array does not replace 1' => [ 1, [ 'x' ], MergeStrategy::ARRAY_MERGE, 1 ];
		yield '1 does not replace empty array' => [ [], 1, MergeStrategy::ARRAY_MERGE, [] ];
		yield '1 does not replace non-empty array' => [ [ 'x' ], 1, MergeStrategy::ARRAY_MERGE, [ 'x' ] ];
	}

	/**
	 * @dataProvider provideSetDefaultValue
	 *
	 * @param mixed $first
	 * @param mixed $second
	 * @param string $strategy
	 * @param mixed $expected
	 */
	public function testSetDefaultValue( $first, $second, $strategy, $expected ) {
		$this->getConfigSink()
			->set( 'TestKey', $first )
			->setDefault(
				'TestKey',
				$second,
				$strategy ? MergeStrategy::newFromName( $strategy ) : null
			);
		$this->assertKeyHasValue( 'TestKey', $expected );
	}

}
