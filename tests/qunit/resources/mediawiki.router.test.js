QUnit.module( 'mediawiki.router', () => {
	const router = require( 'mediawiki.router' );

	QUnit.test( 'instance', ( assert ) => {
		assert.true( router instanceof OO.Router );
	} );
} );
