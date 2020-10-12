<?php

namespace MediaWiki\Tests\Parser;

use Generator;
use ReflectionClass;

trait SerializationTestTrait {

	/**
	 * Data provider for deserialization test.
	 * - For each supported serialization format defined by ::getSupportedSerializationFormats
	 *  - For each acceptance test instance defined by ::getTestInstancesAndAssertions
	 *   - For each object deserialized from stored file for a particular MW version
	 * @return Generator for [ $expectedObject, $deserializedObject ]
	 */
	public function provideTestDeserialization(): Generator {
		$className = $this->getClassToTest();
		foreach ( $this->getSupportedSerializationFormats() as $serializationFormat ) {
			$serializationUtils = new SerializationTestUtils(
				$this->getSerializedDataPath(),
				$this->getTestInstances(),
				$serializationFormat['ext'],
				$serializationFormat['serializer'],
				$serializationFormat['deserializer']
			);
			foreach ( $serializationUtils->getTestInstances() as $testCaseName => $expectedObject ) {
				$deserializedObjects = $serializationUtils->getDeserializedInstancesForTestCase(
					$className,
					$testCaseName
				);
				foreach ( $deserializedObjects as $deserializedObjectInfo ) {
					yield "{$className}:{$testCaseName}, " .
						"deserialized from {$deserializedObjectInfo->ext}, " .
						"{$deserializedObjectInfo->version}" => [ $expectedObject, $deserializedObjectInfo->object ];
				}
			}
		}
	}

	/**
	 * Tests that $deserialized objects retrieved from stored files for various MW versions
	 * equal to the $expected
	 * @dataProvider provideTestDeserialization
	 * @param object $expected
	 * @param object $deserialized
	 */
	public function testDeserialization( object $expected, object $deserialized ) {
		$this->assertInstanceOf( $this->getClassToTest(), $deserialized );
		$this->validateObjectEquality( $expected, $deserialized );
	}

	/**
	 * Data provider for serialization test.
	 * - For each supported serialization format defined by ::getSupportedSerializationFormats
	 *  - For each acceptance test instance defined by ::getTestInstancesAndAssertions
	 * @return Generator for [ $expected serialized instance, $current master version serialized instance ]
	 */
	public function provideSerialization(): Generator {
		$className = $this->getClassToTest();
		foreach ( $this->getSupportedSerializationFormats() as $serializationFormat ) {
			$serializationUtils = new SerializationTestUtils(
				$this->getSerializedDataPath(),
				$this->getTestInstances(),
				$serializationFormat['ext'],
				$serializationFormat['serializer'],
				$serializationFormat['deserializer']
			);
			foreach ( $serializationUtils->getSerializedInstances()
					  as $testCaseName => $currentSerialized ) {
				$expected = $serializationUtils->getStoredSerializedInstance( $className, $testCaseName );
				yield "{$className}:{$testCaseName}, " .
					"serialized with {$serializationFormat['ext']}" => [ $expected->data, $currentSerialized ];
			}
		}
	}

	/**
	 * Test that the current master $serialized instances are equal to stored $expected instances.
	 * @dataProvider provideSerialization
	 * @param string $expected
	 * @param string $serialized
	 */
	public function testSerialization( string $expected, string $serialized ) {
		$this->assertSame( $expected, $serialized );
	}

	/**
	 * Asserts that all the fields across class hierarchy for
	 * provided objects are equal.
	 * @param object $expected
	 * @param object $actual
	 * @param ReflectionClass|null $class
	 */
	private function validateObjectEquality(
		object $expected,
		object $actual,
		ReflectionClass $class = null
	) {
		if ( $actual == $expected ) {
			return;
		}

		if ( !$class ) {
			$class = new ReflectionClass( $expected );
		}

		foreach ( $class->getProperties() as $prop ) {
			$prop->setAccessible( true );
			$expectedValue = $prop->getValue( $expected );
			$actualValue = $prop->getValue( $actual );
			$this->assertSame( $expectedValue, $actualValue );
		}

		$parent = $class->getParentClass();
		if ( $parent ) {
			$this->validateObjectEquality( $expected, $actual, $parent );
		}
	}

	/**
	 * Data provider for acceptance testing, returning object instances created by current code.
	 * - For each acceptance test instance defined by ::getTestInstancesAndAssertions
	 * @return Generator for [ $instance which to run assertions on, $assertionsCallback ]
	 */
	public function provideCurrentVersionTestObjects(): Generator {
		$className = $this->getClassToTest();
		$testCases = $this->getTestInstancesAndAssertions();
		foreach ( $testCases as $testCaseName => $testCase ) {
			yield "{$className}:{$testCaseName}, current" =>
			[ $testCase['instance'], $testCase['assertions'] ];
		}
	}

	/**
	 * Data provider for acceptance testing, returning instances deserialized
	 * from stored files for various MW versions.
	 * - For each supported serialization format defined by ::getSupportedSerializationFormats
	 *  - For each object deserialized from stored file for a particular MW version
	 * @return Generator for [ $instance which to run assertions on, $assertionsCallback ]
	 */
	public function provideDeserializedTestObjects(): Generator {
		$className = $this->getClassToTest();
		$testCases = $this->getTestInstancesAndAssertions();
		$testObjects = array_map( function ( $testCase ) {
			return $testCase['instance'];
		}, $testCases );
		foreach ( $this->getSupportedSerializationFormats() as $serializationFormat ) {
			$serializationUtils = new SerializationTestUtils(
				$this->getSerializedDataPath(),
				$testObjects,
				$serializationFormat['ext'],
				$serializationFormat['serializer'],
				$serializationFormat['deserializer']
			);
			foreach ( array_keys( $testObjects ) as $testCaseName ) {
				$deserializedObjects = $serializationUtils->getDeserializedInstancesForTestCase(
					$className,
					$testCaseName
				);
				foreach ( $deserializedObjects as $deserializedObjectInfo ) {
					yield "{$className}:{$testCaseName}, " .
						"deserialized from {$deserializedObjectInfo->ext}, " .
						"{$deserializedObjectInfo->version}" =>
					[
						$deserializedObjectInfo->object,
						$testCases[ $testCaseName ]['assertions']
					];
				}
			}
		}
	}

	/**
	 * Tests that assertions in $assertionsCallback succeed on $testInstance.
	 * @see self::getTestInstancesAndAssertions()
	 * @dataProvider provideDeserializedTestObjects
	 * @dataProvider provideCurrentVersionTestObjects
	 * @param object $testInstance
	 * @param callable $assertionsCallback
	 */
	public function testAcceptanceOfDeserializedInstances(
		object $testInstance,
		callable $assertionsCallback
	) {
		call_user_func( $assertionsCallback, $this, $testInstance );
	}

	/**
	 * Returns a map of $testCaseName to an instance to test.
	 * @return array
	 */
	private function getTestInstances(): array {
		return array_map( function ( $testCase ) {
			return $testCase['instance'];
		}, $this->getTestInstancesAndAssertions() );
	}

	/**
	 * @return string the name of the class to test.
	 */
	abstract protected function getClassToTest(): string;

	/**
	 * @return string the path to serialized data.
	 */
	abstract protected function getSerializedDataPath(): string;

	/**
	 * @return array a map of $testCaseName to a map, containing the following keys:
	 *  - 'instance' => an instance of the object to perform assertions on.
	 *  - 'assertions' => a callable that performs assertions on the deserialized objects.
	 *  Callable signature: ( MediaWikiIntegrationTestCase $testCase, object $instance )
	 */
	abstract protected function getTestInstancesAndAssertions(): array;

	/**
	 * Get a list of serialization formats supported by the tested class.
	 * @return string[][] a list of supported serialization formats info map,
	 * containing the following keys:
	 *  - 'ext' => string file extension for stored serializations
	 *  - 'serializer' => callable to serialize objects
	 *  - 'deserializer' => callable to deserialize objects
	 */
	abstract protected function getSupportedSerializationFormats(): array;
}
