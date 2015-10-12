<?php
use MediaWiki\Services\ServicePool;

/**
 * @covers MediaWiki\Services\ServicePool
 *
 * @group MediaWiki
 */
class ServicePoolTest extends PHPUnit_Framework_TestCase {

	public function constructService( $name, $text ) {
		$obj = new stdClass();
		$obj->name = $name;
		$obj->text = $text;
		return $obj;
	}

	public function testGetActiveServices() {
		$pool = new ServicePool( array( $this, 'constructService' ) );

		$this->assertEmpty( $pool->getActiveInstances() );

		$serviceA = $pool->getService( 'A', 'test' );
		$active1 = $pool->getActiveInstances();
		$this->assertCount( 1, $active1 );
		$this->assertContains( $serviceA, $active1 );

		$serviceB = $pool->getService( 'B', 'test' );
		$active2 = $pool->getActiveInstances();
		$this->assertCount( 2, $active2 );
		$this->assertContains( $serviceA, $active2 );
		$this->assertContains( $serviceB, $active2 );
	}

	public function testGetService() {
		$pool = new ServicePool(
			array( $this, 'constructService' ),
			function( $params ) { // ID: use first param
				return $params[0];
			},
			function( $params ) { // normalize: make first param upper case
				$params[0] = strtoUpper( $params[0] );
				return $params;
			}
		);

		$serviceA = $pool->getService( 'A', 'foo' );
		$this->assertSame( 'A', $serviceA->name );

		$serviceA2 = $pool->getService( 'a', 'booooo' );
		$this->assertSame( $serviceA, $serviceA2 );
		$this->assertSame( 'foo', $serviceA2->text );

		$serviceB = $pool->getService( 'b', 'bar' );
		$this->assertNotSame( $serviceA, $serviceB );
		$this->assertSame( 'B', $serviceB->name );
	}

	public function testDestroy() {
		$destructible = $this->getMock( 'MediaWiki\Services\DestructibleService' );
		$destructible->expects( $this->once() )
			->method( 'destroy' );

		$pool = new ServicePool( function() use ( $destructible ) {
			return $destructible;
		} );

		// create the service
		$pool->getService( 'Foo' );

		// destroy the container
		$pool->destroy();

		$this->setExpectedException( 'MediaWiki\Services\ContainerDisabledException' );
		$pool->getService( 'Foo' );
	}

}
