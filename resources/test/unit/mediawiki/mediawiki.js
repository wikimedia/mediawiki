module( 'mediawiki.js' );

test( '-- Initial check', function(){

	ok( window.jQuery, 'jQuery defined' );
	ok( window.$j, '$j defined' );
	equals( window.$j, window.jQuery, '$j alias to jQuery' );

	ok( window.mediaWiki, 'mediaWiki defined' );
	ok( window.mw, 'mw defined' );
	equals( window.mw, window.mediaWiki, 'mw alias to mediaWiki' );

});

test( 'jQuery.extend', function(){

	equals( $j.trimLeft( '  foo bar  ' ), 'foo bar  ', 'trimLeft' );
	equals( $j.trimRight( '  foo bar  ' ), '  foo bar', 'trimRight' );
	equals( $j.ucFirst( 'foo'), 'Foo', 'ucFirst' );

	equals( $j.escapeRE( '<!-- ([{+mW+}]) $^|?>' ),
	 '<!\\-\\- \\(\\[\\{\\+mW\\+\\}\\]\\) \\$\\^\\|\\?>', 'escapeRE - Escape specials' );
	equals( $j.escapeRE( 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' ),
	 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'escapeRE - Leave uppercase alone' );
	equals( $j.escapeRE( 'abcdefghijklmnopqrstuvwxyz' ),
	 'abcdefghijklmnopqrstuvwxyz', 'escapeRE - Leave lowercase alone' );
	equals( $j.escapeRE( '0123456789' ), '0123456789', 'escapeRE - Leave numbers alone' );

	same( $j.isDomElement( document.getElementById( 'qunit-header' ) ), true,
	 'isDomElement: #qunit-header Node' );
	same( $j.isDomElement( document.getElementById( 'random-name' ) ), false,
	 'isDomElement: #random-name (null)' );
	same( $j.isDomElement( document.getElementsByTagName( 'div' ) ), false,
	 'isDomElement: getElementsByTagName Array' );
	same( $j.isDomElement( document.getElementsByTagName( 'div' )[0] ), true,
	 'isDomElement: getElementsByTagName(..)[0] Node' );
	same( $j.isDomElement( $j( 'div' ) ), false,
	 'isDomElement: jQuery object' );
	same( $j.isDomElement( $j( 'div' ).get(0) ), true,
	 'isDomElement: jQuery object > Get node' );
	same( $j.isDomElement( document.createElement( 'div' ) ), true,
	 'isDomElement: createElement' );
	same( $j.isDomElement( { foo: 1 } ), false,
	 'isDomElement: Object' );

	equals( $j.isEmpty( 'string' ), false, 'isEmptry: "string"' );
	equals( $j.isEmpty( '0' ), true, 'isEmptry: "0"' );
	equals( $j.isEmpty( [] ), true, 'isEmptry: []' );
	equals( $j.isEmpty( {} ), true, 'isEmptry: {}' );
	// Documented behaviour
	equals( $j.isEmpty( { length: 0 } ), true, 'isEmptry: { length: 0 }' );

	ok( $j.compareArray( [0, 'a', [], [2, 'b'] ], [0, "a", [], [2, "b"] ] ),
	 'compareArray: Two the same deep arrays' );
	ok( !$j.compareArray( [1], [2] ), 'compareArray: Two different arrays' );

	ok( $j.compareObject( {}, {} ), 'compareObject: Two empty objects' );
	ok( $j.compareObject( { foo: 1 }, { foo: 1 } ), 'compareObject: Two the same objects' );
	ok( !$j.compareObject( { bar: true }, { baz: false } ),
	 'compareObject: Two different objects' );
	
});

test( 'mw.Map / mw.config', function(){

	ok( mw.config instanceof mw.Map, 'mw.config instance of mw.Map' );
	ok( mw.config.get, 'get' );
	ok( mw.config.set, 'set' );
	ok( mw.config.exists, 'exists' );

	ok( !mw.config.exists( 'lipsum' ), 'exists: lipsum (inexistant)' );
	ok( mw.config.set( 'lipsum', 'Lorem ipsum' ), 'set: lipsum' );
	ok( mw.config.exists( 'lipsum' ), 'exists: lipsum (existant)' );

	equals( mw.config.get( 'lipsum' ), 'Lorem ipsum', 'get: lipsum' );
	equals( mw.config.get( ['lipsum'] ).lipsum, 'Lorem ipsum', 'get: lipsum (multiple)' );

});

test( 'mw.message / mw.msg / mw.messages', function(){
	ok( mw.message, 'mw.message defined' );
	ok( mw.msg, 'mw.msg defined' );
	ok( mw.messages, 'messages defined' );
	ok( mw.messages instanceof mw.Map, 'mw.messages instance of mw.Map' );
	ok( mw.messages.set( 'hello', 'Hello <b>awesome</b> world' ), 'mw.messages.set: Register' );

	var hello = mw.message( 'hello' );
	ok( hello, 'hello: Instance of Message' );

	equals( hello.format, 'parse', 'Message property "format" (default value)' );
	equals( hello.key, 'hello', 'Message property "key" (currect key)' );
	same( hello.parameters, [], 'Message property "parameters" (default value)' );


	ok( hello.params, 'Message prototype "params"');
	ok( hello.toString, 'Message prototype "toString"');
	ok( hello.parse, 'Message prototype "parse"');
	ok( hello.plain, 'Message prototype "plain"');
	ok( hello.escaped, 'Message prototype "escaped"');
	ok( hello.exists, 'Message prototype "exists"');

	equals( hello.toString(), 'Hello <b>awesome</b> world', 'Message.toString() test');
	equals( hello.escaped(), 'Hello &lt;b&gt;awesome&lt;/b&gt; world', 'Message.escaped() test');
	same( hello.exists(), true, 'Message.exists() test');

	equals( mw.msg( 'random' ), '<random>', 'square brackets around inexistant messages' );
	equals( mw.msg( 'hello' ), 'Hello <b>awesome</b> world', 'get message with default options' );
	
// params, toString, parse, plain, escaped, exists
});

test( 'mw.user', function(){

	ok( mw.user, 'user defined' );
	ok( mw.user.options instanceof mw.Map, 'user.options instance of mw.Map' );

	same( mw.user.name(), null, 'user.name() Anonymous' );
	ok( mw.user.anonymous(), 'user.anonymous() Anonymous' );

	// Not part of startUp module
	mw.config.set( 'wgUserName', 'John' );

	equals( mw.user.name(), 'John', 'user.name() Logged-in' );
	ok( !mw.user.anonymous(), 'user.anonymous() Logged-in' );

	equals( mw.user.id(), 'John', 'user.id() Logged-in' );

});

test( 'mw.loader', function(){
	expect(1);

	stop();
	
	mw.loader.implement( 'is.awesome', [location.href.match(/[^#\?]*/)[0] + 'sample/awesome.js'], {}, {} );
	mw.loader.using( 'is.awesome', function(){
		start();
		same( window.awesome, true, 'Implementing a module, is the callback timed properly ?');
	}, function(){
		start();
		same( 'mw.loader.using error callback fired', true, 'Implementing a module, is the callback timed properly ?');
	});

});

test( 'mw.html', function(){

	equals( mw.html.escape( '<mw awesome="awesome" value=\'test\' />' ),
	 '&lt;mw awesome=&quot;awesome&quot; value=&#039;test&#039; /&gt;', 'html.escape()' );

	equals( mw.html.element( 'div' ), '<div/>', 'mw.html.element() DIV (simple)' );

	equals( mw.html.element( 'div',
	 { id: 'foobar' } ),
	 '<div id="foobar"/>',
	 'mw.html.element() DIV (attribs)' );

	equals( mw.html.element( 'div',
	 null, 'a' ),
	 '<div>a</div>',
	 'mw.html.element() DIV (content)' );

	equals( mw.html.element( 'a',
	  { href: 'http://mediawiki.org/w/index.php?title=RL&action=history' }, 'a' ),
	  '<a href="http://mediawiki.org/w/index.php?title=RL&amp;action=history">a</a>',
	  'mw.html.element() DIV (attribs + content)' );

});
