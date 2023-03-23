<?php

namespace MediaWiki\Tests\Unit\Settings\Source\Format;

use MediaWiki\Settings\Source\Format\JsonFormat;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * @covers \MediaWiki\Settings\Source\Format\JsonFormat
 */
class JsonFormatTest extends TestCase {
	public function testDecode() {
		$format = new JsonFormat();

		$this->assertSame(
			[ 'config' => [ 'MySetting' => 'BlaBla' ] ],
			$format->decode( '{ "config": { "MySetting": "BlaBla" } }' )
		);
	}

	public function testDecodeBadJSON() {
		$format = new JsonFormat();
		$this->expectException( UnexpectedValueException::class );

		$format->decode( '{ bad }' );
	}

	public static function provideSupportsFileExtension() {
		yield 'Supported' => [ 'json', true ];
		yield 'Supported, uppercase' => [ 'JSON', true ];
		yield 'Unsupported' => [ 'txt', false ];
	}

	/**
	 * @dataProvider provideSupportsFileExtension
	 */
	public function testSupportsFileExtension( $extension, $expected ) {
		$this->assertSame( $expected, JsonFormat::supportsFileExtension( $extension ) );
	}
}
