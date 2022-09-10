<?php

namespace MediaWiki\Tests\User\TempUser;

use MediaWiki\MainConfigNames;

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

	public static function provideIsReservedName() {
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
}
