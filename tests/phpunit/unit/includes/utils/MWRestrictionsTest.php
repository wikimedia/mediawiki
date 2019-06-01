<?php
class MWRestrictionsTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	protected static $restrictionsForChecks;

	public static function setUpBeforeClass() {
		self::$restrictionsForChecks = MWRestrictions::newFromArray( [
			'IPAddresses' => [
				'10.0.0.0/8',
				'172.16.0.0/12',
				'2001:db8::/33',
			]
		] );
	}

	/**
	 * @covers MWRestrictions::newDefault
	 * @covers MWRestrictions::__construct
	 */
	public function testNewDefault() {
		$ret = MWRestrictions::newDefault();
		$this->assertInstanceOf( MWRestrictions::class, $ret );
		$this->assertSame(
			'{"IPAddresses":["0.0.0.0/0","::/0"]}',
			$ret->toJson()
		);
	}

	/**
	 * @covers MWRestrictions::newFromArray
	 * @covers MWRestrictions::__construct
	 * @covers MWRestrictions::loadFromArray
	 * @covers MWRestrictions::toArray
	 * @dataProvider provideArray
	 * @param array $data
	 * @param bool|InvalidArgumentException $expect True if the call succeeds,
	 *  otherwise the exception that should be thrown.
	 */
	public function testArray( $data, $expect ) {
		if ( $expect === true ) {
			$ret = MWRestrictions::newFromArray( $data );
			$this->assertInstanceOf( MWRestrictions::class, $ret );
			$this->assertSame( $data, $ret->toArray() );
		} else {
			try {
				MWRestrictions::newFromArray( $data );
				$this->fail( 'Expected exception not thrown' );
			} catch ( InvalidArgumentException $ex ) {
				$this->assertEquals( $expect, $ex );
			}
		}
	}

	public static function provideArray() {
		return [
			[ [ 'IPAddresses' => [] ], true ],
			[ [ 'IPAddresses' => [ '127.0.0.1/32' ] ], true ],
			[
				[ 'IPAddresses' => [ '256.0.0.1/32' ] ],
				new InvalidArgumentException( 'Invalid IP address: 256.0.0.1/32' )
			],
			[
				[ 'IPAddresses' => '127.0.0.1/32' ],
				new InvalidArgumentException( 'IPAddresses is not an array' )
			],
			[
				[],
				new InvalidArgumentException( 'Array is missing required keys: IPAddresses' )
			],
			[
				[ 'foo' => 'bar', 'bar' => 42 ],
				new InvalidArgumentException( 'Array contains invalid keys: foo, bar' )
			],
		];
	}

	/**
	 * @covers MWRestrictions::newFromJson
	 * @covers MWRestrictions::__construct
	 * @covers MWRestrictions::loadFromArray
	 * @covers MWRestrictions::toJson
	 * @covers MWRestrictions::__toString
	 * @dataProvider provideJson
	 * @param string $json
	 * @param array|InvalidArgumentException $expect
	 */
	public function testJson( $json, $expect ) {
		if ( is_array( $expect ) ) {
			$ret = MWRestrictions::newFromJson( $json );
			$this->assertInstanceOf( MWRestrictions::class, $ret );
			$this->assertSame( $expect, $ret->toArray() );

			$this->assertSame( $json, $ret->toJson( false ) );
			$this->assertSame( $json, (string)$ret );

			$this->assertSame(
				FormatJson::encode( $expect, true, FormatJson::ALL_OK ),
				$ret->toJson( true )
			);
		} else {
			try {
				MWRestrictions::newFromJson( $json );
				$this->fail( 'Expected exception not thrown' );
			} catch ( InvalidArgumentException $ex ) {
				$this->assertTrue( true );
			}
		}
	}

	public static function provideJson() {
		return [
			[
				'{"IPAddresses":[]}',
				[ 'IPAddresses' => [] ]
			],
			[
				'{"IPAddresses":["127.0.0.1/32"]}',
				[ 'IPAddresses' => [ '127.0.0.1/32' ] ]
			],
			[
				'{"IPAddresses":["256.0.0.1/32"]}',
				new InvalidArgumentException( 'Invalid IP address: 256.0.0.1/32' )
			],
			[
				'{"IPAddresses":"127.0.0.1/32"}',
				new InvalidArgumentException( 'IPAddresses is not an array' )
			],
			[
				'{}',
				new InvalidArgumentException( 'Array is missing required keys: IPAddresses' )
			],
			[
				'{"foo":"bar","bar":42}',
				new InvalidArgumentException( 'Array contains invalid keys: foo, bar' )
			],
			[
				'{"IPAddresses":[]',
				new InvalidArgumentException( 'Invalid restrictions JSON' )
			],
			[
				'"IPAddresses"',
				new InvalidArgumentException( 'Invalid restrictions JSON' )
			],
		];
	}

	/**
	 * @covers MWRestrictions::checkIP
	 * @dataProvider provideCheckIP
	 * @param string $ip
	 * @param bool $pass
	 */
	public function testCheckIP( $ip, $pass ) {
		$this->assertSame( $pass, self::$restrictionsForChecks->checkIP( $ip ) );
	}

	public static function provideCheckIP() {
		return [
			[ '10.0.0.1', true ],
			[ '172.16.0.0', true ],
			[ '192.0.2.1', false ],
			[ '2001:db8:1::', true ],
			[ '2001:0db8:0000:0000:0000:0000:0000:0000', true ],
			[ '2001:0DB8:8000::', false ],
		];
	}

	/**
	 * @covers MWRestrictions::check
	 * @dataProvider provideCheck
	 * @param WebRequest $request
	 * @param Status $expect
	 */
	public function testCheck( $request, $expect ) {
		$this->assertEquals( $expect, self::$restrictionsForChecks->check( $request ) );
	}

	public function provideCheck() {
		$ret = [];

		$mockBuilder = $this->getMockBuilder( FauxRequest::class )
			->setMethods( [ 'getIP' ] );

		foreach ( self::provideCheckIP() as $checkIP ) {
			$ok = [];
			$request = $mockBuilder->getMock();

			$request->expects( $this->any() )->method( 'getIP' )
				->will( $this->returnValue( $checkIP[0] ) );
			$ok['ip'] = $checkIP[1];

			/* If we ever add more restrictions, add nested for loops here:
			 *  foreach ( self::provideCheckFoo() as $checkFoo ) {
			 *      $request->expects( $this->any() )->method( 'getFoo' )
			 *          ->will( $this->returnValue( $checkFoo[0] );
			 *      $ok['foo'] = $checkFoo[1];
			 *
			 *      foreach ( self::provideCheckBar() as $checkBar ) {
			 *          $request->expects( $this->any() )->method( 'getBar' )
			 *              ->will( $this->returnValue( $checkBar[0] );
			 *          $ok['bar'] = $checkBar[1];
			 *
			 *          // etc.
			 *      }
			 *  }
			 */

			$status = Status::newGood();
			$status->setResult( $ok === array_filter( $ok ), $ok );
			$ret[] = [ $request, $status ];
		}

		return $ret;
	}
}
