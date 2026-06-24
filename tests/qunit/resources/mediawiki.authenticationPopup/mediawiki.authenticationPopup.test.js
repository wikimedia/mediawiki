'use strict';

QUnit.module( 'mediawiki.authenticationPopup', ( hooks ) => {
	let authPopup;

	// test.MediaWiki is a `scripts` module, so require() isn't available at file
	// scope. Resolve the module's export once via mw.loader.using()'s require.
	hooks.before( () => mw.loader.using( 'mediawiki.authenticationPopup' )
		.then( ( require ) => {
			authPopup = require( 'mediawiki.authenticationPopup' );
		} )
	);

	hooks.beforeEach( function () {
		this.server = this.sandbox.useFakeServer();
		this.server.respondImmediately = true;
	} );

	/**
	 * Queue a single canned api.php JSON response on the fake server.
	 *
	 * @param {Object} query Value for the `query` field of the response
	 */
	function respondWith( query ) {
		// `this` is bound by the caller (regular functions) to the test context.
		this.server.respond( [
			200,
			{ 'Content-Type': 'application/json' },
			JSON.stringify( { query: query } )
		] );
	}

	QUnit.test( 'module exports an AuthPopup with a forReauthentication factory', ( assert ) => {
		assert.strictEqual( typeof authPopup.startPopupWindow, 'function',
			'exposes the AuthPopup login instance' );
		assert.strictEqual( typeof authPopup.forReauthentication, 'function',
			'exposes the forReauthentication factory' );
	} );

	QUnit.module( 'login instance', () => {
		QUnit.test( 'URLs are popup-mode login without force=', ( assert ) => {
			assert.true( authPopup.loginPopupUrl.includes( 'display=popup' ),
				'popup URL is in popup display mode' );
			assert.false( authPopup.loginPopupUrl.includes( 'force=' ),
				'popup URL has no force= operation' );
			assert.false( authPopup.loginFallbackUrl.includes( 'display=popup' ),
				'fallback URL is the full-page form' );
		} );

		QUnit.test( 'checkLoggedIn resolves with userinfo for a real user', function ( assert ) {
			respondWith.call( this, { userinfo: { id: 7, name: 'Alice' } } );
			return authPopup.checkLoggedIn().then( ( result ) => {
				assert.strictEqual( this.server.requests.length, 1, 'one API request' );
				assert.true( /meta=userinfo/.test( this.server.requests[ 0 ].url ),
					'queries meta=userinfo' );
				assert.propEqual( result, { id: 7, name: 'Alice' },
					'resolves with the userinfo object' );
			} );
		} );

		QUnit.test( 'checkLoggedIn resolves null for an anonymous user', function ( assert ) {
			respondWith.call( this, { userinfo: { id: 0, name: '1.2.3.4', anon: '' } } );
			return authPopup.checkLoggedIn().then( ( result ) => {
				assert.strictEqual( result, null, 'anon is treated as not logged in' );
			} );
		} );

		QUnit.test( 'checkLoggedIn resolves null for a temporary user', function ( assert ) {
			respondWith.call( this, { userinfo: { id: 9, name: '~2024-1', temp: '' } } );
			return authPopup.checkLoggedIn().then( ( result ) => {
				assert.strictEqual( result, null, 'temp account is treated as not logged in' );
			} );
		} );
	} );

	QUnit.module( 'forReauthentication()', () => {
		QUnit.test( 'URLs carry force= and the correct display mode', ( assert ) => {
			const instance = authPopup.forReauthentication( 'edit' );
			assert.true( instance.loginPopupUrl.includes( 'force=edit' ),
				'popup URL carries the force= operation' );
			assert.true( instance.loginPopupUrl.includes( 'display=popup' ),
				'popup URL stays in popup display mode' );
			assert.true( instance.loginFallbackUrl.includes( 'force=edit' ),
				'fallback URL also carries force=' );
			assert.false( instance.loginFallbackUrl.includes( 'display=popup' ),
				'fallback URL is the full-page form' );
		} );

		QUnit.test( 'returns a distinct instance from the login popup', ( assert ) => {
			assert.notStrictEqual( authPopup.forReauthentication( 'edit' ), authPopup,
				'factory produces a separate AuthPopup' );
		} );

		// The success check (wired in as the instance's checkLoggedIn) must resolve
		// truthy only for SEC_OK ('ok'); SEC_REAUTH and SEC_FAIL are "not done".
		const statusCases = {
			'SEC_OK counts as reauthenticated': [ 'ok', true ],
			'SEC_REAUTH does not': [ 'reauth', false ],
			'SEC_FAIL does not': [ 'fail', false ]
		};
		QUnit.test.each( 'success check maps status to a boolean', statusCases,
			function ( assert, [ status, expected ] ) {
				respondWith.call( this, {
					authmanagerinfo: { securitysensitiveoperationstatus: status }
				} );
				return authPopup.forReauthentication( 'edit' ).checkLoggedIn()
					.then( ( result ) => {
						assert.strictEqual( result, expected, 'only SEC_OK is truthy' );
					} );
			}
		);

		QUnit.test( 'success check queries authmanagerinfo for the operation', function ( assert ) {
			respondWith.call( this, {
				authmanagerinfo: { securitysensitiveoperationstatus: 'ok' }
			} );
			return authPopup.forReauthentication( 'move' ).checkLoggedIn().then( () => {
				const url = this.server.requests[ 0 ].url;
				assert.true( /meta=authmanagerinfo/.test( url ),
					'queries meta=authmanagerinfo' );
				assert.true( /amisecuritysensitiveoperation=move/.test( url ),
					'passes the operation as amisecuritysensitiveoperation' );
			} );
		} );
	} );
} );
