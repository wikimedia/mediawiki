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

	private $serializationTestUtils;

	protected function setUp() : void {
		parent::setUp();
		// Not using the trait since we only need deserialization tests.
		$this->serializationTestUtils = new SerializationTestUtils(
			__DIR__ . '/../../data/GhostFieldAccess',
			[],
			'serialized',
			'serialize',
			'unserialize'
		);
	}

	private function provideUnserializedInstances( string $testCaseName ) {
		$serializationTestUtils = new SerializationTestUtils(
			__DIR__ . '/../../data/GhostFieldAccess',
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
	 * @param GhostFieldTestClass $instance
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
	 * @param GhostFieldTestClass $instance
	 */
	public function testUnserializedWithNulls( GhostFieldTestClass $instance ) {
		$this->assertNull( $instance->getPrivateField() );
		$this->assertNull( $instance->getProtectedField() );
		$this->assertNull( $instance->getPublicField() );
	}
}
