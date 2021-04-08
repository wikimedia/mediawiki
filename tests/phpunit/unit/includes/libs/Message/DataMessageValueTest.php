<?php

namespace Wikimedia\Tests\Message;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;

/**
 * @covers \Wikimedia\Message\DataMessageValue
 */
class DataMessageValueTest extends \PHPUnit\Framework\TestCase {
	public static function provideConstruct() {
		return [
			[
				[ 'key' ],
				'<datamessage key="key" code="key"></datamessage>',
			],
			[
				[ 'key', [ 'a' ] ],
				'<datamessage key="key" code="key"><params><text>a</text></params></datamessage>'
			],
			[
				[ 'key', [], 'code' ],
				'<datamessage key="key" code="code"></datamessage>'
			],
			[
				[ 'key', [ new ScalarParam( ParamType::NUM, 1 ) ], 'code', [ 'value' => 1 ] ],
				'<datamessage key="key" code="code">'
					. '<params><num>1</num></params>'
					. '<data>{"value":1}</data>'
					. '</datamessage>'
			],
		];
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
