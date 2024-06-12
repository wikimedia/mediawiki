<?php

namespace Wikimedia\Tests\Message;

use MediaWiki\Json\JsonCodec;
use MediaWikiUnitTestCase;
use Wikimedia\Message\ListParam;
use Wikimedia\Message\ListType;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;

/**
 * @covers \Wikimedia\Message\ListParam
 */
class ListParamTest extends MediaWikiUnitTestCase {
	use MessageSerializationTestTrait;

	/**
	 * Overrides SerializationTestTrait::getClassToTest
	 * @return string
	 */
	public static function getClassToTest(): string {
		return ListParam::class;
	}

	public static function provideConstruct() {
		return [
			'commaList' => [
				[ ListType::COMMA, [ 1, 2, 3 ] ],
				'<list listType="comma"><text>1</text><text>2</text><text>3</text></list>',
			],
			'andList' => [
				[ ListType::AND, [ new ScalarParam( ParamType::NUM, 5 ), new MessageValue( 'key' ) ] ],
				'<list listType="text"><num>5</num><text><message key="key"></message></text></list>',
			],
		];
	}

	/** @dataProvider provideConstruct */
	public function testSerialize( $args, $_ ) {
		[ $type, $values ] = $args;
		$codec = new JsonCodec;
		$obj = new ListParam( $type, $values );

		$serialized = $codec->serialize( $obj );
		$newObj = $codec->deserialize( $serialized );

		// XXX: would be nice to have a proper ::equals() method.
		$this->assertEquals( $obj->dump(), $newObj->dump() );
	}

	/** @dataProvider provideConstruct */
	public function testConstruct( $args, $expected ) {
		[ $type, $values ] = $args;
		$mp = new ListParam( $type, $values );

		$expectValues = [];
		foreach ( $values as $v ) {
			$expectValues[] = $v instanceof MessageParam ? $v : new ScalarParam( ParamType::TEXT, $v );
		}

		$this->assertSame( ParamType::LIST, $mp->getType() );
		$this->assertSame( $type, $mp->getListType() );
		$this->assertEquals( $expectValues, $mp->getValue() );
		$this->assertSame( $expected, $mp->dump() );
	}

}
