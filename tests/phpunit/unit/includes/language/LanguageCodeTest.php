<?php

/**
 * @covers LanguageCode
 * @group Language
 *
 * @author Thiemo Kreuz
 */
class LanguageCodeTest extends MediaWikiUnitTestCase {

	public function testConstructor() {
		$instance = new LanguageCode();

		$this->assertInstanceOf( LanguageCode::class, $instance );
	}

	public function testGetDeprecatedCodeMapping() {
		$map = LanguageCode::getDeprecatedCodeMapping();

		$this->assertInternalType( 'array', $map );
		$this->assertContainsOnly( 'string', array_keys( $map ) );
		$this->assertArrayNotHasKey( '', $map );
		$this->assertContainsOnly( 'string', $map );
		$this->assertNotContains( '', $map );

		// Codes special to MediaWiki should never appear in a map of "deprecated" codes
		$this->assertArrayNotHasKey( 'qqq', $map, 'documentation' );
		$this->assertNotContains( 'qqq', $map, 'documentation' );
		$this->assertArrayNotHasKey( 'qqx', $map, 'debug code' );
		$this->assertNotContains( 'qqx', $map, 'debug code' );

		// Valid language codes that are currently not "deprecated"
		$this->assertArrayNotHasKey( 'bh', $map, 'family of Bihari languages' );
		$this->assertArrayNotHasKey( 'no', $map, 'family of Norwegian languages' );
		$this->assertArrayNotHasKey( 'simple', $map );
	}

	public function testReplaceDeprecatedCodes() {
		$this->assertEquals( 'gsw', LanguageCode::replaceDeprecatedCodes( 'als' ) );
		$this->assertEquals( 'gsw', LanguageCode::replaceDeprecatedCodes( 'gsw' ) );
		$this->assertNull( LanguageCode::replaceDeprecatedCodes( null ) );
	}

	/**
	 * test @see LanguageCode::bcp47().
	 * Please note the BCP 47 explicitly state that language codes are case
	 * insensitive, there are some exceptions to the rule :)
	 * This test is used to verify our formatting against all lower and
	 * all upper cases language code.
	 *
	 * @see https://tools.ietf.org/html/bcp47
	 * @dataProvider provideLanguageCodes()
	 */
	public function testBcp47( $code, $expected ) {
		$this->assertEquals( $expected, LanguageCode::bcp47( $code ),
			"Applying BCP 47 standard to '$code'"
		);

		$code = strtolower( $code );
		$this->assertEquals( $expected, LanguageCode::bcp47( $code ),
			"Applying BCP 47 standard to lower case '$code'"
		);

		$code = strtoupper( $code );
		$this->assertEquals( $expected, LanguageCode::bcp47( $code ),
			"Applying BCP 47 standard to upper case '$code'"
		);
	}

	/**
	 * Array format is ($code, $expected)
	 */
	public static function provideLanguageCodes() {
		return [
			// Extracted from BCP 47 (list not exhaustive)
			# 2.1.1
			[ 'en-ca-x-ca', 'en-CA-x-ca' ],
			[ 'sgn-be-fr', 'sgn-BE-FR' ],
			[ 'az-latn-x-latn', 'az-Latn-x-latn' ],
			# 2.2
			[ 'sr-Latn-RS', 'sr-Latn-RS' ],
			[ 'az-arab-ir', 'az-Arab-IR' ],

			# 2.2.5
			[ 'sl-nedis', 'sl-nedis' ],
			[ 'de-ch-1996', 'de-CH-1996' ],

			# 2.2.6
			[
				'en-latn-gb-boont-r-extended-sequence-x-private',
				'en-Latn-GB-boont-r-extended-sequence-x-private'
			],

			// Examples from BCP 47 Appendix A
			# Simple language subtag:
			[ 'DE', 'de' ],
			[ 'fR', 'fr' ],
			[ 'ja', 'ja' ],

			# Language subtag plus script subtag:
			[ 'zh-hans', 'zh-Hans' ],
			[ 'sr-cyrl', 'sr-Cyrl' ],
			[ 'sr-latn', 'sr-Latn' ],

			# Extended language subtags and their primary language subtag
			# counterparts:
			[ 'zh-cmn-hans-cn', 'zh-cmn-Hans-CN' ],
			[ 'cmn-hans-cn', 'cmn-Hans-CN' ],
			[ 'zh-yue-hk', 'zh-yue-HK' ],
			[ 'yue-hk', 'yue-HK' ],

			# Language-Script-Region:
			[ 'zh-hans-cn', 'zh-Hans-CN' ],
			[ 'sr-latn-RS', 'sr-Latn-RS' ],

			# Language-Variant:
			[ 'sl-rozaj', 'sl-rozaj' ],
			[ 'sl-rozaj-biske', 'sl-rozaj-biske' ],
			[ 'sl-nedis', 'sl-nedis' ],

			# Language-Region-Variant:
			[ 'de-ch-1901', 'de-CH-1901' ],
			[ 'sl-it-nedis', 'sl-IT-nedis' ],

			# Language-Script-Region-Variant:
			[ 'hy-latn-it-arevela', 'hy-Latn-IT-arevela' ],

			# Language-Region:
			[ 'de-de', 'de-DE' ],
			[ 'en-us', 'en-US' ],
			[ 'es-419', 'es-419' ],

			# Private use subtags:
			[ 'de-ch-x-phonebk', 'de-CH-x-phonebk' ],
			[ 'az-arab-x-aze-derbend', 'az-Arab-x-aze-derbend' ],
			/**
			 * Previous test does not reflect the BCP 47 which states:
			 *  az-Arab-x-AZE-derbend
			 * AZE being private, it should be lower case, hence the test above
			 * should probably be:
			 * [ 'az-arab-x-aze-derbend', 'az-Arab-x-AZE-derbend' ],
			 */

			# Private use registry values:
			[ 'x-whatever', 'x-whatever' ],
			[ 'qaa-qaaa-qm-x-southern', 'qaa-Qaaa-QM-x-southern' ],
			[ 'de-qaaa', 'de-Qaaa' ],
			[ 'sr-latn-qm', 'sr-Latn-QM' ],
			[ 'sr-qaaa-rs', 'sr-Qaaa-RS' ],

			# Tags that use extensions
			[ 'en-us-u-islamcal', 'en-US-u-islamcal' ],
			[ 'zh-cn-a-myext-x-private', 'zh-CN-a-myext-x-private' ],
			[ 'en-a-myext-b-another', 'en-a-myext-b-another' ],

			# Invalid:
			// de-419-DE
			// a-DE
			// ar-a-aaa-b-bbb-a-ccc

			# Non-standard and deprecated language codes used by MediaWiki
			[ 'als', 'gsw' ],
			[ 'bat-smg', 'sgs' ],
			[ 'be-x-old', 'be-tarask' ],
			[ 'fiu-vro', 'vro' ],
			[ 'roa-rup', 'rup' ],
			[ 'zh-classical', 'lzh' ],
			[ 'zh-min-nan', 'nan' ],
			[ 'zh-yue', 'yue' ],
			[ 'cbk-zam', 'cbk' ],
			[ 'de-formal', 'de-x-formal' ],
			[ 'eml', 'egl' ],
			[ 'en-rtl', 'en-x-rtl' ],
			[ 'es-formal', 'es-x-formal' ],
			[ 'hu-formal', 'hu-x-formal' ],
			[ 'kk-Arab', 'kk-Arab' ],
			[ 'kk-Cyrl', 'kk-Cyrl' ],
			[ 'kk-Latn', 'kk-Latn' ],
			[ 'map-bms', 'jv-x-bms' ],
			[ 'mo', 'ro-Cyrl-MD' ],
			[ 'nrm', 'nrf' ],
			[ 'nl-informal', 'nl-x-informal' ],
			[ 'roa-tara', 'nap-x-tara' ],
			[ 'simple', 'en-simple' ],
			[ 'sr-ec', 'sr-Cyrl' ],
			[ 'sr-el', 'sr-Latn' ],
			[ 'zh-cn', 'zh-Hans-CN' ],
			[ 'zh-sg', 'zh-Hans-SG' ],
			[ 'zh-my', 'zh-Hans-MY' ],
			[ 'zh-tw', 'zh-Hant-TW' ],
			[ 'zh-hk', 'zh-Hant-HK' ],
			[ 'zh-mo', 'zh-Hant-MO' ],
			[ 'zh-hans', 'zh-Hans' ],
			[ 'zh-hant', 'zh-Hant' ],
		];
	}

}
