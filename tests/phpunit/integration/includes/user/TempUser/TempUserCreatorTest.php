<?php

namespace MediaWiki\Tests\User\TempUser;

use ExtensionRegistry;
use MediaWiki\Auth\AuthManager;
use MediaWiki\MainConfigNames;
use MediaWiki\Session\Session;
use MediaWiki\User\TempUser\RealTempUserConfig;
use MediaWiki\User\TempUser\SerialMapping;
use MediaWiki\User\TempUser\SerialProvider;
use MediaWiki\User\TempUser\TempUserCreator;
use MediaWiki\User\UserFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 * @covers \MediaWiki\User\TempUser\DBSerialProvider
 * @covers \MediaWiki\User\TempUser\LocalSerialProvider
 * @covers \MediaWiki\User\TempUser\TempUserCreator
 * @covers \MediaWiki\User\TempUser\CreateStatus
 */
class TempUserCreatorTest extends \MediaWikiIntegrationTestCase {
	/** This is meant to be the default config from MainConfigSchema */
	private const DEFAULTS = [
		'enabled' => false,
		'actions' => [ 'edit' ],
		'genPattern' => '*Unregistered $1',
		'matchPattern' => '*$1',
		'serialProvider' => [ 'type' => 'local' ],
		'serialMapping' => [ 'type' => 'plain-numeric' ]
	];

	public function testCreate() {
		$this->tablesUsed[] = 'user';
		$this->tablesUsed[] = 'user_autocreate_serial';

		$this->overrideConfigValue(
			MainConfigNames::AutoCreateTempUser,
			[
				'enabled' => true,
				'actions' => [ 'edit' ],
				'genPattern' => '*Unregistered $1',
				'matchPattern' => '*$1',
				'serialProvider' => [ 'type' => 'local' ],
				'serialMapping' => [ 'type' => 'plain-numeric' ]
			]
		);
		$tuc = $this->getServiceContainer()->getTempUserCreator();
		$this->assertTrue( $tuc->isAutoCreateAction( 'edit' ) );
		$this->assertTrue( $tuc->isReservedName( '*Unregistered 1' ) );
		$status = $tuc->create();
		$this->assertSame( '*Unregistered 1', $status->getUser()->getName() );
		$status = $tuc->create();
		$this->assertSame( '*Unregistered 2', $status->getUser()->getName() );
	}

	private function getTempUserCreatorUnit() {
		$scope1 = ExtensionRegistry::getInstance()->setAttributeForTest(
			'TempUserSerialProviders',
			[
				'test' => [
					'factory' => static function () {
						return new class implements SerialProvider {
							public function acquireIndex(): int {
								return 1;
							}
						};
					}
				],
			]
		);
		$scope2 = ExtensionRegistry::getInstance()->setAttributeForTest(
			'TempUserSerialMappings',
			[
				'test' => [
					'factory' => static function () {
						return new class implements SerialMapping {
							public function getSerialIdForIndex( int $index ): string {
								$index--;
								$adjective = (int)( $index / 2 );
								$animal = $index % 2;
								return [ 'active' ][$adjective] . ' ' . [ 'aardvark' ][$animal];
							}
						};
					}
				]
			]
		);
		$config = new RealTempUserConfig(
			[
				'enabled' => true,
				'serialProvider' => [ 'type' => 'test' ],
				'serialMapping' => [ 'type' => 'test' ]
			] + self::DEFAULTS
		);
		$creator = new TempUserCreator(
			$config,
			$this->createSimpleObjectFactory(),
			$this->createMock( UserFactory::class ),
			$this->createMock( AuthManager::class ),
			null
		);
		return [ $creator, [ $scope1, $scope2 ] ];
	}

	public function testAcquireName_unit() {
		[ $creator, $scope ] = $this->getTempUserCreatorUnit();
		/** @var TempUserCreator $creator */
		$creator = TestingAccessWrapper::newFromObject( $creator );
		$this->assertSame( '*Unregistered active aardvark', $creator->acquireName() );
	}

	public function testAcquireName_db() {
		$this->tablesUsed[] = 'user_autocreate_serial';
		$this->overrideConfigValue(
			MainConfigNames::AutoCreateTempUser,
			[ 'enabled' => true ] + self::DEFAULTS
		);
		$tuc = TestingAccessWrapper::newFromObject(
			$this->getServiceContainer()->getTempUserCreator()
		);
		$this->assertSame( '*Unregistered 1', $tuc->acquireName() );
		$this->assertSame( '*Unregistered 2', $tuc->acquireName() );
	}

	public function testAcquireAndStashName() {
		/** @var TempUserCreator $creator */
		[ $creator, $scope ] = $this->getTempUserCreatorUnit();

		$session = new class extends Session {
			private $data = [];

			public function __construct() {
			}

			public function __destruct() {
			}

			public function get( $key, $default = null ) {
				return array_key_exists( $key, $this->data ) ? $this->data[$key] : $default;
			}

			public function set( $key, $value ) {
				$this->data[$key] = $value;
			}

			public function save() {
			}
		};

		$name = $creator->acquireAndStashName( $session );
		$this->assertSame( '*Unregistered active aardvark', $name );
		$name = $creator->acquireAndStashName( $session );
		$this->assertSame( '*Unregistered active aardvark', $name );
	}
}
