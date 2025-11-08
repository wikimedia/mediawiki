<?php

namespace MediaWiki\Tests\Integration\User\TempUser;

use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\Throttler;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Request\FauxRequest;
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
		$this->enableAutoCreateTempUser( [
			'serialProvider' => [ 'type' => 'local', 'useYear' => false ],
			'matchPattern' => '~$1',
		] );

		// Disable account creation rate limiting for this test, as it tests something else
		$this->overrideConfigValues( [
			MainConfigNames::TempAccountCreationThrottle => [],
		] );

		$tuc = $this->getServiceContainer()->getTempUserCreator();
		$this->assertTrue( $tuc->isAutoCreateAction( 'edit' ) );
		$this->assertTrue( $tuc->isTempName( '~1' ) );

		// Create a temporary account
		$status = $tuc->create( null, new FauxRequest() );
		$this->assertSame( '~1', $status->getUser()->getName() );
		$this->assertSame(
			1,
			$this->getDb()->newSelectQueryBuilder()
				->from( 'logging' )
				->join( 'actor', null, 'log_actor=actor_id' )
				->where( [ 'actor_name' => '~1', 'log_action' => 'autocreate' ] )
				->fetchRowCount(),
			'A logging entry indicating the autocreation of ~1 was expected.'
		);

		// Repeat the test to verify that the serial number increments
		$status = $tuc->create( null, new FauxRequest() );
		$this->assertSame( '~2', $status->getUser()->getName() );
		$this->assertSame(
			1,
			$this->getDb()->newSelectQueryBuilder()
				->from( 'logging' )
				->join( 'actor', null, 'log_actor=actor_id' )
				->where( [ 'actor_name' => '~2', 'log_action' => 'autocreate' ] )
				->fetchRowCount(),
			'A logging entry indicating the autocreation of ~2 was expected.'
		);
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
			$this->createMock( Throttler::class ),
			$this->createMock( Throttler::class )
		);
		return [ $creator, [ $scope1, $scope2 ] ];
	}

	public function testAcquireName_unit() {
		[ $creator, $scope ] = $this->getTempUserCreatorUnit();
		/** @var TempUserCreator $creator */
		$creator = TestingAccessWrapper::newFromObject( $creator );
		$this->assertSame(
			'*Unregistered active aardvark',
			$creator->acquireName( '127.0.0.1' )
		);
	}

	public function testAcquireName_db() {
		$this->enableAutoCreateTempUser( [
			'serialProvider' => [ 'type' => 'local', 'useYear' => false ],
			'matchPattern' => '~$1',
		] );
		$tuc = TestingAccessWrapper::newFromObject(
			$this->getServiceContainer()->getTempUserCreator()
		);
		$this->assertSame( '~1', $tuc->acquireName( '127.0.0.1' ) );
		$this->assertSame( '~2', $tuc->acquireName( '127.0.0.1' ) );
	}

	public function testAcquireName_dbWithYear() {
		$this->enableAutoCreateTempUser( [ 'serialProvider' => [ 'type' => 'local', 'useYear' => true ] ] );

		ConvertibleTimestamp::setFakeTime( '20000101000000' );
		$tuc = TestingAccessWrapper::newFromObject(
			$this->getServiceContainer()->getTempUserCreator()
		);
		$this->assertSame( '~2000-1', $tuc->acquireName( '127.0.0.1' ) );
		$this->assertSame( '~2000-2', $tuc->acquireName( '127.0.0.1' ) );

		ConvertibleTimestamp::setFakeTime( '20010101000000' );
		$this->assertSame( '~2001-1', $tuc->acquireName( '127.0.0.1' ) );
	}

	public function testAcquireNameOnDuplicate_db() {
		ConvertibleTimestamp::setFakeTime( '20000101000000' );
		$this->enableAutoCreateTempUser();
		$tuc = TestingAccessWrapper::newFromObject(
			$this->getServiceContainer()->getTempUserCreator()
		);
		// Create a temporary account
		$this->assertSame( '~2000-1', $tuc->create( null, new FauxRequest() )->value->getName() );
		// Reset the user_autocreate_serial table
		$this->truncateTable( 'user_autocreate_serial' );
		// Because user_autocreate_serial was truncated, the ::acquireName method should
		// return null as the code attempts to return a temporary account that already exists.
		$this->assertSame( null, $tuc->acquireName( '127.0.0.1' ) );
	}

	public function testCreateOnDuplicate_db() {
		// Disable account creation rate limiting for this test, as it tests something else
		$this->overrideConfigValues( [
			MainConfigNames::TempAccountCreationThrottle => [],
		] );

		ConvertibleTimestamp::setFakeTime( '20000101000000' );
		$this->enableAutoCreateTempUser();
		$tuc = $this->getServiceContainer()->getTempUserCreator();
		// Create a temporary account
		$this->assertSame( '~2000-1', $tuc->create( null, new FauxRequest() )->value->getName() );
		// Create a temporary account with an existing temporary account username.
		$secondCreateStatus = $tuc->create( '~2000-1', new FauxRequest() );
		$this->assertStatusError( 'temp-user-unable-to-acquire', $secondCreateStatus );
		// Assert that only one log entry for autocreation exists for ~2000-1, as the second call should have not
		// created a new log entry.
		$this->assertSame(
			1,
			$this->getDb()->newSelectQueryBuilder()
				->from( 'logging' )
				->join( 'actor', null, 'log_actor=actor_id' )
				->where( [ 'actor_name' => '~2000-1', 'log_action' => 'autocreate' ] )
				->fetchRowCount(),
			'Only one logging entry indicating the autocreation of ~2000-1 was expected.'
		);
	}

	public function testCreateOnInvalidUsername() {
		$this->enableAutoCreateTempUser();
		$tuc = $this->getServiceContainer()->getTempUserCreator();
		// Attempt to create the temporary account with an invalid username.
		$secondCreateStatus = $tuc->create( 'Template:InvalidUsername#test', new FauxRequest() );
		$this->assertStatusError( 'internalerror_info', $secondCreateStatus );
	}

	public function testAcquireNameThrottled() {
		ConvertibleTimestamp::setFakeTime( '20000101000000' );
		$this->enableAutoCreateTempUser();
		$this->overrideConfigValue(
			MainConfigNames::TempAccountNameAcquisitionThrottle,
			[
				'count' => 1,
				'seconds' => 30 * 86400,
			]
		);
		$tuc = TestingAccessWrapper::newFromObject(
			$this->getServiceContainer()->getTempUserCreator()
		);
		// Create a temporary account
		$this->assertSame( '~2000-1', $tuc->create( null, new FauxRequest() )->value->getName() );
		// Attempt again; name acquisition should be limited
		$this->assertStatusError( 'temp-user-unable-to-acquire', $tuc->create( null, new FauxRequest() ) );
	}

	public function testAcquireAndStashName() {
		/** @var TempUserCreator $creator */
		[ $creator, $scope ] = $this->getTempUserCreatorUnit();

		$session = new class extends Session {
			/** @var array */
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

			public function getRequest() {
				return new FauxRequest();
			}
		};

		$name = $creator->acquireAndStashName( $session );
		$this->assertSame( '*Unregistered active aardvark', $name );
		$name = $creator->acquireAndStashName( $session );
		$this->assertSame( '*Unregistered active aardvark', $name );
	}

	public function testRateLimit() {
		$this->enableAutoCreateTempUser( [
			'serialProvider' => [ 'type' => 'local', 'useYear' => false ],
			'matchPattern' => '~$1',
		] );

		$this->overrideConfigValues( [
			MainConfigNames::AccountCreationThrottle => [
				'count' => 10,
				'seconds' => 86400
			],
			MainConfigNames::TempAccountCreationThrottle => [
				[
					'count' => 1,
					'seconds' => 600,
				],
				[
					'count' => 6,
					'seconds' => 86400,
				],
			],
		] );
		$tuc = $this->getServiceContainer()->getTempUserCreator();
		$status = $tuc->create( null, new FauxRequest() );
		$this->assertSame( '~1', $status->getUser()->getName() );

		// Repeat creating a temporary account, and verify that this fails due to the rate limit.
		$status = $tuc->create( null, new FauxRequest() );
		// TODO: Use new message key (T357777, T357802)
		$this->assertStatusError( 'acct_creation_throttle_hit', $status );
		// If the temporary account creation failed due to the rate limit, then no log entry should have been created.
		$this->assertSame(
			0,
			$this->getDb()->newSelectQueryBuilder()
				->from( 'logging' )
				->join( 'actor', null, 'log_actor=actor_id' )
				->where( [ 'actor_name' => '~2', 'log_action' => 'autocreate' ] )
				->fetchRowCount(),
			'A logging entry indicating the autocreation of ~2 was not expected.'
		);
	}

	public function testRateLimitIPv6() {
		$this->enableAutoCreateTempUser( [
			'serialProvider' => [ 'type' => 'local', 'useYear' => false ],
			'matchPattern' => '~$1',
		] );

		$this->overrideConfigValues( [
			MainConfigNames::AccountCreationThrottle => [
				'count' => 10,
				'seconds' => 86400
			],
			MainConfigNames::TempAccountCreationThrottle => [
				[
					'count' => 1,
					'seconds' => 600,
				],
				[
					'count' => 6,
					'seconds' => 86400,
				],
			],
		] );

		$tuc = $this->getServiceContainer()->getTempUserCreator();
		$this->assertTrue( $tuc->isAutoCreateAction( 'edit' ) );
		$this->assertTrue( $tuc->isTempName( '~1' ) );

		// Create a temporary account with an IPv6 IP
		RequestContext::getMain()->getRequest()->setIP( '1:1:1:1:1:1:1:1' );
		$status = $tuc->create( null, RequestContext::getMain()->getRequest() );
		$this->assertSame( '~1', $status->getUser()->getName() );

		// Create a temporary account on a different IPv6 IP range and assert it succeeds
		RequestContext::getMain()->getRequest()->setIP( '2:2:2:2:2:2:2:2' );
		$status = $tuc->create( null, RequestContext::getMain()->getRequest() );
		$this->assertSame( '~2', $status->getUser()->getName() );

		// Attempt to create another temporary account on an IP in the same /64 range as one of the
		// already created temporary accounts and assert that it fails
		RequestContext::getMain()->getRequest()->setIP( '1:1:1:1:1:1:1:2' );
		$status = $tuc->create( null, RequestContext::getMain()->getRequest() );
		$this->assertStatusError( 'acct_creation_throttle_hit', $status );
		// If the temporary account creation failed due to the rate limit, then no log entry should have been created.
		$this->assertSame(
			0,
			$this->getDb()->newSelectQueryBuilder()
				->from( 'logging' )
				->join( 'actor', null, 'log_actor=actor_id' )
				->where( [ 'actor_name' => '~3', 'log_action' => 'autocreate' ] )
				->fetchRowCount(),
			'A logging entry indicating the autocreation of ~3 was not expected.'
		);
	}
}
