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

	QUnit.test( '.getModuleSize() - packageFiles, styles', function ( assert ) {
		mw.loader.implement(
			'test.inspect.packageFiles',
			// HACK: Define this with eval as otherwise the file closures below would get
			// minified. That is undesirable for two reasons. 1) It means the perceived
			// size of the functions via Function#toString will vary between debug mode
			// and production mode, making the test fail under one of them, and 2) when
			// something is minified, there are occasionally line breaks inserted after
			// every few thousand bytes, which means the test result changes over time
			// based on the code above.
			// eslint-disable-next-line no-eval
			eval( "({\
	main: 'init.js',\
	files: {\
		'data.json': { hello: 'world' },\
		'alice.js': function ( require, module ) {\
			var core = require( './core.js' );\
			module.exports = core.sayHello( 'Alice' );\
		},\
		'core.js': function ( require, module ) {\
			module.exports = {\
				sayHello: function ( name ) {\
					return 'Hello ' + name;\
				}\
			};\
		},\
		'init.js': function ( require ) {\
			mw.alice = require( './alice.js' );\
		}\
	}\
})" ),
			{ css: [ '.example {}' ] }
		);

		return mw.loader.using( 'test.inspect.packageFiles' ).then( function () {
			assert.strictEqual(
				mw.inspect.getModuleSize( 'test.inspect.packageFiles' ),
				441
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
