QUnit.module( 'mediawiki.router', () => {
	var router = require( 'mediawiki.router' );

	QUnit.test( 'instance', ( assert ) => {
		assert.true( router instanceof OO.Router );
	} );
} );
