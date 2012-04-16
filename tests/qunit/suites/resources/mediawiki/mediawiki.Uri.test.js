module( 'mediawiki.Uri', QUnit.newMwEnvironment() );

test( '-- Initial check', function() {
	expect( 1 );

	ok( mw.UriRelative, 'mw.Uri defined' );
} );

test( 'mw.Uri bug 35658', function() {
	expect( 2 );

	var testProtocol = 'https://';
	var testServer = 'foo.example.org';
	var testPort = '3004';
	var testPath = '/!1qy';

	var uriClass = mw.UriRelative( testProtocol + testServer + '/some/path/index.html' );
	var uri = new uriClass( testPath );
	var href = uri.toString();
	equal( href, testProtocol + testServer + testPath, 'Root-relative URL gets host & protocol supplied' );

	uriClass = mw.UriRelative( testProtocol + testServer + ':' + testPort + '/some/path.php' );
	uri = new uriClass( testPath );
	href = uri.toString();
	equal( href, testProtocol + testServer + ':' + testPort + testPath, 'Root-relative URL gets host, protocol, and port supplied' );

} );
