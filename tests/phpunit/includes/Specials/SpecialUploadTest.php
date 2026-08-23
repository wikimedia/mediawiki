<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\MainConfigNames;
use MediaWiki\Specials\SpecialUpload;
use MediaWikiIntegrationTestCase;

class SpecialUploadTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers \MediaWiki\Specials\SpecialUpload::getInitialPageText
	 * @dataProvider provideGetInitialPageText
	 */
	public function testGetInitialPageText( $expected, $inputParams ) {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'en' );
		$result = SpecialUpload::getInitialPageText( ...$inputParams );
		$this->assertEquals( $expected, $result );
	}

	public static function provideGetInitialPageText() {
		return [
			[
				'expected' => "== Summary ==\nthis is a test\n",
				'inputParams' => [
					'this is a test'
				],
			],
			[
				'expected' => "== Summary ==\nthis is a test\n",
				'inputParams' => [
					"== Summary ==\nthis is a test",
				],
			],
		];
	}
}
