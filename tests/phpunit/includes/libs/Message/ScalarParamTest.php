<?php

namespace Wikimedia\Tests\Message;

use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ScalarParam;
use Wikimedia\Message\ParamType;

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

}
