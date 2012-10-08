<?php

/**
 * @group API
 */
class ApiOptionsTest extends MediaWikiLangTestCase {

	private $mTested, $mUserMock, $mContext, $mSession;

	private static $Success = array( 'options' => 'success' );

	protected function setUp() {
		parent::setUp();

		$this->mUserMock = $this->getMockBuilder( 'User' )
			->disableOriginalConstructor()
			->getMock();

		// Create a new context
		$this->mContext = new DerivativeContext( new RequestContext() );
		$this->mContext->setUser( $this->mUserMock );

		$main = new ApiMain( $this->mContext );

		// Empty session
		$this->mSession = array();

		$this->mTested = new ApiOptions( $main, 'options' );
	}

	private function getSampleRequest( $custom = array() ) {
		$request = array(
			'token' => '123ABC',
			'change' => null,
			'optionname' => null,
			'optionvalue' => null,
		);
		return array_merge( $request, $custom );
	}

	private function executeQuery( $request ) {
		$this->mContext->setRequest( new FauxRequest( $request, true, $this->mSession ) );
		$this->mTested->execute();
		return $this->mTested->getResult()->getData();
	}

	/**
	 * @expectedException UsageException
	 */
	public function testNoToken() {
		$request = $this->getSampleRequest( array( 'token' => null ) );

		$this->executeQuery( $request );
	}

	public function testAnon() {
		$this->mUserMock->expects( $this->once() )
			->method( 'isAnon' )
			->will( $this->returnValue( true ) );

		try {
			$request = $this->getSampleRequest();

			$this->executeQuery( $request );
		} catch ( UsageException $e ) {
			$this->assertEquals( 'notloggedin', $e->getCodeString() );
			$this->assertEquals( 'Anonymous users cannot change preferences', $e->getMessage() );
			return;
		}
		$this->fail( "UsageException was not thrown" );
	}

	public function testNoOptionname() {
		try {
			$request = $this->getSampleRequest( array( 'optionvalue' => '1' ) );

			$this->executeQuery( $request );
		} catch ( UsageException $e ) {
			$this->assertEquals( 'nooptionname', $e->getCodeString() );
			$this->assertEquals( 'The optionname parameter must be set', $e->getMessage() );
			return;
		}
		$this->fail( "UsageException was not thrown" );
	}

	public function testNoChanges() {
		$this->mUserMock->expects( $this->never() )
			->method( 'resetOptions' );

		$this->mUserMock->expects( $this->never() )
			->method( 'setOption' );

		$this->mUserMock->expects( $this->never() )
			->method( 'saveSettings' );

		try {
			$request = $this->getSampleRequest();

			$this->executeQuery( $request );
		} catch ( UsageException $e ) {
			$this->assertEquals( 'nochanges', $e->getCodeString() );
			$this->assertEquals( 'No changes were requested', $e->getMessage() );
			return;
		}
		$this->fail( "UsageException was not thrown" );
	}

	public function testReset() {
		$this->mUserMock->expects( $this->once() )
			->method( 'resetOptions' );

		$this->mUserMock->expects( $this->never() )
			->method( 'setOption' );

		$this->mUserMock->expects( $this->once() )
			->method( 'saveSettings' );

		$request = $this->getSampleRequest( array( 'reset' => '' ) );

		$response = $this->executeQuery( $request );

		$this->assertEquals( self::$Success, $response );
	}

	public function testOptionWithValue() {
		$this->mUserMock->expects( $this->never() )
			->method( 'resetOptions' );

		$this->mUserMock->expects( $this->once() )
			->method( 'setOption' )
			->with( $this->equalTo( 'name' ), $this->equalTo( 'value' ) );

		$this->mUserMock->expects( $this->once() )
			->method( 'saveSettings' );

		$request = $this->getSampleRequest( array( 'optionname' => 'name', 'optionvalue' => 'value' ) );

		$response = $this->executeQuery( $request );

		$this->assertEquals( self::$Success, $response );
	}

	public function testOptionResetValue() {
		$this->mUserMock->expects( $this->never() )
			->method( 'resetOptions' );

		$this->mUserMock->expects( $this->once() )
			->method( 'setOption' )
			->with( $this->equalTo( 'name' ), $this->equalTo( null ) );

		$this->mUserMock->expects( $this->once() )
			->method( 'saveSettings' );

		$request = $this->getSampleRequest( array( 'optionname' => 'name' ) );
		$response = $this->executeQuery( $request );

		$this->assertEquals( self::$Success, $response );
	}

	public function testChange() {
		$this->mUserMock->expects( $this->never() )
			->method( 'resetOptions' );

		$this->mUserMock->expects( $this->at( 1 ) )
			->method( 'setOption' )
			->with( $this->equalTo( 'willBeNull' ), $this->equalTo( null ) );

		$this->mUserMock->expects( $this->at( 2 ) )
			->method( 'setOption' )
			->with( $this->equalTo( 'willBeEmpty' ), $this->equalTo( '' ) );

		$this->mUserMock->expects( $this->at( 3 ) )
			->method( 'setOption' )
			->with( $this->equalTo( 'willBeHappy' ), $this->equalTo( 'Happy' ) );

		$this->mUserMock->expects( $this->once() )
			->method( 'saveSettings' );

		$request = $this->getSampleRequest( array( 'change' => 'willBeNull|willBeEmpty=|willBeHappy=Happy' ) );

		$response = $this->executeQuery( $request );

		$this->assertEquals( self::$Success, $response );
	}

	public function testResetChangeOption() {
		$this->mUserMock->expects( $this->once() )
			->method( 'resetOptions' );

		$this->mUserMock->expects( $this->at( 2 ) )
			->method( 'setOption' )
			->with( $this->equalTo( 'willBeHappy' ), $this->equalTo( 'Happy' ) );

		$this->mUserMock->expects( $this->at( 3 ) )
			->method( 'setOption' )
			->with( $this->equalTo( 'name' ), $this->equalTo( 'value' ) );

		$this->mUserMock->expects( $this->once() )
			->method( 'saveSettings' );

		$args = array(
			'reset' => '',
			'change' => 'willBeHappy=Happy',
			'optionname' => 'name',
			'optionvalue' => 'value'
		);

		$response = $this->executeQuery( $this->getSampleRequest( $args ) );

		$this->assertEquals( self::$Success, $response );
	}
}
