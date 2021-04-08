<?php

namespace Wikimedia\Tests\Message;

use InvalidArgumentException;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;

/**
 * @covers \Wikimedia\Message\ScalarParam
 */
class ScalarParamTest extends \PHPUnit\Framework\TestCase {

	public static function provideConstruct() {
		return [
			[
				ParamType::NUM, 1,
				'<num>1</num>',
			],
			[
				ParamType::PLAINTEXT, 'foo & bar',
				'<plaintext>foo &amp; bar</plaintext>',
			],
			[
				ParamType::TEXT, new MessageValue( 'key' ),
				'<text><message key="key"></message></text>',
			],
		];
	}

	/** @dataProvider provideConstruct */
	public function testConstruct( $type, $value, $expected ) {
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
			'Scalar parameter must be a string, number, or MessageValue; got NULL'
		);
		new ScalarParam( ParamType::TEXT, null );
	}

	public function testConstruct_badValueClass() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage(
			'Scalar parameter must be a string, number, or MessageValue; got stdClass'
		);
		new ScalarParam( ParamType::TEXT, new \stdClass );
	}

}
