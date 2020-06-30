<?php

/**
 * @group Language
 * @covers KkConverter
 */
class KkConverterTest extends MediaWikiIntegrationTestCase {

	use LanguageConverterTestTrait;

	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 * @covers KkConverter::autoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result, $this->getLanguageConverter()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariants() {
		return [
			[
				[
					'kk'      => 'Адамдарға ақыл-парасат, ар-ождан берілген',
					'kk-cyrl' => 'Адамдарға ақыл-парасат, ар-ождан берілген',
					'kk-latn' => 'Adamdarğa aqıl-parasat, ar-ojdan berilgen',
					'kk-arab' => 'ادامدارعا اقىل-پاراسات، ار-وجدان بەرىلگەن',
					'kk-kz'   => 'Адамдарға ақыл-парасат, ар-ождан берілген',
					'kk-tr'   => 'Adamdarğa aqıl-parasat, ar-ojdan berilgen',
					'kk-cn'   => 'ادامدارعا اقىل-پاراسات، ار-وجدان بەرىلگەن'
				],
				'Адамдарға ақыл-парасат, ар-ождан берілген'
			],
		];
	}

	/**
	 * @covers KkConverter::findVariantLink
	 * @covers LanguageConverter::findVariantLink
	 */
	public function testFindVariantLinks() {
		$old = "sample_link";
		$newLink = $old;
		$title = Title::newFromText( "Same page for link" );
		$this->getLanguageConverter()->findVariantLink( $newLink, $title );
		$this->assertSame( $old, $newLink, "inks should'n be changed" );
	}
}
