( function () {
	QUnit.module( 'jquery.localize', QUnit.newMwEnvironment() );

	QUnit.test( 'Handle basic replacements', function ( assert ) {
		var html, $lc;
		mw.messages.set( 'basic', 'Basic stuff' );

		// Tag: html:msg
		html = '<div><span><html:msg key="basic" /></span></div>';
		$lc = $( html ).localize().find( 'span' );

		assert.strictEqual( $lc.text(), 'Basic stuff', 'Tag: html:msg' );

		// Attribute: title-msg
		html = '<div><span title-msg="basic"></span></div>';
		$lc = $( html ).localize().find( 'span' );

		assert.strictEqual( $lc.attr( 'title' ), 'Basic stuff', 'Attribute: title-msg' );

		// Attribute: alt-msg
		html = '<div><span alt-msg="basic"></span></div>';
		$lc = $( html ).localize().find( 'span' );

		assert.strictEqual( $lc.attr( 'alt' ), 'Basic stuff', 'Attribute: alt-msg' );

		// Attribute: placeholder-msg
		html = '<div><input placeholder-msg="basic" /></div>';
		$lc = $( html ).localize().find( 'input' );

		assert.strictEqual( $lc.attr( 'placeholder' ), 'Basic stuff', 'Attribute: placeholder-msg' );
	} );

	QUnit.test( 'Proper escaping', function ( assert ) {
		var html, $lc;
		mw.messages.set( 'properfoo', '<proper esc="test">' );

		// This is handled by jQuery inside $.fn.localize, just a simple sanity checked
		// making sure it is actually using text() and attr() (or something with the same effect)

		// Text escaping
		html = '<div><span><html:msg key="properfoo" /></span></div>';
		$lc = $( html ).localize().find( 'span' );

		assert.strictEqual( $lc.text(), mw.msg( 'properfoo' ), 'Content is inserted as text, not as html.' );

		// Attribute escaping
		html = '<div><span title-msg="properfoo"></span></div>';
		$lc = $( html ).localize().find( 'span' );

		assert.strictEqual( $lc.attr( 'title' ), mw.msg( 'properfoo' ), 'Attributes are not inserted raw.' );
	} );

	QUnit.test( 'Options', function ( assert ) {
		var html, $lc, x, sitename = 'Wikipedia';
		mw.messages.set( {
			'foo-lorem': 'Lorem',
			'foo-ipsum': 'Ipsum',
			'foo-bar-title': 'Read more about bars',
			'foo-bar-label': 'The Bars',
			'foo-bazz-title': 'Read more about bazz at $1 (last modified: $2)',
			'foo-bazz-label': 'The Bazz ($1)',
			'foo-welcome': 'Welcome to $1! (last visit: $2)'
		} );

		// Message key prefix
		html = '<div><span title-msg="lorem"><html:msg key="ipsum" /></span></div>';
		$lc = $( html ).localize( {
			prefix: 'foo-'
		} ).find( 'span' );

		assert.strictEqual( $lc.attr( 'title' ), 'Lorem', 'Message key prefix - attr' );
		assert.strictEqual( $lc.text(), 'Ipsum', 'Message key prefix - text' );

		// Variable keys mapping
		x = 'bar';
		html = '<div><span title-msg="title"><html:msg key="label" /></span></div>';
		$lc = $( html ).localize( {
			keys: {
				title: 'foo-' + x + '-title',
				label: 'foo-' + x + '-label'
			}
		} ).find( 'span' );

		assert.strictEqual( $lc.attr( 'title' ), 'Read more about bars', 'Variable keys mapping - attr' );
		assert.strictEqual( $lc.text(), 'The Bars', 'Variable keys mapping - text' );

		// Passing parameteters to mw.msg
		html = '<div><span><html:msg key="foo-welcome" /></span></div>';
		$lc = $( html ).localize( {
			params: {
				'foo-welcome': [ sitename, 'yesterday' ]
			}
		} ).find( 'span' );

		assert.strictEqual( $lc.text(), 'Welcome to Wikipedia! (last visit: yesterday)', 'Passing parameteters to mw.msg' );

		// Combination of options prefix, params and keys
		x = 'bazz';
		html = '<div><span title-msg="title"><html:msg key="label" /></span></div>';
		$lc = $( html ).localize( {
			prefix: 'foo-',
			keys: {
				title: x + '-title',
				label: x + '-label'
			},
			params: {
				title: [ sitename, '3 minutes ago' ],
				label: [ sitename, '3 minutes ago' ]

			}
		} ).find( 'span' );

		assert.strictEqual( $lc.text(), 'The Bazz (Wikipedia)', 'Combination of options prefix, params and keys - text' );
		assert.strictEqual( $lc.attr( 'title' ), 'Read more about bazz at Wikipedia (last modified: 3 minutes ago)', 'Combination of options prefix, params and keys - attr' );
	} );

	QUnit.test( 'Handle data text', function ( assert ) {
		var html, $lc;
		mw.messages.set( 'option-one', 'Item 1' );
		mw.messages.set( 'option-two', 'Item 2' );
		html = '<select><option data-msg-text="option-one"></option><option data-msg-text="option-two"></option></select>';
		$lc = $( html ).localize().find( 'option' );
		assert.strictEqual( $lc.eq( 0 ).text(), mw.msg( 'option-one' ), 'data-msg-text becomes text of options' );
		assert.strictEqual( $lc.eq( 1 ).text(), mw.msg( 'option-two' ), 'data-msg-text becomes text of options' );
	} );

	QUnit.test( 'Handle data html', function ( assert ) {
		var html, $lc;
		mw.messages.set( 'html', 'behold... there is a <a>link</a> here!!' );
		html = '<div><div data-msg-html="html"></div></div>';
		$lc = $( html ).localize().find( 'a' );
		assert.strictEqual( $lc.length, 1, 'link is created' );
		assert.strictEqual( $lc.text(), 'link', 'the link text got added' );
	} );
}() );
