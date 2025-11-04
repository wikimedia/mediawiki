<?php

namespace MediaWiki\Tests\Unit\Json;

use InvalidArgumentException;
use JsonException;
use JsonSerializable;
use MediaWiki\Json\FormatJson;
use MediaWiki\Json\JsonCodec;
use MediaWiki\Json\JsonConstants;
use MediaWiki\Tests\Mocks\Json\JsonDeserializableSubClass;
use MediaWiki\Tests\Mocks\Json\JsonDeserializableSuperClass;
use MediaWiki\Tests\Mocks\Json\ManagedObjectFactory;
use MediaWiki\Tests\Mocks\Json\SampleContainerObject;
use MediaWiki\Tests\Mocks\Json\SampleObject;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use Psr\Container\ContainerInterface;
use Wikimedia\Assert\PreconditionException;

/**
 * @covers \MediaWiki\Json\JsonCodec
 * @covers \MediaWiki\Json\JsonDeserializableTrait
 */
class JsonCodecTest extends MediaWikiUnitTestCase {

	/** Mock up a services interface. */
	private static function getServices(): ContainerInterface {
		static $services = null;
		if ( !$services ) {
			$services = new class implements ContainerInterface {
				/** @var array */
				private $storage = [];

				public function get( $id ) {
					return $this->storage[$id];
				}

				public function has( $id ): bool {
					return isset( $this->storage[$id] );
				}

				public function set( $id, $value ) {
					$this->storage[$id] = $value;
				}
			};
			$factory = new ManagedObjectFactory();
			$factory->create( "a", 1 );
			$factory->create( "b", 2 );
			$services->set( 'ManagedObjectFactory', $factory );
		}
		return $services;
	}

	private function getCodec(): JsonCodec {
		return new JsonCodec( self::getServices() );
	}

	public static function provideSimpleTypes() {
		yield 'Integer' => [ 1, json_encode( 1 ) ];
		yield 'Boolean' => [ true, json_encode( true ) ];
		yield 'Null' => [ null, json_encode( null ) ];
		yield 'Array' => [ [ 1, 2, 3 ], json_encode( [ 1, 2, 3 ] ) ];
		yield 'Assoc array' => [ [ 'a' => 'b' ], json_encode( [ 'a' => 'b' ] ) ];

		$object = (object)[ 'c' => 'd' ];
		yield 'Object' => [ (array)$object, json_encode( $object ) ];
	}

	public static function provideInvalidJsonData() {
		yield 'Bad string' => [ 'bad string' ];
		yield 'No deserialization metadata' => [ [ 'test' => 'test' ] ];
		yield 'Deserialization metadata, but class not exist' => [ [
			JsonConstants::TYPE_ANNOTATION => 'BadClassNotExist'
		] ];
		yield 'Deserialization metadata, but class is not JsonDeserializable' => [ [
			JsonConstants::TYPE_ANNOTATION => Title::class
		] ];
	}

	/**
	 * @dataProvider provideSimpleTypes
	 */
	public function testSimpleTypesDeserialize( $value, string $serialization ) {
		$this->assertSame( $value, $this->getCodec()->deserialize( $serialization ) );
	}

	public function testActualClassInstanceIsNotJsonObject() {
		// Can be any trivial value class that's suitable to be used in pure unit tests
		$object = UserIdentityValue::newAnonymous( '' );

		// TODO: We probably want this to throw an exception as well
		$array = [ $object ];
		$this->assertSame( $array, $this->getCodec()->deserialize( $array ) );

		$this->expectException( InvalidArgumentException::class );
		$this->getCodec()->deserialize( $object );
	}

	/**
	 * @dataProvider provideSimpleTypes
	 * @dataProvider provideInvalidJsonData
	 */
	public function testInvalidJsonDataForClassExpectation( $jsonData, $simpleJsonData = null ) {
		$jsonData = $simpleJsonData ?? $jsonData;
		$this->expectException( JsonException::class );
		$this->getCodec()->deserialize( $jsonData, JsonDeserializableSuperClass::class );
	}

	public function testExpectedClassMustBeDeserializable() {
		$this->expectException( PreconditionException::class );
		$this->getCodec()->deserialize( '{}', self::class );
	}

	public function testUnexpectedClassDeserialized() {
		$this->expectException( JsonException::class );
		$superClassInstance = new JsonDeserializableSuperClass( 'Godzilla' );
		$this->getCodec()->deserialize(
			$superClassInstance->jsonSerialize(),
			JsonDeserializableSubClass::class
		);
	}

	public function testExpectedClassDeserialized() {
		$subClassInstance = new JsonDeserializableSubClass( 'Godzilla', 'But we are ready!' );
		$this->assertNotNull( $this->getCodec()->deserialize(
			$subClassInstance->jsonSerialize(),
			JsonDeserializableSuperClass::class
		) );
		$this->assertNotNull( $this->getCodec()->deserialize(
			$subClassInstance->jsonSerialize(),
			JsonDeserializableSubClass::class
		) );
	}

	public function testRoundTripSuperClass() {
		$superClassInstance = new JsonDeserializableSuperClass( 'Super Value' );
		$json = $superClassInstance->jsonSerialize();
		$superClassDeserialized = $this->getCodec()->deserialize( $json );
		$this->assertInstanceOf( JsonDeserializableSuperClass::class, $superClassInstance );
		$this->assertSame( $superClassInstance->getSuperClassField(), $superClassDeserialized->getSuperClassField() );
	}

	public function testRoundTripSuperClassObject() {
		$superClassInstance = new JsonDeserializableSuperClass( 'Super Value' );
		$json = (object)$superClassInstance->jsonSerialize();
		$superClassDeserialized = $this->getCodec()->deserialize( $json );
		$this->assertInstanceOf( JsonDeserializableSuperClass::class, $superClassInstance );
		$this->assertInstanceOf( JsonDeserializableSuperClass::class, $superClassDeserialized );
		$this->assertSame( $superClassInstance->getSuperClassField(), $superClassDeserialized->getSuperClassField() );
	}

	public function testRoundTripSubClass() {
		$subClassInstance = new JsonDeserializableSubClass( 'Super Value', 'Sub Value' );
		$json = $subClassInstance->jsonSerialize();
		$superClassDeserialized = $this->getCodec()->deserialize( $json );
		$this->assertInstanceOf( JsonDeserializableSubClass::class, $subClassInstance );
		$this->assertSame( $subClassInstance->getSuperClassField(), $superClassDeserialized->getSuperClassField() );
		$this->assertSame( $subClassInstance->getSubClassField(), $superClassDeserialized->getSubClassField() );
	}

	public function testRoundTripSubClassNested() {
		$subClassInstance1 = new JsonDeserializableSubClass( 'Super Value', 'Sub Value' );
		$superClassInstance1 = new JsonDeserializableSuperClass( 'XYZ' );
		$superClassInstance2 = new JsonDeserializableSuperClass(
			// To be a bit tricky, wrap the embedded instance in an array
			[ $superClassInstance1 ]
		);
		$subClassInstance2 = new JsonDeserializableSubClass(
			$subClassInstance1,
			// Again, we're tricky and wrap this instance in a stdClass object
			(object)[ 'a' => $superClassInstance1 ]
		);
		$json = $this->getCodec()->serialize( $subClassInstance2 );
		$deserialized = $this->getCodec()->deserialize( $json );
		$this->assertEquals( $subClassInstance2, $deserialized );
	}

	public function testArrayRoundTrip() {
		$array = [
			new JsonDeserializableSuperClass( 'Super Value' ),
			new JsonDeserializableSubClass( 'Super Value', 'Sub Value' ),
			42
		];
		// This is pretty bogus, in that it tests the ability of JsonCodec
		// to decode something which *wasn't* generated by JsonCodec, but
		// instead used only json_encode/JsonSerializable.  Still this should
		// work as long as JsonDeserializableTrait is used and the arrays
		// returned contain only primitive types (ie, not nested
		// JsonSerializables)
		$serialized = FormatJson::encode( $array );
		$deserialized = $this->getCodec()->deserializeArray( FormatJson::decode( $serialized ) );
		$this->assertArrayEquals( $array, $deserialized );
	}

	public static function provideValidateSerializable() {
		$classInstance = new class() {
		};
		$serializableClass = new class() implements JsonSerializable {
			public function jsonSerialize(): array {
				return [];
			}
		};
		$badSerializable = new class() implements JsonSerializable {
			public function jsonSerialize(): \stdClass {
				return (object)[];
			}
		};

		yield 'Number' => [ 1, true, null ];
		yield 'Null' => [ null, true, null ];
		yield 'Class' => [ $classInstance, false, '$' ];
		yield 'Empty array' => [ [], true, null ];
		yield 'Empty stdClass' => [ (object)[], true, null ];
		yield 'Non-empty array' => [ [ 1, 2, 3 ], true, null ];
		yield 'Non-empty map' => [ [ 'a' => 'b' ], true, null ];
		yield 'Nested stdClass' => [ [ 'a' => [ 'b' => (object)[] ] ], true, null ];
		yield 'Nested, serializable' => [ [ 'a' => [ 'b' => [ 'c' => 'd' ] ] ], true, null ];
		yield 'Nested, serializable, with null' => [ [ 'a' => [ 'b' => null ] ], true, null ];
		yield 'Nested, serializable, with stdClass' => [ [ 'a' => (object)[ 'b' => [ 'c' => 'd' ] ] ], true, null ];
		yield 'Nested, serializable, with stdClass, with null' => [ [ 'a' => (object)[ 'b' => null ] ], true, null ];
		yield 'Nested, non-serializable' => [ [ 'a' => [ 'b' => $classInstance ] ], true, '$.a.b' ];
		yield 'Nested, non-serializable, in array' => [ [ 'a' => [ 1, 2, $classInstance ] ], true, '$.a.2' ];
		yield 'Nested, non-serializable, in stdClass' => [ [ 'a' => (object)[ 1, 2, $classInstance ] ], true, '$.a.2' ];
		yield 'Nested, non-serializable, in JsonDeserializable' => [ new JsonDeserializableSuperClass( $classInstance ), true, '$.super_class_field' ];
		yield 'JsonDeserializable instance' => [ new JsonDeserializableSuperClass( 'Test' ), true, null ];
		yield 'JsonDeserializable instance, in array' =>
			[ [ new JsonDeserializableSuperClass( 'Test' ) ], true, null ];
		yield 'JsonDeserializable instance, in stdClass' =>
			[ (object)[ new JsonDeserializableSuperClass( 'Test' ) ], true, null ];
		yield 'JsonDeserializable instance, in JsonCodecable' =>
			[ new SampleContainerObject( new JsonDeserializableSuperClass( 'Test' ) ), true, null ];
		yield 'JsonSerializable instance' => [ $serializableClass, false, null ];
		yield 'JsonSerializable instance, in array' => [ [ $serializableClass ], false, null ];
		yield 'JsonSerializable instance, in stdClass' => [ (object)[ $serializableClass ], false, null ];
		yield 'JsonSerializable instance, in JsonCodecable' => [ new SampleContainerObject( $serializableClass ), false, null ];
		yield 'JsonSerializable instance, expect deserialize' => [ $serializableClass, true, '$' ];
		yield 'JsonSerializable instance, in array, expect deserialize' => [ [ $serializableClass ], true, '$.0' ];
		yield 'JsonSerializable instance, in stdClass, expect deserialize' =>
			[ (object)[ $serializableClass ], true, '$.0' ];
		yield 'Bad JsonSerializable instance' => [ $badSerializable, false, '$' ];

		yield 'JsonCodecable instance' => [ new SampleObject( 'a' ), true, null ];
		yield 'JsonCodecable instance, in array' =>
			[ [ new SampleObject( '123' ) ], true, null ];
		yield 'JsonCodecable instance, in stdClass' =>
			[ (object)[ new SampleObject( 'Test' ) ], true, null ];
		yield 'JsonCodecable instance, in JsonCodecable' => [
			new SampleContainerObject( new SampleObject( '123' ) ), true, null
		];
		// Managed values
		$factory = self::getServices()->get( 'ManagedObjectFactory' );
		yield 'Managed instance' => [ $factory->lookup( 'a' ), true, null ];
	}

	/**
	 * @dataProvider provideValidateSerializable
	 * @covers \MediaWiki\Json\JsonCodec::detectNonSerializableData
	 * @covers \MediaWiki\Json\JsonCodec::detectNonSerializableDataInternal
	 */
	public function testValidateSerializable( $value, bool $expectDeserialize, ?string $expected ) {
		$actual = $this->getCodec()
			->detectNonSerializableData( $value, $expectDeserialize );
		// Split off the details string from the detection location
		if ( $actual !== null ) {
			$actual = preg_replace( '/:.*$/', '', $actual );
		}
		$this->assertSame( $expected, $actual );
	}

	/**
	 * @dataProvider provideValidateSerializable
	 * @covers \MediaWiki\Json\JsonCodec::detectNonSerializableData
	 * @covers \MediaWiki\Json\JsonCodec::detectNonSerializableDataInternal
	 */
	public function testValidateSerializable2( $value, bool $expectDeserialize, ?string $result ) {
		if ( $result !== null || !$expectDeserialize ) {
			$this->assertTrue( true ); // skip this test
			return;
		}
		// Sanity check by ensuring that "serializable" things actually
		// are deserializable w/o losing value or type
		$json = $this->getCodec()->serialize( $value );
		$newValue = $this->getCodec()->deserialize( $json );
		$this->assertEquals( $value, $newValue );
	}

	public static function provideSerializeThrowsOnFailure() {
		$classInstance = new class() {
		};
		yield 'non-serializable class' => [ $classInstance ];
		yield 'invalid UTF-8' => [
			"\x1f\x8b\x08\x00\x00\x00\x00\x00\x00\x03\xcb\x48\xcd\xc9\xc9\x57\x28\xcf\x2f"
				. "\xca\x49\x01\x00\x85\x11\x4a\x0d\x0b\x00\x00\x00"
		];
	}

	/**
	 * @dataProvider provideSerializeThrowsOnFailure
	 * @covers \MediaWiki\Json\JsonCodec::serialize
	 */
	public function testSerializeThrowsOnFailure( $value ) {
		$this->expectException( JsonException::class );
		$this->getCodec()->serialize( $value );
	}

	public static function provideSerializeSuccess() {
		$serializableInstance = new class() implements JsonSerializable {
			public function jsonSerialize(): array {
				return [ 'c' => 'd' ];
			}
		};
		yield 'array' => [ [ 'a' => 'b' ], '{"a":"b"}' ];
		yield 'JsonSerializable' => [
			$serializableInstance,
			json_encode( [ "c" => "d", "_type_" => get_class( $serializableInstance ), "_complex_" => true ], JSON_UNESCAPED_SLASHES )
		];
		yield 'JsonCodecable' => [ new SampleObject( 'a' ), json_encode( [
			'property' => 'a',
			'_type_' => SampleObject::class,
			'_complex_' => true,
		] ) ];
	}

	/**
	 * @dataProvider provideSerializeSuccess
	 * @covers \MediaWiki\Json\JsonCodec::serialize
	 */
	public function testSerializeSuccess( $value, string $expected ) {
		$this->assertSame( $expected, $this->getCodec()->serialize( $value ) );
	}

	public function testManagedObjects() {
		$codec = $this->getCodec();
		$factory = self::getServices()->get( 'ManagedObjectFactory' );
		$a = $factory->lookup( 'a' );
		$s = $codec->serialize( $a );
		$v = $codec->deserialize( $s );
		// Not just "equals", $v should be reference-identical to $a
		$this->assertSame( $a, $v );
	}

	public function testCodecableAliases() {
		$codec = $this->getCodec();
		// Note that the class name in _type_ is an *alias*, not the
		// *actual* class name.
		$json = '{"property":"alias!","_type_":"MediaWiki\\\\Tests\\\\Mocks\\\\Json\\\\SampleObjectAlias","_complex_":true}';
		$v = $codec->deserialize( $json, SampleObject::class );
		$this->assertInstanceOf( SampleObject::class, $v );
	}

	public function testJsonDeserializableAliases() {
		$codec = $this->getCodec();
		// Note that the class name in _type_ is an *alias*, not the
		// *actual* class name.
		$json = '{"super_class_field":1,"sub_class_field":"2","_type_":"MediaWiki\\\\Tests\\\\Mocks\\\\Json\\\\JsonDeserializableSubClassAlias","_complex_":true}';
		$v = $codec->deserialize( $json, JsonDeserializableSubClass::class );
		$this->assertInstanceOf( JsonDeserializableSubClass::class, $v );
	}
}
