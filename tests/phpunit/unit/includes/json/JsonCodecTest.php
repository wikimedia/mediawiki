<?php

namespace MediaWiki\Tests\Json;

use FormatJson;
use InvalidArgumentException;
use JsonSerializable;
use MediaWiki\Json\JsonCodec;
use MediaWiki\Json\JsonConstants;
use MediaWikiUnitTestCase;
use Title;
use Wikimedia\Assert\PreconditionException;

/**
 * @covers \MediaWiki\Json\JsonCodec
 * @covers \MediaWiki\Json\JsonUnserializableTrait
 * @package MediaWiki\Tests\Json
 */
class JsonCodecTest extends MediaWikiUnitTestCase {

	private function getCodec(): JsonCodec {
		return new JsonCodec();
	}

	public function provideSimpleTypes() {
		yield 'Integer' => [ 1, json_encode( 1 ) ];
		yield 'Boolean' => [ true, json_encode( true ) ];
		yield 'Null' => [ null, json_encode( null ) ];
		yield 'Array' => [ [ 1, 2, 3 ], json_encode( [ 1, 2, 3 ] ) ];
		yield 'Assoc array' => [ [ 'a' => 'b' ], json_encode( [ 'a' => 'b' ] ) ];

		$object = (object)[ 'c' => 'd' ];
		yield 'Object' => [ (array)$object, json_encode( $object ) ];
	}

	public function provideInvalidJsonData() {
		yield 'Bad string' => [ 'bad string' ];
		yield 'No unserialization metadata' => [ [ 'test' => 'test' ] ];
		yield 'Unserialization metadata, but class not exist' => [ [
			JsonConstants::TYPE_ANNOTATION => 'BadClassNotExist'
		] ];
		yield 'Unserialization metadata, but class is not JsonUnserializable' => [ [
			JsonConstants::TYPE_ANNOTATION => Title::class
		] ];
	}

	/**
	 * @dataProvider provideSimpleTypes
	 */
	public function testSimpleTypesUnserialize( $value, string $serialization ) {
		$this->assertSame( $value, $this->getCodec()->unserialize( $serialization ) );
	}

	/**
	 * @dataProvider provideSimpleTypes
	 * @dataProvider provideInvalidJsonData
	 */
	public function testInvalidJsonDataForClassExpectation( $jsonData ) {
		$this->expectException( InvalidArgumentException::class );
		$this->getCodec()->unserialize( $jsonData, JsonUnserializableSuperClass::class );
	}

	public function testExpectedClassMustBeUnserializable() {
		$this->expectException( PreconditionException::class );
		$this->getCodec()->unserialize( '{}', self::class );
	}

	public function testUnexpectedClassUnserialized() {
		$this->expectException( InvalidArgumentException::class );
		$superClassInstance = new JsonUnserializableSuperClass( 'Godzilla' );
		$this->getCodec()->unserialize(
			$superClassInstance->jsonSerialize(),
			JsonUnserializableSubClass::class
		);
	}

	public function testExpectedClassUnserialized() {
		$subClassInstance = new JsonUnserializableSubClass( 'Godzilla', 'But we are ready!' );
		$this->assertNotNull( $this->getCodec()->unserialize(
			$subClassInstance->jsonSerialize(),
			JsonUnserializableSuperClass::class
		) );
		$this->assertNotNull( $this->getCodec()->unserialize(
			$subClassInstance->jsonSerialize(),
			JsonUnserializableSubClass::class
		) );
	}

	public function testRoundTripSuperClass() {
		$superClassInstance = new JsonUnserializableSuperClass( 'Super Value' );
		$json = $superClassInstance->jsonSerialize();
		$superClassUnserialized = $this->getCodec()->unserialize( $json );
		$this->assertInstanceOf( JsonUnserializableSuperClass::class, $superClassInstance );
		$this->assertSame( $superClassInstance->getSuperClassField(), $superClassUnserialized->getSuperClassField() );
	}

	public function testRoundTripSuperClassObject() {
		$superClassInstance = new JsonUnserializableSuperClass( 'Super Value' );
		$json = (object)$superClassInstance->jsonSerialize();
		$superClassUnserialized = $this->getCodec()->unserialize( $json );
		$this->assertInstanceOf( JsonUnserializableSuperClass::class, $superClassInstance );
		$this->assertSame( $superClassInstance->getSuperClassField(), $superClassUnserialized->getSuperClassField() );
	}

	public function testRoundTripSubClass() {
		$subClassInstance = new JsonUnserializableSubClass( 'Super Value', 'Sub Value' );
		$json = $subClassInstance->jsonSerialize();
		$superClassUnserialized = $this->getCodec()->unserialize( $json );
		$this->assertInstanceOf( JsonUnserializableSubClass::class, $subClassInstance );
		$this->assertSame( $subClassInstance->getSuperClassField(), $superClassUnserialized->getSuperClassField() );
		$this->assertSame( $subClassInstance->getSubClassField(), $superClassUnserialized->getSubClassField() );
	}

	public function testArrayRoundTrip() {
		$array = [
			new JsonUnserializableSuperClass( 'Super Value' ),
			new JsonUnserializableSubClass( 'Super Value', 'Sub Value' ),
			42
		];
		$serialized = FormatJson::encode( $array );
		$unserialized = $this->getCodec()->unserializeArray( FormatJson::decode( $serialized ) );
		$this->assertArrayEquals( $array, $unserialized );
	}

	public function provideValidateSerializable() {
		$classInstance = new class() {
		};
		$serializableClass = new class() implements JsonSerializable {
			#[\ReturnTypeWillChange]
			public function jsonSerialize() {
				return [];
			}
		};

		yield 'Number' => [ 1, true, null ];
		yield 'Null' => [ null, true, null ];
		yield 'Class' => [ $classInstance, false, '$' ];
		yield 'Empty array' => [ [], true, null ];
		yield 'Empty stdClass' => [ (object)[], true, null ];
		yield 'Non-empty array' => [ [ 1, 2, 3 ], true, null ];
		yield 'Non-empty map' => [ [ 'a' => 'b' ], true, null ];
		yield 'Nested, serializable' => [ [ 'a' => [ 'b' => [ 'c' => 'd' ] ] ], true, null ];
		yield 'Nested, serializable, with null' => [ [ 'a' => [ 'b' => null ] ], true, null ];
		yield 'Nested, serializable, with stdClass' => [ [ 'a' => (object)[ 'b' => [ 'c' => 'd' ] ] ], true, null ];
		yield 'Nested, serializable, with stdClass, with null' => [ [ 'a' => (object)[ 'b' => null ] ], true, null ];
		yield 'Nested, non-serializable' => [ [ 'a' => [ 'b' => $classInstance ] ], true, '$.a.b' ];
		yield 'Nested, non-serializable, in array' => [ [ 'a' => [ 1, 2, $classInstance ] ], true, '$.a.2' ];
		yield 'Nested, non-serializable, in stdClass' => [ [ 'a' => (object)[ 1, 2, $classInstance ] ], true, '$.a.2' ];
		yield 'JsonUnserializable instance' => [ new JsonUnserializableSuperClass( 'Test' ), true, null ];
		yield 'JsonUnserializable instance, in array' =>
			[ [ new JsonUnserializableSuperClass( 'Test' ) ], true, null ];
		yield 'JsonUnserializable instance, in stdClass' =>
			[ (object)[ new JsonUnserializableSuperClass( 'Test' ) ], true, null ];
		yield 'JsonSerializable instance' => [ $serializableClass, false, null ];
		yield 'JsonSerializable instance, in array' => [ [ $serializableClass ], false, null ];
		yield 'JsonSerializable instance, in stdClass' => [ (object)[ $serializableClass ], false, null ];
		yield 'JsonSerializable instance, expect unserialize' => [ $serializableClass, true, '$' ];
		yield 'JsonSerializable instance, in array, expect unserialize' => [ [ $serializableClass ], true, '$.0' ];
		yield 'JsonSerializable instance, in stdClass, expect unserialize' =>
			[ (object)[ $serializableClass ], true, '$.0' ];
	}

	/**
	 * @dataProvider provideValidateSerializable
	 * @covers \MediaWiki\Json\JsonCodec::detectNonSerializableData
	 * @covers \MediaWiki\Json\JsonCodec::detectNonSerializableDataInternal
	 */
	public function testValidateSerializable( $value, bool $expectUnserialize, ?string $result ) {
		$this->assertSame( $result, $this->getCodec()
			->detectNonSerializableData( $value, $expectUnserialize ) );
	}

	public function provideSerializeThrowsOnFailure() {
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
		$this->expectException( InvalidArgumentException::class );
		$this->getCodec()->serialize( $value );
	}

	public function provideSerializeSuccess() {
		$serializableInstance = new class() implements JsonSerializable {
			#[\ReturnTypeWillChange]
			public function jsonSerialize() {
				return [ 'c' => 'd' ];
			}
		};
		yield 'array' => [ [ 'a' => 'b' ], '{"a":"b"}' ];
		yield 'JsonSerializable' => [ $serializableInstance, '{"c":"d"}' ];
	}

	/**
	 * @dataProvider provideSerializeSuccess
	 * @covers \MediaWiki\Json\JsonCodec::serialize
	 */
	public function testSerializeSuccess( $value, string $expected ) {
		$this->assertSame( $expected, $this->getCodec()->serialize( $value ) );
	}
}
