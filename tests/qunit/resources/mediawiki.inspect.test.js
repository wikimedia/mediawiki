QUnit.module( 'mediawiki.inspect', () => {

	QUnit.test( '.getModuleSize() - scripts', ( assert ) => {
		// Use eval so that the size doesn't change depending on minification
		/* eslint-disable no-eval */
		mw.loader.impl( eval( "(function(){return['test.inspect.script',function (){'example';}];})" ) );

		return mw.loader.using( 'test.inspect.script' ).then( () => {
			assert.strictEqual(
				mw.inspect.getModuleSize( 'test.inspect.script' ),
				66,
				'test.inspect.script'
			);
		} );
	} );
} );
