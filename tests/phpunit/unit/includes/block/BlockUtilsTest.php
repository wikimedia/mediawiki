<?php

use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\BlockUtils;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers MediaWiki\Block\BlockUtils
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
		UserIdentityLookup $userIdentityLookup = null
	) {
		$baseOptions = [
			MainConfigNames::BlockCIDRLimit => [
				'IPv4' => 16,
				'IPv6' => 19
			]
		];
		$config = $options + $baseOptions;
		$serviceOptions = new ServiceOptions(
			BlockUtils::CONSTRUCTOR_OPTIONS,
			$config
		);

		if ( $userIdentityLookup === null ) {
			$userIdentityLookup = $this->createMock( UserIdentityLookup::class );
		}

		$utils = new BlockUtils(
			$serviceOptions,
			$userIdentityLookup,
			$this->getDummyUserNameUtils()
		);
		$wrapper = TestingAccessWrapper::newFromObject( $utils );
		return $wrapper;
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

		$this->assertSame(
			[ $userIdentity, $type ],
			$this->getUtils()->parseBlockTarget( $userIdentity )
		);
	}

	public function provideTestParseBlockTargetUserIdentity() {
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
		list( $target, $type ) = $blockUtils->parseBlockTarget( $ip );
		$this->assertTrue( $userIdentity->equals( $target ) );
		$this->assertSame( $type, AbstractBlock::TYPE_IP );

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
	 * @param string $baseName if it was a subpage
	 * @param ?UserIdentity $userIdentityLookupResult
	 * @param UserIdentity|string|null $outputTarget
	 * @param ?int $targetType
	 */
	public function testParseBlockTargetNonIpString(
		string $inputTarget,
		string $baseName,
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
			->with( $baseName )
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

	public function provideTestParseBlockTargetNonIpString() {
		$userIdentity = $this->createMock( UserIdentity::class );
		yield 'Name returns a valid user' => [
			'DannyS712',
			'DannyS712',
			$userIdentity,
			$userIdentity,
			AbstractBlock::TYPE_USER
		];

		yield 'Auto block id' => [
			'#123',
			'#123',
			null,
			'123',
			AbstractBlock::TYPE_AUTO
		];

		yield 'Invalid user name, with subpage' => [
			'SomeInvalid#UserName/WithASubpage',
			'SomeInvalid#UserName',
			null,
			null,
			null
		];
	}

}
