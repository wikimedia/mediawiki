QUnit.module( 'mediawiki.inspect', function () {

	// These test cases use eval() to define their fixture so that their code
	// is transferred as a string instead of native JS, and thus not subject
	// to minification. This makes their inspected size constant, regardless of:
	// * whether debug=1 or debug=2 is used (or not at all)
	// * whether the code happpens to be near the end of a 1000-char chunk
	//   in the minifier and thus get an extra line break byte in the middle.
	// * future changes to the minifier that might slightly increase or decrease
	//   the minified size.
	//
	// Bypassing this does not deminish the value of the test as we want to test
	// how a given output is measured by mw.inspect. ResourceLoader has its own
	// integration in PHPUnit for how a function is bundled and minified.

	/* eslint-disable no-eval */

	var exampleFn = eval( "( function () { 'example'; }) " );

	QUnit.test( '.getModuleSize() - scripts', function ( assert ) {
		mw.loader.implement(
			'test.inspect.script',
			exampleFn
		);

		return mw.loader.using( 'test.inspect.script' ).then( function () {
			assert.strictEqual(
				mw.inspect.getModuleSize( 'test.inspect.script' ),
				47,
				'test.inspect.script'
			);
		} );
	} );

	QUnit.test( '.getModuleSize() - scripts, styles', function ( assert ) {
		mw.loader.implement(
			'test.inspect.both',
			exampleFn,
			{ css: [ '.example {}' ] }
		);

		return mw.loader.using( 'test.inspect.both' ).then( function () {
			assert.strictEqual(
				mw.inspect.getModuleSize( 'test.inspect.both' ),
				68,
				'test.inspect.both'
			);
		} );
	} );

	QUnit.test( '.getModuleSize() - packageFiles, styles', function ( assert ) {
		mw.loader.implement(
			'test.inspect.packageFiles',
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
			exampleFn,
			{},
			{ example: 'Hello world.' }
		);

		return mw.loader.using( 'test.inspect.scriptmsg' ).then( function () {
			assert.strictEqual(
				mw.inspect.getModuleSize( 'test.inspect.scriptmsg' ),
				78,
				'test.inspect.scriptmsg'
			);
		} );
	} );

	QUnit.test( '.getModuleSize() - scripts, styles, messages, templates', function ( assert ) {
		mw.loader.implement(
			'test.inspect.all',
			exampleFn,
			{ css: [ '.example {}' ] },
			{ example: 'Hello world.' },
			{ 'example.html': '<p>Hello world.<p>' }
		);

		return mw.loader.using( 'test.inspect.all' ).then( function () {
			assert.strictEqual(
				mw.inspect.getModuleSize( 'test.inspect.all' ),
				130,
				'test.inspect.all'
			);
		} );
	} );
} );
