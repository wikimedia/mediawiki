<?php

namespace Wikimedia\Tests\DebugInfo;

use PHPUnit\Framework\TestCase;
use stdClass;
use Wikimedia\DebugInfo\Placeholder;

/**
 * @covers \Wikimedia\DebugInfo\Placeholder
 */
class PlaceholderTest extends TestCase {
	public static function provideConstruct() {
		return [
			[
				new stdClass,
				'/^stdClass#[0-9]*$/'
			],
			[
				1,
				'/^int$/'
			],
			[
				'test',
				'/^string$/',
			]
		];
	}

	/**
	 * @dataProvider provideConstruct
	 */
	public function testConstruct( $input, $expected ) {
		$placeholder = new Placeholder( $input );
		$this->assertInstanceOf( Placeholder::class, $placeholder );
		$this->assertMatchesRegularExpression( $expected, $placeholder->desc );
	}
}
