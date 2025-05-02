<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Language\LanguageConverter;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\MainConfigNames;
use Wikimedia\TestingAccessWrapper;

/**
 * @group large
 * @group Language
 * @covers \MediaWiki\Languages\LanguageConverterFactory
 */
class LanguageConverterFactoryTest extends MediaWikiLangTestCase {
	/**
	 * @dataProvider codeProvider
	 */
	public function testLanguageConverters(
		$langCode,
		$staticDefaultVariant,
		$type,
		$variants,
		$variantFallbacks,
		$variantNames,
		$flags,
		$manualLevel
	) {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( $langCode );
		$factory = new LanguageConverterFactory(
			new ServiceOptions( LanguageConverterFactory::CONSTRUCTOR_OPTIONS, new HashConfig( [
				MainConfigNames::UsePigLatinVariant => false,
				MainConfigNames::DisableLangConversion => false,
				MainConfigNames::DisableTitleConversion => false,
			] ) ),
			$this->getServiceContainer()->getObjectFactory(),
			static function () use ( $lang ) {
				return $lang;
			}
		);
		$this->assertFalse( $factory->isConversionDisabled() );
		$this->assertFalse( $factory->isLinkConversionDisabled() );
		$converter = $factory->getLanguageConverter( $lang );
		$this->verifyConverter(
			$converter,
			$lang,
			$langCode,
			$staticDefaultVariant,
			$type,
			$variants,
			$variantFallbacks,
			$variantNames,
			$flags,
			$manualLevel
		);
	}

	public function testCreateFromCodeEnPigLatin() {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$factory = new LanguageConverterFactory(
			new ServiceOptions( LanguageConverterFactory::CONSTRUCTOR_OPTIONS, new HashConfig( [
				MainConfigNames::UsePigLatinVariant => true,
				MainConfigNames::DisableLangConversion => false,
				MainConfigNames::DisableTitleConversion => false,
			] ) ),
			$this->getServiceContainer()->getObjectFactory(),
			static function () use ( $lang ) {
				return $lang;
			}
		);
		$this->assertFalse( $factory->isConversionDisabled() );
		$this->assertFalse( $factory->isLinkConversionDisabled() );

		$converter = $factory->getLanguageConverter( $lang );

		$this->verifyConverter(
			$converter,
			$lang,
			'en',
			'en',
			EnConverter::class,
			[ 'en', 'en-x-piglatin' ],
			[],
			[],
			[],
			[ 'en' => 'bidirectional', 'en-x-piglatin' => 'bidirectional' ]
		);
	}

	/**
	 * @dataProvider booleanProvider
	 */
	public function testDisabledBooleans( $pigLatinDisabled, $conversionDisabled, $titleDisabled ) {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$factory = new LanguageConverterFactory(
			new ServiceOptions( LanguageConverterFactory::CONSTRUCTOR_OPTIONS, new HashConfig( [
				MainConfigNames::UsePigLatinVariant => !$pigLatinDisabled,
				MainConfigNames::DisableLangConversion => $conversionDisabled,
				MainConfigNames::DisableTitleConversion => $titleDisabled,
			] ) ),
			$this->getServiceContainer()->getObjectFactory(),
			static function () use ( $lang ) {
				return $lang;
			}
		);
		$converter = $factory->getLanguageConverter( $lang );

		$this->assertSame( $conversionDisabled, $factory->isConversionDisabled() );
		$this->assertSame( $conversionDisabled || $titleDisabled, $factory->isLinkConversionDisabled() );

		if ( $pigLatinDisabled ) {
			$this->assertNotContains(
				'en-x-piglatin', $converter->getVariants()
			);
		} else {
			$this->assertContains(
				'en-x-piglatin', $converter->getVariants()
			);
		}
	}

	public static function booleanProvider() {
		return [
			[ false, false, false ],
			[ false, false, true ],
			[ false, true, false ],
			[ false, true, true ],
			[ true, false, false ],
			[ true, false, true ],
			[ true, true, false ],
			[ true, true, true ],
		];
	}

	public function testDefaultContentLanguageFallback() {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$factory = new LanguageConverterFactory(
			new ServiceOptions( LanguageConverterFactory::CONSTRUCTOR_OPTIONS, new HashConfig( [
				MainConfigNames::UsePigLatinVariant => false,
				MainConfigNames::DisableLangConversion => false,
				MainConfigNames::DisableTitleConversion => false,
			] ) ),
			$this->getServiceContainer()->getObjectFactory(),
			static function () use ( $lang ) {
				return $lang;
			}
		);
		$this->assertFalse( $factory->isConversionDisabled() );
		$this->assertFalse( $factory->isLinkConversionDisabled() );

		$converter = $factory->getLanguageConverter();

		$this->verifyConverter(
			$converter,
			$lang,
			'en',
			'en',
			TrivialLanguageConverter::class,
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
		$langCode,
		$staticDefaultVariant,
		$type,
		$variants,
		$variantFallbacks,
		$variantNames,
		$flags,
		$manualLevel
	) {
		$this->assertInstanceOf( $type, $converter );

		if ( $converter instanceof LanguageConverter ) {
			$testConverter = TestingAccessWrapper::newFromObject( $converter );
			$this->assertSame( $lang, $testConverter->mLangObj, "Language should be as provided" );

			$this->assertEquals( $langCode, $testConverter->getMainCode(),
				"getMainCode should be as $langCode" );
			$this->assertEquals( $staticDefaultVariant, $testConverter->getStaticDefaultVariant(),
				"getStaticDefaultVariant should be as $staticDefaultVariant" );
			$this->assertEquals( $manualLevel, $testConverter->getManualLevel(), "Manual Level" );

			$this->assertEquals( $variants, $testConverter->getVariants(), "Variants" );
			$this->assertEquals( $variantFallbacks, $testConverter->getVariantsFallbacks(), "Variant Fallbacks" );
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
				$converter->getFlags(),
				"Flags"
			);
		}
	}

	public static function codeProvider() {
		/** @phpcs-require-sorted-array */
		$trivialWithNothingElseCodes = [
			'aa',
			'ab',
			'abs',
			'ace',
			'ady',
			'ady-cyrl',
			'aeb',
			'aeb-arab',
			'aeb-latn',
			'af',
			'aln',
			'als',
			'am',
			'an',
			'ang',
			'anp',
			'ar',
			'arc',
			'arn',
			'arq',
			'ary',
			'arz',
			'as',
			'ase',
			'ast',
			'atj',
			'av',
			'avk',
			'awa',
			'ay',
			'az',
			'azb',
			'ba',
			'ban-bali',
			'bar',
			'bat-smg',
			'bbc',
			'bbc-latn',
			'bcc',
			'bcl',
			'be',
			'be-tarask',
			'be-x-old',
			'bg',
			'bgn',
			'bh',
			'bho',
			'bi',
			'bjn',
			'bm',
			'bn',
			'bo',
			'bpy',
			'bqi',
			'br',
			'brh',
			'bs',
			'btm',
			'bto',
			'bug',
			'bxr',
			'ca',
			'cbk-zam',
			'cdo',
			'ce',
			'ceb',
			'ch',
			'cho',
			'chr',
			'chy',
			'ckb',
			'co',
			'cps',
			'cr',
			'crh-cyrl',
			'crh-latn',
			'cs',
			'csb',
			'cu',
			'cv',
			'cy',
			'da',
			'de',
			'de-at',
			'de-ch',
			'de-formal',
			'din',
			'diq',
			'dsb',
			'dtp',
			'dty',
			'dv',
			'dz',
			'ee',
			'egl',
			'el',
			'eml',
			'en',
			'en-ca',
			'en-gb',
			'eo',
			'es',
			'es-419',
			'es-formal',
			'et',
			'eu',
			'ext',
			'fa',
			'ff',
			'fi',
			'fit',
			'fiu-vro',
			'fj',
			'fo',
			'fr',
			'frc',
			'frp',
			'frr',
			'fur',
			'fy',
			'ga',
			'gag',
			'gan-hans',
			'gan-hant',
			'gcr',
			'gd',
			'gl',
			'glk',
			'gn',
			'gom',
			'gom-deva',
			'gom-latn',
			'gor',
			'got',
			'grc',
			'gsw',
			'gu',
			'gv',
			'ha',
			'hak',
			'haw',
			'he',
			'hi',
			'hif',
			'hif-latn',
			'hil',
			'ho',
			'hoc-latn',
			'hr',
			'hrx',
			'hsb',
			'ht',
			'hu',
			'hu-formal',
			'hy',
			'hyw',
			'hz',
			'ia',
			'id',
			'ie',
			'ig',
			'ii',
			'ik',
			'ike-cans',
			'ike-latn',
			'ilo',
			'inh',
			'io',
			'is',
			'it',
			'ja',
			'jam',
			'jbo',
			'jut',
			'jv',
			'ka',
			'kaa',
			'kab',
			'kbd',
			'kbd-cyrl',
			'kbp',
			'kg',
			'khw',
			'ki',
			'kiu',
			'kj',
			'kjp',
			'kl',
			'km',
			'kn',
			'ko',
			'ko-kp',
			'koi',
			'kr',
			'krc',
			'kri',
			'krj',
			'krl',
			'ks',
			'ks-arab',
			'ks-deva',
			'ksh',
			'ku-arab',
			'ku-latn',
			'kum',
			'kv',
			'kw',
			'ky',
			'la',
			'lad',
			'lb',
			'lbe',
			'lez',
			'lfn',
			'lg',
			'li',
			'lij',
			'liv',
			'lki',
			'lmo',
			'ln',
			'lo',
			'loz',
			'lrc',
			'lt',
			'ltg',
			'lus',
			'luz',
			'lv',
			'lzh',
			'lzz',
			'mai',
			'map-bms',
			'mdf',
			'mg',
			'mh',
			'mhr',
			'mi',
			'min',
			'mk',
			'ml',
			'mn',
			'mnw',
			'mo',
			'mr',
			'mrj',
			'ms',
			'mt',
			'mus',
			'mwl',
			'my',
			'myv',
			'mzn',
			'na',
			'nah',
			'nan',
			'nap',
			'nb',
			'nds',
			'nds-nl',
			'ne',
			'new',
			'ng',
			'niu',
			'nl',
			'nl-informal',
			'nn',
			'no',
			'nov',
			'nqo',
			'nrm',
			'nso',
			'nv',
			'ny',
			'nys',
			'oc',
			'olo',
			'om',
			'or',
			'os',
			'pa',
			'pag',
			'pam',
			'pap',
			'pcd',
			'pdc',
			'pdt',
			'pfl',
			'pi',
			'pih',
			'pl',
			'pms',
			'pnb',
			'pnt',
			'prg',
			'ps',
			'pt',
			'pt-br',
			'qu',
			'qug',
			'rgn',
			'rif',
			'rm',
			'rmy',
			'rn',
			'ro',
			'roa-rup',
			'roa-tara',
			'ru',
			'rue',
			'rup',
			'ruq',
			'ruq-cyrl',
			'ruq-latn',
			'rw',
			'sa',
			'sah',
			'sat',
			'sc',
			'scn',
			'sco',
			'sd',
			'sdc',
			'sdh',
			'se',
			'sei',
			'ses',
			'sg',
			'sgs',
			'sh-cyrl',
			'sh-latn',
			'shi-latn',
			'shi-tfng',
			'shn',
			'shy-latn',
			'si',
			'simple',
			'sk',
			'skr',
			'skr-arab',
			'sl',
			'sli',
			'sm',
			'sma',
			'sn',
			'so',
			'sq',
			'sr-ec',
			'sr-el',
			'srn',
			'ss',
			'st',
			'stq',
			'sty',
			'su',
			'sv',
			'sw',
			'szl',
			'szy',
			'ta',
			'tay',
			'tcy',
			'te',
			'tet',
			'tg-cyrl',
			'tg-latn',
			'th',
			'ti',
			'tk',
			'tl',
			'tly-latn',
			'tn',
			'to',
			'tpi',
			'tr',
			'tru',
			'ts',
			'tt',
			'tt-cyrl',
			'tt-latn',
			'tum',
			'tw',
			'ty',
			'tyv',
			'tzm',
			'udm',
			'ug',
			'ug-arab',
			'ug-latn',
			'uk',
			'ur',
			'uz-cyrl',
			'uz-latn',
			've',
			'vec',
			'vep',
			'vi',
			'vls',
			'vmf',
			'vo',
			'vot',
			'vro',
			'wa',
			'war',
			'wo',
			'wuu-hans',
			'wuu-hant',
			'xal',
			'xh',
			'xmf',
			'xsy',
			'yi',
			'yo',
			'yue',
			'yue-hans',
			'yue-hant',
			'za',
			'zea',
			'zh-classical',
			'zh-cn',
			'zh-hans',
			'zh-hant',
			'zh-hk',
			'zh-min-nan',
			'zh-mo',
			'zh-my',
			'zh-sg',
			'zh-tw',
			'zu',
		];
		foreach ( $trivialWithNothingElseCodes as $code ) {
			# $langCode, $mainVariantCode, $type, $variants, $variantFallbacks, $variantNames, $flags, $manualLevel
			yield $code => [ $code, $code, TrivialLanguageConverter::class, [], [], [], [], [] ];
		}

		// Languages with a type of than TrivialLanguageConverter or with variants/flags/manual level
		yield 'ban' => [
			'ban', 'ban', BanConverter::class,
			[ 'ban', 'ban-bali', 'ban-x-dharma', 'ban-x-palmleaf', 'ban-x-pku' ],
			[
				'ban-bali' => 'ban',
				'ban-x-dharma' => 'ban',
				'ban-x-palmleaf' => 'ban',
				'ban-x-pku' => 'ban',
			], [], [], [
				'ban' => 'bidirectional',
				'ban-bali' => 'bidirectional',
				'ban-x-dharma' => 'bidirectional',
				'ban-x-palmleaf' => 'bidirectional',
				'ban-x-pku' => 'bidirectional',
			]
		];

		yield 'crh' => [
			'crh', 'crh', CrhConverter::class,
			[ 'crh', 'crh-cyrl', 'crh-latn' ],
			[
				'crh' => 'crh-latn',
				'crh-cyrl' => 'crh-latn',
				'crh-latn' => 'crh-cyrl',
			], [], [], [
				'crh' => 'bidirectional',
				'crh-cyrl' => 'bidirectional',
				'crh-latn' => 'bidirectional'
			]
		];

		yield 'gan' => [
			'gan', 'gan', GanConverter::class,
			[ 'gan', 'gan-hans', 'gan-hant' ],
			[
				'gan' => [ 'gan-hans', 'gan-hant' ],
				'gan-hans' => [ 'gan' ],
				'gan-hant' => [ 'gan' ],
			], [], [], [
				'gan' => 'disable',
				'gan-hans' => 'bidirectional',
				'gan-hant' => 'bidirectional'
			]
		];

		yield 'iu' => [
			'iu', 'iu', IuConverter::class,
			[ 'iu', 'ike-cans', 'ike-latn' ],
			[
				'iu' => 'ike-cans',
				'ike-cans' => 'iu',
				'ike-latn' => 'iu',
			], [], [], [
				'iu' => 'bidirectional',
				'ike-cans' => 'bidirectional',
				'ike-latn' => 'bidirectional'
			]
		];

		yield 'ku' => [
			'ku', 'ku', KuConverter::class,
			[ 'ku', 'ku-arab', 'ku-latn' ],
			[
				'ku' => 'ku-latn',
				'ku-arab' => 'ku-latn',
				'ku-latn' => 'ku-arab'
			], [], [], [
				'ku' => 'bidirectional',
				'ku-arab' => 'bidirectional',
				'ku-latn' => 'bidirectional'
			]
		];

		yield 'mni' => [
			'mni', 'mni', MniConverter::class,
			[ 'mni', 'mni-beng' ],
			[
				'mni-beng' => 'mni'
			], [], [], [
				'mni' => 'bidirectional',
				'mni-beng' => 'bidirectional'
			]
		];

		yield 'sh' => [
			'sh', 'sh-latn', ShConverter::class,
			[ 'sh-latn', 'sh-cyrl' ],
			[ 'sh-cyrl' => 'sh-latn' ],
			[], [], [
				'sh-latn' => 'bidirectional',
				'sh-cyrl' => 'bidirectional'
			]
		];

		yield 'shi' => [
			'shi', 'shi', ShiConverter::class,
			[ 'shi', 'shi-tfng', 'shi-latn' ],
			[
				'shi' => [ 'shi-latn', 'shi-tfng' ],
				'shi-tfng' => 'shi',
				'shi-latn' => 'shi'
			], [], [], [
				'shi' => 'bidirectional',
				'shi-tfng' => 'bidirectional',
				'shi-latn' => 'bidirectional'
			]
		];

		yield 'sr' => [
			'sr', 'sr', SrConverter::class,
			[ 'sr', 'sr-ec', 'sr-el' ],
			[
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
		];

		yield 'tg' => [
			'tg', 'tg', TgConverter::class,
			[ 'tg', 'tg-latn' ],
			[], [], [], [
				'tg' => 'bidirectional',
				'tg-latn' => 'bidirectional'
			]
		];

		yield 'tly' => [
			'tly', 'tly', TlyConverter::class,
			[ 'tly', 'tly-cyrl' ],
			[ 'tly-cyrl' => 'tly' ],
			[],
			[
				'tly' => 'tly',
				'tly-cyrl' => 'tly-cyrl'
			],
			[
				'tly' => 'bidirectional',
				'tly-cyrl' => 'bidirectional',
			]
		];

		yield 'uz' => [
			'uz', 'uz', UzConverter::class,
			[ 'uz', 'uz-latn', 'uz-cyrl' ],
			[
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
		];

		yield 'wuu' => [
			'wuu', 'wuu', WuuConverter::class,
			[ 'wuu', 'wuu-hans', 'wuu-hant' ],
			[
				'wuu' => [ 'wuu-hans', 'wuu-hant' ],
				'wuu-hans' => [ 'wuu' ],
				'wuu-hant' => [ 'wuu' ],
			], [], [], [
				'wuu' => 'disable',
				'wuu-hans' => 'bidirectional',
				'wuu-hant' => 'bidirectional'
			]
		];

		yield 'zgh' => [
			'zgh', 'zgh', ZghConverter::class,
			[ 'zgh', 'zgh-latn' ],
			[], [], [], [
				'zgh' => 'bidirectional',
				'zgh-latn' => 'bidirectional'
			]
		];

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
			'zh-sg' => [ 'zh-my', 'zh-hans', 'zh-cn' ],
			'zh-my' => [ 'zh-sg', 'zh-hans', 'zh-cn' ],
			'zh-tw' => [ 'zh-hant', 'zh-hk', 'zh-mo' ],
			'zh-hk' => [ 'zh-mo', 'zh-hant', 'zh-tw' ],
			'zh-mo' => [ 'zh-hk', 'zh-hant', 'zh-tw' ],
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
		yield 'zh' => [ 'zh', 'zh', ZhConverter::class, $zh_variants, $zh_variantfallbacks, [], $zh_flags, $zh_ml ];
	}
}
