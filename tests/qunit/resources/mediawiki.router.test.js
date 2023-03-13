QUnit.module( 'mediawiki.router', function () {
	var router = require( 'mediawiki.router' );

	QUnit.test( 'instance', function ( assert ) {
		assert.true( router instanceof OO.Router );
	} );
} );
