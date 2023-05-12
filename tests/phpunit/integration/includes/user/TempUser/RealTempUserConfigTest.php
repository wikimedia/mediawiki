<?php

namespace MediaWiki\Tests\User\TempUser;

use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \MediaWiki\User\TempUser\RealTempUserConfig
 * @group Database
 */
class RealTempUserConfigTest extends \MediaWikiIntegrationTestCase {
	/** This is meant to be the default config from MainConfigSchema */
	private const DEFAULTS = [
		'enabled' => false,
		'actions' => [ 'edit' ],
		'genPattern' => '*Unregistered $1',
		'matchPattern' => '*$1',
		'serialProvider' => [ 'type' => 'local' ],
		'serialMapping' => [ 'type' => 'plain-numeric' ]
	];

	public static function provideIsAutoCreateAction() {
		return [
			'disabled' => [
				'config' => [
					'enabled' => false
				] + self::DEFAULTS,
				'action' => 'edit',
				'expected' => false
			],
			'disabled by action' => [
				'config' => [
					'enabled' => true,
					'actions' => []
				] + self::DEFAULTS,
				'action' => 'edit',
				'expected' => false
			],
			'enabled' => [
				'config' => [
					'enabled' => true,
				] + self::DEFAULTS,
				'action' => 'edit',
				'expected' => true
			],
			// Create isn't an action in the ActionFactory sense, but is is an
			// action in PermissionManager
			'create' => [
				'config' => [
						'enabled' => true,
					] + self::DEFAULTS,
				'action' => 'create',
				'expected' => true
			],
			'unknown action' => [
				'config' => [
						'enabled' => true,
					] + self::DEFAULTS,
				'action' => 'foo',
				'expected' => false
			],
		];
	}

	/**
	 * @dataProvider provideIsAutoCreateAction
	 * @param array $config
	 * @param string $action
	 * @param bool $expected
	 */
	public function testIsAutoCreateAction( $config, $action, $expected ) {
		$this->overrideConfigValue( MainConfigNames::AutoCreateTempUser, $config );
		$tuc = $this->getServiceContainer()->getTempUserConfig();
		$this->assertSame( $expected, $tuc->isAutoCreateAction( $action ) );
	}

	public static function provideShouldAutoCreate() {
		return [
			'enabled' => [
				'config' => [
						'enabled' => true
					] + self::DEFAULTS,
				'id' => 0,
				'rights' => [ 'createaccount' ],
				'action' => 'edit',
				'expected' => true
			],
			'disabled by config' => [
				'config' => self::DEFAULTS,
				'id' => 0,
				'rights' => [ 'createaccount' ],
				'action' => 'edit',
				'expected' => false
			],
			'logged in' => [
				'config' => [
						'enabled' => true
					] + self::DEFAULTS,
				'id' => 1,
				'rights' => [ 'createaccount' ],
				'action' => 'edit',
				'expected' => false
			],
			'no createaccount right' => [
				'config' => [
						'enabled' => true
					] + self::DEFAULTS,
				'id' => 0,
				'rights' => [ 'edit' ],
				'action' => 'edit',
				'expected' => false
			],
			'wrong action' => [
				'config' => [
						'enabled' => true
					] + self::DEFAULTS,
				'id' => 0,
				'rights' => [ 'createaccount' ],
				'action' => 'upload',
				'expected' => false
			],
		];
	}

	/**
	 * @dataProvider provideShouldAutoCreate
	 * @param array $config
	 * @param int $id
	 * @param string[] $rights
	 * @param string $action
	 * @param bool $expected
	 */
	public function testShouldAutoCreate( $config, $id, $rights, $action, $expected ) {
		$this->overrideConfigValue( MainConfigNames::AutoCreateTempUser, $config );
		$tuc = $this->getServiceContainer()->getTempUserConfig();
		$user = new SimpleAuthority(
			new UserIdentityValue( $id, $id ? 'Test' : '127.0.0.1' ),
			$rights
		);
		$this->assertSame( $expected, $tuc->shouldAutoCreate( $user, $action ) );
	}

	public static function provideIsTempName() {
		$defaults = [
			'enabled' => true
		] + self::DEFAULTS;
		return [
			'disabled' => [
				'config' => [
					'enabled' => false
				] + $defaults,
				'name' => '*Some user',
				'expected' => false,
			],
			'default mismatch' => [
				'config' => $defaults,
				'name' => 'Test',
				'expected' => false,
			],
			'default match' => [
				'config' => $defaults,
				'name' => '*Some user',
				'expected' => true,
			]
		];
	}

	/**
	 * @dataProvider provideIsTempName
	 * @param array $config
	 * @param string $name
	 * @param bool $expected
	 */
	public function testIsTempName( $config, $name, $expected ) {
		$this->overrideConfigValue( MainConfigNames::AutoCreateTempUser, $config );
		$tuc = $this->getServiceContainer()->getTempUserConfig();
		$this->assertSame( $expected, $tuc->isTempName( $name ) );
	}

	private function getTempUserConfig() {
		$this->overrideConfigValue(
			MainConfigNames::AutoCreateTempUser,
			[ 'enabled' => true ] + self::DEFAULTS
		);
		return $this->getServiceContainer()->getTempUserConfig();
	}

	public function testGetPlaceholderName() {
		$this->assertSame(
			'*Unregistered *',
			$this->getTempUserConfig()->getPlaceholderName()
		);
	}

	public static function provideIsReservedName() {
		return [
			'no matchPattern when disabled' => [
				'config' => self::DEFAULTS,
				'name' => '*Unregistered 39',
				'expected' => false,
			],
			'matchPattern match' => [
				'config' => [ 'enabled' => true ] + self::DEFAULTS,
				'name' => '*Unregistered 39',
				'expected' => true,
			],
			'genPattern match' => [
				'config' => [ 'enabled' => true, 'matchPattern' => null ] + self::DEFAULTS,
				'name' => '*Unregistered 39',
				'expected' => true,
			],
			'reservedPattern match with enabled=false' => [
				'config' => [ 'reservedPattern' => '*$1' ] + self::DEFAULTS,
				'name' => '*Foo*',
				'expected' => true
			]
		];
	}

	/**
	 * @dataProvider provideIsReservedName
	 * @param array $config
	 * @param string $name
	 * @param bool $expected
	 */
	public function testIsReservedName( $config, $name, $expected ) {
		$this->overrideConfigValue( MainConfigNames::AutoCreateTempUser, $config );
		$tuc = $this->getServiceContainer()->getTempUserConfig();
		$this->assertSame( $expected, $tuc->isReservedName( $name ) );
	}
}
