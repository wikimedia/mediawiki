<?php

namespace MediaWiki\Tests\Integration\User\TempUser;

use ExtensionRegistry;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Session\Session;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\TempUser\RealTempUserConfig;
use MediaWiki\User\TempUser\SerialMapping;
use MediaWiki\User\TempUser\SerialProvider;
use MediaWiki\User\TempUser\TempUserCreator;
use MediaWiki\User\UserFactory;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group Database
 * @covers \MediaWiki\User\TempUser\DBSerialProvider
 * @covers \MediaWiki\User\TempUser\LocalSerialProvider
 * @covers \MediaWiki\User\TempUser\TempUserCreator
 * @covers \MediaWiki\User\TempUser\CreateStatus
 */
class TempUserCreatorTest extends \MediaWikiIntegrationTestCase {

	use TempUserTestTrait;

	public function testCreate() {
		$this->enableAutoCreateTempUser();
		$tuc = $this->getServiceContainer()->getTempUserCreator();
		$this->assertTrue( $tuc->isAutoCreateAction( 'edit' ) );
		$this->assertTrue( $tuc->isTempName( '*Unregistered 1' ) );
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
							public function acquireIndex( int $year = 0 ): int {
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
		$config = new RealTempUserConfig( [
			'enabled' => true,
			'expireAfterDays' => null,
			'actions' => [ 'edit' ],
			'genPattern' => '*Unregistered $1',
			'matchPattern' => '*$1',
			'serialProvider' => [ 'type' => 'test' ],
			'serialMapping' => [ 'type' => 'test' ],
		] );
		$creator = new TempUserCreator(
			$config,
			$this->createSimpleObjectFactory(),
			$this->createMock( UserFactory::class ),
			$this->createMock( AuthManager::class ),
			$this->createMock( CentralIdLookup::class ),
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
		$this->enableAutoCreateTempUser();
		$tuc = TestingAccessWrapper::newFromObject(
			$this->getServiceContainer()->getTempUserCreator()
		);
		$this->assertSame( '*Unregistered 1', $tuc->acquireName() );
		$this->assertSame( '*Unregistered 2', $tuc->acquireName() );
	}

	public function testAcquireName_dbWithYear() {
		$this->enableAutoCreateTempUser( [ 'serialProvider' => [ 'type' => 'local', 'useYear' => true ] ] );

		ConvertibleTimestamp::setFakeTime( '20000101000000' );
		$tuc = TestingAccessWrapper::newFromObject(
			$this->getServiceContainer()->getTempUserCreator()
		);
		$this->assertSame( '*Unregistered 2000-1', $tuc->acquireName() );
		$this->assertSame( '*Unregistered 2000-2', $tuc->acquireName() );

		ConvertibleTimestamp::setFakeTime( '20010101000000' );
		$this->assertSame( '*Unregistered 2001-1', $tuc->acquireName() );
	}

	public function testAcquireNameOnDuplicate_db() {
		$this->enableAutoCreateTempUser();
		$tuc = TestingAccessWrapper::newFromObject(
			$this->getServiceContainer()->getTempUserCreator()
		);
		// Create a temporary account
		$this->assertSame( '*Unregistered 1', $tuc->create()->value->getName() );
		// Reset the user_autocreate_serial table
		$this->truncateTable( 'user_autocreate_serial' );
		// Because user_autocreate_serial was truncated, the ::acquireName method should
		// return null as the code attempts to return a temporary account that already exists.
		$this->assertSame( null, $tuc->acquireName() );
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
