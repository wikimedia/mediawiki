<?php

namespace Wikimedia\Tests\Reflection;

use MediaWikiUnitTestCase;
use Wikimedia\Tests\SerializationTestUtils;

/**
 * @covers \Wikimedia\Reflection\GhostFieldAccessTrait
 */
class GhostFieldAccessTraitTest extends MediaWikiUnitTestCase {

	private static function provideUnserializedInstances( string $testCaseName ) {
		// Not using the trait since we only need deserialization tests.
		$serializationTestUtils = new SerializationTestUtils(
			__DIR__ . '/../../../data/GhostFieldAccess',
			[],
			'serialized',
			'serialize',
			'unserialize'
		);
		$instances = $serializationTestUtils
			->getDeserializedInstancesForTestCase( GhostFieldTestClass::class, $testCaseName );
		foreach ( $instances as $instance ) {
			yield "{$instance->version}" => [ $instance->object ];
		}
	}

	public static function provideUnserializedInstancesWithValues() {
		return self::provideUnserializedInstances( 'withValues' );
	}

	/**
	 * @dataProvider provideUnserializedInstancesWithValues
	 */
	public function testUnserializedWithValues( GhostFieldTestClass $instance ) {
		$this->assertSame( 'private_value', $instance->getPrivateField() );
		$this->assertSame( 'protected_value', $instance->getProtectedField() );
		$this->assertSame( 'public_value', $instance->getPublicField() );
	}

	public static function provideUnserializedInstancesWithNulls() {
		return self::provideUnserializedInstances( 'withNulls' );
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
