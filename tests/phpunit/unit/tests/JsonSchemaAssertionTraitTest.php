<?php

use PHPUnit\Framework\AssertionFailedError;

/**
 * @covers JsonSchemaAssertionTrait
 * @group MediaWikiIntegrationTestCaseTest
 */
class JsonSchemaAssertionTraitTest extends MediaWikiUnitTestCase {
	use JsonSchemaAssertionTrait;

	public static function provideValidJson() {
		$dir = __DIR__ . '/json';
		yield [ "$dir/valid1.json", "$dir/schema1.json" ];
		yield [ "$dir/valid2.json", "$dir/schema2.json" ];
	}

	/**
	 * @dataProvider provideValidJson
	 */
	public function testAssertMatchesJsonSchema_valid( $dataFile, $schemaFile ) {
		$jsonString = file_get_contents( $dataFile );

		$dir = __DIR__ . '/json';
		$this->assertMatchesJsonSchema( $schemaFile, $jsonString, [
			'https://www.mediawiki.org/test-schema/test1#' => "$dir/schema1.json",
			'https://www.mediawiki.org/test-schema/test2#' => "$dir/schema2.json",
			'https://www.mediawiki.org/test-schema/test3#' => "$dir/schema3.json",
		] );
	}

	public static function provideInvalidJson() {
		$dir = __DIR__ . '/json';
		// T391586 - Malformed JSON doesn't pass linting while making releases, so
		// to have a txt file extension.
		foreach ( glob( __DIR__ . '/json/invalid*.{txt,json}', GLOB_BRACE ) as $file ) {
			yield $file => [ $file, "$dir/schema1.json" ];
		}
	}

	/**
	 * @dataProvider provideInvalidJson
	 */
	public function testAssertMatchesJsonSchema_invalid( $dataFile, $schemaFile ) {
		$dir = __DIR__ . '/json';
		$jsonString = file_get_contents( $dataFile );

		$this->expectException( AssertionFailedError::class );
		$this->assertMatchesJsonSchema( $schemaFile, $jsonString,
			[
				'https://www.mediawiki.org/test-schema/test1#' => "$dir/schema1.json",
				'https://www.mediawiki.org/test-schema/test2#' => "$dir/schema2.json",
				'https://www.mediawiki.org/test-schema/test3#' => "$dir/schema3.json",
			]
		);
	}

}
