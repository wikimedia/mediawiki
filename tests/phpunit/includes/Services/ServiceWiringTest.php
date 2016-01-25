<?php
use MediaWiki\Services\ServiceWiring;

/**
 * @covers MediaWiki\Services\ServiceWiring
 *
 * @group MediaWiki
 */
class ServiceWiringTest extends PHPUnit_Framework_TestCase {

	private function newServiceWiring( $args = array() ) {
		return new ServiceWiring( $args );
	}

	public function testGetServiceNames() {
		$services = $this->newServiceWiring();
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
		$services = $this->newServiceWiring();

		$name = 'TestService92834576';
		$this->assertFalse( $services->hasService( $name ) );

		$services->defineService( $name, function() {
			return null;
		} );

		$this->assertTrue( $services->hasService( $name ) );
	}

	public function testCreateService() {
		$services = $this->newServiceWiring( array( 'Foo' ) );

		$theService = new stdClass();
		$name = 'TestService92834576';

		$services->defineService(
			$name,
			function( $actualLocator, $foo, $bar ) use ( $services, $theService ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				PHPUnit_Framework_Assert::assertSame( $foo, 'Foo' );
				PHPUnit_Framework_Assert::assertSame( $bar, 'Bar' );
				return $theService;
			}
		);

		$this->assertSame( $theService, $services->createService( $name, 'Bar' ) );
	}

	public function testCreateService_fail_unknown() {
		$services = $this->newServiceWiring();

		$name = 'TestService92834576';

		$this->setExpectedException( 'InvalidArgumentException' );

		$services->createService( $name );
	}

	public function testDefineService() {
		$services = $this->newServiceWiring();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$services->defineService( $name, function( $actualLocator ) use ( $services, $theService ) {
			PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
			return $theService;
		} );

		$this->assertTrue( $services->hasService( $name ) );
		$this->assertSame( $theService, $services->createService( $name ) );
	}

	public function testDefineService_fail_duplicate() {
		$services = $this->newServiceWiring();

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
		$services = $this->newServiceWiring();

		$wiring = array(
			'Foo' => function() {
				return 'Foo!';
			},
			'Bar' => function() {
				return 'Bar!';
			},
		);

		$services->applyWiring( $wiring );

		$this->assertSame( 'Foo!', $services->createService( 'Foo' ) );
		$this->assertSame( 'Bar!', $services->createService( 'Bar' ) );
	}

	public function testLoadWiringFiles() {
		$services = $this->newServiceWiring();

		$wiringFiles = array(
			__DIR__ . '/TestWiring1.php',
			__DIR__ . '/TestWiring2.php',
		);

		$services->loadWiringFiles( $wiringFiles );

		$this->assertSame( 'Foo!', $services->createService( 'Foo' ) );
		$this->assertSame( 'Bar!', $services->createService( 'Bar' ) );
	}

	public function testLoadWiringFiles_fail_duplicate() {
		$services = $this->newServiceWiring();

		$wiringFiles = array(
			__DIR__ . '/TestWiring1.php',
			__DIR__ . '/./TestWiring1.php',
		);

		// loading the same file twice should fail, because
		$this->setExpectedException( 'RuntimeException' );

		$services->loadWiringFiles( $wiringFiles );
	}

}
