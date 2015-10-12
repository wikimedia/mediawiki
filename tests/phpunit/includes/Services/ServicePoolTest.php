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
		$pool = new ServicePool( [ $this, 'constructService' ] );

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
			[ $this, 'constructService' ],
			function( array $params ) { // ID: use first param
				return $params[0];
			},
			function( array $params ) { // normalize: make first param upper case
				$params[0] = strtoUpper( $params[0] );
				return $params;
			}
		);

		$serviceA = $pool->getService( 'AA', 'foo' );
		$this->assertSame( 'AA', $serviceA->name );

		$serviceA2 = $pool->getService( 'aa', 'booooo' );
		$this->assertSame( $serviceA, $serviceA2 );
		$this->assertSame( 'foo', $serviceA2->text );

		$serviceB = $pool->getService( 'bb', 'bar' );
		$this->assertNotSame( $serviceA, $serviceB );
		$this->assertSame( 'BB', $serviceB->name );
	}

	public function testGetService_generic_id() {
		$pool = new ServicePool(
			[ $this, 'constructService' ]
		);

		$serviceA = $pool->getService( 'AA', 'foo' );
		$this->assertSame( 'AA', $serviceA->name );

		$serviceA = $pool->getService( 'aa', 'foo' );
		$this->assertSame( 'aa', $serviceA->name );

		$serviceA = $pool->getService( 'aa', 'boo' );
		$this->assertSame( 'boo', $serviceA->text );

		$serviceA2 = $pool->getService( 'aa', 'boo' );
		$this->assertSame( $serviceA, $serviceA2 );
	}

	public function testDestroy() {
		$pool = new ServicePool( function() {
			$destructible = $this->getMock( 'MediaWiki\Services\DestructibleService' );
			$destructible->expects( $this->once() )
				->method( 'destroy' );
			return $destructible;
		} );

		// create the service
		$pool->getService( 'Foo' );

		// destroy the container
		$pool->destroy();

		$this->setExpectedException( 'MediaWiki\Services\ContainerDisabledException' );
		$pool->getService( 'Foo' );
	}

	public function testClear() {
		$pool = new ServicePool(
			function( $name, $destroyExpected ) {
				$destructible = $this->getMock( 'MediaWiki\Services\DestructibleService' );

				$destructible->expects( $this->exactly( $destroyExpected ? 1 : 0 ) )
					->method( 'destroy' );

				return $destructible;
			},

			function( array $params ) {
				// only the name counts, the $destroyExpected is ignored
				return $params[0];
			}
		);

		// create the service
		$oldInstance = $pool->getService( 'Foo', true );

		// destroy the container
		$pool->clear();

		$newInstance = $pool->getService( 'Foo', false );

		$this->assertNotSame( $oldInstance, $newInstance );
	}

}
