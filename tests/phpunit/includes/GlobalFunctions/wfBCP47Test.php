<?php
/**
 * @group GlobalFunctions
 * @covers ::wfBCP47
 */
class WfBCP47Test extends MediaWikiTestCase {
	/**
	 * test @see wfBCP47().
	 * Please note the BCP 47 explicitly state that language codes are case
	 * insensitive, there are some exceptions to the rule :)
	 * This test is used to verify our formatting against all lower and
	 * all upper cases language code.
	 *
	 * @see https://tools.ietf.org/html/bcp47
	 * @dataProvider provideLanguageCodes()
	 */
	public function testBCP47( $code, $expected ) {
		$code = strtolower( $code );
		$this->assertEquals( $expected, wfBCP47( $code ),
			"Applying BCP47 standard to lower case '$code'"
		);

		$code = strtoupper( $code );
		$this->assertEquals( $expected, wfBCP47( $code ),
			"Applying BCP47 standard to upper case '$code'"
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
		];
	}
}
