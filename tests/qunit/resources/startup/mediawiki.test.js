( function () {
	const jqueryMsg = require( 'mediawiki.jqueryMsg' ).test;
	QUnit.module( 'mediawiki' );

	QUnit.test( 'Initial check', ( assert ) => {
		assert.strictEqual( typeof window.jQuery, 'function', 'jQuery defined' );
		assert.strictEqual( typeof window.$, 'function', '$ defined' );
		assert.strictEqual( window.$, window.jQuery, '$ alias to jQuery' );

		assert.strictEqual( typeof window.mediaWiki, 'object', 'mediaWiki defined' );
		assert.strictEqual( typeof window.mw, 'object', 'mw defined' );
		assert.strictEqual( window.mw, window.mediaWiki, 'mw alias to mediaWiki' );
	} );

	QUnit.test( 'mw.format', ( assert ) => {
		assert.strictEqual(
			mw.format( 'Format $1 $2', 'foo', 'bar' ),
			'Format foo bar',
			'Simple parameters'
		);
		assert.strictEqual(
			mw.format( 'Format $1 $2' ),
			'Format $1 $2',
			'Missing parameters'
		);
	} );

	QUnit.test( 'mw.now', ( assert ) => {
		assert.strictEqual( typeof mw.now(), 'number', 'Return a number' );
		assert.strictEqual(
			String( Math.round( mw.now() ) ).length,
			String( Date.now() ).length,
			'Match size of current timestamp'
		);
	} );

	QUnit.module( 'mw.Message', ( hooks ) => {
		var parserDefaults;
		hooks.before( () => {
			parserDefaults = jqueryMsg.getParserDefaults();
			jqueryMsg.setParserDefaults( {
				magic: {
					SITENAME: 'My Wiki'
				}
			} );
			mw.messages.set( {
				hello: 'Hello <b>awesome</b> world',
				script: '<script  >alert( "Who?" );</script>'
			} );
		} );
		hooks.after( () => {
			jqueryMsg.setParserDefaults( parserDefaults );
			mw.config.set( 'wgUserLanguage', 'qqx' );
		} );

		QUnit.test( 'Construct', ( assert ) => {
			var hello = mw.message( 'hello' );

			assert.strictEqual( hello.map, mw.messages, 'internal "map" property' );
			assert.strictEqual( hello.key, 'hello', 'internal "key" property' );
			assert.deepEqual( hello.parameters, [], 'internal "parameters" property' );
		} );

		QUnit.test( 'plain()', ( assert ) => {
			var hello = mw.message( 'hello' );
			assert.strictEqual( hello.plain(), 'Hello <b>awesome</b> world', 'hello' );
			var script = mw.message( 'script' );
			assert.strictEqual( script.plain(), '<script  >alert( "Who?" );</script>', 'script' );
		} );

		QUnit.test( 'escaped()', ( assert ) => {
			var hello = mw.message( 'hello' );
			assert.strictEqual( hello.escaped(), 'Hello &lt;b&gt;awesome&lt;/b&gt; world', 'hello' );
			var script = mw.message( 'script' );
			assert.strictEqual( script.escaped(), '&lt;script  &gt;alert( &quot;Who?&quot; );&lt;/script&gt;', 'script' );
		} );

		QUnit.test( 'parse()', ( assert ) => {
			var hello = mw.message( 'hello' );
			assert.strictEqual( hello.parse(), 'Hello <b>awesome</b> world', 'hello' );
			var script = mw.message( 'script' );
			assert.strictEqual( script.parse(), '&lt;script  &gt;alert( "Who?" );&lt;/script&gt;', 'script' );
		} );

		QUnit.test( 'exists()', ( assert ) => {
			var hello = mw.message( 'hello' );
			assert.true( hello.exists(), 'Existing message' );

			var goodbye = mw.message( 'goodbye' );
			assert.false( goodbye.exists(), 'Non-existing message' );
		} );

		QUnit.test( 'toString() non-existing', ( assert ) => {
			var obj = mw.message( 'good<>bye' );
			var expected = '⧼good&lt;&gt;bye⧽';
			assert.strictEqual( obj.plain(), expected, 'plain' );
			assert.strictEqual( obj.text(), expected, 'text' );
			assert.strictEqual( obj.escaped(), expected, 'escaped' );
			assert.strictEqual( obj.parse(), expected, 'parse' );

			mw.config.set( 'wgUserLanguage', 'qqx' );
			mw.messages.set( 'test-qqx', '(test-qqx)' );
			mw.messages.set( 'test-nonqqx', 'hello world' );

			assert.strictEqual( mw.message( 'missing-message' ).plain(), '⧼missing-message⧽', 'qqx message (missing)' );
			assert.strictEqual( mw.message( 'missing-message', 'bar', 'baz' ).plain(), '⧼missing-message⧽', 'qqx message (missing) with parameters' );
			assert.strictEqual( mw.message( 'test-qqx' ).plain(), '(test-qqx)', 'qqx message (defined)' );
			assert.strictEqual( mw.message( 'test-qqx', 'bar', 'baz' ).plain(), '(test-qqx: bar, baz)', 'qqx message (defined) with parameters' );
			assert.strictEqual( mw.message( 'test-nonqqx' ).plain(), 'hello world', 'non-qqx message in qqx mode' );
		} );

		// Basic integration test for magic words
		// See mediawiki.jqueryMsg.test.js for deep coverage.
		QUnit.test( 'jqueryMsg / Magic words', ( assert ) => {
			mw.messages.set( {
				'multiple-curly-brace': '"{{SITENAME}}" is the home of {{int:other-message}}',
				'other-message': 'Other Message',
				'test-formatnum': '{{formatnum:$1}}',
				'test-gender': '{{GENDER:$1|his|her|their}} {{PLURAL:$2|thing|things}}',
				'test-grammar': 'Przeszukaj {{GRAMMAR:grammar_case_foo|{{SITENAME}}}}',
				'test-int': 'Some {{int:other-message}}',
				'test-plural': 'There {{PLURAL:$1|is|are}} $1 {{PLURAL:$1|result|results}}'
			} );

			var obj = mw.message( 'test-plural', 6 );
			var expected = 'There are 6 results';

			assert.strictEqual( obj.plain(), 'There {{PLURAL:6|is|are}} 6 {{PLURAL:6|result|results}}', 'plain applies parameter but leaves magic words' );
			assert.strictEqual( obj.text(), expected, 'Plural text' );
			assert.strictEqual( obj.escaped(), expected, 'Plural escaped' );
			assert.strictEqual( obj.parse(), expected, 'Plural parse' );

			// Use English for formatnum
			mw.config.set( 'wgUserLanguage', 'en' );
			assert.strictEqual( mw.message( 'test-formatnum', '987654321.654321' ).text(), '987,654,321.654', 'Expand formatnum' );
			assert.strictEqual( mw.message( 'test-grammar' ).text(), 'Przeszukaj My Wiki', 'Expand grammar' );
			assert.strictEqual( mw.message( 'test-int' ).text(), 'Some Other Message', 'Expand int' );

			assert.strictEqual( mw.message( 'test-gender', 'male', 1 ).text(), 'his thing', 'Gender male' );
			assert.strictEqual( mw.message( 'test-gender', 'female', 1 ).text(), 'her thing', 'Gender female' );
			assert.strictEqual( mw.message( 'test-gender', 'unknown', 1 ).text(), 'their thing', 'Gender neutral' );
			assert.strictEqual( mw.message( 'test-gender', 'unknown', 10 ).text(), 'their things', 'Gender neutral plural' );

			obj = mw.message( 'multiple-curly-brace' );
			assert.strictEqual( obj.escaped(), '&quot;My Wiki&quot; is the home of Other Message', 'Expand sitename and int' );
		} );

		QUnit.test( 'mw.msg()', ( assert ) => {
			mw.messages.set( 'hello', 'Hello <b>awesome</b> world' );
			assert.strictEqual( mw.msg( 'goodbye' ), '⧼goodbye⧽', 'Non-existing message' );
			assert.strictEqual( mw.msg( 'hello' ), 'Hello <b>awesome</b> world', 'Shortcut does not escape' );
			assert.strictEqual( mw.msg( 'test-gender', 'unknown', 10 ), 'their things', 'Shortcut does text/magic expansion' );
		} );
	} );
}() );
