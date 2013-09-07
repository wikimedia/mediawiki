( function ( mw, $ ) {
	QUnit.module( 'mediawiki.api', QUnit.newMwEnvironment() );

	QUnit.asyncTest( 'Basic functionality', function ( assert ) {
		var api, d1, d2, d3;
		QUnit.expect( 3 );

		api = new mw.Api();

		d1 = api.get( {} )
			.done( function ( data ) {
				assert.deepEqual( data, [], 'If request succeeds without errors, resolve deferred' );
			} );

		d2 = api.get( {
			action: 'doesntexist'
		} )
			.fail( function ( errorCode ) {
				assert.equal( errorCode, 'unknown_action', 'API error (e.g. "unknown_action") should reject the deferred' );
			} );

		d3 = api.post( {} )
			.done( function ( data ) {
				assert.deepEqual( data, [], 'Simple POST request' );
			} );

		// After all are completed, continue the test suite.
		QUnit.whenPromisesComplete( d1, d2, d3 ).always( function () {
			QUnit.start();
		} );
	} );

	QUnit.asyncTest( 'Deprecated callback methods', function ( assert ) {
		var api, d1, d2, d3;
		QUnit.expect( 3 );

		api = new mw.Api();

		d1 = api.get( {}, function () {
			assert.ok( true, 'Function argument treated as success callback.' );
		} );

		d2 = api.get( {}, {
			ok: function () {
				assert.ok( true, '"ok" property treated as success callback.' );
			}
		} );

		d3 = api.get( {
			action: 'doesntexist'
		}, {
			err: function () {
				assert.ok( true, '"err" property treated as error callback.' );
			}
		} );

		QUnit.whenPromisesComplete( d1, d2, d3 ).always( function () {
			QUnit.start();
		} );
	} );

	QUnit.asyncTest( 'postWithToken', 3, function ( assert ) {
		var api = new mw.Api();

		// This mocks an edit adding a section.
		function testDone() {
			var mockIndex;

			QUnit.stop();
			// Return a valid response as if it were a successful edit
			mockIndex = $.mockjax( {
				url: api.defaults.ajax.url,
				contentType: 'application/json',
				response: function ( settings ) {
					var hasToken = /token=[^&]+/.test( settings.data );
					assert.strictEqual( hasToken, true, 'token is passed to mockjax response function' );

					if ( hasToken ) {
						this.responseText = {
							edit: {
								result: 'Success',
								pageid: 123,
								title: 'Lorem ipsum',
								contentmodel: 'wikitext',
								oldrevid: '456',
								newrevid: '567',
								newtimestamp: '2013-11-22T03:15:04Z'
							}
						};
					} else {
						this.responseText = {
							error: {
								code: 'notoken',
								info: 'The token parameter must be set'
							}
						};
					}
				}
			} );

			// Mocked request
			api.postWithToken( 'edit', {
				action: 'edit',
				title: 'Lorem ipsum',
				section: 'new',
				sectiontitle: 'New section',
				text: 'Section text'
			} ).done( function ( response ) {
				assert.strictEqual( response.edit.result, 'Success', 'done called for succesful mock edit with token previously in cache' );
			} ).always( function () {
				$.mockjaxClear( mockIndex );
				QUnit.start();
			} );
		}

		// Tests that fail is called when the token is fetched/available, but the main
		// POST fails.  It also means the token will be cached when testDone runs.
		api.postWithToken( 'edit', {
			action: 'edit',
			title: 'Lorem ipsum',
			summary: 'Make a change'
		} ).fail( function ( code ) {
			QUnit.start();
			assert.strictEqual( code, 'notext', 'Calls fail (with correct code) when token is fetched, but main request fails' );
			testDone();
		} );
	} );
}( mediaWiki, jQuery ) );
