<?php

namespace Wikimedia\DebugInfo;

/**
 * @covers \Wikimedia\DebugInfo\Placeholder
 */
class PlaceholderTest extends \PHPUnit\Framework\TestCase {
	public static function provideConstruct() {
		return [
			[
				new \stdClass,
				'/^stdClass#[0-9]*$/'
			],
			[
				1,
				'/^integer$/'
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
