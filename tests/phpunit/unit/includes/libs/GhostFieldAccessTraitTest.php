<?php

namespace Wikimedia\Tests\Reflection;

use MediaWikiUnitTestCase;
use Wikimedia\Reflection\GhostFieldTestClass;
use Wikimedia\Tests\SerializationTestUtils;

/**
 * @covers \Wikimedia\Reflection\GhostFieldAccessTrait
 * @package Wikimedia\Tests\Reflection
 */
class GhostFieldAccessTraitTest extends MediaWikiUnitTestCase {

	private function provideUnserializedInstances( string $testCaseName ) {
		// Not using the trait since we only need deserialization tests.
		$serializationTestUtils = new SerializationTestUtils(
			__DIR__ . '/../../../data/GhostFieldAccess',
			[],
			'serialized',
			'serialize',
			'unserialize'
		);
		$instances = $serializationTestUtils
			->getDeserializedInstancesForTestCase( 'GhostFieldTestClass', $testCaseName );
		foreach ( $instances as $instance ) {
			yield "{$instance->version}" => [ $instance->object ];
		}
	}

	public function provideUnserializedInstancesWithValues() {
		return $this->provideUnserializedInstances( 'withValues' );
	}

	/**
	 * @dataProvider provideUnserializedInstancesWithValues
	 */
	public function testUnserializedWithValues( GhostFieldTestClass $instance ) {
		$this->assertSame( 'private_value', $instance->getPrivateField() );
		$this->assertSame( 'protected_value', $instance->getProtectedField() );
		$this->assertSame( 'public_value', $instance->getPublicField() );
	}

	public function provideUnserializedInstancesWithNulls() {
		return $this->provideUnserializedInstances( 'withNulls' );
	}

	/**
	 * @dataProvider provideUnserializedInstancesWithNulls
	 */
	public function testUnserializedWithNulls( GhostFieldTestClass $instance ) {
		$this->assertNull( $instance->getPrivateField() );
		$this->assertNull( $instance->getProtectedField() );
		$this->assertNull( $instance->getPublicField() );
	}
}
