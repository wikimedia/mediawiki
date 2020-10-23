<?php

namespace MediaWiki\Tests\Json;

use FormatJson;
use InvalidArgumentException;
use MediaWiki\Json\JsonUnserializer;
use MediaWikiUnitTestCase;
use Title;

/**
 * @covers \MediaWiki\Json\JsonUnserializer
 * @covers \MediaWiki\Json\JsonUnserializableTrait
 * @package MediaWiki\Tests\Json
 */
class JsonUnserializerTest extends MediaWikiUnitTestCase {

	private function getUnserializer(): JsonUnserializer {
		return new JsonUnserializer();
	}

	public function provideInvalidJsonData() {
		yield 'Bad string' => [ 'bad string' ];
		yield 'Integer???' => [ 1 ];
		yield 'No unserialization metadata' => [ [ 'test' => 'test' ] ];
		yield 'Unserialization metadata, but class not exist' => [ [
			JsonUnserializer::TYPE_ANNOTATION => 'BadClassNotExist'
		] ];
		yield 'Unserialization metadata, but class is not JsonUnserializable' => [ [
			JsonUnserializer::TYPE_ANNOTATION => Title::class
		] ];
	}

	/**
	 * @dataProvider provideInvalidJsonData
	 * @param $jsonData
	 */
	public function testInvalidJsonData( $jsonData ) {
		$this->expectException( InvalidArgumentException::class );
		$this->getUnserializer()->unserialize( $jsonData );
	}

	public function testUnexpectedClassUnserialized() {
		$this->expectException( InvalidArgumentException::class );
		$superClassInstance = new JsonUnserializableSuperClass( 'Godzilla' );
		$this->getUnserializer()->unserialize(
			$superClassInstance->jsonSerialize(),
			JsonUnserializableSubClass::class
		);
	}

	public function testExpectedClassUnserialized() {
		$subClassInstance = new JsonUnserializableSubClass( 'Godzilla', 'But we are ready!' );
		$this->assertNotNull( $this->getUnserializer()->unserialize(
			$subClassInstance->jsonSerialize(),
			JsonUnserializableSuperClass::class
		) );
		$this->assertNotNull( $this->getUnserializer()->unserialize(
			$subClassInstance->jsonSerialize(),
			JsonUnserializableSubClass::class
		) );
	}

	public function testRoundTripSuperClass() {
		$superClassInstance = new JsonUnserializableSuperClass( 'Super Value' );
		$json = $superClassInstance->jsonSerialize();
		$superClassUnserialized = $this->getUnserializer()->unserialize( $json );
		$this->assertInstanceOf( JsonUnserializableSuperClass::class, $superClassInstance );
		$this->assertSame( $superClassInstance->getSuperClassField(), $superClassUnserialized->getSuperClassField() );
	}

	public function testRoundTripSuperClassObject() {
		$superClassInstance = new JsonUnserializableSuperClass( 'Super Value' );
		$json = (object)$superClassInstance->jsonSerialize();
		$superClassUnserialized = $this->getUnserializer()->unserialize( $json );
		$this->assertInstanceOf( JsonUnserializableSuperClass::class, $superClassInstance );
		$this->assertSame( $superClassInstance->getSuperClassField(), $superClassUnserialized->getSuperClassField() );
	}

	public function testRoundTripSubClass() {
		$subClassInstance = new JsonUnserializableSubClass( 'Super Value', 'Sub Value' );
		$json = $subClassInstance->jsonSerialize();
		$superClassUnserialized = $this->getUnserializer()->unserialize( $json );
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
		$unserialized = $this->getUnserializer()->unserializeArray( FormatJson::decode( $serialized ) );
		$this->assertArrayEquals( $array, $unserialized );
	}
}
