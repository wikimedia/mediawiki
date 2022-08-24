<?php

// phpcs:disable MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingParamTag -- Traits are not excluded

namespace Wikimedia\Tests;

use Generator;
use ReflectionClass;

trait SerializationTestTrait {

	/**
	 * Data provider for deserialization test.
	 * - For each supported serialization format defined by ::getSupportedSerializationFormats
	 *  - For each acceptance test instance defined by ::getTestInstancesAndAssertions
	 *   - For each object deserialized from stored file for a particular MW version
	 * @return Generator for [ callable $deserializer, object $expectedObject, string $dataToDeserialize ]
	 */
	public function provideTestDeserialization(): Generator {
		$className = $this->getClassToTest();
		foreach ( $this->getSupportedSerializationFormats() as $serializationFormat ) {
			$serializationUtils = new SerializationTestUtils(
				$this->getSerializedDataPath(),
				$this->getTestInstances( $this->getTestInstancesAndAssertions() ),
				$serializationFormat['ext'],
				$serializationFormat['serializer'],
				$serializationFormat['deserializer']
			);
			foreach ( $serializationUtils->getTestInstances() as $testCaseName => $expectedObject ) {
				$deserializationFixtures = $serializationUtils->getFixturesForTestCase(
					$className,
					$testCaseName
				);
				foreach ( $deserializationFixtures as $deserializedObjectInfo ) {
					yield "{$className}:{$testCaseName}, " .
						"deserialized from {$deserializedObjectInfo->ext}, " .
						"{$deserializedObjectInfo->version}" =>
							[ $serializationFormat['deserializer'], $expectedObject, $deserializedObjectInfo->data ];
				}
			}
		}
	}

	/**
	 * Tests that $deserialized objects retrieved from stored files for various MW versions
	 * equal to the $expected
	 * @dataProvider provideTestDeserialization
	 */
	public function testDeserialization( callable $deserializer, object $expected, string $data ) {
		$deserialized = $deserializer( $data );
		$this->assertInstanceOf( $this->getClassToTest(), $deserialized );
		$this->validateObjectEquality( $expected, $deserialized );
	}

	/**
	 * Data provider for serialization test.
	 * - For each supported serialization format defined by ::getSupportedSerializationFormats
	 *  - For each acceptance test instance defined by ::getTestInstancesAndAssertions
	 * @return Generator for [ callable $serializer, string $expectedSerialization, object $testInstanceToSerialize ]
	 */
	public function provideSerialization(): Generator {
		$className = $this->getClassToTest();
		foreach ( $this->getSupportedSerializationFormats() as $serializationFormat ) {
			$serializationUtils = new SerializationTestUtils(
				$this->getSerializedDataPath(),
				$this->getTestInstances( $this->getTestInstancesAndAssertions() ),
				$serializationFormat['ext'],
				$serializationFormat['serializer'],
				$serializationFormat['deserializer']
			);
			foreach ( $serializationUtils->getTestInstances() as $testCaseName => $testInstance ) {
				$expected = $serializationUtils->getStoredSerializedInstance( $className, $testCaseName );

				if ( $expected->data === null ) {
					// The fixture file is missing. This will be detected and reported elsewhere.
					// No need to cause an error here.
					continue;
				}

				yield "{$className}:{$testCaseName}, " .
					"serialized with {$serializationFormat['ext']}" =>
						[
							$serializationFormat['serializer'],
							$serializationFormat['deserializer'],
							$expected->data,
							$testInstance
						];
			}
		}
	}

	/**
	 * Test that the current master $serialized instances are
	 * equal to stored $expected instances.
	 * Serialization formats might change in backwards compatible ways
	 * (in particular, php 8.1 orders protected instance variables differently
	 * than earlier php), so do the comparision on the deserialized version.
	 * @dataProvider provideSerialization
	 */
	public function testSerialization( callable $serializer, callable $deserializer, string $expected, object $testInstance ) {
		$serTestInstance = $serializer( $testInstance );
		$deserExpected = $deserializer( $expected );
		$this->assertNotEmpty( $deserExpected );
		$deserTestInstance = $deserializer( $serTestInstance );
		$this->assertNotEmpty( $deserTestInstance );

		$this->validateObjectEquality( $deserExpected, $deserTestInstance );
	}

	/**
	 * Data provider for serialization round trip test.
	 * - For each supported serialization format defined by ::getSupportedSerializationFormats
	 *  - For each test instance defined by ::getTestInstances
	 * @return Generator for [ object $instance, callable $serializer, callable $deserializer ]
	 */
	public function provideSerializationRoundTrip(): Generator {
		$testCases = $this->getTestInstancesAndAssertions();
		$className = $this->getClassToTest();
		foreach ( $this->getSupportedSerializationFormats() as $serializationFormat ) {
			foreach ( $testCases as $testCaseName => [ 'instance' => $instance ] ) {
				yield "{$className}:{$testCaseName}, " .
					"serialized with {$serializationFormat['ext']}" => [
						$instance,
						$serializationFormat['serializer'],
						$serializationFormat['deserializer']
					];
			}
		}
	}

	/**
	 * Test that the $expected instance can be serialized and successfully be deserialized again.
	 *
	 * @dataProvider provideSerializationRoundTrip
	 */
	public function testSerializationRoundTrip(
		object $instance,
		callable $serializer,
		callable $deserializer
	) {
		$blob = $serializer( $instance );
		$this->assertNotEmpty( $blob );

		$actual = $deserializer( $blob );
		$this->assertNotEmpty( $actual );

		$this->validateObjectEquality( $instance, $actual );
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
		if ( !$class ) {
			$class = new ReflectionClass( $expected );
		}

		foreach ( $class->getProperties() as $prop ) {
			$prop->setAccessible( true );
			$expectedValue = $prop->getValue( $expected );
			$actualValue = $prop->getValue( $actual );
			$this->assertSame( $expectedValue, $actualValue, $prop->getName() );
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
		$testObjects = $this->getTestInstances( $testCases );
		foreach ( $this->getSupportedSerializationFormats() as $serializationFormat ) {
			$serializationUtils = new SerializationTestUtils(
				$this->getSerializedDataPath(),
				$testObjects,
				$serializationFormat['ext'],
				$serializationFormat['serializer'],
				$serializationFormat['deserializer']
			);
			foreach ( $testCases as $testCaseName => [ 'assertions' => $assertions ] ) {
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
						$assertions
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
	 */
	public function testAcceptanceOfDeserializedInstances(
		object $testInstance,
		callable $assertionsCallback
	) {
		call_user_func( $assertionsCallback, $this, $testInstance );
	}

	/**
	 * Returns a map of $testCaseName to an instance to test.
	 * @param array[] $instancesAndAssertions
	 * @return array
	 */
	private function getTestInstances( array $instancesAndAssertions ): array {
		return array_map( static function ( $testCase ) {
			return $testCase['instance'];
		}, $instancesAndAssertions );
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
