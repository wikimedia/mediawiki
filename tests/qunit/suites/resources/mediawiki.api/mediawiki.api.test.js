QUnit.module( 'mediawiki.api', QUnit.newMwEnvironment() );

QUnit.asyncTest( 'Basic functionality', function ( assert ) {
	var api, d1, d2, d3;
	QUnit.expect( 3 );

	api = new mw.Api();

	d1 = api.get( {} )
		.done( function ( data ) {
			assert.deepEqual( data, [], 'If request succeeds without errors, resolve deferred' );
		});

	d2 = api.get({
			action: 'doesntexist'
		})
		.fail( function ( errorCode, details ) {
			assert.equal( errorCode, 'unknown_action', 'API error (e.g. "unknown_action") should reject the deferred' );
		});

	d3 = api.post( {} )
		.done( function ( data ) {
			assert.deepEqual( data, [], 'Simple POST request' );
		});

	// After all are completed, continue the test suite.
	QUnit.whenPromisesComplete( d1, d2, d3 ).always( function () {
		QUnit.start();
	});
});

QUnit.asyncTest( 'Deprecated callback methods', function ( assert ) {
	var api, d1, d2, d3;
	QUnit.expect( 3 );

	api = new mw.Api();

	d1 = api.get( {}, function () {
		assert.ok( true, 'Function argument treated as success callback.' );
	});

	d2 = api.get( {}, {
		ok: function ( data ) {
			assert.ok( true, '"ok" property treated as success callback.' );
		}
	});

	d3 = api.get({
			action: 'doesntexist'
		}, {
		err: function ( data ) {
			assert.ok( true, '"err" property treated as error callback.' );
		}
	});

	QUnit.whenPromisesComplete( d1, d2, d3 ).always( function () {
		QUnit.start();
	});
});
