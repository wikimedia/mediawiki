<?php
use MediaWiki\Services\ServiceContainer;

/**
 * @covers MediaWiki\Services\ServiceContainer
 *
 * @group MediaWiki
 */
class ServiceContainerTest extends PHPUnit_Framework_TestCase {

	private function newServiceContainer( $extraArgs = [] ) {
		return new ServiceContainer( $extraArgs );
	}

	public function testGetServiceNames() {
		$services = $this->newServiceContainer();
		$names = $services->getServiceNames();

		$this->assertInternalType( 'array', $names );
		$this->assertEmpty( $names );

		$name = 'TestService92834576';
		$services->defineService( $name, function() {
			return null;
		} );

		$names = $services->getServiceNames();
		$this->assertContains( $name, $names );
	}

	public function testHasService() {
		$services = $this->newServiceContainer();

		$name = 'TestService92834576';
		$this->assertFalse( $services->hasService( $name ) );

		$services->defineService( $name, function() {
			return null;
		} );

		$this->assertTrue( $services->hasService( $name ) );
	}

	public function testGetService() {
		$services = $this->newServiceContainer( [ 'Foo' ] );

		$theService = new stdClass();
		$name = 'TestService92834576';
		$count = 0;

		$services->defineService(
			$name,
			function( $actualLocator, $extra ) use ( $services, $theService, &$count ) {
				$count++;
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				PHPUnit_Framework_Assert::assertSame( $extra, 'Foo' );
				return $theService;
			}
		);

		$this->assertSame( $theService, $services->getService( $name ) );

		$services->getService( $name );
		$this->assertSame( 1, $count, 'instantiator should be called exactly once!' );
	}

	public function testGetService_fail_unknown() {
		$services = $this->newServiceContainer();

		$name = 'TestService92834576';

		$this->setExpectedException( 'MediaWiki\Services\NoSuchServiceException' );

		$services->getService( $name );
	}

	public function testPeekService() {
		$services = $this->newServiceContainer();

		$services->defineService(
			'Foo',
			function() {
				return new stdClass();
			}
		);

		$services->defineService(
			'Bar',
			function() {
				return new stdClass();
			}
		);

		// trigger instantiation of Foo
		$services->getService( 'Foo' );

		$this->assertInternalType(
			'object',
			$services->peekService( 'Foo' ),
			'Peek should return the service object if it had been accessed before.'
		);

		$this->assertNull(
			$services->peekService( 'Bar' ),
			'Peek should return null if the service was never accessed.'
		);
	}

	public function testPeekService_fail_unknown() {
		$services = $this->newServiceContainer();

		$name = 'TestService92834576';

		$this->setExpectedException( 'MediaWiki\Services\NoSuchServiceException' );

		$services->peekService( $name );
	}

	public function testDefineService() {
		$services = $this->newServiceContainer();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$services->defineService( $name, function( $actualLocator ) use ( $services, $theService ) {
			PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
			return $theService;
		} );

		$this->assertTrue( $services->hasService( $name ) );
		$this->assertSame( $theService, $services->getService( $name ) );
	}

	public function testDefineService_fail_duplicate() {
		$services = $this->newServiceContainer();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$services->defineService( $name, function() use ( $theService ) {
			return $theService;
		} );

		$this->setExpectedException( 'MediaWiki\Services\ServiceAlreadyDefinedException' );

		$services->defineService( $name, function() use ( $theService ) {
			return $theService;
		} );
	}

	public function testApplyWiring() {
		$services = $this->newServiceContainer();

		$wiring = [
			'Foo' => function() {
				return 'Foo!';
			},
			'Bar' => function() {
				return 'Bar!';
			},
		];

		$services->applyWiring( $wiring );

		$this->assertSame( 'Foo!', $services->getService( 'Foo' ) );
		$this->assertSame( 'Bar!', $services->getService( 'Bar' ) );
	}

	public function testImportWiring() {
		$services = $this->newServiceContainer();

		$wiring = [
			'Foo' => function() {
				return 'Foo!';
			},
			'Bar' => function() {
				return 'Bar!';
			},
			'Car' => function() {
				return 'FUBAR!';
			},
		];

		$services->applyWiring( $wiring );

		$newServices = $this->newServiceContainer();

		// define a service before importing, so we can later check that
		// existing service instances survive importWiring()
		$newServices->defineService( 'Car', function() {
			return 'Car!';
		} );

		// force instantiation
		$newServices->getService( 'Car' );

		// Define another service, so we can later check that extra wiring
		// is not lost.
		$newServices->defineService( 'Xar', function() {
			return 'Xar!';
		} );

		// import wiring, but skip `Bar`
		$newServices->importWiring( $services, [ 'Bar' ] );

		$this->assertNotContains( 'Bar', $newServices->getServiceNames(), 'Skip `Bar` service' );
		$this->assertSame( 'Foo!', $newServices->getService( 'Foo' ) );

		// import all wiring, but preserve existing service instance
		$newServices->importWiring( $services );

		$this->assertContains( 'Bar', $newServices->getServiceNames(), 'Import all services' );
		$this->assertSame( 'Bar!', $newServices->getService( 'Bar' ) );
		$this->assertSame( 'Car!', $newServices->getService( 'Car' ), 'Use existing service instance' );
		$this->assertSame( 'Xar!', $newServices->getService( 'Xar' ), 'Predefined services are kept' );
	}

	public function testLoadWiringFiles() {
		$services = $this->newServiceContainer();

		$wiringFiles = [
			__DIR__ . '/TestWiring1.php',
			__DIR__ . '/TestWiring2.php',
		];

		$services->loadWiringFiles( $wiringFiles );

		$this->assertSame( 'Foo!', $services->getService( 'Foo' ) );
		$this->assertSame( 'Bar!', $services->getService( 'Bar' ) );
	}

	public function testLoadWiringFiles_fail_duplicate() {
		$services = $this->newServiceContainer();

		$wiringFiles = [
			__DIR__ . '/TestWiring1.php',
			__DIR__ . '/./TestWiring1.php',
		];

		// loading the same file twice should fail, because
		$this->setExpectedException( 'MediaWiki\Services\ServiceAlreadyDefinedException' );

		$services->loadWiringFiles( $wiringFiles );
	}

	public function testRedefineService() {
		$services = $this->newServiceContainer( [ 'Foo' ] );

		$theService1 = new stdClass();
		$name = 'TestService92834576';

		$services->defineService( $name, function() {
			PHPUnit_Framework_Assert::fail(
				'The original instantiator function should not get called'
			);
		} );

		// redefine before instantiation
		$services->redefineService(
			$name,
			function( $actualLocator, $extra ) use ( $services, $theService1 ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				PHPUnit_Framework_Assert::assertSame( 'Foo', $extra );
				return $theService1;
			}
		);

		// force instantiation, check result
		$this->assertSame( $theService1, $services->getService( $name ) );
	}

	public function testRedefineService_disabled() {
		$services = $this->newServiceContainer( [ 'Foo' ] );

		$theService1 = new stdClass();
		$name = 'TestService92834576';

		$services->defineService( $name, function() {
			return 'Foo';
		} );

		// disable the service. we should be able to redefine it anyway.
		$services->disableService( $name );

		$services->redefineService( $name, function() use ( $theService1 ) {
			return $theService1;
		} );

		// force instantiation, check result
		$this->assertSame( $theService1, $services->getService( $name ) );
	}

	public function testRedefineService_fail_undefined() {
		$services = $this->newServiceContainer();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$this->setExpectedException( 'MediaWiki\Services\NoSuchServiceException' );

		$services->redefineService( $name, function() use ( $theService ) {
			return $theService;
		} );
	}

	public function testRedefineService_fail_in_use() {
		$services = $this->newServiceContainer( [ 'Foo' ] );

		$theService = new stdClass();
		$name = 'TestService92834576';

		$services->defineService( $name, function() {
			return 'Foo';
		} );

		// create the service, so it can no longer be redefined
		$services->getService( $name );

		$this->setExpectedException( 'MediaWiki\Services\CannotReplaceActiveServiceException' );

		$services->redefineService( $name, function() use ( $theService ) {
			return $theService;
		} );
	}

	public function testDisableService() {
		$services = $this->newServiceContainer( [ 'Foo' ] );

		$destructible = $this->getMock( 'MediaWiki\Services\DestructibleService' );
		$destructible->expects( $this->once() )
			->method( 'destroy' );

		$services->defineService( 'Foo', function() use ( $destructible ) {
			return $destructible;
		} );
		$services->defineService( 'Bar', function() {
			return new stdClass();
		} );
		$services->defineService( 'Qux', function() {
			return new stdClass();
		} );

		// instantiate Foo and Bar services
		$services->getService( 'Foo' );
		$services->getService( 'Bar' );

		// disable service, should call destroy() once.
		$services->disableService( 'Foo' );

		// disabled service should still be listed
		$this->assertContains( 'Foo', $services->getServiceNames() );

		// getting other services should still work
		$services->getService( 'Bar' );

		// disable non-destructible service, and not-yet-instantiated service
		$services->disableService( 'Bar' );
		$services->disableService( 'Qux' );

		$this->assertNull( $services->peekService( 'Bar' ) );
		$this->assertNull( $services->peekService( 'Qux' ) );

		// disabled service should still be listed
		$this->assertContains( 'Bar', $services->getServiceNames() );
		$this->assertContains( 'Qux', $services->getServiceNames() );

		$this->setExpectedException( 'MediaWiki\Services\ServiceDisabledException' );
		$services->getService( 'Qux' );
	}

	public function testDisableService_fail_undefined() {
		$services = $this->newServiceContainer();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$this->setExpectedException( 'MediaWiki\Services\NoSuchServiceException' );

		$services->redefineService( $name, function() use ( $theService ) {
			return $theService;
		} );
	}

	public function testDestroy() {
		$services = $this->newServiceContainer();

		$destructible = $this->getMock( 'MediaWiki\Services\DestructibleService' );
		$destructible->expects( $this->once() )
			->method( 'destroy' );

		$services->defineService( 'Foo', function() use ( $destructible ) {
			return $destructible;
		} );

		$services->defineService( 'Bar', function() {
			return new stdClass();
		} );

		// create the service
		$services->getService( 'Foo' );

		// destroy the container
		$services->destroy();

		$this->setExpectedException( 'MediaWiki\Services\ContainerDisabledException' );
		$services->getService( 'Bar' );
	}

}
