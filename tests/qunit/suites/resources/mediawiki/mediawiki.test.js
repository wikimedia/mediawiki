module( 'mediawiki', QUnit.newMwEnvironment() );

test( '-- Initial check', function() {
	expect(8);

	ok( window.jQuery, 'jQuery defined' );
	ok( window.$, '$j defined' );
	ok( window.$j, '$j defined' );
	strictEqual( window.$, window.jQuery, '$ alias to jQuery' );
	strictEqual( window.$j, window.jQuery, '$j alias to jQuery' );

	ok( window.mediaWiki, 'mediaWiki defined' );
	ok( window.mw, 'mw defined' );
	strictEqual( window.mw, window.mediaWiki, 'mw alias to mediaWiki' );
});

test( 'mw.Map', function() {
	var arry, conf, funky, globalConf, nummy, someValues;
	expect(17);

	ok( mw.Map, 'mw.Map defined' );

	conf = new mw.Map();
	// Dummy variables
	funky = function () {};
	arry = [];
	nummy = 7;

	// Tests for input validation
	strictEqual( conf.get( 'inexistantKey' ), null, 'Map.get returns null if selection was a string and the key was not found' );
	strictEqual( conf.set( 'myKey', 'myValue' ), true, 'Map.set returns boolean true if a value was set for a valid key string' );
	strictEqual( conf.set( funky, 'Funky' ), false, 'Map.set returns boolean false if key was invalid (Function)' );
	strictEqual( conf.set( arry, 'Arry' ), false, 'Map.set returns boolean false if key was invalid (Array)' );
	strictEqual( conf.set( nummy, 'Nummy' ), false, 'Map.set returns boolean false if key was invalid (Number)' );
	equal( conf.get( 'myKey' ), 'myValue', 'Map.get returns a single value value correctly' );
	strictEqual( conf.get( nummy ), null, 'Map.get ruturns null if selection was invalid (Number)' );
	strictEqual( conf.get( funky ), null, 'Map.get ruturns null if selection was invalid (Function)' );

	// Multiple values at once
	someValues = {
		'foo': 'bar',
		'lorem': 'ipsum',
		'MediaWiki': true
	};
	strictEqual( conf.set( someValues ), true, 'Map.set returns boolean true if multiple values were set by passing an object' );
	deepEqual( conf.get( ['foo', 'lorem'] ), {
		'foo': 'bar',
		'lorem': 'ipsum'
	}, 'Map.get returns multiple values correctly as an object' );

	deepEqual( conf.get( ['foo', 'notExist'] ), {
		'foo': 'bar',
		'notExist': null
	}, 'Map.get return includes keys that were not found as null values' );

	strictEqual( conf.exists( 'foo' ), true, 'Map.exists returns boolean true if a key exists' );
	strictEqual( conf.exists( 'notExist' ), false, 'Map.exists returns boolean false if a key does not exists' );

	// Interacting with globals and accessing the values object
	strictEqual( conf.get(), conf.values, 'Map.get returns the entire values object by reference (if called without arguments)' );

	conf.set( 'globalMapChecker', 'Hi' );

	ok( false === 'globalMapChecker' in window, 'new mw.Map did not store its values in the global window object by default' );

	globalConf = new mw.Map( true );
	globalConf.set( 'anotherGlobalMapChecker', 'Hello' );

	ok( 'anotherGlobalMapChecker' in window, 'new mw.Map( true ) did store its values in the global window object' );

	// Whitelist this global variable for QUnit's 'noglobal' mode
	if ( QUnit.config.noglobals ) {
		QUnit.config.pollution.push( 'anotherGlobalMapChecker' );
	}
});

test( 'mw.config', function() {
	expect(1);

	ok( mw.config instanceof mw.Map, 'mw.config instance of mw.Map' );
});

test( 'mw.message & mw.messages', function() {
	var goodbye, hello, pluralMessage;
	expect(20);

	ok( mw.messages, 'messages defined' );
	ok( mw.messages instanceof mw.Map, 'mw.messages instance of mw.Map' );
	ok( mw.messages.set( 'hello', 'Hello <b>awesome</b> world' ), 'mw.messages.set: Register' );

	hello = mw.message( 'hello' );

	equal( hello.format, 'plain', 'Message property "format" defaults to "plain"' );
	strictEqual( hello.map, mw.messages, 'Message property "map" defaults to the global instance in mw.messages' );
	equal( hello.key, 'hello', 'Message property "key" (currect key)' );
	deepEqual( hello.parameters, [], 'Message property "parameters" defaults to an empty array' );

	// Todo
	ok( hello.params, 'Message prototype "params"' );

	hello.format = 'plain';
	equal( hello.toString(), 'Hello <b>awesome</b> world', 'Message.toString returns the message as a string with the current "format"' );

	equal( hello.escaped(), 'Hello &lt;b&gt;awesome&lt;/b&gt; world', 'Message.escaped returns the escaped message' );
	equal( hello.format, 'escaped', 'Message.escaped correctly updated the "format" property' );

	hello.parse();
	equal( hello.format, 'parse', 'Message.parse correctly updated the "format" property' );

	hello.plain();
	equal( hello.format, 'plain', 'Message.plain correctly updated the "format" property' );

	strictEqual( hello.exists(), true, 'Message.exists returns true for existing messages' );

	goodbye = mw.message( 'goodbye' );
	strictEqual( goodbye.exists(), false, 'Message.exists returns false for nonexistent messages' );

	equal( goodbye.plain(), '<goodbye>', 'Message.toString returns plain <key> if format is "plain" and key does not exist' );
	// bug 30684
	equal( goodbye.escaped(), '&lt;goodbye&gt;', 'Message.toString returns properly escaped &lt;key&gt; if format is "escaped" and key does not exist' );

	ok( mw.messages.set( 'pluraltestmsg', 'There {{PLURAL:$1|is|are}} $1 {{PLURAL:$1|result|results}}' ), 'mw.messages.set: Register' );
	pluralMessage = mw.message( 'pluraltestmsg' , 6 );
	equal( pluralMessage.plain(), 'There are 6 results', 'plural get resolved when format is plain' );
	equal( pluralMessage.parse(), 'There are 6 results', 'plural get resolved when format is parse' );

});

test( 'mw.msg', function() {
	expect(11);

	ok( mw.messages.set( 'hello', 'Hello <b>awesome</b> world' ), 'mw.messages.set: Register' );
	equal( mw.msg( 'hello' ), 'Hello <b>awesome</b> world', 'Gets message with default options (existing message)' );
	equal( mw.msg( 'goodbye' ), '<goodbye>', 'Gets message with default options (nonexistent message)' );

	ok( mw.messages.set( 'plural-item' , 'Found $1 {{PLURAL:$1|item|items}}' ) );
	equal( mw.msg( 'plural-item', 5 ), 'Found 5 items', 'Apply plural for count 5' );
	equal( mw.msg( 'plural-item', 0 ), 'Found 0 items', 'Apply plural for count 0' );
	equal( mw.msg( 'plural-item', 1 ), 'Found 1 item', 'Apply plural for count 1' );

	ok( mw.messages.set('gender-plural-msg' , '{{GENDER:$1|he|she|they}} {{PLURAL:$2|is|are}} awesome' ) );
	equal( mw.msg( 'gender-plural-msg', 'male', 1 ), 'he is awesome', 'Gender test for male, plural count 1' );
	equal( mw.msg( 'gender-plural-msg', 'female', '1' ), 'she is awesome', 'Gender test for female, plural count 1' );
	equal( mw.msg( 'gender-plural-msg', 'unknown', 10 ), 'they are awesome', 'Gender test for neutral, plural count 10' );

});

test( 'mw.loader', function() {
	var isAwesomeDone;
	expect(2);

	// Async ahead
	stop();

	mw.loader.testCallback = function () {
		start();
		strictEqual( isAwesomeDone, undefined, 'Implementing module is.awesome: isAwesomeDone should still be undefined');
		isAwesomeDone = true;
	};

	mw.loader.implement( 'test.callback', [QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/callMwLoaderTestCallback.js' )], {}, {} );

	mw.loader.using( 'test.callback', function () {

		// /sample/awesome.js declares the "mw.loader.testCallback" function
		// which contains a call to start() and ok()
		strictEqual( isAwesomeDone, true, "test.callback module should've caused isAwesomeDone to be true" );
		delete mw.loader.testCallback;

	}, function () {
		start();
		ok( false, 'Error callback fired while loader.using "test.callback" module' );
	});
});

test( 'mw.loader.implement', function () {
	var isJsExecuted, $element, styleTestUrl;
	expect(5);

	// Async ahead
	stop();

	styleTestUrl = QUnit.fixurl(
		mw.config.get( 'wgScriptPath' )
		+ '/tests/qunit/data/styleTest.css.php?'
		+ $.param({
			selector: '.mw-test-loaderimplement',
			prop: 'float',
			val: 'right'
		})
	);

	mw.loader.implement(
		'test.implement',
		function () {
			var styleTestTimeout, styleTestStart, styleTestSince;

			strictEqual( isJsExecuted, undefined, 'javascript not executed multiple times' );
			isJsExecuted = true;

			equal( mw.loader.getState( 'test.implement' ), 'loaded', 'module state is "loaded" while implement() is executing javascript' );

			$element = $( '<div class="mw-test-loaderimplement">Foo bar</div>' ).appendTo( '#qunit-fixture' );

			equal( mw.msg( 'test-foobar' ), 'Hello Foobar, $1!', 'Messages are loaded before javascript execution' );

			// The @import test. This is, in a way, also an open bug for ResourceLoader
			// ("execute js after styles are loaded"), but browsers don't offer a way to
			// get a callback from when a stylesheet is loaded (that is, including any
			// @import rules inside).
			// To work around this, we'll have a little time loop to check if the styles
			// apply.
			// Note: This test originally used new Image() and onerror to get a callback
			// when the url is loaded, but that is fragile since it doesn't monitor the
			// same request as the css @import, and Safari 4 has issues with
			// onerror/onload not being fired at all in weird cases like this.

			styleTestTimeout = QUnit.config.testTimeout || 5000; // milliseconds

			function isCssImportApplied() {
				return $element.css( 'float' ) === 'right';
			}

			function styleTestLoop() {
				styleTestSince = new Date().getTime() - styleTestStart;
				// If it is passing or if we timed out, run the real test and stop the loop
				if ( isCssImportApplied() || styleTestSince > styleTestTimeout ) {
					equal( $element.css( 'float' ), 'right',
						'CSS stylesheet via @import was applied (after ' + styleTestSince + 'ms) (bug 34669). ("float: right")'
					);

					equal( $element.css( 'text-align' ),'center',
						'CSS styles after the @import are working ("text-align: center")'
					);

					// Async done
					start();

					return;
				}
				// Otherwise, keep polling
				setTimeout( styleTestLoop, 100 );
			}

			// Start the loop
			styleTestStart = new Date().getTime();
			styleTestLoop();
		},
		{
			"all": "@import url('"
				+ styleTestUrl
				+ "');\n"
				+ '.mw-test-loaderimplement { text-align: center; }'
		},
		{
			"test-foobar": "Hello Foobar, $1!"
		}
	);

	mw.loader.load( 'test.implement' );

});

test( 'mw.loader erroneous indirect dependency', function() {
	expect( 3 );
	mw.loader.register( [
		['test.module1', '0'],
		['test.module2', '0', ['test.module1']],
		['test.module3', '0', ['test.module2']]
	] );
	mw.loader.implement( 'test.module1', function() { throw new Error( 'expected' ); }, {}, {} );
	strictEqual( mw.loader.getState( 'test.module1' ), 'error', 'Expected "error" state for test.module1' );
	strictEqual( mw.loader.getState( 'test.module2' ), 'error', 'Expected "error" state for test.module2' );
	strictEqual( mw.loader.getState( 'test.module3' ), 'error', 'Expected "error" state for test.module3' );
} );

test( 'mw.loader out-of-order implementation', function() {
	expect( 9 );
	mw.loader.register( [
		['test.module4', '0'],
		['test.module5', '0', ['test.module4']],
		['test.module6', '0', ['test.module5']]
	] );
	mw.loader.implement( 'test.module4', function() {}, {}, {} );
	strictEqual( mw.loader.getState( 'test.module4' ), 'ready', 'Expected "ready" state for test.module4' );
	strictEqual( mw.loader.getState( 'test.module5' ), 'registered', 'Expected "registered" state for test.module5' );
	strictEqual( mw.loader.getState( 'test.module6' ), 'registered', 'Expected "registered" state for test.module6' );
	mw.loader.implement( 'test.module6', function() {}, {}, {} );
	strictEqual( mw.loader.getState( 'test.module4' ), 'ready', 'Expected "ready" state for test.module4' );
	strictEqual( mw.loader.getState( 'test.module5' ), 'registered', 'Expected "registered" state for test.module5' );
	strictEqual( mw.loader.getState( 'test.module6' ), 'loaded', 'Expected "loaded" state for test.module6' );
	mw.loader.implement( 'test.module5', function() {}, {}, {} );
	strictEqual( mw.loader.getState( 'test.module4' ), 'ready', 'Expected "ready" state for test.module4' );
	strictEqual( mw.loader.getState( 'test.module5' ), 'ready', 'Expected "ready" state for test.module5' );
	strictEqual( mw.loader.getState( 'test.module6' ), 'ready', 'Expected "ready" state for test.module6' );
} );

test( 'mw.loader missing dependency', function() {
	expect( 13 );
	mw.loader.register( [
		['test.module7', '0'],
		['test.module8', '0', ['test.module7']],
		['test.module9', '0', ['test.module8']]
	] );
	mw.loader.implement( 'test.module8', function() {}, {}, {} );
	strictEqual( mw.loader.getState( 'test.module7' ), 'registered', 'Expected "registered" state for test.module7' );
	strictEqual( mw.loader.getState( 'test.module8' ), 'loaded', 'Expected "loaded" state for test.module8' );
	strictEqual( mw.loader.getState( 'test.module9' ), 'registered', 'Expected "registered" state for test.module9' );
	mw.loader.state( 'test.module7', 'missing' );
	strictEqual( mw.loader.getState( 'test.module7' ), 'missing', 'Expected "missing" state for test.module7' );
	strictEqual( mw.loader.getState( 'test.module8' ), 'error', 'Expected "error" state for test.module8' );
	strictEqual( mw.loader.getState( 'test.module9' ), 'error', 'Expected "error" state for test.module9' );
	mw.loader.implement( 'test.module9', function() {}, {}, {} );
	strictEqual( mw.loader.getState( 'test.module7' ), 'missing', 'Expected "missing" state for test.module7' );
	strictEqual( mw.loader.getState( 'test.module8' ), 'error', 'Expected "error" state for test.module8' );
	strictEqual( mw.loader.getState( 'test.module9' ), 'error', 'Expected "error" state for test.module9' );
	mw.loader.using(
		['test.module7'],
		function() {
			ok( false, "Success fired despite missing dependency" );
			ok( true , "QUnit expected() count dummy" );
		},
		function( e, dependencies ) {
			strictEqual( $.isArray( dependencies ), true, 'Expected array of dependencies' );
			deepEqual( dependencies, ['test.module7'], 'Error callback called with module test.module7' );
		}
	);
	mw.loader.using(
		['test.module9'],
		function() {
			ok( false, "Success fired despite missing dependency" );
			ok( true , "QUnit expected() count dummy" );
		},
		function( e, dependencies ) {
			strictEqual( $.isArray( dependencies ), true, 'Expected array of dependencies' );
			dependencies.sort();
			deepEqual(
				dependencies,
				['test.module7', 'test.module8', 'test.module9'],
				'Error callback called with all three modules as dependencies'
			);
		}
	);
} );

test( 'mw.loader dependency handling', function () {
	expect( 5 );

	mw.loader.addSource(
		'testloader',
		{
			loadScript: QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/load.mock.php' )
		}
	);

	mw.loader.register( [
		// [module, version, dependencies, group, source]
		['testMissing', '1', [], null, 'testloader'],
		['testUsesMissing', '1', ['testMissing'], null, 'testloader'],
		['testUsesNestedMissing', '1', ['testUsesMissing'], null, 'testloader']
	] );

	function verifyModuleStates() {
		equal( mw.loader.getState( 'testMissing' ), 'missing', 'Module not known to server must have state "missing"' );
		equal( mw.loader.getState( 'testUsesMissing' ), 'error', 'Module with missing dependency must have state "error"' );
		equal( mw.loader.getState( 'testUsesNestedMissing' ), 'error', 'Module with indirect missing dependency must have state "error"' );
	}

	stop();

	mw.loader.using( ['testUsesNestedMissing'],
		function () {
			ok( false, 'Error handler should be invoked.' );
			ok( true ); // Dummy to reach QUnit expect()

			verifyModuleStates();

			start();
		},
		function ( e, badmodules ) {
			ok( true, 'Error handler should be invoked.' );
			// As soon as server spits out state('testMissing', 'missing');
			// it will bubble up and trigger the error callback.
			// Therefor the badmodules array is not testUsesMissing or testUsesNestedMissing.
			deepEqual( badmodules, ['testMissing'], 'Bad modules as expected.' );

			verifyModuleStates();

			start();
		}
	);
} );

test( 'mw.loader bug29107' , function () {
	expect(2);

	// Message doesn't exist already
	ok( !mw.messages.exists( 'bug29107' ) );

	// Async! Failure in this test may lead to neither the success nor error callbacks getting called.
	// Due to QUnit's timeout feauture we won't hang here forever if this happends.
	stop();

	mw.loader.implement( 'bug29107.messages-only', [], {}, {'bug29107': 'loaded'} );
	mw.loader.using( 'bug29107.messages-only', function() {
		start();
		ok( mw.messages.exists( 'bug29107' ), 'Bug 29107: messages-only module should implement ok' );
	}, function() {
		start();
		ok( false, 'Error callback fired while implementing "bug29107.messages-only" module' );
	});
});

test( 'mw.loader.bug30825', function() {
	// This bug was actually already fixed in 1.18 and later when discovered in 1.17.
	// Test is for regressions!

	expect(2);

	// Forge an URL to the test callback script
	var target = QUnit.fixurl(
		mw.config.get( 'wgServer' ) + mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/qunitOkCall.js'
	);

	// Confirm that mw.loader.load() works with protocol-relative URLs
	target = target.replace( /https?:/, '' );

	equal( target.substr( 0, 2 ), '//',
		'URL must be relative to test relative URLs!'
	);

	// Async!
	stop();
	mw.loader.load( target );
});

test( 'mw.html', function () {
	expect(13);

	raises( function () {
		mw.html.escape();
	}, TypeError, 'html.escape throws a TypeError if argument given is not a string' );

	equal( mw.html.escape( '<mw awesome="awesome" value=\'test\' />' ),
		'&lt;mw awesome=&quot;awesome&quot; value=&#039;test&#039; /&gt;', 'escape() escapes special characters to html entities' );

	equal( mw.html.element(),
		'<undefined/>', 'element() always returns a valid html string (even without arguments)' );

	equal( mw.html.element( 'div' ), '<div/>', 'element() Plain DIV (simple)' );

	equal( mw.html.element( 'div', {}, '' ), '<div></div>', 'element() Basic DIV (simple)' );

	equal(
		mw.html.element(
			'div', {
				id: 'foobar'
			}
		),
		'<div id="foobar"/>',
		'html.element DIV (attribs)' );

	equal( mw.html.element( 'p', null, 12 ), '<p>12</p>', 'Numbers are valid content and should be casted to a string' );

	equal( mw.html.element( 'p', { title: 12 }, '' ), '<p title="12"></p>', 'Numbers are valid attribute values' );

	// Example from https://www.mediawiki.org/wiki/ResourceLoader/Default_modules#mediaWiki.html
	equal(
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

	equal(
		mw.html.element(
			'option', {
				selected: true
			}, 'Foo'
		),
		'<option selected="selected">Foo</option>',
		'Attributes may have boolean values. True copies the attribute name to the value.'
	);

	equal(
		mw.html.element(
			'option', {
				value: 'foo',
				selected: false
			}, 'Foo'
		),
		'<option value="foo">Foo</option>',
		'Attributes may have boolean values. False keeps the attribute from output.'
	);

	equal( mw.html.element( 'div',
			null, 'a' ),
		'<div>a</div>',
		'html.element DIV (content)' );

	equal( mw.html.element( 'a',
			{ href: 'http://mediawiki.org/w/index.php?title=RL&action=history' }, 'a' ),
		'<a href="http://mediawiki.org/w/index.php?title=RL&amp;action=history">a</a>',
		'html.element DIV (attribs + content)' );

});
