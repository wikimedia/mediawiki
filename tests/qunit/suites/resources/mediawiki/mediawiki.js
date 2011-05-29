module( 'mediawiki.js' );

test( '-- Initial check', function(){

	ok( window.jQuery, 'jQuery defined' );
	ok( window.$j, '$j defined' );
	equal( window.$j, window.jQuery, '$j alias to jQuery' );

	ok( window.mediaWiki, 'mediaWiki defined' );
	ok( window.mw, 'mw defined' );
	equal( window.mw, window.mediaWiki, 'mw alias to mediaWiki' );

});

test( 'mw.Map', function(){
	expect(20);

	ok( mw.Map, 'mw.Map defined' );
	ok( mw.Map.prototype.get, 'get prototype defined' );
	ok( mw.Map.prototype.set, 'set prototype defined' );
	ok( mw.Map.prototype.exists, 'exists prototype defined' );

	var conf = new mw.Map();

	var funky = function(){};
	var arry = [];
	var nummy = 7;

	deepEqual( conf.get( 'inexistantKey' ), null, 'Map.get() returns null if selection was a string and the key was not found' );
	deepEqual( conf.set( 'myKey', 'myValue' ), true, 'Map.set() returns boolean true if a value was set for a valid key string' );
	deepEqual( conf.set( funky, 'Funky' ), false, 'Map.set() returns boolean false if key was invalid (Function)' );
	deepEqual( conf.set( arry, 'Arry' ), false, 'Map.set() returns boolean false if key was invalid (Array)' );
	deepEqual( conf.set( nummy, 'Nummy' ), false, 'Map.set() returns boolean false if key was invalid (Number)' );
	equal( conf.get( 'myKey' ), 'myValue', 'Map.get() returns a single value value correctly' );

	var someValues = {
		'foo': 'bar',
		'lorem': 'ipsum',
		'MediaWiki': true
	};
	deepEqual( conf.set( someValues ), true, 'Map.set() returns boolean true if multiple values were set by passing an object' );
	deepEqual( conf.get( ['foo', 'lorem'] ), {
		'foo': 'bar',
		'lorem': 'ipsum'
	}, 'Map.get() returns multiple values correctly as an object' );

	deepEqual( conf.get( ['foo', 'notExist'] ), {
		'foo': 'bar',
		'notExist': null
	}, 'Map.get() return includes keys that were not found as null values' );

	deepEqual( conf.exists( 'foo' ), true, 'Map.exists() returns boolean true if a key exists' );
	deepEqual( conf.exists( 'notExist' ), false, 'Map.exists() returns boolean false if a key does not exists' );
	deepEqual( conf.get() === conf.values, true, 'Map.get() returns the entire values object by reference (if called without arguments)' );

	conf.set( 'globalMapChecker', 'Hi' );

	deepEqual( 'globalMapChecker' in window, false, 'new mw.Map() did not store its values in the global window object by default' );
	ok( !window.globalMapChecker, 'new mw.Map() did not store its values in the global window object by default' );

	var globalConf = new mw.Map( true );
	globalConf.set( 'anotherGlobalMapChecker', 'Hello' );

	deepEqual( 'anotherGlobalMapChecker' in window, true, 'new mw.Map( true ) did store its values in the global window object' );
	ok( window.anotherGlobalMapChecker, 'new mw.Map( true ) did store its values in the global window object' );

	// Whitelist this global variable for QUnit 'noglobal' mode
	if ( QUnit.config.noglobals ) {
		QUnit.config.pollution.push( 'anotherGlobalMapChecker' );
	}
});

test( 'mw.config', function(){
	expect(1);

	deepEqual( mw.config instanceof mw.Map, true, 'mw.config instance of mw.Map' );
});

test( 'mw.messages / mw.message / mw.msg', function(){
	expect(18);

	ok( mw.message, 'mw.message defined' );
	ok( mw.msg, 'mw.msg defined' );
	ok( mw.messages, 'messages defined' );
	ok( mw.messages instanceof mw.Map, 'mw.messages instance of mw.Map' );
	ok( mw.messages.set( 'hello', 'Hello <b>awesome</b> world' ), 'mw.messages.set: Register' );

	var hello = mw.message( 'hello' );

	equal( hello.format, 'parse', 'Message property "format" defaults to "parse"' );
	deepEqual( hello.map === mw.messages, true, 'Message property "map" defaults to the global instance in mw.messages' );
	equal( hello.key, 'hello', 'Message property "key" (currect key)' );
	deepEqual( hello.parameters, [], 'Message property "parameters" defaults to an empty array' );

	// Todo
	ok( hello.params, 'Message prototype "params"' );

	hello.format = 'plain';
	equal( hello.toString(), 'Hello <b>awesome</b> world', 'Message.toString() returns the message as a string with the current "format"' );

	equal( hello.escaped(), 'Hello &lt;b&gt;awesome&lt;/b&gt; world', 'Message.escaped() returns the escaped message' );
	equal( hello.format, 'escaped', 'Message.escaped() correctly updated the "format" property' );

	hello.parse();
	equal( hello.format, 'parse', 'Message.parse() correctly updated the "format" property' );

	hello.plain();
	equal( hello.format, 'plain', 'Message.plain() correctly updated the "format" property' );

	deepEqual( hello.exists(), true, 'Message.exists() returns true for existing messages' );

	var goodbye = mw.message( 'goodbye' );
	deepEqual( goodbye.exists(), false, 'Message.exists() returns false for inexisting messages' );

	equal( goodbye.toString(), '<goodbye>', 'Message.toString() returns <key> if key does not exist' );

});

test( 'mw.msg', function(){
	expect(2);

	equal( mw.msg( 'hello' ), 'Hello <b>awesome</b> world', 'Gets message with default options (existing message)' );
	equal( mw.msg( 'goodbye' ), '<goodbye>', 'Gets message with default options (inexisting message)' );
});

test( 'mw.loader', function(){
	expect(5);

	// Regular expression to extract the path for the QUnit tests
	// Takes into account that tests could be run from a file:// URL
	// by excluding the 'index.html' part from the URL
	var rePath = /(?:[^#\?](?!index.html))*\/?/;

	// Four assertions to test the above regular expression:
	equal(
		rePath.exec( 'http://path/to/tests/?foobar' )[0],
		"http://path/to/tests/",
		"Extracting path from http URL with query"
		);
	equal(
		rePath.exec( 'http://path/to/tests/#frag' )[0],
		"http://path/to/tests/",
		"Extracting path from http URL with fragment"
		);
	equal(
		rePath.exec( 'file://path/to/tests/index.html?foobar' )[0],
		"file://path/to/tests/",
		"Extracting path from local URL (file://) with query"
		);
	equal(
		rePath.exec( 'file://path/to/tests/index.html#frag' )[0],
		"file://path/to/tests/",
		"Extracting path from local URL (file://) with fragment"
		);

	// Asynchronous ahead
	stop();

	// Extract path
	var tests_path = rePath.exec( location.href );

	mw.loader.implement( 'is.awesome', [tests_path + 'sample/awesome.js'], {}, {} );

	mw.loader.using( 'is.awesome', function(){

		// awesome.js declares this function
		mw.loader.testCallback();

	}, function(){
		start();
		deepEqual( true, false, 'Implementing a module, error callback fired!' );
	});

});

test( 'mw.html', function(){

	equal( mw.html.escape( '<mw awesome="awesome" value=\'test\' />' ),
		'&lt;mw awesome=&quot;awesome&quot; value=&#039;test&#039; /&gt;', 'html.escape()' );

	equal( mw.html.element( 'div' ), '<div/>', 'mw.html.element() DIV (simple)' );

	equal( mw.html.element( 'div',
			{ id: 'foobar' } ),
		'<div id="foobar"/>',
		'mw.html.element() DIV (attribs)' );

	equal( mw.html.element( 'div',
			null, 'a' ),
		'<div>a</div>',
		'mw.html.element() DIV (content)' );

	equal( mw.html.element( 'a',
			{ href: 'http://mediawiki.org/w/index.php?title=RL&action=history' }, 'a' ),
		'<a href="http://mediawiki.org/w/index.php?title=RL&amp;action=history">a</a>',
		'mw.html.element() DIV (attribs + content)' );

});
