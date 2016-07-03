<?php

use MediaWiki\Session\SessionManager;

/**
 * @covers BotPassword
 * @group Database
 */
class BotPasswordTest extends MediaWikiTestCase {
	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgEnableBotPasswords' => true,
			'wgBotPasswordsDatabase' => false,
			'wgCentralIdLookupProvider' => 'BotPasswordTest OkMock',
			'wgGrantPermissions' => [
				'test' => [ 'read' => true ],
			],
			'wgUserrightsInterwikiDelimiter' => '@',
		] );

		$mock1 = $this->getMockForAbstractClass( 'CentralIdLookup' );
		$mock1->expects( $this->any() )->method( 'isAttached' )
			->will( $this->returnValue( true ) );
		$mock1->expects( $this->any() )->method( 'lookupUserNames' )
			->will( $this->returnValue( [ 'UTSysop' => 42, 'UTDummy' => 43, 'UTInvalid' => 0 ] ) );
		$mock1->expects( $this->never() )->method( 'lookupCentralIds' );

		$mock2 = $this->getMockForAbstractClass( 'CentralIdLookup' );
		$mock2->expects( $this->any() )->method( 'isAttached' )
			->will( $this->returnValue( false ) );
		$mock2->expects( $this->any() )->method( 'lookupUserNames' )
			->will( $this->returnArgument( 0 ) );
		$mock2->expects( $this->never() )->method( 'lookupCentralIds' );

		$this->mergeMwGlobalArrayValue( 'wgCentralIdLookupProviders', [
			'BotPasswordTest OkMock' => [ 'factory' => function () use ( $mock1 ) {
				return $mock1;
			} ],
			'BotPasswordTest FailMock' => [ 'factory' => function () use ( $mock2 ) {
				return $mock2;
			} ],
		] );

		CentralIdLookup::resetCache();
	}

	public function addDBData() {
		$passwordFactory = new \PasswordFactory();
		$passwordFactory->init( \RequestContext::getMain()->getConfig() );
		// A is unsalted MD5 (thus fast) ... we don't care about security here, this is test only
		$passwordFactory->setDefaultType( 'A' );
		$pwhash = $passwordFactory->newFromPlaintext( 'foobaz' );

		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete(
			'bot_passwords',
			[ 'bp_user' => [ 42, 43 ], 'bp_app_id' => 'BotPassword' ],
			__METHOD__
		);
		$dbw->insert(
			'bot_passwords',
			[
				[
					'bp_user' => 42,
					'bp_app_id' => 'BotPassword',
					'bp_password' => $pwhash->toString(),
					'bp_token' => 'token!',
					'bp_restrictions' => '{"IPAddresses":["127.0.0.0/8"]}',
					'bp_grants' => '["test"]',
				],
				[
					'bp_user' => 43,
					'bp_app_id' => 'BotPassword',
					'bp_password' => $pwhash->toString(),
					'bp_token' => 'token!',
					'bp_restrictions' => '{"IPAddresses":["127.0.0.0/8"]}',
					'bp_grants' => '["test"]',
				],
			],
			__METHOD__
		);
	}

	public function testBasics() {
		$user = User::newFromName( 'UTSysop' );
		$bp = BotPassword::newFromUser( $user, 'BotPassword' );
		$this->assertInstanceOf( 'BotPassword', $bp );
		$this->assertTrue( $bp->isSaved() );
		$this->assertSame( 42, $bp->getUserCentralId() );
		$this->assertSame( 'BotPassword', $bp->getAppId() );
		$this->assertSame( 'token!', trim( $bp->getToken(), " \0" ) );
		$this->assertEquals( '{"IPAddresses":["127.0.0.0/8"]}', $bp->getRestrictions()->toJson() );
		$this->assertSame( [ 'test' ], $bp->getGrants() );

		$this->assertNull( BotPassword::newFromUser( $user, 'DoesNotExist' ) );

		$this->setMwGlobals( [
			'wgCentralIdLookupProvider' => 'BotPasswordTest FailMock'
		] );
		$this->assertNull( BotPassword::newFromUser( $user, 'BotPassword' ) );

		$this->assertSame( '@', BotPassword::getSeparator() );
		$this->setMwGlobals( [
			'wgUserrightsInterwikiDelimiter' => '#',
		] );
		$this->assertSame( '#', BotPassword::getSeparator() );
	}

	public function testUnsaved() {
		$user = User::newFromName( 'UTSysop' );
		$bp = BotPassword::newUnsaved( [
			'user' => $user,
			'appId' => 'DoesNotExist'
		] );
		$this->assertInstanceOf( 'BotPassword', $bp );
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
		$this->assertInstanceOf( 'BotPassword', $bp );
		$this->assertFalse( $bp->isSaved() );
		$this->assertSame( 43, $bp->getUserCentralId() );
		$this->assertSame( 'DoesNotExist2', $bp->getAppId() );
		$this->assertEquals( '{"IPAddresses":["127.0.0.0/8"]}', $bp->getRestrictions()->toJson() );
		$this->assertSame( [ 'test' ], $bp->getGrants() );

		$user = User::newFromName( 'UTSysop' );
		$bp = BotPassword::newUnsaved( [
			'centralId' => 45,
			'appId' => 'DoesNotExist'
		] );
		$this->assertInstanceOf( 'BotPassword', $bp );
		$this->assertFalse( $bp->isSaved() );
		$this->assertSame( 45, $bp->getUserCentralId() );
		$this->assertSame( 'DoesNotExist', $bp->getAppId() );

		$user = User::newFromName( 'UTSysop' );
		$bp = BotPassword::newUnsaved( [
			'user' => $user,
			'appId' => 'BotPassword'
		] );
		$this->assertInstanceOf( 'BotPassword', $bp );
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
			'user' => 'UTSysop',
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
		$bp = TestingAccessWrapper::newFromObject( BotPassword::newFromCentralId( 42, 'BotPassword' ) );

		$password = $bp->getPassword();
		$this->assertInstanceOf( 'Password', $password );
		$this->assertTrue( $password->equals( 'foobaz' ) );

		$bp->centralId = 44;
		$password = $bp->getPassword();
		$this->assertInstanceOf( 'InvalidPassword', $password );

		$bp = TestingAccessWrapper::newFromObject( BotPassword::newFromCentralId( 42, 'BotPassword' ) );
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update(
			'bot_passwords',
			[ 'bp_password' => 'garbage' ],
			[ 'bp_user' => 42, 'bp_app_id' => 'BotPassword' ],
			__METHOD__
		);
		$password = $bp->getPassword();
		$this->assertInstanceOf( 'InvalidPassword', $password );
	}

	public function testInvalidateAllPasswordsForUser() {
		$bp1 = TestingAccessWrapper::newFromObject( BotPassword::newFromCentralId( 42, 'BotPassword' ) );
		$bp2 = TestingAccessWrapper::newFromObject( BotPassword::newFromCentralId( 43, 'BotPassword' ) );

		$this->assertNotInstanceOf( 'InvalidPassword', $bp1->getPassword(), 'sanity check' );
		$this->assertNotInstanceOf( 'InvalidPassword', $bp2->getPassword(), 'sanity check' );
		BotPassword::invalidateAllPasswordsForUser( 'UTSysop' );
		$this->assertInstanceOf( 'InvalidPassword', $bp1->getPassword() );
		$this->assertNotInstanceOf( 'InvalidPassword', $bp2->getPassword() );

		$bp = TestingAccessWrapper::newFromObject( BotPassword::newFromCentralId( 42, 'BotPassword' ) );
		$this->assertInstanceOf( 'InvalidPassword', $bp->getPassword() );
	}

	public function testRemoveAllPasswordsForUser() {
		$this->assertNotNull( BotPassword::newFromCentralId( 42, 'BotPassword' ), 'sanity check' );
		$this->assertNotNull( BotPassword::newFromCentralId( 43, 'BotPassword' ), 'sanity check' );

		BotPassword::removeAllPasswordsForUser( 'UTSysop' );

		$this->assertNull( BotPassword::newFromCentralId( 42, 'BotPassword' ) );
		$this->assertNotNull( BotPassword::newFromCentralId( 43, 'BotPassword' ) );
	}

	public function testLogin() {
		// Test failure when bot passwords aren't enabled
		$this->setMwGlobals( 'wgEnableBotPasswords', false );
		$status = BotPassword::login( 'UTSysop@BotPassword', 'foobaz', new FauxRequest );
		$this->assertEquals( Status::newFatal( 'botpasswords-disabled' ), $status );
		$this->setMwGlobals( 'wgEnableBotPasswords', true );

		// Test failure when BotPasswordSessionProvider isn't configured
		$manager = new SessionManager( [
			'logger' => new Psr\Log\NullLogger,
			'store' => new EmptyBagOStuff,
		] );
		$reset = MediaWiki\Session\TestUtils::setSessionManagerSingleton( $manager );
		$this->assertNull(
			$manager->getProvider( MediaWiki\Session\BotPasswordSessionProvider::class ),
			'sanity check'
		);
		$status = BotPassword::login( 'UTSysop@BotPassword', 'foobaz', new FauxRequest );
		$this->assertEquals( Status::newFatal( 'botpasswords-no-provider' ), $status );
		ScopedCallback::consume( $reset );

		// Now configure BotPasswordSessionProvider for further tests...
		$mainConfig = RequestContext::getMain()->getConfig();
		$config = new HashConfig( [
			'SessionProviders' => $mainConfig->get( 'SessionProviders' ) + [
				MediaWiki\Session\BotPasswordSessionProvider::class => [
					'class' => MediaWiki\Session\BotPasswordSessionProvider::class,
					'args' => [ [ 'priority' => 40 ] ],
				]
			],
		] );
		$manager = new SessionManager( [
			'config' => new MultiConfig( [ $config, RequestContext::getMain()->getConfig() ] ),
			'logger' => new Psr\Log\NullLogger,
			'store' => new EmptyBagOStuff,
		] );
		$reset = MediaWiki\Session\TestUtils::setSessionManagerSingleton( $manager );

		// No "@"-thing in the username
		$status = BotPassword::login( 'UTSysop', 'foobaz', new FauxRequest );
		$this->assertEquals( Status::newFatal( 'botpasswords-invalid-name', '@' ), $status );

		// No base user
		$status = BotPassword::login( 'UTDummy@BotPassword', 'foobaz', new FauxRequest );
		$this->assertEquals( Status::newFatal( 'nosuchuser', 'UTDummy' ), $status );

		// No bot password
		$status = BotPassword::login( 'UTSysop@DoesNotExist', 'foobaz', new FauxRequest );
		$this->assertEquals(
			Status::newFatal( 'botpasswords-not-exist', 'UTSysop', 'DoesNotExist' ),
			$status
		);

		// Failed restriction
		$request = $this->getMock( 'FauxRequest', [ 'getIP' ] );
		$request->expects( $this->any() )->method( 'getIP' )
			->will( $this->returnValue( '10.0.0.1' ) );
		$status = BotPassword::login( 'UTSysop@BotPassword', 'foobaz', $request );
		$this->assertEquals( Status::newFatal( 'botpasswords-restriction-failed' ), $status );

		// Wrong password
		$status = BotPassword::login( 'UTSysop@BotPassword', 'UTSysopPassword', new FauxRequest );
		$this->assertEquals( Status::newFatal( 'wrongpassword' ), $status );

		// Success!
		$request = new FauxRequest;
		$this->assertNotInstanceOf(
			MediaWiki\Session\BotPasswordSessionProvider::class,
			$request->getSession()->getProvider(),
			'sanity check'
		);
		$status = BotPassword::login( 'UTSysop@BotPassword', 'foobaz', $request );
		$this->assertInstanceOf( 'Status', $status );
		$this->assertTrue( $status->isGood() );
		$session = $status->getValue();
		$this->assertInstanceOf( MediaWiki\Session\Session::class, $session );
		$this->assertInstanceOf(
			MediaWiki\Session\BotPasswordSessionProvider::class, $session->getProvider()
		);
		$this->assertSame( $session->getId(), $request->getSession()->getId() );

		ScopedCallback::consume( $reset );
	}

	/**
	 * @dataProvider provideSave
	 * @param string|null $password
	 */
	public function testSave( $password ) {
		$passwordFactory = new \PasswordFactory();
		$passwordFactory->init( \RequestContext::getMain()->getConfig() );
		// A is unsalted MD5 (thus fast) ... we don't care about security here, this is test only
		$passwordFactory->setDefaultType( 'A' );

		$bp = BotPassword::newUnsaved( [
			'centralId' => 42,
			'appId' => 'TestSave',
			'restrictions' => MWRestrictions::newFromJson( '{"IPAddresses":["127.0.0.0/8"]}' ),
			'grants' => [ 'test' ],
		] );
		$this->assertFalse( $bp->isSaved(), 'sanity check' );
		$this->assertNull(
			BotPassword::newFromCentralId( 42, 'TestSave', BotPassword::READ_LATEST ), 'sanity check'
		);

		$pwhash = $password ? $passwordFactory->newFromPlaintext( $password ) : null;
		$this->assertFalse( $bp->save( 'update', $pwhash ) );
		$this->assertTrue( $bp->save( 'insert', $pwhash ) );
		$bp2 = BotPassword::newFromCentralId( 42, 'TestSave', BotPassword::READ_LATEST );
		$this->assertInstanceOf( 'BotPassword', $bp2 );
		$this->assertEquals( $bp->getUserCentralId(), $bp2->getUserCentralId() );
		$this->assertEquals( $bp->getAppId(), $bp2->getAppId() );
		$this->assertEquals( $bp->getToken(), $bp2->getToken() );
		$this->assertEquals( $bp->getRestrictions(), $bp2->getRestrictions() );
		$this->assertEquals( $bp->getGrants(), $bp2->getGrants() );
		$pw = TestingAccessWrapper::newFromObject( $bp )->getPassword();
		if ( $password === null ) {
			$this->assertInstanceOf( 'InvalidPassword', $pw );
		} else {
			$this->assertTrue( $pw->equals( $password ) );
		}

		$token = $bp->getToken();
		$this->assertFalse( $bp->save( 'insert' ) );
		$this->assertTrue( $bp->save( 'update' ) );
		$this->assertNotEquals( $token, $bp->getToken() );
		$bp2 = BotPassword::newFromCentralId( 42, 'TestSave', BotPassword::READ_LATEST );
		$this->assertInstanceOf( 'BotPassword', $bp2 );
		$this->assertEquals( $bp->getToken(), $bp2->getToken() );
		$pw = TestingAccessWrapper::newFromObject( $bp )->getPassword();
		if ( $password === null ) {
			$this->assertInstanceOf( 'InvalidPassword', $pw );
		} else {
			$this->assertTrue( $pw->equals( $password ) );
		}

		$pwhash = $passwordFactory->newFromPlaintext( 'XXX' );
		$token = $bp->getToken();
		$this->assertTrue( $bp->save( 'update', $pwhash ) );
		$this->assertNotEquals( $token, $bp->getToken() );
		$pw = TestingAccessWrapper::newFromObject( $bp )->getPassword();
		$this->assertTrue( $pw->equals( 'XXX' ) );

		$this->assertTrue( $bp->delete() );
		$this->assertFalse( $bp->isSaved() );
		$this->assertNull( BotPassword::newFromCentralId( 42, 'TestSave', BotPassword::READ_LATEST ) );

		$this->assertFalse( $bp->save( 'foobar' ) );
	}

	public static function provideSave() {
		return [
			[ null ],
			[ 'foobar' ],
		];
	}
}
