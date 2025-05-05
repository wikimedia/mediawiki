<?php

use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\BlockTargetFactory;
use MediaWiki\Block\BlockUtils;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Block\BlockUtils
 * @group Blocking
 * @author DannyS712
 */
class BlockUtilsTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;

	/**
	 * @param array $options
	 * @param UserIdentityLookup|null $userIdentityLookup
	 *
	 * @return BlockUtils
	 */
	private function getUtils(
		array $options = [],
		?UserIdentityLookup $userIdentityLookup = null
	) {
		$baseOptions = [
			MainConfigNames::BlockCIDRLimit => [
				'IPv4' => 16,
				'IPv6' => 19
			]
		];
		$serviceOptions = new ServiceOptions(
			BlockTargetFactory::CONSTRUCTOR_OPTIONS,
			$options + $baseOptions
		);

		return TestingAccessWrapper::newFromObject( new BlockUtils(
			new BlockTargetFactory(
				$serviceOptions,
				$userIdentityLookup ?? $this->createMock( UserIdentityLookup::class ),
				$this->getDummyUserNameUtils()
			)
		) );
	}

	/**
	 * @dataProvider provideTestParseBlockTargetUserIdentity
	 * @param string $name
	 * @param int $type either AbstractBlock::TYPE_IP or TYPE_USER
	 */
	public function testParseBlockTargetUserIdentity( string $name, int $type ) {
		// Code path: providing a UserIdentity
		// - target name is a valid IP, TYPE_IP
		// - target name is not a valid IP, TYPE_USER
		$userIdentity = new UserIdentityValue( $type === AbstractBlock::TYPE_IP ? 0 : 1, $name );

		[ $resTarget, $resType ] = $this->getUtils()->parseBlockTarget( $userIdentity );
		$this->assertSame( $type, $resType );
		if ( $type === AbstractBlock::TYPE_IP ) {
			// With the migration to BlockTargetFactory, preservation of the
			// UserIdentity object for an IP is no longer guaranteed.
			$this->assertTrue( $userIdentity->equals( $resTarget ) );
		} else {
			$this->assertSame( $userIdentity, $resTarget );
		}
	}

	public static function provideTestParseBlockTargetUserIdentity() {
		return [
			'Valid IP #1' => [ '1.2.3.4', AbstractBlock::TYPE_IP ],
			'Invalid IP #1' => [ 'DannyS712', AbstractBlock::TYPE_USER ],
		];
	}

	public function testParseBlockTargetNull() {
		// Code path: providing null
		$blockUtils = $this->getUtils( [], null );
		$this->assertSame(
			[ null, null ],
			$blockUtils->parseBlockTarget( null )
		);
	}

	public function testParseBlockTargetString() {
		// Code path: providing a string
		// - valid IP string
		$ip = '1.2.3.4';
		$userIdentity = UserIdentityValue::newAnonymous( $ip );

		$blockUtils = $this->getUtils();
		[ $target, $type ] = $blockUtils->parseBlockTarget( $ip );
		$this->assertTrue( $userIdentity->equals( $target ) );
		$this->assertSame( AbstractBlock::TYPE_IP, $type );

		// - valid IP range
		$ipRange = '127.111.113.151/24';
		$sanitizedRange = '127.111.113.0/24';
		$this->assertSame(
			[ $sanitizedRange, AbstractBlock::TYPE_RANGE ],
			$blockUtils->parseBlockTarget( $ipRange )
		);
	}

	/**
	 * @dataProvider provideTestParseBlockTargetNonIpString
	 * @param string $inputTarget
	 * @param ?UserIdentity $userIdentityLookupResult
	 * @param UserIdentity|string|null $outputTarget
	 * @param ?int $targetType
	 */
	public function testParseBlockTargetNonIpString(
		string $inputTarget,
		?UserIdentity $userIdentityLookupResult,
		$outputTarget,
		?int $targetType
	) {
		// - not an IP string, UserFactory::newFromName returns a User
		// - not an IP string, UserFactory::newFromName returns null,
		//     string begins with # and is an autoblock reference
		// - not an IP string, UserFactory::newFromName returns null,
		//     string does not begin with #
		// Also include the case for subpage handling
		$userIdentityLookup = $this->createMock( UserIdentityLookup::class );
		$userIdentityLookup
			->method( 'getUserIdentityByName' )
			->with( $inputTarget )
			->willReturn( $userIdentityLookupResult );
		$blockUtils = $this->getUtils(
			[],
			$userIdentityLookup
		);
		$this->assertSame(
			[ $outputTarget, $targetType ],
			$blockUtils->parseBlockTarget( $inputTarget )
		);
	}

	public static function provideTestParseBlockTargetNonIpString() {
		$userIdentity = UserIdentityValue::newRegistered( 42, 'DannyS712' );
		yield 'Name returns a valid user' => [
			'DannyS712',
			$userIdentity,
			$userIdentity,
			AbstractBlock::TYPE_USER
		];

		yield 'Auto block id' => [
			'#123',
			null,
			'123',
			AbstractBlock::TYPE_AUTO
		];

		yield 'Invalid user name, with subpage' => [
			'SomeInvalid#UserName/WithASubpage',
			null,
			null,
			null
		];
	}

}
