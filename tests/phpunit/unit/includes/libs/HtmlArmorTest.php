<?php

/**
 * @covers HtmlArmor
 */
class HtmlArmorTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public static function provideConstructor() {
		return [
			[ 'test' ],
			[ null ],
			[ '<em>some html!</em>' ]
		];
	}

	/**
	 * @dataProvider provideConstructor
	 */
	public function testConstructor( $value ) {
		$this->assertInstanceOf( HtmlArmor::class, new HtmlArmor( $value ) );
	}

	public static function provideGetHtml() {
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
			[
				new HtmlArmor( null ),
				null,
			]
		];
	}

	/**
	 * @dataProvider provideGetHtml
	 */
	public function testGetHtml( $input, $expected ) {
		$this->assertEquals(
			$expected,
			HtmlArmor::getHtml( $input )
		);
	}
}
