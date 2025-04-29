<?php

use MediaWiki\Block\AnonIpBlockTarget;
use MediaWiki\Block\AutoBlockTarget;
use MediaWiki\Block\BlockTargetFactory;
use MediaWiki\Block\RangeBlockTarget;
use MediaWiki\Block\UserBlockTarget;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserSelectQueryBuilder;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @covers \MediaWiki\Block\BlockTargetFactory
 */
class BlockTargetFactoryTest extends \MediaWikiIntegrationTestCase {
	private function getBlockTargetFactory( $wikiId = false ) {
		return new BlockTargetFactory(
			new ServiceOptions(
				BlockTargetFactory::CONSTRUCTOR_OPTIONS,
				[
					MainConfigNames::BlockCIDRLimit => [
						'IPv4' => 16,
						'IPv6' => 19,
					],
				]
			),
			$this->getMockUserIdentityLookup(),
			$this->getServiceContainer()->getUserNameUtils(),
			$wikiId
		);
	}

	private function getMockUserIdentityLookup() {
		return new class implements UserIdentityLookup {
			public function getUserIdentityByName(
				string $name, int $queryFlags = IDBAccessObject::READ_NORMAL
			): ?UserIdentity {
				if ( $name === 'Exists' ) {
					return new UserIdentityValue( 1, $name );
				} else {
					return null;
				}
			}

			public function getUserIdentityByUserId(
				int $userId, int $queryFlags = IDBAccessObject::READ_NORMAL
			): ?UserIdentity {
				if ( $userId === 1 ) {
					return new UserIdentityValue( 1, 'Exists' );
				} else {
					return null;
				}
			}

			public function newSelectQueryBuilder( $dbOrQueryFlags = IDBAccessObject::READ_NORMAL
			): UserSelectQueryBuilder {
				throw new RuntimeException( 'unimplemented' );
			}
		};
	}

	public function testGetWikiId() {
		$this->assertSame( 'enwiki',
			$this->getBlockTargetFactory( 'enwiki' )->getWikiId() );
	}

	public static function provideNewFromString() {
		return [
			[ '1.2.3.4', AnonIpBlockTarget::class ],
			[ '::1', AnonIpBlockTarget::class, '0:0:0:0:0:0:0:1' ],
			[ ' 1.2.3.4', AnonIpBlockTarget::class, '1.2.3.4' ],
			[ '1.2.3.0/24', RangeBlockTarget::class ],
			[ '::1/64', RangeBlockTarget::class, '0:0:0:0:0:0:0:0/64' ],
			[ 'Exists', UserBlockTarget::class ],
			[ 'Nonexistent', UserBlockTarget::class ],
			[ '#1234', AutoBlockTarget::class ],
			[ '__', null ],
			[ '', null ],
			[ null, null ]
		];
	}

	/**
	 * @dataProvider provideNewFromString
	 * @param string $input
	 * @param string $class
	 * @param string|null $serialized
	 */
	public function testNewFromString( $input, $class, $serialized = null ) {
		$target = $this->getBlockTargetFactory()->newFromString( $input );
		if ( $class === null ) {
			$this->assertNull( $target );
			return;
		}
		$this->assertInstanceOf( $class, $target );
		// Confirm round trip
		$this->assertSame( $serialized ?? $input, $target->toString() );
	}

	/**
	 * @covers \MediaWiki\Block\UserBlockTarget::validateForCreation
	 */
	public function testNoSuchUser() {
		$target = $this->getBlockTargetFactory()->newFromString( 'Nonexistent' );
		$status = $target->validateForCreation();
		$this->assertStatusError( 'nosuchusershort', $status );
		$this->assertSame( '<text>Nonexistent</text>',
			$status->getMessages()[0]->getParams()[0]->dump() );
	}

	public static function provideNewFromIp() {
		return [
			[ '', null ],
			[ '127.0.0.1', '127.0.0.1' ],
			[ '::0', '0:0:0:0:0:0:0:0' ],
			[ '127.0.0.001', '127.0.0.1' ],
		];
	}

	/**
	 * @dataProvider provideNewFromIp
	 * @param string $input
	 * @param string|null $expected
	 */
	public function testNewFromIp( $input, $expected ) {
		$result = $this->getBlockTargetFactory()->newFromIp( $input );
		if ( $expected === null ) {
			$this->assertNull( $result );
		} else {
			$this->assertInstanceOf( AnonIpBlockTarget::class, $result );
			$this->assertSame( $expected, $result->toString() );
		}
	}

	public static function provideNewFromUser() {
		return [
			[ 1, 'Alice', 'Alice', UserBlockTarget::class ],
			[ 5, 'Exists', 'Exists', UserBlockTarget::class ],
			[ 0, 'Nonexistent', 'Nonexistent', UserBlockTarget::class ],
			[ 0, '127.0.0.1', '127.0.0.1', AnonIpBlockTarget::class ],
			[ 0, '127.0.0.001', '127.0.0.1', AnonIpBlockTarget::class ],
			[ 0, '1.2.3.0/24', '1.2.3.0/24', RangeBlockTarget::class ],
		];
	}

	/**
	 * @dataProvider provideNewFromUser
	 * @param int $id
	 * @param string $name
	 * @param string|null $expectedName
	 * @param string $class
	 */
	public function testNewFromUser( $id, $name, $expectedName, $class ) {
		$user = new UserIdentityValue( $id, $name );
		$target = $this->getBlockTargetFactory()->newFromUser( $user );
		$this->assertInstanceOf( $class, $target );
		$this->assertSame( $expectedName, $target->toString() );
	}

	public static function provideNewFromRow() {
		$default = [
			'bt_auto' => '0',
			'bt_address' => '127.0.0.1',
			'bt_user' => null,
			'bt_user_text' => null,
			'bl_id' => 5,
		];
		return [
			'IP block' => [
				$default,
				false,
				AnonIpBlockTarget::class,
				'127.0.0.1',
			],
			'Autoblock exposed' => [
				[ 'bt_auto' => '1' ] + $default,
				false,
				AnonIpBlockTarget::class,
				'127.0.0.1',
			],
			'Autoblock redacted' => [
				[ 'bt_auto' => '1' ] + $default,
				true,
				AutoBlockTarget::class,
				'#5',
			],
			'Range block' => [
				[ 'bt_address' => '127.0.0.0/24' ] + $default,
				false,
				RangeBlockTarget::class,
				'127.0.0.0/24',
			],
			'IPv6 range block' => [
				[ 'bt_address' => '2001:0:0:0:0:0:0:0/19' ] + $default,
				false,
				RangeBlockTarget::class,
				'2001:0:0:0:0:0:0:0/19',
			],
			'User block' => [
				[ 'bt_user' => 2, 'bt_user_text' => 'Bob' ] + $default,
				false,
				UserBlockTarget::class,
				'Bob'
			],
			'Error case 1' => [
				[ 'bt_address' => null ] + $default,
				false,
				null,
				''
			],
			'Error case 2' => [
				[ 'bt_address' => 'Some invalid string' ] + $default,
				false,
				null,
				''
			],
		];
	}

	/**
	 * @dataProvider provideNewFromRow
	 * @param array $data
	 * @param bool $redact
	 * @param string|null $class
	 * @param string $serialized
	 */
	public function testNewFromRow( $data, $redact, $class, $serialized ) {
		$factory = $this->getBlockTargetFactory();
		$target = $redact ? $factory->newFromRowRedacted( (object)$data )
			: $factory->newFromRowRaw( (object)$data );
		if ( $class === null ) {
			$this->assertNull( $target );
			return;
		}
		$this->assertInstanceOf( $class, $target );
		$this->assertSame( $serialized, $target->toString() );
	}
}
