module( 'mediawiki' );

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
	expect(17);

	ok( mw.Map, 'mw.Map defined' );

	var	conf = new mw.Map(),
		// Dummy variables
		funky = function() {},
		arry = [],
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
	var someValues = {
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

	var globalConf = new mw.Map( true );
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
	expect(17);

	ok( mw.messages, 'messages defined' );
	ok( mw.messages instanceof mw.Map, 'mw.messages instance of mw.Map' );
	ok( mw.messages.set( 'hello', 'Hello <b>awesome</b> world' ), 'mw.messages.set: Register' );

	var hello = mw.message( 'hello' );

	equal( hello.format, 'plain', 'Message property "format" defaults to "parse"' );
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

	var goodbye = mw.message( 'goodbye' );
	strictEqual( goodbye.exists(), false, 'Message.exists returns false for inexisting messages' );

	equal( goodbye.plain(), '<goodbye>', 'Message.toString returns plain <key> if format is "plain" and key does not exist' );
	// bug 30684
	equal( goodbye.escaped(), '&lt;goodbye&gt;', 'Message.toString returns properly escaped &lt;key&gt; if format is "escaped" and key does not exist' );
});

test( 'mw.msg', function() {
	expect(3);

	ok( mw.messages.set( 'hello', 'Hello <b>awesome</b> world' ), 'mw.messages.set: Register' );

	equal( mw.msg( 'hello' ), 'Hello <b>awesome</b> world', 'Gets message with default options (existing message)' );
	equal( mw.msg( 'goodbye' ), '<goodbye>', 'Gets message with default options (inexisting message)' );
});

test( 'mw.loader', function() {
	expect(1);

	// Asynchronous ahead
	stop(5000);

	mw.loader.implement( 'is.awesome', [QUnit.fixurl( mw.config.get( 'wgScriptPath' ) + '/tests/qunit/data/defineTestCallback.js' )], {}, {} );

	mw.loader.using( 'is.awesome', function() {

		// /sample/awesome.js declares the "mw.loader.testCallback" function
		// which contains a call to start() and ok()
		mw.loader.testCallback();
		mw.loader.testCallback = undefined;

	}, function() {
		start();
		ok( false, 'Error callback fired while implementing "is.awesome" module' );
	});

});

test( 'mw.loader.bug29107' , function() {
	expect(2);

	// Message doesn't exist already
	ok( !mw.messages.exists( 'bug29107' ) );

	// Async! Include a timeout, as failure in this test leads to neither the
	// success nor failure callbacks getting called.
	stop(5000);

	mw.loader.implement( 'bug29107.messages-only', [], {}, {'bug29107': 'loaded'} );
	mw.loader.using( 'bug29107.messages-only', function() {
		start();
		ok( mw.messages.exists( 'bug29107' ), 'Bug 29107: messages-only module should implement ok' );
	}, function() {
		start();
		ok( false, 'Error callback fired while implementing "bug29107.messages-only" module' );
	});
});

test( 'mw.html', function() {
	expect(7);

	raises( function(){
		mw.html.escape();
	}, TypeError, 'html.escape throws a TypeError if argument given is not a string' );

	equal( mw.html.escape( '<mw awesome="awesome" value=\'test\' />' ),
		'&lt;mw awesome=&quot;awesome&quot; value=&#039;test&#039; /&gt;', 'html.escape escapes html snippet' );

	equal( mw.html.element(),
		'<undefined/>', 'html.element Always return a valid html string (even without arguments)' );

	equal( mw.html.element( 'div' ), '<div/>', 'html.element DIV (simple)' );

	equal( mw.html.element( 'div',
			{ id: 'foobar' } ),
		'<div id="foobar"/>',
		'html.element DIV (attribs)' );

	equal( mw.html.element( 'div',
			null, 'a' ),
		'<div>a</div>',
		'html.element DIV (content)' );

	equal( mw.html.element( 'a',
			{ href: 'http://mediawiki.org/w/index.php?title=RL&action=history' }, 'a' ),
		'<a href="http://mediawiki.org/w/index.php?title=RL&amp;action=history">a</a>',
		'html.element DIV (attribs + content)' );

});
