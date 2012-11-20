QUnit.module( 'mediawiki.Uri', QUnit.newMwEnvironment({
	setup: function () {
		this.mwUriOrg = mw.Uri;
		mw.Uri = mw.UriRelative( 'http://example.org/w/index.php' );
	},
	teardown: function () {
		mw.Uri = this.mwUriOrg;
		delete this.mwUriOrg;
	}
}) );

$.each( [true, false], function ( i, strictMode ) {
	QUnit.test( 'Basic mw.Uri object test in ' + ( strictMode ? '' : 'non-' ) + 'strict mode for a simple HTTP URI', 2, function ( assert ) {
		var uriString, uri;
		uriString = 'http://www.ietf.org/rfc/rfc2396.txt';
		uri = new mw.Uri( uriString, {
			strictMode: strictMode
		});

		assert.deepEqual(
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

		assert.deepEqual(
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

QUnit.test( 'Parse an ftp URI correctly with user and password', 1, function ( assert ) {
	var uri = new mw.Uri( 'ftp://usr:pwd@192.0.2.16/' );

	assert.deepEqual(
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

QUnit.test( 'Parse a uri with simple querystring', 1, function ( assert ) {
	var uri = new mw.Uri( 'http://www.google.com/?q=uri' );

	assert.deepEqual(
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

QUnit.test( 'Handle multiple query parameter (overrideKeys on)', 5, function ( assert ) {
	var uri = new mw.Uri( 'http://www.example.com/dir/?m=foo&m=bar&n=1', {
		overrideKeys: true
	});

	assert.equal( uri.query.n, '1', 'multiple parameters are parsed' );
	assert.equal( uri.query.m, 'bar', 'last key overrides earlier keys' );

	uri.query.n = [ 'x', 'y', 'z' ];

	// Verify parts and total length instead of entire string because order
	// of iteration can vary.
	assert.ok( uri.toString().indexOf( 'm=bar' ), 'toString preserves other values' );
	assert.ok( uri.toString().indexOf( 'n=x&n=y&n=z' ), 'toString parameter includes all values of an array query parameter' );
	assert.equal( uri.toString().length, 'http://www.example.com/dir/?m=bar&n=x&n=y&n=z'.length, 'toString matches expected string' );
} );

QUnit.test( 'Handle multiple query parameter (overrideKeys off)', 9, function ( assert ) {
	var uri = new mw.Uri( 'http://www.example.com/dir/?m=foo&m=bar&n=1', {
		overrideKeys: false
	});

	// Strict comparison so that types are also verified (n should be string '1')
	assert.strictEqual( uri.query.m.length, 2, 'multi-value query should be an array with 2 items' );
	assert.strictEqual( uri.query.m[0], 'foo', 'order and value is correct' );
	assert.strictEqual( uri.query.m[1], 'bar', 'order and value is correct' );
	assert.strictEqual( uri.query.n, '1', 'n=1 is parsed with the correct value of the expected type' );

	// Change query values
	uri.query.n = [ 'x', 'y', 'z' ];

	// Verify parts and total length instead of entire string because order
	// of iteration can vary.
	assert.ok( uri.toString().indexOf( 'm=foo&m=bar' ) >= 0, 'toString preserves other values' );
	assert.ok( uri.toString().indexOf( 'n=x&n=y&n=z' ) >= 0, 'toString parameter includes all values of an array query parameter' );
	assert.equal( uri.toString().length, 'http://www.example.com/dir/?m=foo&m=bar&n=x&n=y&n=z'.length, 'toString matches expected string' );

	// Remove query values
	uri.query.m.splice( 0, 1 );
	delete uri.query.n;

	assert.equal( uri.toString(), 'http://www.example.com/dir/?m=bar', 'deletion properties' );

	// Remove more query values, leaving an empty array
	uri.query.m.splice( 0, 1 );
	assert.equal( uri.toString(), 'http://www.example.com/dir/', 'empty array value is ommitted' );
} );

QUnit.test( 'All-dressed URI with everything', 11, function ( assert ) {
	var uri, queryString, relativePath;

	uri = new mw.Uri( 'http://auth@www.example.com:81/dir/dir.2/index.htm?q1=0&&test1&test2=value+%28escaped%29#top' );

	assert.deepEqual(
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

	assert.equal( uri.getUserInfo(), 'auth', 'user info' );

	assert.equal( uri.getAuthority(), 'auth@www.example.com:81', 'authority equal to auth@hostport' );

	assert.equal( uri.getHostPort(), 'www.example.com:81', 'hostport equal to host:port' );

	queryString = uri.getQueryString();
	assert.ok( queryString.indexOf( 'q1=0' ) >= 0, 'query param with numbers' );
	assert.ok( queryString.indexOf( 'test1' ) >= 0, 'query param with null value is included' );
	assert.ok( queryString.indexOf( 'test1=' ) === -1, 'query param with null value does not generate equals sign' );
	assert.ok( queryString.indexOf( 'test2=value+%28escaped%29' ) >= 0, 'query param is url escaped' );

	relativePath = uri.getRelativePath();
	assert.ok( relativePath.indexOf( uri.path ) >= 0, 'path in relative path' );
	assert.ok( relativePath.indexOf( uri.getQueryString() ) >= 0, 'query string in relative path' );
	assert.ok( relativePath.indexOf( uri.fragment ) >= 0, 'fragement in relative path' );
} );

QUnit.test( 'Cloning', 6, function ( assert ) {
	var original, clone;

	original = new mw.Uri( 'http://foo.example.org/index.php?one=1&two=2' );
	clone = original.clone();

	assert.deepEqual( clone, original, 'clone has equivalent properties' );
	assert.equal( original.toString(), clone.toString(), 'toString matches original' );

	assert.notStrictEqual( clone, original, 'clone is a different object when compared by reference' );

	clone.host = 'bar.example.org';
	assert.notEqual( original.host, clone.host, 'manipulating clone did not effect original' );
	assert.notEqual( original.toString(), clone.toString(), 'Stringified url no longer matches original' );

	clone.query.three = 3;

	assert.deepEqual(
		original.query,
		{ 'one': '1', 'two': '2' },
		'Properties is deep cloned (bug 37708)'
	);
} );

QUnit.test( 'Constructing mw.Uri from plain object', 3, function ( assert ) {
	var uri = new mw.Uri({
		protocol: 'http',
		host: 'www.foo.local',
		path: '/this'
	});
	assert.equal( uri.toString(), 'http://www.foo.local/this', 'Basic properties' );

	uri = new mw.Uri({
		protocol: 'http',
		host: 'www.foo.local',
		path: '/this',
		query: { hi: 'there' },
		fragment: 'blah'
	});
	assert.equal( uri.toString(), 'http://www.foo.local/this?hi=there#blah', 'More complex properties' );

	assert.throws(
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

QUnit.test( 'Manipulate properties', 8, function ( assert ) {
	var uriBase, uri;

	uriBase = new mw.Uri( 'http://en.wiki.local/w/api.php' );

	uri = uriBase.clone();
	uri.fragment = 'frag';
	assert.equal( uri.toString(), 'http://en.wiki.local/w/api.php#frag', 'add a fragment' );

	uri = uriBase.clone();
	uri.host = 'fr.wiki.local';
	uri.port = '8080';
	assert.equal( uri.toString(), 'http://fr.wiki.local:8080/w/api.php', 'change host and port' );

	uri = uriBase.clone();
	uri.query.foo = 'bar';
	assert.equal( uri.toString(), 'http://en.wiki.local/w/api.php?foo=bar', 'add query arguments' );

	delete uri.query.foo;
	assert.equal( uri.toString(), 'http://en.wiki.local/w/api.php', 'delete query arguments' );

	uri = uriBase.clone();
	uri.query.foo = 'bar';
	assert.equal( uri.toString(), 'http://en.wiki.local/w/api.php?foo=bar', 'extend query arguments' );
	uri.extend({
		foo: 'quux',
		pif: 'paf'
	});
	assert.ok( uri.toString().indexOf( 'foo=quux' ) >= 0, 'extend query arguments' );
	assert.ok( uri.toString().indexOf( 'foo=bar' ) === -1, 'extend query arguments' );
	assert.ok( uri.toString().indexOf( 'pif=paf' ) >= 0 , 'extend query arguments' );
} );

QUnit.test( 'Handle protocol-relative URLs', 5, function ( assert ) {
	var UriRel, uri;

	UriRel = mw.UriRelative( 'glork://en.wiki.local/foo.php' );

	uri = new UriRel( '//en.wiki.local/w/api.php' );
	assert.equal( uri.protocol, 'glork', 'create protocol-relative URLs with same protocol as document' );

	uri = new UriRel( '/foo.com' );
	assert.equal( uri.toString(), 'glork://en.wiki.local/foo.com', 'handle absolute paths by supplying protocol and host from document in loose mode' );

	uri = new UriRel( 'http:/foo.com' );
	assert.equal( uri.toString(), 'http://en.wiki.local/foo.com', 'handle absolute paths by supplying host from document in loose mode' );

	uri = new UriRel( '/foo.com', true );
	assert.equal( uri.toString(), 'glork://en.wiki.local/foo.com', 'handle absolute paths by supplying protocol and host from document in strict mode' );

	uri = new UriRel( 'http:/foo.com', true );
	assert.equal( uri.toString(), 'http://en.wiki.local/foo.com', 'handle absolute paths by supplying host from document in strict mode' );
} );

QUnit.test( 'Bad calls', 3, function ( assert ) {
	var uri;

	assert.throws(
		function () {
			return new mw.Uri( 'glaswegian penguins' );
		},
		function ( e ) {
			return e.message === 'Bad constructor arguments';
		},
		'throw error on non-URI as argument to constructor'
	);

	assert.throws(
		function () {
			return new mw.Uri( 'foo.com/bar/baz', {
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
	assert.equal( uri.toString(), 'http://foo.com/bar/baz', 'normalize URI without protocol or // in loose mode' );
});

QUnit.test( 'bug 35658', 2, function ( assert ) {
	var testProtocol, testServer, testPort, testPath, UriClass, uri, href;

	testProtocol = 'https://';
	testServer = 'foo.example.org';
	testPort = '3004';
	testPath = '/!1qy';

	UriClass = mw.UriRelative( testProtocol + testServer + '/some/path/index.html' );
	uri = new UriClass( testPath );
	href = uri.toString();
	assert.equal( href, testProtocol + testServer + testPath, 'Root-relative URL gets host & protocol supplied' );

	UriClass = mw.UriRelative( testProtocol + testServer + ':' + testPort + '/some/path.php' );
	uri = new UriClass( testPath );
	href = uri.toString();
	assert.equal( href, testProtocol + testServer + ':' + testPort + testPath, 'Root-relative URL gets host, protocol, and port supplied' );

} );

QUnit.test( 'Constructor falls back to default location', 4, function ( assert ) {
	var testuri, MyUri, uri;

	testuri = 'http://example.org/w/index.php';
	MyUri = mw.UriRelative( testuri );

	uri = new MyUri();
	assert.equal( uri.toString(), testuri, 'no arguments' );

	uri = new MyUri( undefined );
	assert.equal( uri.toString(), testuri, 'undefined' );

	uri = new MyUri( null );
	assert.equal( uri.toString(), testuri, 'null' );

	uri = new MyUri( '' );
	assert.equal( uri.toString(), testuri, 'empty string' );
} );
