<?php
/**
 * Unit tests for wfBCP47()
 */
class wfBCP47 extends MediaWikiTestCase {
	/**
	 * test @see wfBCP47().
	 * Please note the BCP explicitly state that language codes are case
	 * insensitive, there are some exceptions to the rule :)
   	 * This test is used to verify our formatting against all lower and
	 * all upper cases language code.
	 *
	 * @see http://tools.ietf.org/html/bcp47
	 * @dataProvider provideLanguageCodes()
	 */
	function testBCP47( $code, $expected ) {
		$code = strtolower( $code );
		$this->assertEquals( $expected, wfBCP47($code),
			"Applying BCP47 standard to lower case '$code'"
		);

		$code = strtoupper( $code );
		$this->assertEquals( $expected, wfBCP47($code),
			"Applying BCP47 standard to upper case '$code'"
		);
	}

	/**
	 * Array format is ($code, $expected)
	 */
	function provideLanguageCodes() {
		return array(
			// Extracted from BCP47 (list not exhaustive)
			# 2.1.1
			array( 'en-ca-x-ca'    , 'en-CA-x-ca'     ),
			array( 'sgn-be-fr'     , 'sgn-BE-FR'      ),
			array( 'az-latn-x-latn', 'az-Latn-x-latn' ),
			# 2.2
			array( 'sr-Latn-RS', 'sr-Latn-RS' ),
			array( 'az-arab-ir', 'az-Arab-IR' ),

			# 2.2.5
			array( 'sl-nedis'  , 'sl-nedis'   ),
			array( 'de-ch-1996', 'de-CH-1996' ),

			# 2.2.6
			array(
				'en-latn-gb-boont-r-extended-sequence-x-private',
				'en-Latn-GB-boont-r-extended-sequence-x-private'
			),

			// Examples from BCP47 Appendix A
			# Simple language subtag:
			array( 'DE', 'de' ),
			array( 'fR', 'fr' ),
			array( 'ja', 'ja' ),

			# Language subtag plus script subtag:
			array( 'zh-hans', 'zh-Hans'),
			array( 'sr-cyrl', 'sr-Cyrl'),
			array( 'sr-latn', 'sr-Latn'),

			# Extended language subtags and their primary language subtag
			# counterparts:
			array( 'zh-cmn-hans-cn', 'zh-cmn-Hans-CN' ),
			array( 'cmn-hans-cn'   , 'cmn-Hans-CN'    ),
			array( 'zh-yue-hk'     , 'zh-yue-HK'      ),
			array( 'yue-hk'        , 'yue-HK'         ),

			# Language-Script-Region:
			array( 'zh-hans-cn', 'zh-Hans-CN' ),
			array( 'sr-latn-RS', 'sr-Latn-RS' ),

			# Language-Variant:
			array( 'sl-rozaj'      , 'sl-rozaj'       ),
			array( 'sl-rozaj-biske', 'sl-rozaj-biske' ),
			array( 'sl-nedis'      , 'sl-nedis'       ),

			# Language-Region-Variant:
			array( 'de-ch-1901'  , 'de-CH-1901'  ),
			array( 'sl-it-nedis' , 'sl-IT-nedis' ),

			# Language-Script-Region-Variant:
			array( 'hy-latn-it-arevela', 'hy-Latn-IT-arevela' ),

			# Language-Region:
			array( 'de-de' , 'de-DE' ),
			array( 'en-us' , 'en-US' ),
			array( 'es-419', 'es-419'),

			# Private use subtags:
			array( 'de-ch-x-phonebk'      , 'de-CH-x-phonebk' ),
			array( 'az-arab-x-aze-derbend', 'az-Arab-x-aze-derbend' ),
			/**
			 * Previous test does not reflect the BCP which states:
			 *  az-Arab-x-AZE-derbend
			 * AZE being private, it should be lower case, hence the test above
			 * should probably be:
			#array( 'az-arab-x-aze-derbend', 'az-Arab-x-AZE-derbend' ),
			 */

			# Private use registry values:
			array( 'x-whatever', 'x-whatever' ),
			array( 'qaa-qaaa-qm-x-southern', 'qaa-Qaaa-QM-x-southern' ),
			array( 'de-qaaa'   , 'de-Qaaa'    ),
			array( 'sr-latn-qm', 'sr-Latn-QM' ),
			array( 'sr-qaaa-rs', 'sr-Qaaa-RS' ),

			# Tags that use extensions
			array( 'en-us-u-islamcal', 'en-US-u-islamcal' ),
			array( 'zh-cn-a-myext-x-private', 'zh-CN-a-myext-x-private' ),
			array( 'en-a-myext-b-another', 'en-a-myext-b-another' ),

			# Invalid:
			// de-419-DE
			// a-DE
			// ar-a-aaa-b-bbb-a-ccc
	
		/*	
			// ISO 15924 :
			array( 'sr-Cyrl', 'sr-Cyrl' ),
			# @todo FIXME: Fix our function?
			array( 'SR-lATN', 'sr-Latn' ),
			array( 'fr-latn', 'fr-Latn' ),
			// Use lowercase for single segment
			// ISO 3166-1-alpha-2 code
			array( 'US', 'us' ),  # USA
			array( 'uS', 'us' ),  # USA
			array( 'Fr', 'fr' ),  # France
			array( 'va', 'va' ),  # Holy See (Vatican City State)
		 */);
	}
}
