<?php

/**
 * @covers HtmlArmor
 */
class HtmlArmorTest extends PHPUnit_Framework_TestCase {

	public static function provideHtmlArmor() {
		return [
			[
				'foobar',
				'foobar',
			],
			[
				'<script>alert("evil!");</script>',
				'&lt;script&gt;alert(&quot;evil!&quot;);&lt;/script&gt;',
			],
			[
				new HtmlArmor( '<script>alert("evil!");</script>' ),
				'<script>alert("evil!");</script>',
			],
		];
	}

	/**
	 * @dataProvider provideHtmlArmor
	 */
	public function testHtmlArmor( $input, $expected ) {
		$this->assertEquals(
			$expected,
			HtmlArmor::getHtml( $input )
		);
	}
}
