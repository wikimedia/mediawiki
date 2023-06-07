<?php

use MediaWiki\Preferences\DefaultPreferencesFactory;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\UserOptionsManager;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiOptions
 */
class ApiOptionsTest extends ApiTestCase {
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

		$this->mUserMock = $this->createMock( User::class );

		// No actual DB data
		$this->mUserMock->method( 'getInstanceForUpdate' )->willReturn( $this->mUserMock );

		$this->mUserMock->method( 'isAllowedAny' )->willReturn( true );

		// Create a new context
		$this->mContext = new DerivativeContext( new RequestContext() );
		$this->mContext->getContext()->setTitle( Title::makeTitle( NS_MAIN, 'Test' ) );
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
			} elseif ( str_starts_with( $key, 'userjs-' ) ) {
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
			$this->assertApiErrorCode( 'notloggedin', $e );
			return;
		}
		$this->fail( "ApiUsageException was not thrown" );
	}

	public function testNoOptionname() {
		$this->mUserMock->method( 'isRegistered' )->willReturn( true );
		$this->mUserMock->method( 'isNamed' )->willReturn( true );

		try {
			$request = $this->getSampleRequest( [ 'optionvalue' => '1' ] );

			$this->executeQuery( $request );
		} catch ( ApiUsageException $e ) {
			$this->assertApiErrorCode( 'nooptionname', $e );
			return;
		}
		$this->fail( "ApiUsageException was not thrown" );
	}

	public function testNoChanges() {
		$this->mUserMock->method( 'isRegistered' )->willReturn( true );
		$this->mUserMock->method( 'isNamed' )->willReturn( true );
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
			$this->assertApiErrorCode( 'nochanges', $e );
			return;
		}
		$this->fail( "ApiUsageException was not thrown" );
	}

	public function userScenarios() {
		return [
			[ true, true, false ],
			[ true, false, true ],
		];
	}

	/**
	 * @dataProvider userScenarios
	 */
	public function testReset( $isRegistered, $isNamed, $expectException ) {
		$this->mUserMock->method( 'isRegistered' )->willReturn( $isRegistered );
		$this->mUserMock->method( 'isNamed' )->willReturn( $isNamed );

		if ( $expectException ) {
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'resetOptions' );
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'setOption' );
			$this->mUserMock->expects( $this->never() )->method( 'saveSettings' );
		} else {
			$this->userOptionsManagerMock->expects( $this->once() )->method( 'resetOptions' );
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'setOption' );
			$this->mUserMock->expects( $this->once() )->method( 'saveSettings' );
		}
		$request = $this->getSampleRequest( [ 'reset' => '' ] );
		try {
			$response = $this->executeQuery( $request );
			if ( $expectException ) {
				$this->fail( 'Expected a "notloggedin" error.' );
			} else {
				$this->assertEquals( self::$Success, $response );
			}
		} catch ( ApiUsageException $e ) {
			if ( !$expectException ) {
				$this->fail( 'Unexpected "notloggedin" error.' );
			} else {
				$this->assertApiErrorCode( 'notloggedin', $e );
			}
		}
	}

	/**
	 * @dataProvider userScenarios
	 */
	public function testResetKinds( $isRegistered, $isNamed, $expectException ) {
		$this->mUserMock->method( 'isRegistered' )->willReturn( $isRegistered );
		$this->mUserMock->method( 'isNamed' )->willReturn( $isNamed );
		if ( $expectException ) {
			$this->mUserMock->expects( $this->never() )->method( 'saveSettings' );
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'resetOptions' );
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'setOption' );
		} else {
			$this->userOptionsManagerMock->expects( $this->once() )->method( 'resetOptions' );
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'setOption' );
			$this->mUserMock->expects( $this->once() )->method( 'saveSettings' );
		}
		$request = $this->getSampleRequest( [ 'reset' => '', 'resetkinds' => 'registered' ] );
		try {
			$response = $this->executeQuery( $request );
			if ( $expectException ) {
				$this->fail( "Expected an ApiUsageException" );
			} else {
				$this->assertEquals( self::$Success, $response );
			}
		} catch ( ApiUsageException $e ) {
			if ( !$expectException ) {
				throw $e;
			}
			$this->assertNotNull( $e->getMessageObject() );
			$this->assertApiErrorCode( 'notloggedin', $e );
		}
	}

	/**
	 * @dataProvider userScenarios
	 */
	public function testResetChangeOption( $isRegistered, $isNamed, $expectException ) {
		$this->mUserMock->method( 'isRegistered' )->willReturn( $isRegistered );
		$this->mUserMock->method( 'isNamed' )->willReturn( $isNamed );

		if ( $expectException ) {
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'resetOptions' );
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'setOption' );
			$this->mUserMock->expects( $this->never() )->method( 'saveSettings' );
		} else {
			$this->userOptionsManagerMock->expects( $this->once() )->method( 'resetOptions' );
			$this->userOptionsManagerMock->expects( $this->exactly( 2 ) )
				->method( 'setOption' )
				->withConsecutive(
					[ $this->mUserMock, 'willBeHappy', 'Happy' ],
					[ $this->mUserMock, 'name', 'value' ]
				);
			$this->mUserMock->expects( $this->once() )->method( 'saveSettings' );
		}

		$args = [
			'reset' => '',
			'change' => 'willBeHappy=Happy',
			'optionname' => 'name',
			'optionvalue' => 'value'
		];

		try {
			$response = $this->executeQuery( $this->getSampleRequest( $args ) );

			if ( $expectException ) {
				$this->fail( "Expected an ApiUsageException" );
			} else {
				$this->assertEquals( self::$Success, $response );
			}
		} catch ( ApiUsageException $e ) {
			if ( !$expectException ) {
				throw $e;
			}
			$this->assertNotNull( $e->getMessageObject() );
			$this->assertApiErrorCode( 'notloggedin', $e );
		}
	}

	/**
	 * @dataProvider provideOptionManupulation
	 */
	public function testOptionManupulation( array $params, array $setOptions, array $result = null,
		$message = ''
	) {
		$this->mUserMock->method( 'isRegistered' )->willReturn( true );
		$this->mUserMock->method( 'isNamed' )->willReturn( true );
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

	public static function provideOptionManupulation() {
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
			[
				[ 'optionname' => 'name', 'optionvalue' => str_repeat( '测试', 16383 ) ],
				[],
				[
					'options' => 'success',
					'warnings' => [
						'options' => [
							'warnings' => 'Validation error for "name": value too long (no more than 65,530 bytes allowed).'
						],
					],
				],
				'Options with too long value should be rejected',
			],
		];
	}
}
