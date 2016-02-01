<?php

class LanguageKkTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariants() {
		return array(
			array(
				array(
					'kk'      => 'Адамдарға ақыл-парасат, ар-ождан берілген',
					'kk-cyrl' => 'Адамдарға ақыл-парасат, ар-ождан берілген',
					'kk-latn' => 'Adamdarğa aqıl-parasat, ar-ojdan berilgen',
					'kk-arab' => 'ادامدارعا اقىل-پاراسات، ار-وجدان بەرىلگەن',
					'kk-kz'   => 'Адамдарға ақыл-парасат, ар-ождан берілген',
					'kk-tr'   => 'Adamdarğa aqıl-parasat, ar-ojdan berilgen',
					'kk-cn'   => 'ادامدارعا اقىل-پاراسات، ار-وجدان بەرىلگەن'
				),
				'Адамдарға ақыл-парасат, ар-ождан берілген'
			),
		);
	}
}
