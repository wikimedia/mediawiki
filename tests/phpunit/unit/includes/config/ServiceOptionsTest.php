<?php

use MediaWiki\Config\ServiceOptions;

/**
 * @coversDefaultClass \MediaWiki\Config\ServiceOptions
 */
class ServiceOptionsTest extends \MediaWikiUnitTestCase {
	public static $testObj;

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();

		self::$testObj = (object)[];
	}

	/**
	 * @dataProvider provideConstructor
	 * @covers ::__construct
	 * @covers ::assertRequiredOptions
	 * @covers ::get
	 */
	public function testConstructor( $expected, $keys, ...$sources ) {
		$options = new ServiceOptions( $keys, ...$sources );

		foreach ( $expected as $key => $val ) {
			$this->assertSame( $val, $options->get( $key ) );
		}

		// This is lumped in the same test because there's no support for depending on a test that
		// has a data provider.
		$options->assertRequiredOptions( array_keys( $expected ) );

		// Suppress warning if no assertions were run. This is expected for empty arguments.
		$this->assertTrue( true );
	}

	public function provideConstructor() {
		return [
			'No keys' => [ [], [], [ 'a' => 'aval' ] ],
			'Simple array source' => [
				[ 'a' => 'aval', 'b' => 'bval' ],
				[ 'a', 'b' ],
				[ 'a' => 'aval', 'b' => 'bval', 'c' => 'cval' ],
			],
			'Simple HashConfig source' => [
				[ 'a' => 'aval', 'b' => 'bval' ],
				[ 'a', 'b' ],
				new HashConfig( [ 'a' => 'aval', 'b' => 'bval', 'c' => 'cval' ] ),
			],
			'Three different sources' => [
				[ 'a' => 'aval', 'b' => 'bval' ],
				[ 'a', 'b' ],
				[ 'z' => 'zval' ],
				new HashConfig( [ 'a' => 'aval', 'c' => 'cval' ] ),
				[ 'b' => 'bval', 'd' => 'dval' ],
			],
			'null key' => [
				[ 'a' => null ],
				[ 'a' ],
				[ 'a' => null ],
			],
			'Numeric option name' => [
				[ '0' => 'nothing' ],
				[ '0' ],
				[ '0' => 'nothing' ],
			],
			'Multiple sources for one key' => [
				[ 'a' => 'winner' ],
				[ 'a' ],
				[ 'a' => 'winner' ],
				[ 'a' => 'second place' ],
			],
			'Object value is passed by reference' => [
				[ 'a' => self::$testObj ],
				[ 'a' ],
				[ 'a' => self::$testObj ],
			],
		];
	}

	/**
	 * @covers ::__construct
	 */
	public function testKeyNotFound() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Key "a" not found in input sources' );

		new ServiceOptions( [ 'a' ], [ 'b' => 'bval' ], [ 'c' => 'cval' ] );
	}

	/**
	 * @covers ::__construct
	 * @covers ::assertRequiredOptions
	 */
	public function testOutOfOrderAssertRequiredOptions() {
		$options = new ServiceOptions( [ 'a', 'b' ], [ 'a' => '', 'b' => '' ] );
		$options->assertRequiredOptions( [ 'b', 'a' ] );
		$this->assertTrue( true, 'No exception thrown' );
	}

	/**
	 * @covers ::__construct
	 * @covers ::get
	 */
	public function testGetUnrecognized() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Unrecognized option "b"' );

		$options = new ServiceOptions( [ 'a' ], [ 'a' => '' ] );
		$options->get( 'b' );
	}

	/**
	 * @covers ::__construct
	 * @covers ::assertRequiredOptions
	 */
	public function testExtraKeys() {
		$this->expectException( Wikimedia\Assert\PreconditionException::class );
		$this->expectExceptionMessage( 'Precondition failed: Unsupported options passed: b, c!' );

		$options = new ServiceOptions( [ 'a', 'b', 'c' ], [ 'a' => '', 'b' => '', 'c' => '' ] );
		$options->assertRequiredOptions( [ 'a' ] );
	}

	/**
	 * @covers ::__construct
	 * @covers ::assertRequiredOptions
	 */
	public function testMissingKeys() {
		$this->expectException( Wikimedia\Assert\PreconditionException::class );
		$this->expectExceptionMessage( 'Precondition failed: Required options missing: a, b!' );

		$options = new ServiceOptions( [ 'c' ], [ 'c' => '' ] );
		$options->assertRequiredOptions( [ 'a', 'b', 'c' ] );
	}

	/**
	 * @covers ::__construct
	 * @covers ::assertRequiredOptions
	 */
	public function testExtraAndMissingKeys() {
		$this->expectException( Wikimedia\Assert\PreconditionException::class );
		$this->expectExceptionMessage(
			'Precondition failed: Unsupported options passed: b! Required options missing: c!' );

		$options = new ServiceOptions( [ 'a', 'b' ], [ 'a' => '', 'b' => '' ] );
		$options->assertRequiredOptions( [ 'a', 'c' ] );
	}
}
