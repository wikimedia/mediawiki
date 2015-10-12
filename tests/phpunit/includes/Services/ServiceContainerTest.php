<?php
use MediaWiki\Services\ServiceContainer;

/**
 * @covers MediaWiki\Services\ServiceContainer
 *
 * @group MediaWiki
 */
class ServiceContainerTest extends PHPUnit_Framework_TestCase {

	private function newServiceContainer( $extraArgs = array() ) {
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
		$services = $this->newServiceContainer( array( 'Foo' ) );

		$theService = new stdClass();
		$name = 'TestService92834576';

		$services->defineService(
			$name,
			function( $actualLocator, $extra ) use ( $services, $theService ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				PHPUnit_Framework_Assert::assertSame( $extra, 'Foo' );
				return $theService;
			}
		);

		$this->assertSame( $theService, $services->getService( $name ) );
	}

	public function testGetService_fail_unknown() {
		$services = $this->newServiceContainer();

		$name = 'TestService92834576';

		$this->setExpectedException( 'InvalidArgumentException' );

		$services->getService( $name );
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

		$this->setExpectedException( 'RuntimeException' );

		$services->defineService( $name, function() use ( $theService ) {
			return $theService;
		} );
	}

	public function testApplyWiring() {
		$services = $this->newServiceContainer();

		$wiring = array(
			'Foo' => function() {
				return 'Foo!';
			},
			'Bar' => function() {
				return 'Bar!';
			},
		);

		$services->applyWiring( $wiring );

		$this->assertSame( 'Foo!', $services->getService( 'Foo' ) );
		$this->assertSame( 'Bar!', $services->getService( 'Bar' ) );
	}

	public function testLoadWiringFiles() {
		$services = $this->newServiceContainer();

		$wiringFiles = array(
			__DIR__ . '/TestWiring1.php',
			__DIR__ . '/TestWiring2.php',
		);

		$services->loadWiringFiles( $wiringFiles );

		$this->assertSame( 'Foo!', $services->getService( 'Foo' ) );
		$this->assertSame( 'Bar!', $services->getService( 'Bar' ) );
	}

	public function testLoadWiringFiles_fail_duplicate() {
		$services = $this->newServiceContainer();

		$wiringFiles = array(
			__DIR__ . '/TestWiring1.php',
			__DIR__ . '/./TestWiring1.php',
		);

		// loading the same file twice should fail, because
		$this->setExpectedException( 'RuntimeException' );

		$services->loadWiringFiles( $wiringFiles );
	}

	public function testReplaceService() {
		$services = $this->newServiceContainer( array( 'Foo' ) );

		$theService1 = new stdClass();
		$theService2 = new stdClass();
		$name = 'TestService92834576';

		$services->defineService( $name, function() {
			PHPUnit_Framework_Assert::fail(
				'The original constructor callback should not get called'
			);
		} );

		// replace before instantiation
		$services->replaceService(
			$name,
			function( $actualLocator, $extra ) use ( $services, $theService1 ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				PHPUnit_Framework_Assert::assertSame( 'Foo', $extra );
				return $theService1;
			},
			function ( $instance, $actualLocator, $extra ) {
				PHPUnit_Framework_Assert::fail(
					'Cleanup callback should not be called if the services wasn\'t yet instantiated'
				);
			}
		);

		// force instantiation, check result
		$this->assertSame( $theService1, $services->getService( $name ) );

		// replace after instantiation
		$cleanedUpInstance = null;
		$services->replaceService(
			$name,
			function( $actualLocator ) use ( $services, $theService2 ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				return $theService2;
			},
			function ( $instance, $actualLocator, $extra ) use ( $services, &$cleanedUpInstance ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				PHPUnit_Framework_Assert::assertSame( 'Foo', $extra );
				$cleanedUpInstance = $instance;
			}
		);

		// check cleanup
		$this->assertSame(
			$theService1,
			$cleanedUpInstance,
			'Cleanup callback should have been called for previous service instance'
		);

		// check instantiation
		$this->assertSame( $theService2, $services->getService( $name ) );

		// replace without cleanup
		$services->replaceService(
			$name,
			function( $actualLocator ) use ( $services, $theService1 ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				return $theService1;
			}
		);

		// check instantiation
		$this->assertSame( $theService1, $services->getService( $name ) );
	}

	public function testReplaceService_fail_undefined() {
		$services = $this->newServiceContainer();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$this->setExpectedException( 'RuntimeException' );

		$services->replaceService( $name, function() use ( $theService ) {
			return $theService;
		} );
	}

	public function testResetService() {
		$services = $this->newServiceContainer( array( 'Foo' ) );

		$name = 'TestService92834576';
		$counter = 0;

		$services->defineService( $name, function() use ( &$counter ) {
			$obj = new stdClass();
			$obj->number = ++$counter;
			return $obj;
		} );

		// reset before instantiation
		$services->resetService(
			$name,
			function ( $instance ) {
				PHPUnit_Framework_Assert::fail(
					'Cleanup callback should not be called if the services wasn\'t yet instantiated'
				);
			}
		);

		// force instantiation, check result
		$service1 = $services->getService( $name );
		$this->assertSame( 1, $service1->number, 'First service instance' );

		// reset after instantiation
		$cleanedUpInstance = null;
		$services->resetService(
			$name,
			function ( $instance, $actualLocator, $extra ) use ( $services, &$cleanedUpInstance ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				PHPUnit_Framework_Assert::assertSame( 'Foo', $extra );
				$cleanedUpInstance = $instance;
			}
		);

		// check cleanup
		$this->assertSame(
			$service1,
			$cleanedUpInstance,
			'Cleanup callback should have been called for previous service instance'
		);

		// check instantiation
		$service2 = $services->getService( $name );
		$this->assertNotSame( $service1, $service2, 'New service instance expected after reset' );
		$this->assertSame( 2, $service2->number, 'New service instance expected after reset' );

		// reset without cleanup
		$services->resetService(
			$name
		);

		// check instantiation
		$service3 = $services->getService( $name );
		$this->assertNotSame( $service2, $service3, 'New service instance expected after reset' );
		$this->assertSame( 3, $service3->number, 'New service instance expected after reset' );
	}

	public function testResetService_fail_undefined() {
		$services = $this->newServiceContainer();

		$name = 'TestService92834576';

		$this->setExpectedException( 'RuntimeException' );

		$services->resetService( $name );
	}

	public function testWrapService() {
		$services = $this->newServiceContainer();

		$serviceOne = new stdClass();
		$serviceTwo = new stdClass();
		$serviceThree = new stdClass();
		$name = 'TestService92834576';

		$services->defineService( $name, function() use ( $serviceOne ) {
			return $serviceOne;
		} );

		$services->wrapService( $name,
			function( $actualService, $actualLocator )
			use ( $serviceOne, $serviceTwo, $services ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				PHPUnit_Framework_Assert::assertSame( $serviceOne, $actualService );

				return $serviceTwo;
			}
		);

		$this->assertSame( $serviceTwo, $services->getService( $name ) );

		$services->wrapService( $name,
			function( $actualService, $actualLocator )
			use ( $serviceTwo, $serviceThree, $services ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				PHPUnit_Framework_Assert::assertSame( $serviceTwo, $actualService );
				return $serviceThree;
			}
		);

		$this->assertSame( $serviceThree, $services->getService( $name ) );
	}

	public function testWrapService_fail_undefined() {
		$services = $this->newServiceContainer();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$this->setExpectedException( 'RuntimeException' );

		$services->wrapService( $name, function() use ( $theService ) {
			return $theService;
		} );
	}

}
