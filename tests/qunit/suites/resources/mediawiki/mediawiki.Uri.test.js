/*jshint -W024 */
( function ( mw, $ ) {
	QUnit.module( 'mediawiki.Uri', QUnit.newMwEnvironment( {
		setup: function () {
			this.mwUriOrg = mw.Uri;
			mw.Uri = mw.UriRelative( 'http://example.org/w/index.php' );
		},
		teardown: function () {
			mw.Uri = this.mwUriOrg;
			delete this.mwUriOrg;
		}
	} ) );

	$.each( [true, false], function ( i, strictMode ) {
		QUnit.test( 'Basic construction and properties (' + ( strictMode ? '' : 'non-' ) + 'strict mode)', 2, function ( assert ) {
			var uriString, uri;
			uriString = 'http://www.ietf.org/rfc/rfc2396.txt';
			uri = new mw.Uri( uriString, {
				strictMode: strictMode
			} );

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
		} );
	} );

	QUnit.test( 'Constructor( String[, Object ] )', 10, function ( assert ) {
		var uri;

		uri = new mw.Uri( 'http://www.example.com/dir/?m=foo&m=bar&n=1', {
			overrideKeys: true
		} );

		// Strict comparison to assert that numerical values stay strings
		assert.strictEqual( uri.query.n, '1', 'Simple parameter with overrideKeys:true' );
		assert.strictEqual( uri.query.m, 'bar', 'Last key overrides earlier keys with overrideKeys:true' );

		uri = new mw.Uri( 'http://www.example.com/dir/?m=foo&m=bar&n=1', {
			overrideKeys: false
		} );

		assert.strictEqual( uri.query.n, '1', 'Simple parameter with overrideKeys:false' );
		assert.strictEqual( uri.query.m[0], 'foo', 'Order of multi-value parameters with overrideKeys:true' );
		assert.strictEqual( uri.query.m[1], 'bar', 'Order of multi-value parameters with overrideKeys:true' );
		assert.strictEqual( uri.query.m.length, 2, 'Number of mult-value field is correct' );

		uri = new mw.Uri( 'ftp://usr:pwd@192.0.2.16/' );

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
			'Parse an ftp URI correctly with user and password'
		);

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
				return new mw.Uri( 'example.com/bar/baz', {
					strictMode: true
				} );
			},
			function ( e ) {
				return e.message === 'Bad constructor arguments';
			},
			'throw error on URI without protocol or // or leading / in strict mode'
		);

		uri = new mw.Uri( 'example.com/bar/baz', {
			strictMode: false
		} );
		assert.equal( uri.toString(), 'http://example.com/bar/baz', 'normalize URI without protocol or // in loose mode' );
	} );

	QUnit.test( 'Constructor( Object )', 3, function ( assert ) {
		var uri = new mw.Uri( {
			protocol: 'http',
			host: 'www.foo.local',
			path: '/this'
		} );
		assert.equal( uri.toString(), 'http://www.foo.local/this', 'Basic properties' );

		uri = new mw.Uri( {
			protocol: 'http',
			host: 'www.foo.local',
			path: '/this',
			query: { hi: 'there' },
			fragment: 'blah'
		} );
		assert.equal( uri.toString(), 'http://www.foo.local/this?hi=there#blah', 'More complex properties' );

		assert.throws(
			function () {
				return new mw.Uri( {
					protocol: 'http',
					host: 'www.foo.local'
				} );
			},
			function ( e ) {
				return e.message === 'Bad constructor arguments';
			},
			'Construction failed when missing required properties'
		);
	} );

	QUnit.test( 'Constructor( empty )', 4, function ( assert ) {
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

	QUnit.test( 'Properties', 8, function ( assert ) {
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
		uri.extend( {
			foo: 'quux',
			pif: 'paf'
		} );
		assert.ok( uri.toString().indexOf( 'foo=quux' ) >= 0, 'extend query arguments' );
		assert.ok( uri.toString().indexOf( 'foo=bar' ) === -1, 'extend query arguments' );
		assert.ok( uri.toString().indexOf( 'pif=paf' ) >= 0, 'extend query arguments' );
	} );

	QUnit.test( '.getQueryString()', 2, function ( assert ) {
		var uri = new mw.Uri( 'http://search.example.com/?q=uri' );

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
				host: 'search.example.com',
				port: undefined,
				path: '/',
				query: { q: 'uri' },
				fragment: undefined,
				queryString: 'q=uri'
			},
			'basic object properties'
		);

		uri = new mw.Uri( 'https://example.com/mw/index.php?title=Sandbox/7&other=Sandbox/7&foo' );
		assert.equal(
			uri.getQueryString(),
			'title=Sandbox/7&other=Sandbox%2F7&foo',
			'title parameter is escaped the wiki-way'
		);

	} );

	QUnit.test( '.clone()', 6, function ( assert ) {
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

	QUnit.test( '.toString() after query manipulation', 8, function ( assert ) {
		var uri;

		uri = new mw.Uri( 'http://www.example.com/dir/?m=foo&m=bar&n=1', {
			overrideKeys: true
		} );

		uri.query.n = [ 'x', 'y', 'z' ];

		// Verify parts and total length instead of entire string because order
		// of iteration can vary.
		assert.ok( uri.toString().indexOf( 'm=bar' ), 'toString preserves other values' );
		assert.ok( uri.toString().indexOf( 'n=x&n=y&n=z' ), 'toString parameter includes all values of an array query parameter' );
		assert.equal( uri.toString().length, 'http://www.example.com/dir/?m=bar&n=x&n=y&n=z'.length, 'toString matches expected string' );

		uri = new mw.Uri( 'http://www.example.com/dir/?m=foo&m=bar&n=1', {
			overrideKeys: false
		} );

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

	QUnit.test( 'Advanced URL', 11, function ( assert ) {
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

	QUnit.test( 'Parse a uri with an @ symbol in the path and query', 1, function ( assert ) {
		var uri = new mw.Uri( 'http://www.example.com/test@test?x=@uri&y@=uri&z@=@' );

		assert.deepEqual(
			{
				protocol: uri.protocol,
				user: uri.user,
				password: uri.password,
				host: uri.host,
				port: uri.port,
				path: uri.path,
				query: uri.query,
				fragment: uri.fragment,
				queryString: uri.getQueryString()
			},
			{
				protocol: 'http',
				user: undefined,
				password: undefined,
				host: 'www.example.com',
				port: undefined,
				path: '/test@test',
				query: { x: '@uri', 'y@': 'uri', 'z@': '@' },
				fragment: undefined,
				queryString: 'x=%40uri&y%40=uri&z%40=%40'
			},
			'basic object properties'
		);
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
}( mediaWiki, jQuery ) );
