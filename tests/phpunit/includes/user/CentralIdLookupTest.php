<?php

/**
 * @covers CentralIdLookup
 * @group Database
 */
class CentralIdLookupTest extends MediaWikiTestCase {

	public function testFactory() {
		$mock = $this->getMockForAbstractClass( 'CentralIdLookup' );

		$this->setMwGlobals( array(
			'wgCentralIdLookupProviders' => array(
				'local' => array( 'class' => 'LocalIdLookup' ),
				'local2' => array( 'class' => 'LocalIdLookup' ),
				'mock' => array( 'factory' => function () use ( $mock ) {
					return $mock;
				} ),
				'bad' => array( 'class' => 'stdClass' ),
			),
			'wgCentralIdLookupProvider' => 'mock',
		) );

		$this->assertSame( $mock, CentralIdLookup::factory() );
		$this->assertSame( $mock, CentralIdLookup::factory( 'mock' ) );
		$this->assertSame( 'mock', $mock->getProviderId() );

		$local = CentralIdLookup::factory( 'local' );
		$this->assertNotSame( $mock, $local );
		$this->assertInstanceOf( 'LocalIdLookup', $local );
		$this->assertSame( $local, CentralIdLookup::factory( 'local' ) );
		$this->assertSame( 'local', $local->getProviderId() );

		$local2 = CentralIdLookup::factory( 'local2' );
		$this->assertNotSame( $local, $local2 );
		$this->assertInstanceOf( 'LocalIdLookup', $local2 );
		$this->assertSame( 'local2', $local2->getProviderId() );

		$this->assertNull( CentralIdLookup::factory( 'unconfigured' ) );
		$this->assertNull( CentralIdLookup::factory( 'bad' ) );
	}

	public function testCheckAudience() {
		$mock = TestingAccessWrapper::newFromObject(
			$this->getMockForAbstractClass( 'CentralIdLookup' )
		);

		$user = User::newFromName( 'UTSysop' );
		$this->assertSame( $user, $mock->checkAudience( $user ) );

		$user = $mock->checkAudience( CentralIdLookup::AUDIENCE_PUBLIC );
		$this->assertInstanceOf( 'User', $user );
		$this->assertSame( 0, $user->getId() );

		$this->assertNull( $mock->checkAudience( CentralIdLookup::AUDIENCE_RAW ) );

		try {
			$mock->checkAudience( 100 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid audience', $ex->getMessage() );
		}
	}

	public function testNameFromCentralId() {
		$mock = $this->getMockForAbstractClass( 'CentralIdLookup' );
		$mock->expects( $this->once() )->method( 'lookupCentralIds' )
			->with(
				$this->equalTo( array( 15 => null ) ),
				$this->equalTo( CentralIdLookup::AUDIENCE_RAW ),
				$this->equalTo( CentralIdLookup::READ_LATEST )
			)
			->will( $this->returnValue( array( 15 => 'FooBar' ) ) );

		$this->assertSame(
			'FooBar',
			$mock->nameFromCentralId( 15, CentralIdLookup::AUDIENCE_RAW, CentralIdLookup::READ_LATEST )
		);
	}

	/**
	 * @dataProvider provideLocalUserFromCentralId
	 * @param string $name
	 * @param bool $succeeds
	 */
	public function testLocalUserFromCentralId( $name, $succeeds ) {
		$mock = $this->getMockForAbstractClass( 'CentralIdLookup' );
		$mock->expects( $this->any() )->method( 'isAttached' )
			->will( $this->returnValue( true ) );
		$mock->expects( $this->once() )->method( 'lookupCentralIds' )
			->with(
				$this->equalTo( array( 42 => null ) ),
				$this->equalTo( CentralIdLookup::AUDIENCE_RAW ),
				$this->equalTo( CentralIdLookup::READ_LATEST )
			)
			->will( $this->returnValue( array( 42 => $name ) ) );

		$user = $mock->localUserFromCentralId(
			42, CentralIdLookup::AUDIENCE_RAW, CentralIdLookup::READ_LATEST
		);
		if ( $succeeds ) {
			$this->assertInstanceOf( 'User', $user );
			$this->assertSame( $name, $user->getName() );
		} else {
			$this->assertNull( $user );
		}

		$mock = $this->getMockForAbstractClass( 'CentralIdLookup' );
		$mock->expects( $this->any() )->method( 'isAttached' )
			->will( $this->returnValue( false ) );
		$mock->expects( $this->once() )->method( 'lookupCentralIds' )
			->with(
				$this->equalTo( array( 42 => null ) ),
				$this->equalTo( CentralIdLookup::AUDIENCE_RAW ),
				$this->equalTo( CentralIdLookup::READ_LATEST )
			)
			->will( $this->returnValue( array( 42 => $name ) ) );
		$this->assertNull(
			$mock->localUserFromCentralId( 42, CentralIdLookup::AUDIENCE_RAW, CentralIdLookup::READ_LATEST )
		);
	}

	public static function provideLocalUserFromCentralId() {
		return array(
			array( 'UTSysop', true ),
			array( 'UTDoesNotExist', false ),
			array( null, false ),
			array( '', false ),
			array( '<X>', false ),
		);
	}

	public function testCentralIdFromName() {
		$mock = $this->getMockForAbstractClass( 'CentralIdLookup' );
		$mock->expects( $this->once() )->method( 'lookupUserNames' )
			->with(
				$this->equalTo( array( 'FooBar' => 0 ) ),
				$this->equalTo( CentralIdLookup::AUDIENCE_RAW ),
				$this->equalTo( CentralIdLookup::READ_LATEST )
			)
			->will( $this->returnValue( array( 'FooBar' => 23 ) ) );

		$this->assertSame(
			23,
			$mock->centralIdFromName( 'FooBar', CentralIdLookup::AUDIENCE_RAW, CentralIdLookup::READ_LATEST )
		);
	}

	public function testCentralIdFromLocalUser() {
		$mock = $this->getMockForAbstractClass( 'CentralIdLookup' );
		$mock->expects( $this->any() )->method( 'isAttached' )
			->will( $this->returnValue( true ) );
		$mock->expects( $this->once() )->method( 'lookupUserNames' )
			->with(
				$this->equalTo( array( 'FooBar' => 0 ) ),
				$this->equalTo( CentralIdLookup::AUDIENCE_RAW ),
				$this->equalTo( CentralIdLookup::READ_LATEST )
			)
			->will( $this->returnValue( array( 'FooBar' => 23 ) ) );

		$this->assertSame(
			23,
			$mock->centralIdFromLocalUser(
				User::newFromName( 'FooBar' ), CentralIdLookup::AUDIENCE_RAW, CentralIdLookup::READ_LATEST
			)
		);

		$mock = $this->getMockForAbstractClass( 'CentralIdLookup' );
		$mock->expects( $this->any() )->method( 'isAttached' )
			->will( $this->returnValue( false ) );
		$mock->expects( $this->never() )->method( 'lookupUserNames' );

		$this->assertSame(
			0,
			$mock->centralIdFromLocalUser(
				User::newFromName( 'FooBar' ), CentralIdLookup::AUDIENCE_RAW, CentralIdLookup::READ_LATEST
			)
		);
	}

}
