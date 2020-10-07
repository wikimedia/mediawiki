'use strict';

const assert = require( 'assert' );
const HistoryPage = require( '../pageobjects/history.page' );
const Api = require( 'wdio-mediawiki/Api' );
const UserLoginPage = require( 'wdio-mediawiki/LoginPage' );
const Util = require( 'wdio-mediawiki/Util' );

function vandalizePage( name, content ) {
	const vandalUsername = 'Evil_' + browser.config.mwUser;
	browser.call( async () => {
		// Note: Repeated logins are inefficient/slow, but we cannot re-use
		// the object across calls because MWBot can only be logged into one
		// account at a time. See <https://github.com/Fannon/mwbot/issues/9>.
		const adminBot = await Api.bot();
		await adminBot.edit( name, content );

		await Api.createAccount( adminBot, vandalUsername, browser.config.mwPwd );
		const vandalBot = await Api.bot( vandalUsername, browser.config.mwPwd );
		await vandalBot.edit( name, 'Vandalized: ' + content );
	} );
}

describe( 'Rollback with confirmation', function () {
	let content, name;

	before( function () {
		browser.deleteAllCookies();

		// Enable rollback confirmation for admin user
		// Requires user to log in again, handled by deleteCookie() call in beforeEach function
		UserLoginPage.loginAdmin();
		HistoryPage.toggleRollbackConfirmationSetting( true );
	} );

	beforeEach( function () {
		browser.deleteAllCookies();

		content = Util.getTestString( 'beforeEach-content-' );
		name = Util.getTestString( 'BeforeEach-name-' );

		vandalizePage( name, content );

		UserLoginPage.loginAdmin();
		HistoryPage.open( name );
	} );

	it.skip( 'should offer rollback options for admin users', function () {
		assert.strictEqual( HistoryPage.rollback.getText(), 'rollback 1 edit' );

		HistoryPage.rollbackLink.click();

		assert.strictEqual( HistoryPage.rollbackConfirmable.getText(), 'Please confirm:' );
		assert.strictEqual( HistoryPage.rollbackConfirmableYes.getText(), 'Rollback' );
		assert.strictEqual( HistoryPage.rollbackConfirmableNo.getText(), 'Cancel' );
	} );

	it.skip( 'should offer a way to cancel rollbacks', function () {
		HistoryPage.rollback.click();

		HistoryPage.rollbackConfirmableNo.waitForDisplayed( 5000 );

		HistoryPage.rollbackConfirmableNo.click();

		browser.pause( 1000 ); // Waiting to ensure we are NOT redirected and stay on the same page

		assert.strictEqual( HistoryPage.heading.getText(), 'Revision history of "' + name + '"' );
	} );

	it.skip( 'should perform rollbacks after confirming intention', function () {
		HistoryPage.rollback.click();

		HistoryPage.rollbackConfirmableYes.waitForDisplayed( 5000 );

		HistoryPage.rollbackConfirmableYes.click();

		// waitUntil indirectly asserts that the content we are looking for is present
		browser.waitUntil( function () {
			return browser.getText( '#firstHeading' ) === 'Action complete';
		}, 5000, 'Expected rollback page to appear.' );
	} );

	it.skip( 'should verify rollbacks via GET requests are confirmed on a follow-up page', function () {
		const rollbackActionUrl = HistoryPage.rollbackLink.getAttribute( 'href' );
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
	let content, name;

	before( function () {
		browser.deleteAllCookies();

		// Disable rollback confirmation for admin user
		// Requires user to log in again, handled by deleteCookie() call in beforeEach function
		UserLoginPage.loginAdmin();
		HistoryPage.toggleRollbackConfirmationSetting( false );
	} );

	beforeEach( function () {
		browser.deleteAllCookies();

		content = Util.getTestString( 'beforeEach-content-' );
		name = Util.getTestString( 'BeforeEach-name-' );

		vandalizePage( name, content );

		UserLoginPage.loginAdmin();
		HistoryPage.open( name );
	} );

	it.skip( 'should perform rollback via POST request without asking the user to confirm', function () {
		HistoryPage.rollback.click();

		// waitUntil indirectly asserts that the content we are looking for is present
		browser.waitUntil( function () {
			return HistoryPage.heading.getText() === 'Action complete';
		}, 5000, 'Expected rollback page to appear.' );
	} );

	it.skip( 'should perform rollback via GET request without asking the user to confirm', function () {
		const rollbackActionUrl = HistoryPage.rollbackLink.getAttribute( 'href' );
		browser.url( rollbackActionUrl );

		browser.waitUntil( function () {
			return browser.getText( '#firstHeading' ) === 'Action complete';
		}, 5000, 'Expected rollback page to appear.' );
	} );
} );
