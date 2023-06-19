<?php

namespace MediaWiki\HookContainer {

	use Error;
	use InvalidArgumentException;
	use MediaWiki\Tests\Unit\DummyServicesTrait;
	use MediaWikiUnitTestCase;
	use stdClass;
	use UnexpectedValueException;
	use Wikimedia\ScopedCallback;
	use Wikimedia\TestingAccessWrapper;

	class HookContainerTest extends MediaWikiUnitTestCase {
		use DummyServicesTrait;

		private const HANDLER_FUNCTION = FooClass::class . '::fooStaticMethod';

		private const HANDLER_REGISTRATION = [
			'extensionPath' => __DIR__,
			'handler' => [
				'name' => 'TestHookHandler',
				'class' => 'FooExtension\Hooks'
			]
		];

		/*
		 * Creates a new hook container with StaticHookRegistry and empty ObjectFactory
		 */
		private function newHookContainer(
			$oldHooks = null, $newHooks = null, $deprecatedHooksArray = []
		) {
			if ( $oldHooks === null ) {
				$oldHooks[ 'FoobarActionComplete' ][] = static function ( &$called ) {
					$called[] = 11;
				};
			}
			if ( $newHooks === null ) {
				$handler = [ 'handler' => [
					'name' => 'FooExtension-FooActionHandler',
					'class' => 'FooExtension\\Hooks',
					'services' => [] ]
				];
				$newHooks = [ 'FooActionComplete' => [ $handler ] ];
			}

			// fake object factory
			$objectFactory = $this->getDummyObjectFactory(
				[
					'SomeService' => static function () {
						return new stdClass();
					}
				]
			);

			$registry = new StaticHookRegistry( $oldHooks, $newHooks, $deprecatedHooksArray );
			$hookContainer = new HookContainer( $registry, $objectFactory );
			return $hookContainer;
		}

		public static function provideRegister() {
			return [
				'function' => [ 'strtoupper', 'strtoupper' ],
				'object' => [ new \FooExtension\Hooks(), 'FooExtension\Hooks::onFooActionComplete' ],
				'object and method' => [ [ new FooClass(), 'fooMethod' ], 'MediaWiki\HookContainer\FooClass::fooMethod' ],
				'extension' => [
					self::HANDLER_REGISTRATION,
					'FooExtension\Hooks::onFooActionComplete'
				],
				'callable referencing a class that extends an unknown class' => [
					[ 'MediaWiki\Tests\BrokenClass', 'aMethod' ],
					'MediaWiki\Tests\BrokenClass::aMethod'
				],
			];
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::register
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * @dataProvider provideRegister
		 */
		public function testRegister( $handler, $expected ) {
			$hookContainer = $this->newHookContainer( [], [
				'FooActionComplete' => [ $handler ]
			], [] );

			$handlers = $hookContainer->getHandlerDescriptions( 'FooActionComplete' );

			$this->assertSame( $expected, $handlers[0] );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::getHandlerDescriptions
		 */
		public function testGetHandlerDescriptions() {
			$handler = 'MediaWiki\HookContainer\FooClass::fooStaticMethod';
			$expected = [ $handler ];

			$hookContainer = $this->newHookContainer(
				[
					'BarActionComplete' => [ $handler ]
				],
				[
					'FooActionComplete' => [ $handler ]
				], [] );

			$this->assertSame( $expected, $hookContainer->getHandlerDescriptions( 'FooActionComplete' ) );
			$this->assertSame( $expected, $hookContainer->getHandlerDescriptions( 'BarActionComplete' ) );

			// Fire the hooks, then check again
			$hookContainer->run( 'FooActionComplete', [ 1 ] );
			$hookContainer->run( 'BarActionComplete', [ 1 ] );

			$this->assertSame( $expected, $hookContainer->getHandlerDescriptions( 'FooActionComplete' ) );
			$this->assertSame( $expected, $hookContainer->getHandlerDescriptions( 'BarActionComplete' ) );
		}

		/**
		 * Values returned: hook, handlersToRegister, expectedReturn
		 */
		public static function provideGetHandlerDescriptions() {
			return [
				'NoHandlersExist' => [
					'MWTestHook',
					null,
					0,
					0
				],
				'SuccessfulHandlerReturn' => [
					'FooActionComplete',
					[
						'handler' => [
							'name' => 'FooExtension-FooActionHandler',
							'class' => 'FooExtension\\Hooks',
							'services' => [],
						],
					],
					1,
					1
				],
				'SkipDeprecated' => [
					'FooActionCompleteDeprecated',
					[
						'handler' => [
							'name' => 'FooExtension-FooActionHandler',
							'class' => 'FooExtension\\Hooks',
							'services' => [],
						],
						'deprecated' => true,
					],
					1,
					0
				],
			];
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::salvage
		 */
		public function testSalvage() {
			$firstHookContainer = $this->newHookContainer( [], [] );
			$secondHookContainer = $this->newHookContainer();

			$firstHookContainer->register( 'TestHook', self::HANDLER_FUNCTION );

			$secondHookContainer->salvage( $firstHookContainer );

			$this->assertTrue( $secondHookContainer->isRegistered( 'TestHook' ) );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::salvage
		 */
		public function testSalvageThrows() {
			$firstHookContainer = $this->newHookContainer( [], [] );
			$secondHookContainer = $this->newHookContainer();

			$secondHookContainer->register( 'TestHook', self::HANDLER_FUNCTION );

			$this->expectException( \MWException::class );
			$secondHookContainer->salvage( $firstHookContainer );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::isRegistered
		 * @covers \MediaWiki\HookContainer\HookContainer::register
		 * @covers \MediaWiki\HookContainer\HookContainer::clear
		 */
		public function testIsRegistered() {
			$hookContainer = $this->newHookContainer(
				[ 'XyzHook' => [ self::HANDLER_FUNCTION ] ],
				[ 'MWTestHook' => [ self::HANDLER_REGISTRATION ] ],
			);

			$hookContainer->register( 'AbcHook', self::HANDLER_FUNCTION );

			$this->assertFalse( $hookContainer->isRegistered( 'XyzzyHook' ) );

			$this->assertTrue( $hookContainer->isRegistered( 'XyzHook' ) );
			$this->assertTrue( $hookContainer->isRegistered( 'MWTestHook' ) );
			$this->assertTrue( $hookContainer->isRegistered( 'AbcHook' ) );

			$hookContainer->clear( 'AbcHook' );
			$hookContainer->clear( 'XyzHook' );
			$hookContainer->clear( 'MWTestHook' );

			$this->assertFalse( $hookContainer->isRegistered( 'XyzHook' ) );
			$this->assertFalse( $hookContainer->isRegistered( 'MWTestHook' ) );
			$this->assertFalse( $hookContainer->isRegistered( 'AbcHook' ) );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::scopedRegister
		 */
		public function testScopedRegister() {
			$hookContainer = $this->newHookContainer();
			$reset = $hookContainer->scopedRegister( 'MWTestHook', [ new FooClass(),
				'fooMethod'
			] );
			$this->assertTrue( $hookContainer->isRegistered( 'MWTestHook' ) );
			ScopedCallback::consume( $reset );
			$this->assertFalse( $hookContainer->isRegistered( 'MWTestHook' ) );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::scopedRegister
		 */
		public function testScopedRegisterTwoHandlers() {
			$hookContainer = $this->newHookContainer();
			$called1 = $called2 = false;
			$reset1 = $hookContainer->scopedRegister( 'MWTestHook',
				static function () use ( &$called1 ) {
					$called1 = true;
				}
			);
			$reset2 = $hookContainer->scopedRegister( 'MWTestHook',
				static function () use ( &$called2 ) {
					$called2 = true;
				}
			);
			$hookContainer->run( 'MWTestHook' );
			$this->assertTrue( $called1 );
			$this->assertTrue( $called2 );

			$called1 = $called2 = false;
			ScopedCallback::consume( $reset1 );
			$hookContainer->run( 'MWTestHook' );
			$this->assertFalse( $called1 );
			$this->assertTrue( $called2 );

			$called1 = $called2 = false;
			ScopedCallback::consume( $reset2 );
			$hookContainer->run( 'MWTestHook' );
			$this->assertFalse( $called1 );
			$this->assertFalse( $called2 );
		}

		/**
		 * Register handlers with scopedRegister() and register()
		 * @covers \MediaWiki\HookContainer\HookContainer::scopedRegister
		 */
		public function testHandlersRegisteredWithScopedRegisterAndRegister() {
			$hookContainer = $this->newHookContainer();
			$numCalls = 0;
			$hookContainer->register( 'MWTestHook', static function () use ( &$numCalls ) {
				$numCalls++;
			} );
			$reset = $hookContainer->scopedRegister( 'MWTestHook', static function () use ( &$numCalls ) {
				$numCalls++;
			} );

			// handlers registered in 2 different ways
			$this->assertCount( 2, $hookContainer->getHandlerDescriptions( 'MWTestHook' ) );
			$hookContainer->run( 'MWTestHook' );
			$this->assertEquals( 2, $numCalls );

			// Remove one of the handlers that increments $called
			ScopedCallback::consume( $reset );
			$this->assertCount( 1, $hookContainer->getHandlerDescriptions( 'MWTestHook' ) );

			$numCalls = 0;
			$hookContainer->run( 'MWTestHook' );
			$this->assertSame( 1, $numCalls );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::getHandlerDescriptions
		 * @covers \MediaWiki\HookContainer\HookContainer::getHandlerCallbacks
		 * @dataProvider provideGetHandlerDescriptions
		 */
		public function testGetHandlers(
			string $hook,
			?array $handlerToRegister,
			int $expectedDescriptions,
			int $expectedCallbacks
		) {
			if ( $handlerToRegister ) {
				$hooks = [ $hook => [ $handlerToRegister ] ];
			} else {
				$hooks = [];
			}
			$fakeDeprecatedHooks = [
				'FooActionCompleteDeprecated' => [ 'deprecatedVersion' => '1.35' ]
			];
			$hookContainer = $this->newHookContainer( [], $hooks, $fakeDeprecatedHooks );

			$descriptions = $hookContainer->getHandlerDescriptions( $hook );
			$this->assertCount(
				$expectedDescriptions,
				$descriptions,
				'getHandlerDescriptions()'
			);

			$this->expectDeprecationAndContinue( '/getHandlerCallbacks/' );
			$callbacks = $hookContainer->getHandlerCallbacks( $hook );
			$this->assertCount(
				$expectedCallbacks,
				$callbacks,
				'getHandlerCallbacks()'
			);

			foreach ( $callbacks as $clbk ) {
				$this->assertIsCallable( $clbk );
			}
		}

		public static function provideRunConfigured() {
			$fooObj = new FooClass();
			$closure = static function ( &$count ) {
				$count++;
			};
			$extra	= 10;
			return [
				// Callables
				'Function' => [ 'fooGlobalFunction' ],
				'Object and method' => [ [ $fooObj, 'fooMethod' ] ],
				'Class name and static method' => [ [ 'MediaWiki\HookContainer\FooClass', 'fooStaticMethod' ] ],
				'static method' => [ 'MediaWiki\HookContainer\FooClass::fooStaticMethod' ],
				'Closure' => [ $closure ],

				// Shorthand
				'Object' => [ $fooObj ],

				// No-ops
				'NOOP' => [ HookContainer::NOOP, 1 ],
			];
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * @dataProvider provideRunConfigured
		 */
		public function testRunConfigured( $handler, $expectedCount = 2 ) {
			$hookContainer = $this->newHookContainer( [ 'Increment' => [ $handler ] ] );

			$count = 1;
			$hookValue = $hookContainer->run( 'Increment', [ &$count ] );
			$this->assertTrue( $hookValue );
			$this->assertSame( $expectedCount, $count );
		}

		public static function provideRunDeprecatedStyle() {
			$fooObj = new FooClass();
			$closure = static function ( &$count ) {
				$count++;
			};
			$extra	= 10;
			return [
				// Handlers with extra data attached
				'static method with extra data' => [
					[ 'MediaWiki\HookContainer\FooClass::fooStaticMethodWithExtra', $extra ],
					11
				],
				'Object and method with extra data' => [ [ [ $fooObj, 'fooMethodWithExtra' ], $extra ], 11 ],
				'Function extra data' => [ [ 'fooGlobalFunctionWithExtra', $extra ], 11 ],
				'Closure with extra data' => [
					[
						static function ( int $inc, &$count ) {
							$count += $inc;
						},
						10
					],
					11
				],

				// No-ops
				'empty array' => [ [], 1 ],
				'null' => [ null, 1 ],
				'false' => [ false, 1 ],

				// Strange edge cases
				'Object in array without method' => [ [ $fooObj ] ],
				'Callable in array' => [ [ [ $fooObj, 'fooMethod' ] ] ],
				'Closure in array with no extra data' => [ [ $closure ] ],
				'Function in array' => [ [ 'fooGlobalFunction' ] ],
				'Function in array in array' => [ [ [ 'fooGlobalFunction' ] ] ],
				'static method as array in array' => [
					[ [ 'MediaWiki\HookContainer\FooClass', 'fooStaticMethod' ] ]
				],
				'Object and fully-qualified non-static method' => [
					[ $fooObj, 'MediaWiki\HookContainer\FooClass::fooMethod' ]
				]
			];
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * @dataProvider provideRunDeprecatedStyle
		 */
		public function testRunDeprecatedStyle( $handler, $expectedCount = 2 ) {
			$hookContainer = $this->newHookContainer( [ 'Increment' => [ $handler ] ] );

			$this->expectDeprecationAndContinue( '/Deprecated handler style/' );

			$count = 1;
			$hookValue = $hookContainer->run( 'Increment', [ &$count ] );
			$this->assertTrue( $hookValue );
			$this->assertSame( $expectedCount, $count );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * @dataProvider provideRunConfigured
		 * @dataProvider provideRunExtensionHook
		 */
		public function testRegisterAndRun( $handler, $expectedCount = 2 ) {
			$hookContainer = $this->newHookContainer( [], [] );
			$hookContainer->register( 'Increment', $handler );

			$count = 1;
			$hookValue = $hookContainer->run( 'Increment', [ &$count ] );
			$this->assertTrue( $hookValue );
			$this->assertSame( $expectedCount, $count );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * @dataProvider provideRunDeprecatedStyle
		 */
		public function testRegisterDeprecatedStyle( $handler ) {
			$hookContainer = $this->newHookContainer( [], [] );

			// Force the handler list to be initialized, so register() will normalize the handler immediately.
			$hookContainer->run( 'Increment' );

			$this->expectDeprecationAndContinue( '/Deprecated handler style for hook/' );
			$hookContainer->register( 'Increment', $handler );
		}

		/**
		 * Values returned: hook, handler, handler arguments, options
		 */
		public static function provideRegisterAndRunCallback() {
			$fooObj = new FooClass();
			return [
				// Callables
				'Function' => [ 'fooGlobalFunction' ],
				'Object and method' => [ [ $fooObj, 'fooMethod' ] ],
				'Class name and static method' => [ [ 'MediaWiki\HookContainer\FooClass', 'fooStaticMethod' ] ],
				'static method' => [ 'MediaWiki\HookContainer\FooClass::fooStaticMethod' ],
				'Closure' => [
					static function ( &$count ) {
						$count++;
					}
				],

				// Extension-style handler
				'Extension handler' => [ self::HANDLER_REGISTRATION ],

				// NOTE: hook handlers with extra data are not supported for callbacks!
			];
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::getHandlerCallbacks
		 * @dataProvider provideRegisterAndRunCallback
		 */
		public function testRegisterAndRunCallback( $handler, $expectedCount = 2 ) {
			$hookContainer = $this->newHookContainer( [], [] );
			$hookContainer->register( 'Increment', $handler );

			$this->expectDeprecationAndContinue( '/getHandlerCallbacks/' );

			$count = 1;
			foreach ( $hookContainer->getHandlerCallbacks( 'Increment' ) as $callback ) {
				$callback( $count );
			}
			$this->assertSame( $expectedCount, $count );
		}

		/**
		 * Values returned: hook, handler, handler arguments, options
		 */
		public static function provideRunExtensionHook() {
			return [
				[ self::HANDLER_REGISTRATION ],
			];
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * @dataProvider provideRunExtensionHook
		 */
		public function testRunExtensionHook( array $handler, $expectedCount = 1 ) {
			$hookContainer = $this->newHookContainer( [], [ 'X\\Y::Increment' => [ $handler ] ] );

			$count = 0;
			$hookValue = $hookContainer->run( 'X\\Y::Increment', [ &$count ] );
			$this->assertTrue( $hookValue );
			$this->assertSame( $expectedCount, $count );
		}

		public static function provideRunFailsWithNoService() {
			$handler = self::HANDLER_REGISTRATION;
			$handler['handler']['services'] = [ 'SomeService' ];

			yield [ $handler ];

			$handler = self::HANDLER_REGISTRATION;
			$handler['handler']['optional_services'] = [ 'SomeService' ];

			yield [ $handler ];
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * @dataProvider provideRunFailsWithNoService
		 */
		public function testRunFailsWithNoService( array $handler ) {
			$hookContainer = $this->newHookContainer( [], [ 'Increment' => [ $handler ] ] );

			$this->expectException( UnexpectedValueException::class );

			$count = 0;
			$options = [ 'noServices' => true ];
			$hookContainer->run( 'Increment', [ &$count ], $options );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 */
		public function testRunOrder() {
			$configured1 = static function ( &$seq ) {
				$seq[] = 'configured1';
			};

			$configured2 = static function ( &$seq ) {
				$seq[] = 'configured2';
			};

			$registered = static function ( &$seq ) {
				$seq[] = 'registered';
			};

			$hookContainer = $this->newHookContainer(
				[ 'Append' => [ $configured1, $configured2 ] ],
				[ 'Append' => [ self::HANDLER_REGISTRATION ] ]
			);

			$hookContainer->register( 'Append', $registered );

			$seq = [ 'start' ];
			$hookContainer->run( 'Append', [ &$seq ] );

			$expected = [ 'start', 'configured1', 'configured2', 'FooExtension', 'registered' ];
			$this->assertSame( $expected, $seq );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * Test HookContainer::run() when the handler returns false
		 */
		public function testRunAbort() {
			$handler1 = [ 'handler' => [
				'name' => 'FooExtension-Abort1',
				'class' => 'FooExtension\\AbortHooks1'
			] ];
			$handler2 = [ 'handler' => [
				'name' => 'FooExtension-Abort2',
				'class' => 'FooExtension\\AbortHooks2'
			] ];
			$handler3 = [ 'handler' => [
				'name' => 'FooExtension-Abort3',
				'class' => 'FooExtension\\AbortHooks3'
			] ];
			$hooks = [
				'Abort' => [
					$handler1,
					$handler2,
					$handler3
				]
			];
			$hookContainer = $this->newHookContainer( [], $hooks );
			$called = [];
			$ret = $hookContainer->run( 'Abort', [ &$called ] );
			$this->assertFalse( $ret );
			$this->assertArrayEquals( [ 1, 2 ], $called );
		}

		public static function provideRegisterDeprecated() {
			// registering a deprecated hook should trigger a warning
			yield [ [ 'deprecatedVersion' => '1.0' ], true ];

			// the silent flag should suppress the warning
			yield [ [ 'deprecatedVersion' => '1.0', 'silent' => true ], false ];
		}

		/**
		 * Test HookContainer::register() successfully registers even when hook is deprecated.
		 * @covers \MediaWiki\HookContainer\HookContainer::register
		 * @dataProvider provideRegisterDeprecated
		 */
		public function testRegisterDeprecated( array $deprecationInfo, bool $expectWarning ) {
			$deprecations = [ 'FooActionComplete' => $deprecationInfo ];

			// Assert we don't get any deprecation warnings during initialization!
			$this->newHookContainer(
				[ 'FooActionComplete' => [ self::HANDLER_FUNCTION ] ],
				[ 'FooActionComplete' => [ self::HANDLER_REGISTRATION ] ],
				$deprecations
			);

			// Make a hook container with no hooks registered yet
			$hookContainer = $this->newHookContainer( [], [], $deprecations );

			// Expected deprecation?
			if ( $expectWarning ) {
				$this->expectDeprecationAndContinue( '/FooActionComplete hook/' );
			}

			$hookContainer->register( 'FooActionComplete', self::HANDLER_FUNCTION );

			// Deprecated hooks should still be functional!
			$this->assertTrue( $hookContainer->isRegistered( 'FooActionComplete' ) );
		}

		/**
		 * Test running deprecated hooks from $wgHooks with the deprecation declared in HookContainer.
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @dataProvider provideRegisterDeprecated
		 */
		public function testRunConfiguredDeprecated( array $deprecationInfo ) {
			$hookContainer = $this->newHookContainer(
				[ 'Increment' => [ self::HANDLER_FUNCTION ] ],
				[],
				[ 'Increment' => $deprecationInfo ]
			);

			// No warning expected when running the hook!
			// Deprecated hooks should still be functional!
			$count = 0;
			$hookContainer->run( 'Increment', [ &$count ] );
			$this->assertSame( 1, $count );
		}

		/**
		 * Test running deprecated hooks from $wgHooks with the deprecation passed in the options parameter.
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @dataProvider provideRegisterDeprecated
		 */
		public function testRunConfiguredDeprecatedWithOption( array $deprecationInfo, bool $expectWarning ) {
			// Assert we don't get any deprecation warnings during initialization!
			$hookContainer = $this->newHookContainer(
				[ 'Increment' => [ self::HANDLER_FUNCTION ] ],
			);

			// Expected deprecation?
			if ( $expectWarning ) {
				$this->expectDeprecationAndContinue( '/Use of Increment hook/' );
			}

			// Deprecated hooks should still be functional!
			$count = 0;
			$hookContainer->run( 'Increment', [ &$count ], $deprecationInfo );
			$this->assertSame( 1, $count );
		}

		/**
		 * Test running deprecated hooks from extensions.
		 *
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 */
		public function testRunHandlerObjectDeprecated() {
			$deprecationInfo = [ 'deprecatedVersion' => '1.0' ];

			// If the handler acknowledges deprecation, it should be skipped
			$knownDeprecated = self::HANDLER_REGISTRATION + [ 'deprecated' => true ];

			$hookContainer = $this->newHookContainer(
				[],
				[ 'Increment' => [ self::HANDLER_REGISTRATION, $knownDeprecated ] ],
				[ 'Increment' => $deprecationInfo ]
			);

			// Deprecated hooks should be functional, the handle that acknowledges deprecation should be skipped.
			// We do not expect deprecation warnings here. They are covered by emitDeprecationWarnings()
			$count = 0;
			$hookContainer->run( 'Increment', [ &$count ] );
			$this->assertSame( 1, $count );
		}

		/**
		 * Test running deprecated hooks from extensions.
		 *
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 */
		public function testRunHandlerObjectDeprecatedWithOption() {
			$deprecationInfo = [ 'deprecatedVersion' => '1.0' ];

			// If the handler acknowledges deprecation, it should be skipped
			$knownDeprecated = self::HANDLER_REGISTRATION + [ 'deprecated' => true ];

			$hookContainer = $this->newHookContainer(
				[],
				[ 'Increment' => [ self::HANDLER_REGISTRATION, $knownDeprecated ] ],
				[ 'Increment' => $deprecationInfo ]
			);

			// We do expect deprecation warnings when the 'deprecationVersion' key is provided in the $options parameter.
			$this->expectDeprecationAndContinue( '/Use of Increment hook/' );

			// Deprecated hooks should be functional, the handle that acknowledges deprecation should be skipped.
			$count = 0;
			$hookContainer->run( 'Increment', [ &$count ], $deprecationInfo );
			$this->assertSame( 1, $count );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::getHookNames
		 */
		public function testGetHookNames() {
			$fooHandler = [ 'handler' => [
				'name' => 'FooHookHandler',
				'class' => 'FooExtension\\Hooks'
			] ];

			$noop = static function () {
				// noop
			};

			$container = $this->newHookContainer(
				[
					'A' => [ $noop ]
				],
				[
					'B' => [ $fooHandler ]
				]
			);

			$container->register( 'C', 'strtoupper' );

			// Ask for a few hooks that have no handlers.
			// Negative caching inside HookHandler should not cause them to be returned from getHookNames
			$container->isRegistered( 'X' );

			$this->expectDeprecationAndContinue( '/getHandlerCallbacks/' );
			$container->getHandlerCallbacks( 'Y' );

			$this->assertArrayEquals( [ 'A', 'B', 'C' ], $container->getHookNames() );

			// make sure we are getting each hook name only once
			$container->register( 'B', 'strtoupper' );
			$container->register( 'A', 'strtoupper' );

			$this->assertArrayEquals( [ 'A', 'B', 'C' ], $container->getHookNames() );
		}

		/**
		 * Values returned: hook, handlersToRegister, options
		 */
		public static function provideRunErrors() {
			// XXX: should also fail: non-function string, empty array
			return [
				'return a string' => [
					static function () {
						return 'string';
					},
					[]
				],
				'abort even though not abortable' => [
					static function () {
						return false;
					},
					[ 'abortable' => false ]
				],
				'callable referencing a class that extends an unknown class' => [
					[ 'MediaWiki\\Tests\\BrokenClass', 'aMethod' ],
					[],
					Error::class
				],
			];
		}

		/**
		 * @dataProvider provideRunErrors
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * Test errors thrown with invalid handlers
		 */
		public function testRunErrors( $handler, $options, $expected = UnexpectedValueException::class ) {
			$hookContainer = $this->newHookContainer();
			$hookContainer->register( 'MWTestHook', $handler );

			$this->filterDeprecated( '/^Returning a string from a hook handler/' );
			$this->expectException( $expected );
			$hookContainer->run( 'MWTestHook', [], $options );
		}

		/**
		 * Values returned: hook, handlersToRegister, options
		 */
		public static function provideRegisterErrors() {
			// XXX: should also fail: non-function string, empty array
			return [
				'a number' => [ 123 ],
				'non-callable string' => [ 'a, b, c' ],
				'array referencing an unknown method' => [ [ self::class, 'thisMethodDoesNotExist' ] ],
				'empty string' => [ '' ],
				'zero' => [ 0 ],
				'true' => [ true ],
				'callable referencing an unknown class' => [
					[ 'FooExtension\DoesNotExist', 'onFoo' ],
					'FooExtension\DoesNotExist::onFoo'
				],
			];
		}

		/**
		 * @dataProvider provideRegisterErrors
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * @covers \MediaWiki\HookContainer\HookContainer::register
		 */
		public function testRegisterErrors( $badHandler ) {
			$hookContainer = $this->newHookContainer();

			// Force the handler list to be initialized, so register() will normalize the handler immediately.
			$hookContainer->run( 'MWTestHook' );

			$this->expectException( InvalidArgumentException::class );
			$hookContainer->register( 'MWTestHook', $badHandler );
		}

		/**
		 * @dataProvider provideRegisterErrors
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 */
		public function testRunWithBadHandlers( $badHandler ) {
			$goodHandler = self::HANDLER_FUNCTION;
			$hookContainer = $this->newHookContainer( [ 'MWTestHook' => [ $badHandler, $goodHandler ] ] );

			// Bad handlers from the constructor should fail silently
			$count = 0;
			$hookContainer->run( 'MWTestHook', [ &$count ] );

			$this->assertSame( 1, $count );
		}

		public static function provideEmitDeprecationWarnings() {
			yield 'Deprecated extension hook' => [
				'$oldHooks' => [],
				'$newHooks' => [ self::HANDLER_REGISTRATION ],
				'$deprecationInfo' => [ 'deprecatedVersion' => '1.35' ],
				'$expectWarning' => true,
			];

			yield 'Deprecated extension hook, silent' => [
				'$oldHooks' => [],
				'$newHooks' => [ self::HANDLER_REGISTRATION ],
				'$deprecationInfo' => [ 'deprecatedVersion' => '1.35', 'silent' => true ],
				'$expectWarning' => false,
			];

			yield 'Deprecated extension hook, acknowledged' => [
				'$oldHooks' => [],
				'$newHooks' => [ self::HANDLER_REGISTRATION + [ 'deprecated' => true ] ],
				'$deprecationInfo' => [ 'deprecatedVersion' => '1.35' ],
				'$expectWarning' => false,
			];

			yield 'Deprecated configured hook' => [
				'$oldHooks' => [ self::HANDLER_FUNCTION ],
				'$newHooks' => [],
				'$deprecationInfo' => [ 'deprecatedVersion' => '1.35' ],
				'$expectWarning' => false, // NOTE: Currently expected to be ignored. This may change.
			];

			yield 'Deprecated configured hook, silent' => [
				'$oldHooks' => [ self::HANDLER_FUNCTION ],
				'$newHooks' => [],
				'$deprecationInfo' => [ 'deprecatedVersion' => '1.35', 'silent' => true ],
				'$expectWarning' => false,
			];
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::emitDeprecationWarnings
		 * @dataProvider provideEmitDeprecationWarnings
		 */
		public function testEmitDeprecationWarnings( $oldHandlers, $newHandlers, $deprecationInfo, $expectWarning ) {
			$hookContainer = $this->newHookContainer(
				[ 'FooActionComplete' => $oldHandlers ],
				[ 'FooActionComplete' => $newHandlers ],
				[ 'FooActionComplete' => $deprecationInfo ]
			);

			if ( $expectWarning ) {
				$this->expectDeprecationAndContinue( '/Hook FooActionComplete was deprecated/' );
			}

			$hookContainer->emitDeprecationWarnings();
			$this->addToAssertionCount( 1 );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::emitDeprecationWarnings
		 */
		public function testEmitDeprecationWarningsSilent() {
			$hooks = [
				'FooActionComplete' => [
					[
						'handler' => 'fooGlobalFunction',
						'extensionPath' => 'fake-extension.json'
					]
				]
			];
			$deprecatedHooksArray = [
				'FooActionComplete' => [
					'deprecatedVersion' => '1.35',
					'silent' => true
				]
			];
			$hookContainer = $this->newHookContainer( [], $hooks, $deprecatedHooksArray );
			$hookContainer->emitDeprecationWarnings();
			$this->assertTrue( true );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::isRegistered
		 * @covers \MediaWiki\HookContainer\HookContainer::getHandlerCallbacks
		 * @covers \MediaWiki\HookContainer\HookContainer::register
		 * @covers \MediaWiki\HookContainer\HookContainer::clear
		 */
		public function testClear() {
			$increment = [ new \FooExtension\Hooks(), 'onIncrement' ];

			$hookContainer = $this->newHookContainer(
				[ 'Increment' => [ $increment ], 'XyzHook' => [ self::HANDLER_FUNCTION ], ],
				[ 'Increment' => [ self::HANDLER_REGISTRATION ], 'FooActionComplete' => [ self::HANDLER_REGISTRATION ] ],
			);

			$hookContainer->register( 'AbcHook', self::HANDLER_FUNCTION );
			$hookContainer->register( 'Increment', static function ( &$count ) {
				$count++;
			} );

			// Check: all three handlers should be called initially.
			$count = 0;
			$hookContainer->run( 'Increment', [ &$count ] );
			$this->assertSame( 3, $count );

			$hookContainer->clear( 'Increment' );

			$this->assertFalse( $hookContainer->isRegistered( 'Increment' ) );
			$this->assertTrue( $hookContainer->isRegistered( 'AbcHook' ) );
			$this->assertTrue( $hookContainer->isRegistered( 'XyzHook' ) );
			$this->assertTrue( $hookContainer->isRegistered( 'FooActionComplete' ) );

			$this->assertCount( 0, $hookContainer->getHandlerDescriptions( 'Increment' ) );
			$this->assertNotEmpty( $hookContainer->getHandlerDescriptions( 'AbcHook' ) );
			$this->assertNotEmpty( $hookContainer->getHandlerDescriptions( 'FooActionComplete' ) );
			$this->assertNotEmpty( $hookContainer->getHandlerDescriptions( 'XyzHook' ) );

			// No more increment!
			$hookContainer->run( 'Increment', [ &$count ] );
			$this->assertSame( 3, $count );

			// When adding a handler again...
			$hookContainer->register( 'Increment', static function ( &$count ) {
				$count = 11;
			} );

			// ...the new handler should be called, but not the old ones.
			$count = 0;
			$hookContainer->run( 'Increment', [ &$count ] );
			$this->assertSame( 11, $count );
			$this->assertTrue( $hookContainer->isRegistered( 'Increment' ) );
		}

		public static function provideMayBeCallable() {
			yield 'function' => [
				'strtoupper',
			];
			yield 'closure' => [
				static function () {
					// noop
				},
			];
			yield 'object and method' => [
				[ new FooClass(), 'fooMethod' ],
			];
			yield 'static method as array' => [
				[ FooClass::class, 'fooStaticMethod', ],
			];
			yield 'static method as string' => [
				'MediaWiki\HookContainer\FooClass::fooStaticMethod',
			];
			yield 'callable referencing a class that extends an unknown class' => [
				[ 'MediaWiki\Tests\BrokenClass', 'aMethod' ],
			];
		}

		public static function provideNotCallable() {
			yield 'object' => [
				new \FooExtension\Hooks(),
			];
			yield 'object and non-existing method' => [
				[ new FooClass(), 'noSuchMethod' ],
			];
			yield 'object and method and extra stuff' => [
				[ new FooClass(), 'fooMethod', 'extra', 'stuff' ],
			];
			yield 'object and method assoc' => [
				[ 'a' => new FooClass(), 'b' => 'fooMethod' ],
			];
			yield 'object and method nested in array' => [
				[ [ new FooClass(), 'fooMethod' ], 'whatever' ],
			];
			yield 'non-existing static method on existing class' => [
				'MediaWiki\HookContainer\FooClass::noSuchMethod',
			];
			yield 'global function with extra data in array' => [
				[ 'strtoupper', 'extra' ],
			];
			yield 'non-existing static method on existing class as array' => [
				[ FooClass::class, 'noSuchMethod' ],
			];
			yield 'non-function text' => [
				'just some text',
			];
			yield 'object in array with no method' => [
				[ new \FooExtension\Hooks() ],
			];
			yield 'callable referencing an unknown class' => [
				[ 'FooExtension\DoesNotExist', 'onFoo' ],
			];
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::mayBeCallable
		 * @dataProvider provideMayBeCallable
		 */
		public function testMayBeCallable_true( $v ) {
			$access = TestingAccessWrapper::newFromClass( HookContainer::class );
			$this->assertTrue( $access->mayBeCallable( $v ) );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::mayBeCallable
		 * @dataProvider provideNotCallable
		 */
		public function testMayBeCallable_false( $v ) {
			$access = TestingAccessWrapper::newFromClass( HookContainer::class );
			$this->assertFalse( $access->mayBeCallable( $v ) );
		}
	}

	// Mock class for different types of handler functions
	class FooClass {

		public function fooMethod( &$count ) {
			$count++;
			return true;
		}

		public function onIncrement( &$count ) {
			$count++;
		}

		public static function fooStaticMethod( &$count ) {
			$count++;
			return null;
		}

		public function fooMethodWithExtra( int $inc, &$count ) {
			$count += $inc;
			return true;
		}

		public static function fooStaticMethodWithExtra( int $inc, &$count ) {
			$count += $inc;
			return null;
		}

		public static function fooMethodReturnValueError() {
			return 'a string';
		}

		public static function onMWTestHook() {
			// noop
		}
	}

}

// Function in global namespace
namespace {

	function fooGlobalFunction( &$count ) {
		$count++;
		return true;
	}

	function fooGlobalFunctionWithExtra( $inc, &$count ) {
		$count += $inc;
	}

}

// Mock Extension
namespace FooExtension {

	class Hooks {

		public function onFooActionComplete() {
			return true;
		}

		public function onMWTest() {
			// noop
		}

		public function onIncrement( &$count ) {
			$count++;
		}

		public function onX_Y__Increment( &$count ) {
			$count++;
		}

		public function onAppend( &$list ) {
			$list[] = 'FooExtension';
		}
	}

	class AbortHooks1 {
		public function onAbort( &$called ) {
			$called[] = 1;
			return true;
		}
	}

	class AbortHooks2 {
		public function onAbort( &$called ) {
			$called[] = 2;
			return false;
		}
	}

	class AbortHooks3 {
		public function onAbort( &$called ) {
			$called[] = 3;
			return true;
		}
	}

}
