<?php

namespace Wikimedia\Tests\Message;

use Wikimedia\Message\ListParam;
use Wikimedia\Message\ListType;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;

/**
 * @covers \Wikimedia\Message\ListParam
 */
class ListParamTest extends \PHPUnit\Framework\TestCase {

	public static function provideConstruct() {
		return [
			[
				ListType::COMMA, [ 1, 2, 3 ],
				'<list listType="comma"><text>1</text><text>2</text><text>3</text></list>',
			],
			[
				ListType::AND, [ new ScalarParam( ParamType::NUM, 5 ), new MessageValue( 'key' ) ],
				'<list listType="text"><num>5</num><text><message key="key"></message></text></list>',
			],
		];
	}

	/** @dataProvider provideConstruct */
	public function testConstruct( $type, $values, $expected ) {
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
