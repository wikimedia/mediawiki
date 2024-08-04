<?php

namespace Wikimedia\Tests\Message;

use InvalidArgumentException;
use MediaWiki\Json\JsonCodec;
use MediaWikiUnitTestCase;
use stdClass;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;

/**
 * @covers \Wikimedia\Message\ScalarParam
 */
class ScalarParamTest extends MediaWikiUnitTestCase {
	use MessageSerializationTestTrait;

	/**
	 * Overrides SerializationTestTrait::getClassToTest
	 * @return string
	 */
	public static function getClassToTest(): string {
		return ScalarParam::class;
	}

	public static function provideConstruct() {
		return [
			'num' => [
				[ ParamType::NUM, 1, ],
				'<num>1</num>',
			],
			'plain' => [
				[ ParamType::PLAINTEXT, 'foo & bar', ],
				'<plaintext>foo &amp; bar</plaintext>',
			],
			'text' => [
				[ ParamType::TEXT, new MessageValue( 'key' ), ],
				'<text><message key="key"></message></text>',
			],
		];
	}

	/** @dataProvider provideConstruct */
	public function testSerialize( $args, $_ ) {
		[ $type, $value ] = $args;
		$codec = new JsonCodec;
		$obj = new ScalarParam( $type, $value );

		$serialized = $codec->serialize( $obj );
		$newObj = $codec->deserialize( $serialized );

		// XXX: would be nice to have a proper ::equals() method.
		$this->assertEquals( $obj->dump(), $newObj->dump() );
	}

	/** @dataProvider provideConstruct */
	public function testConstruct( $args, $expected ) {
		[ $type, $value ] = $args;
		$mp = new ScalarParam( $type, $value );
		$this->assertSame( $type, $mp->getType() );
		$this->assertSame( $value, $mp->getValue() );
		$this->assertSame( $expected, $mp->dump() );
	}

	public function testConstruct_badType() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage(
			'ParamType::LIST cannot be used with ScalarParam; use ListParam instead'
		);
		new ScalarParam( ParamType::LIST, [] );
	}

	public function testConstruct_badValueNULL() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage(
			'Scalar parameter must be a string, number, or MessageValue; got null'
		);
		new ScalarParam( ParamType::TEXT, null );
	}

	public function testConstruct_badValueClass() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage(
			'Scalar parameter must be a string, number, or MessageValue; got stdClass'
		);
		new ScalarParam( ParamType::TEXT, new stdClass );
	}

}
