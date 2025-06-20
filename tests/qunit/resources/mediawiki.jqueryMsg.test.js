( function () {
	const jqueryMsg = require( 'mediawiki.jqueryMsg' ).test;

	/* eslint-disable camelcase */
	let formatText, formatParse, specialCharactersPageName, expectedListUsers,
		expectedListUsersSitename, expectedLinkPagenamee, expectedEntrypoints;
	const testData = require( 'mediawiki.language.jqueryMsg.testdata' ),
		phpParserData = testData.phpParserData;

	// When the expected result is the same in both modes
	function assertBothModes( assert, parserArguments, expectedResult, assertMessage ) {
		assert.strictEqual( formatText( ...parserArguments ), expectedResult, assertMessage + ' when format is \'text\'' );
		assert.strictEqual( formatParse( ...parserArguments ), expectedResult, assertMessage + ' when format is \'parse\'' );
	}

	QUnit.module( 'mediawiki.jqueryMsg', QUnit.newMwEnvironment( {
		beforeEach: function () {
			this.originalMwLanguage = mw.language;
			this.getMwLanguage = function ( langCode ) {
				mw.language = Object.create( this.originalMwLanguage );
				mw.language.setData( langCode, phpParserData.jsData[ langCode ] );
				testData.initLang( langCode );
				return mw.language;
			};
			this.parserDefaults = jqueryMsg.getParserDefaults();
			jqueryMsg.setParserDefaults( {
				magic: {
					SITENAME: 'Wiki',
					// Repeat parserDefaults.magic from mediawiki.jqueryMsg.js. The original
					// runs before the mock config is set up.
					PAGENAME: mw.config.get( 'wgPageName' ),
					PAGENAMEE: mw.util.wikiUrlencode( mw.config.get( 'wgPageName' ) ),
					SERVERNAME: mw.config.get( 'wgServerName' ),
					CONTENTLANGUAGE: mw.config.get( 'wgContentLanguage' )
				}
			} );

			specialCharactersPageName = '"Who" wants to be a millionaire & live on \'Exotic Island\'?';

			expectedListUsers = '注册<a title="Special:ListUsers" href="/wiki/Special:ListUsers">用户</a>';
			expectedListUsersSitename = '注册<a title="Special:ListUsers" href="/wiki/Special:ListUsers">用户' +
				'Wiki</a>';
			expectedLinkPagenamee = '<a href="https://example.org/wiki/Foo?bar=baz#val/2_%2B_2" class="external">Test</a>';

			expectedEntrypoints = '<a href="https://www.mediawiki.org/wiki/Manual:index.php" class="external">index.php</a>';

			formatText = jqueryMsg.getMessageFunction( {
				format: 'text'
			} );

			formatParse = jqueryMsg.getMessageFunction( {
				format: 'parse'
			} );
		},
		afterEach: function () {
			mw.language = this.originalMwLanguage;
			jqueryMsg.setParserDefaults( this.parserDefaults );
		},
		config: {
			wgPageName: '2 + 2',
			wgServerName: 'wiki.xyz',
			wgContentLanguage: 'sjn',
			wgArticlePath: '/wiki/$1',
			wgNamespaceIds: {
				template: 10,
				template_talk: 11,
				// Localised
				szablon: 10,
				dyskusja_szablonu: 11
			},
			wgFormattedNamespaces: {
				// Localised
				10: 'Szablon',
				11: 'Dyskusja szablonu'
			},
			wgCaseSensitiveNamespaces: []
		},
		// Messages that are reused in multiple tests
		messages: {
			// The values for gender are not significant,
			// what matters is which of the values is choosen by the parser
			'gender-msg': '$1: {{GENDER:$2|blue|pink|green}}',
			'gender-msg-currentuser': '{{GENDER:|blue|pink|green}}',

			'plural-msg': 'Found $1 {{PLURAL:$1|item|items}}',
			// See https://phabricator.wikimedia.org/T71993
			'plural-msg-explicit-forms-nested': 'Found {{PLURAL:$1|$1 results|0=no results in {{SITENAME}}|1=$1 result}}',
			// Assume the grammar form grammar_case_foo is not valid in any language
			'grammar-msg': 'Przeszukaj {{GRAMMAR:grammar_case_foo|{{SITENAME}}}}',

			'formatnum-msg': '{{formatnum:$1}}',

			'bidi-msg': 'Welcome {{BIDI:$1}}!',

			'portal-url': 'Project:Community portal',
			'see-portal-url': '{{Int:portal-url}} is an important community page.',

			'jquerymsg-test-statistics-users': '注册[[Special:ListUsers|用户]]',
			'jquerymsg-test-statistics-users-sitename': '注册[[Special:ListUsers|用户{{SITENAME}}]]',
			'jquerymsg-test-link-pagenamee': '[https://example.org/wiki/Foo?bar=baz#val/{{PAGENAMEE}} Test]',

			'jquerymsg-test-version-entrypoints-index-php': '[https://www.mediawiki.org/wiki/Manual:index.php index.php]',

			'external-link-replace': 'Foo [$1 bar]',
			'external-link-plural': 'Foo {{PLURAL:$1|is [$2 one]|are [$2 some]|2=[$2 two]|3=three|4=a=b}} things.',
			'plural-only-explicit-forms': 'It is a {{PLURAL:$1|1=single|2=double}} room.',
			'plural-empty-explicit-form': 'There is me{{PLURAL:$1|0=| and other people}}.'
		}
	} ) );

	QUnit.test( 'Replace', ( assert ) => {
		mw.messages.set( 'simple', 'Foo $1 baz $2' );

		assert.strictEqual( formatParse( 'simple' ), 'Foo $1 baz $2', 'Replacements with no substitutes' );
		assert.strictEqual( formatParse( 'simple', 'bar' ), 'Foo bar baz $2', 'Replacements with less substitutes' );
		assert.strictEqual( formatParse( 'simple', 'bar', 'quux' ), 'Foo bar baz quux', 'Replacements with all substitutes' );

		mw.messages.set( 'plain-input', '<foo foo="foo">x$1y&lt;</foo>z' );

		assert.strictEqual(
			formatParse( 'plain-input', 'bar' ),
			'&lt;foo foo="foo"&gt;xbary&amp;lt;&lt;/foo&gt;z',
			'Input is not considered html'
		);

		mw.messages.set( 'plain-replace', 'Foo $1' );

		assert.strictEqual(
			formatParse( 'plain-replace', '<bar bar="bar">&gt;</bar>' ),
			'Foo &lt;bar bar="bar"&gt;&amp;gt;&lt;/bar&gt;',
			'Replacement is not considered html'
		);

		mw.messages.set( 'object-replace', 'Foo $1' );

		assert.strictEqual(
			formatParse( 'object-replace', $( '<div class="bar">&gt;</div>' ) ),
			'Foo <div class="bar">&gt;</div>',
			'jQuery objects are preserved as raw html'
		);

		assert.strictEqual(
			formatParse( 'object-replace', $( '<div class="bar">&gt;</div>' ).get( 0 ) ),
			'Foo <div class="bar">&gt;</div>',
			'HTMLElement objects are preserved as raw html'
		);

		assert.strictEqual(
			formatParse( 'object-replace', $( '<div class="bar">&gt;</div>' ).toArray() ),
			'Foo <div class="bar">&gt;</div>',
			'HTMLElement[] arrays are preserved as raw html'
		);

		mw.messages.set( 'simple-double-replace', 'Foo 1: $1 2: $1' );
		assert.strictEqual(
			formatParse( 'simple-double-replace', 'bar' ),
			'Foo 1: bar 2: bar',
			'string params can be used multiple times'
		);

		mw.messages.set( 'object-double-replace', 'Foo 1: $1 2: $1' );
		assert.strictEqual(
			formatParse( 'object-double-replace', $( '<div class="bar">&gt;</div>' ) ),
			'Foo 1: <div class="bar">&gt;</div> 2: <div class="bar">&gt;</div>',
			'jQuery objects can be used multiple times'
		);

		assert.strictEqual(
			formatParse( 'object-double-replace', $( '<div class="bar">&gt;</div>' ).get( 0 ) ),
			'Foo 1: <div class="bar">&gt;</div> 2: <div class="bar">&gt;</div>',
			'HTMLElement can be used multiple times'
		);

		assert.strictEqual(
			formatParse( 'external-link-replace', 'http://example.org/?x=y&z' ),
			'Foo <a href="http://example.org/?x=y&amp;z" class="external">bar</a>',
			'Href is not double-escaped in wikilink function'
		);
		assert.strictEqual(
			formatParse( 'external-link-plural', 1, 'http://example.org' ),
			'Foo is <a href="http://example.org" class="external">one</a> things.',
			'Link is expanded inside plural and is not escaped html'
		);
		assert.strictEqual(
			formatParse( 'external-link-plural', 2, 'http://example.org' ),
			'Foo <a href="http://example.org" class="external">two</a> things.',
			'Link is expanded inside an explicit plural form and is not escaped html'
		);
		assert.strictEqual(
			formatParse( 'external-link-plural', 3 ),
			'Foo three things.',
			'A simple explicit plural form co-existing with complex explicit plural forms'
		);
		assert.strictEqual(
			formatParse( 'external-link-plural', 4, 'http://example.org' ),
			'Foo a=b things.',
			'Only first equal sign is used as delimiter for explicit plural form. Repeated equal signs does not create issue'
		);
		assert.strictEqual(
			formatParse( 'external-link-plural', 6, 'http://example.org' ),
			'Foo are <a href="http://example.org" class="external">some</a> things.',
			'Plural fallback to the "other" plural form'
		);
		assert.strictEqual(
			formatParse( 'plural-only-explicit-forms', 2 ),
			'It is a double room.',
			'Plural with explicit forms alone.'
		);
	} );

	QUnit.test( 'Plural', ( assert ) => {
		assert.strictEqual( formatParse( 'plural-msg', 0 ), 'Found 0 items', 'Plural test for english with zero as count' );
		assert.strictEqual( formatParse( 'plural-msg', 1 ), 'Found 1 item', 'Singular test for english' );
		assert.strictEqual( formatParse( 'plural-msg', 2 ), 'Found 2 items', 'Plural test for english' );
		assert.strictEqual( formatParse( 'plural-msg-explicit-forms-nested', 6 ), 'Found 6 results', 'Plural message with explicit plural forms' );
		assert.strictEqual( formatParse( 'plural-msg-explicit-forms-nested', 0 ), 'Found no results in Wiki', 'Plural message with explicit plural forms, with nested {{SITENAME}}' );
		assert.strictEqual( formatParse( 'plural-msg-explicit-forms-nested', 1 ), 'Found 1 result', 'Plural message with explicit plural forms with placeholder nested' );
		assert.strictEqual( formatParse( 'plural-empty-explicit-form', 0 ), 'There is me.' );
		assert.strictEqual( formatParse( 'plural-empty-explicit-form', 1 ), 'There is me and other people.' );
		assert.strictEqual( formatParse( 'plural-empty-explicit-form', 2 ), 'There is me and other people.' );
	} );

	QUnit.test( 'Gender', ( assert ) => {
		const originalGender = mw.user.options.get( 'gender' );

		// TODO: These tests should be for mw.msg once mw.msg integrated with jqueryMsg
		// TODO: English may not be the best language for these tests. Use a language like Arabic or Russian
		mw.user.options.set( 'gender', 'male' );
		assert.strictEqual(
			formatParse( 'gender-msg', 'Bob', 'male' ),
			'Bob: blue',
			'Masculine from string "male"'
		);
		assert.strictEqual(
			formatParse( 'gender-msg', 'Bob', mw.user ),
			'Bob: blue',
			'Masculine from mw.user object'
		);
		assert.strictEqual(
			formatParse( 'gender-msg-currentuser' ),
			'blue',
			'Masculine for current user'
		);

		mw.user.options.set( 'gender', 'female' );
		assert.strictEqual(
			formatParse( 'gender-msg', 'Alice', 'female' ),
			'Alice: pink',
			'Feminine from string "female"' );
		assert.strictEqual(
			formatParse( 'gender-msg', 'Alice', mw.user ),
			'Alice: pink',
			'Feminine from mw.user object'
		);
		assert.strictEqual(
			formatParse( 'gender-msg-currentuser' ),
			'pink',
			'Feminine for current user'
		);

		mw.user.options.set( 'gender', 'unknown' );
		assert.strictEqual(
			formatParse( 'gender-msg', 'Foo', mw.user ),
			'Foo: green',
			'Neutral from mw.user object' );
		assert.strictEqual(
			formatParse( 'gender-msg', 'User' ),
			'User: green',
			'Neutral when no parameter given' );
		assert.strictEqual(
			formatParse( 'gender-msg', 'User', 'unknown' ),
			'User: green',
			'Neutral from string "unknown"'
		);
		assert.strictEqual(
			formatParse( 'gender-msg-currentuser' ),
			'green',
			'Neutral for current user'
		);

		mw.messages.set( 'gender-msg-one-form', '{{GENDER:$1|User}}: $2 {{PLURAL:$2|edit|edits}}' );

		assert.strictEqual(
			formatParse( 'gender-msg-one-form', 'male', 10 ),
			'User: 10 edits',
			'Gender neutral and plural form'
		);
		assert.strictEqual(
			formatParse( 'gender-msg-one-form', 'female', 1 ),
			'User: 1 edit',
			'Gender neutral and singular form'
		);

		mw.messages.set( 'gender-msg-lowercase', '{{gender:$1|he|she}} is awesome' );
		assert.strictEqual(
			formatParse( 'gender-msg-lowercase', 'male' ),
			'he is awesome',
			'Gender masculine'
		);
		assert.strictEqual(
			formatParse( 'gender-msg-lowercase', 'female' ),
			'she is awesome',
			'Gender feminine'
		);

		mw.messages.set( 'gender-msg-wrong', '{{gender}} test' );
		assert.strictEqual(
			formatParse( 'gender-msg-wrong', 'female' ),
			' test',
			'Invalid syntax should result in {{gender}} simply being stripped away'
		);

		mw.user.options.set( 'gender', originalGender );
	} );

	QUnit.test( 'Case changing', ( assert ) => {
		mw.messages.set( 'to-lowercase', '{{lc:thIS hAS MEsSed uP CapItaliZatiON}}' );
		assert.strictEqual( formatParse( 'to-lowercase' ), 'this has messed up capitalization', 'To lowercase' );

		mw.messages.set( 'to-caps', '{{uc:thIS hAS MEsSed uP CapItaliZatiON}}' );
		assert.strictEqual( formatParse( 'to-caps' ), 'THIS HAS MESSED UP CAPITALIZATION', 'To caps' );

		mw.messages.set( 'uc-to-lcfirst', '{{lcfirst:THis hAS MEsSed uP CapItaliZatiON}}' );
		mw.messages.set( 'lc-to-lcfirst', '{{lcfirst:thIS hAS MEsSed uP CapItaliZatiON}}' );
		assert.strictEqual( formatParse( 'uc-to-lcfirst' ), 'tHis hAS MEsSed uP CapItaliZatiON', 'Lcfirst caps' );
		assert.strictEqual( formatParse( 'lc-to-lcfirst' ), 'thIS hAS MEsSed uP CapItaliZatiON', 'Lcfirst lowercase' );

		mw.messages.set( 'uc-to-ucfirst', '{{ucfirst:THis hAS MEsSed uP CapItaliZatiON}}' );
		mw.messages.set( 'lc-to-ucfirst', '{{ucfirst:thIS hAS MEsSed uP CapItaliZatiON}}' );
		assert.strictEqual( formatParse( 'uc-to-ucfirst' ), 'THis hAS MEsSed uP CapItaliZatiON', 'Ucfirst caps' );
		assert.strictEqual( formatParse( 'lc-to-ucfirst' ), 'ThIS hAS MEsSed uP CapItaliZatiON', 'Ucfirst lowercase' );

		mw.messages.set( 'mixed-to-sentence', '{{ucfirst:{{lc:thIS hAS MEsSed uP CapItaliZatiON}}}}' );
		assert.strictEqual( formatParse( 'mixed-to-sentence' ), 'This has messed up capitalization', 'To sentence case' );
		mw.messages.set( 'all-caps-except-first', '{{lcfirst:{{uc:thIS hAS MEsSed uP CapItaliZatiON}}}}' );
		assert.strictEqual( formatParse( 'all-caps-except-first' ), 'tHIS HAS MESSED UP CAPITALIZATION', 'To opposite sentence case' );

		mw.messages.set( 'ucfirst-outside-BMP', '{{ucfirst:\uD803\uDCC0 is U+10CC0 (OLD HUNGARIAN SMALL LETTER A)}}' );
		assert.strictEqual( formatParse( 'ucfirst-outside-BMP' ), '\uD803\uDC80 is U+10CC0 (OLD HUNGARIAN SMALL LETTER A)', 'Ucfirst outside BMP' );

		mw.messages.set( 'lcfirst-outside-BMP', '{{lcfirst:\uD803\uDC80 is U+10C80 (OLD HUNGARIAN CAPITAL LETTER A)}}' );
		assert.strictEqual( formatParse( 'lcfirst-outside-BMP' ), '\uD803\uDCC0 is U+10C80 (OLD HUNGARIAN CAPITAL LETTER A)', 'Lcfirst outside BMP' );

	} );

	QUnit.test( 'Grammar', ( assert ) => {
		assert.strictEqual( formatParse( 'grammar-msg' ), 'Przeszukaj Wiki', 'Grammar Test with sitename' );

		mw.messages.set( 'grammar-msg-wrong-syntax', 'Przeszukaj {{GRAMMAR:grammar_case_xyz}}' );
		assert.strictEqual( formatParse( 'grammar-msg-wrong-syntax' ), 'Przeszukaj ', 'Grammar Test with wrong grammar template syntax' );
	} );

	QUnit.test( 'Formal', ( assert ) => {
		mw.language.setData( mw.config.get( 'wgUserLanguage' ), 'formalityIndex', 0 );
		mw.messages.set( 'formal-msg', '{{#FORMAL:Du hast|Sie haben}}' );
		assert.strictEqual( formatParse( 'formal-msg' ), 'Du hast', 'Formal Test ' );

		mw.messages.set( 'formal-msg-no-arguments', 'a{{#FORMAL:}}b' );
		assert.strictEqual( formatParse( 'formal-msg-no-arguments' ), 'ab', 'Formal Test with wrong formal template syntax' );

		mw.messages.set( 'formal-msg-one-argument', '{{#FORMAL:Single form}}' );
		assert.strictEqual( formatParse( 'formal-msg-one-argument' ), 'Single form', 'Formal Test with a single argument' );

		mw.messages.set( 'formal-msg-complex1', '{{#FORMAL:Informal hello|{{GENDER:|Formal}} hello}}' );
		assert.strictEqual( formatParse( 'formal-msg-complex1' ), 'Informal hello', 'Complex first parameter' );
		mw.messages.set( 'formal-msg-complex2', '{{#FORMAL:{{GENDER:|Informal}} hello|Formal hello}}' );
		assert.strictEqual( formatParse( 'formal-msg-complex2' ), 'Informal hello', 'Complex second parameter' );
		mw.messages.set( 'formal-msg-complex3', '{{#FORMAL:{{GENDER:|Informal}} hello|{{GENDER:|Formal}} hello}}' );
		assert.strictEqual( formatParse( 'formal-msg-complex3' ), 'Informal hello', 'Complex both parameters' );

		mw.language.setData( mw.config.get( 'wgUserLanguage' ), 'formalityIndex', 1 );
		assert.strictEqual( formatParse( 'formal-msg' ), 'Sie haben', 'Formal Test' );
		assert.strictEqual( formatParse( 'formal-msg-one-argument' ), 'Single form', 'Formal Test with a single argument when the second argument should be chosen' );

		mw.messages.set( 'formal-msg-complex1', '{{#FORMAL:Informal hello|{{GENDER:|Formal}} hello}}' );
		assert.strictEqual( formatParse( 'formal-msg-complex1' ), 'Formal hello', 'Complex first parameter' );
		mw.messages.set( 'formal-msg-complex2', '{{#FORMAL:{{GENDER:|Informal}} hello|Formal hello}}' );
		assert.strictEqual( formatParse( 'formal-msg-complex2' ), 'Formal hello', 'Complex second parameter' );
		mw.messages.set( 'formal-msg-complex3', '{{#FORMAL:{{GENDER:|Informal}} hello|{{GENDER:|Formal}} hello}}' );
		assert.strictEqual( formatParse( 'formal-msg-complex3' ), 'Formal hello', 'Complex both parameters' );

	} );

	QUnit.test( 'Variables', ( assert ) => {
		mw.messages.set( 'variables-pagename', '{{PAGENAME}}' );
		assert.strictEqual( formatParse( 'variables-pagename' ), '2 + 2', 'PAGENAME' );
		mw.messages.set( 'variables-pagenamee', '{{PAGENAMEE}}' );
		assert.strictEqual( formatParse( 'variables-pagenamee' ), mw.util.wikiUrlencode( '2 + 2' ), 'PAGENAMEE' );
		mw.messages.set( 'variables-sitename', '{{SITENAME}}' );
		assert.strictEqual( formatParse( 'variables-sitename' ), 'Wiki', 'SITENAME' );
		mw.messages.set( 'variables-servername', '{{SERVERNAME}}' );
		assert.strictEqual( formatParse( 'variables-servername' ), 'wiki.xyz', 'SERVERNAME' );
		mw.messages.set( 'variables-contentlanguage', '{{CONTENTLANGUAGE}}' );
		assert.strictEqual( formatParse( 'variables-contentlanguage' ), 'sjn', 'CONTENTLANGUAGE' );
	} );

	QUnit.test( 'Bi-di', ( assert ) => {
		assert.strictEqual( formatParse( 'bidi-msg', 'Bob' ), 'Welcome \u202ABob\u202C!', 'Bidi test (LTR)' );
		assert.strictEqual( formatParse( 'bidi-msg', 'בוב' ), 'Welcome \u202Bבוב\u202C!', 'Bidi test (RTL)' );
	} );

	QUnit.test( 'Match PHP parser', function ( assert ) {
		const self = this;
		mw.messages.set( phpParserData.messages );
		phpParserData.tests.forEach( ( test ) => {
			const langClass = self.getMwLanguage( test.lang );
			mw.config.set( 'wgUserLanguage', test.lang );
			const parser = new jqueryMsg.Parser( { language: langClass } );
			assert.strictEqual(
				parser.parse( test.key, test.args ).html(),
				test.result,
				test.name
			);
		} );
	} );

	QUnit.test( 'Links', function ( assert ) {
		// The below three are all identical to or based on real messages.  For disambiguations-text,
		// the bold was removed because it is not yet implemented.

		assert.htmlEqual(
			formatParse( 'jquerymsg-test-statistics-users' ),
			expectedListUsers,
			'Piped wikilink'
		);

		const expectedDisambiguationsText = 'The following pages contain at least one link to a disambiguation page.\nThey may have to link to a more appropriate page instead.\nA page is treated as a disambiguation page if it uses a template that is linked from ' +
			'<a title="MediaWiki:Disambiguationspage" href="/wiki/MediaWiki:Disambiguationspage">MediaWiki:Disambiguationspage</a>.';

		mw.messages.set( 'disambiguations-text', 'The following pages contain at least one link to a disambiguation page.\nThey may have to link to a more appropriate page instead.\nA page is treated as a disambiguation page if it uses a template that is linked from [[MediaWiki:Disambiguationspage]].' );
		assert.htmlEqual(
			formatParse( 'disambiguations-text' ),
			expectedDisambiguationsText,
			'Wikilink without pipe'
		);

		assert.htmlEqual(
			formatParse( 'jquerymsg-test-version-entrypoints-index-php' ),
			expectedEntrypoints,
			'External link'
		);

		// Pipe trick is not supported currently, but should not parse as text either.
		mw.messages.set( 'pipe-trick', '[[Tampa, Florida|]]' );
		mw.messages.set( 'reverse-pipe-trick', '[[|Tampa, Florida]]' );
		mw.messages.set( 'empty-link', '[[]]' );
		this.suppressWarnings();
		assert.strictEqual(
			formatParse( 'pipe-trick' ),
			'[[Tampa, Florida|]]',
			'Pipe trick should not be parsed.'
		);
		assert.strictEqual(
			formatParse( 'reverse-pipe-trick' ),
			'[[|Tampa, Florida]]',
			'Reverse pipe trick should not be parsed.'
		);
		assert.strictEqual(
			formatParse( 'empty-link' ),
			'[[]]',
			'Empty link should not be parsed.'
		);
		this.restoreWarnings();

		const expectedMultipleBars = '<a title="Main Page" href="/wiki/Main_Page">Main|Page</a>';
		mw.messages.set( 'multiple-bars', '[[Main Page|Main|Page]]' );
		assert.htmlEqual(
			formatParse( 'multiple-bars' ),
			expectedMultipleBars,
			'Bar in anchor'
		);

		const expectedSpecialCharacters = '<a title="&quot;Who&quot; wants to be a millionaire &amp; live on &#039;Exotic Island&#039;?" href="/wiki/%22Who%22_wants_to_be_a_millionaire_%26_live_on_%27Exotic_Island%27%3F">&quot;Who&quot; wants to be a millionaire &amp; live on &#039;Exotic Island&#039;?</a>';

		mw.messages.set( 'special-characters', '[[' + specialCharactersPageName + ']]' );
		assert.htmlEqual(
			formatParse( 'special-characters' ),
			expectedSpecialCharacters,
			'Special characters'
		);

		mw.messages.set( 'leading-colon', '[[:File:Foo.jpg]]' );
		assert.htmlEqual(
			formatParse( 'leading-colon' ),
			'<a title="File:Foo.jpg" href="/wiki/File:Foo.jpg">File:Foo.jpg</a>',
			'Leading colon in links is stripped'
		);

		assert.htmlEqual(
			formatParse( 'jquerymsg-test-statistics-users-sitename' ),
			expectedListUsersSitename,
			'Piped wikilink with parser function in the text'
		);

		assert.htmlEqual(
			formatParse( 'jquerymsg-test-link-pagenamee' ),
			expectedLinkPagenamee,
			'External link with parser function in the URL'
		);

		const testCases = [
			[
				'extlink-html-full',
				'asd [http://example.org <strong>Example</strong>] asd',
				'asd <a href="http://example.org" class="external"><strong>Example</strong></a> asd'
			],
			[
				'extlink-html-partial',
				'asd [http://example.org foo <strong>Example</strong> bar] asd',
				'asd <a href="http://example.org" class="external">foo <strong>Example</strong> bar</a> asd'
			],
			[
				'wikilink-html-full',
				'asd [[Example|<strong>Example</strong>]] asd',
				'asd <a title="Example" href="/wiki/Example"><strong>Example</strong></a> asd'
			],
			[
				'wikilink-html-partial',
				'asd [[Example|foo <strong>Example</strong> bar]] asd',
				'asd <a title="Example" href="/wiki/Example">foo <strong>Example</strong> bar</a> asd'
			]
		];

		testCases.forEach( ( testCase ) => {
			const
				key = testCase[ 0 ],
				input = testCase[ 1 ],
				output = testCase[ 2 ];
			mw.messages.set( key, input );
			assert.htmlEqual(
				formatParse( key ),
				output,
				'HTML in links: ' + key
			);
		} );

		mw.messages.set( 'jquerymsg-title-fragment', 'Link to [[Title#Fragment]]' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-title-fragment' ),
			'Link to <a title="Title" href="/wiki/Title#Fragment">Title#Fragment</a>',
			'Link with title and fragment'
		);

		mw.messages.set( 'jquerymsg-fragment', 'Link to [[#Fragment]]' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-fragment' ),
			'Link to <a href="#Fragment">#Fragment</a>',
			'Link with fragment only'
		);
	} );

	// Test case sensitive namespaces
	QUnit.test( 'CaseSensitiveNamespaces', ( assert ) => {
		const oldCaseSensitiveNamespaces = mw.config.get( 'wgCaseSensitiveNamespaces' );
		mw.config.set( 'wgCaseSensitiveNamespaces', [ 0 ] );

		mw.messages.set( 'jquerymsg-lowercase-link', 'A [[lowercase]] and [[Uppercase]] wiki-link' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-lowercase-link' ),
			'A <a title="lowercase" href="/wiki/lowercase">lowercase</a> and <a title="Uppercase" href="/wiki/Uppercase">Uppercase</a> wiki-link',
			'wgCaseSensitiveNamespaces allows lowercase namespaces.'
		);

		mw.config.set( 'wgCaseSensitiveNamespaces', oldCaseSensitiveNamespaces );
	} );

	// Test localized namespace names
	QUnit.test( 'LocalizedNamespaces', ( assert ) => {
		mw.messages.set( 'jquerymsg-localized-link', 'Link to [[template:foo|template foo]]' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-localized-link' ),
			'Link to <a title="Szablon:Foo" href="/wiki/Szablon:Foo">template foo</a>',
			'Links have localized namespace names.'
		);
	} );

	QUnit.test( 'Replacements in links', ( assert ) => {
		const testCases = [
			[
				'extlink-param-href-full',
				'asd [$1 Example] asd',
				'asd <a href="http://example.com" class="external">Example</a> asd'
			],
			[
				'extlink-param-href-partial',
				'asd [$1/example Example] asd',
				'asd <a href="http://example.com/example" class="external">Example</a> asd'
			],
			[
				'extlink-param-text-full',
				'asd [http://example.org $2] asd',
				'asd <a href="http://example.org" class="external">Text</a> asd'
			],
			[
				'extlink-param-text-partial',
				'asd [http://example.org Example $2] asd',
				'asd <a href="http://example.org" class="external">Example Text</a> asd'
			],
			[
				'extlink-param-both-full',
				'asd [$1 $2] asd',
				'asd <a href="http://example.com" class="external">Text</a> asd'
			],
			[
				'extlink-param-both-partial',
				'asd [$1/example Example $2] asd',
				'asd <a href="http://example.com/example" class="external">Example Text</a> asd'
			],
			[
				'wikilink-param-href-full',
				'asd [[$1|Example]] asd',
				'asd <a title="Example" href="/wiki/Example">Example</a> asd'
			],
			[
				'wikilink-param-href-partial',
				'asd [[$1/Test|Example]] asd',
				'asd <a title="Example/Test" href="/wiki/Example/Test">Example</a> asd'
			],
			[
				'wikilink-param-text-full',
				'asd [[Example|$2]] asd',
				'asd <a title="Example" href="/wiki/Example">Text</a> asd'
			],
			[
				'wikilink-param-text-partial',
				'asd [[Example|Example $2]] asd',
				'asd <a title="Example" href="/wiki/Example">Example Text</a> asd'
			],
			[
				'wikilink-param-both-full',
				'asd [[$1|$2]] asd',
				'asd <a title="Example" href="/wiki/Example">Text</a> asd'
			],
			[
				'wikilink-param-both-partial',
				'asd [[$1/Test|Example $2]] asd',
				'asd <a title="Example/Test" href="/wiki/Example/Test">Example Text</a> asd'
			],
			[
				'wikilink-param-unpiped-full',
				'asd [[$1]] asd',
				'asd <a title="Example" href="/wiki/Example">Example</a> asd'
			],
			[
				'wikilink-param-unpiped-partial',
				'asd [[$1/Test]] asd',
				'asd <a title="Example/Test" href="/wiki/Example/Test">Example/Test</a> asd'
			]
		];

		testCases.forEach( ( testCase ) => {
			const
				key = testCase[ 0 ],
				input = testCase[ 1 ],
				output = testCase[ 2 ],
				paramHref = key.startsWith( 'wikilink' ) ? 'Example' : 'http://example.com',
				paramText = 'Text';
			mw.messages.set( key, input );
			assert.htmlEqual(
				formatParse( key, paramHref, paramText ),
				output,
				'Replacements in links: ' + key
			);
		} );
	} );

	// Tests that {{-transformation vs. general parsing are done as requested
	QUnit.test( 'Curly brace transformation', ( assert ) => {
		const oldUserLang = mw.config.get( 'wgUserLanguage' );

		assertBothModes( assert, [ 'gender-msg', 'Bob', 'male' ], 'Bob: blue', 'gender is resolved' );

		assertBothModes( assert, [ 'plural-msg', 5 ], 'Found 5 items', 'plural is resolved' );

		assertBothModes( assert, [ 'grammar-msg' ], 'Przeszukaj Wiki', 'grammar is resolved' );

		mw.config.set( 'wgUserLanguage', 'en' );
		assertBothModes( assert, [ 'formatnum-msg', '987654321.654321' ], '987,654,321.654', 'formatnum is resolved' );

		// Test non-{{ wikitext, where behavior differs

		// Wikilink
		assert.strictEqual(
			formatText( 'jquerymsg-test-statistics-users' ),
			mw.messages.get( 'jquerymsg-test-statistics-users' ),
			'Internal link message unchanged when format is \'text\''
		);
		assert.htmlEqual(
			formatParse( 'jquerymsg-test-statistics-users' ),
			expectedListUsers,
			'Internal link message parsed when format is \'parse\''
		);

		// External link
		assert.strictEqual(
			formatText( 'jquerymsg-test-version-entrypoints-index-php' ),
			mw.messages.get( 'jquerymsg-test-version-entrypoints-index-php' ),
			'External link message unchanged when format is \'text\''
		);
		assert.htmlEqual(
			formatParse( 'jquerymsg-test-version-entrypoints-index-php' ),
			expectedEntrypoints,
			'External link message processed when format is \'parse\''
		);

		// External link with parameter
		assert.strictEqual(
			formatText( 'external-link-replace', 'http://example.com' ),
			'Foo [http://example.com bar]',
			'External link message only substitutes parameter when format is \'text\''
		);
		assert.htmlEqual(
			formatParse( 'external-link-replace', 'http://example.com' ),
			'Foo <a href="http://example.com" class="external">bar</a>',
			'External link message processed when format is \'parse\''
		);
		assert.htmlEqual(
			formatParse( 'external-link-replace', '/wiki/Special:Version' ),
			'Foo <a href="/wiki/Special:Version">bar</a>',
			'External link message allows relative URL when processed'
		);
		assert.htmlEqual(
			formatParse( 'external-link-replace', '//example.com' ),
			'Foo <a href="//example.com" class="external">bar</a>',
			'External link message allows protocol-relative URL when processed'
		);
		assert.htmlEqual(
			formatParse( 'external-link-replace', $( '<i>' ) ),
			'Foo <i>bar</i>',
			'External link message processed as jQuery object when format is \'parse\''
		);
		assert.htmlEqual(
			formatParse( 'external-link-replace', () => {} ),
			'Foo <a role="button" tabindex="0">bar</a>',
			'External link message processed as function when format is \'parse\''
		);

		mw.config.set( 'wgUserLanguage', oldUserLang );
	} );

	QUnit.test( 'Int', ( assert ) => {
		const newarticletextSource = 'You have followed a link to a page that does not exist yet. To create the page, start typing in the box below (see the [[{{Int:Foobar}}|foobar]] for more info). If you are here by mistake, click your browser\'s back button.',
			helpPageTitle = 'Help:Foobar';

		const expectedNewarticletext = 'You have followed a link to a page that does not exist yet. To create the page, start typing in the box below (see the ' +
			'<a title="Help:Foobar" href="/wiki/Help:Foobar">foobar</a> for more info). If you are here by mistake, click your browser\'s back button.';

		mw.config.set( 'wgUserLanguage', 'en' );
		mw.messages.set( {
			foobar: helpPageTitle,
			newarticletext: newarticletextSource
		} );

		assert.htmlEqual(
			formatParse( 'newarticletext' ),
			expectedNewarticletext,
			'Link with nested message'
		);

		assert.strictEqual(
			formatParse( 'see-portal-url' ),
			'Project:Community portal is an important community page.',
			'Nested message'
		);

		mw.messages.set( 'newarticletext-lowercase',
			newarticletextSource.replace( 'Int:Helppage', 'int:helppage' ) );

		assert.htmlEqual(
			formatParse( 'newarticletext-lowercase' ),
			expectedNewarticletext,
			'Link with nested message, lowercase include'
		);

		mw.messages.set( 'uses-missing-int', '{{int:doesnt-exist}}' );

		assert.strictEqual(
			formatParse( 'uses-missing-int' ),
			'⧼doesnt-exist⧽',
			'int: where nested message does not exist'
		);
	} );

	QUnit.test( 'Ns', ( assert ) => {
		mw.messages.set( 'ns-template-talk', '{{ns:Template talk}}' );
		assert.strictEqual(
			formatParse( 'ns-template-talk' ),
			'Dyskusja szablonu',
			'ns: returns localised namespace when used with a canonical namespace name'
		);

		mw.messages.set( 'ns-10', '{{ns:10}}' );
		assert.strictEqual(
			formatParse( 'ns-10' ),
			'Szablon',
			'ns: returns localised namespace when used with a namespace number'
		);

		mw.messages.set( 'ns-unknown', '{{ns:doesnt-exist}}' );
		assert.strictEqual(
			formatParse( 'ns-unknown' ),
			'',
			'ns: returns empty string for unknown namespace name'
		);

		mw.messages.set( 'ns-in-a-link', '[[{{ns:template}}:Foo]]' );
		assert.strictEqual(
			formatParse( 'ns-in-a-link' ),
			'<a title="Szablon:Foo" href="/wiki/Szablon:Foo">Szablon:Foo</a>',
			'ns: works when used inside a wikilink'
		);
	} );

	// Tests that getMessageFunction is used for non-plain messages with curly braces or
	// square brackets, but not otherwise.
	QUnit.test( 'mw.Message.prototype.parser monkey-patch', ( assert ) => {
		let outerCalled, innerCalled;

		mw.messages.set( {
			'curly-brace': '{{int:message}}',
			'single-square-bracket': '[https://www.mediawiki.org/ MediaWiki]',
			'double-square-bracket': '[[Some page]]',
			regular: 'Other message'
		} );

		const restore = jqueryMsg.setMessageFunction( () => {
			outerCalled = true;
			return function () {
				innerCalled = true;
			};
		} );

		function verifyGetMessageFunction( key, format, shouldCall ) {
			outerCalled = false;
			innerCalled = false;
			// eslint-disable-next-line mediawiki/msg-doc
			const message = mw.message( key );
			message[ format ]();
			assert.strictEqual( outerCalled, shouldCall, 'Outer function called for ' + key );
			assert.strictEqual( innerCalled, shouldCall, 'Inner function called for ' + key );
			delete mw.messages[ format ];
		}

		verifyGetMessageFunction( 'curly-brace', 'parse', true );
		verifyGetMessageFunction( 'curly-brace', 'plain', false );

		verifyGetMessageFunction( 'single-square-bracket', 'parse', true );
		verifyGetMessageFunction( 'single-square-bracket', 'plain', false );

		verifyGetMessageFunction( 'double-square-bracket', 'parse', true );
		verifyGetMessageFunction( 'double-square-bracket', 'plain', false );

		verifyGetMessageFunction( 'regular', 'parse', false );
		verifyGetMessageFunction( 'regular', 'plain', false );

		verifyGetMessageFunction( 'jquerymsg-test-pagetriage-del-talk-page-notify-summary', 'plain', false );
		verifyGetMessageFunction( 'jquerymsg-test-categorytree-collapse-bullet', 'plain', false );
		verifyGetMessageFunction( 'jquerymsg-test-wikieditor-toolbar-help-content-signature-result', 'plain', false );

		restore();
	} );

	// Tests that HTML in message parameters is escaped,
	// whether the message looks like wikitext or not.
	QUnit.test( 'mw.Message.prototype.parser monkey-patch HTML-escape', ( assert ) => {
		mw.messages.set( '1x-wikitext', '<span>$1</span>' );
		assert.htmlEqual(
			mw.message( '1x-wikitext', '<script>alert( "1x-wikitext test" )</script>' ).parse(),
			'<span>&lt;script&gt;alert( &quot;1x-wikitext test&quot; )&lt;/script&gt;</span>',
			'Message parameters are escaped if message contains wikitext'
		);

		mw.messages.set( '1x-plain', '$1' );
		assert.htmlEqual(
			mw.message( '1x-plain', '<script>alert( "1x-plain test" )</script>' ).parse(),
			'&lt;script&gt;alert( &quot;1x-plain test&quot; )&lt;/script&gt;',
			'Message parameters are still escaped if message contains no wikitext'
		);
	} );

	const formatnumTests = [
		{
			lang: 'en',
			number: 987654321.654321,
			result: '987,654,321.654',
			description: 'formatnum test for English, decimal separator'
		},
		{
			lang: 'ar',
			number: 987654321.654321,
			result: '٩٨٧٬٦٥٤٬٣٢١٫٦٥٤',
			description: 'formatnum test for Arabic, with decimal separator'
		},
		{
			lang: 'ar',
			number: '٩٨٧٦٥٤٣٢١٫٦٥٤٣٢١',
			result: '987654321',
			integer: true,
			description: 'formatnum test for Arabic, with decimal separator, reverse'
		},
		{
			lang: 'ar',
			number: -12.89,
			result: '-١٢٫٨٩',
			description: 'formatnum test for Arabic, negative number'
		},
		{
			lang: 'ar',
			number: '-١٢٫٨٩',
			result: '-12',
			integer: true,
			description: 'formatnum test for Arabic, negative number, reverse'
		},
		{
			lang: 'nl',
			number: 987654321.654321,
			result: '987.654.321,654',
			description: 'formatnum test for Nederlands, decimal separator'
		},
		{
			lang: 'nl',
			number: -12.89,
			result: '-12,89',
			description: 'formatnum test for Nederlands, negative number'
		},
		{
			lang: 'nl',
			number: '.89',
			result: '0,89',
			description: 'formatnum test for Nederlands'
		},
		{
			lang: 'nl',
			number: 'invalidnumber',
			result: 'invalidnumber',
			description: 'formatnum test for Nederlands, invalid number'
		},
		{
			lang: 'ml',
			number: '1000000000',
			result: '1,00,00,00,000',
			description: 'formatnum test for Malayalam'
		},
		{
			lang: 'ml',
			number: '-1000000000',
			result: '-1,00,00,00,000',
			description: 'formatnum test for Malayalam, negative number'
		},
		/*
		 * This will fail because of wrong pattern for ml in MW(different from CLDR)
		{
			lang: 'ml',
			number: '1000000000.000',
			result: '1,00,00,00,000.000',
			description: 'formatnum test for Malayalam with decimal place'
		},
		*/
		{
			lang: 'hi',
			number: '123456789.123456789',
			result: '१२,३४,५६,७८९.१२३',
			description: 'formatnum test for Hindi'
		},
		{
			lang: 'hi',
			number: '१२,३४,५६,७८९',
			result: '१२,३४,५६,७८९',
			description: 'formatnum test for Hindi, Devanagari digits passed'
		},
		{
			lang: 'hi',
			number: '१,२३,४५६',
			result: '123456',
			integer: true,
			description: 'formatnum test for Hindi, Devanagari digits passed to get integer value'
		}
	];

	QUnit.test( 'formatnum', function ( assert ) {
		const self = this;
		mw.messages.set( 'formatnum-msg', '{{formatnum:$1}}' );
		mw.messages.set( 'formatnum-msg-int', '{{formatnum:$1|R}}' );
		formatnumTests.forEach( ( test ) => {
			const langClass = self.getMwLanguage( test.lang );
			mw.config.set( 'wgUserLanguage', test.lang );
			const parser = new jqueryMsg.Parser( { language: langClass } );
			assert.strictEqual(
				parser.parse( test.integer ? 'formatnum-msg-int' : 'formatnum-msg',
					[ test.number ] ).html(),
				test.result,
				test.description
			);
		} );
	} );

	QUnit.test( 'fullurl', ( assert ) => {
		mw.messages.set( {
			'fullurl-plain-msg': '{{fullurl:Main Page}}',
			'fullurl-with-params-msg': '{{fullurl:Main Page|action=history&safemode=1}}',
			'fullurl-link-msg': 'Link to [{{fullurl:$1}} main page]',
			'fullurl-nested-params-msg': '{{fullurl:Special:MyLanguage/$1|action={{lc:$2}}}}'
		} );

		assert.strictEqual(
			formatText( 'fullurl-plain-msg' ),
			mw.config.get( 'wgServer' ) + mw.config.get( 'wgArticlePath' ).replace( '$1', 'Main_Page' ),
			'Fullurl with no parameters'
		);
		assert.strictEqual(
			formatText( 'fullurl-with-params-msg' ),
			mw.config.get( 'wgServer' ) + mw.config.get( 'wgScriptPath' ) + '/index.php?title=Main_Page&action=history&safemode=1',
			'Fullurl with URL parameters'
		);
		assert.strictEqual(
			formatText( 'fullurl-link-msg', 'Main Page' ),
			'Link to [' + mw.config.get( 'wgServer' ) + mw.config.get( 'wgArticlePath' ).replace( '$1', 'Main_Page' ) + ' main page]',
			'Fullurl with link syntax'
		);
		assert.strictEqual(
			formatText( 'fullurl-nested-params-msg', 'Hellø World?', 'DELETE' ),
			mw.config.get( 'wgServer' ) + mw.config.get( 'wgScriptPath' ) + '/index.php?title=Special:MyLanguage/Hell%C3%B8_World%3F&action=delete',
			'Fullurl with nested parameters'
		);
	} );

	// HTML in wikitext
	QUnit.test( 'HTML', ( assert ) => {
		mw.messages.set( 'jquerymsg-italics-msg', '<i>Very</i> important' );

		assertBothModes( assert, [ 'jquerymsg-italics-msg' ], mw.messages.get( 'jquerymsg-italics-msg' ), 'Simple italics unchanged' );

		mw.messages.set( 'jquerymsg-bold-msg', '<b>Strong</b> speaker' );
		assertBothModes( assert, [ 'jquerymsg-bold-msg' ], mw.messages.get( 'jquerymsg-bold-msg' ), 'Simple bold unchanged' );

		mw.messages.set( 'jquerymsg-bold-italics-msg', 'It is <b><i>key</i></b>' );
		assertBothModes( assert, [ 'jquerymsg-bold-italics-msg' ], mw.messages.get( 'jquerymsg-bold-italics-msg' ), 'Bold and italics nesting order preserved' );

		mw.messages.set( 'jquerymsg-italics-bold-msg', 'It is <i><b>vital</b></i>' );
		assertBothModes( assert, [ 'jquerymsg-italics-bold-msg' ], mw.messages.get( 'jquerymsg-italics-bold-msg' ), 'Italics and bold nesting order preserved' );

		mw.messages.set( 'jquerymsg-italics-with-link', 'An <i>italicized [[link|wiki-link]]</i>' );

		assert.htmlEqual(
			formatParse( 'jquerymsg-italics-with-link' ),
			'An <i>italicized <a title="Link" href="/wiki/Link">wiki-link</i>',
			'Italics with link inside in parse mode'
		);

		assert.strictEqual(
			formatText( 'jquerymsg-italics-with-link' ),
			mw.messages.get( 'jquerymsg-italics-with-link' ),
			'Italics with link unchanged in text mode'
		);

		mw.messages.set( 'jquerymsg-italics-id-class', '<i id="foo" class="bar">Foo</i>' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-italics-id-class' ),
			mw.messages.get( 'jquerymsg-italics-id-class' ),
			'ID and class are allowed'
		);

		mw.messages.set( 'jquerymsg-italics-onclick', '<i onclick="alert(\'foo\')">Foo</i>' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-italics-onclick' ),
			'&lt;i onclick=&quot;alert(\'foo\')&quot;&gt;Foo&lt;/i&gt;',
			'element with onclick is escaped because it is not allowed'
		);

		mw.messages.set( 'jquerymsg-script-msg', '<script  >alert( "Who put this tag here?" );</script>' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-script-msg' ),
			'&lt;script  &gt;alert( &quot;Who put this tag here?&quot; );&lt;/script&gt;',
			'Tag outside list of allowed ones is escaped in parse mode'
		);

		assert.strictEqual(
			formatText( 'jquerymsg-script-msg' ),
			mw.messages.get( 'jquerymsg-script-msg' ),
			'Tag outside list of allowed ones is unchanged in text mode'
		);

		mw.messages.set( 'jquerymsg-script-link-msg', '<script>[[Foo|bar]]</script>' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-script-link-msg' ),
			'&lt;script&gt;<a title="Foo" href="/wiki/Foo">bar</a>&lt;/script&gt;',
			'Script tag text is escaped because that element is not allowed, but link inside is still HTML'
		);

		mw.messages.set( 'jquerymsg-mismatched-html', '<i class="important">test</b>' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-mismatched-html' ),
			'&lt;i class=&quot;important&quot;&gt;test&lt;/b&gt;',
			'Mismatched HTML start and end tag treated as text'
		);

		mw.messages.set( 'jquerymsg-script-and-external-link', '<script>alert( "jquerymsg-script-and-external-link test" );</script> [http://example.com <i>Foo</i> bar]' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-script-and-external-link' ),
			'&lt;script&gt;alert( "jquerymsg-script-and-external-link test" );&lt;/script&gt; <a href="http://example.com" class="external"><i>Foo</i> bar</a>',
			'HTML tags in external links not interfering with escaping of other tags'
		);

		mw.messages.set( 'jquerymsg-link-script', '[http://example.com <script>alert( "jquerymsg-link-script test" );</script>]' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-link-script' ),
			'<a href="http://example.com" class="external">&lt;script&gt;alert( "jquerymsg-link-script test" );&lt;/script&gt;</a>',
			'HTML tag not is list of allowed ones in external link anchor is treated as text'
		);

		// Intentionally not using htmlEqual for the quote tests
		mw.messages.set( 'jquerymsg-double-quotes-preserved', '<i id="double">Double</i>' );
		assert.strictEqual(
			formatParse( 'jquerymsg-double-quotes-preserved' ),
			mw.messages.get( 'jquerymsg-double-quotes-preserved' ),
			'Attributes with double quotes are preserved as such'
		);

		mw.messages.set( 'jquerymsg-single-quotes-normalized-to-double', '<i id=\'single\'>Single</i>' );
		assert.strictEqual(
			formatParse( 'jquerymsg-single-quotes-normalized-to-double' ),
			'<i id="single">Single</i>',
			'Attributes with single quotes are normalized to double'
		);

		mw.messages.set( 'jquerymsg-escaped-double-quotes-attribute', '<i style="font-family:&quot;Arial&quot;">Styled</i>' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-escaped-double-quotes-attribute' ),
			mw.messages.get( 'jquerymsg-escaped-double-quotes-attribute' ),
			'Escaped attributes are parsed correctly'
		);

		mw.messages.set( 'jquerymsg-escaped-single-quotes-attribute', '<i style=\'font-family:&#039;Arial&#039;\'>Styled</i>' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-escaped-single-quotes-attribute' ),
			mw.messages.get( 'jquerymsg-escaped-single-quotes-attribute' ),
			'Escaped attributes are parsed correctly'
		);

		mw.messages.set( 'jquerymsg-wikitext-contents-parsed', '<i>[http://example.com Example]</i>' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-wikitext-contents-parsed' ),
			'<i><a href="http://example.com" class="external">Example</a></i>',
			'Contents of valid tag are treated as wikitext, so external link is parsed'
		);

		mw.messages.set( 'jquerymsg-wikitext-contents-script', '<i><script>Script inside</script></i>' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-wikitext-contents-script' ),
			'<i>&lt;script&gt;Script inside&lt;/script&gt;</i>',
			'Contents of valid tag are treated as wikitext, so invalid HTML element is treated as text'
		);

		mw.messages.set( 'jquerymsg-unclosed-tag', 'Foo<tag>bar' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-unclosed-tag' ),
			'Foo&lt;tag&gt;bar',
			'Nonsupported unclosed tags are escaped'
		);

		mw.messages.set( 'jquerymsg-self-closing-tag', 'Foo<tag/>bar' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-self-closing-tag' ),
			'Foo&lt;tag/&gt;bar',
			'Self-closing tags don\'t cause a parse error'
		);

		mw.messages.set( 'jquerymsg-asciialphabetliteral-regression', '<b >>>="dir">asd</b>' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-asciialphabetliteral-regression' ),
			'<b>&gt;&gt;="dir"&gt;asd</b>',
			'Regression test for bad "asciiAlphabetLiteral" definition'
		);

		mw.messages.set( 'jquerymsg-entities1', 'A&B' );
		mw.messages.set( 'jquerymsg-entities2', 'A&gt;B' );
		mw.messages.set( 'jquerymsg-entities3', 'A&rarr;B' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-entities1' ),
			'A&amp;B',
			'Lone "&" is escaped in text'
		);
		assert.htmlEqual(
			formatParse( 'jquerymsg-entities2' ),
			'A&amp;gt;B',
			'"&gt;" entity is double-escaped in text' // (WHY?)
		);
		assert.htmlEqual(
			formatParse( 'jquerymsg-entities3' ),
			'A&amp;rarr;B',
			'"&rarr;" entity is double-escaped in text'
		);

		mw.messages.set( 'jquerymsg-entities-attr1', '<i title="A&B"></i>' );
		mw.messages.set( 'jquerymsg-entities-attr2', '<i title="A&gt;B"></i>' );
		mw.messages.set( 'jquerymsg-entities-attr3', '<i title="A&rarr;B"></i>' );
		assert.htmlEqual(
			formatParse( 'jquerymsg-entities-attr1' ),
			'<i title="A&amp;B"></i>',
			'Lone "&" is escaped in attribute'
		);
		assert.htmlEqual(
			formatParse( 'jquerymsg-entities-attr2' ),
			'<i title="A&gt;B"></i>',
			'"&gt;" entity is not double-escaped in attribute' // (WHY?)
		);
		assert.htmlEqual(
			formatParse( 'jquerymsg-entities-attr3' ),
			'<i title="A&amp;rarr;B"></i>',
			'"&rarr;" entity is double-escaped in attribute'
		);
	} );

	QUnit.test( 'Nowiki', ( assert ) => {
		mw.messages.set( 'jquerymsg-nowiki-link', 'Foo <nowiki>[[bar]]</nowiki> baz.' );
		assert.strictEqual(
			formatParse( 'jquerymsg-nowiki-link' ),
			'Foo [[bar]] baz.',
			'Link inside nowiki is not parsed'
		);

		mw.messages.set( 'jquerymsg-nowiki-htmltag', 'Foo <nowiki><b>bar</b></nowiki> baz.' );
		assert.strictEqual(
			formatParse( 'jquerymsg-nowiki-htmltag' ),
			'Foo &lt;b&gt;bar&lt;/b&gt; baz.',
			'HTML inside nowiki is not parsed and escaped'
		);

		mw.messages.set( 'jquerymsg-nowiki-template', 'Foo <nowiki>{{bar}}</nowiki> baz.' );
		assert.strictEqual(
			formatParse( 'jquerymsg-nowiki-template' ),
			'Foo {{bar}} baz.',
			'Template inside nowiki is not parsed and does not cause a parse error'
		);
	} );

	QUnit.test( 'Behavior in case of invalid wikitext', function ( assert ) {
		mw.messages.set( 'invalid-wikitext', '<b>{{FAIL}}</b>' );

		this.suppressWarnings();
		const logSpy = this.sandbox.spy( mw.log, 'warn' );

		assert.false(
			mw.message( 'invalid-wikitext' ).isParseable(),
			'Invalid wikitext: reported as not parseable'
		);

		assert.strictEqual(
			formatParse( 'invalid-wikitext' ),
			'&lt;b&gt;{{FAIL}}&lt;/b&gt;',
			'Invalid wikitext: \'parse\' format'
		);

		assert.strictEqual(
			formatText( 'invalid-wikitext' ),
			'<b>{{FAIL}}</b>',
			'Invalid wikitext: \'text\' format'
		);

		assert.strictEqual( logSpy.callCount, 2, 'mw.log.warn calls' );
	} );

	QUnit.test( 'Non-string parameters to various functions', ( assert ) => {
		// For jquery-param-int
		mw.messages.set( 'x', 'y' );
		// For jquery-param-grammar
		mw.language.setData( 'en', 'grammarTransformations', {
			test: [
				[ 'x', 'y' ]
			]
		} );

		const cases = [
			{
				key: 'jquery-param-wikilink',
				msg: '[[$1]] [[$1|a]]',
				expected: '<a title="X" href="/wiki/X">x</a> <a title="X" href="/wiki/X">a</a>'
			},
			{
				key: 'jquery-param-plural',
				msg: '{{PLURAL:$1|a|b}}',
				expected: 'b'
			},
			{
				key: 'jquery-param-gender',
				msg: '{{GENDER:$1|a|b}}',
				expected: 'a'
			},
			{
				key: 'jquery-param-grammar',
				msg: '{{GRAMMAR:test|$1}}',
				expected: '<b>x</b>'
			},
			{
				key: 'jquery-param-int',
				msg: '{{int:$1}}',
				expected: 'y'
			},
			{
				key: 'jquery-param-ns',
				msg: '{{ns:$1}}',
				expected: ''
			},
			{
				key: 'jquery-param-formatnum',
				msg: '{{formatnum:$1}}',
				expected: '<b>x</b>'
			},
			{
				key: 'jquery-param-case',
				msg: '{{lc:$1}} {{uc:$1}} {{lcfirst:$1}} {{ucfirst:$1}}',
				expected: 'x X x X'
			}
		];

		for ( let i = 0; i < cases.length; i++ ) {
			mw.messages.set( cases[ i ].key, cases[ i ].msg );
			assert.strictEqual(
				// eslint-disable-next-line mediawiki/msg-doc
				mw.message( cases[ i ].key, $( '<b>' ).text( 'x' ) ).parse(),
				cases[ i ].expected,
				cases[ i ].key
			);
		}
	} );

	QUnit.test( 'Do not allow javascript: urls', function ( assert ) {
		mw.messages.set( 'illegal-url', '[javascript:alert(1) foo]' );
		mw.messages.set( 'illegal-url-param', '[$1 foo]' );

		this.suppressWarnings();

		assert.strictEqual(
			formatParse( 'illegal-url' ),
			'[javascript:alert(1) foo]',
			'illegal-url: \'parse\' format'
		);

		assert.strictEqual(
			// eslint-disable-next-line no-script-url
			formatParse( 'illegal-url-param', 'javascript:alert(1)' ),
			'[javascript:alert(1) foo]',
			'illegal-url-param: \'parse\' format'
		);
	} );

	QUnit.test( 'Do not allow arbitrary style', function ( assert ) {
		mw.messages.set( 'illegal-style', '<span style="background-image:url( http://example.com )">bar</span>' );

		this.suppressWarnings();

		assert.strictEqual(
			formatParse( 'illegal-style' ),
			'&lt;span style="background-image:url( http://example.com )"&gt;bar&lt;/span&gt;',
			'illegal-style: \'parse\' format'
		);
	} );

	QUnit.test( 'Integration', ( assert ) => {
		const expected = '<b><a title="Bold" href="/wiki/Bold">Bold</a>!</b>';
		mw.messages.set( 'integration-test', '<b>[[Bold]]!</b>' );
		mw.messages.set( 'param-test', 'Hello $1' );
		mw.messages.set( 'param-test-with-link', 'Hello $1 [[$2|$3]]' );

		assert.strictEqual(
			mw.message( 'integration-test' ).parse(),
			expected,
			'mw.message().parse() works correctly'
		);

		assert.strictEqual(
			$( '<span>' ).append( mw.message( 'integration-test' ).parseDom() ).html(),
			expected,
			'mw.message().parseDom() works correctly'
		);

		assert.strictEqual(
			$( '<span>' ).msg( 'integration-test' ).html(),
			expected,
			'jQuery plugin $.fn.msg() works correctly'
		);

		assert.true(
			mw.message( 'integration-test' ).isParseable(),
			'mw.message().isParseable() works correctly'
		);

		assert.strictEqual(
			mw.message( 'param-test', $( '<span>' ).text( 'World' ) ).parse(),
			'Hello <span>World</span>',
			'Passing a jQuery object as a parameter to a message without wikitext works correctly'
		);

		mw.messages.set( 'object-double-replace', 'Foo 1: $1 2: $1' );
		const $messageArgument = $( '<div class="bar">&gt;</div>' );
		const $message = $( '<span>' ).msg( 'object-double-replace', $messageArgument );
		assert.true(
			$message[ 0 ].contains( $messageArgument[ 0 ] ),
			'The original jQuery object is actually in the DOM'
		);

		assert.strictEqual(
			mw.message( 'param-test', $( '<span>' ).text( 'World' ).get( 0 ) ).parse(),
			'Hello <span>World</span>',
			'Passing a DOM node as a parameter to a message without wikitext works correctly'
		);

		assert.strictEqual(
			mw.message( 'param-test', undefined ).parse(),
			'Hello $1',
			'Passing undefined as a parameter to a message does not throw an exception'
		);

		assert.strictEqual(
			mw.message(
				'param-test-with-link',
				$( '<span>' ).text( 'cruel' ),
				'Globe',
				'world'
			).parse(),
			'Hello <span>cruel</span> <a title="Globe" href="/wiki/Globe">world</a>',
			'Message with a jQuery parameter and a parsed link'
		);

		mw.messages.set( 'integration-test-extlink', '[$1 Link]' );
		const msg = mw.message(
			'integration-test-extlink',
			$( '<a>' ).attr( 'href', 'http://example.com/' )
		);
		msg.parse(); // Not a no-op
		assert.strictEqual(
			msg.parse(),
			'<a href="http://example.com/">Link</a>',
			'Calling .parse() multiple times does not duplicate link contents'
		);

		mw.config.set( 'wgUserLanguage', 'qqx' );

		const $bar = $( '<b>' ).text( 'bar' );
		mw.messages.set( 'qqx-message', '(qqx-message)' );
		mw.messages.set( 'non-qqx-message', '<b>hello world</b>' );

		assert.strictEqual( mw.message( 'missing-message' ).parse(), '(missing-message)', 'qqx message (missing)' );
		assert.strictEqual( mw.message( 'missing-message', $bar, 'baz' ).parse(), '(missing-message: <b>bar</b>, baz)', 'qqx message (missing) with parameters' );
		assert.strictEqual( mw.message( 'qqx-message' ).parse(), '(qqx-message)', 'qqx message (defined)' );
		assert.strictEqual( mw.message( 'qqx-message', $bar, 'baz' ).parse(), '(qqx-message: <b>bar</b>, baz)', 'qqx message (defined) with parameters' );
		assert.strictEqual( mw.message( 'non-qqx-message' ).parse(), '<b>hello world</b>', 'non-qqx message in qqx mode' );
	} );

	QUnit.test( 'setParserDefaults', ( assert ) => {
		jqueryMsg.setParserDefaults( {
			magic: {
				FOO: 'foo',
				BAR: 'bar'
			}
		} );

		assert.deepEqual(
			jqueryMsg.getParserDefaults().magic,
			{
				FOO: 'foo',
				BAR: 'bar'
			},
			'setParserDefaults updates the parser defaults'
		);
	} );
}() );
