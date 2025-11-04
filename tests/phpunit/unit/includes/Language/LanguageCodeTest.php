<?php

use MediaWiki\Language\Language;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use Wikimedia\Bcp47Code\Bcp47CodeValue;

/**
 * @group Language
 * @covers \MediaWiki\Language\LanguageCode
 *
 * @author Thiemo Kreuz
 */
class LanguageCodeTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;

	public function testConstructor() {
		$instance = new LanguageCode( 'fa' );

		$this->assertInstanceOf( LanguageCode::class, $instance );
		$this->assertSame( 'fa', $instance->toString() );
	}

	public function testGetDeprecatedCodeMapping() {
		$map = LanguageCode::getDeprecatedCodeMapping();

		$this->assertIsArray( $map );
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
	 * Please note the BCP 47 explicitly state that language codes are case
	 * insensitive, there are some exceptions to the rule :)
	 * This test is used to verify our formatting against all lower and
	 * all upper cases language code.
	 *
	 * @see https://tools.ietf.org/html/bcp47
	 * @dataProvider provideLanguageCodes
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

	public function testBcp47ToInternal() {
		foreach ( $this->getBcp47ToInternal() as $message => [ $expected, $bcp47 ] ) {
			$result = LanguageCode::bcp47ToInternal( $bcp47 );
			$this->assertEquals( $expected, $result, $message );
		}
	}

	public function testBcp47ToInternalLanguage() {
		foreach ( $this->getSupportedLanguageCodes() as [ $internalCode ] ) {
			if ( $internalCode === 'egl' ) {
				# 'egl' was added as an internal code prematurely; 'eml' hasn't
				# been added to the deprecated list yet (T36217) and so only
				# 'eml' is a "real" internal code.
				$internalCode = 'eml';
			}
			$lang = $this->createMock( Language::class );
			$lang->method( 'getCode' )->willReturn( $internalCode );
			$result = LanguageCode::bcp47ToInternal( $lang );
			$this->assertEquals( $internalCode, $result );
		}
	}

	public function getSupportedLanguageCodes() {
		$lnu = $this->getDummyLanguageNameUtils();
		$languages = $lnu->getLanguageNames(
			LanguageNameUtils::AUTONYMS, LanguageNameUtils::SUPPORTED
		);
		foreach ( $languages as $code => $autonym ) {
			yield [ $code ];
		}
	}

	public function getBcp47ToInternal() {
		foreach ( $this->getSupportedLanguageCodes() as [ $code ] ) {
			if ( $code === 'egl' ) {
				# 'egl' was added as an internal code prematurely; 'eml' hasn't
				# been added to the deprecated list yet (T36217) and so only
				# 'eml' is a "real" internal code.
				continue;
			}
			$bcp47 = LanguageCode::bcp47( $code );
			yield "$code as string" => [ $code, $bcp47 ];
			yield "$code as Bcp47Code object" => [ $code, new Bcp47CodeValue( $bcp47 ) ];
			// Verify case-insensitivity: lowercase
			$bcp47 = strtolower( $bcp47 );
			yield "$code as lowercase string" => [ $code, $bcp47 ];
			yield "$code as lowercase Bcp47Code object" => [ $code, new Bcp47CodeValue( $bcp47 ) ];
			// Verify case-insensitivity: uppercase
			$bcp47 = strtoupper( $bcp47 );
			yield "$code as uppercase string" => [ $code, $bcp47 ];
			yield "$code as uppercase Bcp47Code object" => [ $code, new Bcp47CodeValue( $bcp47 ) ];
		}
	}

	/**
	 * @dataProvider provideWellFormedLanguageTags
	 */
	public function testWellFormedLanguageTag( $code, $message = '' ) {
		$this->assertTrue(
			LanguageCode::isWellFormedLanguageTag( $code ),
			"validating code $code $message"
		);
	}

	/**
	 * The test cases are based on the tests in the GaBuZoMeu parser
	 * written by Stéphane Bortzmeyer <bortzmeyer@nic.fr>
	 * and distributed as free software, under the GNU General Public Licence.
	 * https://www.bortzmeyer.org/gabuzomeu-parsing-language-tags.html
	 */
	public static function provideWellFormedLanguageTags() {
		return [
			[ 'fr', 'two-letter code' ],
			[ 'fr-latn', 'two-letter code with lower case script code' ],
			[ 'fr-Latn-FR', 'two-letter code with title case script code and uppercase country code' ],
			[ 'fr-Latn-419', 'two-letter code with title case script code and region number' ],
			[ 'fr-FR', 'two-letter code with uppercase' ],
			[ 'ax-TZ', 'Not in the registry, but well-formed' ],
			[ 'fr-shadok', 'two-letter code with variant' ],
			[ 'fr-y-myext-myext2', 'non-x singleton' ],
			[ 'fra-Latn', 'ISO 639 can be 3-letters' ],
			[ 'fra', 'three-letter language code' ],
			[ 'fra-FX', 'three-letter language code with country code' ],
			[ 'i-klingon', 'grandfathered with singleton' ],
			[ 'I-kLINgon', 'tags are case-insensitive...' ],
			[ 'no-bok', 'grandfathered without singleton' ],
			[ 'i-enochian', 'Grandfathered' ],
			[ 'x-fr-CH', 'private use' ],
			[ 'es-419', 'two-letter code with region number' ],
			[ 'en-Latn-GB-boont-r-extended-sequence-x-private', 'weird, but well-formed' ],
			[ 'ab-x-abc-x-abc', 'anything goes after x' ],
			[ 'ab-x-abc-a-a', 'anything goes after x, including several non-x singletons' ],
			[ 'i-default', 'grandfathered' ],
			[ 'abcd-Latn', 'Language of 4 chars reserved for future use' ],
			[ 'AaBbCcDd-x-y-any-x', 'Language of 5-8 chars, registered' ],
			[ 'de-CH-1901', 'with country and year' ],
			[ 'en-US-x-twain', 'with country and singleton' ],
			[ 'zh-cmn', 'three-letter variant' ],
			[ 'zh-cmn-Hant', 'three-letter variant and script' ],
			[ 'zh-cmn-Hant-HK', 'three-letter variant, script and country' ],
			[ 'xr-p-lze', 'Extension' ],
			[ 'en-GB-oed', 'grandfathered language tag, mixed capitalisation' ],
			[ 'en-gb-oed', 'grandfathered language tag, all lowercase' ],
		];
	}

	/**
	 * @dataProvider provideMalformedLanguageTags
	 */
	public function testMalformedLanguageTag( $code, $message = '' ) {
		$this->assertFalse(
			LanguageCode::isWellFormedLanguageTag( $code ),
			"validating that code $code is a malformed language tag - $message"
		);
	}

	/**
	 * The test cases are based on the tests in the GaBuZoMeu parser
	 * written by Stéphane Bortzmeyer <bortzmeyer@nic.fr>
	 * and distributed as free software, under the GNU General Public Licence.
	 * https://www.bortzmeyer.org/gabuzomeu-parsing-language-tags.html
	 */
	public static function provideMalformedLanguageTags() {
		return [
			[ 'f', 'language too short' ],
			[ 'f-Latn', 'language too short with script' ],
			[ 'xr-lxs-qut', 'variants too short' ], # extlangS
			[ 'fr-Latn-F', 'region too short' ],
			[ 'a-value', 'language too short with region' ],
			[ 'tlh-a-b-foo', 'valid three-letter with wrong variant' ],
			[
				'i-notexist',
				'grandfathered but not registered: invalid, even if we only test well-formedness'
			],
			[ 'abcdefghi-012345678', 'numbers too long' ],
			[ 'ab-abc-abc-abc-abc', 'invalid extensions' ],
			[ 'ab-abcd-abc', 'invalid extensions' ],
			[ 'ab-ab-abc', 'invalid extensions' ],
			[ 'ab-123-abc', 'invalid extensions' ],
			[ 'a-Hant-ZH', 'short language with valid extensions' ],
			[ 'a1-Hant-ZH', 'invalid character in language' ],
			[ 'ab-abcde-abc', 'invalid extensions' ],
			[ 'ab-1abc-abc', 'invalid characters in extensions' ],
			[ 'ab-ab-abcd', 'invalid order of extensions' ],
			[ 'ab-123-abcd', 'invalid order of extensions' ],
			[ 'ab-abcde-abcd', 'invalid extensions' ],
			[ 'ab-1abc-abcd', 'invalid characters in extensions' ],
			[ 'ab-a-b', 'extensions too short' ],
			[ 'ab-a-x', 'extensions too short, even with singleton' ],
			[ 'ab--ab', 'two separators' ],
			[ 'ab-abc-', 'separator in the end' ],
			[ '-ab-abc', 'separator in the beginning' ],
			[ 'abcd-efg', 'language too long' ],
			[ 'aabbccddE', 'tag too long' ],
			[ 'pa_guru', 'A tag with underscore is invalid in strict mode' ],
			[ 'de-f', 'subtag too short' ],
			[ 'zh-classical', 'internal language code zh-classical is not a well-formed language tag' ],
		];
	}

	public function testLenientLanguageTag() {
		$this->assertTrue(
			LanguageCode::isWellFormedLanguageTag( 'pa_guru', true ),
			'pa_guru is a well-formed language tag in lenient mode'
		);
	}

}
