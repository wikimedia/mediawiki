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
		$this->setExpectedException( 'RuntimeException' );

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

	public function testRedefineService_fail_undefined() {
		$services = $this->newServiceContainer();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$this->setExpectedException( 'RuntimeException' );

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

		$this->setExpectedException( 'RuntimeException' );

		$services->redefineService( $name, function() use ( $theService ) {
			return $theService;
		} );
	}

}
