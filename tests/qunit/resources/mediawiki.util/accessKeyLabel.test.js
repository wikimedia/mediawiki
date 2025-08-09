( function () {
	QUnit.module( 'mediawiki.util: jquery.accessKeyLabel', QUnit.newMwEnvironment( {
		messages: {
			brackets: '[$1]',
			'word-separator': ' '
		}
	} ) );

	const getAccessKeyPrefixTestData = [
		// ua string, platform string, expected prefix
		// Internet Explorer
		[ 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)', 'Win32', 'alt-' ],
		[ 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.10136', 'Win64', 'alt-' ],
		// Firefox
		[ 'Mozilla/5.0 (Windows NT 6.0; rv:2.0.1) Gecko/20100101 Firefox/4.0.1', 'Win32', 'alt-shift-' ],
		[ 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.12; rv:50.0) Gecko/20100101 Firefox/50.0', 'MacIntel', 'ctrl-option-' ],
		[ 'Mozilla/5.0 (X11; Linux x86_64; rv:17.0) Gecko/20121202 Firefox/17.0 Iceweasel/17.0.1', 'Linux 1686', 'alt-shift-' ],
		// Safari
		[ 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; nl-nl) AppleWebKit/531.22.7 (KHTML, like Gecko) Version/4.0.5 Safari/531.22.7', 'MacIntel', 'ctrl-option-' ],
		// Opera/Chrome/Konqueror
		[ 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.52 Safari/537.36 OPR/15.0.1147.130', 'Win32', 'alt-' ],
		[ 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_8) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30', 'MacIntel', 'ctrl-option-' ],
		[ 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.68 Safari/534.30', 'Linux i686', 'alt-' ],
		[ 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) QtWebEngine/5.11.3 Chrome/65.0.3325.230 Safari/537.36 Konqueror (WebEnginePart)', 'Linux x86_64', 'alt-' ],
		// Unknown! Note: These aren't necessarily *right* for the browser.
		// This is testing plaform-based fallback (Mac vs other).
		[ 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US; rv:1.0.1) Gecko/20021111 Chimera/0.6', 'MacPPC', 'ctrl-' ],
		[ 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.3a) Gecko/20021207 Phoenix/0.5', 'Linux i686', 'alt-' ]
	];
	// strings appended to title to make sure updateTooltipAccessKeys handles them correctly
	const updateTooltipAccessKeysTestData = [ '', ' [a]', ' [test-a]', ' [alt-b]' ];

	function makeInput( title, accessKey ) {
		// The properties aren't escaped, so make sure you don't call this function with values that need to be escaped!
		return '<input title="' + title + '" ' + ( accessKey ? 'accessKey="' + accessKey + '" ' : '' ) + ' />';
	}

	QUnit.test( 'getAccessKeyPrefix', ( assert ) => {
		let i;
		for ( i = 0; i < getAccessKeyPrefixTestData.length; i++ ) {
			assert.strictEqual( $.fn.updateTooltipAccessKeys.getAccessKeyPrefix( {
				userAgent: getAccessKeyPrefixTestData[ i ][ 0 ],
				platform: getAccessKeyPrefixTestData[ i ][ 1 ]
			} ), getAccessKeyPrefixTestData[ i ][ 2 ], 'Correct prefix for ' + getAccessKeyPrefixTestData[ i ][ 0 ] );
		}
	} );

	QUnit.test( 'updateTooltipAccessKeys - current browser', ( assert ) => {
		const title = $( makeInput( 'Title', 'a' ) ).updateTooltipAccessKeys().prop( 'title' ),
			// The new title should be something like "Title [alt-a]", but the exact label will depend on the browser.
			// The "a" could be capitalized, and the prefix could be anything, e.g. a simple "^" for ctrl-
			// (no browser is known using such a short prefix, though) or "Alt+Umschalt+" in German Firefox.
			result = /^Title \[(.+)[aA]\]$/.exec( title );
		assert.notStrictEqual( result, null, 'title should match expected structure.' );
		assert.notStrictEqual( result[ 1 ], 'test-', 'Prefix used for testing shouldn\'t be used in production.' );
	} );

	QUnit.test( 'updateTooltipAccessKeys - no access key', ( assert ) => {
		let i, oldTitle, $input, newTitle;
		for ( i = 0; i < updateTooltipAccessKeysTestData.length; i++ ) {
			oldTitle = 'Title' + updateTooltipAccessKeysTestData[ i ];
			$input = $( makeInput( oldTitle ) );
			$( '#qunit-fixture' ).append( $input );
			newTitle = $input.updateTooltipAccessKeys().prop( 'title' );
			assert.strictEqual( newTitle, 'Title', 'title="' + oldTitle + '"' );
		}
	} );

	QUnit.test( 'updateTooltipAccessKeys - with access key', ( assert ) => {
		let i, oldTitle, $input, newTitle;
		$.fn.updateTooltipAccessKeys.setTestMode( true );
		for ( i = 0; i < updateTooltipAccessKeysTestData.length; i++ ) {
			oldTitle = 'Title' + updateTooltipAccessKeysTestData[ i ];
			$input = $( makeInput( oldTitle, 'a' ) );
			$( '#qunit-fixture' ).append( $input );
			newTitle = $input.updateTooltipAccessKeys().prop( 'title' );
			assert.strictEqual( newTitle, 'Title [test-a]', 'title="' + oldTitle + '"' );
		}
		$.fn.updateTooltipAccessKeys.setTestMode( false );
	} );

	QUnit.test( 'updateTooltipAccessKeys with label element', ( assert ) => {
		$.fn.updateTooltipAccessKeys.setTestMode( true );
		const html = '<label for="testInput" title="Title">Label</label><input id="testInput" accessKey="a" />';
		$( '#qunit-fixture' ).html( html );
		const $label = $( '#qunit-fixture label' );
		const $input = $( '#qunit-fixture input' );
		$input.updateTooltipAccessKeys();
		assert.strictEqual( $input.prop( 'title' ), '', 'No title attribute added to input element.' );
		assert.strictEqual( $label.prop( 'title' ), 'Title [test-a]', 'title updated for associated label element.' );
		$.fn.updateTooltipAccessKeys.setTestMode( false );
	} );

	QUnit.test( 'updateTooltipAccessKeys with label element as parent', ( assert ) => {
		$.fn.updateTooltipAccessKeys.setTestMode( true );
		const html = '<label title="Title">Label<input id="testInput" accessKey="a" /></label>';
		$( '#qunit-fixture' ).html( html );
		const $label = $( '#qunit-fixture label' );
		const $input = $( '#qunit-fixture input' );
		$input.updateTooltipAccessKeys();
		assert.strictEqual( $input.prop( 'title' ), '', 'No title attribute added to input element.' );
		assert.strictEqual( $label.prop( 'title' ), 'Title [test-a]', 'title updated for associated label element.' );
		$.fn.updateTooltipAccessKeys.setTestMode( false );
	} );

}() );
