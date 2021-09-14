<?php

use MediaWiki\Preferences\DefaultPreferencesFactory;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserOptionsManager;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiOptions
 */
class ApiOptionsTest extends MediaWikiLangTestCase {
	use MockAuthorityTrait;

	/** @var MockObject */
	private $mUserMock;
	/** @var MockObject */
	private $userOptionsManagerMock;
	/** @var ApiOptions */
	private $mTested;
	private $mSession;
	/** @var DerivativeContext */
	private $mContext;

	private static $Success = [ 'options' => 'success' ];

	protected function setUp(): void {
		parent::setUp();

		$this->mUserMock = $this->getMockBuilder( User::class )
			->disableOriginalConstructor()
			->getMock();

		// No actual DB data
		$this->mUserMock->method( 'getInstanceForUpdate' )->willReturn( $this->mUserMock );

		$this->mUserMock->method( 'isAllowedAny' )->willReturn( true );

		// Create a new context
		$this->mContext = new DerivativeContext( new RequestContext() );
		$this->mContext->getContext()->setTitle( Title::newFromText( 'Test' ) );
		$this->mContext->setAuthority(
			$this->mockUserAuthorityWithPermissions( $this->mUserMock, [ 'editmyoptions' ] )
		);

		$main = new ApiMain( $this->mContext );

		// Empty session
		$this->mSession = [];

		$this->userOptionsManagerMock = $this->createNoOpMock(
			UserOptionsManager::class,
			[ 'getOptions', 'listOptionKinds', 'getOptionKinds', 'resetOptions', 'setOption' ]
		);
		// Needs to return something
		$this->userOptionsManagerMock->method( 'getOptions' )->willReturn( [] );

		// Set up callback for UserOptionsManager::getOptionKinds
		$this->userOptionsManagerMock->method( 'getOptionKinds' )
			->willReturnCallback( [ $this, 'getOptionKinds' ] );

		$this->userOptionsManagerMock->method( 'listOptionKinds' )->willReturn(
			[
				'registered',
				'registered-multiselect',
				'registered-checkmatrix',
				'userjs',
				'special',
				'unused'
			]
		);

		$preferencesFactory = $this->createNoOpMock(
			DefaultPreferencesFactory::class,
			[ 'getFormDescriptor' ]
		);
		$preferencesFactory->method( 'getFormDescriptor' )
			->willReturnCallback( [ $this, 'getPreferencesFormDescription' ] );

		$this->mTested = new ApiOptions( $main, 'options', $this->userOptionsManagerMock, $preferencesFactory );

		$this->mergeMwGlobalArrayValue( 'wgDefaultUserOptions', [
			'testradio' => 'option1',
		] );
	}

	public function getPreferencesFormDescription() {
		$preferences = [];

		foreach ( [ 'name', 'willBeNull', 'willBeEmpty', 'willBeHappy' ] as $k ) {
			$preferences[$k] = [
				'type' => 'text',
				'section' => 'test',
				'label' => "\u{00A0}",
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
			'label' => "\u{00A0}",
			'prefix' => 'testmultiselect-',
			'default' => [],
		];

		$preferences['testradio'] = [
			'type' => 'radio',
			'options' => [ 'Option 1' => 'option1', 'Option 2' => 'option2' ],
			'section' => 'test',
		];

		return $preferences;
	}

	/**
	 * @param mixed $unused
	 * @param IContextSource $context
	 * @param array|null $options
	 *
	 * @return array
	 */
	public function getOptionKinds( $unused, IContextSource $context, $options = null ) {
		// Match with above.
		$kinds = [
			'name' => 'registered',
			'willBeNull' => 'registered',
			'willBeEmpty' => 'registered',
			'willBeHappy' => 'registered',
			'testradio' => 'registered',
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
		$this->mUserMock->method( 'getRequest' )->willReturn( $this->mContext->getRequest() );

		$this->mTested->execute();

		return $this->mTested->getResult()->getResultData( null, [ 'Strip' => 'all' ] );
	}

	public function testNoToken() {
		$request = $this->getSampleRequest( [ 'token' => null ] );

		$this->expectException( ApiUsageException::class );
		$this->executeQuery( $request );
	}

	public function testAnon() {
		$this->mUserMock
			->method( 'isRegistered' )
			->willReturn( false );

		try {
			$request = $this->getSampleRequest();

			$this->executeQuery( $request );
		} catch ( ApiUsageException $e ) {
			$this->assertTrue( ApiTestCase::apiExceptionHasCode( $e, 'notloggedin' ) );
			return;
		}
		$this->fail( "ApiUsageException was not thrown" );
	}

	public function testNoOptionname() {
		$this->mUserMock->method( 'isRegistered' )->willReturn( true );

		try {
			$request = $this->getSampleRequest( [ 'optionvalue' => '1' ] );

			$this->executeQuery( $request );
		} catch ( ApiUsageException $e ) {
			$this->assertTrue( ApiTestCase::apiExceptionHasCode( $e, 'nooptionname' ) );
			return;
		}
		$this->fail( "ApiUsageException was not thrown" );
	}

	public function testNoChanges() {
		$this->mUserMock->method( 'isRegistered' )->willReturn( true );

		$this->userOptionsManagerMock->expects( $this->never() )
			->method( 'resetOptions' );

		$this->userOptionsManagerMock->expects( $this->never() )
			->method( 'setOption' );

		$this->mUserMock->expects( $this->never() )
			->method( 'saveSettings' );

		try {
			$request = $this->getSampleRequest();

			$this->executeQuery( $request );
		} catch ( ApiUsageException $e ) {
			$this->assertTrue( ApiTestCase::apiExceptionHasCode( $e, 'nochanges' ) );
			return;
		}
		$this->fail( "ApiUsageException was not thrown" );
	}

	public function testReset() {
		$this->mUserMock->method( 'isRegistered' )->willReturn( true );

		$this->userOptionsManagerMock->expects( $this->once() )
			->method( 'resetOptions' );

		$this->userOptionsManagerMock->expects( $this->never() )
			->method( 'setOption' );

		$this->mUserMock->expects( $this->once() )
			->method( 'saveSettings' );

		$request = $this->getSampleRequest( [ 'reset' => '' ] );

		$response = $this->executeQuery( $request );

		$this->assertEquals( self::$Success, $response );
	}

	public function testResetKinds() {
		$this->mUserMock->method( 'isRegistered' )->willReturn( true );

		$this->userOptionsManagerMock->expects( $this->once() )
			->method( 'resetOptions' );

		$this->userOptionsManagerMock->expects( $this->never() )
			->method( 'setOption' );

		$this->mUserMock->expects( $this->once() )
			->method( 'saveSettings' );

		$request = $this->getSampleRequest( [ 'reset' => '', 'resetkinds' => 'registered' ] );

		$response = $this->executeQuery( $request );

		$this->assertEquals( self::$Success, $response );
	}

	public function testResetChangeOption() {
		$this->mUserMock->method( 'isRegistered' )->willReturn( true );

		$this->userOptionsManagerMock->expects( $this->once() )
			->method( 'resetOptions' );

		$this->userOptionsManagerMock->expects( $this->exactly( 2 ) )
			->method( 'setOption' )
			->withConsecutive(
				[ $this->mUserMock, 'willBeHappy', 'Happy' ],
				[ $this->mUserMock, 'name', 'value' ]
			);

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

	/**
	 * @dataProvider provideOptionManupulation
	 */
	public function testOptionManupulation( array $params, array $setOptions, array $result = null,
		$message = ''
	) {
		$this->mUserMock->method( 'isRegistered' )->willReturn( true );

		$this->userOptionsManagerMock->expects( $this->never() )
			->method( 'resetOptions' );

		$args = [];
		foreach ( $setOptions as $setOption ) {
			$args[] = array_merge( [ $this->mUserMock ], $setOption );
		}

		$this->userOptionsManagerMock->expects( $this->exactly( count( $setOptions ) ) )
			->method( 'setOption' )
			->withConsecutive( ...$args );

		if ( $setOptions ) {
			$this->mUserMock->expects( $this->once() )
				->method( 'saveSettings' );
		} else {
			$this->mUserMock->expects( $this->never() )
				->method( 'saveSettings' );
		}

		$request = $this->getSampleRequest( $params );
		$response = $this->executeQuery( $request );

		if ( !$result ) {
			$result = self::$Success;
		}
		$this->assertEquals( $result, $response, $message );
	}

	public function provideOptionManupulation() {
		return [
			[
				[ 'change' => 'userjs-option=1' ],
				[ [ 'userjs-option', '1' ] ],
				null,
				'Setting userjs options',
			],
			[
				[ 'change' => 'willBeNull|willBeEmpty=|willBeHappy=Happy' ],
				[
					[ 'willBeNull', null ],
					[ 'willBeEmpty', '' ],
					[ 'willBeHappy', 'Happy' ],
				],
				null,
				'Basic option setting',
			],
			[
				[ 'change' => 'testradio=option2' ],
				[ [ 'testradio', 'option2' ] ],
				null,
				'Changing radio options',
			],
			[
				[ 'change' => 'testradio' ],
				[ [ 'testradio', null ] ],
				null,
				'Resetting radio options',
			],
			[
				[ 'change' => 'unknownOption=1' ],
				[],
				[
					'options' => 'success',
					'warnings' => [
						'options' => [
							'warnings' => "Validation error for \"unknownOption\": not a valid preference."
						],
					],
				],
				'Unrecognized options should be rejected',
			],
			[
				[ 'change' => 'special=1' ],
				[],
				[
					'options' => 'success',
					'warnings' => [
						'options' => [
							'warnings' => "Validation error for \"special\": cannot be set by this module."
						]
					]
				],
				'Refuse setting special options',
			],
			[
				[
					'change' => 'testmultiselect-opt1=1|testmultiselect-opt2|'
						. 'testmultiselect-opt3=|testmultiselect-opt4=0'
				],
				[
					[ 'testmultiselect-opt1', true ],
					[ 'testmultiselect-opt2', null ],
					[ 'testmultiselect-opt3', false ],
					[ 'testmultiselect-opt4', false ],
				],
				null,
				'Setting multiselect options',
			],
			[
				[ 'optionname' => 'name', 'optionvalue' => 'value' ],
				[ [ 'name', 'value' ] ],
				null,
				'Setting options via optionname/optionvalue'
			],
			[
				[ 'optionname' => 'name' ],
				[ [ 'name', null ] ],
				null,
				'Resetting options via optionname without optionvalue',
			],
		];
	}
}
