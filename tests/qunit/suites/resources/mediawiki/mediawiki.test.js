/*jshint -W024 */
( function ( mw, $ ) {
	var specialCharactersPageName,
		// Can't mock SITENAME since jqueryMsg caches it at load
		siteName = mw.config.get( 'wgSiteName' );

	// Since QUnitTestResources.php loads both mediawiki and mediawiki.jqueryMsg as
	// dependencies, this only tests the monkey-patched behavior with the two of them combined.

	// See mediawiki.jqueryMsg.test.js for unit tests for jqueryMsg-specific functionality.

	QUnit.module( 'mediawiki', QUnit.newMwEnvironment( {
		setup: function () {
			specialCharactersPageName = '"Who" wants to be a millionaire & live on \'Exotic Island\'?';
		},
		config: {
			wgArticlePath: '/wiki/$1',

			// For formatnum tests
			wgUserLanguage: 'en'
		},
		// Messages used in multiple tests
		messages: {
			'other-message': 'Other Message',
			'mediawiki-test-pagetriage-del-talk-page-notify-summary': 'Notifying author of deletion nomination for [[$1]]',
			'gender-plural-msg': '{{GENDER:$1|he|she|they}} {{PLURAL:$2|is|are}} awesome',
			'grammar-msg': 'Przeszukaj {{GRAMMAR:grammar_case_foo|{{SITENAME}}}}',
			'formatnum-msg': '{{formatnum:$1}}',
			'int-msg': 'Some {{int:other-message}}',
			'mediawiki-test-version-entrypoints-index-php': '[https://www.mediawiki.org/wiki/Manual:index.php index.php]',
			'external-link-replace': 'Foo [$1 bar]'
		}
	} ) );

	mw.loader.addSource(
		'testloader',
		QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/load.mock.php' )
	);

	QUnit.test( 'Initial check', 8, function ( assert ) {
		assert.ok( window.jQuery, 'jQuery defined' );
		assert.ok( window.$, '$ defined' );
		assert.strictEqual( window.$, window.jQuery, '$ alias to jQuery' );

		this.suppressWarnings();
		assert.ok( window.$j, '$j defined' );
		assert.strictEqual( window.$j, window.jQuery, '$j alias to jQuery' );
		this.restoreWarnings();

		// window.mw and window.mediaWiki are not deprecated, but for some reason
		// PhantomJS is triggerring the accessors on all mw.* properties in this test,
		// and with that lots of unrelated deprecation notices.
		this.suppressWarnings();
		assert.ok( window.mediaWiki, 'mediaWiki defined' );
		assert.ok( window.mw, 'mw defined' );
		assert.strictEqual( window.mw, window.mediaWiki, 'mw alias to mediaWiki' );
		this.restoreWarnings();
	} );

	QUnit.test( 'mw.Map', 35, function ( assert ) {
		var arry, conf, funky, globalConf, nummy, someValues;

		conf = new mw.Map();
		// Dummy variables
		funky = function () {};
		arry = [];
		nummy = 7;

		// Single get and set

		assert.strictEqual( conf.set( 'foo', 'Bar' ), true, 'Map.set returns boolean true if a value was set for a valid key string' );
		assert.equal( conf.get( 'foo' ), 'Bar', 'Map.get returns a single value value correctly' );

		assert.strictEqual( conf.get( 'example' ), null, 'Map.get returns null if selection was a string and the key was not found' );
		assert.strictEqual( conf.get( 'example', arry ), arry, 'Map.get returns fallback by reference if the key was not found' );
		assert.strictEqual( conf.get( 'example', undefined ), undefined, 'Map.get supports `undefined` as fallback instead of `null`' );

		assert.strictEqual( conf.get( 'constructor' ), null, 'Map.get does not look at Object.prototype of internal storage (constructor)' );
		assert.strictEqual( conf.get( 'hasOwnProperty' ), null, 'Map.get does not look at Object.prototype of internal storage (hasOwnProperty)' );

		conf.set( 'hasOwnProperty', function () { return true; } );
		assert.strictEqual( conf.get( 'example', 'missing' ), 'missing', 'Map.get uses neutral hasOwnProperty method (positive)' );

		conf.set( 'example', 'Foo' );
		conf.set( 'hasOwnProperty', function () { return false; } );
		assert.strictEqual( conf.get( 'example' ), 'Foo', 'Map.get uses neutral hasOwnProperty method (negative)' );

		assert.strictEqual( conf.set( 'constructor', 42 ), true, 'Map.set for key "constructor"' );
		assert.strictEqual( conf.get( 'constructor' ), 42, 'Map.get for key "constructor"' );

		assert.strictEqual( conf.set( 'undef' ), false, 'Map.set requires explicit value (no undefined default)' );

		assert.strictEqual( conf.set( 'undef', undefined ), true, 'Map.set allows setting value to `undefined`' );
		assert.equal( conf.get( 'undef', 'fallback' ), undefined, 'Map.get supports retreiving value of `undefined`' );

		assert.strictEqual( conf.set( funky, 'Funky' ), false, 'Map.set returns boolean false if key was invalid (Function)' );
		assert.strictEqual( conf.set( arry, 'Arry' ), false, 'Map.set returns boolean false if key was invalid (Array)' );
		assert.strictEqual( conf.set( nummy, 'Nummy' ), false, 'Map.set returns boolean false if key was invalid (Number)' );

		assert.strictEqual( conf.get( funky ), null, 'Map.get ruturns null if selection was invalid (Function)' );
		assert.strictEqual( conf.get( nummy ), null, 'Map.get ruturns null if selection was invalid (Number)' );

		conf.set( String( nummy ), 'I used to be a number' );

		assert.strictEqual( conf.exists( 'doesNotExist' ), false, 'Map.exists where property does not exist' );
		assert.strictEqual( conf.exists( 'undef' ), true, 'Map.exists where value is `undefined`' );
		assert.strictEqual( conf.exists( nummy ), false, 'Map.exists where key is invalid but looks like an existing key' );

		// Multiple values at once
		someValues = {
			'foo': 'bar',
			'lorem': 'ipsum',
			'MediaWiki': true
		};
		assert.strictEqual( conf.set( someValues ), true, 'Map.set returns boolean true if multiple values were set by passing an object' );
		assert.deepEqual( conf.get( ['foo', 'lorem'] ), {
			'foo': 'bar',
			'lorem': 'ipsum'
		}, 'Map.get returns multiple values correctly as an object' );

		assert.deepEqual( conf, new mw.Map( conf.values ), 'new mw.Map maps over existing values-bearing object' );

		assert.deepEqual( conf.get( ['foo', 'notExist'] ), {
			'foo': 'bar',
			'notExist': null
		}, 'Map.get return includes keys that were not found as null values' );

		// Interacting with globals and accessing the values object
		assert.strictEqual( conf.get(), conf.values, 'Map.get returns the entire values object by reference (if called without arguments)' );

		conf.set( 'globalMapChecker', 'Hi' );

		assert.ok( ( 'globalMapChecker' in window ) === false, 'Map does not its store values in the window object by default' );

		globalConf = new mw.Map( true );
		globalConf.set( 'anotherGlobalMapChecker', 'Hello' );

		assert.ok( 'anotherGlobalMapChecker' in window, 'global Map stores its values in the window object' );

		assert.equal( globalConf.get( 'anotherGlobalMapChecker' ), 'Hello', 'get value from global Map via get()' );
		this.suppressWarnings();
		assert.equal( window.anotherGlobalMapChecker, 'Hello', 'get value from global Map via window object' );
		this.restoreWarnings();

		// Change value via global Map
		globalConf.set('anotherGlobalMapChecker', 'Again');
		assert.equal( globalConf.get( 'anotherGlobalMapChecker' ), 'Again', 'Change in global Map reflected via get()' );
		this.suppressWarnings();
		assert.equal( window.anotherGlobalMapChecker, 'Again', 'Change in global Map reflected window object' );
		this.restoreWarnings();

		// Change value via window object
		this.suppressWarnings();
		window.anotherGlobalMapChecker = 'World';
		assert.equal( window.anotherGlobalMapChecker, 'World', 'Change in window object works' );
		this.restoreWarnings();
		assert.equal( globalConf.get( 'anotherGlobalMapChecker' ), 'Again', 'Change in window object not reflected in global Map' );

		// Whitelist this global variable for QUnit's 'noglobal' mode
		if ( QUnit.config.noglobals ) {
			QUnit.config.pollution.push( 'anotherGlobalMapChecker' );
		}
	} );

	QUnit.test( 'mw.config', 1, function ( assert ) {
		assert.ok( mw.config instanceof mw.Map, 'mw.config instance of mw.Map' );
	} );

	QUnit.test( 'mw.message & mw.messages', 100, function ( assert ) {
		var goodbye, hello;

		// Convenience method for asserting the same result for multiple formats
		function assertMultipleFormats( messageArguments, formats, expectedResult, assertMessage ) {
			var format, i,
				len = formats.length;

			for ( i = 0; i < len; i++ ) {
				format = formats[i];
				assert.equal( mw.message.apply( null, messageArguments )[format](), expectedResult, assertMessage + ' when format is ' + format );
			}
		}

		assert.ok( mw.messages, 'messages defined' );
		assert.ok( mw.messages instanceof mw.Map, 'mw.messages instance of mw.Map' );
		assert.ok( mw.messages.set( 'hello', 'Hello <b>awesome</b> world' ), 'mw.messages.set: Register' );

		hello = mw.message( 'hello' );

		// https://bugzilla.wikimedia.org/show_bug.cgi?id=44459
		assert.equal( hello.format, 'text', 'Message property "format" defaults to "text"' );

		assert.strictEqual( hello.map, mw.messages, 'Message property "map" defaults to the global instance in mw.messages' );
		assert.equal( hello.key, 'hello', 'Message property "key" (currect key)' );
		assert.deepEqual( hello.parameters, [], 'Message property "parameters" defaults to an empty array' );

		// Todo
		assert.ok( hello.params, 'Message prototype "params"' );

		hello.format = 'plain';
		assert.equal( hello.toString(), 'Hello <b>awesome</b> world', 'Message.toString returns the message as a string with the current "format"' );

		assert.equal( hello.escaped(), 'Hello &lt;b&gt;awesome&lt;/b&gt; world', 'Message.escaped returns the escaped message' );
		assert.equal( hello.format, 'escaped', 'Message.escaped correctly updated the "format" property' );

		assert.ok( mw.messages.set( 'multiple-curly-brace', '"{{SITENAME}}" is the home of {{int:other-message}}' ), 'mw.messages.set: Register' );
		assertMultipleFormats( ['multiple-curly-brace'], ['text', 'parse'], '"' + siteName + '" is the home of Other Message', 'Curly brace format works correctly' );
		assert.equal( mw.message( 'multiple-curly-brace' ).plain(), mw.messages.get( 'multiple-curly-brace' ), 'Plain format works correctly for curly brace message' );
		assert.equal( mw.message( 'multiple-curly-brace' ).escaped(), mw.html.escape( '"' + siteName + '" is the home of Other Message' ), 'Escaped format works correctly for curly brace message' );

		assert.ok( mw.messages.set( 'multiple-square-brackets-and-ampersand', 'Visit the [[Project:Community portal|community portal]] & [[Project:Help desk|help desk]]' ), 'mw.messages.set: Register' );
		assertMultipleFormats( ['multiple-square-brackets-and-ampersand'], ['plain', 'text'], mw.messages.get( 'multiple-square-brackets-and-ampersand' ), 'Square bracket message is not processed' );
		assert.equal( mw.message( 'multiple-square-brackets-and-ampersand' ).escaped(), 'Visit the [[Project:Community portal|community portal]] &amp; [[Project:Help desk|help desk]]', 'Escaped format works correctly for square bracket message' );
		assert.htmlEqual( mw.message( 'multiple-square-brackets-and-ampersand' ).parse(), 'Visit the ' +
			'<a title="Project:Community portal" href="/wiki/Project:Community_portal">community portal</a>' +
			' &amp; <a title="Project:Help desk" href="/wiki/Project:Help_desk">help desk</a>', 'Internal links work with parse' );

		assertMultipleFormats( ['mediawiki-test-version-entrypoints-index-php'], ['plain', 'text', 'escaped'], mw.messages.get( 'mediawiki-test-version-entrypoints-index-php' ), 'External link markup is unprocessed' );
		assert.htmlEqual( mw.message( 'mediawiki-test-version-entrypoints-index-php' ).parse(), '<a href="https://www.mediawiki.org/wiki/Manual:index.php">index.php</a>', 'External link works correctly in parse mode' );

		assertMultipleFormats( ['external-link-replace', 'http://example.org/?x=y&z'], ['plain', 'text'], 'Foo [http://example.org/?x=y&z bar]', 'Parameters are substituted but external link is not processed' );
		assert.equal( mw.message( 'external-link-replace', 'http://example.org/?x=y&z' ).escaped(), 'Foo [http://example.org/?x=y&amp;z bar]', 'In escaped mode, parameters are substituted and ampersand is escaped, but external link is not processed' );
		assert.htmlEqual( mw.message( 'external-link-replace', 'http://example.org/?x=y&z' ).parse(), 'Foo <a href="http://example.org/?x=y&amp;z">bar</a>', 'External link with replacement works in parse mode without double-escaping' );

		hello.parse();
		assert.equal( hello.format, 'parse', 'Message.parse correctly updated the "format" property' );

		hello.plain();
		assert.equal( hello.format, 'plain', 'Message.plain correctly updated the "format" property' );

		hello.text();
		assert.equal( hello.format, 'text', 'Message.text correctly updated the "format" property' );

		assert.strictEqual( hello.exists(), true, 'Message.exists returns true for existing messages' );

		goodbye = mw.message( 'goodbye' );
		assert.strictEqual( goodbye.exists(), false, 'Message.exists returns false for nonexistent messages' );

		assertMultipleFormats( ['goodbye'], ['plain', 'text'], '<goodbye>', 'Message.toString returns <key> if key does not exist' );
		// bug 30684
		assertMultipleFormats( ['goodbye'], ['parse', 'escaped'], '&lt;goodbye&gt;', 'Message.toString returns properly escaped &lt;key&gt; if key does not exist' );

		assert.ok( mw.messages.set( 'plural-test-msg', 'There {{PLURAL:$1|is|are}} $1 {{PLURAL:$1|result|results}}' ), 'mw.messages.set: Register' );
		assertMultipleFormats( ['plural-test-msg', 6], ['text', 'parse', 'escaped'], 'There are 6 results', 'plural get resolved' );
		assert.equal( mw.message( 'plural-test-msg', 6 ).plain(), 'There {{PLURAL:6|is|are}} 6 {{PLURAL:6|result|results}}', 'Parameter is substituted but plural is not resolved in plain' );

		assert.ok( mw.messages.set( 'plural-test-msg-explicit', 'There {{plural:$1|is one car|are $1 cars|0=are no cars|12=are a dozen cars}}' ), 'mw.messages.set: Register message with explicit plural forms' );
		assertMultipleFormats( ['plural-test-msg-explicit', 12], ['text', 'parse', 'escaped'], 'There are a dozen cars', 'explicit plural get resolved' );

		assert.ok( mw.messages.set( 'plural-test-msg-explicit-beginning', 'Basket has {{plural:$1|0=no eggs|12=a dozen eggs|6=half a dozen eggs|one egg|$1 eggs}}' ), 'mw.messages.set: Register message with explicit plural forms' );
		assertMultipleFormats( ['plural-test-msg-explicit-beginning', 1], ['text', 'parse', 'escaped'], 'Basket has one egg', 'explicit plural given at beginning get resolved for singular' );
		assertMultipleFormats( ['plural-test-msg-explicit-beginning', 4], ['text', 'parse', 'escaped'], 'Basket has 4 eggs', 'explicit plural given at beginning get resolved for plural' );
		assertMultipleFormats( ['plural-test-msg-explicit-beginning', 6], ['text', 'parse', 'escaped'], 'Basket has half a dozen eggs', 'explicit plural given at beginning get resolved for 6' );
		assertMultipleFormats( ['plural-test-msg-explicit-beginning', 0], ['text', 'parse', 'escaped'], 'Basket has no eggs', 'explicit plural given at beginning get resolved for 0' );

		assertMultipleFormats( ['mediawiki-test-pagetriage-del-talk-page-notify-summary'], ['plain', 'text'], mw.messages.get( 'mediawiki-test-pagetriage-del-talk-page-notify-summary' ), 'Double square brackets with no parameters unchanged' );

		assertMultipleFormats( ['mediawiki-test-pagetriage-del-talk-page-notify-summary', specialCharactersPageName], ['plain', 'text'], 'Notifying author of deletion nomination for [[' + specialCharactersPageName + ']]', 'Double square brackets with one parameter' );

		assert.equal( mw.message( 'mediawiki-test-pagetriage-del-talk-page-notify-summary', specialCharactersPageName ).escaped(), 'Notifying author of deletion nomination for [[' + mw.html.escape( specialCharactersPageName ) + ']]', 'Double square brackets with one parameter, when escaped' );

		assert.ok( mw.messages.set( 'mediawiki-test-categorytree-collapse-bullet', '[<b>−</b>]' ), 'mw.messages.set: Register' );
		assert.equal( mw.message( 'mediawiki-test-categorytree-collapse-bullet' ).plain(), mw.messages.get( 'mediawiki-test-categorytree-collapse-bullet' ), 'Single square brackets unchanged in plain mode' );

		assert.ok( mw.messages.set( 'mediawiki-test-wikieditor-toolbar-help-content-signature-result', '<a href=\'#\' title=\'{{#special:mypage}}\'>Username</a> (<a href=\'#\' title=\'{{#special:mytalk}}\'>talk</a>)' ), 'mw.messages.set: Register' );
		assert.equal( mw.message( 'mediawiki-test-wikieditor-toolbar-help-content-signature-result' ).plain(), mw.messages.get( 'mediawiki-test-wikieditor-toolbar-help-content-signature-result' ), 'HTML message with curly braces is not changed in plain mode' );

		assertMultipleFormats( ['gender-plural-msg', 'male', 1], ['text', 'parse', 'escaped'], 'he is awesome', 'Gender and plural are resolved' );
		assert.equal( mw.message( 'gender-plural-msg', 'male', 1 ).plain(), '{{GENDER:male|he|she|they}} {{PLURAL:1|is|are}} awesome', 'Parameters are substituted, but gender and plural are not resolved in plain mode' );

		assert.equal( mw.message( 'grammar-msg' ).plain(), mw.messages.get( 'grammar-msg' ), 'Grammar is not resolved in plain mode' );
		assertMultipleFormats( ['grammar-msg'], ['text', 'parse'], 'Przeszukaj ' + siteName, 'Grammar is resolved' );
		assert.equal( mw.message( 'grammar-msg' ).escaped(), 'Przeszukaj ' + siteName, 'Grammar is resolved in escaped mode' );

		assertMultipleFormats( ['formatnum-msg', '987654321.654321'], ['text', 'parse', 'escaped'], '987,654,321.654', 'formatnum is resolved' );
		assert.equal( mw.message( 'formatnum-msg' ).plain(), mw.messages.get( 'formatnum-msg' ), 'formatnum is not resolved in plain mode' );

		assertMultipleFormats( ['int-msg'], ['text', 'parse', 'escaped'], 'Some Other Message', 'int is resolved' );
		assert.equal( mw.message( 'int-msg' ).plain(), mw.messages.get( 'int-msg' ), 'int is not resolved in plain mode' );

		assert.ok( mw.messages.set( 'mediawiki-italics-msg', '<i>Very</i> important' ),	'mw.messages.set: Register' );
		assertMultipleFormats( ['mediawiki-italics-msg'], ['plain', 'text', 'parse'], mw.messages.get( 'mediawiki-italics-msg' ), 'Simple italics unchanged' );
		assert.htmlEqual(
			mw.message( 'mediawiki-italics-msg' ).escaped(),
			'&lt;i&gt;Very&lt;/i&gt; important',
			'Italics are escaped in	escaped mode'
		);

		assert.ok( mw.messages.set( 'mediawiki-italics-with-link', 'An <i>italicized [[link|wiki-link]]</i>' ), 'mw.messages.set: Register' );
		assertMultipleFormats( ['mediawiki-italics-with-link'], ['plain', 'text'], mw.messages.get( 'mediawiki-italics-with-link' ), 'Italics with link unchanged' );
		assert.htmlEqual(
			mw.message( 'mediawiki-italics-with-link' ).escaped(),
			'An &lt;i&gt;italicized [[link|wiki-link]]&lt;/i&gt;',
			'Italics and link unchanged except for escaping in escaped mode'
		);
		assert.htmlEqual(
			mw.message( 'mediawiki-italics-with-link' ).parse(),
			'An <i>italicized <a title="link" href="' + mw.util.getUrl( 'link' ) + '">wiki-link</i>',
			'Italics with link inside in parse mode'
		);

		assert.ok( mw.messages.set( 'mediawiki-script-msg', '<script  >alert( "Who put this script here?" );</script>' ), 'mw.messages.set: Register' );
		assertMultipleFormats( ['mediawiki-script-msg'], ['plain', 'text'], mw.messages.get( 'mediawiki-script-msg' ), 'Script unchanged' );
		assert.htmlEqual(
			mw.message( 'mediawiki-script-msg' ).escaped(),
			'&lt;script  &gt;alert( "Who put this script here?" );&lt;/script&gt;',
			'Script escaped when using escaped format'
		);
		assert.htmlEqual(
			mw.message( 'mediawiki-script-msg' ).parse(),
			'&lt;script  &gt;alert( "Who put this script here?" );&lt;/script&gt;',
			'Script escaped when using parse format'
		);

	} );

	QUnit.test( 'mw.msg', 14, function ( assert ) {
		assert.ok( mw.messages.set( 'hello', 'Hello <b>awesome</b> world' ), 'mw.messages.set: Register' );
		assert.equal( mw.msg( 'hello' ), 'Hello <b>awesome</b> world', 'Gets message with default options (existing message)' );
		assert.equal( mw.msg( 'goodbye' ), '<goodbye>', 'Gets message with default options (nonexistent message)' );

		assert.ok( mw.messages.set( 'plural-item', 'Found $1 {{PLURAL:$1|item|items}}' ), 'mw.messages.set: Register' );
		assert.equal( mw.msg( 'plural-item', 5 ), 'Found 5 items', 'Apply plural for count 5' );
		assert.equal( mw.msg( 'plural-item', 0 ), 'Found 0 items', 'Apply plural for count 0' );
		assert.equal( mw.msg( 'plural-item', 1 ), 'Found 1 item', 'Apply plural for count 1' );

		assert.equal( mw.msg( 'mediawiki-test-pagetriage-del-talk-page-notify-summary', specialCharactersPageName ), 'Notifying author of deletion nomination for [[' + specialCharactersPageName + ']]', 'Double square brackets in mw.msg one parameter' );

		assert.equal( mw.msg( 'gender-plural-msg', 'male', 1 ), 'he is awesome', 'Gender test for male, plural count 1' );
		assert.equal( mw.msg( 'gender-plural-msg', 'female', '1' ), 'she is awesome', 'Gender test for female, plural count 1' );
		assert.equal( mw.msg( 'gender-plural-msg', 'unknown', 10 ), 'they are awesome', 'Gender test for neutral, plural count 10' );

		assert.equal( mw.msg( 'grammar-msg' ), 'Przeszukaj ' + siteName, 'Grammar is resolved' );

		assert.equal( mw.msg( 'formatnum-msg', '987654321.654321' ), '987,654,321.654', 'formatnum is resolved' );

		assert.equal( mw.msg( 'int-msg' ), 'Some Other Message', 'int is resolved' );
	} );

	/**
	 * The sync style load test (for @import). This is, in a way, also an open bug for
	 * ResourceLoader ("execute js after styles are loaded"), but browsers don't offer a
	 * way to get a callback from when a stylesheet is loaded (that is, including any
	 * @import rules inside). To work around this, we'll have a little time loop to check
	 * if the styles apply.
	 * Note: This test originally used new Image() and onerror to get a callback
	 * when the url is loaded, but that is fragile since it doesn't monitor the
	 * same request as the css @import, and Safari 4 has issues with
	 * onerror/onload not being fired at all in weird cases like this.
	 */
	function assertStyleAsync( assert, $element, prop, val, fn ) {
		var styleTestStart,
			el = $element.get( 0 ),
			styleTestTimeout = ( QUnit.config.testTimeout || 5000 ) - 200;

		function isCssImportApplied() {
			// Trigger reflow, repaint, redraw, whatever (cross-browser)
			var x = $element.css( 'height' );
			x = el.innerHTML;
			el.className = el.className;
			x = document.documentElement.clientHeight;

			return $element.css( prop ) === val;
		}

		function styleTestLoop() {
			var styleTestSince = new Date().getTime() - styleTestStart;
			// If it is passing or if we timed out, run the real test and stop the loop
			if ( isCssImportApplied() || styleTestSince > styleTestTimeout ) {
				assert.equal( $element.css( prop ), val,
					'style "' + prop + ': ' + val + '" from url is applied (after ' + styleTestSince + 'ms)'
				);

				if ( fn ) {
					fn();
				}

				return;
			}
			// Otherwise, keep polling
			setTimeout( styleTestLoop );
		}

		// Start the loop
		styleTestStart = new Date().getTime();
		styleTestLoop();
	}

	function urlStyleTest( selector, prop, val ) {
		return QUnit.fixurl(
			mw.config.get( 'wgScriptPath' ) +
				'/tests/qunit/data/styleTest.css.php?' +
				$.param( {
					selector: selector,
					prop: prop,
					val: val
				} )
		);
	}

	QUnit.asyncTest( 'mw.loader', 2, function ( assert ) {
		var isAwesomeDone;

		mw.loader.testCallback = function () {
			QUnit.start();
			assert.strictEqual( isAwesomeDone, undefined, 'Implementing module is.awesome: isAwesomeDone should still be undefined' );
			isAwesomeDone = true;
		};

		mw.loader.implement( 'test.callback', [QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/callMwLoaderTestCallback.js' )], {}, {} );

		mw.loader.using( 'test.callback', function () {

			// /sample/awesome.js declares the "mw.loader.testCallback" function
			// which contains a call to start() and ok()
			assert.strictEqual( isAwesomeDone, true, 'test.callback module should\'ve caused isAwesomeDone to be true' );
			delete mw.loader.testCallback;

		}, function () {
			QUnit.start();
			assert.ok( false, 'Error callback fired while loader.using "test.callback" module' );
		} );
	} );

	QUnit.asyncTest( 'mw.loader with Object method as module name', 2, function ( assert ) {
		var isAwesomeDone;

		mw.loader.testCallback = function () {
			QUnit.start();
			assert.strictEqual( isAwesomeDone, undefined, 'Implementing module hasOwnProperty: isAwesomeDone should still be undefined' );
			isAwesomeDone = true;
		};

		mw.loader.implement( 'hasOwnProperty', [QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/callMwLoaderTestCallback.js' )], {}, {} );

		mw.loader.using( 'hasOwnProperty', function () {

			// /sample/awesome.js declares the "mw.loader.testCallback" function
			// which contains a call to start() and ok()
			assert.strictEqual( isAwesomeDone, true, 'hasOwnProperty module should\'ve caused isAwesomeDone to be true' );
			delete mw.loader.testCallback;

		}, function () {
			QUnit.start();
			assert.ok( false, 'Error callback fired while loader.using "hasOwnProperty" module' );
		} );
	} );

	QUnit.asyncTest( 'mw.loader.using( .. ).promise', 2, function ( assert ) {
		var isAwesomeDone;

		mw.loader.testCallback = function () {
			QUnit.start();
			assert.strictEqual( isAwesomeDone, undefined, 'Implementing module is.awesome: isAwesomeDone should still be undefined' );
			isAwesomeDone = true;
		};

		mw.loader.implement( 'test.promise', [QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/callMwLoaderTestCallback.js' )], {}, {} );

		mw.loader.using( 'test.promise' )
		.done( function () {

			// /sample/awesome.js declares the "mw.loader.testCallback" function
			// which contains a call to start() and ok()
			assert.strictEqual( isAwesomeDone, true, 'test.promise module should\'ve caused isAwesomeDone to be true' );
			delete mw.loader.testCallback;

		} )
		.fail( function () {
			QUnit.start();
			assert.ok( false, 'Error callback fired while loader.using "test.promise" module' );
		} );
	} );

	QUnit.asyncTest( 'mw.loader.implement( styles={ "css": [text, ..] } )', 2, function ( assert ) {
		var $element = $( '<div class="mw-test-implement-a"></div>' ).appendTo( '#qunit-fixture' );

		assert.notEqual(
			$element.css( 'float' ),
			'right',
			'style is clear'
		);

		mw.loader.implement(
			'test.implement.a',
			function () {
				assert.equal(
					$element.css( 'float' ),
					'right',
					'style is applied'
				);
				QUnit.start();
			},
			{
				'all': '.mw-test-implement-a { float: right; }'
			},
			{}
		);

		mw.loader.load( [
			'test.implement.a'
		] );
	} );

	QUnit.asyncTest( 'mw.loader.implement( styles={ "url": { <media>: [url, ..] } } )', 7, function ( assert ) {
		var $element1 = $( '<div class="mw-test-implement-b1"></div>' ).appendTo( '#qunit-fixture' ),
			$element2 = $( '<div class="mw-test-implement-b2"></div>' ).appendTo( '#qunit-fixture' ),
			$element3 = $( '<div class="mw-test-implement-b3"></div>' ).appendTo( '#qunit-fixture' );

		assert.notEqual(
			$element1.css( 'text-align' ),
			'center',
			'style is clear'
		);
		assert.notEqual(
			$element2.css( 'float' ),
			'left',
			'style is clear'
		);
		assert.notEqual(
			$element3.css( 'text-align' ),
			'right',
			'style is clear'
		);

		mw.loader.implement(
			'test.implement.b',
			function () {
				// Note: QUnit.start() must only be called when the entire test is
				// complete. So, make sure that we don't start until *both*
				// assertStyleAsync calls have completed.
				var pending = 2;
				assertStyleAsync( assert, $element2, 'float', 'left', function () {
					assert.notEqual( $element1.css( 'text-align' ), 'center', 'print style is not applied' );

					pending--;
					if ( pending === 0 ) {
						QUnit.start();
					}
				} );
				assertStyleAsync( assert, $element3, 'float', 'right', function () {
					assert.notEqual( $element1.css( 'text-align' ), 'center', 'print style is not applied' );

					pending--;
					if ( pending === 0 ) {
						QUnit.start();
					}
				} );
			},
			{
				'url': {
					'print': [urlStyleTest( '.mw-test-implement-b1', 'text-align', 'center' )],
					'screen': [
						// bug 40834: Make sure it actually works with more than 1 stylesheet reference
						urlStyleTest( '.mw-test-implement-b2', 'float', 'left' ),
						urlStyleTest( '.mw-test-implement-b3', 'float', 'right' )
					]
				}
			},
			{}
		);

		mw.loader.load( [
			'test.implement.b'
		] );
	} );

	// Backwards compatibility
	QUnit.asyncTest( 'mw.loader.implement( styles={ <media>: text } ) (back-compat)', 2, function ( assert ) {
		var $element = $( '<div class="mw-test-implement-c"></div>' ).appendTo( '#qunit-fixture' );

		assert.notEqual(
			$element.css( 'float' ),
			'right',
			'style is clear'
		);

		mw.loader.implement(
			'test.implement.c',
			function () {
				assert.equal(
					$element.css( 'float' ),
					'right',
					'style is applied'
				);
				QUnit.start();
			},
			{
				'all': '.mw-test-implement-c { float: right; }'
			},
			{}
		);

		mw.loader.load( [
			'test.implement.c'
		] );
	} );

	// Backwards compatibility
	QUnit.asyncTest( 'mw.loader.implement( styles={ <media>: [url, ..] } ) (back-compat)', 4, function ( assert ) {
		var $element = $( '<div class="mw-test-implement-d"></div>' ).appendTo( '#qunit-fixture' ),
			$element2 = $( '<div class="mw-test-implement-d2"></div>' ).appendTo( '#qunit-fixture' );

		assert.notEqual(
			$element.css( 'float' ),
			'right',
			'style is clear'
		);
		assert.notEqual(
			$element2.css( 'text-align' ),
			'center',
			'style is clear'
		);

		mw.loader.implement(
			'test.implement.d',
			function () {
				assertStyleAsync( assert, $element, 'float', 'right', function () {

					assert.notEqual( $element2.css( 'text-align' ), 'center', 'print style is not applied (bug 40500)' );

					QUnit.start();
				} );
			},
			{
				'all': [urlStyleTest( '.mw-test-implement-d', 'float', 'right' )],
				'print': [urlStyleTest( '.mw-test-implement-d2', 'text-align', 'center' )]
			},
			{}
		);

		mw.loader.load( [
			'test.implement.d'
		] );
	} );

	// @import (bug 31676)
	QUnit.asyncTest( 'mw.loader.implement( styles has @import)', 5, function ( assert ) {
		var isJsExecuted, $element;

		mw.loader.implement(
			'test.implement.import',
			function () {
				assert.strictEqual( isJsExecuted, undefined, 'javascript not executed multiple times' );
				isJsExecuted = true;

				assert.equal( mw.loader.getState( 'test.implement.import' ), 'ready', 'module state is "ready" while implement() is executing javascript' );

				$element = $( '<div class="mw-test-implement-import">Foo bar</div>' ).appendTo( '#qunit-fixture' );

				assert.equal( mw.msg( 'test-foobar' ), 'Hello Foobar, $1!', 'Messages are loaded before javascript execution' );

				assertStyleAsync( assert, $element, 'float', 'right', function () {
					assert.equal( $element.css( 'text-align' ), 'center',
						'CSS styles after the @import rule are working'
					);

					QUnit.start();
				} );
			},
			{
				'css': [
					'@import url(\''
						+ urlStyleTest( '.mw-test-implement-import', 'float', 'right' )
						+ '\');\n'
						+ '.mw-test-implement-import { text-align: center; }'
				]
			},
			{
				'test-foobar': 'Hello Foobar, $1!'
			}
		);

		mw.loader.load( 'test.implement' );

	} );

	QUnit.test( 'mw.loader.implement( only scripts )', 1, function ( assert ) {
		mw.loader.implement( 'test.onlyscripts', function () {} );
		assert.strictEqual( mw.loader.getState( 'test.onlyscripts' ), 'ready' );
	} );

	QUnit.asyncTest( 'mw.loader.implement( only messages )', 2, function ( assert ) {
		assert.assertFalse( mw.messages.exists( 'bug_29107' ), 'Verify that the test message doesn\'t exist yet' );

		mw.loader.implement( 'test.implement.msgs', [], {}, { 'bug_29107': 'loaded' } );
		mw.loader.using( 'test.implement.msgs', function () {
			QUnit.start();
			assert.ok( mw.messages.exists( 'bug_29107' ), 'Bug 29107: messages-only module should implement ok' );
		}, function () {
			QUnit.start();
			assert.ok( false, 'Error callback fired while implementing "test.implement.msgs" module' );
		} );
	} );

	QUnit.test( 'mw.loader erroneous indirect dependency', 4, function ( assert ) {
		// don't emit an error event
		this.sandbox.stub( mw, 'track' );

		mw.loader.register( [
			['test.module1', '0'],
			['test.module2', '0', ['test.module1']],
			['test.module3', '0', ['test.module2']]
		] );
		mw.loader.implement( 'test.module1', function () {
			throw new Error( 'expected' );
		}, {}, {} );
		assert.strictEqual( mw.loader.getState( 'test.module1' ), 'error', 'Expected "error" state for test.module1' );
		assert.strictEqual( mw.loader.getState( 'test.module2' ), 'error', 'Expected "error" state for test.module2' );
		assert.strictEqual( mw.loader.getState( 'test.module3' ), 'error', 'Expected "error" state for test.module3' );

		assert.strictEqual( mw.track.callCount, 1 );
	} );

	QUnit.test( 'mw.loader out-of-order implementation', 9, function ( assert ) {
		mw.loader.register( [
			['test.module4', '0'],
			['test.module5', '0', ['test.module4']],
			['test.module6', '0', ['test.module5']]
		] );
		mw.loader.implement( 'test.module4', function () {
		}, {}, {} );
		assert.strictEqual( mw.loader.getState( 'test.module4' ), 'ready', 'Expected "ready" state for test.module4' );
		assert.strictEqual( mw.loader.getState( 'test.module5' ), 'registered', 'Expected "registered" state for test.module5' );
		assert.strictEqual( mw.loader.getState( 'test.module6' ), 'registered', 'Expected "registered" state for test.module6' );
		mw.loader.implement( 'test.module6', function () {
		}, {}, {} );
		assert.strictEqual( mw.loader.getState( 'test.module4' ), 'ready', 'Expected "ready" state for test.module4' );
		assert.strictEqual( mw.loader.getState( 'test.module5' ), 'registered', 'Expected "registered" state for test.module5' );
		assert.strictEqual( mw.loader.getState( 'test.module6' ), 'loaded', 'Expected "loaded" state for test.module6' );
		mw.loader.implement( 'test.module5', function () {
		}, {}, {} );
		assert.strictEqual( mw.loader.getState( 'test.module4' ), 'ready', 'Expected "ready" state for test.module4' );
		assert.strictEqual( mw.loader.getState( 'test.module5' ), 'ready', 'Expected "ready" state for test.module5' );
		assert.strictEqual( mw.loader.getState( 'test.module6' ), 'ready', 'Expected "ready" state for test.module6' );
	} );

	QUnit.test( 'mw.loader missing dependency', 13, function ( assert ) {
		mw.loader.register( [
			['test.module7', '0'],
			['test.module8', '0', ['test.module7']],
			['test.module9', '0', ['test.module8']]
		] );
		mw.loader.implement( 'test.module8', function () {
		}, {}, {} );
		assert.strictEqual( mw.loader.getState( 'test.module7' ), 'registered', 'Expected "registered" state for test.module7' );
		assert.strictEqual( mw.loader.getState( 'test.module8' ), 'loaded', 'Expected "loaded" state for test.module8' );
		assert.strictEqual( mw.loader.getState( 'test.module9' ), 'registered', 'Expected "registered" state for test.module9' );
		mw.loader.state( 'test.module7', 'missing' );
		assert.strictEqual( mw.loader.getState( 'test.module7' ), 'missing', 'Expected "missing" state for test.module7' );
		assert.strictEqual( mw.loader.getState( 'test.module8' ), 'error', 'Expected "error" state for test.module8' );
		assert.strictEqual( mw.loader.getState( 'test.module9' ), 'error', 'Expected "error" state for test.module9' );
		mw.loader.implement( 'test.module9', function () {
		}, {}, {} );
		assert.strictEqual( mw.loader.getState( 'test.module7' ), 'missing', 'Expected "missing" state for test.module7' );
		assert.strictEqual( mw.loader.getState( 'test.module8' ), 'error', 'Expected "error" state for test.module8' );
		assert.strictEqual( mw.loader.getState( 'test.module9' ), 'error', 'Expected "error" state for test.module9' );
		mw.loader.using(
			['test.module7'],
			function () {
				assert.ok( false, 'Success fired despite missing dependency' );
				assert.ok( true, 'QUnit expected() count dummy' );
			},
			function ( e, dependencies ) {
				assert.strictEqual( $.isArray( dependencies ), true, 'Expected array of dependencies' );
				assert.deepEqual( dependencies, ['test.module7'], 'Error callback called with module test.module7' );
			}
		);
		mw.loader.using(
			['test.module9'],
			function () {
				assert.ok( false, 'Success fired despite missing dependency' );
				assert.ok( true, 'QUnit expected() count dummy' );
			},
			function ( e, dependencies ) {
				assert.strictEqual( $.isArray( dependencies ), true, 'Expected array of dependencies' );
				dependencies.sort();
				assert.deepEqual(
					dependencies,
					['test.module7', 'test.module8', 'test.module9'],
					'Error callback called with all three modules as dependencies'
				);
			}
		);
	} );

	QUnit.asyncTest( 'mw.loader dependency handling', 5, function ( assert ) {
		mw.loader.register( [
			// [module, version, dependencies, group, source]
			['testMissing', '1', [], null, 'testloader'],
			['testUsesMissing', '1', ['testMissing'], null, 'testloader'],
			['testUsesNestedMissing', '1', ['testUsesMissing'], null, 'testloader']
		] );

		function verifyModuleStates() {
			assert.equal( mw.loader.getState( 'testMissing' ), 'missing', 'Module not known to server must have state "missing"' );
			assert.equal( mw.loader.getState( 'testUsesMissing' ), 'error', 'Module with missing dependency must have state "error"' );
			assert.equal( mw.loader.getState( 'testUsesNestedMissing' ), 'error', 'Module with indirect missing dependency must have state "error"' );
		}

		mw.loader.using( ['testUsesNestedMissing'],
			function () {
				assert.ok( false, 'Error handler should be invoked.' );
				assert.ok( true ); // Dummy to reach QUnit expect()

				verifyModuleStates();

				QUnit.start();
			},
			function ( e, badmodules ) {
				assert.ok( true, 'Error handler should be invoked.' );
				// As soon as server spits out state('testMissing', 'missing');
				// it will bubble up and trigger the error callback.
				// Therefor the badmodules array is not testUsesMissing or testUsesNestedMissing.
				assert.deepEqual( badmodules, ['testMissing'], 'Bad modules as expected.' );

				verifyModuleStates();

				QUnit.start();
			}
		);
	} );

	QUnit.asyncTest( 'mw.loader skin-function handling', 5, function ( assert ) {
		mw.loader.register( [
			// [module, version, dependencies, group, source, skip]
			['testSkipped', '1', [], null, 'testloader', 'return true;'],
			['testNotSkipped', '1', [], null, 'testloader', 'return false;'],
			['testUsesSkippable', '1', ['testSkipped', 'testNotSkipped'], null, 'testloader']
		] );

		function verifyModuleStates() {
			assert.equal( mw.loader.getState( 'testSkipped' ), 'ready', 'Module is ready when skipped' );
			assert.equal( mw.loader.getState( 'testNotSkipped' ), 'ready', 'Module is ready when not skipped but loaded' );
			assert.equal( mw.loader.getState( 'testUsesSkippable' ), 'ready', 'Module is ready when skippable dependencies are ready' );
		}

		mw.loader.using( ['testUsesSkippable'],
			function () {
				assert.ok( true, 'Success handler should be invoked.' );
				assert.ok( true ); // Dummy to match error handler and reach QUnit expect()

				verifyModuleStates();

				QUnit.start();
			},
			function ( e, badmodules ) {
				assert.ok( false, 'Error handler should not be invoked.' );
				assert.deepEqual( badmodules, [], 'Bad modules as expected.' );

				verifyModuleStates();

				QUnit.start();
			}
		);
	} );

	QUnit.asyncTest( 'mw.loader( "//protocol-relative" ) (bug 30825)', 2, function ( assert ) {
		// This bug was actually already fixed in 1.18 and later when discovered in 1.17.
		// Test is for regressions!

		// Forge an URL to the test callback script
		var target = QUnit.fixurl(
			mw.config.get( 'wgServer' ) + mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/qunitOkCall.js'
		);

		// Confirm that mw.loader.load() works with protocol-relative URLs
		target = target.replace( /https?:/, '' );

		assert.equal( target.slice( 0, 2 ), '//',
			'URL must be relative to test relative URLs!'
		);

		// Async!
		// The target calls QUnit.start
		mw.loader.load( target );
	} );

	QUnit.test( 'mw.html', 13, function ( assert ) {
		assert.throws( function () {
			mw.html.escape();
		}, TypeError, 'html.escape throws a TypeError if argument given is not a string' );

		assert.equal( mw.html.escape( '<mw awesome="awesome" value=\'test\' />' ),
			'&lt;mw awesome=&quot;awesome&quot; value=&#039;test&#039; /&gt;', 'escape() escapes special characters to html entities' );

		assert.equal( mw.html.element(),
			'<undefined/>', 'element() always returns a valid html string (even without arguments)' );

		assert.equal( mw.html.element( 'div' ), '<div/>', 'element() Plain DIV (simple)' );

		assert.equal( mw.html.element( 'div', {}, '' ), '<div></div>', 'element() Basic DIV (simple)' );

		assert.equal(
			mw.html.element(
				'div', {
					id: 'foobar'
				}
			),
			'<div id="foobar"/>',
			'html.element DIV (attribs)' );

		assert.equal( mw.html.element( 'p', null, 12 ), '<p>12</p>', 'Numbers are valid content and should be casted to a string' );

		assert.equal( mw.html.element( 'p', { title: 12 }, '' ), '<p title="12"></p>', 'Numbers are valid attribute values' );

		// Example from https://www.mediawiki.org/wiki/ResourceLoader/Default_modules#mediaWiki.html
		assert.equal(
			mw.html.element(
				'div',
				{},
				new mw.html.Raw(
					mw.html.element( 'img', { src: '<' } )
				)
			),
			'<div><img src="&lt;"/></div>',
			'Raw inclusion of another element'
		);

		assert.equal(
			mw.html.element(
				'option', {
					selected: true
				}, 'Foo'
			),
			'<option selected="selected">Foo</option>',
			'Attributes may have boolean values. True copies the attribute name to the value.'
		);

		assert.equal(
			mw.html.element(
				'option', {
					value: 'foo',
					selected: false
				}, 'Foo'
			),
			'<option value="foo">Foo</option>',
			'Attributes may have boolean values. False keeps the attribute from output.'
		);

		assert.equal( mw.html.element( 'div',
			null, 'a' ),
			'<div>a</div>',
			'html.element DIV (content)' );

		assert.equal( mw.html.element( 'a',
			{ href: 'http://mediawiki.org/w/index.php?title=RL&action=history' }, 'a' ),
			'<a href="http://mediawiki.org/w/index.php?title=RL&amp;action=history">a</a>',
			'html.element DIV (attribs + content)' );

	} );

	QUnit.test( 'mw.hook', 13, function ( assert ) {
		var hook, add, fire, chars, callback;

		mw.hook( 'test.hook.unfired' ).add( function () {
			assert.ok( false, 'Unfired hook' );
		} );

		mw.hook( 'test.hook.basic' ).add( function () {
			assert.ok( true, 'Basic callback' );
		} );
		mw.hook( 'test.hook.basic' ).fire();

		mw.hook( 'hasOwnProperty' ).add( function () {
			assert.ok( true, 'hook with name of predefined method' );
		} );
		mw.hook( 'hasOwnProperty' ).fire();

		mw.hook( 'test.hook.data' ).add( function ( data1, data2 ) {
			assert.equal( data1, 'example', 'Fire with data (string param)' );
			assert.deepEqual( data2, ['two'], 'Fire with data (array param)' );
		} );
		mw.hook( 'test.hook.data' ).fire( 'example', ['two'] );

		hook = mw.hook( 'test.hook.chainable' );
		assert.strictEqual( hook.add(), hook, 'hook.add is chainable' );
		assert.strictEqual( hook.remove(), hook, 'hook.remove is chainable' );
		assert.strictEqual( hook.fire(), hook, 'hook.fire is chainable' );

		hook = mw.hook( 'test.hook.detach' );
		add = hook.add;
		fire = hook.fire;
		add( function ( x, y ) {
			assert.deepEqual( [x, y], ['x', 'y'], 'Detached (contextless) with data' );
		} );
		fire( 'x', 'y' );

		mw.hook( 'test.hook.fireBefore' ).fire().add( function () {
			assert.ok( true, 'Invoke handler right away if it was fired before' );
		} );

		mw.hook( 'test.hook.fireTwiceBefore' ).fire().fire().add( function () {
			assert.ok( true, 'Invoke handler right away if it was fired before (only last one)' );
		} );

		chars = [];

		mw.hook( 'test.hook.many' )
			.add( function ( chr ) {
				chars.push( chr );
			} )
			.fire( 'x' ).fire( 'y' ).fire( 'z' )
			.add( function ( chr ) {
				assert.equal( chr, 'z', 'Adding callback later invokes right away with last data' );
			} );

		assert.deepEqual( chars, ['x', 'y', 'z'], 'Multiple callbacks with multiple fires' );

		chars = [];
		callback = function ( chr ) {
			chars.push( chr );
		};

		mw.hook( 'test.hook.variadic' )
			.add(
				callback,
				callback,
				function ( chr ) {
					chars.push( chr );
				},
				callback
			)
			.fire( 'x' )
			.remove(
				function () {
					'not-added';
				},
				callback
			)
			.fire( 'y' )
			.remove( callback )
			.fire( 'z' );

		assert.deepEqual(
			chars,
			['x', 'x', 'x', 'x', 'y', 'z'],
			'"add" and "remove" support variadic arguments. ' +
				'"add" does not filter unique. ' +
				'"remove" removes all equal by reference. ' +
				'"remove" is silent if the function is not found'
		);
	} );

}( mediaWiki, jQuery ) );
