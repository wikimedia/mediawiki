<?php

namespace Wikimedia\Tests\Message;

use MediaWiki\Json\JsonCodec;
use MediaWikiUnitTestCase;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;

/**
 * @covers \Wikimedia\Message\DataMessageValue
 */
class DataMessageValueTest extends MediaWikiUnitTestCase {
	use MessageSerializationTestTrait;

	/**
	 * Overrides SerializationTestTrait::getClassToTest
	 * @return string
	 */
	public static function getClassToTest(): string {
		return DataMessageValue::class;
	}

	public static function provideConstruct() {
		return [
			'empty' => [
				[ 'key' ],
				'<datamessage key="key" code="key"></datamessage>',
			],
			'withParam' => [
				[ 'key', [ 'a' ] ],
				'<datamessage key="key" code="key"><params><text>a</text></params></datamessage>'
			],
			'withCode' => [
				[ 'key', [], 'code' ],
				'<datamessage key="key" code="code"></datamessage>'
			],
			'withData' => [
				[ 'key', [ new ScalarParam( ParamType::NUM, 1 ) ], 'code', [ 'value' => 1 ] ],
				'<datamessage key="key" code="code">'
					. '<params><num>1</num></params>'
					. '<data>{"value":1}</data>'
					. '</datamessage>'
			],
		];
	}

	/** @dataProvider provideConstruct */
	public function testSerialize( $args, $_ ) {
		$codec = new JsonCodec;
		$obj = new DataMessageValue( ...$args );

		$serialized = $codec->serialize( $obj );
		$newObj = $codec->deserialize( $serialized );

		// XXX: would be nice to have a proper ::equals() method.
		$this->assertEquals( $obj->dump(), $newObj->dump() );
	}

	/** @dataProvider provideConstruct */
	public function testConstruct( $args, $expected ) {
		$mv = new DataMessageValue( ...$args );
		$this->assertSame( $expected, $mv->dump() );
	}

	/** @dataProvider provideConstruct */
	public function testNew( $args, $expected ) {
		$mv = DataMessageValue::new( ...$args );
		$this->assertSame( $expected, $mv->dump() );
	}

	public function testGetCode() {
		$mv = new DataMessageValue( 'key', [], 'code' );
		$this->assertSame( 'code', $mv->getCode() );
	}

	public function testGetData() {
		$mv = new DataMessageValue( 'key', [], 'code', [ 'data' => 'foobar' ] );
		$this->assertSame( [ 'data' => 'foobar' ], $mv->getData() );
	}

}
