module( 'jquery.localize.js' );

test( '-- Initial check', function() {
	expect(1);
	ok( $.fn.localize, 'jQuery.fn.localize defined' );
} );

test( 'Handle basic replacements', function() {
	expect(4);

	var html, $lc, expected;
	mw.messages.set( 'basic', 'Basic stuff' );

	// Tag: html:msg
	html = '<div><span class="foobar"><html:msg key="basic"></span></div>';
	$lc = $( html ).localize();
	expected = '<span class="foobar">Basic stuff</span>';

	strictEqual( $lc.html(), expected, 'Tag: html:msg' );

	// Tag: msg (deprecated)
	html = '<div><span class="foobar"><msg key="basic"></span></div>';
	$lc = $( html ).localize();
	expected = '<span class="foobar">Basic stuff</span>';

	strictEqual( $lc.html(), expected, 'Tag: msg' );

	// Attribute: title-msg
	html = '<div><span class="foobar" title-msg="basic"></span></div>';
	$lc = $( html ).localize();
	expected = '<span class="foobar" title="Basic stuff"></span>';

	strictEqual( $lc.html(), expected, 'Attribute: title-msg' );

	// Attribute: alt-msg
	html = '<div><span class="foobar" alt-msg="basic"></span></div>';
	$lc = $( html ).localize();
	expected = '<span class="foobar" alt="Basic stuff"></span>';

	strictEqual( $lc.html(), expected, 'Attribute: alt-msg' );
} );

test( 'Proper escaping', function() {
	expect(2);

	var html, $lc, expected;
	mw.messages.set( 'properfoo', '<proper esc="test">' );

	// This is handled by jQuery inside $.fn.localize, just a simple sanity checked
	// making sure it is actually using text() and attr() (or something with the same effect)

	// Text escaping
	html = '<div><span class="foobar"><html:msg key="properfoo"></span></div>';
	$lc = $( html ).localize();
	expected = '<span class="foobar">&lt;proper esc="test"&gt;</span>';

	strictEqual( $lc.html(), expected, 'Content is inserted as text, not as html.' );

	// Attribute escaping
	html = '<div><span class="foobar" title-msg="properbar"></span></div>';
	$lc = $( html ).localize();
	expected = '<span class="foobar" title="&lt;properbar&gt;"></span>';

	strictEqual( $lc.html(), expected, 'Attributes are not inserted raw.' );


} );

test( 'Options', function() {
	expect(4);

	mw.messages.set( {
		'foo-lorem': 'Lorem',
		'foo-ipsum': 'Ipsum',
		'foo-bar-title': 'Read more about bars',
		'foo-bar-label': 'The Bars',
		'foo-bazz-title': 'Read more about bazz at $1 (last modified: $2)',
		'foo-bazz-label': 'The Bazz ($1)',
		'foo-welcome': 'Welcome to $1! (last visit: $2)'
	} );
	var html, $lc, expected, x, sitename = 'Wikipedia';

	// Message key prefix
	html = '<div><span title-msg="lorem"><html:msg key="ipsum" /></span></div>';
	$lc = $( html ).localize( {
		prefix: 'foo-'
	} );
	expected = '<span title="Lorem">Ipsum</span>';

	strictEqual( $lc.html(), expected, 'Message key prefix' );

	// Variable keys mapping
	x = 'bar';
	html = '<div><span title-msg="title"><html:msg key="label" /></span></div>';
	$lc = $( html ).localize( {
		keys: {
			'title': 'foo-' + x + '-title',
			'label': 'foo-' + x + '-label'
		}
	} );
	expected = '<span title="Read more about bars">The Bars</span>';

	strictEqual( $lc.html(), expected, 'Message prefix' );

	// Passing parameteters to mw.msg
	html = '<div><span><html:msg key="foo-welcome" /></span></div>';
	$lc = $( html ).localize( {
		params: {
			'foo-welcome': [sitename, 'yesterday']
		}
	} );
	expected = '<span>Welcome to Wikipedia! (last visit: yesterday)</span>';

	strictEqual( $lc.html(), expected, 'Message prefix' );

	// Combination of options prefix, params and keys
	x = 'bazz';
	html = '<div><span title-msg="title"><html:msg key="label" /></span></div>';
	$lc = $( html ).localize( {
		prefix: 'foo-',
		keys: {
			'title': x + '-title',
			'label': x + '-label'
		},
		params: {
			'title': [sitename, '3 minutes ago'],
			'label': [sitename, '3 minutes ago']

		}
	} );
	expected = '<span title="Read more about bazz at Wikipedia (last modified: 3 minutes ago)">The Bazz (Wikipedia)</span>';

	strictEqual( $lc.html(), expected, 'Message prefix' );
} );
