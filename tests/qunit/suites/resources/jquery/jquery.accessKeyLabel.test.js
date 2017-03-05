( function ( $ ) {
	var getAccessKeyPrefixTestData, updateTooltipAccessKeysTestData;

	QUnit.module( 'jquery.accessKeyLabel', QUnit.newMwEnvironment( {
		messages: {
			brackets: '[$1]',
			'word-separator': ' '
		}
	} ) );

	getAccessKeyPrefixTestData = [
		// ua string, platform string, expected prefix
		// Internet Explorer
		[ 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)', 'Win32', 'alt-' ],
		[ 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)', 'Win32', 'alt-' ],
		[ 'Mozilla/5.0 (Windows NT 6.3; Win64; x64; Trident/7.0; rv:11.0) like Gecko', 'Win64', 'alt-' ],
		[ 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.10136', 'Win64', 'alt-' ],
		// Firefox
		[ 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.1.19) Gecko/20110420 Firefox/3.5.19', 'MacIntel', 'ctrl-' ],
		[ 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.17) Gecko/20110422 Ubuntu/10.10 (maverick) Firefox/3.6.17', 'Linux i686', 'alt-shift-' ],
		[ 'Mozilla/5.0 (Windows NT 6.0; rv:2.0.1) Gecko/20100101 Firefox/4.0.1', 'Win32', 'alt-shift-' ],
		[ 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.12; rv:50.0) Gecko/20100101 Firefox/50.0', 'MacIntel', 'ctrl-option-' ],
		[ 'Mozilla/5.0 (X11; Linux x86_64; rv:17.0) Gecko/20121202 Firefox/17.0 Iceweasel/17.0.1', 'Linux 1686', 'alt-shift-' ],
		[ 'Mozilla/5.0 (Windows NT 5.2; U; de; rv:1.8.0) Gecko/20060728 Firefox/1.5.0', 'Win32', 'alt-' ],
		// Safari / Konqueror
		[ 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; nl-nl) AppleWebKit/531.22.7 (KHTML, like Gecko) Version/4.0.5 Safari/531.22.7', 'MacIntel', 'ctrl-option-' ],
		[ 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_7; de-de) AppleWebKit/525.28.3 (KHTML, like Gecko) Version/3.2.3 Safari/525.28.3', 'MacIntel', 'ctrl-' ],
		[ 'Mozilla/5.0 (Windows; U; Windows NT 5.1; cs-CZ) AppleWebKit/525.28.3 (KHTML, like Gecko) Version/3.2.3 Safari/525.29', 'Win32', 'alt-' ],
		[ 'Mozilla/5.0 (Windows; U; Windows NT 6.0; cs-CZ) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/4.0.5 Safari/531.22.7', 'Win32', 'alt-' ],
		[ 'Mozilla/5.0 (X11; Linux i686) KHTML/4.9.1 (like Gecko) Konqueror/4.9', 'Linux i686', 'ctrl-' ],
		// Opera
		[ 'Opera/9.80 (Windows NT 5.1)', 'Win32', 'shift-esc-' ],
		[ 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.52 Safari/537.36 OPR/15.0.1147.130', 'Win32', 'alt-shift-' ],
		// Chrome
		[ 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_8) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'MacIntel', 'ctrl-option-' ],
		[ 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.68 Safari/534.30', 'Linux i686', 'alt-shift-' ],
		// Unknown! Note: These aren't necessarily *right*, this is just
		// testing that we're getting the expected output based on the
		// platform.
		[ 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US; rv:1.0.1) Gecko/20021111 Chimera/0.6', 'MacPPC', 'ctrl-' ],
		[ 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.3a) Gecko/20021207 Phoenix/0.5', 'Linux i686', 'alt-' ]
	];
	// strings appended to title to make sure updateTooltipAccessKeys handles them correctly
	updateTooltipAccessKeysTestData = [ '', ' [a]', ' [test-a]', ' [alt-b]' ];

	function makeInput( title, accessKey ) {
		// The properties aren't escaped, so make sure you don't call this function with values that need to be escaped!
		return '<input title="' + title + '" ' + ( accessKey ? 'accessKey="' + accessKey + '" ' : '' ) + ' />';
	}

	QUnit.test( 'getAccessKeyPrefix', function ( assert ) {
		var i;
		for ( i = 0; i < getAccessKeyPrefixTestData.length; i++ ) {
			assert.equal( $.fn.updateTooltipAccessKeys.getAccessKeyPrefix( {
				userAgent: getAccessKeyPrefixTestData[ i ][ 0 ],
				platform: getAccessKeyPrefixTestData[ i ][ 1 ]
			} ), getAccessKeyPrefixTestData[ i ][ 2 ], 'Correct prefix for ' + getAccessKeyPrefixTestData[ i ][ 0 ] );
		}
	} );

	QUnit.test( 'updateTooltipAccessKeys - current browser', function ( assert ) {
		var title = $( makeInput( 'Title', 'a' ) ).updateTooltipAccessKeys().prop( 'title' ),
			// The new title should be something like "Title [alt-a]", but the exact label will depend on the browser.
			// The "a" could be capitalized, and the prefix could be anything, e.g. a simple "^" for ctrl-
			// (no browser is known using such a short prefix, though) or "Alt+Umschalt+" in German Firefox.
			result = /^Title \[(.+)[aA]\]$/.exec( title );
		assert.ok( result, 'title should match expected structure.' );
		assert.notEqual( result[ 1 ], 'test-', 'Prefix used for testing shouldn\'t be used in production.' );
	} );

	QUnit.test( 'updateTooltipAccessKeys - no access key', function ( assert ) {
		var i, oldTitle, $input, newTitle;
		for ( i = 0; i < updateTooltipAccessKeysTestData.length; i++ ) {
			oldTitle = 'Title' + updateTooltipAccessKeysTestData[ i ];
			$input = $( makeInput( oldTitle ) );
			$( '#qunit-fixture' ).append( $input );
			newTitle = $input.updateTooltipAccessKeys().prop( 'title' );
			assert.equal( newTitle, 'Title', 'title="' + oldTitle + '"' );
		}
	} );

	QUnit.test( 'updateTooltipAccessKeys - with access key', function ( assert ) {
		var i, oldTitle, $input, newTitle;
		$.fn.updateTooltipAccessKeys.setTestMode( true );
		for ( i = 0; i < updateTooltipAccessKeysTestData.length; i++ ) {
			oldTitle = 'Title' + updateTooltipAccessKeysTestData[ i ];
			$input = $( makeInput( oldTitle, 'a' ) );
			$( '#qunit-fixture' ).append( $input );
			newTitle = $input.updateTooltipAccessKeys().prop( 'title' );
			assert.equal( newTitle, 'Title [test-a]', 'title="' + oldTitle + '"' );
		}
		$.fn.updateTooltipAccessKeys.setTestMode( false );
	} );

	QUnit.test( 'updateTooltipAccessKeys with label element', function ( assert ) {
		var html, $label, $input;
		$.fn.updateTooltipAccessKeys.setTestMode( true );
		html = '<label for="testInput" title="Title">Label</label><input id="testInput" accessKey="a" />';
		$( '#qunit-fixture' ).html( html );
		$label = $( '#qunit-fixture label' );
		$input = $( '#qunit-fixture input' );
		$input.updateTooltipAccessKeys();
		assert.equal( $input.prop( 'title' ), '', 'No title attribute added to input element.' );
		assert.equal( $label.prop( 'title' ), 'Title [test-a]', 'title updated for associated label element.' );
		$.fn.updateTooltipAccessKeys.setTestMode( false );
	} );

	QUnit.test( 'updateTooltipAccessKeys with label element as parent', function ( assert ) {
		var html, $label, $input;
		$.fn.updateTooltipAccessKeys.setTestMode( true );
		html = '<label title="Title">Label<input id="testInput" accessKey="a" /></label>';
		$( '#qunit-fixture' ).html( html );
		$label = $( '#qunit-fixture label' );
		$input = $( '#qunit-fixture input' );
		$input.updateTooltipAccessKeys();
		assert.equal( $input.prop( 'title' ), '', 'No title attribute added to input element.' );
		assert.equal( $label.prop( 'title' ), 'Title [test-a]', 'title updated for associated label element.' );
		$.fn.updateTooltipAccessKeys.setTestMode( false );
	} );

}( jQuery ) );
