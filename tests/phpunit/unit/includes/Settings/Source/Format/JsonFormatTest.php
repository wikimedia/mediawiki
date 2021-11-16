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

	public function testSupportsFileExtension() {
		$format = new JsonFormat();

		$this->assertTrue( $format->supportsFileExtension( 'json' ) );
		$this->assertTrue( $format->supportsFileExtension( 'JSON' ) );
	}

	public function testSupportsFileExtensionUnsupported() {
		$format = new JsonFormat();

		$this->assertFalse( $format->supportsFileExtension( 'yaml' ) );
	}
}
