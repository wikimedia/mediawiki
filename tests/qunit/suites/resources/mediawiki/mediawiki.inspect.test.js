( function () {

	QUnit.module( 'mediawiki.inspect' );

	QUnit.test( '.getModuleSize() - scripts', function ( assert ) {
		mw.loader.implement(
			'test.inspect.script',
			function () { 'example'; }
		);

		return mw.loader.using( 'test.inspect.script' ).then( function () {
			assert.strictEqual(
				mw.inspect.getModuleSize( 'test.inspect.script' ),
				// name, script function
				43,
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
			assert.strictEqual(
				mw.inspect.getModuleSize( 'test.inspect.both' ),
				// name, script function, styles object
				64,
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
			assert.strictEqual(
				mw.inspect.getModuleSize( 'test.inspect.scriptmsg' ),
				// name, script function, empty styles object, messages object
				74,
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
			assert.strictEqual(
				mw.inspect.getModuleSize( 'test.inspect.all' ),
				// name, script function, styles object, messages object, templates object
				126,
				'test.inspect.all'
			);
		} );
	} );
}() );
