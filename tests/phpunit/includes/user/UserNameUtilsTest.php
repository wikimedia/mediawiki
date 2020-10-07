<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserNameUtils;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;

/**
 * @covers MediaWiki\User\UserNameUtils
 * @author DannyS712
 */
class UserNameUtilsTest extends MediaWikiIntegrationTestCase {

	private function getUCFirstLanguageMock() {
		// Used by a number of tests
		$contentLang = $this->getMockBuilder( Language::class )
			->disableOriginalConstructor()
			->getMock();
		$contentLang->method( 'ucfirst' )
			->willReturnCallback( function ( $str ) {
				return ucfirst( $str );
			} );
		return $contentLang;
	}

	private function getUtils(
		array $options = [],
		Language $contentLang = null,
		LoggerInterface $logger = null,
		ITextFormatter $textFormatter = null
	) {
		$baseOptions = [
			'MaxNameChars' => 255,
			'ReservedUsernames' => [
				'MediaWiki default'
			],
			'InvalidUsernameCharacters' => '@:'
		];
		$config = $options + $baseOptions;
		$serviceOptions = new ServiceOptions( UserNameUtils::CONSTRUCTOR_OPTIONS, $config );

		if ( $contentLang === null ) {
			$contentLang = $this->createMock( Language::class );
		}

		if ( $logger === null ) {
			$logger = new NullLogger();
		}

		$titleParser = MediaWikiServices::getInstance()->getTitleParser();

		if ( $textFormatter === null ) {
			$textFormatter = $this->getMockForAbstractClass( ITextFormatter::class );
		}

		$hookContainer = $this->createHookContainer();

		$utils = new UserNameUtils(
			$serviceOptions,
			$contentLang,
			$logger,
			$titleParser,
			$textFormatter,
			$hookContainer
		);
		return $utils;
	}

	/**
	 * @dataProvider provideIsValid
	 * @covers MediaWiki\User\UserNameUtils::isValid
	 */
	public function testIsValid( string $name, bool $result ) {
		$utils = $this->getUtils(
			[],
			$this->getUCFirstLanguageMock()
		);
		$this->assertSame(
			$result,
			$utils->isValid( $name )
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
			'Blacklisted characters' => [ "\u{E000}", false ],
		];
	}

	/**
	 * @dataProvider provideIsUsable
	 * @covers MediaWiki\User\UserNameUtils::isUsable
	 */
	public function testIsUsable( string $name, bool $result ) {
		$textFormatter = $this->getMockForAbstractClass( ITextFormatter::class );
		$textFormatter->method( 'format' )
			->with( $this->equalTo( MessageValue::new( 'reserved-user' ) ) )
			->willReturn( 'reserved-user' );

		$utils = $this->getUtils(
			[
				'ReservedUsernames' => [
					'MediaWiki default',
					'msg:reserved-user'
				],
			],
			$this->getUCFirstLanguageMock(),
			null,
			$textFormatter
		);
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
		$logger = new TestLogger( true, function ( $message ) {
			$message = str_replace(
				'MediaWiki\\User\\UserNameUtils::isCreatable: ',
				'',
				$message
			);
			return $message;
		} );
		$utils = $this->getUtils(
			[],
			$this->getUCFirstLanguageMock(),
			$logger
		);

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
	}

	/**
	 * @dataProvider provideGetCanonical
	 * @covers MediaWiki\User\UserNameUtils::getCanonical
	 */
	public function testGetCanonical( string $name, array $expectedArray ) {
		$utils = $this->getUtils(
			[],
			$this->getUCFirstLanguageMock()
		);
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
					UserNameUtils::RIGOR_CREATABLE => 'Normal name',
					UserNameUtils::RIGOR_USABLE => 'Normal name',
					UserNameUtils::RIGOR_VALID => 'Normal name',
					UserNameUtils::RIGOR_NONE => 'Normal name'
				]
			],
			'Leading space' => [
				' Leading space',
				[ UserNameUtils::RIGOR_CREATABLE => 'Leading space' ]
			],
			'Trailing space' => [
				'Trailing space ',
				[ UserNameUtils::RIGOR_CREATABLE => 'Trailing space' ]
			],
			'Namespace prefix' => [
				'Talk:Username',
				[
					UserNameUtils::RIGOR_CREATABLE => false,
					UserNameUtils::RIGOR_USABLE => false,
					UserNameUtils::RIGOR_VALID => false,
					UserNameUtils::RIGOR_NONE => 'Talk:Username'
				]
			],
			'With hash' => [
				'name with # hash',
				[
					UserNameUtils::RIGOR_CREATABLE => false,
					UserNameUtils::RIGOR_USABLE => false
				]
			],
			'Multi spaces' => [
				'Multi  spaces',
				[
					UserNameUtils::RIGOR_CREATABLE => 'Multi spaces',
					UserNameUtils::RIGOR_USABLE => 'Multi spaces'
				]
			],
			'Lowercase' => [
				'lowercase',
				[ UserNameUtils::RIGOR_CREATABLE => 'Lowercase' ]
			],
			'Invalid character' => [
				'in[]valid',
				[
					UserNameUtils::RIGOR_CREATABLE => false,
					UserNameUtils::RIGOR_USABLE => false,
					UserNameUtils::RIGOR_VALID => false,
					UserNameUtils::RIGOR_NONE => 'In[]valid'
				]
			],
			'With slash' => [
				'with / slash',
				[
					UserNameUtils::RIGOR_CREATABLE => false,
					UserNameUtils::RIGOR_USABLE => false,
					UserNameUtils::RIGOR_VALID => false,
					UserNameUtils::RIGOR_NONE => 'With / slash'
				]
			],
		];
	}

	/**
	 * @covers MediaWiki\User\UserNameUtils::getCanonical
	 */
	public function testGetCanonical_interwiki() {
		// fake interwiki map for the 'Interwiki prefix' testcase
		$this->setTemporaryHook(
			'InterwikiLoadPrefix',
			function ( $prefix, &$iwdata ) {
				if ( $prefix === 'interwiki' ) {
					$iwdata = [
						'iw_url' => 'http://example.com/',
						'iw_local' => 0,
						'iw_trans' => 0,
					];
					return false;
				}
			}
		);

		$utils = $this->getUtils(
			[],
			$this->getUCFirstLanguageMock()
		);

		$name = 'interwiki:Username';
		$this->assertFalse(
			$utils->getCanonical(
				$name,
				UserNameUtils::RIGOR_CREATABLE
			),
			"'$name' is not creatable"
		);
		$this->assertFalse(
			$utils->getCanonical(
				$name,
				UserNameUtils::RIGOR_USABLE
			),
			"'$name' is not usable"
		);
		$this->assertFalse(
			$utils->getCanonical(
				$name,
				UserNameUtils::RIGOR_VALID
			),
			"'$name' is not valid"
		);
		$this->assertSame(
			'Interwiki:Username',
			$utils->getCanonical( $name, UserNameUtils::RIGOR_NONE )
		);
	}

	/**
	 * @covers MediaWiki\User\UserNameUtils::getCanonical
	 */
	public function testGetCanonical_bad() {
		// Only ucfirst is called
		$utils = $this->getUtils(
			[],
			$this->getUCFirstLanguageMock()
		);
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Invalid parameter value for validation' );
		$utils->getCanonical( 'ValidName', 'InvalidValidationValue' );
	}

	/**
	 * @dataProvider provideIPs
	 * @covers MediaWiki\User\UserNameUtils::isIP
	 */
	public function testIsIP( string $value, bool $result ) {
		$utils = $this->getUtils();
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
		$utils = $this->getUtils();
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

}
