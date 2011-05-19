module( 'mediawiki.js' );

test( '-- Initial check', function(){

	ok( window.jQuery, 'jQuery defined' );
	ok( window.$j, '$j defined' );
	equal( window.$j, window.jQuery, '$j alias to jQuery' );

	ok( window.mediaWiki, 'mediaWiki defined' );
	ok( window.mw, 'mw defined' );
	equal( window.mw, window.mediaWiki, 'mw alias to mediaWiki' );

});

test( 'mw.Map / mw.config', function(){

	ok( mw.config instanceof mw.Map, 'mw.config instance of mw.Map' );
	ok( mw.config.get, 'get' );
	ok( mw.config.set, 'set' );
	ok( mw.config.exists, 'exists' );

	ok( !mw.config.exists( 'lipsum' ), 'exists: lipsum (inexistant)' );
	ok( mw.config.set( 'lipsum', 'Lorem ipsum' ), 'set: lipsum' );
	ok( mw.config.exists( 'lipsum' ), 'exists: lipsum (existant)' );

	equal( mw.config.get( 'lipsum' ), 'Lorem ipsum', 'get: lipsum' );
	equal( mw.config.get( ['lipsum'] ).lipsum, 'Lorem ipsum', 'get: lipsum (multiple)' );

});

test( 'mw.message / mw.msg / mw.messages', function(){
	ok( mw.message, 'mw.message defined' );
	ok( mw.msg, 'mw.msg defined' );
	ok( mw.messages, 'messages defined' );
	ok( mw.messages instanceof mw.Map, 'mw.messages instance of mw.Map' );
	ok( mw.messages.set( 'hello', 'Hello <b>awesome</b> world' ), 'mw.messages.set: Register' );

	var hello = mw.message( 'hello' );
	ok( hello, 'hello: Instance of Message' );

	equal( hello.format, 'parse', 'Message property "format" (default value)' );
	equal( hello.key, 'hello', 'Message property "key" (currect key)' );
	deepEqual( hello.parameters, [], 'Message property "parameters" (default value)' );


	ok( hello.params, 'Message prototype "params"');
	ok( hello.toString, 'Message prototype "toString"');
	ok( hello.parse, 'Message prototype "parse"');
	ok( hello.plain, 'Message prototype "plain"');
	ok( hello.escaped, 'Message prototype "escaped"');
	ok( hello.exists, 'Message prototype "exists"');

	equal( hello.toString(), 'Hello <b>awesome</b> world', 'Message.toString() test');
	equal( hello.escaped(), 'Hello &lt;b&gt;awesome&lt;/b&gt; world', 'Message.escaped() test');
	deepEqual( hello.exists(), true, 'Message.exists() test');

	equal( mw.msg( 'random' ), '<random>', 'square brackets around inexistant messages' );
	equal( mw.msg( 'hello' ), 'Hello <b>awesome</b> world', 'get message with default options' );
	
// params, toString, parse, plain, escaped, exists
});

test( 'mw.loader', function(){
	expect(2);
	
	ok( location.href.match(/[^#\?]*/) && location.href.match(/[^#\?]*/)[0], true, 'Extracting file path from location' );

	stop();
	
	mw.loader.implement( 'is.awesome', [location.href.match(/[^#\?]*/)[0] + 'sample/awesome.js'], {}, {} );
	mw.loader.using( 'is.awesome', function(){
		start();
		deepEqual( window.awesome, true, 'Implementing a module, is the callback timed properly ?');

		// Clean up
		delete window.awesome;

	}, function(){
		start();
		deepEqual( 'mw.loader.using error callback fired', true, 'Implementing a module, is the callback timed properly ?');
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
