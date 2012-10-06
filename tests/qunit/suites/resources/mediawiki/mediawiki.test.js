( function ( mw ) {

QUnit.module( 'mediawiki', QUnit.newMwEnvironment() );

QUnit.test( 'Initial check', 8, function ( assert ) {
	assert.ok( window.jQuery, 'jQuery defined' );
	assert.ok( window.$, '$j defined' );
	assert.ok( window.$j, '$j defined' );
	assert.strictEqual( window.$, window.jQuery, '$ alias to jQuery' );
	assert.strictEqual( window.$j, window.jQuery, '$j alias to jQuery' );

	assert.ok( window.mediaWiki, 'mediaWiki defined' );
	assert.ok( window.mw, 'mw defined' );
	assert.strictEqual( window.mw, window.mediaWiki, 'mw alias to mediaWiki' );
});

QUnit.test( 'mw.Map', 17, function ( assert ) {
	var arry, conf, funky, globalConf, nummy, someValues;

	assert.ok( mw.Map, 'mw.Map defined' );

	conf = new mw.Map();
	// Dummy variables
	funky = function () {};
	arry = [];
	nummy = 7;

	// Tests for input validation
	assert.strictEqual( conf.get( 'inexistantKey' ), null, 'Map.get returns null if selection was a string and the key was not found' );
	assert.strictEqual( conf.set( 'myKey', 'myValue' ), true, 'Map.set returns boolean true if a value was set for a valid key string' );
	assert.strictEqual( conf.set( funky, 'Funky' ), false, 'Map.set returns boolean false if key was invalid (Function)' );
	assert.strictEqual( conf.set( arry, 'Arry' ), false, 'Map.set returns boolean false if key was invalid (Array)' );
	assert.strictEqual( conf.set( nummy, 'Nummy' ), false, 'Map.set returns boolean false if key was invalid (Number)' );
	assert.equal( conf.get( 'myKey' ), 'myValue', 'Map.get returns a single value value correctly' );
	assert.strictEqual( conf.get( nummy ), null, 'Map.get ruturns null if selection was invalid (Number)' );
	assert.strictEqual( conf.get( funky ), null, 'Map.get ruturns null if selection was invalid (Function)' );

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

	assert.deepEqual( conf.get( ['foo', 'notExist'] ), {
		'foo': 'bar',
		'notExist': null
	}, 'Map.get return includes keys that were not found as null values' );

	assert.strictEqual( conf.exists( 'foo' ), true, 'Map.exists returns boolean true if a key exists' );
	assert.strictEqual( conf.exists( 'notExist' ), false, 'Map.exists returns boolean false if a key does not exists' );

	// Interacting with globals and accessing the values object
	assert.strictEqual( conf.get(), conf.values, 'Map.get returns the entire values object by reference (if called without arguments)' );

	conf.set( 'globalMapChecker', 'Hi' );

	assert.ok( false === 'globalMapChecker' in window, 'new mw.Map did not store its values in the global window object by default' );

	globalConf = new mw.Map( true );
	globalConf.set( 'anotherGlobalMapChecker', 'Hello' );

	assert.ok( 'anotherGlobalMapChecker' in window, 'new mw.Map( true ) did store its values in the global window object' );

	// Whitelist this global variable for QUnit's 'noglobal' mode
	if ( QUnit.config.noglobals ) {
		QUnit.config.pollution.push( 'anotherGlobalMapChecker' );
	}
});

QUnit.test( 'mw.config', 1, function ( assert ) {
	assert.ok( mw.config instanceof mw.Map, 'mw.config instance of mw.Map' );
});

QUnit.test( 'mw.message & mw.messages', 20, function ( assert ) {
	var goodbye, hello, pluralMessage;

	assert.ok( mw.messages, 'messages defined' );
	assert.ok( mw.messages instanceof mw.Map, 'mw.messages instance of mw.Map' );
	assert.ok( mw.messages.set( 'hello', 'Hello <b>awesome</b> world' ), 'mw.messages.set: Register' );

	hello = mw.message( 'hello' );

	assert.equal( hello.format, 'plain', 'Message property "format" defaults to "plain"' );
	assert.strictEqual( hello.map, mw.messages, 'Message property "map" defaults to the global instance in mw.messages' );
	assert.equal( hello.key, 'hello', 'Message property "key" (currect key)' );
	assert.deepEqual( hello.parameters, [], 'Message property "parameters" defaults to an empty array' );

	// Todo
	assert.ok( hello.params, 'Message prototype "params"' );

	hello.format = 'plain';
	assert.equal( hello.toString(), 'Hello <b>awesome</b> world', 'Message.toString returns the message as a string with the current "format"' );

	assert.equal( hello.escaped(), 'Hello &lt;b&gt;awesome&lt;/b&gt; world', 'Message.escaped returns the escaped message' );
	assert.equal( hello.format, 'escaped', 'Message.escaped correctly updated the "format" property' );

	hello.parse();
	assert.equal( hello.format, 'parse', 'Message.parse correctly updated the "format" property' );

	hello.plain();
	assert.equal( hello.format, 'plain', 'Message.plain correctly updated the "format" property' );

	assert.strictEqual( hello.exists(), true, 'Message.exists returns true for existing messages' );

	goodbye = mw.message( 'goodbye' );
	assert.strictEqual( goodbye.exists(), false, 'Message.exists returns false for nonexistent messages' );

	assert.equal( goodbye.plain(), '<goodbye>', 'Message.toString returns plain <key> if format is "plain" and key does not exist' );
	// bug 30684
	assert.equal( goodbye.escaped(), '&lt;goodbye&gt;', 'Message.toString returns properly escaped &lt;key&gt; if format is "escaped" and key does not exist' );

	assert.ok( mw.messages.set( 'pluraltestmsg', 'There {{PLURAL:$1|is|are}} $1 {{PLURAL:$1|result|results}}' ), 'mw.messages.set: Register' );
	pluralMessage = mw.message( 'pluraltestmsg' , 6 );
	assert.equal( pluralMessage.plain(), 'There are 6 results', 'plural get resolved when format is plain' );
	assert.equal( pluralMessage.parse(), 'There are 6 results', 'plural get resolved when format is parse' );

});

QUnit.test( 'mw.msg', 11, function ( assert ) {
	assert.ok( mw.messages.set( 'hello', 'Hello <b>awesome</b> world' ), 'mw.messages.set: Register' );
	assert.equal( mw.msg( 'hello' ), 'Hello <b>awesome</b> world', 'Gets message with default options (existing message)' );
	assert.equal( mw.msg( 'goodbye' ), '<goodbye>', 'Gets message with default options (nonexistent message)' );

	assert.ok( mw.messages.set( 'plural-item' , 'Found $1 {{PLURAL:$1|item|items}}' ) );
	assert.equal( mw.msg( 'plural-item', 5 ), 'Found 5 items', 'Apply plural for count 5' );
	assert.equal( mw.msg( 'plural-item', 0 ), 'Found 0 items', 'Apply plural for count 0' );
	assert.equal( mw.msg( 'plural-item', 1 ), 'Found 1 item', 'Apply plural for count 1' );

	assert.ok( mw.messages.set('gender-plural-msg' , '{{GENDER:$1|he|she|they}} {{PLURAL:$2|is|are}} awesome' ) );
	assert.equal( mw.msg( 'gender-plural-msg', 'male', 1 ), 'he is awesome', 'Gender test for male, plural count 1' );
	assert.equal( mw.msg( 'gender-plural-msg', 'female', '1' ), 'she is awesome', 'Gender test for female, plural count 1' );
	assert.equal( mw.msg( 'gender-plural-msg', 'unknown', 10 ), 'they are awesome', 'Gender test for neutral, plural count 10' );

});

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
		styleTestTimeout = ( QUnit.config.testTimeout - 200 ) || 5000;

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
		setTimeout( styleTestLoop, 150 );
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
		assert.strictEqual( isAwesomeDone, undefined, 'Implementing module is.awesome: isAwesomeDone should still be undefined');
		isAwesomeDone = true;
	};

	mw.loader.implement( 'test.callback', [QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/callMwLoaderTestCallback.js' )], {}, {} );

	mw.loader.using( 'test.callback', function () {

		// /sample/awesome.js declares the "mw.loader.testCallback" function
		// which contains a call to start() and ok()
		assert.strictEqual( isAwesomeDone, true, "test.callback module should've caused isAwesomeDone to be true" );
		delete mw.loader.testCallback;

	}, function () {
		QUnit.start();
		assert.ok( false, 'Error callback fired while loader.using "test.callback" module' );
	});
});

QUnit.test( 'mw.loader.implement( styles={ "css": [text, ..] } )', 2, function ( assert ) {
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
		},
		{
			'all': '.mw-test-implement-a { float: right; }'
		},
		{}
	);

	mw.loader.load([
		'test.implement.a'
	]);
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
			assertStyleAsync( assert, $element2, 'float', 'left', function () {
				assert.notEqual( $element1.css( 'text-align' ), 'center', 'print style is not applied' );

				QUnit.start();
			} );
			assertStyleAsync( assert, $element3, 'float', 'right', function () {
				assert.notEqual( $element1.css( 'text-align' ), 'center', 'print style is not applied' );

				QUnit.start();
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

	mw.loader.load([
		'test.implement.b'
	]);
} );

// Backwards compatibility
QUnit.test( 'mw.loader.implement( styles={ <media>: text } ) (back-compat)', 2, function ( assert ) {
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
		},
		{
			'all': '.mw-test-implement-c { float: right; }'
		},
		{}
	);

	mw.loader.load([
		'test.implement.c'
	]);
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

	mw.loader.load([
		'test.implement.d'
	]);
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
				assert.equal( $element.css( 'text-align' ),'center',
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

});

QUnit.asyncTest( 'mw.loader.implement( only messages )' , 2, function ( assert ) {
	assert.assertFalse( mw.messages.exists( 'bug_29107' ), 'Verify that the test message doesn\'t exist yet' );

	mw.loader.implement( 'test.implement.msgs', [], {}, { 'bug_29107': 'loaded' } );
	mw.loader.using( 'test.implement.msgs', function() {
		QUnit.start();
		assert.ok( mw.messages.exists( 'bug_29107' ), 'Bug 29107: messages-only module should implement ok' );
	}, function() {
		QUnit.start();
		assert.ok( false, 'Error callback fired while implementing "test.implement.msgs" module' );
	});
});

QUnit.test( 'mw.loader erroneous indirect dependency', 3, function ( assert ) {
	mw.loader.register( [
		['test.module1', '0'],
		['test.module2', '0', ['test.module1']],
		['test.module3', '0', ['test.module2']]
	] );
	mw.loader.implement( 'test.module1', function () { throw new Error( 'expected' ); }, {}, {} );
	assert.strictEqual( mw.loader.getState( 'test.module1' ), 'error', 'Expected "error" state for test.module1' );
	assert.strictEqual( mw.loader.getState( 'test.module2' ), 'error', 'Expected "error" state for test.module2' );
	assert.strictEqual( mw.loader.getState( 'test.module3' ), 'error', 'Expected "error" state for test.module3' );
} );

QUnit.test( 'mw.loader out-of-order implementation', 9, function ( assert ) {
	mw.loader.register( [
		['test.module4', '0'],
		['test.module5', '0', ['test.module4']],
		['test.module6', '0', ['test.module5']]
	] );
	mw.loader.implement( 'test.module4', function () {}, {}, {} );
	assert.strictEqual( mw.loader.getState( 'test.module4' ), 'ready', 'Expected "ready" state for test.module4' );
	assert.strictEqual( mw.loader.getState( 'test.module5' ), 'registered', 'Expected "registered" state for test.module5' );
	assert.strictEqual( mw.loader.getState( 'test.module6' ), 'registered', 'Expected "registered" state for test.module6' );
	mw.loader.implement( 'test.module6', function () {}, {}, {} );
	assert.strictEqual( mw.loader.getState( 'test.module4' ), 'ready', 'Expected "ready" state for test.module4' );
	assert.strictEqual( mw.loader.getState( 'test.module5' ), 'registered', 'Expected "registered" state for test.module5' );
	assert.strictEqual( mw.loader.getState( 'test.module6' ), 'loaded', 'Expected "loaded" state for test.module6' );
	mw.loader.implement( 'test.module5', function() {}, {}, {} );
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
	mw.loader.implement( 'test.module8', function () {}, {}, {} );
	assert.strictEqual( mw.loader.getState( 'test.module7' ), 'registered', 'Expected "registered" state for test.module7' );
	assert.strictEqual( mw.loader.getState( 'test.module8' ), 'loaded', 'Expected "loaded" state for test.module8' );
	assert.strictEqual( mw.loader.getState( 'test.module9' ), 'registered', 'Expected "registered" state for test.module9' );
	mw.loader.state( 'test.module7', 'missing' );
	assert.strictEqual( mw.loader.getState( 'test.module7' ), 'missing', 'Expected "missing" state for test.module7' );
	assert.strictEqual( mw.loader.getState( 'test.module8' ), 'error', 'Expected "error" state for test.module8' );
	assert.strictEqual( mw.loader.getState( 'test.module9' ), 'error', 'Expected "error" state for test.module9' );
	mw.loader.implement( 'test.module9', function () {}, {}, {} );
	assert.strictEqual( mw.loader.getState( 'test.module7' ), 'missing', 'Expected "missing" state for test.module7' );
	assert.strictEqual( mw.loader.getState( 'test.module8' ), 'error', 'Expected "error" state for test.module8' );
	assert.strictEqual( mw.loader.getState( 'test.module9' ), 'error', 'Expected "error" state for test.module9' );
	mw.loader.using(
		['test.module7'],
		function () {
			assert.ok( false, "Success fired despite missing dependency" );
			assert.ok( true , "QUnit expected() count dummy" );
		},
		function ( e, dependencies ) {
			assert.strictEqual( $.isArray( dependencies ), true, 'Expected array of dependencies' );
			assert.deepEqual( dependencies, ['test.module7'], 'Error callback called with module test.module7' );
		}
	);
	mw.loader.using(
		['test.module9'],
		function () {
			assert.ok( false, "Success fired despite missing dependency" );
			assert.ok( true , "QUnit expected() count dummy" );
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

QUnit.asyncTest( 'mw.loader( "//protocol-relative" ) (bug 30825)', 2, function ( assert ) {
	// This bug was actually already fixed in 1.18 and later when discovered in 1.17.
	// Test is for regressions!

	// Forge an URL to the test callback script
	var target = QUnit.fixurl(
		mw.config.get( 'wgServer' ) + mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/qunitOkCall.js'
	);

	// Confirm that mw.loader.load() works with protocol-relative URLs
	target = target.replace( /https?:/, '' );

	assert.equal( target.substr( 0, 2 ), '//',
		'URL must be relative to test relative URLs!'
	);

	// Async!
	// The target calls QUnit.start
	mw.loader.load( target );
});

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

});

}( mediaWiki ) );
