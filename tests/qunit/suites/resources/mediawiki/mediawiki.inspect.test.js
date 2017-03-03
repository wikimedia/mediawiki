( function ( mw ) {
	// Whitespace and serialisation of function bodies
	// different in browsers.
	var functionSize = String( function () {} ).length;

	QUnit.module( 'mediawiki.inspect' );

	QUnit.test( '.getModuleSize() - scripts', function ( assert ) {
		mw.loader.implement(
			'test.inspect.script',
			function () { 'example'; }
		);

		return mw.loader.using( 'test.inspect.script' ).then( function () {
			assert.equal(
				mw.inspect.getModuleSize( 'test.inspect.script' ) - functionSize,
				// name, script function
				32,
				'test.inspect.script'
			);
		} );
	} );

	QUnit.test( '.getModuleSize() - scripts, styles', function ( assert ) {
		mw.loader.implement(
			'test.inspect.both',
			function () { 'example'; },
			{ css: [ '.example {}' ] }
		);

		return mw.loader.using( 'test.inspect.both' ).then( function () {
			assert.equal(
				mw.inspect.getModuleSize( 'test.inspect.both' ) - functionSize,
				// name, script function, styles object
				54,
				'test.inspect.both'
			);
		} );
	} );

	QUnit.test( '.getModuleSize() - scripts, messages', function ( assert ) {
		mw.loader.implement(
			'test.inspect.scriptmsg',
			function () { 'example'; },
			{},
			{ example: 'Hello world.' }
		);

		return mw.loader.using( 'test.inspect.scriptmsg' ).then( function () {
			assert.equal(
				mw.inspect.getModuleSize( 'test.inspect.scriptmsg' ) - functionSize,
				// name, script function, empty styles object, messages object
				65,
				'test.inspect.scriptmsg'
			);
		} );
	} );

	QUnit.test( '.getModuleSize() - scripts, styles, messages, templates', function ( assert ) {
		mw.loader.implement(
			'test.inspect.all',
			function () { 'example'; },
			{ css: [ '.example {}' ] },
			{ example: 'Hello world.' },
			{ 'example.html': '<p>Hello world.<p>' }
		);

		return mw.loader.using( 'test.inspect.all' ).then( function () {
			assert.equal(
				mw.inspect.getModuleSize( 'test.inspect.all' ) - functionSize,
				// name, script function, styles object, messages object, templates object
				118,
				'test.inspect.all'
			);
		} );
	} );
}( mediaWiki ) );
