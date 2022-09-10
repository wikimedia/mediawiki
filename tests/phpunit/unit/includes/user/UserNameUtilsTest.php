<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\UserRigorOptions;
use Psr\Log\LogLevel;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;

/**
 * @covers MediaWiki\User\UserNameUtils
 * @author DannyS712
 */
class UserNameUtilsTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;

	/**
	 * @dataProvider provideIsValid
	 * @covers MediaWiki\User\UserNameUtils::isValid
	 */
	public function testIsValid( string $name, bool $result ) {
		$this->assertSame(
			$result,
			$this->getDummyUserNameUtils()->isValid( $name )
		);
	}

	public function provideIsValid() {
		return [
			'Empty string' => [ '', false ],
			'Blank space' => [ ' ', false ],
			'Starts with small letter' => [ 'abcd', false ],
			'Contains slash' => [ 'Ab/cd', false ],
			'Whitespace' => [ 'Ab cd', true ],
			'IP' => [ '192.168.1.1', false ],
			'IP range' => [ '116.17.184.5/32', false ],
			'IPv6 range' => [ '::e:f:2001/96', false ],
			'Reserved Namespace' => [ 'User:Abcd', false ],
			'Starts with Numbers' => [ '12abcd232', true ],
			'Start with ? mark' => [ '?abcd', true ],
			'Start with #' => [ '#abcd', false ],
			' Mixed scripts' => [ 'Abcdകഖഗഘ', true ],
			'ZWNJ- Format control character' => [ 'ജോസ്‌തോമസ്', false ],
			' Ideographic space' => [ 'Ab　cd', false ],
			'Looks too much like an IPv4 address (1)' => [ '300.300.300.300', false ],
			'Looks too much like an IPv4 address (2)' => [ '302.113.311.900', false ],
			'Reserved for usage by UseMod for cloaked logged-out users' => [ '203.0.113.xxx', false ],
			'Disallowed characters' => [ "\u{E000}", false ],
		];
	}

	/**
	 * @dataProvider provideIsUsable
	 * @covers MediaWiki\User\UserNameUtils::isUsable
	 */
	public function testIsUsable( string $name, bool $result ) {
		$textFormatter = $this->getMockForAbstractClass( ITextFormatter::class );
		$textFormatter->method( 'format' )
			->with( MessageValue::new( 'reserved-user' ) )
			->willReturn( 'reserved-user' );

		$utils = $this->getDummyUserNameUtils( [
			MainConfigNames::ReservedUsernames => [
				'MediaWiki default',
				'msg:reserved-user'
			],
			'textFormatter' => $textFormatter,
		] );
		$this->assertSame(
			$result,
			$utils->isUsable( $name )
		);
	}

	public function provideIsUsable() {
		return [
			'Only valid user names are creatable' => [ '', false ],
			'Reserved names cannot be used' => [ 'MediaWiki default', false ],
			'Names can also be reserved via msg: ' => [ 'reserved-user', false ],
			'User names with no issues can be used' => [ 'FooBar', true ],
		];
	}

	/**
	 * @covers MediaWiki\User\UserNameUtils::isCreatable
	 */
	public function testIsCreatable() {
		$logger = new TestLogger( true, static function ( $message ) {
			$message = str_replace(
				'MediaWiki\\User\\UserNameUtils::isCreatable: ',
				'',
				$message
			);
			return $message;
		} );
		$utils = $this->getDummyUserNameUtils( [
			'logger' => $logger,
		] );

		$longUserName = str_repeat( 'q', 1000 );
		$this->assertFalse(
			$utils->isCreatable( $longUserName ),
			'longUserName is too long'
		);
		$this->assertSame( [
			[ LogLevel::DEBUG, "'$longUserName' uncreatable due to length" ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$atUserName = 'Foo@Bar';
		$this->assertFalse(
			$utils->isCreatable( $atUserName ),
			'User name contains invalid character'
		);
		$this->assertSame( [
			[ LogLevel::DEBUG, "'$atUserName' uncreatable due to wgInvalidUsernameCharacters" ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$this->assertTrue(
			$utils->isCreatable( 'FooBar' ),
			'User names with no issues can be created'
		);

		$tempUserName = '*Unregistered 1234';
		$this->assertFalse(
			$utils->isCreatable( $tempUserName ),
			'UI account creation with the temp user prefix needs to be prevented'
		);
	}

	/**
	 * @dataProvider provideGetCanonical
	 * @covers MediaWiki\User\UserNameUtils::getCanonical
	 */
	public function testGetCanonical( string $name, array $expectedArray ) {
		$utils = $this->getDummyUserNameUtils();
		foreach ( $expectedArray as $validate => $expected ) {
			$this->assertSame(
				$expected,
				$utils->getCanonical( $name, $validate ),
				"Validating '$name' with level '$validate' should be '$expected'"
			);
		}
	}

	public static function provideGetCanonical() {
		return [
			'Normal name' => [
				'Normal name',
				[
					UserRigorOptions::RIGOR_CREATABLE => 'Normal name',
					UserRigorOptions::RIGOR_USABLE => 'Normal name',
					UserRigorOptions::RIGOR_VALID => 'Normal name',
					UserRigorOptions::RIGOR_NONE => 'Normal name'
				]
			],
			'Leading space' => [
				' Leading space',
				[ UserRigorOptions::RIGOR_CREATABLE => 'Leading space' ]
			],
			'Trailing space' => [
				'Trailing space ',
				[ UserRigorOptions::RIGOR_CREATABLE => 'Trailing space' ]
			],
			'Subject namespace prefix' => [
				'User:Username',
				[
					UserRigorOptions::RIGOR_NONE => 'Username'
				]
			],
			'Talk namespace prefix' => [
				'Talk:Username',
				[
					UserRigorOptions::RIGOR_CREATABLE => false,
					UserRigorOptions::RIGOR_USABLE => false,
					UserRigorOptions::RIGOR_VALID => false,
					UserRigorOptions::RIGOR_NONE => 'Talk:Username'
				]
			],
			'With hash' => [
				'name with # hash',
				[
					UserRigorOptions::RIGOR_CREATABLE => false,
					UserRigorOptions::RIGOR_USABLE => false
				]
			],
			'Multi spaces' => [
				'Multi  spaces',
				[
					UserRigorOptions::RIGOR_CREATABLE => 'Multi spaces',
					UserRigorOptions::RIGOR_USABLE => 'Multi spaces'
				]
			],
			'Lowercase' => [
				'lowercase',
				[ UserRigorOptions::RIGOR_CREATABLE => 'Lowercase' ]
			],
			'Invalid character' => [
				'in[]valid',
				[
					UserRigorOptions::RIGOR_CREATABLE => false,
					UserRigorOptions::RIGOR_USABLE => false,
					UserRigorOptions::RIGOR_VALID => false,
					UserRigorOptions::RIGOR_NONE => 'In[]valid'
				]
			],
			'With slash' => [
				'with / slash',
				[
					UserRigorOptions::RIGOR_CREATABLE => false,
					UserRigorOptions::RIGOR_USABLE => false,
					UserRigorOptions::RIGOR_VALID => false,
					UserRigorOptions::RIGOR_NONE => 'With / slash'
				]
			],
			// 'interwiki' is a valid interwiki prefix, per configuration of the
			// title formatter in DummyServicesTrait::getDummyUserNameUtils()
			'Interwiki prefix in username' => [
				'interwiki:Username',
				[
					UserRigorOptions::RIGOR_CREATABLE => false,
					UserRigorOptions::RIGOR_USABLE => false,
					UserRigorOptions::RIGOR_VALID => false,
					UserRigorOptions::RIGOR_NONE => 'Interwiki:Username'
				]
			],
		];
	}

	/**
	 * @covers MediaWiki\User\UserNameUtils::getCanonical
	 */
	public function testGetCanonical_bad() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Invalid parameter value for validation' );
		$this->getDummyUserNameUtils()->getCanonical( 'ValidName', 'InvalidValidationValue' );
	}

	/**
	 * @dataProvider provideIPs
	 * @covers MediaWiki\User\UserNameUtils::isIP
	 */
	public function testIsIP( string $value, bool $result ) {
		$utils = $this->getDummyUserNameUtils();
		$this->assertSame(
			$result,
			$utils->isIP( $value )
		);
	}

	public function provideIPs() {
		return [
			'Empty string' => [ '', false ],
			'Blank space' => [ ' ', false ],
			'IPv4 private 10/8 (1)' => [ '10.0.0.0', true ],
			'IPv4 private 10/8 (2)' => [ '10.255.255.255', true ],
			'IPv4 private 192.168/16' => [ '192.168.1.1', true ],
			'IPv4 example' => [ '203.0.113.0', true ],
			'IPv6 example' => [ '2002:ffff:ffff:ffff:ffff:ffff:ffff:ffff', true ],
			// Not valid IPs but classified as such by MediaWiki for negated asserting
			// of whether this might be the identifier of a logged-out user or whether
			// to allow usernames like it.
			'Looks too much like an IPv4 address' => [ '300.300.300.300', true ],
			'Assigned by UseMod to cloaked logged-out users' => [ '203.0.113.xxx', true ],
			'Does not accept IPv4 ranges' => [ '74.24.52.13/20', false ],
			'Does not accept IPv6 ranges' => [ 'fc:100:a:d:1:e:ac:0/24', false ],
		];
	}

	/**
	 * @dataProvider provideIPRanges
	 * @covers MediaWiki\User\UserNameUtils::isValidIPRange
	 */
	public function testIsValidIPRange( $value, $result ) {
		$utils = $this->getDummyUserNameUtils();
		$this->assertSame(
			$result,
			$utils->isValidIPRange( $value )
		);
	}

	public function provideIPRanges() {
		return [
			[ '116.17.184.5/32', true ],
			[ '0.17.184.5/30', true ],
			[ '16.17.184.1/24', true ],
			[ '30.242.52.14/1', true ],
			[ '10.232.52.13/8', true ],
			[ '30.242.52.14/0', true ],
			[ '::e:f:2001/96', true ],
			[ '::c:f:2001/128', true ],
			[ '::10:f:2001/70', true ],
			[ '::fe:f:2001/1', true ],
			[ '::6d:f:2001/8', true ],
			[ '::fe:f:2001/0', true ],
			[ '116.17.184.5/33', false ],
			[ '0.17.184.5/130', false ],
			[ '16.17.184.1/-1', false ],
			[ '10.232.52.13/*', false ],
			[ '7.232.52.13/ab', false ],
			[ '11.232.52.13/', false ],
			[ '::e:f:2001/129', false ],
			[ '::c:f:2001/228', false ],
			[ '::10:f:2001/-1', false ],
			[ '::6d:f:2001/*', false ],
			[ '::86:f:2001/ab', false ],
			[ '::23:f:2001/', false ]
		];
	}

	public function testIsTemp() {
		$utils = $this->getDummyUserNameUtils();
		$this->assertFalse( $utils->isTemp( 'Some user' ) );
		$this->assertTrue( $utils->isTemp( '*Unregistered 1234' ) );
		$this->assertTrue( $utils->isTemp( '*1234' ) );
	}

	public function testGetTempPlaceholder() {
		$utils = $this->getDummyUserNameUtils();
		$name = $utils->getTempPlaceholder();
		$this->assertSame( '*Unregistered *', $name );
	}

}
