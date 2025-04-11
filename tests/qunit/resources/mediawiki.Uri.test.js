QUnit.module( 'mediawiki.Uri', ( hooks ) => {
	hooks.beforeEach( function () {
		this.sandbox.replace( mw, 'Uri', mw.UriRelative( 'http://example.org/w/index.php' ) );
	} );

	QUnit.test.each( 'new mw.Uri( string ) with HTTP value', {
		'strict mode': true,
		'non-strict mode': false
	}, ( assert, strictMode ) => {
		const uriString = 'http://www.ietf.org/rfc/rfc2396.txt';
		const uri = new mw.Uri( uriString, {
			strictMode: strictMode
		} );

		assert.propContains( uri,
			{
				protocol: 'http',
				user: undefined,
				password: undefined,
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

	QUnit.test.each( 'new mw.Uri( string ) with overrideKeys', [
		[ true, {
			// simple parameter
			n: '1',
			// last key overrides earlier keys
			m: 'bar'
		} ],
		[ false, {
			// simple parameter
			n: '1',
			// preserve order of multi-value parameters
			m: [ 'foo', 'bar' ]
		} ]
	], ( assert, [ overrideKeys, expected ] ) => {
		const uri = new mw.Uri( 'http://www.example.com/dir/?m=foo&m=bar&n=1', {
			overrideKeys: overrideKeys
		} );

		assert.propEqual( uri.query, expected, 'query object' );
	} );

	QUnit.test( 'new mw.Uri( string ) with non-HTTP values', ( assert ) => {
		let uri = new mw.Uri( 'ftp://usr:pwd@192.0.2.16/' );
		assert.propContains( uri,
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
			'ftp URI with user and password'
		);

		uri = new mw.Uri( 'http://example.com/?foo[1]=b&foo[0]=a&foo[]=c' );
		assert.deepEqual(
			uri.query,
			{
				'foo[1]': 'b',
				'foo[0]': 'a',
				'foo[]': 'c'
			},
			'Array query parameters parsed as normal with arrayParams:false'
		);
		assert.throws(
			() => new mw.Uri( 'glaswegian penguins' ),
			( e ) => e.message === 'Bad constructor arguments',
			'throw error on non-URI as argument to constructor'
		);
		assert.throws(
			() => new mw.Uri( 'example.com/bar/baz', {
				strictMode: true
			} ),
			( e ) => e.message === 'Bad constructor arguments',
			'throw error on URI without protocol or // or leading / in strict mode'
		);

		uri = new mw.Uri( 'example.com/bar/baz', {
			strictMode: false
		} );
		assert.strictEqual( uri.toString(), 'http://example.com/bar/baz', 'normalize URI without protocol or // in loose mode' );

		uri = new mw.Uri( 'http://example.com/index.php?key=key&hasOwnProperty=hasOwnProperty&constructor=constructor&watch=watch' );
		assert.deepEqual(
			uri.query,
			{
				key: 'key',
				constructor: 'constructor',
				hasOwnProperty: 'hasOwnProperty',
				watch: 'watch'
			},
			'Keys in query strings support names of Object prototypes (bug T114344)'
		);
	} );

	QUnit.test( 'new mw.Uri( Object )', ( assert ) => {
		let uri = new mw.Uri( {
			protocol: 'http',
			host: 'www.foo.local',
			path: '/this'
		} );
		assert.strictEqual( uri.toString(), 'http://www.foo.local/this', 'simple input' );

		uri = new mw.Uri( {
			protocol: 'http',
			host: 'www.foo.local',
			path: '/this',
			query: { hi: 'there' },
			fragment: 'blah'
		} );
		assert.strictEqual( uri.toString(), 'http://www.foo.local/this?hi=there#blah', 'complex input' );

		assert.throws(
			() => new mw.Uri( {
				protocol: 'http',
				host: 'www.foo.local'
			} ),
			( e ) => e.message === 'Bad constructor arguments',
			'missing required properties'
		);
	} );

	QUnit.test( 'new mw.Uri( empty )', ( assert ) => {
		const testuri = 'http://example.org/w/index.php?a=1&a=2';
		const MyUri = mw.UriRelative( testuri );

		let uri = new MyUri();
		assert.strictEqual( uri.toString(), testuri, 'no arguments' );

		uri = new MyUri( undefined );
		assert.strictEqual( uri.toString(), testuri, 'undefined' );

		uri = new MyUri( null );
		assert.strictEqual( uri.toString(), testuri, 'null' );

		uri = new MyUri( '' );
		assert.strictEqual( uri.toString(), testuri, 'empty string' );

		uri = new MyUri( null, { overrideKeys: true } );
		assert.deepEqual( uri.query, { a: '2' }, 'null, with options' );
	} );

	QUnit.test( 'Setting properties', ( assert ) => {
		const uriBase = new mw.Uri( 'http://en.wiki.local/w/api.php' );

		let uri = uriBase.clone();
		uri.fragment = 'frag';
		assert.strictEqual( uri.toString(), 'http://en.wiki.local/w/api.php#frag', 'add a fragment' );
		uri.fragment = 'café';
		assert.strictEqual( uri.toString(), 'http://en.wiki.local/w/api.php#caf%C3%A9', 'fragment is url-encoded' );

		uri = uriBase.clone();
		uri.host = 'fr.wiki.local';
		uri.port = '8080';
		assert.strictEqual( uri.toString(), 'http://fr.wiki.local:8080/w/api.php', 'change host and port' );

		uri = uriBase.clone();
		uri.query.foo = 'bar';
		assert.strictEqual( uri.toString(), 'http://en.wiki.local/w/api.php?foo=bar', 'add query arguments' );

		delete uri.query.foo;
		assert.strictEqual( uri.toString(), 'http://en.wiki.local/w/api.php', 'delete query arguments' );

		uri = uriBase.clone();
		uri.query.foo = 'bar';
		assert.strictEqual( uri.toString(), 'http://en.wiki.local/w/api.php?foo=bar', 'extend query arguments' );
		uri.extend( {
			foo: 'quux',
			pif: 'paf',
			absent: undefined // T372742
		} );
		assert.true( uri.toString().includes( 'foo=quux' ), 'extend query arguments' );
		assert.false( uri.toString().includes( 'foo=bar' ), 'extend query arguments' );
		assert.true( uri.toString().includes( 'pif=paf' ), 'extend query arguments' );
		assert.false( uri.toString().includes( 'absent' ), 'extend query arguments' );
	} );

	QUnit.test( '.getQueryString()', ( assert ) => {
		let uri = new mw.Uri( 'http://search.example.com/?q=uri' );

		assert.propContains( uri,
			{
				protocol: 'http',
				user: undefined,
				password: undefined,
				host: 'search.example.com',
				port: undefined,
				path: '/',
				query: { q: 'uri' },
				fragment: undefined
			},
			'basic object properties'
		);
		assert.strictEqual( uri.getQueryString(), 'q=uri', 'query string' );

		uri = new mw.Uri( 'https://example.com/mw/index.php?title=Sandbox/7&other=Sandbox/7&foo' );
		assert.strictEqual(
			uri.getQueryString(),
			'title=Sandbox/7&other=Sandbox%2F7&foo',
			'title parameter is escaped the wiki-way'
		);
	} );

	QUnit.test( 'arrayParams', ( assert ) => {
		const uri1 = new mw.Uri( 'http://example.com/?foo[]=a&foo[]=b&foo[]=c', { arrayParams: true } );
		const uri2 = new mw.Uri( 'http://example.com/?foo[0]=a&foo[1]=b&foo[2]=c', { arrayParams: true } );
		const uri3 = new mw.Uri( 'http://example.com/?foo[1]=b&foo[0]=a&foo[]=c', { arrayParams: true } );
		const expectedQ = { foo: [ 'a', 'b', 'c' ] };
		const expectedS = 'foo%5B0%5D=a&foo%5B1%5D=b&foo%5B2%5D=c';

		assert.deepEqual( uri1.query, expectedQ,
			'array query parameters are parsed (implicit indexes)' );
		assert.deepEqual( uri1.getQueryString(), expectedS,
			'array query parameters are encoded (always with explicit indexes)' );
		assert.deepEqual( uri2.query, expectedQ,
			'array query parameters are parsed (explicit indexes)' );
		assert.deepEqual( uri2.getQueryString(), expectedS,
			'array query parameters are encoded (always with explicit indexes)' );
		assert.deepEqual( uri3.query, expectedQ,
			'array query parameters are parsed (mixed indexes, out of order)' );
		assert.deepEqual( uri3.getQueryString(), expectedS,
			'array query parameters are encoded (always with explicit indexes)' );

		const uriMissing = new mw.Uri( 'http://example.com/?foo[0]=a&foo[2]=c', { arrayParams: true } );
		// eslint-disable-next-line no-sparse-arrays
		const expectedMissingQ = { foo: [ 'a', , 'c' ] };
		const expectedMissingS = 'foo%5B0%5D=a&foo%5B2%5D=c';

		assert.deepEqual( uriMissing.query, expectedMissingQ,
			'array query parameters are parsed (missing array item)' );
		assert.deepEqual( uriMissing.getQueryString(), expectedMissingS,
			'array query parameters are encoded (missing array item)' );

		const uriWeird = new mw.Uri( 'http://example.com/?foo[0]=a&foo[1][1]=b&foo[x]=c', { arrayParams: true } );
		const expectedWeirdQ = { foo: [ 'a' ], 'foo[1][1]': 'b', 'foo[x]': 'c' };
		const expectedWeirdS = 'foo%5B0%5D=a&foo%5B1%5D%5B1%5D=b&foo%5Bx%5D=c';

		assert.deepEqual( uriWeird.query, expectedWeirdQ,
			'array query parameters are parsed (multi-dimensional or associative arrays are ignored)' );
		assert.deepEqual( uriWeird.getQueryString(), expectedWeirdS,
			'array query parameters are encoded (multi-dimensional or associative arrays are ignored)' );
	} );

	QUnit.test( '.clone()', ( assert ) => {
		const original = new mw.Uri( 'http://foo.example.org/index.php?one=1&two=2' );
		const clone = original.clone();

		assert.deepEqual( clone, original, 'clone has equivalent properties' );
		assert.strictEqual( original.toString(), clone.toString(), 'toString matches original' );

		assert.notStrictEqual( clone, original, 'clone is a different object when compared by reference' );

		clone.host = 'bar.example.org';
		assert.notStrictEqual( original.host, clone.host, 'manipulating clone did not effect original' );
		assert.notStrictEqual( original.toString(), clone.toString(), 'Stringified url no longer matches original' );

		clone.query.three = 3;

		assert.deepEqual(
			original.query,
			{ one: '1', two: '2' },
			'Properties is deep cloned (T39708)'
		);
	} );

	QUnit.test( '.toString() after query manipulation', ( assert ) => {
		let uri = new mw.Uri( 'http://www.example.com/dir/?m=foo&m=bar&n=1', {
			overrideKeys: true
		} );

		uri.query.n = [ 'x', 'y', 'z' ];

		// Verify parts and total length instead of entire string because order
		// of iteration can vary.
		assert.true( uri.toString().includes( 'm=bar' ), 'toString preserves other values' );
		assert.true( uri.toString().includes( 'n=x&n=y&n=z' ), 'toString parameter includes all values of an array query parameter' );
		assert.strictEqual( uri.toString().length, 'http://www.example.com/dir/?m=bar&n=x&n=y&n=z'.length, 'toString matches expected string' );

		uri = new mw.Uri( 'http://www.example.com/dir/?m=foo&m=bar&n=1', {
			overrideKeys: false
		} );

		// Change query values
		uri.query.n = [ 'x', 'y', 'z' ];

		// Verify parts and total length instead of entire string because order
		// of iteration can vary.
		assert.true( uri.toString().includes( 'm=foo&m=bar' ), 'toString preserves other values' );
		assert.true( uri.toString().includes( 'n=x&n=y&n=z' ), 'toString parameter includes all values of an array query parameter' );
		assert.strictEqual( uri.toString().length, 'http://www.example.com/dir/?m=foo&m=bar&n=x&n=y&n=z'.length, 'toString matches expected string' );

		// Remove query values
		uri.query.m.splice( 0, 1 );
		delete uri.query.n;

		assert.strictEqual( uri.toString(), 'http://www.example.com/dir/?m=bar', 'deletion properties' );

		// Remove more query values, leaving an empty array
		uri.query.m.splice( 0, 1 );
		assert.strictEqual( uri.toString(), 'http://www.example.com/dir/', 'empty array value is omitted' );
	} );

	QUnit.test( 'Variable defaultUri', ( assert ) => {
		let href = 'http://example.org/w/index.php#here';
		const UriClass = mw.UriRelative( () => href );

		let uri = new UriClass();
		assert.propContains( uri,
			{
				protocol: 'http',
				user: undefined,
				password: undefined,
				host: 'example.org',
				port: undefined,
				path: '/w/index.php',
				query: {},
				fragment: 'here'
			},
			'basic object properties'
		);

		// Default URI may change, e.g. via history.replaceState, pushState or location.hash (T74334)
		href = 'https://example.com/wiki/Foo?v=2';
		uri = new UriClass();
		assert.propContains( uri,
			{
				protocol: 'https',
				user: undefined,
				password: undefined,
				host: 'example.com',
				port: undefined,
				path: '/wiki/Foo',
				query: { v: '2' },
				fragment: undefined
			},
			'basic object properties'
		);
	} );

	QUnit.test( 'Advanced URL', ( assert ) => {
		const uri = new mw.Uri( 'http://auth@www.example.com:81/dir/dir.2/index.htm?q1=0&&test1&test2=value+%28escaped%29#caf%C3%A9' );
		assert.propContains( uri,
			{
				protocol: 'http',
				user: 'auth',
				password: undefined,
				host: 'www.example.com',
				port: '81',
				path: '/dir/dir.2/index.htm',
				query: { q1: '0', test1: null, test2: 'value (escaped)' },
				fragment: 'café'
			},
			'basic object properties'
		);

		assert.strictEqual( uri.getUserInfo(), 'auth', 'user info' );
		assert.strictEqual( uri.getAuthority(), 'auth@www.example.com:81', 'authority equal to auth@hostport' );
		assert.strictEqual( uri.getHostPort(), 'www.example.com:81', 'hostport equal to host:port' );

		const queryString = uri.getQueryString();
		assert.true( queryString.includes( 'q1=0' ), 'query param with numbers' );
		assert.true( queryString.includes( 'test1' ), 'query param with null value is included' );
		assert.false( queryString.includes( 'test1=' ), 'query param with null value does not generate equals sign' );
		assert.true( queryString.includes( 'test2=value+%28escaped%29' ), 'query param is url escaped' );

		const relativePath = uri.getRelativePath();
		assert.true( relativePath.includes( uri.path ), 'path in relative path' );
		assert.true( relativePath.includes( uri.getQueryString() ), 'query string in relative path' );
		assert.true( relativePath.includes( mw.Uri.encode( uri.fragment ) ), 'escaped fragment in relative path' );
	} );

	QUnit.test( 'Parse a uri with an @ symbol in the path and query', ( assert ) => {
		const uri = new mw.Uri( 'http://www.example.com/test@test?x=@uri&y@=uri&z@=@' );
		assert.propContains( uri,
			{
				protocol: 'http',
				user: undefined,
				password: undefined,
				host: 'www.example.com',
				port: undefined,
				path: '/test@test',
				query: { x: '@uri', 'y@': 'uri', 'z@': '@' },
				fragment: undefined
			},
			'basic object properties'
		);
		assert.strictEqual( uri.getQueryString(), 'x=%40uri&y%40=uri&z%40=%40', 'query string' );
	} );

	QUnit.test( 'Handle protocol-relative URLs', ( assert ) => {
		const UriRel = mw.UriRelative( 'glork://en.wiki.local/foo.php' );

		let uri = new UriRel( '//en.wiki.local/w/api.php' );
		assert.strictEqual( uri.protocol, 'glork', 'create protocol-relative URLs with same protocol as document' );

		uri = new UriRel( '/foo.com' );
		assert.strictEqual( uri.toString(), 'glork://en.wiki.local/foo.com', 'handle absolute paths by supplying protocol and host from document in loose mode' );

		uri = new UriRel( 'http:/foo.com' );
		assert.strictEqual( uri.toString(), 'http://en.wiki.local/foo.com', 'handle absolute paths by supplying host from document in loose mode' );

		uri = new UriRel( '/foo.com', true );
		assert.strictEqual( uri.toString(), 'glork://en.wiki.local/foo.com', 'handle absolute paths by supplying protocol and host from document in strict mode' );

		uri = new UriRel( 'http:/foo.com', true );
		assert.strictEqual( uri.toString(), 'http://en.wiki.local/foo.com', 'handle absolute paths by supplying host from document in strict mode' );
	} );

	QUnit.test( 'T37658', ( assert ) => {
		const testProtocol = 'https://';
		const testServer = 'foo.example.org';
		const testPort = '3004';
		const testPath = '/!1qy';

		let UriClass = mw.UriRelative( testProtocol + testServer + '/some/path/index.html' );
		let uri = new UriClass( testPath );
		let href = uri.toString();
		assert.strictEqual( href, testProtocol + testServer + testPath, 'Root-relative URL gets host & protocol supplied' );

		UriClass = mw.UriRelative( testProtocol + testServer + ':' + testPort + '/some/path.php' );
		uri = new UriClass( testPath );
		href = uri.toString();
		assert.strictEqual( href, testProtocol + testServer + ':' + testPort + testPath, 'Root-relative URL gets host, protocol, and port supplied' );
	} );
} );
