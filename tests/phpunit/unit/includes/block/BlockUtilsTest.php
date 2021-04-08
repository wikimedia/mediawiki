<?php

use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\BlockUtils;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers MediaWiki\Block\BlockUtils
 * @group Blocking
 * @author DannyS712
 */
class BlockUtilsTest extends MediaWikiUnitTestCase {

	private function getUtils(
		array $options = [],
		UserFactory $userFactory = null
	) {
		$baseOptions = [
			'BlockCIDRLimit' => [
				'IPv4' => 16,
				'IPv6' => 19
			]
		];
		$config = $options + $baseOptions;
		$serviceOptions = new ServiceOptions(
			BlockUtils::CONSTRUCTOR_OPTIONS,
			$config
		);

		if ( $userFactory === null ) {
			$userFactory = $this->createMock( UserFactory::class );
		}

		$utils = new BlockUtils(
			$serviceOptions,
			$userFactory
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
		$userIdentity = $this->createMock( UserIdentity::class );
		$userIdentity->method( 'getName' )
			->willReturn( $name );
		$userObject = $this->createMock( User::class );

		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->expects( $this->once() )
			->method( 'newFromUserIdentity' )
			->with(
				$this->equalTo( $userIdentity )
			)
			->willReturn( $userObject );

		$blockUtils = $this->getUtils( [], $userFactory );
		$this->assertSame(
			[ $userObject, $type ],
			$blockUtils->parseBlockTarget( $userIdentity )
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
		$user = $this->createMock( User::class );
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->expects( $this->once() )
			->method( 'newFromName' )
			->with(
				$this->equalTo( $ip ),
				$this->equalTo( UserFactory::RIGOR_NONE )
			)
			->willReturn( $user );

		$blockUtils = $this->getUtils( [], $userFactory );
		$this->assertSame(
			[ $user, AbstractBlock::TYPE_IP ],
			$blockUtils->parseBlockTarget( $ip )
		);

		// - valid IP range
		// UserFactory isn't used for this, so no need to create a new BlockUtils
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
	 * @param ?User $userFactoryResult
	 * @param User|string|null $outputTarget
	 * @param ?int $targetType
	 */
	public function testParseBlockTargetNonIpString(
		string $inputTarget,
		string $baseName,
		?User $userFactoryResult,
		$outputTarget,
		?int $targetType
	) {
		// - not an IP string, UserFactory::newFromName returns a User
		// - not an IP string, UserFactory::newFromName returns null,
		//     string begins with # and is an autoblock reference
		// - not an IP string, UserFactory::newFromName returns null,
		//     string does not begin with #
		// Also include the case for subpage handling
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->expects( $this->once() )
			->method( 'newFromName' )
			->with(
				$this->equalTo( $baseName )
			)
			->willReturn( $userFactoryResult );
		$blockUtils = $this->getUtils( [], $userFactory );
		$this->assertSame(
			[ $outputTarget, $targetType ],
			$blockUtils->parseBlockTarget( $inputTarget )
		);
	}

	public function provideTestParseBlockTargetNonIpString() {
		$userObject = $this->createMock( User::class );
		yield 'Name returns a valid user' => [
			'DannyS712',
			'DannyS712',
			$userObject,
			$userObject,
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
			'SomeInvalidUserName/WithASubpage',
			'SomeInvalidUserName',
			null,
			null,
			null
		];
	}

}
