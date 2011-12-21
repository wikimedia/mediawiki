( function() {

	// ensure we have a generic URI parser if not running in a browser
	if ( !mw.Uri ) {
		mw.Uri = mw.UriRelative( 'http://example.com/' );
	}

	describe( "mw.Uri", function() {

		describe( "should work well in loose and strict mode", function() {

			function basicTests( strict ) {
			
				describe( "should parse a simple HTTP URI correctly", function() { 

					var uriString = 'http://www.ietf.org/rfc/rfc2396.txt';
					var uri;
					if ( strict ) {
						uri = new mw.Uri( uriString, strict );
					} else {
						uri = new mw.Uri( uriString );
					}

					it( "should have basic object properties", function() {
						expect( uri.protocol ).toEqual( 'http' );
						expect( uri.host ).toEqual( 'www.ietf.org' );
						expect( uri.port ).not.toBeDefined();
						expect( uri.path ).toEqual( '/rfc/rfc2396.txt' );
						expect( uri.query ).toEqual( {} );
						expect( uri.fragment ).not.toBeDefined();
					} );

					describe( "should construct composite components of URI on request", function() { 
						it( "should have empty userinfo", function() { 
							expect( uri.getUserInfo() ).toEqual( '' );
						} );

						it( "should have authority equal to host", function() { 
							expect( uri.getAuthority() ).toEqual( 'www.ietf.org' );
						} );

						it( "should have hostport equal to host", function() { 
							expect( uri.getHostPort() ).toEqual( 'www.ietf.org' );
						} );

						it( "should have empty string as query string", function() { 
							expect( uri.getQueryString() ).toEqual( '' );
						} );

						it( "should have path as relative path", function() { 
							expect( uri.getRelativePath() ).toEqual( '/rfc/rfc2396.txt' );
						} );

						it( "should return a uri string equivalent to original", function() { 
							expect( uri.toString() ).toEqual( uriString );
						} );
					} );
				} );
			}

			describe( "should work in loose mode", function() { 
				basicTests( false );
			} );

			describe( "should work in strict mode", function() {
				basicTests( true );
			} );

		} );

		it( "should parse a simple ftp URI correctly with user and password", function() {
			var uri = new mw.Uri( 'ftp://usr:pwd@192.0.2.16/' );
			expect( uri.protocol ).toEqual( 'ftp' );
			expect( uri.user ).toEqual( 'usr' );
			expect( uri.password ).toEqual( 'pwd' );
			expect( uri.host ).toEqual( '192.0.2.16' );
			expect( uri.port ).not.toBeDefined();
			expect( uri.path ).toEqual( '/' );
			expect( uri.query ).toEqual( {} );
			expect( uri.fragment ).not.toBeDefined();
		} );

		it( "should parse a simple querystring", function() {
			var uri = new mw.Uri( 'http://www.google.com/?q=uri' );
			expect( uri.protocol ).toEqual( 'http' );
			expect( uri.host ).toEqual( 'www.google.com' );
			expect( uri.port ).not.toBeDefined();
			expect( uri.path ).toEqual( '/' );
			expect( uri.query ).toBeDefined();
			expect( uri.query ).toEqual( { q: 'uri' } );
			expect( uri.fragment ).not.toBeDefined();
			expect( uri.getQueryString() ).toEqual( 'q=uri' );
		} );

		describe( "should handle multiple value query args (overrideKeys on)", function() {
			var uri = new mw.Uri( 'http://www.example.com/dir/?m=foo&m=bar&n=1', { overrideKeys: true } );
			it ( "should parse with multiple values", function() {
				expect( uri.query.m ).toEqual( 'bar' );
				expect( uri.query.n ).toEqual( '1' );
			} );
			it ( "should accept multiple values", function() {
				uri.query.n = [ "x", "y", "z" ];
				expect( uri.toString() ).toContain( 'm=bar' );
				expect( uri.toString() ).toContain( 'n=x&n=y&n=z' );
				expect( uri.toString().length ).toEqual( 'http://www.example.com/dir/?m=bar&n=x&n=y&n=z'.length );
			} );
		} );

		describe( "should handle multiple value query args (overrideKeys off)", function() {
			var uri = new mw.Uri( 'http://www.example.com/dir/?m=foo&m=bar&n=1', { overrideKeys: false } );
			it ( "should parse with multiple values", function() {
				expect( uri.query.m.length ).toEqual( 2 );
				expect( uri.query.m[0] ).toEqual( 'foo' );
				expect( uri.query.m[1] ).toEqual( 'bar' );
				expect( uri.query.n ).toEqual( '1' );
			} );
			it ( "should accept multiple values", function() {
				uri.query.n = [ "x", "y", "z" ];
				expect( uri.toString() ).toContain( 'm=foo&m=bar' );
				expect( uri.toString() ).toContain( 'n=x&n=y&n=z' );
				expect( uri.toString().length ).toEqual( 'http://www.example.com/dir/?m=foo&m=bar&n=x&n=y&n=z'.length );
			} );
			it ( "should be okay with removing values", function() {
				uri.query.m.splice( 0, 1 );
				delete uri.query.n;
				expect( uri.toString() ).toEqual( 'http://www.example.com/dir/?m=bar' );
				uri.query.m.splice( 0, 1 );
				expect( uri.toString() ).toEqual( 'http://www.example.com/dir/' );
			} );
		} );

		describe( "should deal with an all-dressed URI with everything", function() {
			var uri = new mw.Uri( 'http://auth@www.example.com:81/dir/dir.2/index.htm?q1=0&&test1&test2=value+%28escaped%29#top' );

			it( "should have basic object properties", function() {
				expect( uri.protocol ).toEqual( 'http' );
				expect( uri.user ).toEqual( 'auth' );
				expect( uri.password ).not.toBeDefined();
				expect( uri.host ).toEqual( 'www.example.com' );
				expect( uri.port ).toEqual( '81' );
				expect( uri.path ).toEqual( '/dir/dir.2/index.htm' );
				expect( uri.query ).toEqual( { q1: '0', test1: null, test2: 'value (escaped)' } );
				expect( uri.fragment ).toEqual( 'top' );
			} );

			describe( "should construct composite components of URI on request", function() { 
				it( "should have userinfo", function() { 
					expect( uri.getUserInfo() ).toEqual( 'auth' );
				} );

				it( "should have authority equal to auth@hostport", function() { 
					expect( uri.getAuthority() ).toEqual( 'auth@www.example.com:81' );
				} );

				it( "should have hostport equal to host:port", function() { 
					expect( uri.getHostPort() ).toEqual( 'www.example.com:81' );
				} );

				it( "should have query string which contains all components", function() { 
					var queryString = uri.getQueryString();
					expect( queryString ).toContain( 'q1=0' );
					expect( queryString ).toContain( 'test1' );
					expect( queryString ).not.toContain( 'test1=' );
					expect( queryString ).toContain( 'test2=value+%28escaped%29' );
				} );

				it( "should have path as relative path", function() { 
					expect( uri.getRelativePath() ).toContain( uri.path );
					expect( uri.getRelativePath() ).toContain( uri.getQueryString() );
					expect( uri.getRelativePath() ).toContain( uri.fragment );
				} );

			} );
		} );

		describe( "should be able to clone itself", function() {
			var original = new mw.Uri( 'http://en.wiki.local/w/api.php?action=query&foo=bar' );			
			var clone = original.clone();

			it( "should make clones equivalent", function() { 
				expect( original ).toEqual( clone );
				expect( original.toString() ).toEqual( clone.toString() );
			} );

			it( "should be able to manipulate clones independently", function() { 
				// but they are still different objects
				expect( original ).not.toBe( clone );
				// and can diverge
				clone.host = 'fr.wiki.local';
				expect( original.host ).not.toEqual( clone.host );
				expect( original.toString() ).not.toEqual( clone.toString() );
			} );
		} );

		describe( "should be able to construct URL from object", function() {
			it ( "should construct given basic arguments", function() {  
				var uri = new mw.Uri( { protocol: 'http', host: 'www.foo.local',  path: '/this' } );
				expect( uri.toString() ).toEqual( 'http://www.foo.local/this' );
			} );
		
			it ( "should construct given more complex arguments", function() {  
				var uri = new mw.Uri( { 
					protocol: 'http', 
					host: 'www.foo.local',  
					path: '/this', 
					query: { hi: 'there' },
					fragment: 'blah'  
				} );
				expect( uri.toString() ).toEqual( 'http://www.foo.local/this?hi=there#blah' );
			} );	

			it ( "should fail to construct without required properties", function() {  
				expect( function() { 
					var uri = new mw.Uri( { protocol: 'http', host: 'www.foo.local' } );
				} ).toThrow( "Bad constructor arguments" );
			} );
		} );

		describe( "should be able to manipulate properties", function() { 
			var uri;

			beforeEach( function() { 
				uri = new mw.Uri( 'http://en.wiki.local/w/api.php' );			
			} );

			it( "can add a fragment", function() {
				uri.fragment = 'frag';
				expect( uri.toString() ).toEqual( 'http://en.wiki.local/w/api.php#frag' );
			} );

			it( "can change host and port", function() {
				uri.host = 'fr.wiki.local';
				uri.port = '8080';
				expect( uri.toString() ).toEqual( 'http://fr.wiki.local:8080/w/api.php' );
			} );

			it ( "can add query arguments", function() {
				uri.query.foo = 'bar';
				expect( uri.toString() ).toEqual( 'http://en.wiki.local/w/api.php?foo=bar' );
			} );

			it ( "can extend query arguments", function() {
				uri.query.foo = 'bar';
				expect( uri.toString() ).toEqual( 'http://en.wiki.local/w/api.php?foo=bar' );
				uri.extend( { foo: 'quux', pif: 'paf' } );
				expect( uri.toString() ).toContain( 'foo=quux' );
				expect( uri.toString() ).not.toContain( 'foo=bar' );
				expect( uri.toString() ).toContain( 'pif=paf' );
			} );

			it ( "can remove query arguments", function() {
				uri.query.foo = 'bar';
				expect( uri.toString() ).toEqual( 'http://en.wiki.local/w/api.php?foo=bar' );	
				delete( uri.query.foo );
				expect( uri.toString() ).toEqual( 'http://en.wiki.local/w/api.php' );	
			} );

		} );

		describe( "should handle protocol-relative URLs", function() { 

			it ( "should create protocol-relative URLs with same protocol as document", function() {
				var uriRel = mw.UriRelative( 'glork://en.wiki.local/foo.php' );
				var uri = new uriRel( '//en.wiki.local/w/api.php' );
				expect( uri.protocol ).toEqual( 'glork' );
			} );

		} );

		it( "should throw error on no arguments to constructor", function() {
			expect( function() { 
				var uri = new mw.Uri();
			} ).toThrow( "Bad constructor arguments" );
		} );

		it( "should throw error on empty string as argument to constructor", function() {
			expect( function() { 
				var uri = new mw.Uri( '' );
			} ).toThrow( "Bad constructor arguments" );
		} );

		it( "should throw error on non-URI as argument to constructor", function() {
			expect( function() { 
				var uri = new mw.Uri( 'glaswegian penguins' );
			} ).toThrow( "Bad constructor arguments" );
		} );

		it( "should throw error on improper URI as argument to constructor", function() {
			expect( function() { 
				var uri = new mw.Uri( 'http:/foo.com' );
			} ).toThrow( "Bad constructor arguments" );
		} );

		it( "should throw error on URI without protocol or // in strict mode", function() {
			expect( function() { 
				var uri = new mw.Uri( 'foo.com/bar/baz', true );
			} ).toThrow( "Bad constructor arguments" );
		} );

		it( "should normalize URI without protocol or // in loose mode", function() {
			var uri = new mw.Uri( 'foo.com/bar/baz', false );
			expect( uri.toString() ).toEqual( 'http://foo.com/bar/baz' );
		} );

	} );

} )();
