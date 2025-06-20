<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\MultiConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Password\InvalidPassword;
use MediaWiki\Password\Password;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Session\SessionManager;
use MediaWiki\Status\Status;
use MediaWiki\User\BotPassword;
use MediaWiki\User\CentralId\CentralIdLookup;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\User\BotPassword
 * @group Database
 */
class BotPasswordTest extends MediaWikiIntegrationTestCase {

	private TestUser $testUser;

	/** @var string */
	private $testUserName;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::EnableBotPasswords => true,
			MainConfigNames::CentralIdLookupProvider => 'BotPasswordTest OkMock',
			MainConfigNames::GrantPermissions => [
				'test' => [ 'read' => true ],
			],
			MainConfigNames::UserrightsInterwikiDelimiter => '@',
		] );

		$this->testUser = $this->getMutableTestUser();
		$this->testUserName = $this->testUser->getUser()->getName();

		$mock1 = $this->getMockForAbstractClass( CentralIdLookup::class );
		$mock1->method( 'isAttached' )
			->willReturn( true );
		$mock1->method( 'lookupUserNamesWithFilter' )
			->willReturn( [ $this->testUserName => 42, 'UTDummy' => 43, 'UTInvalid' => 0 ] );
		$mock1->expects( $this->never() )->method( 'lookupCentralIds' );

		$mock2 = $this->getMockForAbstractClass( CentralIdLookup::class );
		$mock2->method( 'isAttached' )
			->willReturn( false );
		$mock2->method( 'lookupUserNamesWithFilter' )
			->willReturnArgument( 0 );
		$mock2->expects( $this->never() )->method( 'lookupCentralIds' );

		$this->mergeMwGlobalArrayValue( 'wgCentralIdLookupProviders', [
			'BotPasswordTest OkMock' => [ 'factory' => static function () use ( $mock1 ) {
				return $mock1;
			} ],
			'BotPasswordTest FailMock' => [ 'factory' => static function () use ( $mock2 ) {
				return $mock2;
			} ],
		] );
	}

	public function addDBData() {
		$passwordFactory = $this->getServiceContainer()->getPasswordFactory();
		$passwordHash = $passwordFactory->newFromPlaintext( 'foobaz' );

		$dbw = $this->getDb();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'bot_passwords' )
			->where( [ 'bp_user' => [ 42, 43 ], 'bp_app_id' => 'BotPassword' ] )
			->caller( __METHOD__ )->execute();
		$dbw->newInsertQueryBuilder()
			->insertInto( 'bot_passwords' )
			->row( [
				'bp_user' => 42,
				'bp_app_id' => 'BotPassword',
				'bp_password' => $passwordHash->toString(),
				'bp_token' => 'token!',
				'bp_restrictions' => '{"IPAddresses":["127.0.0.0/8"]}',
				'bp_grants' => '["test"]',
			] )
			->row( [
				'bp_user' => 43,
				'bp_app_id' => 'BotPassword',
				'bp_password' => $passwordHash->toString(),
				'bp_token' => 'token!',
				'bp_restrictions' => '{"IPAddresses":["127.0.0.0/8"]}',
				'bp_grants' => '["test"]',
			] )
			->caller( __METHOD__ )
			->execute();
	}

	public function testBasics() {
		$user = $this->testUser->getUser();
		$bp = BotPassword::newFromUser( $user, 'BotPassword' );
		$this->assertInstanceOf( BotPassword::class, $bp );
		$this->assertTrue( $bp->isSaved() );
		$this->assertSame( 42, $bp->getUserCentralId() );
		$this->assertSame( 'BotPassword', $bp->getAppId() );
		$this->assertSame( 'token!', trim( $bp->getToken(), " \0" ) );
		$this->assertEquals( '{"IPAddresses":["127.0.0.0/8"]}', $bp->getRestrictions()->toJson() );
		$this->assertSame( [ 'test' ], $bp->getGrants() );

		$this->assertNull( BotPassword::newFromUser( $user, 'DoesNotExist' ) );

		$this->overrideConfigValue( MainConfigNames::CentralIdLookupProvider, 'BotPasswordTest FailMock' );
		$this->assertNull( BotPassword::newFromUser( $user, 'BotPassword' ) );

		$this->assertSame( '@', BotPassword::getSeparator() );
		$this->overrideConfigValue( MainConfigNames::UserrightsInterwikiDelimiter, '#' );
		$this->assertSame( '#', BotPassword::getSeparator() );
	}

	public function testUnsaved() {
		$user = $this->testUser->getUser();
		$bp = BotPassword::newUnsaved( [
			'user' => $user,
			'appId' => 'DoesNotExist'
		] );
		$this->assertInstanceOf( BotPassword::class, $bp );
		$this->assertFalse( $bp->isSaved() );
		$this->assertSame( 42, $bp->getUserCentralId() );
		$this->assertSame( 'DoesNotExist', $bp->getAppId() );
		$this->assertEquals( MWRestrictions::newDefault(), $bp->getRestrictions() );
		$this->assertSame( [], $bp->getGrants() );

		$bp = BotPassword::newUnsaved( [
			'username' => 'UTDummy',
			'appId' => 'DoesNotExist2',
			'restrictions' => MWRestrictions::newFromJson( '{"IPAddresses":["127.0.0.0/8"]}' ),
			'grants' => [ 'test' ],
		] );
		$this->assertInstanceOf( BotPassword::class, $bp );
		$this->assertFalse( $bp->isSaved() );
		$this->assertSame( 43, $bp->getUserCentralId() );
		$this->assertSame( 'DoesNotExist2', $bp->getAppId() );
		$this->assertEquals( '{"IPAddresses":["127.0.0.0/8"]}', $bp->getRestrictions()->toJson() );
		$this->assertSame( [ 'test' ], $bp->getGrants() );

		$bp = BotPassword::newUnsaved( [
			'centralId' => 45,
			'appId' => 'DoesNotExist'
		] );
		$this->assertInstanceOf( BotPassword::class, $bp );
		$this->assertFalse( $bp->isSaved() );
		$this->assertSame( 45, $bp->getUserCentralId() );
		$this->assertSame( 'DoesNotExist', $bp->getAppId() );

		$user = $this->testUser->getUser();
		$bp = BotPassword::newUnsaved( [
			'user' => $user,
			'appId' => 'BotPassword'
		] );
		$this->assertInstanceOf( BotPassword::class, $bp );
		$this->assertFalse( $bp->isSaved() );

		$this->assertNull( BotPassword::newUnsaved( [
			'user' => $user,
			'appId' => '',
		] ) );
		$this->assertNull( BotPassword::newUnsaved( [
			'user' => $user,
			'appId' => str_repeat( 'X', BotPassword::APPID_MAXLENGTH + 1 ),
		] ) );
		$this->assertNull( BotPassword::newUnsaved( [
			'user' => $this->testUserName,
			'appId' => 'Ok',
		] ) );
		$this->assertNull( BotPassword::newUnsaved( [
			'username' => 'UTInvalid',
			'appId' => 'Ok',
		] ) );
		$this->assertNull( BotPassword::newUnsaved( [
			'appId' => 'Ok',
		] ) );
	}

	public function testGetPassword() {
		/** @var BotPassword $bp */
		$bp = TestingAccessWrapper::newFromObject( BotPassword::newFromCentralId( 42, 'BotPassword' ) );

		$password = $bp->getPassword();
		$this->assertInstanceOf( Password::class, $password );
		$this->assertTrue( $password->verify( 'foobaz' ) );

		$bp->centralId = 44;
		$password = $bp->getPassword();
		$this->assertInstanceOf( InvalidPassword::class, $password );

		$bp = TestingAccessWrapper::newFromObject( BotPassword::newFromCentralId( 42, 'BotPassword' ) );
		$dbw = $this->getDb();
		$dbw->newUpdateQueryBuilder()
			->update( 'bot_passwords' )
			->set( [ 'bp_password' => 'garbage' ] )
			->where( [ 'bp_user' => 42, 'bp_app_id' => 'BotPassword' ] )
			->caller( __METHOD__ )->execute();
		$password = $bp->getPassword();
		$this->assertInstanceOf( InvalidPassword::class, $password );
	}

	public function testInvalidateAllPasswordsForUser() {
		$bp1 = TestingAccessWrapper::newFromObject( BotPassword::newFromCentralId( 42, 'BotPassword' ) );
		$bp2 = TestingAccessWrapper::newFromObject( BotPassword::newFromCentralId( 43, 'BotPassword' ) );

		$this->assertNotInstanceOf( InvalidPassword::class, $bp1->getPassword() );
		$this->assertNotInstanceOf( InvalidPassword::class, $bp2->getPassword() );
		BotPassword::invalidateAllPasswordsForUser( $this->testUserName );
		$this->assertInstanceOf( InvalidPassword::class, $bp1->getPassword() );
		$this->assertNotInstanceOf( InvalidPassword::class, $bp2->getPassword() );

		$bp = TestingAccessWrapper::newFromObject( BotPassword::newFromCentralId( 42, 'BotPassword' ) );
		$this->assertInstanceOf( InvalidPassword::class, $bp->getPassword() );
	}

	public function testRemoveAllPasswordsForUser() {
		$this->assertNotNull( BotPassword::newFromCentralId( 42, 'BotPassword' ) );
		$this->assertNotNull( BotPassword::newFromCentralId( 43, 'BotPassword' ) );

		BotPassword::removeAllPasswordsForUser( $this->testUserName );

		$this->assertNull( BotPassword::newFromCentralId( 42, 'BotPassword' ) );
		$this->assertNotNull( BotPassword::newFromCentralId( 43, 'BotPassword' ) );
	}

	/**
	 * @dataProvider provideCanonicalizeLoginData
	 */
	public function testCanonicalizeLoginData( $username, $password, $expectedResult ) {
		$result = BotPassword::canonicalizeLoginData( $username, $password );
		if ( is_array( $expectedResult ) ) {
			$this->assertArrayEquals( $expectedResult, $result, true, true );
		} else {
			$this->assertSame( $expectedResult, $result );
		}
	}

	public static function provideCanonicalizeLoginData() {
		return [
			// T388255
			[ '', '', false ],
			[ 'user', '', false ],
			[ '', '12345678901234567890123456789012', false ],
			[ 'user', 'pass', false ],
			[ 'user', 'abc@def', false ],
			[ 'legacy@user', 'pass', false ],
			[ 'user@bot', '12345678901234567890123456789012',
				[ 'user@bot', '12345678901234567890123456789012' ] ],
			[ 'user', 'bot@12345678901234567890123456789012',
				[ 'user@bot', '12345678901234567890123456789012' ] ],
			[ 'user', 'bot@12345678901234567890123456789012345',
				[ 'user@bot', '12345678901234567890123456789012345' ] ],
			[ 'user', 'bot@x@12345678901234567890123456789012',
				[ 'user@bot@x', '12345678901234567890123456789012' ] ],
		];
	}

	public function testLoginFailure() {
		$this->overrideConfigValue( MainConfigNames::EnableBotPasswords, false );
		$status = BotPassword::login( "{$this->testUserName}@BotPassword", 'foobaz', new FauxRequest );
		$this->assertEquals( Status::newFatal( 'botpasswords-disabled' ), $status );
		$this->overrideConfigValue( MainConfigNames::EnableBotPasswords, true );

		$mainConfig = $this->getServiceContainer()->getMainConfig();

		// Test failure when BotPasswordSessionProvider isn't configured
		$manager = new SessionManager(
			$mainConfig,
			new Psr\Log\NullLogger,
			$this->getServiceContainer()->getHookContainer(),
			$this->getServiceContainer()->getObjectFactory(),
			$this->getServiceContainer()->getProxyLookup(),
			$this->getServiceContainer()->getUserNameUtils(),
			$this->getServiceContainer()->getSessionStore()
		);
		$this->setService( 'SessionManager', $manager );
		$this->assertNull(
			$manager->getProvider( MediaWiki\Session\BotPasswordSessionProvider::class )
		);
		$status = BotPassword::login( "{$this->testUserName}@BotPassword", 'foobaz', new FauxRequest );
		$this->assertEquals( Status::newFatal( 'botpasswords-no-provider' ), $status );
	}

	public function testLogin() {
		$mainConfig = $this->getServiceContainer()->getMainConfig();
		$config = new HashConfig( [
			MainConfigNames::SessionProviders => $mainConfig->get( MainConfigNames::SessionProviders ) + [
				MediaWiki\Session\BotPasswordSessionProvider::class => [
					'class' => MediaWiki\Session\BotPasswordSessionProvider::class,
					'args' => [ [ 'priority' => 40 ] ],
					'services' => [ 'GrantsInfo' ],
				]
			],
		] );

		$manager = new SessionManager(
			new MultiConfig( [ $config, $mainConfig ] ),
			new Psr\Log\NullLogger,
			$this->getServiceContainer()->getHookContainer(),
			$this->getServiceContainer()->getObjectFactory(),
			$this->getServiceContainer()->getProxyLookup(),
			$this->getServiceContainer()->getUserNameUtils(),
			$this->getServiceContainer()->getSessionStore()
		);
		$this->setService( 'SessionManager', $manager );

		// No "@"-thing in the username
		$status = BotPassword::login( $this->testUserName, 'foobaz', new FauxRequest );
		$this->assertStatusError( 'botpasswords-invalid-name', $status );

		// No base user
		$status = BotPassword::login( 'UTDummy@BotPassword', 'foobaz', new FauxRequest );
		$this->assertStatusError( 'nosuchuser', $status );

		// No bot password
		$status = BotPassword::login( "{$this->testUserName}@DoesNotExist", 'foobaz', new FauxRequest );
		$this->assertStatusError( 'botpasswords-not-exist', $status );

		// Failed restriction
		$request = new FauxRequest();
		$request->setIP( '10.0.0.1' );
		$status = BotPassword::login( "{$this->testUserName}@BotPassword", 'foobaz', $request );
		$this->assertStatusError( 'botpasswords-restriction-failed', $status );

		// Wrong password
		$status = BotPassword::login(
			"{$this->testUserName}@BotPassword", $this->testUser->getPassword(), new FauxRequest );
		$this->assertStatusError( 'wrongpassword', $status );

		// Success!
		$request = new FauxRequest;
		$this->assertNotInstanceOf(
			MediaWiki\Session\BotPasswordSessionProvider::class,
			$request->getSession()->getProvider()
		);
		$status = BotPassword::login( "{$this->testUserName}@BotPassword", 'foobaz', $request );
		$this->assertInstanceOf( Status::class, $status );
		$this->assertStatusGood( $status );
		$session = $status->getValue();
		$this->assertInstanceOf( MediaWiki\Session\Session::class, $session );
		$this->assertInstanceOf(
			MediaWiki\Session\BotPasswordSessionProvider::class, $session->getProvider()
		);
		$this->assertSame( $session->getId(), $request->getSession()->getId() );
	}

	/**
	 * @dataProvider provideSave
	 * @param string|null $password
	 */
	public function testSave( $password ) {
		$passwordFactory = $this->getServiceContainer()->getPasswordFactory();

		$bp = BotPassword::newUnsaved( [
			'centralId' => 42,
			'appId' => 'TestSave',
			'restrictions' => MWRestrictions::newFromJson( '{"IPAddresses":["127.0.0.0/8"]}' ),
			'grants' => [ 'test' ],
		] );
		$this->assertFalse( $bp->isSaved() );
		$this->assertNull(
			BotPassword::newFromCentralId( 42, 'TestSave', IDBAccessObject::READ_LATEST )
		);

		$passwordHash = $password ? $passwordFactory->newFromPlaintext( $password ) : null;
		$this->assertStatusNotOk( $bp->save( 'update', $passwordHash ) );
		$this->assertStatusGood( $bp->save( 'insert', $passwordHash ) );

		$bp2 = BotPassword::newFromCentralId( 42, 'TestSave', IDBAccessObject::READ_LATEST );
		$this->assertInstanceOf( BotPassword::class, $bp2 );
		$this->assertEquals( $bp->getUserCentralId(), $bp2->getUserCentralId() );
		$this->assertEquals( $bp->getAppId(), $bp2->getAppId() );
		$this->assertEquals( $bp->getToken(), $bp2->getToken() );
		$this->assertEquals( $bp->getRestrictions(), $bp2->getRestrictions() );
		$this->assertEquals( $bp->getGrants(), $bp2->getGrants() );

		/** @var Password $pw */
		$pw = TestingAccessWrapper::newFromObject( $bp )->getPassword();
		if ( $password === null ) {
			$this->assertInstanceOf( InvalidPassword::class, $pw );
		} else {
			$this->assertTrue( $pw->verify( $password ) );
		}

		$token = $bp->getToken();
		$this->assertEquals( 42, $bp->getUserCentralId() );
		$this->assertEquals( 'TestSave', $bp->getAppId() );
		$this->assertStatusNotOk( $bp->save( 'insert' ) );
		$this->assertStatusGood( $bp->save( 'update' ) );
		$this->assertNotEquals( $token, $bp->getToken() );

		$bp2 = BotPassword::newFromCentralId( 42, 'TestSave', IDBAccessObject::READ_LATEST );
		$this->assertInstanceOf( BotPassword::class, $bp2 );
		$this->assertEquals( $bp->getToken(), $bp2->getToken() );
		/** @var Password $pw */
		$pw = TestingAccessWrapper::newFromObject( $bp )->getPassword();
		if ( $password === null ) {
			$this->assertInstanceOf( InvalidPassword::class, $pw );
		} else {
			$this->assertTrue( $pw->verify( $password ) );
		}

		$passwordHash = $passwordFactory->newFromPlaintext( 'XXX' );
		$token = $bp->getToken();
		$this->assertStatusGood( $bp->save( 'update', $passwordHash ) );
		$this->assertNotEquals( $token, $bp->getToken() );

		/** @var Password $pw */
		$pw = TestingAccessWrapper::newFromObject( $bp )->getPassword();
		$this->assertTrue( $pw->verify( 'XXX' ) );

		$this->assertTrue( $bp->delete() );
		$this->assertFalse( $bp->isSaved() );
		$this->assertNull( BotPassword::newFromCentralId( 42, 'TestSave', IDBAccessObject::READ_LATEST ) );

		$this->expectException( UnexpectedValueException::class );
		$bp->save( 'foobar' )->isGood();
	}

	public static function provideSave() {
		return [
			[ null ],
			[ 'foobar' ],
		];
	}

	/**
	 * Tests for error handling when bp_restrictions and bp_grants are too long
	 */
	public function testSaveValidation() {
		$lotsOfIPs = [
			'IPAddresses' => array_fill(
				0,
				5000,
				"127.0.0.0/8"
			)
		];

		$bp = BotPassword::newUnsaved( [
			'centralId' => 42,
			'appId' => 'TestSave',
			// When this becomes JSON, it'll be 70,017 characters, which is
			// greater than BotPassword::GRANTS_MAXLENGTH, so it will cause an error.
			'restrictions' => MWRestrictions::newFromArray( $lotsOfIPs ),
			'grants' => [
				// Maximum length of the JSON is BotPassword::RESTRICTIONS_MAXLENGTH characters.
				// So one long grant name should be good. Turning it into JSON will add
				// a couple of extra characters, taking it over BotPassword::RESTRICTIONS_MAXLENGTH
				// characters long, so it will cause an error.
				str_repeat( '*', BotPassword::RESTRICTIONS_MAXLENGTH )
			],
		] );

		$status = $bp->save( 'insert' );

		$this->assertStatusError( 'botpasswords-toolong-restrictions', $status );
		$this->assertStatusError( 'botpasswords-toolong-grants', $status );
	}
}
