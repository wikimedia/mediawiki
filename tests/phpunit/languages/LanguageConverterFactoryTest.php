<?php

use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * @group large
 * @group Language
 * @coversDefaultClass MediaWiki\Languages\LanguageConverterFactory
 */
class LanguageConverterFactoryTest extends MediaWikiLangTestCase {
	/**
	 * @covers ::__construct
	 * @covers ::classFromCode
	 * @covers ::getLanguageConverter
	 * @dataProvider codeProvider
	 */
	public function testLanguageConverters(
		$code,
		$type,
		$variants,
		$variantFallbacks,
		$variantNames,
		$flags,
		$manualLevel
	) {
		$lang = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( $code );
		$factory = new LanguageConverterFactory( false, function () use ( $lang ) {
			return $lang;
		} );
		$converter = $factory->getLanguageConverter( $lang );
		$this->verifyConverter(
			$converter,
			$lang,
			$code,
			$type,
			$variants,
			$variantFallbacks,
			$variantNames,
			$flags,
			$manualLevel
		);
	}

	/**
	 * @covers ::__construct
	 * @covers ::classFromCode
	 * @covers ::getLanguageConverter
	 */
	public function testCreateFromCodeEnPigLatin() {
		$lang = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' );
		$factory = new LanguageConverterFactory( true, function () use ( $lang ) {
			return $lang;
		} );

		$converter = $factory->getLanguageConverter( $lang );

		$this->verifyConverter(
			$converter,
			$lang,
			'en',
			'EnConverter',
			[ 'en', 'en-x-piglatin' ],
			[],
			[],
			[],
			[ 'en' => 'bidirectional', 'en-x-piglatin' => 'bidirectional' ]
		);
	}

	/**
	 * @covers ::__construct
	 * @covers ::classFromCode
	 * @covers ::getLanguageConverter
	 */
	public function testDefaultConentLanguageFallback() {
		$lang = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' );
		$factory = new LanguageConverterFactory( false, function () use ( $lang ) {
			return $lang;
		} );

		$converter = $factory->getLanguageConverter();

		$this->verifyConverter(
			$converter,
			$lang,
			'en',
			'TrivialLanguageConverter',
			[ 'en' ],
			[],
			[],
			[],
			[]
		);
	}

	private function verifyConverter(
		$converter,
		$lang,
		$code,
		$type,
		$variants,
		$variantFallbacks,
		$variantNames,
		$flags,
		$manualLevel
	) {
		$this->assertEquals( $type, get_class( $converter ) );

		if ( is_a( $converter, LanguageConverter::class ) ) {
			$testConverter = TestingAccessWrapper::newFromObject( $converter );
			$this->assertSame( $lang, $testConverter->mLangObj, "Language should be as provided" );

			$this->assertEquals( $code, $testConverter->mMainLanguageCode,
				"mMainLanguageCode should be as $code" );
			$this->assertEquals( $manualLevel, $testConverter->mManualLevel, "Manual Level" );

			$this->assertEquals( $variants, $testConverter->mVariants, "Variants" );
			$this->assertEquals( $variantFallbacks, $testConverter->mVariantFallbacks, "Variant Fallbacks" );
			$defaultFlags = [
				'A' => 'A',
				'T' => 'T',
				'R' => 'R',
				'D' => 'D',
				'-' => '-',
				'H' => 'H',
				'N' => 'N',
			];
			$this->assertArraySubmapSame(
				array_merge( $defaultFlags, $flags ),
				$converter->mFlags,
				"Flags"
			);
		}
	}

	public function codeProvider() {
		$zh_variants = [
			'zh',
			'zh-hans',
			'zh-hant',
			'zh-cn',
			'zh-hk',
			'zh-mo',
			'zh-my',
			'zh-sg',
			'zh-tw'
		];

		$zh_variantfallbacks = [
			'zh' => [ 'zh-hans', 'zh-hant', 'zh-cn', 'zh-tw', 'zh-hk', 'zh-sg', 'zh-mo', 'zh-my' ],
			'zh-hans' => [ 'zh-cn', 'zh-sg', 'zh-my' ],
			'zh-hant' => [ 'zh-tw', 'zh-hk', 'zh-mo' ],
			'zh-cn' => [ 'zh-hans', 'zh-sg', 'zh-my' ],
			'zh-sg' => [ 'zh-hans', 'zh-cn', 'zh-my' ],
			'zh-my' => [ 'zh-hans', 'zh-sg', 'zh-cn' ],
			'zh-tw' => [ 'zh-hant', 'zh-hk', 'zh-mo' ],
			'zh-hk' => [ 'zh-hant', 'zh-mo', 'zh-tw' ],
			'zh-mo' => [ 'zh-hant', 'zh-hk', 'zh-tw' ],
		];
		$zh_ml = [
			'zh' => 'disable',
			'zh-hans' => 'unidirectional',
			'zh-hant' => 'unidirectional',
			'zh-cn' => 'bidirectional',
			'zh-hk' => 'bidirectional',
			'zh-mo' => 'bidirectional',
			'zh-my' => 'bidirectional',
			'zh-sg' => 'bidirectional',
			'zh-tw' => 'bidirectional',
		];

		$zh_flags = [
			'A' => 'A',
			'T' => 'T',
			'R' => 'R',
			'D' => 'D',
			'-' => '-',
			'H' => 'H',
			'N' => 'N',
			'zh' => 'zh',
			'zh-hans' => 'zh-hans',
			'zh-hant' => 'zh-hant',
			'zh-cn' => 'zh-cn',
			'zh-hk' => 'zh-hk',
			'zh-mo' => 'zh-mo',
			'zh-my' => 'zh-my',
			'zh-sg' => 'zh-sg',
			'zh-tw' => 'zh-tw'
		];

		return [
			# $code, $type, $variants, $variantFallbacks, $variantNames, $flags, $manualLevel
			'aa' => [ 'aa', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ab' => [ 'ab', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'abs' => [ 'abs', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ace' => [ 'ace', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ady' => [ 'ady', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ady-cyrl' => [ 'ady-cyrl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'aeb' => [ 'aeb', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'aeb-arab' => [ 'aeb-arab', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'aeb-latn' => [ 'aeb-latn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'af' => [ 'af', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ak' => [ 'ak', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'aln' => [ 'aln', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'als' => [ 'als', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'am' => [ 'am', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'an' => [ 'an', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ang' => [ 'ang', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'anp' => [ 'anp', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ar' => [ 'ar', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'arc' => [ 'arc', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'arn' => [ 'arn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'arq' => [ 'arq', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ary' => [ 'ary', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'arz' => [ 'arz', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'as' => [ 'as', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ase' => [ 'ase', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ast' => [ 'ast', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'atj' => [ 'atj', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'av' => [ 'av', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'avk' => [ 'avk', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'awa' => [ 'awa', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ay' => [ 'ay', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'az' => [ 'az', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'azb' => [ 'azb', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ba' => [ 'ba', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ban' => [ 'ban', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bar' => [ 'bar', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bat-smg' => [ 'bat-smg', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bbc' => [ 'bbc', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bbc-latn' => [ 'bbc-latn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bcc' => [ 'bcc', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bcl' => [ 'bcl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'be' => [ 'be', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'be-tarask' => [ 'be-tarask', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'be-x-old' => [ 'be-x-old', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bg' => [ 'bg', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bgn' => [ 'bgn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bh' => [ 'bh', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bho' => [ 'bho', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bi' => [ 'bi', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bjn' => [ 'bjn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bm' => [ 'bm', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bn' => [ 'bn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bo' => [ 'bo', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bpy' => [ 'bpy', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bqi' => [ 'bqi', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'br' => [ 'br', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'brh' => [ 'brh', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bs' => [ 'bs', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'btm' => [ 'btm', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bto' => [ 'bto', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bug' => [ 'bug', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'bxr' => [ 'bxr', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ca' => [ 'ca', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'cbk-zam' => [ 'cbk-zam', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'cdo' => [ 'cdo', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ce' => [ 'ce', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ceb' => [ 'ceb', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ch' => [ 'ch', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'cho' => [ 'cho', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'chr' => [ 'chr', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'chy' => [ 'chy', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ckb' => [ 'ckb', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'co' => [ 'co', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'cps' => [ 'cps', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'cr' => [ 'cr', 'TrivialLanguageConverter', [], [], [], [], [] ],
			# $code, $type, $variants, $variantFallbacks, $variantNames, $flags, $manualLevel
			'crh' => [ 'crh', 'CrhConverter', [ 'crh', 'crh-cyrl', 'crh-latn' ], [
				'crh' => 'crh-latn',
				'crh-cyrl' => 'crh-latn',
				'crh-latn' => 'crh-cyrl',
				], [], [], [
					'crh' => 'bidirectional',
					'crh-cyrl' => 'bidirectional',
					'crh-latn' => 'bidirectional'
				]
			],
			'crh-latn' => [ 'crh-latn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'crh-cyrl' => [ 'crh-cyrl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'cs' => [ 'cs', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'csb' => [ 'csb', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'cu' => [ 'cu', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'cv' => [ 'cv', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'cy' => [ 'cy', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'da' => [ 'da', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'de' => [ 'de', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'de-at' => [ 'de-at', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'de-ch' => [ 'de-ch', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'de-formal' => [ 'de-formal', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'din' => [ 'din', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'diq' => [ 'diq', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'dsb' => [ 'dsb', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'dtp' => [ 'dtp', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'dty' => [ 'dty', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'dv' => [ 'dv', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'dz' => [ 'dz', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ee' => [ 'ee', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'egl' => [ 'egl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'el' => [ 'el', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'eml' => [ 'eml', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'en' => [ 'en', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'en-ca' => [ 'en-ca', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'en-gb' => [ 'en-gb', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'eo' => [ 'eo', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'es' => [ 'es', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'es-419' => [ 'es-419', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'es-formal' => [ 'es-formal', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'et' => [ 'et', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'eu' => [ 'eu', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ext' => [ 'ext', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'fa' => [ 'fa', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ff' => [ 'ff', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'fi' => [ 'fi', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'fit' => [ 'fit', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'fiu-vro' => [ 'fiu-vro', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'fj' => [ 'fj', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'fo' => [ 'fo', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'fr' => [ 'fr', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'frc' => [ 'frc', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'frp' => [ 'frp', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'frr' => [ 'frr', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'fur' => [ 'fur', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'fy' => [ 'fy', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ga' => [ 'ga', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'gag' => [ 'gag', 'TrivialLanguageConverter', [], [], [], [], [] ],

			# $code, $type, $variants, $variantFallbacks, $variantNames, $flags, $manualLevel
			'gan' => [ 'gan', 'GanConverter', [ 'gan', 'gan-hans', 'gan-hant' ], [
					'gan' => [ 'gan-hans', 'gan-hant' ],
					'gan-hans' => [ 'gan' ],
					'gan-hant' => [ 'gan' ],
				], [], [], [
					'gan' => 'disable',
					'gan-hans' => 'bidirectional',
					'gan-hant' => 'bidirectional'
				]
			],
			'gan-hans' => [ 'gan-hans', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'gan-hant' => [ 'gan-hant', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'gcr' => [ 'gcr', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'gd' => [ 'gd', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'gl' => [ 'gl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'glk' => [ 'glk', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'gn' => [ 'gn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'gom' => [ 'gom', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'gom-deva' => [ 'gom-deva', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'gom-latn' => [ 'gom-latn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'gor' => [ 'gor', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'got' => [ 'got', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'grc' => [ 'grc', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'gsw' => [ 'gsw', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'gu' => [ 'gu', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'gv' => [ 'gv', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ha' => [ 'ha', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'hak' => [ 'hak', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'haw' => [ 'haw', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'he' => [ 'he', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'hi' => [ 'hi', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'hif' => [ 'hif', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'hif-latn' => [ 'hif-latn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'hil' => [ 'hil', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ho' => [ 'ho', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'hr' => [ 'hr', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'hrx' => [ 'hrx', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'hsb' => [ 'hsb', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ht' => [ 'ht', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'hu' => [ 'hu', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'hu-formal' => [ 'hu-formal', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'hy' => [ 'hy', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'hyw' => [ 'hyw', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'hz' => [ 'hz', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ia' => [ 'ia', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'id' => [ 'id', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ie' => [ 'ie', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ig' => [ 'ig', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ii' => [ 'ii', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ik' => [ 'ik', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ike-cans' => [ 'ike-cans', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ike-latn' => [ 'ike-latn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ilo' => [ 'ilo', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'inh' => [ 'inh', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'io' => [ 'io', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'is' => [ 'is', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'it' => [ 'it', 'TrivialLanguageConverter', [], [], [], [], [] ],
			# $code, $type, $variants, $variantFallbacks, $variantNames, $flags, $manualLevel
			'iu' => [ 'iu', 'IuConverter', [ 'iu', 'ike-cans', 'ike-latn' ], [
					'iu' => 'ike-cans',
					'ike-cans' => 'iu',
					'ike-latn' => 'iu',
				], [], [], [
					'iu' => 'bidirectional',
					'ike-cans' => 'bidirectional',
					'ike-latn' => 'bidirectional'
				]
			],
			'ja' => [ 'ja', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'jam' => [ 'jam', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'jbo' => [ 'jbo', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'jut' => [ 'jut', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'jv' => [ 'jv', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ka' => [ 'ka', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kaa' => [ 'kaa', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kab' => [ 'kab', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kbd' => [ 'kbd', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kbd-cyrl' => [ 'kbd-cyrl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kbp' => [ 'kbp', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kg' => [ 'kg', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'khw' => [ 'khw', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ki' => [ 'ki', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kiu' => [ 'kiu', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kj' => [ 'kj', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kjp' => [ 'kjp', 'TrivialLanguageConverter', [], [], [], [], [] ],
			# $code, $type, $variants, $variantFallbacks, $variantNames, $flags, $manualLevel
			'kk' => [ 'kk', 'KkConverter',
				[ 'kk', 'kk-cyrl', 'kk-latn', 'kk-arab', 'kk-kz', 'kk-tr', 'kk-cn' ], [
					'kk' => 'kk-cyrl',
					'kk-cyrl' => 'kk',
					'kk-latn' => 'kk',
					'kk-arab' => 'kk',
					'kk-kz' => 'kk-cyrl',
					'kk-tr' => 'kk-latn',
					'kk-cn' => 'kk-arab'
				], [], [], [
					'kk' => 'bidirectional',
					'kk-cyrl' => 'bidirectional',
					'kk-latn' => 'bidirectional',
					'kk-arab' => 'bidirectional',
					'kk-kz' => 'bidirectional',
					'kk-tr' => 'bidirectional',
					'kk-cn' => 'bidirectional'
				]
			],
			'kk-arab' => [ 'kk-arab', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kk-cyrl' => [ 'kk-cyrl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kk-latn' => [ 'kk-latn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kk-cn' => [ 'kk-cn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kk-kz' => [ 'kk-kz', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kk-tr' => [ 'kk-tr', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kl' => [ 'kl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'km' => [ 'km', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kn' => [ 'kn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ko' => [ 'ko', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ko-kp' => [ 'ko-kp', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'koi' => [ 'koi', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kr' => [ 'kr', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'krc' => [ 'krc', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kri' => [ 'kri', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'krj' => [ 'krj', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'krl' => [ 'krl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ks' => [ 'ks', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ks-arab' => [ 'ks-arab', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ks-deva' => [ 'ks-deva', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ksh' => [ 'ksh', 'TrivialLanguageConverter', [], [], [], [], [] ],
			# $code, $type, $variants, $variantFallbacks, $variantNames, $flags, $manualLevel
			'ku' => [ 'ku', 'KuConverter', [
					'ku',
					'ku-arab',
					'ku-latn'
				], [
					'ku' => 'ku-latn',
					'ku-arab' => 'ku-latn',
					'ku-latn' => 'ku-arab'
				], [], [], [
					'ku' => 'bidirectional',
					'ku-arab' => 'bidirectional',
					'ku-latn' => 'bidirectional'
				]
			],
			'ku-latn' => [ 'ku-latn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ku-arab' => [ 'ku-arab', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kum' => [ 'kum', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kv' => [ 'kv', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'kw' => [ 'kw', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ky' => [ 'ky', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'la' => [ 'la', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lad' => [ 'lad', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lb' => [ 'lb', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lbe' => [ 'lbe', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lez' => [ 'lez', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lfn' => [ 'lfn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lg' => [ 'lg', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'li' => [ 'li', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lij' => [ 'lij', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'liv' => [ 'liv', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lki' => [ 'lki', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lmo' => [ 'lmo', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ln' => [ 'ln', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lo' => [ 'lo', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lrc' => [ 'lrc', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'loz' => [ 'loz', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lt' => [ 'lt', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ltg' => [ 'ltg', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lus' => [ 'lus', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'luz' => [ 'luz', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lv' => [ 'lv', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lzh' => [ 'lzh', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'lzz' => [ 'lzz', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mai' => [ 'mai', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'map-bms' => [ 'map-bms', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mdf' => [ 'mdf', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mg' => [ 'mg', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mh' => [ 'mh', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mhr' => [ 'mhr', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mi' => [ 'mi', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'min' => [ 'min', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mk' => [ 'mk', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ml' => [ 'ml', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mn' => [ 'mn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mni' => [ 'mni', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mnw' => [ 'mnw', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mo' => [ 'mo', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mr' => [ 'mr', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mrj' => [ 'mrj', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ms' => [ 'ms', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mt' => [ 'mt', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mus' => [ 'mus', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mwl' => [ 'mwl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'my' => [ 'my', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'myv' => [ 'myv', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'mzn' => [ 'mzn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'na' => [ 'na', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'nah' => [ 'nah', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'nan' => [ 'nan', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'nap' => [ 'nap', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'nb' => [ 'nb', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'nds' => [ 'nds', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'nds-nl' => [ 'nds-nl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ne' => [ 'ne', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'new' => [ 'new', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ng' => [ 'ng', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'niu' => [ 'niu', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'nl' => [ 'nl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'nl-informal' => [ 'nl-informal', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'nn' => [ 'nn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'no' => [ 'no', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'nov' => [ 'nov', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'nqo' => [ 'nqo', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'nrm' => [ 'nrm', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'nso' => [ 'nso', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'nv' => [ 'nv', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ny' => [ 'ny', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'nys' => [ 'nys', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'oc' => [ 'oc', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'olo' => [ 'olo', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'om' => [ 'om', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'or' => [ 'or', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'os' => [ 'os', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pa' => [ 'pa', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pag' => [ 'pag', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pam' => [ 'pam', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pap' => [ 'pap', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pcd' => [ 'pcd', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pdc' => [ 'pdc', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pdt' => [ 'pdt', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pfl' => [ 'pfl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pi' => [ 'pi', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pih' => [ 'pih', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pl' => [ 'pl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pms' => [ 'pms', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pnb' => [ 'pnb', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pnt' => [ 'pnt', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'prg' => [ 'prg', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ps' => [ 'ps', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pt' => [ 'pt', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'pt-br' => [ 'pt-br', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'qu' => [ 'qu', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'qug' => [ 'qug', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'rgn' => [ 'rgn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'rif' => [ 'rif', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'rm' => [ 'rm', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'rmy' => [ 'rmy', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'rn' => [ 'rn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ro' => [ 'ro', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'roa-rup' => [ 'roa-rup', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'roa-tara' => [ 'roa-tara', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ru' => [ 'ru', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'rue' => [ 'rue', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'rup' => [ 'rup', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ruq' => [ 'ruq', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ruq-cyrl' => [ 'ruq-cyrl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			# ['ruq-grek', 'TrivialLanguageConverter', [], [], [], [], []],
			'ruq-latn' => [ 'ruq-latn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'rw' => [ 'rw', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sa' => [ 'sa', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sah' => [ 'sah', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sat' => [ 'sat', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sc' => [ 'sc', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'scn' => [ 'scn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sco' => [ 'sco', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sd' => [ 'sd', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sdc' => [ 'sdc', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sdh' => [ 'sdh', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'se' => [ 'se', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sei' => [ 'sei', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ses' => [ 'ses', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sg' => [ 'sg', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sgs' => [ 'sgs', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sh' => [ 'sh', 'TrivialLanguageConverter', [], [], [], [], [] ],
			# $code, $type, $variants, $variantFallbacks, $variantNames, $flags, $manualLevel
			'shi' => [ 'shi', 'ShiConverter', [ 'shi', 'shi-tfng', 'shi-latn' ],
				[ 'shi' => 'shi-tfng','shi-tfng' => 'shi','shi-latn' => 'shi' ],
				[], [], [
					'shi' => 'bidirectional',
					'shi-tfng' => 'bidirectional',
					'shi-latn' => 'bidirectional'
				]
			],
			'shi-tfng' => [ 'shi-tfng', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'shi-latn' => [ 'shi-latn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'shn' => [ 'shn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'shy-latn' => [ 'shy-latn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'si' => [ 'si', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'simple' => [ 'simple', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sk' => [ 'sk', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'skr' => [ 'skr', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'skr-arab' => [ 'skr-arab', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sl' => [ 'sl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sli' => [ 'sli', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sm' => [ 'sm', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sma' => [ 'sma', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sn' => [ 'sn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'so' => [ 'so', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sq' => [ 'sq', 'TrivialLanguageConverter', [], [], [], [], [] ],

			# $code, $type, $variants, $variantFallbacks, $variantNames, $flags, $manualLevel

			'sr' => [ 'sr', 'SrConverter', [ 'sr', 'sr-ec', 'sr-el' ], [
					'sr' => 'sr-ec',
					'sr-ec' => 'sr',
					'sr-el' => 'sr'
				], [], [
					'S' => 'S',
					'писмо' => 'S',
					'pismo' => 'S',
					'W' => 'W',
					'реч' => 'W',
					'reč' => 'W',
					'ријеч' => 'W',
					'riječ' => 'W'
				], [
					'sr' => 'bidirectional',
					'sr-ec' => 'bidirectional',
					'sr-el' => 'bidirectional'
				]
			],
			'sr-ec' => [ 'sr-ec', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sr-el' => [ 'sr-el', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'srn' => [ 'srn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ss' => [ 'ss', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'st' => [ 'st', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sty' => [ 'sty', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'stq' => [ 'stq', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'su' => [ 'su', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sv' => [ 'sv', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'sw' => [ 'sw', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'szl' => [ 'szl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'szy' => [ 'szy', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ta' => [ 'ta', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tay' => [ 'tay', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tcy' => [ 'tcy', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'te' => [ 'te', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tet' => [ 'tet', 'TrivialLanguageConverter', [], [], [], [], [] ],
			# $code, $type, $variants, $variantFallbacks, $variantNames, $flags, $manualLevel
			'tg' => [ 'tg', 'TgConverter', [ 'tg', 'tg-latn' ], [], [], [], [
					'tg' => 'bidirectional',
					'tg-latn' => 'bidirectional'
				]
			],
			'tg-cyrl' => [ 'tg-cyrl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tg-latn' => [ 'tg-latn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'th' => [ 'th', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ti' => [ 'ti', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tk' => [ 'tk', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tl' => [ 'tl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tly' => [ 'tly', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tn' => [ 'tn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'to' => [ 'to', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tpi' => [ 'tpi', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tr' => [ 'tr', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tru' => [ 'tru', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ts' => [ 'ts', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tt' => [ 'tt', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tt-cyrl' => [ 'tt-cyrl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tt-latn' => [ 'tt-latn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tum' => [ 'tum', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tw' => [ 'tw', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ty' => [ 'ty', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tyv' => [ 'tyv', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'tzm' => [ 'tzm', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'udm' => [ 'udm', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ug' => [ 'ug', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ug-arab' => [ 'ug-arab', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ug-latn' => [ 'ug-latn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'uk' => [ 'uk', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'ur' => [ 'ur', 'TrivialLanguageConverter', [], [], [], [], [] ],

			# $code, $type, $variants, $variantFallbacks, $variantNames, $flags, $manualLevel
			'uz' => [ 'uz', 'UzConverter', [ 'uz', 'uz-latn', 'uz-cyrl' ], [
					'uz' => 'uz-latn',
					'uz-cyrl' => 'uz',
					'uz-latn' => 'uz',
				], [], [
					'uz' => 'uz',
					'uz-latn' => 'uz-latn',
					'uz-cyrl' => 'uz-cyrl'
				], [
					'uz' => 'bidirectional',
					'uz-latn' => 'bidirectional',
					'uz-cyrl' => 'bidirectional',
				]
			],
			'uz-cyrl' => [ 'uz-cyrl', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'uz-latn' => [ 'uz-latn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			've' => [ 've', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'vec' => [ 'vec', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'vep' => [ 'vep', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'vi' => [ 'vi', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'vls' => [ 'vls', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'vmf' => [ 'vmf', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'vo' => [ 'vo', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'vot' => [ 'vot', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'vro' => [ 'vro', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'wa' => [ 'wa', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'war' => [ 'war', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'wo' => [ 'wo', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'wuu' => [ 'wuu', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'xal' => [ 'xal', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'xh' => [ 'xh', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'xmf' => [ 'xmf', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'xsy' => [ 'xsy', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'yi' => [ 'yi', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'yo' => [ 'yo', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'yue' => [ 'yue', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'za' => [ 'za', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'zea' => [ 'zea', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'zgh' => [ 'zgh', 'TrivialLanguageConverter', [], [], [], [], [] ],
			# $code, $type, $variants, $variantFallbacks, $variantNames, $flags, $manualLevel
			'zh' => [ 'zh', 'ZhConverter', $zh_variants, $zh_variantfallbacks,[], $zh_flags, $zh_ml ],
			'zh-classical' => [ 'zh-classical', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'zh-cn' => [ 'zh-cn', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'zh-hans' => [ 'zh-hans', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'zh-hant' => [ 'zh-hant', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'zh-hk' => [ 'zh-hk', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'zh-min-nan' => [ 'zh-min-nan', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'zh-mo' => [ 'zh-mo', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'zh-my' => [ 'zh-my', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'zh-sg' => [ 'zh-sg', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'zh-tw' => [ 'zh-tw', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'zh-yue' => [ 'zh-yue', 'TrivialLanguageConverter', [], [], [], [], [] ],
			'zu' => [ 'zu', 'TrivialLanguageConverter', [], [], [], [], [] ]
		];
	}
}
