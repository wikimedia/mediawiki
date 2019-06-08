const assert = require( 'assert' ),
	HistoryPage = require( '../pageobjects/history.page' ),
	UserLoginPage = require( 'wdio-mediawiki/LoginPage' ),
	Util = require( 'wdio-mediawiki/Util' );

describe( 'Rollback with confirmation', function () {
	var content,
		name;

	before( function () {
		// disable VisualEditor welcome dialog
		browser.deleteCookie();
		UserLoginPage.open();
		browser.localStorage( 'POST', { key: 've-beta-welcome-dialog', value: '1' } );

		// Enable rollback confirmation for admin user
		// Requires user to log in again, handled by deleteCookie() call in beforeEach function
		UserLoginPage.loginAdmin();

		browser.pause( 300 );
		browser.execute( function () {
			return ( new mw.Api() ).saveOption(
				'showrollbackconfirmation',
				'1'
			);
		} );
	} );

	beforeEach( function () {
		browser.deleteCookie();

		content = Util.getTestString( 'beforeEach-content-' );
		name = Util.getTestString( 'BeforeEach-name-' );

		HistoryPage.vandalizePage( name, content );

		UserLoginPage.loginAdmin();
		HistoryPage.open( name );
	} );

	it( 'should offer rollback options for admin users', function () {
		assert.strictEqual( HistoryPage.rollback.getText(), 'rollback 1 edit' );

		HistoryPage.rollback.click();

		assert.strictEqual( HistoryPage.rollbackConfirmable.getText(), 'Please confirm:' );
		assert.strictEqual( HistoryPage.rollbackConfirmableYes.getText(), 'Rollback' );
		assert.strictEqual( HistoryPage.rollbackConfirmableNo.getText(), 'Cancel' );
	} );

	it.skip( 'should offer a way to cancel rollbacks', function () {
		HistoryPage.rollback.click();

		browser.pause( 300 );

		HistoryPage.rollbackConfirmableNo.click();

		browser.pause( 500 );

		assert.strictEqual( HistoryPage.heading.getText(), 'Revision history of "' + name + '"' );
	} );

	it.skip( 'should perform rollbacks after confirming intention', function () {
		HistoryPage.rollback.click();

		browser.pause( 300 );

		HistoryPage.rollbackConfirmableYes.click();

		// waitUntil indirectly asserts that the content we are looking for is present
		browser.waitUntil( function () {
			return browser.getText( '#firstHeading' ) === 'Action complete';
		}, 5000, 'Expected rollback page to appear.' );
	} );

	it( 'should verify rollbacks via GET requests are confirmed on a follow-up page', function () {
		var rollbackActionUrl = HistoryPage.rollbackLink.getAttribute( 'href' );
		browser.url( rollbackActionUrl );

		browser.waitUntil( function () {
			return HistoryPage.rollbackNonJsConfirmable.getText() === 'Revert edits to this page?';
		}, 5000, 'Expected rollback confirmation page to appear for GET-based rollbacks.' );

		HistoryPage.rollbackNonJsConfirmableYes.click();

		browser.waitUntil( function () {
			return browser.getText( '#firstHeading' ) === 'Action complete';
		}, 5000, 'Expected rollback page to appear.' );
	} );

} );

describe( 'Rollback without confirmation', function () {
	var content,
		name;

	before( function () {
		// disable VisualEditor welcome dialog
		browser.deleteCookie();
		UserLoginPage.open();
		browser.localStorage( 'POST', { key: 've-beta-welcome-dialog', value: '1' } );

		// Disable rollback confirmation for admin user
		// Requires user to log in again, handled by deleteCookie() call in beforeEach function
		UserLoginPage.loginAdmin();

		browser.pause( 300 );
		browser.execute( function () {
			return ( new mw.Api() ).saveOption(
				'showrollbackconfirmation',
				'0'
			);
		} );
	} );

	beforeEach( function () {
		browser.deleteCookie();

		content = Util.getTestString( 'beforeEach-content-' );
		name = Util.getTestString( 'BeforeEach-name-' );

		HistoryPage.vandalizePage( name, content );

		UserLoginPage.loginAdmin();
		HistoryPage.open( name );
	} );

	it( 'should perform rollback via POST request without asking the user to confirm', function () {
		HistoryPage.rollback.click();

		// waitUntil indirectly asserts that the content we are looking for is present
		browser.waitUntil( function () {
			return HistoryPage.headingText === 'Action complete';
		}, 5000, 'Expected rollback page to appear.' );
	} );

	it( 'should perform rollback via GET request without asking the user to confirm', function () {
		var rollbackActionUrl = HistoryPage.rollbackLink.getAttribute( 'href' );
		browser.url( rollbackActionUrl );

		browser.waitUntil( function () {
			return browser.getText( '#firstHeading' ) === 'Action complete';
		}, 5000, 'Expected rollback page to appear.' );
	} );
} );
