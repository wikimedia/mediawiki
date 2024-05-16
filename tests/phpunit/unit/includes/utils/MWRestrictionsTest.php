<?php

use MediaWiki\Json\FormatJson;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\Status\Status;

class MWRestrictionsTest extends MediaWikiUnitTestCase {

	protected static $restrictionsForChecks;

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();
		self::$restrictionsForChecks = MWRestrictions::newFromArray( [
			'IPAddresses' => [
				'10.0.0.0/8',
				'172.16.0.0/12',
				'2001:db8::/33',
			]
		] );
	}

	/**
	 * @covers \MWRestrictions::newDefault
	 * @covers \MWRestrictions::__construct
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
	 * @covers \MWRestrictions::newFromArray
	 * @covers \MWRestrictions::__construct
	 * @covers \MWRestrictions::loadFromArray
	 * @covers \MWRestrictions::toArray
	 * @dataProvider provideArray
	 * @param array $data
	 * @param StatusValue|InvalidArgumentException $expect StatusValue if the call succeeds,
	 *  otherwise the exception that should be thrown.
	 */
	public function testArray( $data, $expect ) {
		if ( $expect instanceof StatusValue ) {
			$ret = MWRestrictions::newFromArray( $data );
			$this->assertInstanceOf( MWRestrictions::class, $ret );
			$this->assertSame( $data, $ret->toArray() );
			$this->assertSame( $ret->validity->getErrors(), $expect->getErrors() );
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
			[ [ 'IPAddresses' => [] ], StatusValue::newGood() ],
			[ [ 'IPAddresses' => [ '127.0.0.1/32' ] ], StatusValue::newGood() ],
			[
				[ 'IPAddresses' => [ '256.0.0.1/32' ] ],
				StatusValue::newFatal( 'restrictionsfield-badip', '256.0.0.1/32' )
			],
			[
				[ 'IPAddresses' => [ '127.0.0.1/32' ], 'Pages' => [ "Main Page", "Main Page/sandbox" ] ],
				StatusValue::newGood()
			],
			[
				[ 'IPAddresses' => '127.0.0.1/32' ],
				new InvalidArgumentException( 'IPAddresses is not an array' )
			],
			[
				[],
				new InvalidArgumentException( 'Array is missing required keys: IPAddresses' )
			]
		];
	}

	/**
	 * @covers \MWRestrictions::newFromJson
	 * @covers \MWRestrictions::__construct
	 * @covers \MWRestrictions::loadFromArray
	 * @covers \MWRestrictions::toJson
	 * @covers \MWRestrictions::__toString
	 * @dataProvider provideJson
	 * @param string $json
	 * @param array|null $restrictions
	 * @param StatusValue|InvalidArgumentException $expect
	 */
	public function testJson( $json, $restrictions, $expect ) {
		if ( $expect instanceof StatusValue ) {
			$ret = MWRestrictions::newFromJson( $json );
			$this->assertInstanceOf( MWRestrictions::class, $ret );
			$this->assertSame( $restrictions, $ret->toArray() );

			$this->assertSame( $json, $ret->toJson( false ) );
			$this->assertSame( $json, (string)$ret );

			$this->assertSame(
				FormatJson::encode( $restrictions, true, FormatJson::ALL_OK ),
				$ret->toJson( true )
			);
			$this->assertSame( $expect->getErrors(), $ret->validity->getErrors() );
		} else {
			try {
				MWRestrictions::newFromJson( $json );
				$this->fail( 'Expected exception not thrown' );
			} catch ( InvalidArgumentException $ex ) {
				$this->assertEquals( $expect, $ex );
			}
		}
	}

	public static function provideJson() {
		return [
			[
				'{"IPAddresses":[]}',
				[ 'IPAddresses' => [] ],
				StatusValue::newGood()
			],
			[
				'{"IPAddresses":["127.0.0.1/32"]}',
				[ 'IPAddresses' => [ '127.0.0.1/32' ] ],
				StatusValue::newGood()
			],
			[
				'{"IPAddresses":["127.0.0.1/32"],"Pages":["Main Page"]}',
				[ 'IPAddresses' => [ '127.0.0.1/32' ], 'Pages' => [ "Main Page" ] ],
				StatusValue::newGood()
			],
			[
				'{"IPAddresses":["256.0.0.1/32"]}',
				[ 'IPAddresses' => [ '256.0.0.1/32' ] ],
				StatusValue::newFatal( 'restrictionsfield-badip', '256.0.0.1/32' )
			],
			[
				'{"IPAddresses":"127.0.0.1/32"}',
				null,
				new InvalidArgumentException( 'IPAddresses is not an array' )
			],
			[
				'{}',
				null,
				new InvalidArgumentException( 'Array is missing required keys: IPAddresses' )
			],
			[
				'{"IPAddresses":[]',
				null,
				new InvalidArgumentException( 'Invalid restrictions JSON' )
			],
			[
				'"IPAddresses"',
				null,
				new InvalidArgumentException( 'Invalid restrictions JSON' )
			],
		];
	}

	/**
	 * @covers \MWRestrictions::checkIP
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
	 * @covers \MWRestrictions::check
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
			->onlyMethods( [ 'getIP' ] );

		foreach ( self::provideCheckIP() as $checkIP ) {
			$ok = [];
			$request = $mockBuilder->getMock();

			$request->method( 'getIP' )
				->willReturn( $checkIP[0] );
			$ok['ip'] = $checkIP[1];

			/* If we ever add more restrictions, add nested for loops here:
			 *  foreach ( self::provideCheckFoo() as $checkFoo ) {
			 *      $request->method( 'getFoo' )->willReturn( $checkFoo[0] );
			 *      $ok['foo'] = $checkFoo[1];
			 *
			 *      foreach ( self::provideCheckBar() as $checkBar ) {
			 *          $request->method( 'getBar' )->willReturn( $checkBar[0] );
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
