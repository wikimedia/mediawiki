<?php

// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic -- Test traits are not excluded

namespace MediaWiki\Tests\Unit\Settings\Config;

use MediaWiki\Settings\Config\ConfigBuilder;
use MediaWiki\Settings\Config\MergeStrategy;

trait ConfigSinkTestTrait {

	abstract protected function getConfigSink(): ConfigBuilder;

	abstract protected function assertKeyHasValue( string $key, mixed $value ): void;

	public function testSet() {
		$this->getConfigSink()
			->set( 'TestKey1', 'foo' )
			->set( 'TestKey2', 'bar' );
		$this->assertKeyHasValue( 'TestKey1', 'foo' );
		$this->assertKeyHasValue( 'TestKey2', 'bar' );
	}

	public function testSetMulti() {
		$this->getConfigSink()
			->setMulti(
				[
					'TestSetMulti_string' => 'a',
					'TestSetMulti_array1' => [ 1, 2 ],
					'TestSetMulti_array2' => [ 'a', 'b' ]
				],
				[
					'TestSetMulti_array1' => MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE ),
					'TestSetMulti_array2' => MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE ),
				]
			)
			->setMulti(
				[
					'TestSetMulti_string' => 'b',
					'TestSetMulti_array1' => [ 3, 4 ],
					'TestSetMulti_array2' => [ 'c', 'd' ]
				],
				[
					'TestSetMulti_array1' => MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE ),
					'TestSetMulti_array2' => MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE ),
				]
			)
			->setMulti( [ 'TestSetMulti_array1' => [ 'x' ] ] );

		$this->assertKeyHasValue( 'TestSetMulti_string', 'b' );
		$this->assertKeyHasValue( 'TestSetMulti_array1', [ 'x' ] );
		$this->assertKeyHasValue( 'TestSetMulti_array2', [ 'a', 'b', 'c', 'd' ] );
	}

	public function testSetDefault() {
		$this->getConfigSink()
			->setDefault( 'TestDefaultKey1', 'foo' )
			->setDefault( 'TestDefaultKey2', 'bar' );
		$this->assertKeyHasValue( 'TestDefaultKey1', 'foo' );
		$this->assertKeyHasValue( 'TestDefaultKey2', 'bar' );
	}

	public static function provideSetNewValue() {
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
			->set( 'TestNewValueKey', $first )
			->set(
				'TestNewValueKey',
				$second, $strategy ? MergeStrategy::newFromName( $strategy ) : null
			);
		$this->assertKeyHasValue( 'TestNewValueKey', $expected );
	}

	/**
	 * @note Since implementations of setMulti may inline logic of setDefault() for performance,
	 * we need to test all edge cases for setMultiDefault() as well.
	 *
	 * @dataProvider provideSetNewValue
	 *
	 * @param mixed $first
	 * @param mixed $second
	 * @param string $strategy
	 * @param mixed $expected
	 */
	public function testSetNewValue_multi( $first, $second, $strategy, $expected ) {
		$this->getConfigSink()
			->set( 'TestNewValueKeyMulti', $first )
			->setMulti(
				[ 'TestNewValueKeyMulti' => $second ],
				[ 'TestNewValueKeyMulti' => $strategy ? MergeStrategy::newFromName( $strategy ) : null ]
			);
		$this->assertKeyHasValue( 'TestNewValueKeyMulti', $expected );
	}

	public static function provideSetDefaultValue() {
		yield 'do not replace 1 with 2' => [ 1, 2, null, 1 ];
		yield 'do not replace 0 with 2' => [ 0, 2, null, 0 ];
		yield 'do not replace null with 2' => [ null, 2, null, null ];
		yield 'do not replace false with 2' => [ false, 2, null, false ];
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
			->set( 'TestDefaultValueKey', $first )
			->setDefault(
				'TestDefaultValueKey',
				$second,
				$strategy ? MergeStrategy::newFromName( $strategy ) : null
			);
		$this->assertKeyHasValue( 'TestDefaultValueKey', $expected );
	}

	/**
	 * @note Since implementations of setMultiDefault may inline logic of setDefault() for performance,
	 * we need to test all edge cases for setMultiDefault() as well.
	 *
	 * @dataProvider provideSetDefaultValue
	 *
	 * @param mixed $first
	 * @param mixed $second
	 * @param string $strategy
	 * @param mixed $expected
	 */
	public function testSetDefaultValue_multi( $first, $second, $strategy, $expected ) {
		$this->getConfigSink()
			->set( 'TestDefaultValueKey', $first )
			->setMultiDefault(
				[ 'TestDefaultValueKey' => $second ],
				[ 'TestDefaultValueKey' => $strategy ? MergeStrategy::newFromName( $strategy ) : null ]
			);
		$this->assertKeyHasValue( 'TestDefaultValueKey', $expected );
	}

	public function testSetMultiDefault() {
		$this->getConfigSink()
			->setMultiDefault(
				[
					'TestSetMultiDefault_string' => 'a',
					'TestSetMultiDefault_array' => [ 1, 2 ]
				],
				[
					'TestSetMultiDefault_array' => MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE )
				]
			)
			->setMultiDefault(
				[
					'TestSetMultiDefault_string' => 'b',
					'TestSetMultiDefault_array' => [ 3, 4 ]
				],
				[
					'TestSetMultiDefault_array' => MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE )
				]
			);
		$this->assertKeyHasValue( 'TestSetMultiDefault_string', 'a' );
		$this->assertKeyHasValue( 'TestSetMultiDefault_array', [ 3, 4, 1, 2 ] );
	}

}
