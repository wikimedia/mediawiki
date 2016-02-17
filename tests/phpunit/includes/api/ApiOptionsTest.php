<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiOptions
 */
class ApiOptionsTest extends MediaWikiLangTestCase {

	/** @var PHPUnit_Framework_MockObject_MockObject */
	private $mUserMock;
	/** @var ApiOptions */
	private $mTested;
	private $mSession;
	/** @var DerivativeContext */
	private $mContext;

	private static $Success = [ 'options' => 'success' ];

	protected function setUp() {
		parent::setUp();

		$this->mUserMock = $this->getMockBuilder( 'User' )
			->disableOriginalConstructor()
			->getMock();

		// Set up groups and rights
		$this->mUserMock->expects( $this->any() )
			->method( 'getEffectiveGroups' )->will( $this->returnValue( [ '*', 'user' ] ) );
		$this->mUserMock->expects( $this->any() )
			->method( 'isAllowed' )->will( $this->returnValue( true ) );

		// Set up callback for User::getOptionKinds
		$this->mUserMock->expects( $this->any() )
			->method( 'getOptionKinds' )->will( $this->returnCallback( [ $this, 'getOptionKinds' ] ) );

		// No actual DB data
		$this->mUserMock->expects( $this->any() )
			->method( 'getInstanceForUpdate' )->will( $this->returnValue( $this->mUserMock ) );

		// Create a new context
		$this->mContext = new DerivativeContext( new RequestContext() );
		$this->mContext->getContext()->setTitle( Title::newFromText( 'Test' ) );
		$this->mContext->setUser( $this->mUserMock );

		$main = new ApiMain( $this->mContext );

		// Empty session
		$this->mSession = [];

		$this->mTested = new ApiOptions( $main, 'options' );

		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'GetPreferences' => [
				[ $this, 'hookGetPreferences' ]
			]
		] );
	}

	public function hookGetPreferences( $user, &$preferences ) {
		$preferences = [];

		foreach ( [ 'name', 'willBeNull', 'willBeEmpty', 'willBeHappy' ] as $k ) {
			$preferences[$k] = [
				'type' => 'text',
				'section' => 'test',
				'label' => '&#160;',
			];
		}

		$preferences['testmultiselect'] = [
			'type' => 'multiselect',
			'options' => [
				'Test' => [
					'<span dir="auto">Some HTML here for option 1</span>' => 'opt1',
					'<span dir="auto">Some HTML here for option 2</span>' => 'opt2',
					'<span dir="auto">Some HTML here for option 3</span>' => 'opt3',
					'<span dir="auto">Some HTML here for option 4</span>' => 'opt4',
				],
			],
			'section' => 'test',
			'label' => '&#160;',
			'prefix' => 'testmultiselect-',
			'default' => [],
		];

		return true;
	}

	/**
	 * @param IContextSource $context
	 * @param array|null $options
	 *
	 * @return array
	 */
	public function getOptionKinds( IContextSource $context, $options = null ) {
		// Match with above.
		$kinds = [
			'name' => 'registered',
			'willBeNull' => 'registered',
			'willBeEmpty' => 'registered',
			'willBeHappy' => 'registered',
			'testmultiselect-opt1' => 'registered-multiselect',
			'testmultiselect-opt2' => 'registered-multiselect',
			'testmultiselect-opt3' => 'registered-multiselect',
			'testmultiselect-opt4' => 'registered-multiselect',
			'special' => 'special',
		];

		if ( $options === null ) {
			return $kinds;
		}

		$mapping = [];
		foreach ( $options as $key => $value ) {
			if ( isset( $kinds[$key] ) ) {
				$mapping[$key] = $kinds[$key];
			} elseif ( substr( $key, 0, 7 ) === 'userjs-' ) {
				$mapping[$key] = 'userjs';
			} else {
				$mapping[$key] = 'unused';
			}
		}

		return $mapping;
	}

	private function getSampleRequest( $custom = [] ) {
		$request = [
			'token' => '123ABC',
			'change' => null,
			'optionname' => null,
			'optionvalue' => null,
		];

		return array_merge( $request, $custom );
	}

	private function executeQuery( $request ) {
		$this->mContext->setRequest( new FauxRequest( $request, true, $this->mSession ) );
		$this->mTested->execute();

		return $this->mTested->getResult()->getResultData( null, [ 'Strip' => 'all' ] );
	}

	/**
	 * @expectedException UsageException
	 */
	public function testNoToken() {
		$request = $this->getSampleRequest( [ 'token' => null ] );

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
			$request = $this->getSampleRequest( [ 'optionvalue' => '1' ] );

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
			->method( 'resetOptions' )
			->with( $this->equalTo( [ 'all' ] ) );

		$this->mUserMock->expects( $this->never() )
			->method( 'setOption' );

		$this->mUserMock->expects( $this->once() )
			->method( 'saveSettings' );

		$request = $this->getSampleRequest( [ 'reset' => '' ] );

		$response = $this->executeQuery( $request );

		$this->assertEquals( self::$Success, $response );
	}

	public function testResetKinds() {
		$this->mUserMock->expects( $this->once() )
			->method( 'resetOptions' )
			->with( $this->equalTo( [ 'registered' ] ) );

		$this->mUserMock->expects( $this->never() )
			->method( 'setOption' );

		$this->mUserMock->expects( $this->once() )
			->method( 'saveSettings' );

		$request = $this->getSampleRequest( [ 'reset' => '', 'resetkinds' => 'registered' ] );

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

		$request = $this->getSampleRequest( [ 'optionname' => 'name', 'optionvalue' => 'value' ] );

		$response = $this->executeQuery( $request );

		$this->assertEquals( self::$Success, $response );
	}

	public function testOptionResetValue() {
		$this->mUserMock->expects( $this->never() )
			->method( 'resetOptions' );

		$this->mUserMock->expects( $this->once() )
			->method( 'setOption' )
			->with( $this->equalTo( 'name' ), $this->identicalTo( null ) );

		$this->mUserMock->expects( $this->once() )
			->method( 'saveSettings' );

		$request = $this->getSampleRequest( [ 'optionname' => 'name' ] );
		$response = $this->executeQuery( $request );

		$this->assertEquals( self::$Success, $response );
	}

	public function testChange() {
		$this->mUserMock->expects( $this->never() )
			->method( 'resetOptions' );

		$this->mUserMock->expects( $this->at( 2 ) )
			->method( 'getOptions' );

		$this->mUserMock->expects( $this->at( 5 ) )
			->method( 'setOption' )
			->with( $this->equalTo( 'willBeNull' ), $this->identicalTo( null ) );

		$this->mUserMock->expects( $this->at( 6 ) )
			->method( 'getOptions' );

		$this->mUserMock->expects( $this->at( 7 ) )
			->method( 'setOption' )
			->with( $this->equalTo( 'willBeEmpty' ), $this->equalTo( '' ) );

		$this->mUserMock->expects( $this->at( 8 ) )
			->method( 'getOptions' );

		$this->mUserMock->expects( $this->at( 9 ) )
			->method( 'setOption' )
			->with( $this->equalTo( 'willBeHappy' ), $this->equalTo( 'Happy' ) );

		$this->mUserMock->expects( $this->once() )
			->method( 'saveSettings' );

		$request = $this->getSampleRequest( [
			'change' => 'willBeNull|willBeEmpty=|willBeHappy=Happy'
		] );

		$response = $this->executeQuery( $request );

		$this->assertEquals( self::$Success, $response );
	}

	public function testResetChangeOption() {
		$this->mUserMock->expects( $this->once() )
			->method( 'resetOptions' );

		$this->mUserMock->expects( $this->at( 5 ) )
			->method( 'getOptions' );

		$this->mUserMock->expects( $this->at( 6 ) )
			->method( 'setOption' )
			->with( $this->equalTo( 'willBeHappy' ), $this->equalTo( 'Happy' ) );

		$this->mUserMock->expects( $this->at( 7 ) )
			->method( 'getOptions' );

		$this->mUserMock->expects( $this->at( 8 ) )
			->method( 'setOption' )
			->with( $this->equalTo( 'name' ), $this->equalTo( 'value' ) );

		$this->mUserMock->expects( $this->once() )
			->method( 'saveSettings' );

		$args = [
			'reset' => '',
			'change' => 'willBeHappy=Happy',
			'optionname' => 'name',
			'optionvalue' => 'value'
		];

		$response = $this->executeQuery( $this->getSampleRequest( $args ) );

		$this->assertEquals( self::$Success, $response );
	}

	public function testMultiSelect() {
		$this->mUserMock->expects( $this->never() )
			->method( 'resetOptions' );

		$this->mUserMock->expects( $this->at( 4 ) )
			->method( 'setOption' )
			->with( $this->equalTo( 'testmultiselect-opt1' ), $this->identicalTo( true ) );

		$this->mUserMock->expects( $this->at( 5 ) )
			->method( 'setOption' )
			->with( $this->equalTo( 'testmultiselect-opt2' ), $this->identicalTo( null ) );

		$this->mUserMock->expects( $this->at( 6 ) )
			->method( 'setOption' )
			->with( $this->equalTo( 'testmultiselect-opt3' ), $this->identicalTo( false ) );

		$this->mUserMock->expects( $this->at( 7 ) )
			->method( 'setOption' )
			->with( $this->equalTo( 'testmultiselect-opt4' ), $this->identicalTo( false ) );

		$this->mUserMock->expects( $this->once() )
			->method( 'saveSettings' );

		$request = $this->getSampleRequest( [
			'change' => 'testmultiselect-opt1=1|testmultiselect-opt2|'
				. 'testmultiselect-opt3=|testmultiselect-opt4=0'
		] );

		$response = $this->executeQuery( $request );

		$this->assertEquals( self::$Success, $response );
	}

	public function testSpecialOption() {
		$this->mUserMock->expects( $this->never() )
			->method( 'resetOptions' );

		$this->mUserMock->expects( $this->never() )
			->method( 'saveSettings' );

		$request = $this->getSampleRequest( [
			'change' => 'special=1'
		] );

		$response = $this->executeQuery( $request );

		$this->assertEquals( [
			'options' => 'success',
			'warnings' => [
				'options' => [
					'warnings' => "Validation error for 'special': cannot be set by this module"
				]
			]
		], $response );
	}

	public function testUnknownOption() {
		$this->mUserMock->expects( $this->never() )
			->method( 'resetOptions' );

		$this->mUserMock->expects( $this->never() )
			->method( 'saveSettings' );

		$request = $this->getSampleRequest( [
			'change' => 'unknownOption=1'
		] );

		$response = $this->executeQuery( $request );

		$this->assertEquals( [
			'options' => 'success',
			'warnings' => [
				'options' => [
					'warnings' => "Validation error for 'unknownOption': not a valid preference"
				]
			]
		], $response );
	}

	public function testUserjsOption() {
		$this->mUserMock->expects( $this->never() )
			->method( 'resetOptions' );

		$this->mUserMock->expects( $this->once() )
			->method( 'setOption' )
			->with( $this->equalTo( 'userjs-option' ), $this->equalTo( '1' ) );

		$this->mUserMock->expects( $this->once() )
			->method( 'saveSettings' );

		$request = $this->getSampleRequest( [
			'change' => 'userjs-option=1'
		] );

		$response = $this->executeQuery( $request );

		$this->assertEquals( self::$Success, $response );
	}
}
