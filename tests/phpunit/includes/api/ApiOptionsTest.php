<?php

namespace MediaWiki\Tests\Api;

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiOptions;
use MediaWiki\Api\ApiUsageException;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Preferences\DefaultPreferencesFactory;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsManager;
use MediaWiki\User\User;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiOptions
 */
class ApiOptionsTest extends ApiTestCase {
	use MockAuthorityTrait;

	/** @var MockObject */
	private $mUserMock;
	/** @var MockObject|UserOptionsManager */
	private $userOptionsManagerMock;
	/** @var ApiOptions */
	private $mTested;
	/** @var array */
	private $mSession;
	/** @var DerivativeContext */
	private $mContext;

	private const SUCCESS = [ 'options' => 'success' ];

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
			[ 'getOptions', 'resetOptionsByName', 'setOption', 'isOptionGlobal' ]
		);
		// Needs to return something
		$this->userOptionsManagerMock->method( 'getOptions' )->willReturn( [] );

		$preferencesFactory = $this->createNoOpMock(
			DefaultPreferencesFactory::class,
			[ 'getFormDescriptor', 'listResetKinds', 'getResetKinds', 'getOptionNamesForReset' ]
		);
		$preferencesFactory->method( 'getFormDescriptor' )
			->willReturnCallback( $this->getPreferencesFormDescription( ... ) );
		$preferencesFactory->method( 'listResetKinds' )->willReturn(
			[
				'registered',
				'registered-multiselect',
				'registered-checkmatrix',
				'userjs',
				'special',
				'unused'
			]
		);
		$preferencesFactory->method( 'getResetKinds' )
			->willReturnCallback( $this->getResetKinds( ... ) );
		$preferencesFactory->method( 'getOptionNamesForReset' )
			->willReturn( [] );

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
	public function getResetKinds( $unused, IContextSource $context, $options = null ) {
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

	private function executeQuery( array $request ) {
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
			->method( 'resetOptionsByName' );

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

	public static function provideUserScenarios() {
		return [
			[ true, true, false ],
			[ true, false, true ],
		];
	}

	/**
	 * @dataProvider provideUserScenarios
	 */
	public function testReset( $isRegistered, $isNamed, $expectException ) {
		$this->mUserMock->method( 'isRegistered' )->willReturn( $isRegistered );
		$this->mUserMock->method( 'isNamed' )->willReturn( $isNamed );

		if ( $expectException ) {
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'resetOptionsByName' );
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'setOption' );
			$this->mUserMock->expects( $this->never() )->method( 'saveSettings' );
		} else {
			$this->userOptionsManagerMock->expects( $this->once() )->method( 'resetOptionsByName' );
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'setOption' );
			$this->mUserMock->expects( $this->once() )->method( 'saveSettings' );
		}
		$request = $this->getSampleRequest( [ 'reset' => '' ] );
		try {
			$response = $this->executeQuery( $request );
			if ( $expectException ) {
				$this->fail( 'Expected a "notloggedin" error.' );
			} else {
				$this->assertEquals( self::SUCCESS, $response );
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
	 * @dataProvider provideUserScenarios
	 */
	public function testResetKinds( $isRegistered, $isNamed, $expectException ) {
		$this->mUserMock->method( 'isRegistered' )->willReturn( $isRegistered );
		$this->mUserMock->method( 'isNamed' )->willReturn( $isNamed );
		if ( $expectException ) {
			$this->mUserMock->expects( $this->never() )->method( 'saveSettings' );
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'resetOptionsByName' );
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'setOption' );
		} else {
			$this->userOptionsManagerMock->expects( $this->once() )->method( 'resetOptionsByName' );
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'setOption' );
			$this->mUserMock->expects( $this->once() )->method( 'saveSettings' );
		}
		$request = $this->getSampleRequest( [ 'reset' => '', 'resetkinds' => 'registered' ] );
		try {
			$response = $this->executeQuery( $request );
			if ( $expectException ) {
				$this->fail( "Expected an ApiUsageException" );
			} else {
				$this->assertEquals( self::SUCCESS, $response );
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
	 * @dataProvider provideUserScenarios
	 */
	public function testResetChangeOption( $isRegistered, $isNamed, $expectException ) {
		$this->mUserMock->method( 'isRegistered' )->willReturn( $isRegistered );
		$this->mUserMock->method( 'isNamed' )->willReturn( $isNamed );

		if ( $expectException ) {
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'resetOptionsByName' );
			$this->userOptionsManagerMock->expects( $this->never() )->method( 'setOption' );
			$this->mUserMock->expects( $this->never() )->method( 'saveSettings' );
		} else {
			$this->userOptionsManagerMock->expects( $this->once() )->method( 'resetOptionsByName' );
			$expectedOptions = [
				'willBeHappy' => 'Happy',
				'name' => 'value',
			];
			$this->userOptionsManagerMock->expects( $this->exactly( count( $expectedOptions ) ) )
				->method( 'setOption' )
				->willReturnCallback( function ( $user, $oname, $val ) use ( &$expectedOptions ) {
					$this->assertSame( $this->mUserMock, $user );
					$this->assertArrayHasKey( $oname, $expectedOptions );
					$this->assertSame( $expectedOptions[$oname], $val );
					unset( $expectedOptions[$oname] );
				} );
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
				$this->assertEquals( self::SUCCESS, $response );
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
	 * @dataProvider provideOptionManipulation
	 */
	public function testOptionManipulation(
		array $params, array $setOptions, ?array $result = null, ?bool $isOptionGlobal = false
	) {
		$this->mUserMock->method( 'isRegistered' )->willReturn( true );
		$this->mUserMock->method( 'isNamed' )->willReturn( true );
		$this->userOptionsManagerMock->expects( $this->never() )
			->method( 'resetOptionsByName' );

		$expectedOptions = [];
		foreach ( $setOptions as [ $opt, $val ] ) {
			$expectedOptions[$opt] = $val;
		}
		$this->userOptionsManagerMock->expects( $this->exactly( count( $setOptions ) ) )
			->method( 'setOption' )
			->willReturnCallback( function ( $user, $oname, $val, $global ) use ( &$expectedOptions, $params ) {
				$this->assertSame( $this->mUserMock, $user );
				$this->assertArrayHasKey( $oname, $expectedOptions );
				$this->assertSame( $expectedOptions[$oname], $val );
				unset( $expectedOptions[$oname] );

				if ( !isset( $params['global'] ) ) {
					$expectedGlobalValue = UserOptionsManager::GLOBAL_IGNORE;
				} else {
					$expectedGlobalValue = $params['global'];
				}
				$this->assertSame( $expectedGlobalValue, $global );
			} );
		$this->userOptionsManagerMock->method( 'isOptionGlobal' )
			->with( $this->mUserMock )
			->willReturn( $isOptionGlobal );

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
			$result = self::SUCCESS;
		}
		$this->assertEquals( $result, $response );
	}

	public static function provideOptionManipulation() {
		return [
			'Setting userjs options' => [
				[ 'change' => 'userjs-option=1' ],
				[ [ 'userjs-option', '1' ] ],
			],
			'Basic option setting' => [
				[ 'change' => 'willBeNull|willBeEmpty=|willBeHappy=Happy' ],
				[
					[ 'willBeNull', null ],
					[ 'willBeEmpty', '' ],
					[ 'willBeHappy', 'Happy' ],
				],
			],
			'Changing radio options' => [
				[ 'change' => 'testradio=option2' ],
				[ [ 'testradio', 'option2' ] ],
			],
			'Resetting radio options' => [
				[ 'change' => 'testradio' ],
				[ [ 'testradio', null ] ],
			],
			'Unrecognized options should be rejected' => [
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
			],
			'Refuse setting special options' => [
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
			],
			'Setting multiselect options' => [
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
			],
			'Setting options via optionname/optionvalue' => [
				[ 'optionname' => 'name', 'optionvalue' => 'value' ],
				[ [ 'name', 'value' ] ],
			],
			'Resetting options via optionname without optionvalue' => [
				[ 'optionname' => 'name' ],
				[ [ 'name', null ] ],
			],
			'Options with too long value should be rejected' => [
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
			],
			'global param is set to ignore and option is global' => [
				[ 'optionname' => 'name', 'optionvalue' => 'value', 'global' => 'ignore' ],
				[],
				[
					'options' => 'success',
					'warnings' => [
						'options' => [
							'warnings' => 'Option "name" is globally overridden. You can use "global=update" to ' .
								'change the option globally, or "global=override" to set a local override.'
						],
					],
				],
				true,
			],
			'global param is set to update' => [
				[ 'optionname' => 'name', 'optionvalue' => 'value', 'global' => 'update' ],
				[ [ 'name', 'value' ] ],
			],
			'global param is set to override' => [
				[ 'optionname' => 'name', 'optionvalue' => 'value', 'global' => 'override' ],
				[ [ 'name', 'value' ] ],
			],
			'global param is set to create' => [
				[ 'optionname' => 'name', 'optionvalue' => 'value', 'global' => 'create' ],
				[ [ 'name', 'value' ] ],
			],
		];
	}
}
