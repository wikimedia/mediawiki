( function () {
	'use strict';

	QUnit.module( 'mediawiki.language', QUnit.newMwEnvironment( {
		beforeEach: function () {
			this.userLang = mw.config.get( 'wgUserLanguage' );
			this.liveLangData = mw.language.data;
			mw.language.data = {};
		},
		afterEach: function () {
			mw.language.data = this.liveLangData;
			mw.config.set( 'wgUserLanguage', this.userLang );
		},
		messages: {
			// mw.language.listToText test
			and: ' and',
			'comma-separator': ', ',
			'word-separator': ' '
		}
	} ) );

	QUnit.test( 'mw.language getData and setData', ( assert ) => {
		mw.language.setData( 'en', 'testkey', 'testvalue' );
		assert.strictEqual( mw.language.getData( 'en', 'testkey' ), 'testvalue', 'Getter setter test for mw.language' );
		assert.strictEqual( mw.language.getData( 'en', 'invalidkey' ), null, 'Getter setter test for mw.language with invalid key' );
		assert.strictEqual( mw.language.getData( 'xx', 'invalidLang' ), undefined, 'Getter setter test for mw.language with invalid lang' );
		mw.language.setData( 'en-us', 'testkey', 'testvalue' );
		assert.strictEqual( mw.language.getData( 'en-US', 'testkey' ), 'testvalue', 'Case insensitive test for mw.language' );
	} );

	QUnit.test( 'mw.language.convertNumber', ( assert ) => {
		mw.language.setData( 'en', 'digitGroupingPattern', null );
		mw.language.setData( 'en', 'digitTransformTable', null );
		mw.language.setData( 'en', 'separatorTransformTable', { ',': '.', '.': ',' } );
		mw.language.setData( 'en', 'minimumGroupingDigits', 1 );
		mw.config.set( 'wgUserLanguage', 'en' );
		mw.config.set( 'wgTranslateNumerals', true );

		assert.strictEqual( mw.language.convertNumber( 180 ), '180', 'formatting 3-digit' );
		assert.strictEqual( mw.language.convertNumber( 1800 ), '1.800', 'formatting 4-digit' );
		assert.strictEqual( mw.language.convertNumber( 18000 ), '18.000', 'formatting 5-digit' );

		assert.strictEqual( mw.language.convertNumber( '1.800', true ), 1800, 'unformatting' );

		mw.language.setData( 'en', 'minimumGroupingDigits', 2 );
		assert.strictEqual( mw.language.convertNumber( 180 ), '180', 'formatting 3-digit with minimumGroupingDigits=2' );
		assert.strictEqual( mw.language.convertNumber( 1800 ), '1800', 'formatting 4-digit with minimumGroupingDigits=2' );
		assert.strictEqual( mw.language.convertNumber( 18000 ), '18.000', 'formatting 5-digit with minimumGroupingDigits=2' );

		mw.language.setData( 'en', 'minimumGroupingDigits', 3 );
		assert.strictEqual( mw.language.convertNumber( 180 ), '180', 'formatting 3-digit with minimumGroupingDigits=3' );
		assert.strictEqual( mw.language.convertNumber( 1800 ), '1800', 'formatting 4-digit with minimumGroupingDigits=3' );
		assert.strictEqual( mw.language.convertNumber( 18000 ), '18000', 'formatting 5-digit with minimumGroupingDigits=3' );
		assert.strictEqual( mw.language.convertNumber( 180000 ), '180.000', 'formatting 6-digit with minimumGroupingDigits=3' );
	} );

	QUnit.test( 'mw.language.convertNumber - digitTransformTable', ( assert ) => {
		mw.config.set( 'wgUserLanguage', 'hi' );
		mw.config.set( 'wgTranslateNumerals', true );
		mw.language.setData( 'hi', 'digitGroupingPattern', null );
		mw.language.setData( 'hi', 'separatorTransformTable', { ',': '.', '.': ',' } );
		mw.language.setData( 'hi', 'minimumGroupingDigits', null );

		// Example from Hindi (MessagesHi.php)
		mw.language.setData( 'hi', 'digitTransformTable', {
			0: '०',
			1: '१',
			2: '२'
		} );

		assert.strictEqual( mw.language.convertNumber( 1200 ), '१.२००', 'format' );
		assert.strictEqual( mw.language.convertNumber( '१.२००', true ), 1200, 'unformat from digit transform' );
		assert.strictEqual( mw.language.convertNumber( '1.200', true ), 1200, 'unformat plain' );

		mw.config.set( 'wgTranslateNumerals', false );

		assert.strictEqual( mw.language.convertNumber( 1200 ), '1.200', 'format (digit transform disabled)' );
		assert.strictEqual( mw.language.convertNumber( '१.२००', true ), 1200, 'unformat from digit transform (when disabled)' );
		assert.strictEqual( mw.language.convertNumber( '1.200', true ), 1200, 'unformat plain (digit transform disabled)' );
	} );

	// These tests run only for the current UI language.
	const grammarTests = {
		bs: [
			{
				word: 'word',
				grammarForm: 'instrumental',
				expected: 's word',
				description: 'Grammar test for instrumental case'
			},
			{
				word: 'word',
				grammarForm: 'lokativ',
				expected: 'o word',
				description: 'Grammar test for lokativ case'
			}
		],

		he: [
			{
				word: 'ויקיפדיה',
				grammarForm: 'prefixed',
				expected: 'וויקיפדיה',
				description: 'Duplicate the "Waw" if prefixed'
			},
			{
				word: 'וולפגנג',
				grammarForm: 'prefixed',
				expected: 'וולפגנג',
				description: 'Duplicate the "Waw" if prefixed, but not if it is already duplicated.'
			},
			{
				word: 'הקובץ',
				grammarForm: 'prefixed',
				expected: 'קובץ',
				description: 'Remove the "He" if prefixed'
			},
			{
				word: 'Wikipedia',
				grammarForm: 'תחילית',
				expected: '־Wikipedia',
				description: 'Add a hyphen (maqaf) before non-Hebrew letters'
			},
			{
				word: '1995',
				grammarForm: 'תחילית',
				expected: '־1995',
				description: 'Add a hyphen (maqaf) before numbers'
			}
		],

		hsb: [
			{
				word: 'word',
				grammarForm: 'instrumental',
				expected: 'z word',
				description: 'Grammar test for instrumental case'
			},
			{
				word: 'word',
				grammarForm: 'lokatiw',
				expected: 'wo word',
				description: 'Grammar test for lokatiw case'
			}
		],

		dsb: [
			{
				word: 'word',
				grammarForm: 'instrumental',
				expected: 'z word',
				description: 'Grammar test for instrumental case'
			},
			{
				word: 'word',
				grammarForm: 'lokatiw',
				expected: 'wo word',
				description: 'Grammar test for lokatiw case'
			}
		],

		hy: [
			{
				word: 'Մաունա',
				grammarForm: 'genitive',
				expected: 'Մաունայի',
				description: 'Grammar test for genitive case'
			},
			{
				word: 'հետո',
				grammarForm: 'genitive',
				expected: 'հետոյի',
				description: 'Grammar test for genitive case'
			},
			{
				word: 'գիրք',
				grammarForm: 'genitive',
				expected: 'գրքի',
				description: 'Grammar test for genitive case'
			},
			{
				word: 'ժամանակի',
				grammarForm: 'genitive',
				expected: 'ժամանակիի',
				description: 'Grammar test for genitive case'
			}
		],

		fi: [
			{
				word: 'talo',
				grammarForm: 'genitive',
				expected: 'talon',
				description: 'Grammar test for genitive case'
			},
			{
				word: 'linux',
				grammarForm: 'genitive',
				expected: 'linuxin',
				description: 'Grammar test for genitive case'
			},
			{
				word: 'talo',
				grammarForm: 'elative',
				expected: 'talosta',
				description: 'Grammar test for elative case'
			},
			{
				word: 'pastöroitu',
				grammarForm: 'partitive',
				expected: 'pastöroitua',
				description: 'Grammar test for partitive case'
			},
			{
				word: 'talo',
				grammarForm: 'partitive',
				expected: 'taloa',
				description: 'Grammar test for partitive case'
			},
			{
				word: 'talo',
				grammarForm: 'illative',
				expected: 'taloon',
				description: 'Grammar test for illative case'
			},
			{
				word: 'linux',
				grammarForm: 'inessive',
				expected: 'linuxissa',
				description: 'Grammar test for inessive case'
			}
		],

		ka: [
			{
				word: 'ვიკიპედია',
				grammarForm: 'ნათესაობითი',
				expected: 'ვიკიპედიის',
				description: 'Grammar test for Georgian genitive case'
			},
			{
				word: 'ვიქსიკონი',
				grammarForm: 'ნათესაობითი',
				expected: 'ვიქსიკონის',
				description: 'Grammar test for Georgian genitive case'
			},
			{
				word: 'ვიკიწიგნები',
				grammarForm: 'ნათესაობითი',
				expected: 'ვიკიწიგნების',
				description: 'Grammar test for Georgian genitive case'
			},
			{
				word: 'ვიკიციტატა',
				grammarForm: 'ნათესაობითი',
				expected: 'ვიკიციტატის',
				description: 'Grammar test for Georgian genitive case'
			},
			{
				word: 'ვიკისიახლეები',
				grammarForm: 'ნათესაობითი',
				expected: 'ვიკისიახლეების',
				description: 'Grammar test for Georgian genitive case'
			},
			{
				word: 'ვიკისახეობები',
				grammarForm: 'ნათესაობითი',
				expected: 'ვიკისახეობების',
				description: 'Grammar test for Georgian genitive case'
			},
			{
				word: 'ვიკიმონაცემები',
				grammarForm: 'ნათესაობითი',
				expected: 'ვიკიმონაცემების',
				description: 'Grammar test for Georgian genitive case'
			},
			{
				word: 'ვიკისაწყობი',
				grammarForm: 'ნათესაობითი',
				expected: 'ვიკისაწყობის',
				description: 'Grammar test for Georgian genitive case'
			},
			{
				word: 'ვიკივოიაჟი',
				grammarForm: 'ნათესაობითი',
				expected: 'ვიკივოიაჟის',
				description: 'Grammar test for Georgian genitive case'
			},
			{
				word: 'მეტა-ვიკი',
				grammarForm: 'ნათესაობითი',
				expected: 'მეტა-ვიკის',
				description: 'Grammar test for Georgian genitive case'
			},
			{
				word: 'მედიავიკი',
				grammarForm: 'ნათესაობითი',
				expected: 'მედიავიკის',
				description: 'Grammar test for Georgian genitive case'
			},
			{
				word: 'ვიკივერსიტეტი',
				grammarForm: 'ნათესაობითი',
				expected: 'ვიკივერსიტეტის',
				description: 'Grammar test for Georgian genitive case'
			},
			{
				word: 'თავისუფლება',
				grammarForm: 'ნათესაობითი',
				expected: 'თავისუფლების',
				description: 'Grammar test for Georgian genitive case'
			}
		],

		ru: [
			{
				word: 'тесть',
				grammarForm: 'genitive',
				expected: 'тестя',
				description: 'Grammar test for genitive case, тесть -> тестя'
			},
			{
				word: 'привилегия',
				grammarForm: 'genitive',
				expected: 'привилегии',
				description: 'Grammar test for genitive case, привилегия -> привилегии'
			},
			{
				word: 'установка',
				grammarForm: 'genitive',
				expected: 'установки',
				description: 'Grammar test for genitive case, установка -> установки'
			},
			{
				word: 'похоти',
				grammarForm: 'genitive',
				expected: 'похотей',
				description: 'Grammar test for genitive case, похоти -> похотей'
			},
			{
				word: 'доводы',
				grammarForm: 'genitive',
				expected: 'доводов',
				description: 'Grammar test for genitive case, доводы -> доводов'
			},
			{
				word: 'песчаник',
				grammarForm: 'genitive',
				expected: 'песчаника',
				description: 'Grammar test for genitive case, песчаник -> песчаника'
			},
			{
				word: 'данные',
				grammarForm: 'genitive',
				expected: 'данных',
				description: 'Grammar test for genitive case, данные -> данных'
			},
			{
				word: 'тесть',
				grammarForm: 'prepositional',
				expected: 'тесте',
				description: 'Grammar test for prepositional case, тесть -> тесте'
			},
			{
				word: 'привилегия',
				grammarForm: 'prepositional',
				expected: 'привилегии',
				description: 'Grammar test for prepositional case, привилегия -> привилегии'
			},
			{
				word: 'университет',
				grammarForm: 'prepositional',
				expected: 'университете',
				description: 'Grammar test for prepositional case, университет -> университете'
			},
			{
				word: 'университет',
				grammarForm: 'genitive',
				expected: 'университета',
				description: 'Grammar test for prepositional case, университет -> университете'
			},
			{
				word: 'установка',
				grammarForm: 'prepositional',
				expected: 'установке',
				description: 'Grammar test for prepositional case, установка -> установке'
			},
			{
				word: 'похоти',
				grammarForm: 'prepositional',
				expected: 'похотях',
				description: 'Grammar test for prepositional case, похоти -> похотях'
			},
			{
				word: 'доводы',
				grammarForm: 'prepositional',
				expected: 'доводах',
				description: 'Grammar test for prepositional case, доводы -> доводах'
			},
			{
				word: 'Викисклад',
				grammarForm: 'prepositional',
				expected: 'Викискладе',
				description: 'Grammar test for prepositional case, Викисклад -> Викискладе'
			},
			{
				word: 'Викисклад',
				grammarForm: 'genitive',
				expected: 'Викисклада',
				description: 'Grammar test for genitive case, Викисклад -> Викисклада'
			},
			{
				word: 'Википедия',
				grammarForm: 'accusative',
				expected: 'Википедию',
				description: 'Grammar test for accusative case, Википедия -> Вики'
			},
			{
				word: 'Викисловарь',
				grammarForm: 'accusative',
				expected: 'Викисловарь',
				description: 'Grammar test for accusative case, Викисловарь -> Викисловарь'
			},
			{
				word: 'Викицитатник',
				grammarForm: 'accusative',
				expected: 'Викицитатник',
				description: 'Grammar test for accusative case, Викицитатник -> Викицитатник'
			},
			{
				word: 'Викиучебник',
				grammarForm: 'accusative',
				expected: 'Викиучебник',
				description: 'Grammar test for accusative case, Викиучебник -> Викиучебник'
			},
			{
				word: 'Викитека',
				grammarForm: 'accusative',
				expected: 'Викитеку',
				description: 'Grammar test for accusative case, Викитека -> Викитеку'
			},
			{
				word: 'Викиновости',
				grammarForm: 'accusative',
				expected: 'Викиновости',
				description: 'Grammar test for accusative case, Викиновости -> Викиновости'
			},
			{
				word: 'Викиверситет',
				grammarForm: 'accusative',
				expected: 'Викиверситет',
				description: 'Grammar test for accusative case, Викиверситет -> Викиверситет'
			},
			{
				word: 'Викивиды',
				grammarForm: 'accusative',
				expected: 'Викивиды',
				description: 'Grammar test for accusative case, Викивиды -> Викивиды'
			},
			{
				word: 'Викиданные',
				grammarForm: 'accusative',
				expected: 'Викиданные',
				description: 'Grammar test for accusative case, Викиданные -> Викиданные'
			},
			{
				word: 'Викисклад',
				grammarForm: 'accusative',
				expected: 'Викисклад',
				description: 'Grammar test for accusative case, Викисклад -> Викисклад'
			},
			{
				word: 'Викигид',
				grammarForm: 'accusative',
				expected: 'Викигид',
				description: 'Grammar test for accusative case, Викигид -> Викигид'
			},
			{
				word: 'Мета',
				grammarForm: 'accusative',
				expected: 'Мету',
				description: 'Grammar test for accusative case, Мета -> Мету'
			},
			{
				word: 'Инкубатор',
				grammarForm: 'accusative',
				expected: 'Инкубатор',
				description: 'Grammar test for accusative case, Инкубатор -> Инкубатор'
			},
			{
				word: 'песчаник',
				grammarForm: 'prepositional',
				expected: 'песчанике',
				description: 'Grammar test for prepositional case, песчаник -> песчанике'
			},
			{
				word: 'данные',
				grammarForm: 'prepositional',
				expected: 'данных',
				description: 'Grammar test for prepositional case, данные -> данных'
			},
			{
				word: 'русский',
				grammarForm: 'languagegen',
				expected: 'русского',
				description: 'Grammar test for languagegen case, русский -> русского'
			},
			{
				word: 'немецкий',
				grammarForm: 'languagegen',
				expected: 'немецкого',
				description: 'Grammar test for languagegen case, немецкий -> немецкого'
			},
			{
				word: 'иврит',
				grammarForm: 'languagegen',
				expected: 'иврита',
				description: 'Grammar test for languagegen case, иврит -> иврита'
			},
			{
				word: 'эсперанто',
				grammarForm: 'languagegen',
				expected: 'эсперанто',
				description: 'Grammar test for languagegen case, эсперанто -> эсперанто'
			},
			{
				word: 'русский',
				grammarForm: 'languageprep',
				expected: 'русском',
				description: 'Grammar test for languageprep case, русский -> русском'
			},
			{
				word: 'немецкий',
				grammarForm: 'languageprep',
				expected: 'немецком',
				description: 'Grammar test for languageprep case, немецкий -> немецком'
			},
			{
				word: 'идиш',
				grammarForm: 'languageprep',
				expected: 'идише',
				description: 'Grammar test for languageprep case, идиш -> идише'
			},
			{
				word: 'эсперанто',
				grammarForm: 'languageprep',
				expected: 'эсперанто',
				description: 'Grammar test for languageprep case, эсперанто -> эсперанто'
			},
			{
				word: 'русский',
				grammarForm: 'languageadverb',
				expected: 'по-русски',
				description: 'Grammar test for languageadverb case, русский -> по-русски'
			},
			{
				word: 'немецкий',
				grammarForm: 'languageadverb',
				expected: 'по-немецки',
				description: 'Grammar test for languageadverb case, немецкий -> по-немецки'
			},
			{
				word: 'иврит',
				grammarForm: 'languageadverb',
				expected: 'на иврите',
				description: 'Grammar test for languageadverb case, иврит -> на иврите'
			},
			{
				word: 'эсперанто',
				grammarForm: 'languageadverb',
				expected: 'на эсперанто',
				description: 'Grammar test for languageadverb case, эсперанто -> на эсперанто'
			},
			{
				word: 'гуарани',
				grammarForm: 'languageadverb',
				expected: 'на языке гуарани',
				description: 'Grammar test for languageadverb case, гуарани -> на языке гуарани'
			}
		],

		hu: [
			{
				word: 'Wikipédiá',
				grammarForm: 'rol',
				expected: 'Wikipédiáról',
				description: 'Grammar test for rol case'
			},
			{
				word: 'Wikipédiá',
				grammarForm: 'ba',
				expected: 'Wikipédiába',
				description: 'Grammar test for ba case'
			},
			{
				word: 'Wikipédiá',
				grammarForm: 'k',
				expected: 'Wikipédiák',
				description: 'Grammar test for k case'
			}
		],

		ga: [
			{
				word: 'an Domhnach',
				grammarForm: 'ainmlae',
				expected: 'Dé Domhnaigh',
				description: 'Grammar test for ainmlae case'
			},
			{
				word: 'an Luan',
				grammarForm: 'ainmlae',
				expected: 'Dé Luain',
				description: 'Grammar test for ainmlae case'
			},
			{
				word: 'an Satharn',
				grammarForm: 'ainmlae',
				expected: 'Dé Sathairn',
				description: 'Grammar test for ainmlae case'
			}
		],

		mn: [
			{
				word: 'Википедиа',
				grammarForm: 'genitive',
				expected: 'Википедиагийн',
				description: 'Grammar test for genitive case'
			},
			{
				word: 'Викитолийн',
				grammarForm: 'genitive',
				expected: 'Викитолийн',
				description: 'Grammar test for genitive case'
			}
		],

		uk: [
			{
				word: 'Вікіпедія',
				grammarForm: 'genitive',
				expected: 'Вікіпедії',
				description: 'Grammar test for genitive case'
			},
			{
				word: 'Віківиди',
				grammarForm: 'genitive',
				expected: 'Віківидів',
				description: 'Grammar test for genitive case'
			},
			{
				word: 'Вікіцитати',
				grammarForm: 'genitive',
				expected: 'Вікіцитат',
				description: 'Grammar test for genitive case'
			},
			{
				word: 'Вікіпідручник',
				grammarForm: 'genitive',
				expected: 'Вікіпідручника',
				description: 'Grammar test for genitive case'
			},
			{
				word: 'Вікіпедія',
				grammarForm: 'accusative',
				expected: 'Вікіпедію',
				description: 'Grammar test for accusative case'
			}
		],

		sl: [
			{
				word: 'word',
				grammarForm: 'orodnik',
				expected: 'z word',
				description: 'Grammar test for orodnik case'
			},
			{
				word: 'word',
				grammarForm: 'mestnik',
				expected: 'o word',
				description: 'Grammar test for mestnik case'
			}
		],

		os: [
			{
				word: 'бæстæ',
				grammarForm: 'genitive',
				expected: 'бæсты',
				description: 'Grammar test for genitive case'
			},
			{
				word: 'бæстæ',
				grammarForm: 'allative',
				expected: 'бæстæм',
				description: 'Grammar test for allative case'
			},
			{
				word: 'Тигр',
				grammarForm: 'dative',
				expected: 'Тигрæн',
				description: 'Grammar test for dative case'
			},
			{
				word: 'цъити',
				grammarForm: 'dative',
				expected: 'цъитийæн',
				description: 'Grammar test for dative case'
			},
			{
				word: 'лæппу',
				grammarForm: 'genitive',
				expected: 'лæппуйы',
				description: 'Grammar test for genitive case'
			},
			{
				word: '2011',
				grammarForm: 'equative',
				expected: '2011-ау',
				description: 'Grammar test for equative case'
			}
		],

		la: [
			{
				word: 'Translatio',
				grammarForm: 'genitive',
				expected: 'Translationis',
				description: 'Grammar test for genitive case'
			},
			{
				word: 'Translatio',
				grammarForm: 'accusative',
				expected: 'Translationem',
				description: 'Grammar test for accusative case'
			},
			{
				word: 'Translatio',
				grammarForm: 'ablative',
				expected: 'Translatione',
				description: 'Grammar test for ablative case'
			}
		]
	};

	const languageTestData = require( 'mediawiki.language.testdata' );

	Object.keys( grammarTests ).forEach( ( langCode ) => {
		QUnit.test( `Language data for lang=${ langCode }`, ( assert ) => {
			assert.true(
				Object.prototype.hasOwnProperty.call( languageTestData, langCode ),
				`Missing test data for lang=${ langCode }.` +
				'Update the definition of the "mediawiki.language.testdata" module.'
			);
		} );

		QUnit.test( `Grammar test for lang=${ langCode }`, ( assert ) => {
			mw.config.set( 'wgUserLanguage', langCode );
			mw.language.setData( langCode, languageTestData[ langCode ] );

			const test = grammarTests[ langCode ];
			for ( let i = 0; i < test.length; i++ ) {
				assert.strictEqual(
					mw.language.convertGrammar( test[ i ].word, test[ i ].grammarForm ),
					test[ i ].expected,
					test[ i ].description
				);
			}
		} );
	} );

	QUnit.test( 'List to text test', ( assert ) => {
		assert.strictEqual( mw.language.listToText( [] ), '', 'Blank list' );
		assert.strictEqual( mw.language.listToText( [ 'a' ] ), 'a', 'Single item' );
		assert.strictEqual( mw.language.listToText( [ 'a', 'b' ] ), 'a and b', 'Two items' );
		assert.strictEqual( mw.language.listToText( [ 'a', 'b', 'c' ] ), 'a, b and c', 'More than two items' );
	} );

	const bcp47Tests = [
		// Extracted from BCP 47 (list not exhaustive)
		// # 2.1.1
		[ 'en-ca-x-ca', 'en-CA-x-ca' ],
		[ 'sgn-be-fr', 'sgn-BE-FR' ],
		[ 'az-latn-x-latn', 'az-Latn-x-latn' ],
		// # 2.2
		[ 'sr-Latn-RS', 'sr-Latn-RS' ],
		[ 'az-arab-ir', 'az-Arab-IR' ],

		// # 2.2.5
		[ 'sl-nedis', 'sl-nedis' ],
		[ 'de-ch-1996', 'de-CH-1996' ],

		// # 2.2.6
		[
			'en-latn-gb-boont-r-extended-sequence-x-private',
			'en-Latn-GB-boont-r-extended-sequence-x-private'
		],

		// Examples from BCP 47 Appendix A
		// # Simple language subtag:
		[ 'DE', 'de' ],
		[ 'fR', 'fr' ],
		[ 'ja', 'ja' ],

		// # Language subtag plus script subtag:
		[ 'zh-hans', 'zh-Hans' ],
		[ 'sr-cyrl', 'sr-Cyrl' ],
		[ 'sr-latn', 'sr-Latn' ],

		// # Extended language subtags and their primary language subtag
		// # counterparts:
		[ 'zh-cmn-hans-cn', 'zh-cmn-Hans-CN' ],
		[ 'cmn-hans-cn', 'cmn-Hans-CN' ],
		[ 'zh-yue-hk', 'zh-yue-HK' ],
		[ 'yue-hk', 'yue-HK' ],

		// # Language-Script-Region:
		[ 'zh-hans-cn', 'zh-Hans-CN' ],
		[ 'sr-latn-RS', 'sr-Latn-RS' ],

		// # Language-Variant:
		[ 'sl-rozaj', 'sl-rozaj' ],
		[ 'sl-rozaj-biske', 'sl-rozaj-biske' ],
		[ 'sl-nedis', 'sl-nedis' ],

		// # Language-Region-Variant:
		[ 'de-ch-1901', 'de-CH-1901' ],
		[ 'sl-it-nedis', 'sl-IT-nedis' ],

		// # Language-Script-Region-Variant:
		[ 'hy-latn-it-arevela', 'hy-Latn-IT-arevela' ],

		// # Language-Region:
		[ 'de-de', 'de-DE' ],
		[ 'en-us', 'en-US' ],
		[ 'es-419', 'es-419' ],

		// # Private use subtags:
		[ 'de-ch-x-phonebk', 'de-CH-x-phonebk' ],
		[ 'az-arab-x-aze-derbend', 'az-Arab-x-aze-derbend' ],
		/**
		 * Previous test does not reflect the BCP 47 which states:
		 *  az-Arab-x-AZE-derbend
		 * AZE being private, it should be lower case, hence the test above
		 * should probably be:
		 * [ 'az-arab-x-aze-derbend', 'az-Arab-x-AZE-derbend' ],
		 */

		// # Private use registry values:
		[ 'x-whatever', 'x-whatever' ],
		[ 'qaa-qaaa-qm-x-southern', 'qaa-Qaaa-QM-x-southern' ],
		[ 'de-qaaa', 'de-Qaaa' ],
		[ 'sr-latn-qm', 'sr-Latn-QM' ],
		[ 'sr-qaaa-rs', 'sr-Qaaa-RS' ],

		// # Tags that use extensions
		[ 'en-us-u-islamcal', 'en-US-u-islamcal' ],
		[ 'zh-cn-a-myext-x-private', 'zh-CN-a-myext-x-private' ],
		[ 'en-a-myext-b-another', 'en-a-myext-b-another' ],

		// # Invalid:
		// de-419-DE
		// a-DE
		// ar-a-aaa-b-bbb-a-ccc

		// Non-standard and deprecated language codes used by MediaWiki
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
		[ 'zh-hant', 'zh-Hant' ]
	];

	QUnit.test( 'mw.language.bcp47', function ( assert ) {
		mw.language.data = this.liveLangData;
		bcp47Tests.forEach( ( data ) => {
			const input = data[ 0 ],
				expected = data[ 1 ];
			assert.strictEqual( mw.language.bcp47( input ), expected );
			assert.strictEqual( mw.language.bcp47( input.toLowerCase() ), expected );
			assert.strictEqual( mw.language.bcp47( input.toUpperCase() ), expected );
		} );
	} );
}() );
