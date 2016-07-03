( function ( mw, $ ) {
	'use strict';

	QUnit.module( 'mediawiki.language', QUnit.newMwEnvironment( {
		setup: function () {
			this.liveLangData = mw.language.data.values;
			mw.language.data.values = $.extend( true, {}, this.liveLangData );
		},
		teardown: function () {
			mw.language.data.values = this.liveLangData;
		},
		messages: {
			// mw.language.listToText test
			and: ' and',
			'comma-separator': ', ',
			'word-separator': ' '
		}
	} ) );

	QUnit.test( 'mw.language getData and setData', 3, function ( assert ) {
		mw.language.setData( 'en', 'testkey', 'testvalue' );
		assert.equal( mw.language.getData( 'en', 'testkey' ), 'testvalue', 'Getter setter test for mw.language' );
		assert.equal( mw.language.getData( 'en', 'invalidkey' ), undefined, 'Getter setter test for mw.language with invalid key' );
		mw.language.setData( 'en-us', 'testkey', 'testvalue' );
		assert.equal( mw.language.getData( 'en-US', 'testkey' ), 'testvalue', 'Case insensitive test for mw.language' );
	} );

	QUnit.test( 'mw.language.commafy test', 9, function ( assert ) {
		mw.language.setData( 'en', 'digitGroupingPattern', null );
		mw.language.setData( 'en', 'digitTransformTable', null );
		mw.language.setData( 'en', 'separatorTransformTable', null );

		mw.config.set( 'wgUserLanguage', 'en' );
		// Number grouping patterns are as per http://cldr.unicode.org/translation/number-patterns
		assert.equal( mw.language.commafy( 1234.567, '###0.#####' ), '1234.567', 'Pattern with no digit grouping separator defined' );
		assert.equal( mw.language.commafy( 123456789.567, '###0.#####' ), '123456789.567', 'Pattern with no digit grouping separator defined, bigger decimal part' );
		assert.equal( mw.language.commafy( 0.567, '###0.#####' ), '0.567', 'Decimal part 0' );
		assert.equal( mw.language.commafy( '.567', '###0.#####' ), '0.567', 'Decimal part missing. replace with zero' );
		assert.equal( mw.language.commafy( 1234, '##,#0.#####' ), '12,34', 'Pattern with no fractional part' );
		assert.equal( mw.language.commafy( -1234.567, '###0.#####' ), '-1234.567', 'Negative number' );
		assert.equal( mw.language.commafy( -1234.567, '#,###.00' ), '-1,234.56', 'Fractional part bigger than pattern.' );
		assert.equal( mw.language.commafy( 123456789.567, '###,##0.00' ), '123,456,789.56', 'Decimal part as group of 3' );
		assert.equal( mw.language.commafy( 123456789.567, '###,###,#0.00' ), '1,234,567,89.56', 'Decimal part as group of 3 and last one 2' );
	} );

	function grammarTest( langCode, test ) {
		// The test works only if the content language is opt.language
		// because it requires [lang].js to be loaded.
		QUnit.test( 'Grammar test for lang=' + langCode, function ( assert ) {
			QUnit.expect( test.length );

			for ( var i = 0; i < test.length; i++ ) {
				assert.equal(
					mw.language.convertGrammar( test[ i ].word, test[ i ].grammarForm ),
					test[ i ].expected,
					test[ i ].description
				);
			}
		} );
	}

	// These tests run only for the current UI language.
	var grammarTests = {
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

	$.each( grammarTests, function ( langCode, test ) {
		if ( langCode === mw.config.get( 'wgUserLanguage' ) ) {
			grammarTest( langCode, test );
		}
	} );

	QUnit.test( 'List to text test', 4, function ( assert ) {
		assert.equal( mw.language.listToText( [] ), '', 'Blank list' );
		assert.equal( mw.language.listToText( [ 'a' ] ), 'a', 'Single item' );
		assert.equal( mw.language.listToText( [ 'a', 'b' ] ), 'a and b', 'Two items' );
		assert.equal( mw.language.listToText( [ 'a', 'b', 'c' ] ), 'a, b and c', 'More than two items' );
	} );
}( mediaWiki, jQuery ) );
