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
				'expect' => "== Summary ==\nthis is a test\n",
				'params' => [
					'this is a test'
				],
			],
			[
				'expect' => "== Summary ==\nthis is a test\n",
				'params' => [
					"== Summary ==\nthis is a test",
				],
			],
		];
	}
}
