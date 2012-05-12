module( 'mediawiki.Uri', QUnit.newMwEnvironment() );

test( '-- Initial check', function () {
	expect( 2 );

	// Ensure we have a generic mw.Uri constructor. By default mediawiki.uri,
	// will use the currrent window ocation as base. But for testing we need
	// to have a generic one, so that it doens't return false negatives if
	// we run the test suite from an https server.
	mw.Uri = mw.UriRelative( 'http://example.org/w/index.php' );

	ok( mw.UriRelative, 'mw.UriRelative defined' );
	ok( mw.Uri, 'mw.Uri defined' );
} );

$.each( [true, false], function ( i, strictMode ) {
	test( 'Basic mw.Uri object test in ' + ( strictMode ? '' : 'non-' ) + 'strict mode for a simple HTTP URI', function () {
		var uriString, uri;
		expect( 2 );

		uriString = 'http://www.ietf.org/rfc/rfc2396.txt';
		uri = new mw.Uri( uriString, {
			strictMode: strictMode
		});

		deepEqual(
			{
				protocol: uri.protocol,
				host: uri.host,
				port: uri.port,
				path: uri.path,
				query: uri.query,
				fragment: uri.fragment
			}, {
				protocol: 'http',
				host: 'www.ietf.org',
				port: undefined,
				path: '/rfc/rfc2396.txt',
				query: {},
				fragment: undefined
			},
			'basic object properties'
		);

		deepEqual(
			{
				userInfo: uri.getUserInfo(),
				authority: uri.getAuthority(),
				hostPort: uri.getHostPort(),
				queryString: uri.getQueryString(),
				relativePath: uri.getRelativePath(),
				toString: uri.toString()
			},
			{
				userInfo: '',
				authority: 'www.ietf.org',
				hostPort: 'www.ietf.org',
				queryString: '',
				relativePath: '/rfc/rfc2396.txt',
				toString: uriString
			},
			'construct composite components of URI on request'
		);

	});
});

test( 'Parse an ftp URI correctly with user and password', function () {
	var uri;
	expect( 1 );

	uri = new mw.Uri( 'ftp://usr:pwd@192.0.2.16/' );

	deepEqual(
		{
			protocol: uri.protocol,
			user: uri.user,
			password: uri.password,
			host: uri.host,
			port: uri.port,
			path: uri.path,
			query: uri.query,
			fragment: uri.fragment
		},
		{
			protocol: 'ftp',
			user: 'usr',
			password: 'pwd',
			host: '192.0.2.16',
			port: undefined,
			path: '/',
			query: {},
			fragment: undefined
		},
		'basic object properties'
	);
} );

test( 'Parse a uri with simple querystring', function () {
	var uri;
	expect( 1 );

	uri = new mw.Uri( 'http://www.google.com/?q=uri' );

	deepEqual(
		{
			protocol: uri.protocol,
			host: uri.host,
			port: uri.port,
			path: uri.path,
			query: uri.query,
			fragment: uri.fragment,
			queryString: uri.getQueryString()
		},
		{
			protocol: 'http',
			host: 'www.google.com',
			port: undefined,
			path: '/',
			query: { q: 'uri' },
			fragment: undefined,
			queryString: 'q=uri'
		},
		'basic object properties'
	);
} );

test( 'Handle multiple query parameter (overrideKeys on)', function () {
	var uri;
	expect( 5 );

	uri = new mw.Uri( 'http://www.example.com/dir/?m=foo&m=bar&n=1', {
		overrideKeys: true
	});

	equal( uri.query.n, '1', 'multiple parameters are parsed' );
	equal( uri.query.m, 'bar', 'last key overrides earlier keys' );

	uri.query.n = [ 'x', 'y', 'z' ];

	// Verify parts and total length instead of entire string because order
	// of iteration can vary.
	ok( uri.toString().indexOf( 'm=bar' ), 'toString preserves other values' );
	ok( uri.toString().indexOf( 'n=x&n=y&n=z' ), 'toString parameter includes all values of an array query parameter' );
	equal( uri.toString().length, 'http://www.example.com/dir/?m=bar&n=x&n=y&n=z'.length, 'toString matches expected string' );
} );

test( 'Handle multiple query parameter (overrideKeys off)', function () {
	var uri;
	expect( 9 );

	uri = new mw.Uri( 'http://www.example.com/dir/?m=foo&m=bar&n=1', {
		overrideKeys: false
	});

	// Strict comparison so that types are also verified (n should be string '1')
	strictEqual( uri.query.m.length, 2, 'multi-value query should be an array with 2 items' );
	strictEqual( uri.query.m[0], 'foo', 'order and value is correct' );
	strictEqual( uri.query.m[1], 'bar', 'order and value is correct' );
	strictEqual( uri.query.n, '1', 'n=1 is parsed with the correct value of the expected type' );

	// Change query values
	uri.query.n = [ 'x', 'y', 'z' ];

	// Verify parts and total length instead of entire string because order
	// of iteration can vary.
	ok( uri.toString().indexOf( 'm=foo&m=bar' ) >= 0, 'toString preserves other values' );
	ok( uri.toString().indexOf( 'n=x&n=y&n=z' ) >= 0, 'toString parameter includes all values of an array query parameter' );
	equal( uri.toString().length, 'http://www.example.com/dir/?m=foo&m=bar&n=x&n=y&n=z'.length, 'toString matches expected string' );

	// Remove query values
	uri.query.m.splice( 0, 1 );
	delete uri.query.n;

	equal( uri.toString(), 'http://www.example.com/dir/?m=bar', 'deletion properties' );

	// Remove more query values, leaving an empty array
	uri.query.m.splice( 0, 1 );
	equal( uri.toString(), 'http://www.example.com/dir/', 'empty array value is ommitted' );
} );

test( 'All-dressed URI with everything', function () {
	var uri, queryString, relativePath;
	expect( 11 );

	uri = new mw.Uri( 'http://auth@www.example.com:81/dir/dir.2/index.htm?q1=0&&test1&test2=value+%28escaped%29#top' );

	deepEqual(
		{
			protocol: uri.protocol,
			user: uri.user,
			password: uri.password,
			host: uri.host,
			port: uri.port,
			path: uri.path,
			query: uri.query,
			fragment: uri.fragment
		},
		{
			protocol: 'http',
			user: 'auth',
			password: undefined,
			host: 'www.example.com',
			port: '81',
			path: '/dir/dir.2/index.htm',
			query: { q1: '0', test1: null, test2: 'value (escaped)' },
			fragment: 'top'
		},
		'basic object properties'
	);

	equal( uri.getUserInfo(), 'auth', 'user info' );

	equal( uri.getAuthority(), 'auth@www.example.com:81', 'authority equal to auth@hostport' );

	equal( uri.getHostPort(), 'www.example.com:81', 'hostport equal to host:port' );

	queryString = uri.getQueryString();
	ok( queryString.indexOf( 'q1=0' ) >= 0, 'query param with numbers' );
	ok( queryString.indexOf( 'test1' ) >= 0, 'query param with null value is included' );
	ok( queryString.indexOf( 'test1=' ) === -1, 'query param with null value does not generate equals sign' );
	ok( queryString.indexOf( 'test2=value+%28escaped%29' ) >= 0, 'query param is url escaped' );

	relativePath = uri.getRelativePath();
	ok( relativePath.indexOf( uri.path ) >= 0, 'path in relative path' );
	ok( relativePath.indexOf( uri.getQueryString() ) >= 0, 'query string in relative path' );
	ok( relativePath.indexOf( uri.fragment ) >= 0, 'fragement in relative path' );
} );

test( 'Cloning', function () {
	var original, clone;
	expect( 5 );

	original = new mw.Uri( 'http://en.wiki.local/w/api.php?action=query&foo=bar' );
	clone = original.clone();

	deepEqual( clone, original, 'clone has equivalent properties' );
	equal( original.toString(), clone.toString(), 'toString matches original' );

	notStrictEqual( clone, original, 'clone is not the same when compared by reference' );

	clone.host = 'fr.wiki.local';
	notEqual( original.host, clone.host, 'manipulating clone did not effect original' );
	notEqual( original.toString(), clone.toString(), 'toString no longer matches original' );
} );

test( 'Constructing mw.Uri from plain object', function () {
	var uri;
	expect( 3 );

	uri = new mw.Uri({
		protocol: 'http',
		host: 'www.foo.local',
		path: '/this'
	});
	equal( uri.toString(), 'http://www.foo.local/this', 'Basic properties' );

	uri = new mw.Uri({
		protocol: 'http',
		host: 'www.foo.local',
		path: '/this',
		query: { hi: 'there' },
		fragment: 'blah'
	});
	equal( uri.toString(), 'http://www.foo.local/this?hi=there#blah', 'More complex properties' );

	raises(
		function () {
			var uri = new mw.Uri({
				protocol: 'http',
				host: 'www.foo.local'
			});
		},
		function ( e ) {
			return e.message === 'Bad constructor arguments';
		},
		'Construction failed when missing required properties'
	);
} );

test( 'Manipulate properties', function () {
	var uriBase, uri;
	expect( 8 );

	uriBase = new mw.Uri( 'http://en.wiki.local/w/api.php' );

	uri = uriBase.clone();
	uri.fragment = 'frag';
	equal( uri.toString(), 'http://en.wiki.local/w/api.php#frag', 'add a fragment' );

	uri = uriBase.clone();
	uri.host = 'fr.wiki.local';
	uri.port = '8080';
	equal( uri.toString(), 'http://fr.wiki.local:8080/w/api.php', 'change host and port' );

	uri = uriBase.clone();
	uri.query.foo = 'bar';
	equal( uri.toString(), 'http://en.wiki.local/w/api.php?foo=bar', 'add query arguments' );

	delete uri.query.foo;
	equal( uri.toString(), 'http://en.wiki.local/w/api.php', 'delete query arguments' );

	uri = uriBase.clone();
	uri.query.foo = 'bar';
	equal( uri.toString(), 'http://en.wiki.local/w/api.php?foo=bar', 'extend query arguments' );
	uri.extend({
		foo: 'quux',
		pif: 'paf'
	});
	ok( uri.toString().indexOf( 'foo=quux' ) >= 0, 'extend query arguments' );
	ok( uri.toString().indexOf( 'foo=bar' ) === -1, 'extend query arguments' );
	ok( uri.toString().indexOf( 'pif=paf' ) >= 0 , 'extend query arguments' );
} );

test( 'Handle protocol-relative URLs', function () {
	var UriRel, uri;
	expect( 5 );

	UriRel = mw.UriRelative( 'glork://en.wiki.local/foo.php' );

	uri = new UriRel( '//en.wiki.local/w/api.php' );
	equal( uri.protocol, 'glork', 'create protocol-relative URLs with same protocol as document' );

	uri = new UriRel( '/foo.com' );
	equal( uri.toString(), 'glork://en.wiki.local/foo.com', 'handle absolute paths by supplying protocol and host from document in loose mode' );

	uri = new UriRel( 'http:/foo.com' );
	equal( uri.toString(), 'http://en.wiki.local/foo.com', 'handle absolute paths by supplying host from document in loose mode' );

	uri = new UriRel( '/foo.com', true );
	equal( uri.toString(), 'glork://en.wiki.local/foo.com', 'handle absolute paths by supplying protocol and host from document in strict mode' );

	uri = new UriRel( 'http:/foo.com', true );
	equal( uri.toString(), 'http://en.wiki.local/foo.com', 'handle absolute paths by supplying host from document in strict mode' );
} );

test( 'Bad calls', function () {
	var uri;
	expect( 5 );

	raises(
		function () {
			new mw.Uri();
		},
		function ( e ) {
			return e.message === 'Bad constructor arguments';
		},
		'throw error on no arguments to constructor'
	);

	raises(
		function () {
			new mw.Uri( '' );
		},
		function ( e ) {
			return e.message === 'Bad constructor arguments';
		},
		'throw error on empty string as argument to constructor'
	);

	raises(
		function () {
			new mw.Uri( 'glaswegian penguins' );
		},
		function ( e ) {
			return e.message === 'Bad constructor arguments';
		},
		'throw error on non-URI as argument to constructor'
	);

	raises(
		function () {
			new mw.Uri( 'foo.com/bar/baz', {
				strictMode: true
			});
		},
		function ( e ) {
			return e.message === 'Bad constructor arguments';
		},
		'throw error on URI without protocol or // or leading / in strict mode'
	);

	uri = new mw.Uri( 'foo.com/bar/baz', {
		strictMode: false
	});
	equal( uri.toString(), 'http://foo.com/bar/baz', 'normalize URI without protocol or // in loose mode' );
});

test( 'bug 35658', function () {
	expect( 2 );

	var testProtocol, testServer, testPort, testPath, UriClass, uri, href;

	testProtocol = 'https://';
	testServer = 'foo.example.org';
	testPort = '3004';
	testPath = '/!1qy';

	UriClass = mw.UriRelative( testProtocol + testServer + '/some/path/index.html' );
	uri = new UriClass( testPath );
	href = uri.toString();
	equal( href, testProtocol + testServer + testPath, 'Root-relative URL gets host & protocol supplied' );

	UriClass = mw.UriRelative( testProtocol + testServer + ':' + testPort + '/some/path.php' );
	uri = new UriClass( testPath );
	href = uri.toString();
	equal( href, testProtocol + testServer + ':' + testPort + testPath, 'Root-relative URL gets host, protocol, and port supplied' );

} );
