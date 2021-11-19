<?php

namespace MediaWiki\Tests\Unit\Settings\Source\Format;

use MediaWiki\Settings\Source\Format\YamlFormat;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/** @covers \MediaWiki\Settings\Source\Format\YamlFormat */
class YamlFormatTest extends TestCase {

	private const VALID_YAML = <<<'VALID_YAML'
config-schema:
  MySetting:
    type: boolean
VALID_YAML;

	private const INVALID_YAML = <<<'INVALID_YAML'
config-schema: []
  MySetting: []
INVALID_YAML;

	public function provideParser() {
		yield 'php-yaml' => [ 'parser' => YamlFormat::PARSER_PHP_YAML ];
		yield 'symfony' => [ 'parser' => YamlFormat::PARSER_SYMFONY ];
	}

	/**
	 * @dataProvider provideParser
	 */
	public function testDecode( string $parser ) {
		if ( !YamlFormat::isParserAvailable( $parser ) ) {
			$this->markTestSkipped( "Parser '$parser' is not available" );
		}

		$format = new YamlFormat( [ $parser ] );
		$this->assertEquals(
			[ 'config-schema' => [ 'MySetting' => [ 'type' => 'boolean' ] ] ],
			$format->decode( self::VALID_YAML )
		);
	}

	/**
	 * @dataProvider provideParser
	 */
	public function testDecodeInvalid( string $parser ) {
		if ( !YamlFormat::isParserAvailable( $parser ) ) {
			$this->markTestSkipped( "Parser '$parser' is not available" );
		}

		$format = new YamlFormat( [ $parser ] );
		$this->expectException( UnexpectedValueException::class );
		$format->decode( self::INVALID_YAML );
	}

	/**
	 * @dataProvider provideParser
	 */
	public function testDecodeDoesNotUnserializeObjects( string $parser ) {
		if ( !YamlFormat::isParserAvailable( $parser ) ) {
			$this->markTestSkipped( "Parser '$parser' is not available" );
		}

		$format = new YamlFormat( [ $parser ] );
		$this->expectException( UnexpectedValueException::class );
		$format->decode( 'object: !php/object "' . serialize( $format ) . '"' );
	}

	/**
	 * @dataProvider provideParser
	 */
	public function testDecodeDoesNotRespectPHPConst( string $parser ) {
		if ( !YamlFormat::isParserAvailable( $parser ) ) {
			$this->markTestSkipped( "Parser '$parser' is not available" );
		}

		$format = new YamlFormat( [ $parser ] );
		$this->expectException( UnexpectedValueException::class );
		$format->decode( '{ bar: !php/const PHP_INT_SIZE }' );
	}

	public function provideSupportsFileExtension() {
		yield 'Supported' => [ 'yaml', true ];
		yield 'Supported, uppercase' => [ 'YAML', true ];
		yield 'Supported, short' => [ 'yml', true ];
		yield 'Unsupported' => [ 'txt', false ];
	}

	/**
	 * @dataProvider provideSupportsFileExtension
	 */
	public function testSupportsFileExtension( $extension, $expected ) {
		$this->assertSame( $expected, YamlFormat::supportsFileExtension( $extension ) );
	}
}
